<?php
// conexao.php

$host = 'localhost';  // Ou o IP do seu servidor de banco
$user = 'root';       // Usuário do banco
$pass = '';           // Senha do banco
$db   = 'portalemp';    // Nome do banco

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Se quiser usar charset UTF-8:
$mysqli->set_charset("utf8");
?>
