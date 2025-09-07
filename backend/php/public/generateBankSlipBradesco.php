<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['valor']) && isset($_POST['descricao']) && isset($_POST['vencimento']) && isset($_POST['opcao']) && isset($_POST['dados'])) {
        $valor_cobrado = $_POST['valor'];
        $descricao = $_POST['descricao'];
        $vencimento = $_POST['vencimento'];
        $opcao = $_POST['opcao']; // Recuperando a opção selecionada do formulário
        $dados = $_POST['dados']; // Recuperando os dados (IM ou CNPJ) do formulário

        $taxa_boleto = 2.95;
        $data_venc = DateTime::createFromFormat('Y-m-d', $vencimento)->format('d-m-Y');
        $valor_cobrado = str_replace(",", ".", $valor_cobrado);
        $valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');

        // Construir a consulta SQL base
        $sql = "SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social, 
                endereco.cep, endereco.rua, endereco.numero, endereco.bairro, endereco.cidade, endereco.uf
                FROM pj
                INNER JOIN cnpj ON pj.cnpj_id = cnpj.id
                INNER JOIN endereco ON pj.endereco_id = endereco.id
                WHERE ";

        // Adicionar a condição baseada na opção selecionada (im ou cnpj)
        if ($opcao === 'im') {
            $sql .= "pj.im = ?";
        } elseif ($opcao === 'cnpj') {
            $sql .= "cnpj.cnpj = ?";
        } else {
            echo "Opção inválida.";
            exit;
        }

        // Preparar e executar a consulta SQL
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $dados);
            $stmt->execute();
            $result = $stmt->get_result();

            // Inicializando a variável $dadosboleto
            $dadosboleto = array();

            $dadosboleto["nosso_numero"] = "75896452";
            $dadosboleto["numero_documento"] = $dadosboleto["nosso_numero"];
            $dadosboleto["data_vencimento"] = $data_venc;
            $dadosboleto["data_documento"] = date("d/m/Y");
            $dadosboleto["data_processamento"] = date("d/m/Y");
            $dadosboleto["valor_boleto"] = $valor_boleto;

            // Exibir os resultados na tela
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $dadosboleto["sacado"] = $row["razao_social"];
                $dadosboleto["endereco1"] = $row["rua"] . ' ' . $row["numero"] . ' ' . $row["bairro"];
                $dadosboleto["endereco2"] = $row["cidade"] . ' ' . $row["uf"];

                $dadosboleto["demonstrativo1"] = "Guia de";
                $dadosboleto["demonstrativo2"] = "Pagamento referente a " . $descricao . "<br>Taxa bancária - R$ " . number_format($taxa_boleto, 2, ',', '');
                $dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
                $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
                $dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
                $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
                $dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

                $dadosboleto["quantidade"] = "001";
                $dadosboleto["valor_unitario"] = $valor_boleto;
                $dadosboleto["aceite"] = "";
                $dadosboleto["especie"] = "R$";
                $dadosboleto["especie_doc"] = "DS";

                $dadosboleto["agencia"] = "1100";
                $dadosboleto["agencia_dv"] = "0";
                $dadosboleto["conta"] = "0102003";
                $dadosboleto["conta_dv"] = "4";
                $dadosboleto["conta_cedente"] = "0102003";
                $dadosboleto["conta_cedente_dv"] = "4";
                $dadosboleto["carteira"] = "06";

                $dadosboleto["identificacao"] = "BoletoPhp - Código Aberto de Sistema de Boletos";
                $dadosboleto["cpf_cnpj"] = "00.000.000/0000-00";
                $dadosboleto["endereco"] = "Rua Teste, n° 123 - Bairro Teste";
                $dadosboleto["cidade_uf"] = "Campo Grande/MS";
                $dadosboleto["cedente"] = "Portal Consulta Empreendimentos";

                // Incluir os arquivos necessários para as funções e layout do boleto
                include __DIR__ . '/funcoes_bradesco.php';
                include __DIR__ . '/layout_bradesco.php';

                // Certifique-se de ter o FPDF ou qualquer biblioteca que esteja usando

                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(40, 10, 'Boleto gerado com sucesso');
                $pdf->Output('I', 'boleto.pdf');
                
            } else {
                echo "Nenhum registro encontrado.";
            }
        } else {
            echo "Erro na preparação da consulta SQL.";
        }
    } else {
        echo "Dados insuficientes para gerar o boleto.";
    }
} else {
    echo "Requisição inválida.";
}
?>