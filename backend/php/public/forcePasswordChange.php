<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['cpf_login'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sessão expirada. Faça login novamente.'
    ]);
    exit;
}

$cpf = $_SESSION['cpf_login'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova = $_POST['nova_senha'] ?? '';
    $confirma = $_POST['confirm_senha'] ?? '';

    if ($nova !== $confirma) {
        echo json_encode([
            'status' => 'error',
            'message' => 'As senhas não coincidem.'
        ]);
        exit;
    }

    if (strlen($nova) < 8) {
        echo json_encode([
            'status' => 'error',
            'message' => 'A senha deve ter pelo menos 8 caracteres.'
        ]);
        exit;
    }

    $novaHash = password_hash($nova, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("UPDATE login SET senha = ?, must_change_pwd = 0 WHERE cpf_login = ?");
    $stmt->bind_param("ss", $novaHash, $cpf);

    if ($stmt->execute()) {
        session_destroy();
        echo json_encode([
            'status' => 'success',
            'message' => 'Senha alterada com sucesso! Faça login novamente.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao atualizar a senha no sistema.'
        ]);
    }
}