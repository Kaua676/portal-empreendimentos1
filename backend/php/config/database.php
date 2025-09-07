<?php
// conexao.php

$host = 'localhost:3306';  // Ou o IP do seu servidor de banco
$user = 'kadom_admin';       // Usuário do banco
$pass = 'M8OkAl&7jcna7px#';           // Senha do banco
$db   = 'kadom_portalemp';    // Nome do banco

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Se quiser usar charset UTF-8:
$mysqli->set_charset("utf8");
?>