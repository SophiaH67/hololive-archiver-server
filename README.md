# hololive-archiver-server

This project is responsible for adding download jobs(youtube or twitter) to the job queue.
These jobs will then be downloaded and archived by the hololive-archiver-worker pods.
A job can be in state `pending`, `running`, `finished`, `error`.
If a job is in state `error`, the error message will be available in the `error` field.

## Setup

To run the server, you need to have a working docker installation.

```bash
docker run -d \
  -p 5000:5000 \
  -v /path/to/config.yaml:/app/config.yaml \
  -v /path/to/shared/folder:/shared \
  -v /path/to/final/downloads:/downloads \
  -e DATABASE_URL=postgres://user:password@host:port/database \
  --name hololive-archiver-server \
  ghcr.io/marnixah/hololive-archiver-server:master
```
