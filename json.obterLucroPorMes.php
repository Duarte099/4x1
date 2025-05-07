<?php
    include('./db/conexao.php');

    // Definir a query
    $sql = "
        SELECT
            m.mes,
            COALESCE(SUM(CASE WHEN tipo = 'credito' THEN valor ELSE 0 END), 0) AS total_creditos,
            COALESCE(SUM(CASE WHEN tipo = 'debito' THEN valor ELSE 0 END), 0) AS total_debitos,
            COALESCE(SUM(CASE WHEN tipo = 'credito' THEN valor ELSE -valor END), 0) AS lucro
        FROM (
            SELECT 1 AS mes UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
            UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
            UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
        ) AS m
        LEFT JOIN transacoes AS t ON MONTH(t.data) = m.mes AND YEAR(t.data) = YEAR(CURDATE())
        LEFT JOIN categorias AS c ON t.idCategoria = c.id
        GROUP BY m.mes
        ORDER BY m.mes;
    ";

    // Executar a consulta
    $result = $con->query($sql);

    // Verificar se há resultados
    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Fechar a ligação
    $con->close();

    // Devolver os dados em formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
?>