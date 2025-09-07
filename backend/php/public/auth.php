<?php
session_start();

if (!isset($_SESSION['user_cpf'])) {
    // Se for requisição JSON (via fetch, por exemplo)
    if (
        isset($_SERVER['HTTP_ACCEPT']) &&
        strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
    ) {
        http_response_code(401);
        echo json_encode(['status' => 'unauthorized', 'message' => 'Você precisa estar logado para acessar esta área.']);
        exit;
    } else {
        // Para HTML: define mensagem e redireciona
        $_SESSION['login_message'] = 'Você precisa estar logado para acessar esta página.';
        header("Location: /login.php");
        exit;
    }
}
