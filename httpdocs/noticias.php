<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
include_once 'config.php'; 
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Notícias</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/pages/noticias.css">
	<title>Notícias | Acompanhe as Novidades | Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>
    <article>
        <h1 class="title" >Notícias</h1>
        <div id="noticias-container"></div>
    </article>


    <script>
    fetch('api/noticiasProxy.php')
        .then(res => res.json())
        .then(noticias => {
            const container = document.getElementById('noticias-container');
            noticias.forEach(n => {
                const div = document.createElement('div');
                div.className = 'notice';
                div.innerHTML = `
                        <img src="${n.imagem}" alt="${n.titulo}" class="notice-img">
                        <h2>${n.titulo}</h2>
                    `;
                div.onclick = () => {
                    window.location.href = `noticia.php?id=${n.id}`;
                };
                container.appendChild(div);
            });
        });
    </script>

    <?php include_once 'includes/footer.php'; ?>

    <script src="../scripts/util/acessibility.js"></script>
    <script src="../scripts/util/modal.js"></script>
    <script src="../scripts/util/inativity.js"></script>
    <script src="../scripts/util/dropdown.js"></script>
</body>

</html>