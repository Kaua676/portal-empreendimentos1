<?php
session_start();
require_once __DIR__ . '/../config/email.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método inválido.'
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$nome = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$mensagem = htmlspecialchars(trim($data['message'] ?? ''));

if (!$nome || !$email || !$mensagem) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Preencha todos os campos.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'E-mail inválido.'
    ]);
    exit;
}

$assunto = "Nova mensagem de contato - Portal Consulta";
$corpo = "Você recebeu uma nova mensagem de contato:\n\n" .
    "Nome: $nome\n" .
    "Email: $email\n\n" .
    "Mensagem:\n$mensagem\n";

$destinatario = 'kauavic676@gmail.com';

if (enviarEmail($destinatario, $assunto, $corpo)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Mensagem enviada com sucesso!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao enviar a mensagem. Tente novamente mais tarde.'
    ]);
}