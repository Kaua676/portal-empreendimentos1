<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

function validarCPF($cpf)
{
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) $d += $cpf[$c] * (($t + 1) - $c);
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}

function jsonResponse($status, $message)
{
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse('error', 'Requisição inválida.');
}

// Captura e saneamento dos dados
$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');
$documento = preg_replace('/\D/', '', $_POST['documento'] ?? '');
$nasc = $_POST['nasc'] ?? '';
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$confirmEmail = filter_input(INPUT_POST, 'confirmEmail', FILTER_VALIDATE_EMAIL);
$number = preg_replace('/\D/', '', $_POST['number'] ?? '');
$cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$rua = trim($_POST['rua'] ?? '');
$numero = $_POST['numero'] ?? '';
$complemento = trim($_POST['complemento'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$uf = $_POST['estado'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$termos = isset($_POST['termoslgpd']);


// Validações
if (!$nome || !$sobrenome || !$documento || !$nasc || !$email || !$confirmEmail || !$number || !$cep || !$bairro || !$rua || !$numero || !$cidade || !$uf || !$password || !$confirmPassword) {
    jsonResponse('error', 'Preencha todos os campos obrigatórios.');
}

if (!validarCPF($documento)) {
    jsonResponse('error', 'CPF inválido.');
}

if (!$termos) {
    jsonResponse('error', 'Você deve aceitar os termos e políticas.');
}

if ($email !== $confirmEmail) {
    jsonResponse('error', 'Os e-mails não coincidem.');
}

if ($password !== $confirmPassword) {
    jsonResponse('error', 'As senhas não coincidem.');
}

if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>-]).{8,}$/', $password)) {
    jsonResponse('error', message: 'Senha não atende aos requisitos.');
}

// Valida data
$date = DateTime::createFromFormat('d/m/Y', $nasc);
if (!$date) jsonResponse('error', 'Data de nascimento inválida.');
$nasc_formatada = $date->format('Y-m-d');

// Verifica CPF duplicado
$check = $mysqli->prepare("SELECT cpf FROM pessoa_fisica WHERE cpf = ?");
$check->bind_param("s", $documento);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $check->close();
    jsonResponse('error', 'CPF já cadastrado.');
}
$check->close();

// Hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// Inserção
$stmt1 = $mysqli->prepare("INSERT INTO pessoa_fisica (
    cpf, telefone, primeiro_nome, sobrenome, cep, rua, numero, complemento, bairro, cidade, uf, email, nasc
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("sssssssssssss", $documento, $number, $nome, $sobrenome, $cep, $rua, $numero, $complemento, $bairro, $cidade, $uf, $email, $nasc_formatada);

if (!$stmt1->execute()) {
    $stmt1->close();
    jsonResponse('error', 'Erro ao cadastrar dados.');
}
$stmt1->close();

$stmt2 = $mysqli->prepare("INSERT INTO login (cpf_login, senha) VALUES (?, ?)");
$stmt2->bind_param("ss", $documento, $hash);

if (!$stmt2->execute()) {
    $stmt2->close();
    jsonResponse('error', 'Erro ao cadastrar login.');
}
$stmt2->close();
$mysqli->close();

jsonResponse('success', 'Cadastro realizado com sucesso!');