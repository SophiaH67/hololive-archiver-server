from quart import Blueprint
from config import get_final_output_path_from_stream, holodex_searches
from sqlalchemy.exc import IntegrityError
from hololive import hololive
import psycopg2
import asyncio
import datetime
from classes.live_job import db, live_job
from config import get_final_output_path_from_stream, holodex_searches

update_api = Blueprint('update_api', __name__)


@update_api.get("/update")
async def update_live_jobs():
    for holodex_search in holodex_searches:
        for topic in holodex_search.topics:
            for stream in await hololive.get_live(channel_id=holodex_search.channel_id, org=holodex_search.org, topic=topic, limit=50):
                stream_to_live_job(stream)

    return "OK"


def stream_to_live_job(stream: hololive.Stream):
    formatted_date = (
        stream.start_scheduled or datetime.datetime.now()).strftime("%Y-%m-%d")
    safe_title = stream.title.replace("/", "-").replace("\\", "-")
    job = live_job(
        f"https://youtube.com/watch?v={stream.id}",
        f"/shared/{stream.id}/[{formatted_date}] {safe_title}.mkv",
        get_final_output_path_from_stream(stream),
        handler="ytarchive"
    )
    db.session.add(job)
    try:
        db.session.commit()
    except IntegrityError as e:
        db.session.rollback()
        if not isinstance(e.orig, psycopg2.errors.UniqueViolation):
            raise e
        # Update job if it already exists
        existing_job = db.session.query(live_job).filter(
            job.url == f"https://youtube.com/watch?v={stream.id}").first()
        if not existing_job.automatic:
            return
        if existing_job.status == "finished" or existing_job.status == "error":
            return
        job.final_location = get_final_output_path_from_stream(stream)


async def update_jobs():
    await update_live_jobs()
    while True:
        await asyncio.sleep(300)
        await update_live_jobs()
