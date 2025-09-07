<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

// Verifica se está logado
if (!isset($_SESSION['user_cpf']) || !isset($_SESSION['usuario_logado'])) {
    echo json_encode([
        'nome' => 'Usuário',
        'email' => '',
        'telefone' => '',
        'foto' => null
    ]);
    exit;
}

// Busca os dados completos do usuário
$cpf = $_SESSION['user_cpf'];
$stmt = $mysqli->prepare("SELECT primeiro_nome, email, telefone, foto_perfil FROM pessoa_fisica WHERE cpf = ?");
$stmt->bind_param("s", $cpf);
$stmt->execute();
$stmt->bind_result($nome, $email, $telefone, $foto);
$stmt->fetch();
$stmt->close();

echo json_encode([
    'nome' => $nome ?? 'Usuário',
    'email' => $email ?? '',
    'telefone' => $telefone ?? '',
    'foto' => $foto ? "../../../../httpdocs/assets/uploads/$foto" : null
]);