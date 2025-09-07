<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <link rel="stylesheet" href="../httpdocs/css/pages/municipalRegistration.css">
    <title>Portal Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <main class="container">
        <header class="header-title bg-primary">
            <h1 class="title">Inscrições Municipais</h1>

            <form id="buscaForm" class="form-header">
                <label for="opcao" class="subtitle">Selecione uma opção:</label>

                <select name="opcao" id="opcao" class="select-secundary">
                    <option value="im" selected>Inscrição Municipal</option>
                    <option value="cnpj">CPF ou CNPJ</option>
                    <option value="razao_social">Nome ou Razão Social</option>
                </select>

                <input name="dados" id="dados" type="text" class="select-secundary" placeholder="Digite o valor"
                    required>

                <button type="submit" class="btn-secundary">Buscar</button>
            </form>
        </header>

        <table id="tabela">
            <thead>
                <tr>
                    <th>Inscrição Municipal</th>
                    <th>CPF/CNPJ</th>
                    <th>Nome/Razão Social</th>
                    <th>Situação</th>
                    <th>Ficha Cadastral</th>
                    <th>Débitos</th>
                    <th>Alvarás e Certidões</th>
                    <th>Licenças</th>
                </tr>
            </thead>
            <tbody id="resultado">
                <!-- preenchido via JS -->
            </tbody>
        </table>
    </main>

    <?php include_once 'includes/footer.php'; ?>

    <!-- JS da página -->
    <script>
    document.getElementById('buscaForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const opcao = document.getElementById('opcao').value;
        const dados = document.getElementById('dados').value.trim();
        const tabela = document.getElementById('resultado');

        if (!dados) return;

        showSpinner(); // spinner.js (carregado pelo footer)

        fetcg('../backend/php/public/handleInscricaoProxy.php', {
                method: 'POST',
                body: new URLSearchParams({
                    opcao,
                    dados
                })
            })
            .then(r => r.json())
            .then(({
                status,
                resultados
            }) => {
                tabela.innerHTML = '';

                if (status === 'success' && resultados.length) {
                    resultados.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.inscricao_municipal}</td>
                            <td>${row.cnpj || '-'}</td>
                            <td>${row.razao_social}</td>
                            <td>${row.situacao}</td>

                            <td>
                                <a class="link-tabela"
                                   href="api/generateRegistrationFormPDFProxy.php?im=${row.inscricao_municipal}"
                                   target="_blank">
                                   Ficha Cadastral
                                </a>
                            </td>

                            <td>
                                <a class="link-tabela"
                                   href="debtCheck.php?im=${row.inscricao_municipal}">
                                   Ver Débitos
                                </a>
                            </td>

                            <td>
                                <a class="link-tabela"
                                   href="alvarasCertidoes.php?im=${row.inscricao_municipal}">
                                   Ver Alvarás e Certidões
                                </a>
                            </td>

                            <td>
                                <a class="link-tabela"
                                   href="licensing.php?im=${row.inscricao_municipal}">
                                   Ver Licenças
                                </a>
                            </td>
                        `;
                        tabela.appendChild(tr);
                    });
                } else {
                    tabela.innerHTML = '<tr><td colspan="8">Nenhum resultado encontrado.</td></tr>';
                }
            })
            .catch(() => {
                tabela.innerHTML = '<tr><td colspan="8">Erro ao buscar dados.</td></tr>';
            })
            .finally(hideSpinner);
    });
    </script>
    <script src="scripts/utilities/spinner.js"></script>
</body>

</html>