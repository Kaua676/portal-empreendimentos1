<?php  ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../httpdocs/css/user/register.css">
    <link rel="stylesheet" href="../httpdocs/css/styles.css">
    <title>Cadastre-se | Criar Conta | Consulta Empreendimentos</title>
</head>

<body>
    <div class="container-register">
        <div class="container-form">
            <form class="form-cadastro" id="registerForm">
                <div class="header-form">
                    <h2>Cadastre-se</h2>
                    <button type="button" class="btn-primary" id="btn-entrar">Entrar</button>
                </div>

                <!-- DADOS PESSOAIS -->
                <div class="container-inputs">
                    <div class="input-group">
                        <div>
                            <label>Seu nome</label>
                            <input class="input-primary" id="nome" name="nome" type="text" placeholder="Primeiro Nome"
                                required>
                        </div>
                        <div>
                            <label>Sobrenome</label>
                            <input class="input-primary" id="sobrenome" name="sobrenome" type="text"
                                placeholder="Sobrenome" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <div>
                            <label>CPF</label>
                            <input class="input-primary" id="documento" name="documento" maxlength="18"
                                placeholder="Digite seu CPF" required>
                            <small class="input-error" id="cpf-error"></small>
                        </div>
                        <div>
                            <label>Data de nascimento</label>
                            <input class="input-primary" id="nasc" name="nasc" placeholder="DD/MM/AAAA" required>
                        </div>
                        <div>
                            <label>Telefone</label>
                            <input class="input-primary" id="number" name="number" placeholder="(xx) xxxxx-xxxx"
                                required>
                        </div>
                    </div>
                </div>

                <!-- ENDEREÇO -->
                <div class="container-inputs">
                    <div class="input-group">
                        <div>
                            <label>CEP</label>
                            <input class="input-primary" id="cep" name="cep" placeholder="Digite seu CEP" required>
                        </div>
                        <div>
                            <label>Endereço</label>
                            <input class="input-primary" id="rua" name="rua" placeholder="Rua" required>
                        </div>
                        <div>
                            <label>Número</label>
                            <input class="input-primary" id="numero" name="numero" placeholder="Número" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <div>
                            <label>Complemento</label>
                            <input class="input-primary" id="complemento" name="complemento" placeholder="Complemento">
                        </div>
                        <div>
                            <label>Bairro</label>
                            <input class="input-primary" id="bairro" name="bairro" placeholder="Bairro" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <div>
                            <label>Cidade</label>
                            <input class="input-primary" id="cidade" name="cidade" placeholder="Cidade" required>
                        </div>
                        <div>
                            <label>Estado</label>
                            <select class="select-primary" name="estado">
                                <option value="ms">MS</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- EMAIL + SENHA -->
                <div class="container-inputs">
                    <div class="input-group">
                        <div>
                            <label>E-mail</label>
                            <input class="input-primary" id="email" name="email" type="email"
                                placeholder="Digite seu E-mail" required>
                            <small class="input-error" id="email-error"></small>
                        </div>
                        <div>
                            <label>Confirmar E-mail</label>
                            <input class="input-primary" id="confirmEmail" name="confirmEmail" type="email"
                                placeholder="Confirme seu E-mail" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <div>
                            <label>Senha</label>
                            <input class="input-primary" id="password" name="password" type="password"
                                placeholder="••••••••" required>
                            <small class="input-error" id="password-error"></small>
                        </div>
                        <div>
                            <label>Confirmar Senha</label>
                            <input class="input-primary" id="confirmPassword" name="confirmPassword" type="password"
                                placeholder="••••••••" required>
                            <small class="input-error" id="confirmPassword-error"></small>
                        </div>
                    </div>
                </div>

                <!-- REGRAS DE SENHA -->
                <div class="password-requirements">
                    <p>A senha deve conter:</p>
                    <ul>
                        <li id="length_validate">Pelo menos 8 caracteres</li>
                        <li id="uppercase_validate">Pelo menos uma letra maiúscula (A-Z)</li>
                        <li id="number_validate">Pelo menos um número (0-9)</li>
                        <li id="special_validate">Pelo menos um caractere especial (!@#$%^&*)</li>
                    </ul>
                </div>

                <!-- ACEITE DE TERMOS -->
                <div class="accept-termos">
                    <input class="input-primary" type="checkbox" name="termoslgpd" required>
                    <label>Eu aceito a <a href="#" onclick="openModal('modalLGPD')">Lei Geral de Proteção de Dados</a>,
                        <a href="#" onclick="openModal('modalPrivacidade')">Política de Privacidade</a> e <a href="#"
                            onclick="openModal('modalTermos')">Termos de Uso</a></label>
                </div>

                <button id="submit" type="submit" class="btn-primary submit">Continuar</button>

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
                        <iframe src="/includes/modalTerms.html" width="100%" height="500px"></iframe>
                    </div>
                </div>

                <div id="modalPrivacidade" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('modalPrivacidade')">&times;</span>
                        <iframe src="/includes/modalPrivacy.html" width="100%" height="500px"></iframe>
                    </div>
                </div>
            </form>
        </div>

        <!-- WAVES SVG (inalteradas) -->
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
    </div>

    <!-- BIBLIOTECAS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>

    <!-- Utilitários -->
    <script src="scripts/utilities/modal.js"></script>
    <script src="scripts/utilities/toast.js"></script>
    <script src="scripts/utilities/spinner.js"></script>

    <!-- Scripts da tela de registro -->
    <script src="scripts/register/cpf.js"></script>
    <script src="scripts/register/password.js"></script>
    <script src="scripts/register/autoFillAddress.js"></script>
    <script src="scripts/register/navigation.js"></script>
    <script src="scripts/register/handler.js"></script>


</body>

</html>