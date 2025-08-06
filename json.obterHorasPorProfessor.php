<?php
    include('./db/conexao.php');

    // Definir a query
    $sql = "
        SELECT 
            pr.nome AS professor,
            COALESCE(ROUND(SUM(p.duracao) / 60, 2), 0) AS horas
        FROM 
            professores pr
        LEFT JOIN 
            alunos_presenca p ON p.idProfessor = pr.id 
            AND MONTH(p.dia) = MONTH(CURDATE()) 
            AND YEAR(p.dia) = YEAR(CURDATE())
        WHERE pr.estado = 1
        GROUP BY 
            pr.id
        ORDER BY 
            horas DESC;
    ";

    // Executar a consulta
    $result = $con->query($sql);

    // Verificar se há resultados
    $data = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $row['horas'] = decimalParaHoraMinutos($row['horas']);
            $data[] = $row;
        }
    }

    // Fechar a ligação
    $con->close();

    // Devolver os dados em formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
?>