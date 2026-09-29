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

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
