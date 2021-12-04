from quart import abort
from quart import Quart, request
from quart import request
from quart import current_app as app
from sqlalchemy.inspection import inspect
from os import environ
from classes.live_job import db, live_job
import shutil
import os
from pathlib import Path
from routes.update import update_api, update_jobs

app = Quart(__name__)

app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('DATABASE_URL')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.app = app
db.init_app(app)


# Create tables if they don't exist
if not inspect(db.engine).has_table('live_jobs'):
    print("Creating tables")
    db.create_all()

app.register_blueprint(update_api)


@app.before_serving
async def startup():
    app.add_background_task(update_jobs)


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


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
