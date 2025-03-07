<?php
    include('./db/conexao.php');

    // Prepara a query com placeholder para evitar injeção e erros
    $sql1 = "SELECT * FROM alunos WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows >= 0) {
        while ($row1 = $result1->fetch_assoc()) {
            //Mês Atual
            $mesAtual = date("m-Y");

            echo $mesAtual;

            //Mês passado
            $mesAnterior = date("m-Y", strtotime("-1 month"));

            echo $mesAnterior;

            //Horas Grupo Realizadas
            $horasRealizadasGrupo = 0;
            $sql2 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND DATE_FORMAT(dia, '%m-%Y') = '$mesAnterior' AND individual = 0";
            $result2 = $con->query($sql2);
            if ($result2->num_rows >= 0) {
                $row2 = $result2->fetch_assoc();
                $horasRealizadasGrupo = $row2['horasRealizadas'];
            }

            //Horas Individuais Realizadas
            $horasRealizadasindividual = 0;
            $sql3 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND DATE_FORMAT(dia, '%m-%Y') = '$mesAnterior' AND individual = 1";
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
            $sql4 = "INSERT INTO alunos_recibo (idAluno, anoAluno, packGrupo, horasRealizadasGrupo, horasBalancoGrupo, packIndividual, horasRealizadasIndividual, horasBalancoIndividual, mensalidade, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiiiiiiiis", $row1['id'], $row1['ano'], $row1['horasGrupo'], $horasRealizadasGrupo, $balancoGrupo, $row1['horasIndividual'], $horasRealizadasindividual, $balancoIndividual, $mensalidade, $mesAnterior);
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
?>