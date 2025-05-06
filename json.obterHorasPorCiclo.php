<?php
    include('./db/conexao.php');

    // Definir a query
    $sql = "
        SELECT 
            DATE(p.dia) AS dia,
            IF(
                a.ano >= 1 AND a.ano <= 4, '1º CICLO',
                IF(
                    a.ano > 4 AND a.ano < 7, '2º CICLO',
                    IF(
                        a.ano > 6 AND a.ano <= 9, '3º CICLO',
                        IF(
                            a.ano > 9 AND a.ano <= 12, 'SECUNDÁRIO',
                            IF(a.ano = 0, 'UNIVERSIDADE', 'ERRO')
                        )
                    )
                )
            ) AS ensino,
            COALESCE(ROUND(SUM(p.duracao) / 60, 2), 0) AS horas
        FROM 
            alunos a
        LEFT JOIN 
            alunos_presenca p ON p.idAluno = a.id
        WHERE 
            a.ativo = 1 AND YEARWEEK(p.dia, 1) = YEARWEEK(CURDATE(), 1) OR p.dia IS NULL
        GROUP BY 
            ensino, dia
        ORDER BY 
            dia, ensino;
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