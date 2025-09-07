$(function () {
    const $doc = $('#documento');
    const applyMask = () => $doc.mask('000.000.000-00', { reverse: true });

    $doc.on('input', () => {
        const v = $doc.val().replace(/\D/g, '').substring(0, 11);
        $doc.val(v);
    }).focus(() => $doc.unmask()).blur(applyMask);

    applyMask();

    $('#number').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    $('#nasc').mask('00/00/0000');
});
