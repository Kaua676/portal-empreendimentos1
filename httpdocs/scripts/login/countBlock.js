let intervaloBloqueio;

function mostrarBloqueio(tempoRestante) {
    const divBloqueio = document.getElementById('bloqueio-tentativas');
    if (!divBloqueio) return;

    clearInterval(intervaloBloqueio); // Evita múltiplos timers

    divBloqueio.innerHTML = `Você foi bloqueado. Tente novamente em ${formatarTempo(tempoRestante)}.`;

    intervaloBloqueio = setInterval(() => {
        tempoRestante--;
        if (tempoRestante <= 0) {
            clearInterval(intervaloBloqueio);
            divBloqueio.innerHTML = 'Você pode tentar novamente.';
        } else {
            divBloqueio.innerHTML = `Você foi bloqueado. Tente novamente em ${formatarTempo(tempoRestante)}.`;
        }
    }, 1000);
}

function formatarTempo(segundos) {
    const minutos = Math.floor(segundos / 60);
    const segundosRestantes = segundos % 60;
    return `${minutos}m ${segundosRestantes < 10 ? '0' : ''}${segundosRestantes}s`;
}