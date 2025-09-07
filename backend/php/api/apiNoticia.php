<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$id = intval($_GET['id'] ?? 0);

$stmt = $mysqli->prepare("SELECT titulo, imagem, resumo, conteudo, data_publicacao FROM noticias WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $noticia = $result->fetch_assoc();
    $noticia['data_publicacao'] = date('d/m/Y H:i', strtotime($noticia['data_publicacao']));
    echo json_encode($noticia);
} else {
    http_response_code(404);
    echo json_encode(["erro" => "Notícia não encontrada"]);
}