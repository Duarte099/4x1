<?php
    include('./db/conexao.php');

    $estado = "Pendente";

    // RECIBO ALUNOS
    $sql1 = "SELECT * FROM alunos WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $mensalidade = 0;

            if ($row1['transporte'] == 1) {
                $mensalidade = 20;
            }

            //Mensalidade Grupo
            $result2 = $con->prepare('SELECT mensalidadeHorasGrupo FROM mensalidade INNER JOIN alunos ON alunos.idMensalidadeGrupo  = mensalidade.id WHERE alunos.id = ?');
            $result2->bind_param("i", $row1['id']);
            $result2->execute();
            $result2 = $result2->get_result();
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_assoc();
                $mensalidade += $row2['mensalidadeHorasGrupo'];
            }

            //Mensalidade Individual
            $result3 = $con->prepare('SELECT mensalidadeHorasIndividual FROM mensalidade INNER JOIN alunos ON alunos.idMensalidadeIndividual = mensalidade.id WHERE alunos.id = ?');
            $result3->bind_param("i", $row1['id']);
            $result3->execute(); 
            $result3 = $result3->get_result();
            if ($result3->num_rows > 0) {
                $row3 = $result3->fetch_assoc();
                $mensalidade = $mensalidade + $row3['mensalidadeHorasGrupo'];
            }

            // Data do mês anterior
            $dataAnterior = date("Y-m-d H:i:s", strtotime("first day of last month"));

            // Inserir Pagamento com a data do mês anterior
            $sql4 = "INSERT INTO alunos_pagamentos (idAluno, mensalidade, estado, created) VALUES (?, ?, ?, ?)";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiss", $row1['id'], $mensalidade, $estado, $dataAnterior);
                $result4->execute();
            }
        }
    }
?>