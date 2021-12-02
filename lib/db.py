from flask import current_app as app
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.inspection import inspect
from sqlalchemy_serializer import SerializerMixin
from os import environ

app.config['SQLALCHEMY_DATABASE_URI'] = environ.get('DATABASE_URL')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)


class live_job(db.Model, SerializerMixin):
    __tablename__ = 'live_jobs'
    id = db.Column(db.Integer, primary_key=True, nullable=False)
    status = db.Column(db.String, index=True, nullable=False)
    url = db.Column(db.String, unique=True, nullable=False)

    def __init__(self, url, save_location, status="pending"):
        self.url = url
        self.save_location = save_location
        self.status = status

# Create tables if they don't exist
if not inspect(db.engine).has_table('live_jobs'):
    print("Creating tables")
    db.create_all()