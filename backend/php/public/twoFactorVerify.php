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
    exit;
}

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 5;
}

$cpfUsuario = $_SESSION['user_cpf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
    $codigoInserido = $_POST['codigo'];

    $stmt = $mysqli->prepare("SELECT auth_token FROM pessoa_fisica WHERE cpf = ?");
    $stmt->bind_param("s", $cpfUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'CPF não encontrado. Faça login novamente.'
        ]);
        exit;
    }

    $dados = $result->fetch_assoc();
    $codigoBanco = $dados['auth_token'];

    if ($codigoInserido === $codigoBanco) {
        $_SESSION['attempts'] = 5;

        // Remove o token do banco
        $stmt = $mysqli->prepare("UPDATE pessoa_fisica SET auth_token = NULL WHERE cpf = ?");
        $stmt->bind_param("s", $cpfUsuario);
        $stmt->execute();

        // Marca como logado completo
        $_SESSION['usuario_logado'] = true;

        echo json_encode([
			'status' => 'success',
			'message' => 'Código verificado com sucesso!'
		]);

        exit;
    } else {
        $_SESSION['attempts']--;

        if ($_SESSION['attempts'] <= 0) {
            $stmt = $mysqli->prepare("UPDATE pessoa_fisica SET auth_token = NULL WHERE cpf = ?");
            $stmt->bind_param("s", $cpfUsuario);
            $stmt->execute();

            session_unset();
            session_destroy();

            echo json_encode([
                'status' => 'error',
                'message' => 'Você excedeu o limite de tentativas. Faça login novamente.',
                'redirect' => 'login.php'
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Código incorreto.',
                'tentativas' => $_SESSION['attempts']
            ]);
            exit;
        }
    }
}

echo json_encode([
    'status' => 'error',
    'message' => 'Requisição inválida.'
]);
exit;