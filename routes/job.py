from quart import Blueprint
from classes.live_job import db, live_job
from quart import abort, jsonify, request
import shutil
import os
from pathlib import Path

job_api = Blueprint('job_api', __name__)


@job_api.get("/job/<int:job_id>")
def get_specific_job(job_id):
    return db.session.query(live_job).filter(live_job.id == job_id).first().to_dict()


@job_api.get("/job")
def all_jobs():
    jobs = db.session.query(live_job).all()
    return jsonify([job.to_dict() for job in jobs])


@job_api.delete("/job")
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


@job_api.patch("/job/<int:job_id>")
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
