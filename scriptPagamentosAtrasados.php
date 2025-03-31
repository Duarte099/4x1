<?php
    include('./db/conexao.php');

    $estado = "Pendente";

    // RECIBO ALUNOS
    $sql1 = "SELECT alunos.id, estado FROM alunos INNER JOIN alunos_pagamentos ON alunos.id = idAluno WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            if ($row1['estado'] == "Pendente") {
                // Data do mês anterior
                $dataAnterior = date("Y-m-d H:i:s", strtotime("first day of last month"));
                
                // Inserir Pagamento com a data do mês anterior
                $sql4 = "UPDATE alunos_pagamentos SET estado = ? WHERE idAluno = ?";
                $result4 = $con->prepare($sql4);
                $estado = "Em atraso";
                if ($result4) {
                    $result4->bind_param("si", $estado, $row1['id']);
                    $result4->execute();
                }
            }
        }
    }
?>