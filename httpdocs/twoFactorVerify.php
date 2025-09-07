<?php  ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Verificação 2FA</title>
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <link rel="stylesheet" href="../httpdocs/css/user/twoFactor.css">
</head>

<body>
    <section class="container">
        <div class="container-content">
            <h2 class="title">Autenticação de Segurança</h2>

            <form id="codeForm">
                <p>Informe o código que foi enviado ao seu e-mail:</p>
                <input class="input-primary" type="text" name="codigo" id="codigo" maxlength="6" required>
                <button type="submit" class="btn-primary">Verificar</button>
            </form>

            <div class="attempts" id="tentativasRestantes"></div>

            <p>Ainda não recebeu o código?</p>
            <button id="reenviarCodigo" class="btn-primary">
                Reenviar Código
            </button>
        </div>
    </section>

    <!-- Utilitários -->
    <script src="scripts/utilities/toast.js"></script>
    <script src="scripts/utilities/spinner.js"></script>

    <!-- Lógica da página -->
    <script>
        document.getElementById('codeForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const codigo = document.getElementById('codigo').value.trim();

            showSpinner();

            fetch('../backend/php/public/twoFactorVerify.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `codigo=${encodeURIComponent(codigo)}`
            })
                .then(r => r.json())
                .then(({ status, message, redirect, tentativas }) => {
                    hideSpinner();

                    toast(message, status === 'success' ? 'success' : 'error');

                    if (status === 'success') {
                        setTimeout(() => (window.location.href = 'home.php'), 2000);
                    } else {
                        if (tentativas !== undefined) {
                            document.getElementById('tentativasRestantes').textContent =
                                `Tentativas restantes: ${tentativas}`;
                        }
                        if (redirect) {
                            setTimeout(() => (window.location.href = redirect), 3000);
                        }
                    }
                })
                .catch(() => {
                    hideSpinner();
                    toast('Erro de conexão. Tente novamente.', 'error');
                });
        });

        document.getElementById('reenviarCodigo').addEventListener('click', () => {
            showSpinner();
            toast('Reenviando código...', 'info');

            fetch('../backend/php/public/twoFactorStart.php')
                .then(r => r.json())
                .then(({ status, message }) => {
                    hideSpinner();
                    toast(message || 'Código reenviado com sucesso.', status === 'success' ? 'success' : 'error');
                })
                .catch(() => {
                    hideSpinner();
                    toast('Erro ao reenviar código. Tente novamente.', 'error');
                });
        });
    </script>
</body>

</html>