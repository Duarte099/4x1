<?php
    //header('Content-Type: application/json');

    include('./db/conexao.php');

    $dados = [];

    $sql = "SELECT email FROM professores;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $dados[] = [
            'email' => $row['email']
        ];
    }

    $sql = "SELECT email FROM administrador;";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $dados[] = [
            'email' => $row['email']
        ];
    }

    echo json_encode($dados);