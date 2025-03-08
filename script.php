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

            //Horas Grupo Realizadas
            $horasRealizadasGrupo = 0;
            $sql2 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 0";
            $result2 = $con->query($sql2);
            if ($result2->num_rows >= 0) {
                $row2 = $result2->fetch_assoc();
                $horasRealizadasGrupo = $row2['horasRealizadas'];
            }

            //Horas Individuais Realizadas
            $horasRealizadasindividual = 0;
            $sql3 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 1";
            $result3 = $con->query($sql3);
            if ($result3->num_rows >= 0) {
                $row3 = $result3->fetch_assoc();
                $horasRealizadasindividual = $row3['horasRealizadas'];
            }

            //Balanço Grupo
            $balancoGrupo = $row1['balancoGrupo'] + ($row1['horasGrupo'] - $horasRealizadasGrupo);

            //Balanço Individual
            $balancoIndividual = $row1['balancoIndividual'] + ($row1['horasIndividual'] - $horasRealizadasindividual);

            $mensalidade = 30;

            //Inserir Recibo
            $sql4 = "INSERT INTO alunos_recibo (idAluno, anoAluno, packGrupo, horasRealizadasGrupo, horasBalancoGrupo, packIndividual, horasRealizadasIndividual, horasBalancoIndividual, mensalidade, ano, mes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiiiiiiiiii", $row1['id'], $row1['ano'], $row1['horasGrupo'], $horasRealizadasGrupo, $balancoGrupo, $row1['horasIndividual'], $horasRealizadasindividual, $balancoIndividual, $mensalidade, $ano, $mes);
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

    //RECIBO PROFESSORES
    $sql1 = "SELECT * FROM professores WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows >= 0) {
        while ($row1 = $result1->fetch_assoc()) {
            //Horas dadas 1 Ciclo
            $sql = "SELECT
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
                        SUBSTRING_INDEX(p.hora, ' - ', -1), 
                        SUBSTRING_INDEX(p.hora, ' - ', 1)
                    )))) AS total_horas
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano >= 1 AND a.ano <= 4;";
            $result = $con->query($sql);
            if ($result->num_rows >= 0) {
                $row = $result->fetch_assoc();
                if (!empty($row['total_horas'])) {
                    $partes = explode(":", $row['total_horas']);
                    $horasDadas1Ciclo = $partes[0];
                } else {
                    $horasDadas1Ciclo = 0;
                }
                $valorParcial1Ciclo = ((int) $horasDadas1Ciclo) * 2;
            }

            //Horas dadas 2 Ciclo
            $sql = "SELECT
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
                        SUBSTRING_INDEX(p.hora, ' - ', -1), 
                        SUBSTRING_INDEX(p.hora, ' - ', 1)
                    )))) AS total_horas
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 4 AND a.ano < 7;";
            $result = $con->query($sql);
            if ($result->num_rows >= 0) {
                $row = $result->fetch_assoc();
                if (!empty($row['total_horas'])) {
                    $partes = explode(":", $row['total_horas']);
                    $horasDadas2Ciclo = $partes[0];
                } else {
                    $horasDadas2Ciclo = 0;
                }
                $valorParcial2Ciclo = ((int) $horasDadas2Ciclo) * 2;
            }

            //Horas dadas 3 Ciclo
            $sql = "SELECT
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
                        SUBSTRING_INDEX(p.hora, ' - ', -1), 
                        SUBSTRING_INDEX(p.hora, ' - ', 1)
                    )))) AS total_horas
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 6 AND a.ano <= 9;";
            $result = $con->query($sql);
            if ($result->num_rows >= 0) {
                $row = $result->fetch_assoc();
                if (!empty($row['total_horas'])) {
                    $partes = explode(":", $row['total_horas']);
                    $horasDadas3Ciclo = $partes[0];
                } else {
                    $horasDadas3Ciclo = 0;
                }
                $valorParcial3Ciclo = ((int) $horasDadas3Ciclo) * 2;
            }

            //Horas dadas secundario
            $sql = "SELECT
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
                        SUBSTRING_INDEX(p.hora, ' - ', -1), 
                        SUBSTRING_INDEX(p.hora, ' - ', 1)
                    )))) AS total_horas
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 9;";
            $result = $con->query($sql);
            if ($result->num_rows >= 0) {
                $row = $result->fetch_assoc();
                if (!empty($row['total_horas'])) {
                    $partes = explode(":", $row['total_horas']);
                    $horasDadasSecundario = $partes[0];
                } else {
                    $horasDadasSecundario = 0;
                }
                $valorParcialSecundario = ((int) $horasDadasSecundario) * 2;
            }

            //Horas dadas Universidade
            $sql = "SELECT
                    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
                        SUBSTRING_INDEX(p.hora, ' - ', -1), 
                        SUBSTRING_INDEX(p.hora, ' - ', 1)
                    )))) AS total_horas
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano = 0;";
            $result = $con->query($sql);
            if ($result->num_rows >= 0) {
                $row = $result->fetch_assoc();
                if (!empty($row['total_horas'])) {
                    $partes = explode(":", $row['total_horas']);
                    $horasDadasUniversidade = $partes[0];
                } else {
                    $horasDadasUniversidade = 0;
                }
                $valorParcialUniversidade = ((int) $horasDadasUniversidade) * 2;
            }

            $sql = "SELECT valor FROM professores_valores;";
            $result = $con->query($sql);
            $valores = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $valores[] = $row['valor'];
                }
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