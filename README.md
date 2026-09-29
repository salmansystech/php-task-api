# PHP Task API

A small REST API for managing tasks, written in plain PHP (no framework) with SQLite via PDO.

## Endpoints

| Method | Path          | Description       |
|--------|---------------|--------------------|
| GET    | /tasks        | List all tasks     |
| GET    | /tasks/{id}   | Get a single task  |
| POST   | /tasks        | Create a task       |
| PATCH  | /tasks/{id}   | Update a task       |
| DELETE | /tasks/{id}   | Delete a task       |

## Run locally

```bash
php -S localhost:8000
```

Then:

```bash
curl -X POST localhost:8000/tasks -d '{"title":"Buy milk"}'
curl localhost:8000/tasks
```

SQLite database file (`tasks.sqlite`) is created automatically on first request.
