if (!window.__inactivityInitialized) {
    window.__inactivityInitialized = true;

    let idleTime = 0;
    const maxIdleTime = 240; // segundos
    let idleInterval = null;

    function resetIdleTime() {
        idleTime = 0;
    }

    function logoutUser() {
        if (!window.__inactivityHandled) {
            window.__inactivityHandled = true;

            // Para o contador
            clearInterval(idleInterval);

            // Alerta e redirecionamento
            alert("Sessão expirada! Você será deslogado.");
            window.location.href = 'login.php';
        }
    }

    // Inicia o contador
    idleInterval = setInterval(() => {
        idleTime++;
        if (idleTime >= maxIdleTime) {
            logoutUser();
        }
    }, 1000);

    // Resetar contador com qualquer atividade
    window.addEventListener('mousemove', resetIdleTime);
    window.addEventListener('keypress', resetIdleTime);
    window.addEventListener('click', resetIdleTime);
    window.addEventListener('scroll', resetIdleTime);
}
