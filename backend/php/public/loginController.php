<?php
require_once __DIR__ . '/../../vendor/autoload.php';
session_start();
require_once __DIR__ . '/../config/database.php';


header('Content-Type: application/json');

// Verificação dos campos esperados
if (
    isset($_POST['submit']) &&
    !empty($_POST['documento']) &&
    !empty($_POST['password']) &&
    !empty($_POST['captchaInput']) &&
    !empty($_POST['captcha_code'])
) {
    $limite_tentativas = 2;
    $tempo_bloqueio = 200;

    if (!isset($_SESSION['tentativas_login'])) {
        $_SESSION['tentativas_login'] = 0;
    }

    if (!isset($_SESSION['bloqueio_tempo'])) {
        $_SESSION['bloqueio_tempo'] = 0;
    }

    $tempo_restante = time() - $_SESSION['bloqueio_tempo'];
    if ($_SESSION['tentativas_login'] >= $limite_tentativas && $tempo_restante < $tempo_bloqueio) {
        echo json_encode([
            'status' => 'blocked',
            'tempoRestante' => $tempo_bloqueio - $tempo_restante
        ]);
        exit;
    }

    // Coleta e sanitiza
    $documento = preg_replace('/\D/', '', $_POST['documento']);
    $senha = $_POST['password'];
    $captchaInput = htmlspecialchars($_POST['captchaInput']);
    $captchaCode = $_POST['captcha_code'];

    // Verifica CAPTCHA
    if ($captchaInput !== $captchaCode) {
        echo json_encode([
            'status' => 'error',
            'message' => 'CAPTCHA incorreto.'
        ]);
        exit;
    }

    // Verifica login no banco
    $stmt = $mysqli->prepare("SELECT senha, must_change_pwd FROM login WHERE cpf_login = ?");
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuário não encontrado.'
        ]);
        exit;
    }

    $row = $result->fetch_assoc();
    $hash_armazenado = $row['senha'];
    $mustChange = $row['must_change_pwd'] ?? 0;

    if (password_verify($senha, $hash_armazenado)) {
        $_SESSION['tentativas_login'] = 0;
        $_SESSION['bloqueio_tempo'] = 0;

        if ($mustChange == 1) {
            $_SESSION['cpf_login'] = $documento;
            echo json_encode([
                'status' => 'change_password'
            ]);
        } else {
            $_SESSION['user_cpf'] = $documento;
            $_SESSION['usuario_logado'] = true;

            echo json_encode([
                'status' => 'success'
            ]);
        }
    } else {
        $_SESSION['tentativas_login']++;
        if ($_SESSION['tentativas_login'] >= $limite_tentativas) {
            $_SESSION['bloqueio_tempo'] = time();
            echo json_encode([
                'status' => 'blocked',
                'tempoRestante' => $tempo_bloqueio
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'CPF ou senha incorretos.'
            ]);
        }
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Todos os campos são obrigatórios.'
    ]);
}