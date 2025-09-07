<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['opcao'], $_POST['dados'])) {
    echo json_encode(['status' => 'error', 'message' => 'Parâmetros ausentes.']);
    exit;
}

$opcao = $_POST['opcao'];
$dados = $_POST['dados'];

switch ($opcao) {
    case 'im':
        $sql = "SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social, situacao.tipo AS situacao
                FROM pj
                JOIN cnpj ON pj.cnpj_id = cnpj.id
                JOIN situacao ON pj.situacao_id = situacao.id
                WHERE pj.im = ?";
        break;
    case 'cnpj':
        $sql = "SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social, situacao.tipo AS situacao
                FROM pj
                JOIN cnpj ON pj.cnpj_id = cnpj.id
                JOIN situacao ON pj.situacao_id = situacao.id
                WHERE cnpj.cnpj = ?";
        break;
    case 'razao_social':
        $sql = "SELECT pj.im AS inscricao_municipal, cnpj.cnpj, cnpj.razao_social, situacao.tipo AS situacao
                FROM pj
                JOIN cnpj ON pj.cnpj_id = cnpj.id
                JOIN situacao ON pj.situacao_id = situacao.id
                WHERE cnpj.razao_social LIKE ?";
        $dados = "%$dados%";
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Filtro inválido.']);
        exit;
}

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $dados);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
while ($row = $result->fetch_assoc()) {
    $response[] = $row;
}

echo json_encode([
    'status' => 'success',
    'resultados' => $response
]);