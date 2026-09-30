# API Specification

## Endpoints

### GET /tasks
Retrieve all tasks

### POST /tasks
Create a new task

Request:
```json
{
  "title": "Task title (required)"
}
```

### GET /tasks/{id}
Get specific task by ID

### PATCH /tasks/{id}
Update task (partial)

### DELETE /tasks/{id}
Delete task
