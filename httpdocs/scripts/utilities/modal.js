// Função para abrir o modal, fechando o anterior se necessário
function openModal(modalId) {
    closeAllModals(); // Fecha outros modais
    const modal = document.getElementById(modalId);
    modal.classList.add('show'); // Mostra o modal
}

// Função para fechar um modal específico
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('show'); // Esconde o modal
}

// Função para fechar todos os modais
function closeAllModals() {
    const modals = document.getElementsByClassName('modal');
    for (let modal of modals) {
        modal.classList.remove('show');
    }
}

// Fecha o modal quando o usuário clica fora do conteúdo
window.onclick = function (event) {
    const modals = document.getElementsByClassName('modal');
    for (let modal of modals) {
        if (event.target == modal) {
            modal.classList.remove('show');
        }
    }
};
