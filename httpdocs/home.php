<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <link rel="stylesheet" href="../httpdocs/css/pages/home.css">
    <title>Início | Consulta Empreendimentos</title>
</head>

<body>
    <?php include_once 'includes/header.php'; ?>

    <section id="noticias-carrossel">
        <h2 class="titulo-carrossel">Últimas Notícias</h2>
        <div class="carousel-wrapper">
            <div class="carousel-container" id="carrossel-noticias">
                <button class="nav-btn left-btn">&#9664;</button>
                <button class="nav-btn right-btn">&#9654;</button>
            </div>
        </div>
    </section>

    <section id="section-cards">
        <div class="card-container">
            <div class="card">
                <a href="municipalRegistration.php">
                    <div class="title-card">
                        <img src="assets/img/background_1.jpeg" alt="Imagem de Inscrição Municipal">
                        <h2>Inscrições Municipais</h2>
                    </div>
                    <div class="description">
                        <p>Refere-se ao cadastro obrigatório de empresas ou profissionais autônomos para controle e
                            tributação municipal.</p>
                    </div>
                </a>
            </div>

            <div class="card">
                <a href="debtCheck.php">
                    <div class="title-card">
                        <img src="assets/img/background_2.jpeg" alt="Imagem de Consulta de Débitos">
                        <h2>Consultar Débitos</h2>
                    </div>
                    <div class="description">
                        <p>Permite verificar pendências financeiras, como impostos e taxas atrasadas, vinculadas a uma
                            empresa ou pessoa física junto ao município.</p>
                    </div>
                </a>
            </div>

            <div class="card">
                <a href="alvarasCertidoes.php">
                    <div class="title-card">
                        <img src="assets/img/background_3.jpeg" alt="Imagem de Alvarás e Certidões">
                        <h2>Alvarás e Certidões</h2>
                    </div>
                    <div class="description">
                        <p>Emissão de documentos que autorizam atividades comerciais (alvarás) e fornecimento de
                            certidões, como negativa de débitos, para comprovar a regularidade fiscal.</p>
                    </div>
                </a>
            </div>

            <div class="card">
                <a href="licensing.php">
                    <div class="title-card">
                        <img src="assets/img/background_4.jpeg" alt="Imagem de Licenciamentos">
                        <h2>Licenciamentos</h2>
                    </div>
                    <div class="description">
                        <p>Processo para obtenção de autorização para o funcionamento de estabelecimentos ou a
                            realização de atividades que exigem aprovação municipal.</p>
                    </div>
                </a>
            </div>

            <div class="card">
                <a href="noticias.php">
                    <div class="title-card">
                        <img src="assets/img/background_5.jpeg" alt="">
                        <h2>Noticias</h2>
                    </div>
                    <div class="description">
                        <p>Notícias em primeira mão, direcionadas ao mundo da contabilidade.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <?php include_once 'includes/footer.php'; ?>


    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const container = document.getElementById("carrossel-noticias");
        const leftBtn = document.querySelector(".left-btn");
        const rightBtn = document.querySelector(".right-btn");
        let currentIndex = 0;
        let cards = [];

        function showCard(index) {
            cards.forEach((card, i) => {
                card.classList.remove("active");
                if (i === index) {
                    setTimeout(() => {
                        card.classList.add("active");
                    }, 50);
                }
            });
        }


        fetch("../backend/php/api/apiNoticiaCarrossel.php")
            .then(response => response.json())
            .then(noticias => {
                if (noticias.length === 0) {
                    container.innerHTML = "<p>Nenhuma notícia encontrada.</p>";
                    return;
                }

                noticias.forEach((noticia, index) => {
                    const card = document.createElement("div");
                    card.className = "carousel-card";
                    if (index === 0) card.classList.add("active");

                    card.innerHTML = `
                    <a href="noticia.php?id=${noticia.id}" class="carousel-link">
                        <img src="${noticia.imagem}" alt="${noticia.titulo}" class="carousel-img">
                        <h3 class="carousel-title">${noticia.titulo}</h3>
                    </a>
                `;
                    container.appendChild(card);
                    cards.push(card);
                });

                function showCard(index) {
                    cards.forEach((card, i) => {
                        card.classList.toggle("active", i === index);
                    });
                }

                leftBtn.addEventListener("click", () => {
                    currentIndex = (currentIndex - 1 + cards.length) % cards.length;
                    showCard(currentIndex);
                });

                rightBtn.addEventListener("click", () => {
                    currentIndex = (currentIndex + 1) % cards.length;
                    showCard(currentIndex);
                });

                setInterval(() => {
                    currentIndex = (currentIndex + 1) % cards.length;
                    showCard(currentIndex);
                }, 3000);
            })
            .catch(err => {
                console.error("Erro ao carregar carrossel:", err);
                container.innerHTML = "<p>Erro ao carregar notícias.</p>";
            });
    });
    </script>
</body>

</html>