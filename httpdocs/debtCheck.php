<?php
require_once __DIR__ . '/../backend/php/public/auth.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../httpdocs/css/pages/debtCheck.css">
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <title>Consulta de Débitos | Verifique Situação Fiscal | Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <section class="container">
        <form id="consultaForm" class="container-content bg-primary" action="api/gerarBoletoBradesco.php" method="POST"
            target="_blank">

            <h1 class="title">Consulta de Débitos</h1>

            <h2 class="subtitle">Cadastro</h2>
            <div>
                <select name="opcao" class="select-secundary" autofocus>
                    <option value="im" selected>Inscrição Municipal</option>
                    <option value="cnpj">CPF ou CNPJ</option>
                </select>

                <input name="dados" type="text" class="input-secundary" />

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

        fetch('../backend/php/public/handleDebitos.php', {
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