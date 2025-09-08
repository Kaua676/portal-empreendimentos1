<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../httpdocs/css/styles.css" />
    <link rel="stylesheet" href="../httpdocs/css/pages/contact.css" />
    <title>Fale Conosco | Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <section id="container">
        <div class="form-contact">
            <h2>Contato</h2>

            <form id="contactForm">
                <label>Nome</label>
                <input class="input-secundary" type="text" name="name" placeholder="Digite seu Nome" required />

                <label>E-mail</label>
                <input class="input-secundary" type="email" name="email" placeholder="Digite seu E-mail" required />

                <label>Mensagem</label>
                <textarea class="textarea-secundary" name="message" rows="10" placeholder="Digite sua mensagem"
                    required></textarea>

                <button type="submit" class="btn-primary">Enviar Mensagem</button>
            </form>
        </div>
    </section>

    <?php include_once 'includes/footer.php'; ?>

    <script src="scripts/utilities/spinner.js"></script>
    <script src="scripts/utilities/toast.js"></script>



    <script>
    document.getElementById('contactForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const data = {
            name: form.name.value.trim(),
            email: form.email.value.trim(),
            message: form.message.value.trim()
        };

        if (!data.name || !data.email || !data.message) {
            toast('Preencha todos os campos.', 'error');
            return;
        }

        showSpinner();

        try {
            const res = await fetch('../backend/php/api/apiContact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(r => r.json());

            hideSpinner();
            toast(res.message, res.status === 'success' ? 'success' : 'error');
            if (res.status === 'success') form.reset();
        } catch (err) {
            hideSpinner();
            toast('Erro inesperado ao enviar. Tente novamente.', 'error');
            console.error(err);
        }
    });
    </script>
</body>

</html>