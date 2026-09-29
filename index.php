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
