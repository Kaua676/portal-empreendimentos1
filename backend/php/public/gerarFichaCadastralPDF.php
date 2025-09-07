<?php
// Iniciar a sessão para acessar o CPF do usuário logado
session_start();

// Inclua o arquivo de conexão com o banco de dados
require_once __DIR__ . '/../config/database.php';

use Dompdf\Dompdf;

// Carregue a biblioteca Dompdf
require_once __DIR__ . '/../dompdf/autoload.inc.php';

// Verifique se os parâmetros foram passados na URL
if (isset($_GET['im'])) {
    // Recupere o parâmetro da URL
    $im = $_GET['im'];

    // Recupere o CPF do usuário logado da sessão
    $cpf_logado = $_SESSION['user_cpf'];

    // Construa a consulta SQL para recuperar as informações com base no parâmetro
    $sql = "SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social, 
                situacao.tipo AS situacao, 
                endereco.cep, endereco.rua, endereco.numero, endereco.bairro, endereco.cidade, endereco.uf, 
                pj.nome_fantasia, pj.data_abertura, pj.capital_social, 
                natureza_juridica.cod, natureza_juridica.descricao, 
                cnae.cnae, cnae.descricao_cnae, cnae.tipo, cnae.data_inicio, cnae.objeto_social,
                socio_pf.cpf_id, socio_pf.endereco_id, cpf.cpf, cpf.nome, cpf.data_nasc
            FROM pj
            INNER JOIN cnpj ON pj.cnpj_id = cnpj.id
            INNER JOIN situacao ON pj.situacao_id = situacao.id
            INNER JOIN endereco ON pj.endereco_id = endereco.id
            INNER JOIN natureza_juridica ON pj.natureza_juridica_id = natureza_juridica.id
            INNER JOIN pj_cnae ON pj.id = pj_cnae.pj_id
            INNER JOIN cnae ON pj_cnae.cnae_id = cnae.id
            INNER JOIN pj_socios ON pj.id = pj_socios.pj_id
            INNER JOIN socio_pf ON pj_socios.socio_pf_id = socio_pf.id
            INNER JOIN cpf ON socio_pf.cpf_id = cpf.id
            WHERE pj.im = ?";

    // Preparar a consulta
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $mysqli->error);
    }

    // Vincular os parâmetros aos marcadores de posição
    $stmt->bind_param("s", $im);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Inicialize o HTML do PDF
        $html = '';

        // Armazenar os dados do PJ e atividades
        $pj_data = null;
        $atividades = [];

        // Loop pelos resultados da consulta
        while ($row = $result->fetch_assoc()) {
            $data_abertura = DateTime::createFromFormat('Y-m-d', $row["data_abertura"]);
            $data_formatada_ab = $data_abertura->format('d-m-Y');

            $data_nasc = DateTime::createFromFormat('Y-m-d', $row["data_nasc"]);
            $data_formatada_nasc = $data_nasc->format('d-m-Y');

            $data_inicio = DateTime::createFromFormat('Y-m-d', $row["data_inicio"]);
            $data_formatada_in = $data_inicio->format('d-m-Y');

            // Armazenar os dados do PJ e limpar atividades
            if ($pj_data === null || $pj_data['inscricao_municipal'] !== $row['inscricao_municipal']) {
                $pj_data = [
                    "inscricao_municipal" => $row["inscricao_municipal"],
                    "cnpj" => $row["cnpj"],
                    "razao_social" => $row["razao_social"],
                    "nome_fantasia" => $row["nome_fantasia"],
                    "situacao" => $row["situacao"],
                    "data_abertura" => $row["data_abertura"],
                    "cod" => $row["cod"],
                    "descricao" => $row["descricao"],
                    "capital_social" => $row["capital_social"],
                    "cep" => $row["cep"],
                    "rua" => $row["rua"],
                    "numero" => $row["numero"],
                    "bairro" => $row["bairro"],
                    "cidade" => $row["cidade"],
                    "uf" => $row["uf"],
                    "socio" => $row["cpf_id"],
                    "cpf" => $row["cpf"],
                    "nome" => $row["nome"],
                    "data_nasc" => $row["data_nasc"]
                ];
                $atividades = [];
            }

            // Adicionar atividades
            $atividades[] = [
                "cnae" => $row["cnae"],
                "descricao_cnae" => $row["descricao_cnae"],
                "tipo" => $row["tipo"],
                "data_inicio" => $row["data_inicio"],
                "objeto_social" => $row["objeto_social"]
            ];
        }

        // Gerar o HTML do PDF
        $html = "<!DOCTYPE html><html lang='pt-br'><head><meta charset='UTF-8'><title>Ficha Cadastral</title>";
        $html .= "<style>body { font-family: Arial, sans-serif; } table { width: 100%; } th, td { padding: 10px; }</style></head><body>";
        $html .= "<h1>Ficha Cadastral</h1>";

        // Exibir os dados do PJ
        $html .= "<h2>Dados Cadastrais</h2><table>";
        $html .= "<tr><td>Inscrição Municipal: " . $pj_data["inscricao_municipal"] . "</td><td>CNPJ: " . $pj_data["cnpj"] . "</td></tr>";
        $html .= "<tr><td>Razão Social: " . $pj_data["razao_social"] . "</td><td>Nome Fantasia: " . $pj_data["nome_fantasia"] . "</td></tr>";
        $html .= "<tr><td>Situação: " . $pj_data["situacao"] . "</td><td>Data de Abertura: " . $data_formatada_ab . "</td></tr>";
        $html .= "<tr><td>Natureza Jurídica: " . $pj_data["cod"] . " - " . $pj_data["descricao"] . "</td><td>Capital Social: R$ " . $pj_data["capital_social"] . "</td></tr></table>";

        // Exibir os dados do endereço
        $html .= "<h2>Endereço</h2><table>";
        $html .= "<tr><td>CEP: " . $pj_data["cep"] . "</td><td>Rua: " . $pj_data["rua"] . "</td><td>Número: " . $pj_data["numero"] . "</td></tr>";
        $html .= "<tr><td>Bairro: " . $pj_data["bairro"] . "</td><td>Cidade: " . $pj_data["cidade"] . "</td><td>UF: " . $pj_data["uf"] . "</td></tr></table>";

        // Verificar se o usuário logado é sócio
        $html .= "<h2>Sócios</h2><table>";
        if ($pj_data["cpf"] === $cpf_logado) {
            // Exibe dados completos se for o sócio
            $html .= "<tr><td>CPF: " . $pj_data["cpf"] . "</td></tr>";
            $html .= "<tr><td>Nome: " . $pj_data["nome"] . "</td></tr>";
            $html .= "<tr><td>Data Nascimento: " . $data_formatada_nasc . "</td></tr>";
        } else {
            // Exibe dados mascarados se não for o sócio
            $html .= "<tr><td>CPF: XXXXXXXX</td></tr>";
            $html .= "<tr><td>Nome: " . $pj_data["nome"] . "</td></tr>";
            $html .= "<tr><td>Data Nascimento: XXXXXXXX</td></tr>";
        }
        $html .= "</table>";

        // Adicionar as atividades
        $html .= "<h2>Atividades</h2>";
        foreach ($atividades as $atividade) {
            $html .= "<table><tr><td>CNAE: " . $atividade["cnae"] . "</td><td>Descrição CNAE: " . $atividade["descricao_cnae"] . "</td></tr>";
            $html .= "<tr><td>Tipo: " . $atividade["tipo"] . "</td><td>Data de Início: " . $data_formatada_in . "</td></tr>";
            $html .= "<tr><td>Objeto Social: " . $atividade["objeto_social"] . "</td></tr></table>";
        }

        // Fechar o corpo do documento HTML
        $html .= "</body></html>";

        // Crie uma nova instância do Dompdf
        $dompdf = new Dompdf();

        // Carregue o HTML no Dompdf
        $dompdf->loadHtml($html);

        // Renderize o HTML como PDF
        $dompdf->render();

        // Envie o PDF para o navegador
        $dompdf->stream("ficha_cadastral.pdf", ["Attachment" => false]);

    } else {
        echo "<p>Nenhum registro encontrado.</p>";
    }

    $stmt->close();
}
?>