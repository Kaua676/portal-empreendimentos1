<?php
require_once __DIR__ . '/env.php';

$mysqli = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");