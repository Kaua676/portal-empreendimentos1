<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php'; 
require_once __DIR__ . '/../config/email.php';

session_start();


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['email'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Informe seu e-mail.'
    ]);
    exit;
}

$email = trim($_POST['email']);

// Verifica se o e-mail está cadastrado
$stmt = $mysqli->prepare("SELECT cpf FROM pessoa_fisica WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'E-mail não cadastrado.'
    ]);
    exit;
}

$cpf = $result->fetch_assoc()['cpf'];

// Gera nova senha
$novaSenhaPlana = gerarSenhaTemporaria(8);
$novaSenhaHash = password_hash($novaSenhaPlana, PASSWORD_DEFAULT);

// Atualiza senha + flag
$stmt = $mysqli->prepare("UPDATE login SET senha = ?, must_change_pwd = 1 WHERE cpf_login = ?");
$stmt->bind_param("ss", $novaSenhaHash, $cpf);

if ($stmt->execute()) {
    $assunto = "Recuperação de Senha";
    $corpo = "
    <html>
      <head>
        <meta charset='UTF-8'>
      </head>
      <body>
        <p>Olá!</p>
        <p>Sua nova senha temporária é: <strong>$novaSenhaPlana</strong></p>
        <p>Use-a para fazer login e altere sua senha depois.</p>
      </body>
    </html>
    ";

    if (enviarEmail($email, $assunto, $corpo)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Uma nova senha foi enviada para seu e-mail.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao enviar o e-mail.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro ao atualizar a senha.'
    ]);
}

// Geração da senha temporária
function gerarSenhaTemporaria($length = 8) {
    $letrasMaiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $letrasMinusculas = 'abcdefghijklmnopqrstuvwxyz';
    $numeros = '0123456789';
    $especiais = '@#$';

    $senha = $letrasMaiusculas[random_int(0, strlen($letrasMaiusculas) - 1)];
    $senha .= $numeros[random_int(0, strlen($numeros) - 1)];
    $senha .= $especiais[random_int(0, strlen($especiais) - 1)];

    $todos = $letrasMaiusculas . $letrasMinusculas . $numeros . $especiais;
    for ($i = 3; $i < $length; $i++) {
        $senha .= $todos[random_int(0, strlen($todos) - 1)];
    }

    return str_shuffle($senha);
}