<?php
require_once __DIR__ . '/../backend/php/public/auth.php';
 
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../httpdocs/css/pages/aboutUs.css">
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
	<title>Quem Somos | Consulta Empreendimentos</title>
</head>

<body>

    <?php include_once 'includes/header.php'; ?>

    <section id="container">
        <div class="descricao">
            <p>Seja bem-vindo ao Portal de Consulta Empreendimentos, criado para atender empresas e profissionais
                autônomos com eficiência e segurança. Nosso compromisso é oferecer acesso rápido e sigiloso a
                informações essenciais, garantindo que cada consulta seja realizada com total privacidade. Sabemos que
                o sucesso dos negócios depende de dados precisos e acessíveis, por isso, desenvolvemos uma plataforma
                intuitiva e confiável, que permite a obtenção das informações necessárias de forma prática e segura.
                Além de facilitar o seu acesso às informações, nosso portal se destaca pelo compromisso com a
                integridade
                e pela proteção dos dados. Atuamos com seriedade para assegurar que todas as interações respeitem a
                privacidade e a sensibilidade das informações tratadas, promovendo confiança e tranquilidade para nossos
                usuários. Conte conosco para uma experiência eficiente, transparente e sempre comprometida com o sucesso
                do seu negócio.</p>
        </div>

        <div class="card-content">
            <div class="cards">
                <div class="card-person">
                    <img src="assets/img/fotokaua.png" alt="Foto de Kauã Vicente">
                    <h2>Kauã Vicente</h2>
                    <p>Olá, meu nome é Kauã Vicente, responsável pelo front-end do site</p>
                </div>

                <div class="card-person">
                    <img src="assets/img/fotokrysten.png" alt="Foto de Krysten Yumi">
                    <h2>Krysten Yumi</h2>
                    <p>Olá, meu nome é Krysten Yumi, responsável pelo back-end do site</p>
                </div>

                <div class="card-person">
                    <img src="assets/img/fotojonas.png" alt="Foto de Jonas Shimabukuro">
                    <h2>Jonas Shimabukuro</h2>
                    <p>Olá, meu nome é Jonas Shimabukuro, responsável pelo back-end do site</p>
                </div>

                <div class="card-person">
                    <img src="assets/img/fotofelipe.png" alt="Foto de Felipe Pedrozo">
                    <h2>Felipe Pedrozo</h2>
                    <p>Olá, meu nome é Felipe Pedrozo, responsável pelo front-end do site</p>
                </div>

                <div class="card-person">
                    <img src="assets/img/fotopiettro.png" alt="Foto de Piettro Cavalli">
                    <h2>Piettro Cavalli</h2>
                    <p>Olá, meu nome é Piettro Cavalli, responsável pela segurança do site</p>
                </div>
            </div>
        </div>
    </section>

    <?php include_once 'includes/footer.php'; ?>
</body>

</html>