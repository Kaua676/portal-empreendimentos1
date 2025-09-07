document.getElementById('cep').addEventListener('blur', function () {
    const cep = this.value.replace(/\D/g, '');
    if (cep.length !== 8) return;

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(d => {
            if (!d.erro) {
                document.getElementById('rua').value = d.logradouro;
                document.getElementById('bairro').value = d.bairro;
                document.getElementById('cidade').value = d.localidade;
            } else {
                toast('CEP não encontrado.', 'error');
            }
        })
        .catch(() => toast('Erro ao buscar o CEP.', 'error'));
});
