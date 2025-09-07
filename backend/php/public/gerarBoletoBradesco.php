<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['valor']) && !empty($_POST['descricao']) && !empty($_POST['vencimento']) && !empty($_POST['opcao']) && !empty($_POST['dados'])) {

        $valor_cobrado = floatval(str_replace(",", ".", $_POST['valor']));
        $descricao = trim($_POST['descricao']);
        $vencimento = $_POST['vencimento'];
        $opcao = $_POST['opcao'];
        $dados = trim($_POST['dados']);

        $taxa_boleto = 2.95;
        $data_venc = DateTime::createFromFormat('Y-m-d', $vencimento)->format('d/m/Y');
        $valor_total = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');

        $sql = "
            SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social,
                   endereco.cep, endereco.rua, endereco.numero, endereco.bairro, endereco.cidade, endereco.uf
              FROM pj
              INNER JOIN cnpj ON pj.cnpj_id = cnpj.id
              INNER JOIN endereco ON pj.endereco_id = endereco.id
             WHERE " . ($opcao === 'im' ? 'pj.im = ?' : 'cnpj.cnpj = ?');

        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $dados);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $sacado = $row['razao_social'];
                $endereco1 = "{$row['rua']} {$row['numero']} - {$row['bairro']}";
                $endereco2 = "{$row['cidade']}/{$row['uf']} - CEP {$row['cep']}";

                // HTML do boleto (simples, personalizável)
                $html = "
                <html>
                <head><meta charset='UTF-8'><style>body { font-family: Arial; font-size: 12px; }</style></head>
                <body>
                    <h1 style='text-align: center;'>Boleto - Portal Consulta Empreendimentos</h1>
                    <p><strong>Sacado:</strong> {$sacado}</p>
                    <p><strong>Endereço:</strong><br>{$endereco1}<br>{$endereco2}</p>
                    <p><strong>Valor do Boleto:</strong> R$ {$valor_total}</p>
                    <p><strong>Vencimento:</strong> {$data_venc}</p>
                    <p><strong>Referente a:</strong> {$descricao}</p>
                    <p><strong>Taxa bancária:</strong> R$ " . number_format($taxa_boleto, 2, ',', '') . "</p>
                    <hr>
                    <p><em>- Sr. Caixa, cobrar multa de 2% após o vencimento.<br>
                    - Receber até 10 dias após o vencimento.<br>
                    - Em caso de dúvidas, entre em contato: atendimento@kadom.com.br</em></p>
                </body>
                </html>
                ";

                // Geração do PDF
                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream("boleto_gerado.pdf", ["Attachment" => false]);
                exit;

            } else {
                echo "Nenhum registro encontrado.";
            }
        } else {
            echo "Erro na preparação da consulta.";
        }
    } else {
        echo "Dados insuficientes.";
    }
} else {
    echo "Requisição inválida.";
}
