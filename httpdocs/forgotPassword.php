<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/user/resetPassword.css">
</head>

<body>
    <section class="container">
        <div class="container-content">
            <h2 class="title">Recuperar Senha</h2>

            <form id="resetForm">
                <label for="email">Digite seu e-mail cadastrado:</label>
                <input class="input-primary" type="email" name="email" id="email" required>

                <button class="btn-primary" type="submit">Enviar senha temporária</button>
            </form>

            <a href="login.php">Voltar ao Login</a>
        </div>
    </section>

    <!-- Scripts utilitários -->
    <script src="scripts/utilities/toast.js"></script>
    <script src="scripts/utilities/spinner.js"></script>

    <!-- Lógica da página -->
    <script>
        document.getElementById('resetForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            if (!email) {
                toast('Informe seu e-mail.', 'error'); // toast.js
                return;
            }

            const formData = new FormData();
            formData.append('email', email);

            showSpinner(); // spinner.js

            try {
                const res = await fetch('api/resetPasswordProxy.php', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                hideSpinner();

                toast(res.message || 'Erro ao recuperar senha.',
                    res.status === 'success' ? 'success' : 'error');

                if (res.status === 'success') {
                    setTimeout(() => (window.location.href = 'login.php'), 3000);
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