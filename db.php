<?php

function getDb(): PDO
{
    // Keep the DB file next to the script so `php -S` demos work with zero config.
    $dbFile = __DIR__ . '/tasks.sqlite';
    $isNew = !file_exists($dbFile);

    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($isNew) {
        $pdo->exec('
            CREATE TABLE tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                done INTEGER NOT NULL DEFAULT 0,
                created_at TEXT NOT NULL
            )
        ');
    }

    return $pdo;
}
