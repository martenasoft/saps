<?php

try {
    $dsn = "pgsql:host=database;port=5432;dbname=app;";
    // make a database connection
    $pdo = new PDO($dsn, 'app', 'root', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $pdo->query("insert into users(email) values ('one') /s insert into users(email) values ('two')");
    if ($pdo) {
        echo "Connected to the app database successfully!";
    }
} catch (PDOException $e) {
    die($e->getMessage());
} finally {
    if ($pdo) {
        $pdo = null;
    }
}
