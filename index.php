<?php

require __DIR__ . '/db.php';

header('Content-Type: application/json');

$pdo = getDb();
$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = $path === '' ? [] : explode('/', $path);

// Expected routes: /tasks and /tasks/{id}
if (($segments[0] ?? '') !== 'tasks') {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

$id = $segments[1] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
                $stmt->execute([$id]);
                $task = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$task) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Task not found']);
                    break;
                }
                echo json_encode($task);
            } else {
                $tasks = $pdo->query('SELECT * FROM tasks ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($tasks);
            }
            break;

        case 'POST':
            $body = json_decode(file_get_contents('php://input'), true);
            $title = trim($body['title'] ?? '');
            if ($title === '') {
                http_response_code(422);
                echo json_encode(['error' => 'title is required']);
                break;
            }
            $stmt = $pdo->prepare('INSERT INTO tasks (title, done, created_at) VALUES (?, 0, ?)');
            $stmt->execute([$title, date('c')]);
            http_response_code(201);
            echo json_encode(['id' => $pdo->lastInsertId(), 'title' => $title, 'done' => 0]);
            break;

        case 'PATCH':
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Task id required']);
                break;
            }
            $body = json_decode(file_get_contents('php://input'), true);
            $fields = [];
            $values = [];
            if (array_key_exists('title', $body ?? [])) {
                $fields[] = 'title = ?';
                $values[] = $body['title'];
            }
            if (array_key_exists('done', $body ?? [])) {
                $fields[] = 'done = ?';
                $values[] = (int) $body['done'];
            }
            if (empty($fields)) {
                http_response_code(422);
                echo json_encode(['error' => 'Nothing to update']);
                break;
            }
            $values[] = $id;
            $pdo->prepare('UPDATE tasks SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($values);
            $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Task id required']);
                break;
            }
            $pdo->prepare('DELETE FROM tasks WHERE id = ?')->execute([$id]);
            http_response_code(204);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
