<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
include_once 'config.php'; 
?>

<?php
session_start();
require_once BACKEND_PATH . 'config/database.php';

$cpf  = $_SESSION['user_cpf'];
$stmt = $mysqli->prepare(
    'SELECT primeiro_nome, sobrenome, telefone, email, foto_perfil
     FROM pessoa_fisica WHERE cpf = ?'
);
$stmt->bind_param('s', $cpf);
$stmt->execute();
$stmt->bind_result($nome, $sobrenome, $telefone, $email, $foto_perfil);
$stmt->fetch();
$stmt->close();

$foto = $foto_perfil && file_exists("assets/uploads/$foto_perfil")
    ? "assets/uploads/$foto_perfil"
    : 'assets/img/defaultUser.svg';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/user/profile.css">
	<title>Meu Perfil | Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <div class="perfil-container">
        <h2 class="title" >Meu Perfil</h2>

        <form id="profileForm" enctype="multipart/form-data">
            <div class="foto-section">
                <img src="<?= $foto ?>?v=<?= time() ?>" id="previewImagem" class="foto-perfil-preview"
                    alt="Foto de perfil atual">
                <input type="file" name="nova_foto" id="inputImagem" accept=".png,.jpg,.jpeg">
            </div>

            <div class="info-section">
                <label>Nome Completo:</label>
                <input class="input-primary" type="text" value="<?= htmlspecialchars("$nome $sobrenome") ?>" disabled>

                <label>Telefone:</label>
                <input class="input-primary" type="text" value="<?= htmlspecialchars($telefone) ?>" disabled>

                <label>E-mail:</label>
                <input class="input-primary" type="email" value="<?= htmlspecialchars($email) ?>" disabled>
            </div>

            <div class="senha-section">
                <label>Senha Atual:</label>
                <input class="input-primary" type="password" name="senha_atual" id="senha_atual">

                <label>Nova Senha:</label>
                <input class="input-primary" type="password" name="nova_senha" id="nova_senha">

                <label>Confirmar Nova Senha:</label>
                <input class="input-primary" type="password" name="confirmar_senha" id="confirmar_senha">
            </div>

            <button class="btn-primary" type="submit">Atualizar Perfil</button>
        </form>
    </div>

    <?php include_once 'includes/footer.php'; ?>

    <script src="scripts/utilities/toast.js"></script>
    <script src="scripts/utilities/spinner.js"></script>

    <script>
    document.getElementById('inputImagem').addEventListener('change', ({
        target
    }) => {
        const file = target.files[0];
        if (file) document.getElementById('previewImagem').src = URL.createObjectURL(file);
    });

    document.getElementById('profileForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const senhaAtual = formData.get('senha_atual');
        const novaSenha = formData.get('nova_senha');
        const confirma = formData.get('confirmar_senha');

        if ((novaSenha || confirma) && !senhaAtual) {
            toast('Informe sua senha atual para alterar a senha.', 'error');
            return;
        }

        if (novaSenha && novaSenha !== confirma) {
            toast('As senhas não coincidem.', 'error');
            return;
        }

        showSpinner();

        fetch('api/updateProfileProxy.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(({
                status,
                message
            }) => {
                hideSpinner();
                toast(message, status === 'success' ? 'success' : 'error');
                if (status === 'success') setTimeout(() => location.reload(), 1500);
            })
            .catch(err => {
                hideSpinner();
                toast('Erro ao atualizar perfil.', 'error');
                console.error(err);
            });
    });
    </script>
</body>

</html>