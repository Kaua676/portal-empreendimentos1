<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$limit = 3; // define o número de notícias a serem retornadas
$sql = "SELECT id, titulo, imagem FROM noticias ORDER BY data_publicacao DESC LIMIT ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $noticias = [];

    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
    }

    echo json_encode($noticias);
} else {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao preparar consulta."]);
}
?>