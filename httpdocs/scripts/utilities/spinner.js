function showSpinner() {
    let existing = document.querySelector('.spinner-overlay');
    if (!existing) {
        const overlay = document.createElement('div');
        overlay.className = 'spinner-overlay';

        const spinner = document.createElement('div');
        spinner.className = 'spinner';

        overlay.appendChild(spinner);
        document.body.appendChild(overlay);
    }
    document.querySelector('.spinner-overlay').style.display = 'flex';
}

function hideSpinner() {
    const overlay = document.querySelector('.spinner-overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}  