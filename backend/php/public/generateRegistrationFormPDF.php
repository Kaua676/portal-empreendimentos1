<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

header('Content-Type: text/html; charset=utf-8');

// 🔎 Detecta qual filtro está sendo usado
$filtro = '';
$valor = '';

if (isset($_GET['im'])) {
    $filtro = 'pj.im = ?';
    $valor = $_GET['im'];
} elseif (isset($_GET['cnpj'])) {
    $filtro = 'cnpj.cnpj = ?';
    $valor = preg_replace('/\D/', '', $_GET['cnpj']); // remove pontos e traços
} elseif (isset($_GET['razao_social'])) {
    $filtro = 'cnpj.razao_social LIKE ?';
    $valor = '%' . $_GET['razao_social'] . '%';
} else {
    die("Parâmetros ausentes.");
}

// 🧠 SQL com joins e filtro dinâmico
$sql = "SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social, 
            situacao.tipo AS situacao, 
            endereco.cep, endereco.rua, endereco.numero, endereco.bairro, endereco.cidade, endereco.uf, 
            pj.nome_fantasia, pj.data_abertura, pj.capital_social, 
            natureza_juridica.cod, natureza_juridica.descricao AS natureza_descricao, 
            cnae.cnae, cnae.descricao_cnae, cnae.tipo, cnae.data_inicio, cnae.objeto_social
        FROM pj
        INNER JOIN cnpj ON pj.cnpj_id = cnpj.id
        INNER JOIN situacao ON pj.situacao_id = situacao.id
        INNER JOIN endereco ON pj.endereco_id = endereco.id
        INNER JOIN natureza_juridica ON pj.natureza_juridica_id = natureza_juridica.id
        INNER JOIN pj_cnae ON pj.id = pj_cnae.pj_id
        INNER JOIN cnae ON pj_cnae.cnae_id = cnae.id
        WHERE $filtro";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param("s", $valor);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<p>Nenhum registro encontrado.</p>");
}

$pj_data = null;
$atividades = [];

while ($row = $result->fetch_assoc()) {
    $data_abertura = DateTime::createFromFormat('Y-m-d', $row["data_abertura"]);
    $data_formatada_ab = $data_abertura ? $data_abertura->format('d/m/Y') : '';

    $data_inicio = DateTime::createFromFormat('Y-m-d', $row["data_inicio"]);
    $data_formatada_in = $data_inicio ? $data_inicio->format('d/m/Y') : '';

    if ($pj_data === null) {
        $pj_data = [
            "im" => $row["inscricao_municipal"],
            "cnpj" => $row["cnpj"],
            "razao_social" => $row["razao_social"],
            "nome_fantasia" => $row["nome_fantasia"],
            "situacao" => $row["situacao"],
            "data_abertura" => $data_formatada_ab,
            "cod_natureza" => $row["cod"],
            "desc_natureza" => $row["natureza_descricao"],
            "capital_social" => $row["capital_social"],
            "cep" => $row["cep"],
            "rua" => $row["rua"],
            "numero" => $row["numero"],
            "bairro" => $row["bairro"],
            "cidade" => $row["cidade"],
            "uf" => $row["uf"]
        ];
    }

    $atividades[] = [
        "cnae" => $row["cnae"],
        "descricao_cnae" => $row["descricao_cnae"],
        "tipo" => $row["tipo"],
        "data_inicio" => $data_formatada_in,
        "objeto_social" => $row["objeto_social"]
    ];
}

$stmt->close();

// 🧾 HTML para PDF
$html = "<html><head><meta charset='UTF-8'><style>
body { font-family: Arial, sans-serif; font-size: 12px; }
h1, h2 { color: #333; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
td { border: 1px solid #ccc; padding: 8px; }
</style></head><body>";
$html .= "<h1>Ficha Cadastral</h1>";
$html .= "<h2>Dados da Empresa</h2><table>";
$html .= "<tr><td>Inscrição Municipal</td><td>{$pj_data['im']}</td></tr>";
$html .= "<tr><td>CNPJ</td><td>{$pj_data['cnpj']}</td></tr>";
$html .= "<tr><td>Razão Social</td><td>{$pj_data['razao_social']}</td></tr>";
$html .= "<tr><td>Nome Fantasia</td><td>{$pj_data['nome_fantasia']}</td></tr>";
$html .= "<tr><td>Situação</td><td>{$pj_data['situacao']}</td></tr>";
$html .= "<tr><td>Data Abertura</td><td>{$pj_data['data_abertura']}</td></tr>";
$html .= "<tr><td>Natureza Jurídica</td><td>{$pj_data['cod_natureza']} - {$pj_data['desc_natureza']}</td></tr>";
$html .= "<tr><td>Capital Social</td><td>R$ {$pj_data['capital_social']}</td></tr>";
$html .= "</table>";

$html .= "<h2>Endereço</h2><table>";
$html .= "<tr><td>CEP</td><td>{$pj_data['cep']}</td></tr>";
$html .= "<tr><td>Rua</td><td>{$pj_data['rua']}, {$pj_data['numero']}</td></tr>";
$html .= "<tr><td>Bairro</td><td>{$pj_data['bairro']}</td></tr>";
$html .= "<tr><td>Cidade/UF</td><td>{$pj_data['cidade']}/{$pj_data['uf']}</td></tr>";
$html .= "</table>";

$html .= "<h2>Atividades Econômicas (CNAE)</h2>";
foreach ($atividades as $atv) {
    $html .= "<table>";
    $html .= "<tr><td>CNAE</td><td>{$atv['cnae']}</td></tr>";
    $html .= "<tr><td>Descrição</td><td>{$atv['descricao_cnae']}</td></tr>";
    $html .= "<tr><td>Tipo</td><td>{$atv['tipo']}</td></tr>";
    $html .= "<tr><td>Data Início</td><td>{$atv['data_inicio']}</td></tr>";
    $html .= "<tr><td>Objeto Social</td><td>{$atv['objeto_social']}</td></tr>";
    $html .= "</table>";
}

$html .= "</body></html>";

// 📄 Geração do PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("ficha_cadastral.pdf", ["Attachment" => false]);
?>