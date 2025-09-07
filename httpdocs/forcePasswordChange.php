<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/user/forcePasswordChange.css">
</head>

<body>
	<section class="container">
		<div class="container-content">
			<h2>Alterar Senha</h2>
			<p>Você está usando uma senha temporária. Crie uma nova senha segura.</p>

			<form id="changeForm">
				<input class="input-primary" type="password" name="nova_senha" id="nova_senha" placeholder="Nova senha"
					required>
				<input class="input-primary" type="password" name="confirm_senha" id="confirm_senha"
					placeholder="Confirmar senha" required>
				<button type="submit" class="btn-primary">Alterar Senha</button>
			</form>
		</div>
	</section>


    <!-- Scripts utilitários -->
    <script src="scripts/utilities/toast.js"></script>
    <script src="scripts/utilities/spinner.js"></script>

    <!-- Lógica da página -->
    <script>
    document.getElementById('changeForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const nova = document.getElementById('nova_senha').value;
        const conf = document.getElementById('confirm_senha').value;

        if (nova !== conf) {
            toast('As senhas não coincidem.', 'error'); // toast.js
            return;
        }

        showSpinner(); // spinner.js

        try {
            const res = await fetch('api/forcePasswordChangeProxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    nova_senha: nova,
                    confirm_senha: conf
                })
            }).then(r => r.json());

            hideSpinner();

            toast(res.message, res.status === 'success' ? 'success' : 'error');

            if (res.status === 'success') {
                setTimeout(() => (window.location.href = 'login.php'), 2500);
            }
        } catch (err) {
            hideSpinner();
            toast('Erro de conexão. Tente novamente.', 'error');
            console.error(err);
        }
    });
    </script>
</body>

</html>