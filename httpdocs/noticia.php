<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
 
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Notícia</title>
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <link rel="stylesheet" href="../httpdocs/css/pages/noticia.css">
</head>

<body>
    <?php include_once 'includes/header.php'; ?>
    <article id="noticia"></article>

    <script>
    function getIdFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('id');
    }

    const id = getIdFromUrl();

    if (id) {
        fetch(`../backend/php/api/apiNoticia.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('noticia');

                if (data.erro) {
                    container.innerHTML = `<p>${data.erro}</p>`;
                } else {
                    container.innerHTML = `
                            <div class="text-title">
                                <h1>${data.titulo}</h1>
                                <p class="resumo">${data.resumo}</p>
                                <p class="data-publicacao"><strong>Publicado em:</strong> ${data.data_publicacao}</p>
                            </div>
                            <div class="img-container">
                                <img src="${data.imagem}" alt="${data.titulo}" class="noticia-img">
                            </div>
                            <div class="text-container">
                               
                                ${data.conteudo}
                            </div>
                        `;
                }
            });
    } else {
        document.getElementById('noticia').innerHTML = "<p>ID da notícia não informado.</p>";
    }
    </script>

    <?php include_once 'includes/footer.php'; ?>
</body>

</html>