document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const documento = document.getElementById("documento").value.trim();
    const senha = document.getElementById("password").value;
    const captchaInput = document.getElementById("captchaInput").value.trim();
    const captchaCode = document.getElementById("captchaCode").innerText;
    const captchaHidden = document.getElementById("captchaCodeHidden");
    captchaHidden.value = captchaCode;

    // Validações front-end
    if (!validarCPF(documento)) {
      showToast("CPF inválido.", 4000);
      return;
    }

    if (!validarSenha(senha)) {
      showToast(
        "Senha deve conter no mínimo 8 caracteres, uma letra maiúscula, um número e um caractere especial.",
        5000
      );
      return;
    }

    if (captchaInput !== captchaCode) {
      showToast("CAPTCHA incorreto.", 4000);
      return;
    }

    const formData = new FormData(this);
    formData.append("submit", "true");

    fetch("../backend/php/public/loginController.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "blocked") {
          mostrarBloqueio(data.tempoRestante);
          showToast(
            `Você foi bloqueado por ${data.tempoRestante} segundos.`,
            6000
          );
        } else if (data.status === "error") {
          showToast(data.message || "Erro ao processar login.", 4000);
        } else if (data.status === "change_password") {
          showToast("Você precisa alterar sua senha.", 3000);
          setTimeout(
            () =>
              (window.location.href = "../httpdocs/forcePasswordChange.php"),
            1500
          );
        } else if (data.status === "success") {
          showToast(
            "Login bem-sucedido! Enviando código de verificação...",
            2000
          );

          fetch("../backend/php/public/twoFactorStart.php")
            .then((response) => response.json())
            .then(({ status, message }) => {
              if (status === "success") {
                showToast(message || "Código enviado com sucesso.", 1500);
                setTimeout(() => {
                  window.location.href = "../httpdocs/twoFactorVerify.php";
                }, 1500);
              } else {
                showToast(message || "Erro ao enviar o código.", 4000);
              }
            })
            .catch(() => {
              showToast("Erro ao iniciar verificação em duas etapas.", 4000);
            });
        } else {
          showToast("Resposta inesperada do servidor.", 4000);
        }
      })
      .catch((error) => {
        console.error("Erro na requisição:", error);
        showToast("Erro de conexão. Verifique sua internet.", 4000);
      });
  });
