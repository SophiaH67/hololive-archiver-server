# hololive-archiver-server

This project is responsible for adding download jobs(youtube or twitter) to the job queue.
These jobs will then be downloaded and archived by the hololive-archiver-worker pods.
A job can be in state `pending`, `waiting`, `running`, `finished`, `failed`.
If a job is in state `failed`, the error message will be available in the `error` field.
