import asyncio
import datetime
from config import get_final_output_path_from_stream, holodex_searches
from quart import abort
from quart import Quart, request
from hololive import hololive
from quart import request
from quart import current_app as app
from sqlalchemy.inspection import inspect
from os import environ
from sqlalchemy.exc import IntegrityError
import psycopg2
from classes.live_job import db, live_job
import shutil
import os
from pathlib import Path

app = Quart(__name__)

app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('DATABASE_URL')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.app = app
db.init_app(app)


# Create tables if they don't exist
if not inspect(db.engine).has_table('live_jobs'):
    print("Creating tables")
    db.create_all()


@app.get("/job/<int:job_id>")
def get_specific_job(job_id):
    return db.session.query(live_job).filter(live_job.id == job_id).first().to_dict()


@app.get("/job")
def peek_job():
    # Get the first job from the database where the status is "pending"
    job = db.session.query(live_job).filter(
        live_job.status == "pending").first()
    if not job:
        abort(404)
    return job.to_dict()


@app.delete("/job")
def pop_job():
    job = db.session.query(live_job).filter(
        live_job.status == "pending").first()
    if not job:
        abort(404)
    job.status = "scheduled"
    job.ip = request.remote_addr
    db.session.add(job)
    db.session.commit()
    return job.to_dict()


def finish_job(job: live_job):
    os.makedirs(Path(job.final_location).parent, exist_ok=True)
    shutil.move(job.save_location, job.final_location)


@app.patch("/job/<int:job_id>")
async def update_job_state(job_id):
    new_job = (await request.json)
    job = db.session.query(live_job).filter(live_job.id == job_id).first()
    if not job:
        abort(404)
    if job.status == "finished":
        abort(400)
    job.status = new_job["status"]
    job.error = new_job["error"]
    job.hostname = new_job["hostname"]
    db.session.add(job)
    db.session.commit()
    if new_job["status"] == "finished":
        finish_job(job)
    return job.to_dict()


def stream_to_live_job(stream: hololive.Stream):
    formatted_date = (
        stream.start_scheduled or datetime.datetime.now()).strftime("%Y-%m-%d")
    safe_title = stream.title.replace("/", "-").replace("\\", "-")
    job = live_job(
        f"https://youtube.com/watch?v={stream.id}",
        f"/shared/{stream.id}/[{formatted_date}] {safe_title}.mkv",
        get_final_output_path_from_stream(stream),
    )
    db.session.add(job)
    try:
        db.session.commit()
    except IntegrityError as e:
        db.session.rollback()
        if not isinstance(e.orig, psycopg2.errors.UniqueViolation):
            raise e
        # Update job if it already exists
        job = db.session.query(live_job).filter(
            job.url == f"https://youtube.com/watch?v={stream.id}").first()
        if not job.automatic:
            return
        if job.status == "finished" or job.status == "error":
            return
        job.final_location = get_final_output_path_from_stream(stream)


@app.get("/update")
async def update_live_jobs():
    for holodex_search in holodex_searches:
        for topic in holodex_search.topics:
            for stream in await hololive.get_live(channel_id=holodex_search.channel_id, org=holodex_search.org, topic=topic, limit=50):
                stream_to_live_job(stream)

    return "OK"


async def schedule():
    await update_live_jobs()
    while True:
        await asyncio.sleep(300)
        await update_live_jobs()


@app.before_serving
async def startup():
    app.add_background_task(schedule)

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
