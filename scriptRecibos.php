<?php
    include('./db/conexao.php');

    //RECIBO ALUNOS
    $sql1 = "SELECT * FROM alunos WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows >= 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $mes = date("n");
            $ano = date("Y");
            $mes = $mes - 1;
            $mensalidade = 0;

            //Valores pagamento transporte
            $sql = "SELECT * FROM valores_pagamento WHERE id = 7;";
            $result = $con->query($sql);
            //Se houver um aluno com o id recebido, guarda as informações
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $valorTransporte = $row["valor"];
            }

            //Valores pagamento
            $sql = "SELECT * FROM valores_pagamento WHERE id = 9;";
            $result = $con->query($sql);
            //Se houver um aluno com o id recebido, guarda as informações
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $valorInscricao = $row["valor"];
            }

            //Horas Grupo Realizadas
            $horasRealizadasGrupo = 0;
            $sql2 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 0";
            $result2 = $con->query($sql2);
            if ($result2->num_rows >= 0) {
                $row2 = $result2->fetch_assoc();
                $horasRealizadasGrupo = $row2['horasRealizadas'];
            }

            //Horas Individuais Realizadas
            $horasRealizadasIndividual = 0;
            $sql3 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 1";
            $result3 = $con->query($sql3);
            if ($result3->num_rows >= 0) {
                $row3 = $result3->fetch_assoc();
                $horasRealizadasIndividual = $row3['horasRealizadas'];
            }

            //Balanço Grupo
            $balancoGrupo = $row1['balancoGrupo'] + ($row1['horasGrupo'] - $horasRealizadasGrupo);

            //Balanço Individual
            $balancoIndividual = $row1['balancoIndividual'] + ($row1['horasIndividual'] - $horasRealizadasIndividual);

            //Valor do transporte
            if ($row1['transporte'] == 1) {
                $mensalidade = $mensalidade + $valorTransporte;
            }

            //Valor da inscrição
            if(!empty($row1['dataInscricao'])){
                $mesInscricao = date('Y-m', strtotime($row1['dataInscricao']));
                if ($mesInscricao == date('Y-m')) {
                    $mensalidade += $valorInscricao;
                }
            }

            ///Mensalidade Grupo
            $result = $con->prepare('SELECT mensalidadeHorasGrupo FROM mensalidade INNER JOIN alunos ON alunos.idMensalidadeGrupo  = mensalidade.id WHERE alunos.id = ?');
            $result->bind_param("i", $row1['id']);
            $result->execute();
            $result = $result->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $mensalidade = $mensalidade + $row['mensalidadeHorasGrupo'];
            }

            //Mensalidade Individual
            $result = $con->prepare('SELECT mensalidadeHorasIndividual FROM mensalidade INNER JOIN alunos ON alunos.idMensalidadeIndividual = mensalidade.id WHERE alunos.id = ?');
            $result->bind_param("i", $row1['id']);
            $result->execute(); 
            $result = $result->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $mensalidade = $mensalidade + $row['mensalidadeHorasIndividual'];
            }

            //Inserir Recibo
            $sql4 = "INSERT INTO alunos_recibo (idAluno, anoAluno, packGrupo, horasRealizadasGrupo, horasBalancoGrupo, packIndividual, horasRealizadasIndividual, horasBalancoIndividual, mensalidade, ano, mes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiiiiiiiiii", $row1['id'], $row1['ano'], $row1['horasGrupo'], $horasRealizadasGrupo, $balancoGrupo, $row1['horasIndividual'], $horasRealizadasIndividual, $balancoIndividual, $mensalidade, $ano, $mes);
                $result4->execute();
            }

            //Atualizar balanço do aluno
            $sql5 = "UPDATE alunos SET balancoGrupo = ?, balancoIndividual = ? WHERE id = ?";
            $result5 = $con->prepare($sql5);
            if ($result5) {
                $result5->bind_param("ddi", $balancoGrupo, $balancoIndividual, $row1['id']);
                $result5->execute();
            }
        }
    }

    $sql = "SELECT valor FROM valores_pagamento;";
    $result = $con->query($sql);
    $valores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $valores[] = $row['valor'];
        }
    }

    function minutosToValor($minutos){
        // Conversão para horas e minutos
        $horas = intdiv($minutos, 60);

        $minutosRestantes = $minutos % 60;

        // Conversão para horas decimais
        return $minutos / 60;
    }

    //RECIBO PROFESSORES
    $sql1 = "SELECT * FROM professores WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows >= 0) {
        while ($row1 = $result1->fetch_assoc()) {
            //Horas dadas 1 Ciclo
            $valorParcial1Ciclo = 0;
            $horasDadas1Ciclo = 0;
            $sql = "SELECT duracao
                    FROM professores_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano >= 1 AND a.ano <= 4 AND idProfessor = {$row1['id']};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadas1Ciclo = $horasDadas1Ciclo + $row["duracao"];
                }
                $horasDadas1Ciclo = minutosToValor($horasDadas1Ciclo);
                $valorParcial1Ciclo = $horasDadas1Ciclo * $valores[0];
            }
    
            //Horas dadas 2 Ciclo
            $valorParcial2Ciclo = 0;
            $horasDadas2Ciclo = 0;
            $sql = "SELECT duracao
                    FROM professores_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 4 AND a.ano < 7 AND idProfessor = {$row1["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadas2Ciclo = $horasDadas2Ciclo + $row["duracao"];
                }
                $horasDadas2Ciclo = minutosToValor($horasDadas2Ciclo);
                $valorParcial2Ciclo = $horasDadas2Ciclo * $valores[1];
            }
    
            //Horas dadas 3 Ciclo
            $valorParcial3Ciclo = 0;
            $horasDadas3Ciclo = 0;
            $sql = "SELECT duracao
                    FROM professores_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 6 AND a.ano <= 9 AND idProfessor = {$row1["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadas3Ciclo = $horasDadas3Ciclo + $row["duracao"];
                }
                $horasDadas3Ciclo = minutosToValor($horasDadas3Ciclo);
                $valorParcial3Ciclo = $horasDadas3Ciclo * $valores[2];
            }
    
            //Horas dadas secundario
            $valorParcialSecundario = 0;
            $horasDadasSecundario = 0;
            $sql = "SELECT duracao
                    FROM professores_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 9 AND idProfessor = {$row1["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadasSecundario = $horasDadasSecundario + $row["duracao"];
                }
                $horasDadasSecundario = minutosToValor($horasDadasSecundario);
                $valorParcialSecundario = $horasDadasSecundario * $valores[3];
            }
    
            //Horas dadas Universidade
            $valorParcialUniversidade = 0;
            $horasDadasUniversidade = 0;
            $sql = "SELECT duracao
                    FROM professores_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano = 0 AND idProfessor = {$row1["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadasUniversidade = $horasDadasUniversidade + $row["duracao"];
                }
                $horasDadasUniversidade = minutosToValor($horasDadasUniversidade);
                $valorParcialUniversidade = $horasDadasUniversidade * $valores[4];
            }
    
            $total = $valorParcial1Ciclo + $valorParcial2Ciclo + $valorParcial3Ciclo + $valorParcialSecundario + $valorParcialUniversidade; 
            
            //Inserir Recibo
            $sql4 = "INSERT INTO `professores_recibo`(`idProfessor`, `horasDadas1Ciclo`, `valorUnitario1Ciclo`, `valorParcial1Ciclo`, `horasDadas2Ciclo`, `valorUnitario2Ciclo`, `valorParcial2Ciclo`, `horasDadas3Ciclo`, `valorUnitario3Ciclo`, `valorParcial3Ciclo`, `horasDadasSecundario`, `valorUnitarioSecundario`, `valorParcialSecundario`, `horasDadasUniversidade`, `valorUnitarioUniversidade`, `valorParcialUniversidade`, `total`, `ano`, `mes`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiiiiiiiiiiiiiiiiii", $row1['id'], $horasDadas1Ciclo, $valores[0], $valorParcial1Ciclo, $horasDadas2Ciclo, $valores[1], $valorParcial2Ciclo, $horasDadas3Ciclo, $valores[2], $valorParcial3Ciclo,  $horasDadasSecundario, $valores[3], $valorParcialSecundario, $horasDadasUniversidade, $valores[4], $valorParcialUniversidade, $total, $ano, $mes);
                $result4->execute();
            }
        }
    }
?>