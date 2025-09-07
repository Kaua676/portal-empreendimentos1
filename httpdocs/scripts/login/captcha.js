function generateCaptcha() {
    const characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // sem O/0 e I/1
    let captcha = '';
    for (let i = 0; i < 6; i++) {
        captcha += characters.charAt(Math.floor(Math.random() * characters.length));
    }

    document.getElementById('captchaCode').innerText = captcha;
    document.getElementById('captchaCodeHidden').value = captcha;
}

// Gera o CAPTCHA ao carregar a página
document.addEventListener('DOMContentLoaded', generateCaptcha);
