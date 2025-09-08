document
  .getElementById("registerForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    formData.append("submit", "true");

    // Funções auxiliares
    function setFieldError(id, message) {
      const el = document.getElementById(id);
      if (el) el.textContent = message;
    }

    function clearFieldErrors() {
      document
        .querySelectorAll(".input-error")
        .forEach((el) => (el.textContent = ""));
    }

    clearFieldErrors();

    const email = formData.get("email");
    const confirmEmail = formData.get("confirmEmail");
    const password = formData.get("password");
    const confirmPassword = formData.get("confirmPassword");

    let hasError = false;

    if (email !== confirmEmail) {
      setFieldError("email-error", "❌ E-mails não coincidem.");
      hasError = true;
    }

    if (password !== confirmPassword) {
      setFieldError("confirmPassword-error", "❌ Senhas não coincidem.");
      hasError = true;
    }

    if (hasError) return;

    showSpinner();

    try {
      const response = await fetch(
        "../backend/php/public/registerUser.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const data = await response.json();
      hideSpinner();

      if (data.status === "success") {
        toast(
          "✅ Cadastro realizado com sucesso! Redirecionando...",
          "success"
        );
        setTimeout(() => (window.location.href = "login.php"), 1500);
      } else {
        const msg = data.message.toLowerCase();

        if (msg.includes("cpf")) {
          setFieldError("cpf-error", `❌ ${data.message}`);
        } else if (msg.includes("email")) {
          setFieldError("email-error", `❌ ${data.message}`);
        } else if (msg.includes("senha")) {
          setFieldError("password-error", `❌ ${data.message}`);
        } else {
          toast(`❌ ${data.message}`, "error");
        }
      }
    } catch (err) {
      console.error(err);
      hideSpinner();
      toast("Erro de conexão. Tente novamente.", "error");
    }
  });
