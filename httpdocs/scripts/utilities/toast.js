function showToast(message, duration = 3000) {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerText = message;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 10);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Expor uma função global chamada toast()
function toast(message, type = 'info') {
    const emoji = {
        success: '✅ ',
        error: '❌ ',
        info: 'ℹ️ '
    };

    const prefix = emoji[type] || '';
    showToast(prefix + message);
}
