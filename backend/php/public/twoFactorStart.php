<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/email.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_cpf'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sessão expirada. Faça login novamente.'
    ]);
    exit();
}

$cpfUsuario = $_SESSION['user_cpf'];

$stmt = $mysqli->prepare("SELECT email FROM pessoa_fisica WHERE cpf = ?");
$stmt->bind_param("s", $cpfUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Usuário não encontrado. Faça login novamente.'
    ]);
    exit();
}

$usuario = $result->fetch_assoc();
$emailUsuario = $usuario['email'];

$codigoVerificacao = rand(100000, 999999);
$stmtUpdate = $mysqli->prepare("UPDATE pessoa_fisica SET auth_token = ? WHERE cpf = ?");
$stmtUpdate->bind_param("ss", $codigoVerificacao, $cpfUsuario);
$stmtUpdate->execute();

$assunto = "Código de Verificação";
$corpo = "Seu código de verificação é: $codigoVerificacao";

if (enviarEmail($emailUsuario, $assunto, $corpo)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Novo código enviado com sucesso.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao enviar o e-mail. Tente novamente.'
    ]);
}
exit();
