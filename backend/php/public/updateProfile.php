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

$cpf = $_SESSION['user_cpf'];
$erros = [];
$mensagens = [];

// Upload de foto
if (isset($_FILES['nova_foto']) && $_FILES['nova_foto']['error'] == UPLOAD_ERR_OK) {
    $permitidos = ['image/png', 'image/jpg', 'image/jpeg'];
    $tipo = mime_content_type($_FILES['nova_foto']['tmp_name']);

    if (in_array($tipo, $permitidos)) {
        $ext = strtolower(pathinfo($_FILES['nova_foto']['name'], PATHINFO_EXTENSION));
        $novo_nome = uniqid("foto_", true) . "." . $ext;
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/';   // -> /var/www/vhosts/kadom.com.br/httpdocs/assets/uploads/
		if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }   // cria se faltar

		$destino = $uploadDir . $novo_nome;   // move_uploaded_file() usa esse caminho

        if (move_uploaded_file($_FILES['nova_foto']['tmp_name'], $destino)) {
            $stmtFoto = $mysqli->prepare("UPDATE pessoa_fisica SET foto_perfil = ? WHERE cpf = ?");
            $stmtFoto->bind_param("ss", $novo_nome, $cpf);

            if ($stmtFoto->execute()) {
                $mensagens[] = "Imagem atualizada com sucesso.";
            } else {
                $erros[] = "Erro ao atualizar a imagem no banco.";
            }

            $stmtFoto->close();
        } else {
            $erros[] = "Erro ao mover a nova foto.";
        }
    } else {
        $erros[] = "Formato de imagem inválido. Use PNG, JPG ou JPEG.";
    }
}


// Troca de senha
$senhaAtual = $_POST['senha_atual'] ?? '';
$novaSenha = $_POST['nova_senha'] ?? '';
$confirmar = $_POST['confirmar_senha'] ?? '';

if (!empty($novaSenha) || !empty($confirmar)) {
    if (empty($senhaAtual)) {
        $erros[] = "Você precisa informar sua senha atual.";
    } else {
        // Verificar se a senha atual está correta
        $stmtVerifica = $mysqli->prepare("SELECT senha FROM login WHERE cpf_login = ?");
        $stmtVerifica->bind_param("s", $cpf);
        $stmtVerifica->execute();
        $stmtVerifica->bind_result($senhaHash);
        $stmtVerifica->fetch();
        $stmtVerifica->close();

        if (!password_verify($senhaAtual, $senhaHash)) {
            $erros[] = "Senha atual incorreta.";
        } elseif ($novaSenha !== $confirmar) {
            $erros[] = "A nova senha e a confirmação não coincidem.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $novaSenha)) {
            $erros[] = "A nova senha deve conter no mínimo 8 caracteres, incluindo número, letra maiúscula, minúscula e símbolo.";
        } else {
            // Atualizar senha
            $hashNova = password_hash($novaSenha, PASSWORD_DEFAULT);
            $stmtAtualiza = $mysqli->prepare("UPDATE login SET senha = ?, must_change_pwd = 0 WHERE cpf_login = ?");
            $stmtAtualiza->bind_param("ss", $hashNova, $cpf);
            $stmtAtualiza->execute();

            // Enviar email de confirmação
            $stmtEmail = $mysqli->prepare("SELECT email FROM pessoa_fisica WHERE cpf = ?");
            $stmtEmail->bind_param("s", $cpf);
            $stmtEmail->execute();
            $stmtEmail->bind_result($email);
            $stmtEmail->fetch();
            $stmtEmail->close();

            $assunto = "Sua senha foi alterada";
            $corpo = "Olá! Informamos que sua senha foi alterada com sucesso em nosso sistema.";
            enviarEmail($email, $assunto, $corpo);

            $mensagens[] = "Senha alterada com sucesso.";
        }
    }
}

// Resposta final
if (empty($erros)) {
    echo json_encode([
        'status' => 'success',
        'message' => implode(" ", $mensagens)
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => implode(" ", $erros)
    ]);
}