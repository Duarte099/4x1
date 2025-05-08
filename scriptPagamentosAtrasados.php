<?php
    include('./admin/db/conexao.php');

    //Valores pagamento inscrição
    $sql = "SELECT * FROM valores_pagamento WHERE id = 10;";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $valorCoima = $row["valor"];
    }

    $dataAnterior = new DateTime('first day of last month');
    $mes = $dataAnterior->format('n');
    $ano = $dataAnterior->format('Y');

    // RECIBO ALUNOS
    $sql1 = "SELECT alunos.id, estado FROM alunos INNER JOIN alunos_recibos ON alunos.id = idAluno WHERE ativo = 1 AND estado == 'Pendente' AND ano = $ano AND mes = $mes";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            // Inserir Pagamento com a data do mês anterior
            $sql4 = "UPDATE alunos_recibos SET estado = 'Em atraso', coima = ? WHERE idAluno = ? AND ano = ? AND mes = ?";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiii", $valorCoima, $row1['id'], $ano, $mes);
                $result4->execute();
            }
        }
    }
?>