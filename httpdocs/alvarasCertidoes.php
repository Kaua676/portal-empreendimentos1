<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../httpdocs/css/pages/alvarasCertidoes.css">
    <link rel="stylesheet" href="../httpdocs/css/styles.css">


    <title>Portal Consulta Empreendimentos</title>
</head>

<body>

    <?php include_once 'includes/header.php'; ?>

    <section class="container">
        <form class="container-content bg-primary" id="consultaForm">
            <h1 class="title">Consulta de Alvará</h1>

            <h2 class="subtitle">Cadastro</h2>
            <div id="opcao" class="card-content">
                <select name="opcao" class="select-secundary" autofocus>
                    <option selected value="im">Inscrição Municipal</option>
                    <option value="cnpj">CPF ou CNPJ</option>
                </select>
                <input class="input-secundary" name="dados" type="text">
            </div>

            <h2 class="subtitle">Tipo de Certidão</h2>
            <div>
                <select class="select-secundary" name="tipoCertidao">
                    <option selected></option>
                    <option value="alvara">Alvará de Localização e Funcionamento</option>
                    <option value="alvaraprovisorio">
                        Alvará de Localização e Funcionamento Provisório
                    </option>
                </select>

                <button type="button" class="btn-secundary" onclick="buscarInscricao()">
                    BUSCAR
                </button>
            </div>

            <div id="resultado"></div>
            </fieldset>
        </form>
    </section>

    <?php include_once 'includes/footer.php'; ?>

    <!-- JS da própria página -->
    <script>
    function buscarInscricao() {
        const formData = new FormData(document.getElementById('consultaForm'));

        fetcg('../backend/php/public/handleAlvarasProxy.php', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.text())
            .then(html => document.getElementById('resultado').innerHTML = html)
            .catch(() => console.error('Erro ao processar a solicitação.'));
    }
    </script>
</body>

</html>