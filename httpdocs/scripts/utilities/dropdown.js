function toggleDropdown() {
    const dropdown = document.getElementById("userDropdown");
    const perfil = document.getElementById("userButton");

    dropdown.classList.toggle("show");
    perfil.classList.toggle("expandido");

    if (dropdown.classList.contains("show")) {
        dropdown.style.width = perfil.offsetWidth + "px";
    } else {
        dropdown.style.width = "";
    }
}


function logout() {
    if (confirm("Tem certeza de que deseja sair?")) {
        // Redireciona para logout.php para destruir a sessão
        window.location.href = "api/logoutProxy.php";

    } else {
        toggleDropdown();
    }
}

window.onload = function () {
    loadUserName();
};