<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['opcao']) && isset($_POST['dados'])) {
        $opcao = $_POST['opcao'];
        $dados = $_POST['dados'];

        if ($opcao === 'im' || $opcao === 'cnpj') {
            $sql = "SELECT valor, descricao, vencimento
                    FROM debitos
                    INNER JOIN pj_debitos ON debitos.id = pj_debitos.debitos_id
                    INNER JOIN pj p ON pj_debitos.pj_id = p.id";

            if ($opcao === 'im') {
                $sql .= " WHERE p.im = ?";
            } elseif ($opcao === 'cnpj') {
                $sql .= " WHERE EXISTS (
                            SELECT 1 FROM cnpj cn
                            WHERE p.cnpj_id = cn.id AND cn.cnpj = ?
                          )";
            }

            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("s", $dados);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<h2>Resultados da Busca:</h2>";
                    while ($row = $result->fetch_assoc()) {
                        $data_vencimento = DateTime::createFromFormat('Y-m-d', $row["vencimento"]);
                        $data_formatada = $data_vencimento->format('d-m-Y');

                        echo "<p>Tipo: " . $row["descricao"] . "</p>";
                        echo "<p>Valor: R$ " . $row["valor"] . "</p>";
                        echo "<p>Data de Vencimento: " . $data_formatada . "</p>";

                        echo "<form action='api/gerarBoletoBradescoProxy.php' method='post' target='_blank'>";
                        echo "<input type='hidden' name='valor' value='" . $row["valor"] . "'>";
                        echo "<input type='hidden' name='descricao' value='" . $row["descricao"] . "'>";
                        echo "<input type='hidden' name='vencimento' value='" . $row["vencimento"] . "'>";
                        echo "<input type='hidden' name='opcao' value='" . $opcao . "'>";
                        echo "<input type='hidden' name='dados' value='" . $dados . "'>";
                        echo "<button class='btn-secundary' type='submit'>Gerar Boleto</button>";
                        echo "</form><hr>";
                    }
                } else {
                    echo "Nenhum débito encontrado.";
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
