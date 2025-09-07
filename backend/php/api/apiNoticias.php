<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$sql = "SELECT id, titulo, imagem FROM noticias ORDER BY data_publicacao DESC";
$result = $mysqli->query($sql);

$noticias = [];

while ($row = $result->fetch_assoc()) {
    $noticias[] = $row;
}

echo json_encode($noticias);
?>