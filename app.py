import datetime
from flask import abort
from flask import Flask, request
from flask_cors import CORS
from hololive import hololive
from flask import request

app = Flask(__name__)
CORS(app)
app.app_context().push()

from lib.db import db, live_job


@app.get("/job/<int:job_id>")
def get_specific_job(job_id):
    return db.session.query(live_job).filter(live_job.id == job_id).first().to_dict()

@app.get("/job")
def peek_job():
    # Get the first job from the database where the status is "pending"
    job = db.session.query(live_job).filter(
        live_job.status == "pending").first()
    if not job: abort(404)
    return job.to_dict()

@app.delete("/job")
def pop_job():
    job = db.session.query(live_job).filter(
        live_job.status == "pending").first()
    if not job: abort(404)
    job.status = "scheduled"
    db.session.add(job)
    db.session.commit()
    return job.to_dict()

@app.patch("/job/<int:job_id>")
def update_job_state(job_id):
    new_status = request.json["status"]
    job = db.session.query(live_job).filter(live_job.id == job_id).first()
    if not job: abort(404)
    job.status = new_status
    db.session.add(job)
    db.session.commit()
    return job.to_dict()

def stream_to_live_job(stream: hololive.Stream):
    formatted_date = (stream.start_scheduled or datetime.now()).strftime("%Y-%m-%d")
    job = live_job(
        stream.url,
        f"/shared/{stream.id}/[{formatted_date}] {stream.title}.mkv"
    )
    db.session.add(job)
    db.session.commit()

async def update_live_jobs():
    for stream in await hololive.get_streams():
        try:
            stream_to_live_job(stream)
        except Exception as e:
            print(e)


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
