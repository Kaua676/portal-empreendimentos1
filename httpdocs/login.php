<?php
session_start();

$mensagem_login = $_SESSION['login_message'] ?? null;
unset($_SESSION['login_message']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <link rel="stylesheet" href="../httpdocs/css/user/login.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Entrar | Acesso ao Sistema | Consulta Empreendimentos</title>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator
            .userAgent);

        if (isMobileDevice) {
            window.location.href = "emManutencao.php";
        }
    });
    </script>

</head>

<body>
    <div class="container-login">
        <?php if ($mensagem_login): ?>
        <div id="modal-login-aviso" class="modal-login-aviso">
            <div class="modal-box">
                <div class="modal-timer-bar"></div>
                <p><?= htmlspecialchars($mensagem_login) ?></p>
            </div>
        </div>
        <?php endif; ?>


        <!-- WAVES -->
        <div class="wave-container">
            <!-- Onda 1 -->
            <svg class="waves wave1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#e8f1f2" fill-opacity="1"
                    d="M0,224L40,213.3C80,203,160,181,240,181.3C320,181,400,203,480,197.3C560,192,640,160,720,133.3C800,107,880,85,960,101.3C1040,117,1120,171,1200,202.7C1280,235,1360,245,1400,250.7L1440,256L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
                </path>
            </svg>
            <!-- Onda 2 -->
            <svg class="waves wave2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#1b98e0" fill-opacity="1"
                    d="M0,288L48,272C96,256,192,224,288,218.7C384,213,480,235,576,234.7C672,235,768,213,864,202.7C960,192,1056,192,1152,181.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
            <!-- Onda 3 -->
            <svg class="waves wave3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#247ba0" fill-opacity="1"
                    d="M0,192L80,213.3C160,235,320,277,480,293.3C640,309,800,299,960,272C1120,245,1280,203,1360,181.3L1440,160L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
                </path>
            </svg>
            <!-- Onda 4 -->
            <svg class="waves wave4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#006494" fill-opacity="1"
                    d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,250.7C1248,256,1344,288,1392,304L1440,320L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </div>

        <!-- ÁREA DE LOGIN -->
        <div class="login-container">

            <!-- TÍTULOS -->
            <h2 class="title">Portal Consulta Empreendimentos</h2>
            <p class="subtitle">Bem-vindo! Faça o login para continuar.</p>

            <!-- FORMULÁRIO -->
            <form id="loginForm" class="container-form">
                <div class="formulario">
                    <div class="input-group">
                        <label class="label-documento" for="documento">CPF</label>
                        <div class="input-wrapper">
                            <i class="bx bxs-user"></i>
                            <input name="documento" class="input-primary" type="text" id="documento" maxlength="18"
                                placeholder="CPF" autocomplete="off">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="label-password" for="password">Senha</label>
                        <div class="input-wrapper">
                            <i class="bx bxs-key"></i>
                            <input class="input-primary" type="password" id="password" name="password"
                                placeholder="••••••••">
                            <i class="bi bi-eye-fill" id="olho" onclick="mostrarSenha()"></i>
                        </div>
                    </div>


                    <div id="bloqueio-tentativas" style="color:red;"></div>

                    <a class="forgot" href="forgotPassword.php">Esqueceu a senha?</a>

                    <div class="captcha-container">
                        <p id="captchaCode" style="user-select:none;"></p>
                        <i class="bi bi-arrow-clockwise" onclick="generateCaptcha()"></i>
                        <input type="text" name="captchaInput" id="captchaInput" placeholder="Digite o CAPTCHA"
                            class="input-primary">
                        <input type="hidden" name="captcha_code" id="captchaCodeHidden">
                        <p id="captchaMessage"></p>
                    </div>

                    <button id="submit" class="btn-primary" type="submit">Entrar</button>

                    <p>Ainda não possui uma conta? <a href="register.php">Criar conta</a></p>
                </div>
            </form>

            <!-- TERMOS -->
            <div class="termos">
                <ul>
                    <li><a href="#" onclick="openModal('modalLGPD')">Lei Geral de Proteção de Dados</a></li>
                    <li><a href="#" onclick="openModal('modalTermos')">Termos de Uso</a></li>
                    <li><a href="#" onclick="openModal('modalPrivacidade')">Política de Privacidade</a></li>
                </ul>
            </div>

            <!-- MODAIS -->
            <div id="modalLGPD" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalLGPD')">&times;</span>
                    <iframe src="https://www.lgpd.ms.gov.br/wp-content/uploads/2021/11/Cartilha_LGPD-com-links.pdf"
                        width="100%" height="500px"></iframe>
                </div>
            </div>

            <div id="modalTermos" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalTermos')">&times;</span>
                    <iframe src="./includes/modalTerms.html" width="100%" height="500px"></iframe>
                </div>
            </div>

            <div id="modalPrivacidade" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalPrivacidade')">&times;</span>
                    <iframe src="./includes/modalPrivacy.html" width="100%" height="500px"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("modal-login-aviso");

        if (modal) {
            // Fecha ao clicar fora da caixa
            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    fecharModal();
                }
            });

            // Fecha automaticamente em 5 segundos
            setTimeout(() => {
                fecharModal();
            }, 5000);

            function fecharModal() {
                modal.style.opacity = "0";
                setTimeout(() => modal.remove(), 300);
            }
        }
    });
    </script>



    <!-- LIBS EXTERNAS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>

    <!-- UTILITÁRIOS -->
    <script src="scripts/utilities/modal.js"></script>
    <script src="scripts/utilities/accessibility.js"></script>
    <script src="scripts/utilities/toast.js"></script>

    <!-- SCRIPTS DO LOGIN -->
    <script src="scripts/login/cpf.js"></script>
    <script src="scripts/login/password.js"></script>
    <script src="scripts/login/captcha.js"></script>
    <script src="scripts/login/countBlock.js"></script>
    <script src="scripts/login/showPassword.js"></script>
    <script src="scripts/login/handler.js"></script>
</body>

</html>