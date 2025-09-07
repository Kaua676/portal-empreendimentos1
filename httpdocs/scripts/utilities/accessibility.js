var currentFontSize = 100;
var isHighContrast = false;
var isBlackAndWhite = false;

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('accessibilityMenu').style.display = 'none';

    // Recuperar configurações salvas no localStorage
    const savedFontSize = localStorage.getItem('fontSize');
    const savedHighContrast = localStorage.getItem('highContrast');
    const savedBlackAndWhite = localStorage.getItem('blackAndWhite');

    // Aplicar configurações salvas
    if (savedFontSize) {
        currentFontSize = parseInt(savedFontSize);
        document.body.style.fontSize = currentFontSize + '%';
    }

    if (savedHighContrast === 'true') {
        isHighContrast = true;
        document.body.classList.add('high-contrast');
    }

    if (savedBlackAndWhite === 'true') {
        isBlackAndWhite = true;
        document.body.classList.add('black-and-white');
    }
});

function toggleAccessibilityMenu() {
    var menu = document.getElementById('accessibilityMenu');
    if (menu.style.display === 'flex') {
        menu.style.display = 'none';
    } else {
        menu.style.display = 'flex';
    }
}

function increaseFontSize() {
    if (currentFontSize < 150) { // Maximum font size limit
        currentFontSize += 10;
        document.body.style.fontSize = currentFontSize + '%';
        localStorage.setItem('fontSize', currentFontSize);
    }
}

function decreaseFontSize() {
    if (currentFontSize > 70) { // Minimum font size limit
        currentFontSize -= 10;
        document.body.style.fontSize = currentFontSize + '%';
        localStorage.setItem('fontSize', currentFontSize);
    }
}

function resetFontSize() {
    currentFontSize = 100;
    document.body.style.fontSize = currentFontSize + '%';
    localStorage.setItem('fontSize', currentFontSize);
}

function toggleContrast() {
    isHighContrast = !isHighContrast;
    if (isHighContrast) {
        document.body.classList.add('high-contrast');
    } else {
        document.body.classList.remove('high-contrast');
    }
    localStorage.setItem('highContrast', isHighContrast);
}

function resetContrast() {
    isHighContrast = false;
    document.body.classList.remove('high-contrast');
    localStorage.setItem('highContrast', isHighContrast);
}

function toggleBlackAndWhite() {
    isBlackAndWhite = !isBlackAndWhite;
    if (isBlackAndWhite) {
        document.body.classList.add('black-and-white');
    } else {
        document.body.classList.remove('black-and-white');
    }
    localStorage.setItem('blackAndWhite', isBlackAndWhite);
}

function resetToDefault() {
    resetContrast();
    if (isBlackAndWhite) {
        toggleBlackAndWhite(); // Remove black and white filter
    }
    resetFontSize();
    localStorage.clear(); // Limpa todas as configurações salvas
}
