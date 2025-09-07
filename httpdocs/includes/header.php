<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../backend/php/config/database.php';

$foto = "assets/img/defaultUser.svg";
$nome = "Usuário";

if (isset($_SESSION['user_cpf'])) {
    $cpf = $_SESSION['user_cpf'];
    $stmt = $mysqli->prepare("SELECT primeiro_nome, foto_perfil FROM pessoa_fisica WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $stmt->bind_result($primeiro_nome, $foto_perfil);

    if ($stmt->fetch()) {
        $nome = $primeiro_nome;

        // caminho de FS correto: sobe 1 nível (de includes/ para httpdocs/)
        $uploadsFs = dirname(__DIR__) . '/assets/uploads/'; // httpdocs/assets/uploads/
        if ($foto_perfil && is_file($uploadsFs . $foto_perfil)) {
            $foto = 'assets/uploads/' . rawurlencode($foto_perfil); // URL relativa à página
        }
    }
    $stmt->close();
}
?>

<header id="header">
    <a href="home.php">
        <div class="logo-titulo">
            <div class="logo">
                <img src="../httpdocs/assets/img/buildings.png" alt="Logo Portal Consulta">
            </div>
            <h1>Portal Consulta<br>Empreendimentos</h1>
        </div>
    </a>

    <nav class="navbar">
        <ul>
            <li><a href="contact.php">Contato</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="aboutUs.php" class="sobre-nos">Sobre-nós</a></li>
            <li>
                <div class="accessibility">
                    <img src="../httpdocs/assets/img/accessibility.png" alt="Acessibilidade" id="accessibilityBtn"
                        onclick="toggleAccessibilityMenu()">
                    <div id="accessibilityMenu" class="dropdown-menu">
                        <button onclick="increaseFontSize()">A+</button>
                        <button onclick="resetFontSize()">A</button>
                        <button onclick="decreaseFontSize()">A-</button>
                        <button onclick="resetToDefault()">Padrão</button>
                        <button onclick="toggleBlackAndWhite()">P&B</button>
                        <button onclick="toggleContrast()">Contraste</button>
                    </div>
                </div>
            </li>
        </ul>
    </nav>

    <div class="usuario">
        <div class="perfil" id="userButton" onclick="toggleDropdown()">
            <img src="<?= htmlspecialchars($foto) ?>?v=<?= time() ?>" alt="Foto de perfil" class="foto-perfil">

            <span>Olá, <?= htmlspecialchars($nome) ?></span>
        </div>

        <div class="dropdown-content" id="userDropdown">
            <button onclick="toggleVlibrasAside()">Ativar Libras</button>
            <a href="profile.php">Perfil</a>
            <a href="#" onclick="logout()" class="logout">Sair</a>
        </div>
    </div>
</header>

<aside id="vlibrasAside" class="vlibras-aside">
    <div class="vlibras-content">
        <button class="close-btn" onclick="toggleVlibrasAside()">×</button>
        <h2>O que é o VLibras?</h2>
        <p>Como funciona:
            O VLibras é uma suite de ferramentas utilizadas na tradução
            automática do Português para a Lingua Brasileira de Sinais. É possivel
            utilizar essas ferramentas tanto no computador Desktop quanto em
            smartphones e tablets.
            <br>
            Passo a passo:
            Após instalar o plugin, selecione o texto que deseja ser traduzido pelo
            VLibras.
            Clique no texto selecionado, com o botão direito do mouse.
            E clique na opcão TRADUZIR "Texto selecionado" PARA LIBRAS.
        </p>
        <img src="../httpdocs/assets/img/vlibras-img.png" alt="Ilustração do VLibras" class="vlibras-img">
        <div class="btn-group">
            <a href="https://www.vlibras.gov.br/" target="_blank">Site do VLibras</a>
            <a href="https://chromewebstore.google.com/detail/pgmmmoocgnompmjoogpnkmdohpelkpne?utm_source=item-share-cb"
                target="_blank">Extensão Chrome</a>
        </div>
    </div>
</aside>

<script src="scripts/utilities/vlibras.js"></script>