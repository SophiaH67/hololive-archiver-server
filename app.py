import asyncio
import datetime
from quart import abort
from quart import Quart, request
from hololive import hololive
from quart import request
from quart import current_app as app
from sqlalchemy.inspection import inspect
from os import environ
from sqlalchemy.exc import IntegrityError
from classes.live_job import db, live_job
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
    db.session.add(job)
    db.session.commit()
    return job.to_dict()


@app.patch("/job/<int:job_id>")
async def update_job_state(job_id):
    new_status = (await request.json)["status"]
    job = db.session.query(live_job).filter(live_job.id == job_id).first()
    if not job:
        abort(404)
    job.status = new_status
    db.session.add(job)
    db.session.commit()
    return job.to_dict()


def stream_to_live_job(stream: hololive.Stream):
    formatted_date = (
        stream.start_scheduled or datetime.datetime.now()).strftime("%Y-%m-%d")
    safe_title = stream.title.replace("/", "_").replace("\\", "_")
    job = live_job(
        f"https://youtube.com/watch?v={stream.id}",
        f"/shared/{stream.id}/[{formatted_date}] {safe_title}.mkv"
    )
    db.session.add(job)
    try:
        db.session.commit()
    except IntegrityError:
        db.session.rollback()


@app.get("/update")
async def update_live_jobs():
    print("Updating live jobs")
    for stream in await hololive.get_live():
        try:
            stream_to_live_job(stream)
        except Exception as e:
            print(e)
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
