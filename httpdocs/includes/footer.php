<footer id="footer">
    <div class="secFooter">
        <div class="textFooter">
            <div class="textF"><a href="#" onclick="openModal('modalLGPD')">Lei Geral de Proteção de Dados</a></div>
            <div class="textF"><a href="#" onclick="openModal('modalTermos')">Termos de Uso</a></div>
            <div class="textF"><a href="#" onclick="openModal('modalPrivacidade')">Política de Privacidade</a></div>
        </div>
        <div class="TextFo">© 2025 Consulta Empreendimentos. Todos os direitos reservados.</div>
    </div>
</footer>

<!-- Modais -->
<div id="modalLGPD" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalLGPD')">&times;</span>
        <iframe src="https://www.lgpd.ms.gov.br/wp-content/uploads/2021/11/Cartilha_LGPD-com-links.pdf" width="100%"
            height="500px"></iframe>
    </div>
</div>

<div id="modalTermos" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalTermos')">&times;</span>
        <iframe src="includes/modalTerms.html" width="100%" height="500px"></iframe>
    </div>
</div>

<div id="modalPrivacidade" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalPrivacidade')">&times;</span>
        <iframe src="includes/modalPrivacy.html" width="100%" height="500px"></iframe>
    </div>
</div>

<!-- Scripts de Utilidades -->
<script src="scripts/utilities/accessibility.js"></script>
<script src="scripts/utilities/modal.js"></script>
<script src="scripts/utilities/inactivity.js"></script>
<script src="scripts/utilities/dropdown.js"></script>
<script src="scripts/utilities/blockMobile.js"></script>