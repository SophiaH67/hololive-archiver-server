from quart import abort
from classes.live_job import db
from quart import Blueprint

health_api = Blueprint('health_api', __name__)


@health_api.get("/health")
def health():
    # Check if the SQLAlchemy engine is up and running
    if db.engine.execute("SELECT 1").scalar() == 1:
        return {"status": "OK"}
    abort(500)
