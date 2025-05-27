<?php
    header('Content-Type: application/json');

    include('./db/conexao.php');

    $idAluno = $_GET['idAluno'];

    $stmt = $con->prepare('SELECT id FROM alunos WHERE id = ?');
    $stmt->bind_param('i', $idAluno);
    $stmt->execute(); 
    $stmt->store_result();
    if ($stmt->num_rows <= 0) {
        header('Location: dashboard.php');
        exit();
    }

    $sql = "SELECT ar.ano, mes, idAluno, (mensalidadeGrupo + mensalidadeIndividual + ar.transporte + inscricao + coima) AS valor, 
                CASE 
                    WHEN pagoEm = '0000-00-00 00:00:00' AND DAY(CURDATE()) > 8 THEN '3'
                    WHEN pagoEm != '0000-00-00 00:00:00' THEN '1'
                    WHEN pagoEm = '0000-00-00 00:00:00' AND DAY(CURDATE()) < 8 THEN '2'
                    ELSE 'Outro'
                END AS estado
            FROM alunos_recibo as ar 
            INNER JOIN alunos as a ON idAluno = a.id
            WHERE idAluno = ?
            ORDER BY ano, mes;";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $idAluno);
    $stmt->execute();

    $result = $stmt->get_result();
    $dados = [];

    $meses = [
        1 => "Jan", 2 => "Fev", 3 => "Mar", 4 => "Abr", 5 => "Mai", 6 => "Jun",
        7 => "Jul", 8 => "Ago", 9 => "Set", 10 => "Out", 11 => "Nov", 12 => "Dez"
    ];

    while ($row = $result->fetch_assoc()) {
        $dados[] = [
            'ano' => $row['ano'],
            'mes' => $meses[intval($row['mes'])],
            'mesNum' => $row['mes'],
            'valor' => floatval($row['valor']),
            'status' => $row['estado'],
            'idAluno' => $row['idAluno']
        ];
    }

    echo json_encode($dados);