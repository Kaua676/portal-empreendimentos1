<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
include_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/pages/licensing.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">

    <title>Portal Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <section class="container">
        <form id="consultaForm" class="container-content bg-primary">
            <h1 class="title">Consulta de Licenças</h1>

            <h2 class="subtitle">Cadastro</h2>
            <div>
                <select name="opcao" class="select-secundary" autofocus>
                    <option value="im" selected>Inscrição Municipal</option>
                    <option value="cnpj">CPF ou CNPJ</option>
                </select>
                <input name="dados" type="text" class="input-secundary">
            </div>

            <h2 class="subtitle">Tipo de Certidão/Licença</h2>
            <div>
                <select name="tipoCertidao" class="select-secundary">
                    <option selected></option>
                    <option value="sanitaria">Licença Sanitária</option>
                    <option value="ambiental">Licença Ambiental</option>
                </select>

                <button type="button" class="btn-secundary" onclick="buscarInscricao()">
                    BUSCAR
                </button>
            </div>
            </fieldset>

            <div id="resultado"></div>
        </form>
    </section>

    <?php include_once 'includes/footer.php'; ?>

    <!-- JS próprio da página -->
    <script>
    function buscarInscricao() {
        const formData = new FormData(document.getElementById('consultaForm'));

        fetch('api/handleLicencasProxy.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.text())
            .then(html => {
                document.getElementById('resultado').innerHTML = html;
            })
            .catch(() => console.error('Erro ao processar a solicitação.'));
    }
    </script>
</body>

</html>