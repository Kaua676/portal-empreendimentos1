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
        $uploadDir = __DIR__ . '../../../../httpdocs/assets/uploads/';
		if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

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
$novaSenha  = $_POST['nova_senha'] ?? '';
$confirmar  = $_POST['confirmar_senha'] ?? '';

// 1) normalize o CPF para só números (evita "não encontrado")
$cpf = preg_replace('/\D/', '', $cpf);

if ($novaSenha !== '' || $confirmar !== '') {
    if ($senhaAtual === '') {
        $erros[] = "Você precisa informar sua senha atual.";
    } else {
        // Buscar hash atual
        $stmtVerifica = $mysqli->prepare("SELECT senha FROM login WHERE cpf_login = ? LIMIT 1");
        if (!$stmtVerifica) {
            $erros[] = "Falha ao preparar consulta (login): " . $mysqli->error;
        } else {
            $stmtVerifica->bind_param("s", $cpf);
            $stmtVerifica->execute();
            $stmtVerifica->store_result();

            if ($stmtVerifica->num_rows === 0) {
                $erros[] = "Conta não encontrada para este CPF.";
            } else {
                $stmtVerifica->bind_result($senhaHash);
                $stmtVerifica->fetch();
            }
            $stmtVerifica->close();
        }

        // Só segue se não houve erro até aqui
        if (empty($erros)) {
            $okAtual = false;
            $rehashAntigo = false;

            // 2) verifica hash moderno
            if (!empty($senhaHash) && password_verify($senhaAtual, $senhaHash)) {
                $okAtual = true;
            } else {
                // 3) fallbacks para bases antigas (md5 ou texto puro)
                if (is_string($senhaHash)) {
                    // md5: 32 chars hex
                    if (strlen($senhaHash) === 32 && ctype_xdigit($senhaHash) &&
                        md5($senhaAtual) === strtolower($senhaHash)) {
                        $okAtual = true;
                        $rehashAntigo = true;
                    }
                    // texto puro (NÃO recomendado, mas comum em bases antigas)
                    elseif (hash_equals($senhaHash, $senhaAtual)) {
                        $okAtual = true;
                        $rehashAntigo = true;
                    }
                }
            }

            if (!$okAtual) {
                $erros[] = "Senha atual incorreta.";
            } elseif ($novaSenha !== $confirmar) {
                $erros[] = "A nova senha e a confirmação não coincidem.";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $novaSenha)) {
                $erros[] = "A nova senha deve conter no mínimo 8 caracteres, incluindo número, letra maiúscula, minúscula e símbolo.";
            } else {
                // 4) Atualiza para hash moderno
                $hashNova = password_hash($novaSenha, PASSWORD_DEFAULT);

                $stmtAtualiza = $mysqli->prepare("UPDATE login SET senha = ?, must_change_pwd = 0 WHERE cpf_login = ? LIMIT 1");
                if (!$stmtAtualiza) {
                    $erros[] = "Falha ao preparar atualização de senha: " . $mysqli->error;
                } else {
                    $stmtAtualiza->bind_param("ss", $hashNova, $cpf);
                    if (!$stmtAtualiza->execute()) {
                        $erros[] = "Falha ao salvar nova senha: " . $stmtAtualiza->error;
                    }
                    $stmtAtualiza->close();
                }

                // 5) E-mail de confirmação (não bloqueia sucesso da senha)
                if (empty($erros)) {
                    $stmtEmail = $mysqli->prepare("SELECT email FROM pessoa_fisica WHERE cpf = ? LIMIT 1");
                    if ($stmtEmail) {
                        $stmtEmail->bind_param("s", $cpf);
                        $stmtEmail->execute();
                        $stmtEmail->bind_result($email);
                        $stmtEmail->fetch();
                        $stmtEmail->close();
                    }

                    if (!empty($email)) {
                        try {
                            $assunto = "Sua senha foi alterada";
                            $corpo   = "Olá! Informamos que sua senha foi alterada com sucesso em nosso sistema.";
                            // se sua enviarEmail() retorna bool, você pode checar e logar falha
                            enviarEmail($email, $assunto, $corpo);
                        } catch (Throwable $e) {
                            // log opcional: error_log($e->getMessage());
                            $mensagens[] = "Senha alterada. Não foi possível enviar o e-mail de confirmação.";
                        }
                    }

                    // Se vinha de hash antigo, considere marcar para re-login ou só informa
                    if ($rehashAntigo) {
                        $mensagens[] = "Senha atualizada e formato de segurança modernizado.";
                    } else {
                        $mensagens[] = "Senha alterada com sucesso.";
                    }
                }
            }
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