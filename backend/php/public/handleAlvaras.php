<?php
// Inclui a conexão
require_once __DIR__ . '/../config/database.php';

// Se requisição for POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se recebeu opção (im/cnpj) e dados do usuário
    if (isset($_POST['opcao'], $_POST['dados'])) {
        $opcao = $_POST['opcao'];
        $dados = $_POST['dados'];

        // Somente IM (Inscrição Municipal) ou CNPJ
        if ($opcao === 'im' || $opcao === 'cnpj') {
            // Consulta base
            $sql = "SELECT c.tipo, c.numero_certidao, c.data_vencimento 
                    FROM certidoes c
                    INNER JOIN pj_certidoes pc ON c.id = pc.certidoes_id
                    INNER JOIN pj p ON pc.pj_id = p.id";

            // Filtro adicional se o usuário escolher tipo de certidão
            if (!empty($_POST['tipoCertidao'])) {
                $tipoCertidao = $_POST['tipoCertidao'];
                if ($tipoCertidao === 'alvara') {
                    $sql .= " WHERE c.tipo = 'Alvará de Localização e Funcionamento' ";
                } elseif ($tipoCertidao === 'alvaraprovisorio') {
                    $sql .= " WHERE c.tipo = 'Alvará de Localização e Funcionamento Provisório' ";
                }
            }

            // Condição para IM ou CNPJ
            if ($opcao === 'im') {
                // Busca pela inscrição municipal em pj (coluna p.im)
                $sql .= " AND p.im = ?";
            } else {
                // Busca pelo CNPJ (tabela cnpj, col cnpj)
                // Importante: verifique se precisa remover pontuação do $dados
                $sql .= " AND EXISTS (SELECT 1 
                                      FROM cnpj cn 
                                      WHERE p.cnpj_id = cn.id 
                                        AND cn.cnpj = ?)";
            }

            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $dados);
                $stmt->execute();
                $result = $stmt->get_result();

                // Exibe resultados
                if ($result->num_rows > 0) {
                    echo "<h2>Resultados da Busca:</h2>";
                    while ($row = $result->fetch_assoc()) {
                        $data_vencimento = DateTime::createFromFormat('Y-m-d', $row["data_vencimento"]);
                        $data_formatada  = $data_vencimento ? $data_vencimento->format('d-m-Y') : '';

                        echo "<p>Tipo: " . $row["tipo"] . "</p>";
                        echo "<p>Número da Certidão: " . $row["numero_certidao"] . "</p>";
                        echo "<p>Data de Vencimento: " . $data_formatada . "</p>";
                    }
                } else {
                    echo "Nenhuma certidão encontrada.";
                }
            } else {
                echo "Erro ao preparar a consulta SQL.";
            }
        } else {
            echo "Opção inválida.";
        }
    } else {
        echo "Nenhuma opção selecionada.";
    }
}
?>