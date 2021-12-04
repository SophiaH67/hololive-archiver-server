from quart import Quart, request
from quart import request
from quart import current_app as app
from sqlalchemy.inspection import inspect
from os import environ
from classes.live_job import db
from routes.update import update_api, update_jobs
from routes.job import job_api
import logging

app = Quart(__name__)

app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('DATABASE_URL')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.app = app
db.init_app(app)

logging.getLogger('quart.serving').setLevel(logging.ERROR)

# Create tables if they don't exist
if not inspect(db.engine).has_table('live_jobs'):
    print("Creating tables")
    db.create_all()

# Register routes
app.register_blueprint(update_api)
app.register_blueprint(job_api)

@app.before_serving
async def startup():
    app.add_background_task(update_jobs)


@app.after_request
def after_request(response):
    response.headers.add('Access-Control-Allow-Origin', '*')
    response.headers.add('Access-Control-Allow-Headers',
                         'Content-Type,Authorization')
    response.headers.add('Access-Control-Allow-Methods',
                         'GET,PUT,POST,DELETE,PATCH')
    return response

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
