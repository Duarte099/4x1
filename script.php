<?php
    include('./db/conexao.php');

    // Prepara a query com placeholder para evitar injeção e erros
    $stmt = $con->prepare("SELECT * FROM alunos WHERE ativo = 1");
    // Executa a query
    $stmt->execute();
    // Obtém o resultado
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        //Mês Atual
        $mesAtual = date("m-Y");

        //Mês passado
        $data = DateTime::createFromFormat("m-Y", $mesAtual);
        $data->modify("-1 month");
        $mesAnterior = $data->format("m-Y");

        //Horas Grupo Realizadas
        $horasRealizadasGrupo = 0;
        $sql = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = $row['id'] AND DATE_FORMAT(dia, '%m-%Y') = '$mesAnterior' AND individual = 0";
        $result = $con->query($sql);
        if ($result->num_rows >= 0) {
            $row1 = $result->fetch_assoc();
            $horasRealizadasGrupo = $row['horasRealizadas'];
        }

        //Horas Individuais Realizadas
        $horasRealizadasindividual = 0;
        $sql = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = $row['id'] AND DATE_FORMAT(dia, '%m-%Y') = '$mesAnterior' AND individual = 1";
        $result = $con->query($sql);
        if ($result->num_rows >= 0) {
            $row1 = $result->fetch_assoc();
            $horasRealizadasindividual = $row['horasRealizadas'];
        }

        //Balanço Grupo
        $aux = $row['horasGrupo'] - $horasRealizadasGrupo;
        $balancoGrupo = $row['balancoGrupo'] + $aux;

        //Balanço Individual
        $aux = $row['horasIndividual'] - $horasRealizadasindividual;
        $balancoIndividual = $row['balancoIndividual'] + $aux;

        //Inserir Recibo
        $sql = "INSERT INTO alunos_recibo (idAluno, anoAluno, packGrupo, horasRealizadasGrupo, horasBalancoGrupo, packIndividual, horasRealizadasIndividual, horasBalancoIndividual, mensalidade, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("iiiiiiiiis", $row['id'], $row['ano'], $row['horasGrupo'], $horasRealizadasGrupo, $balancoGrupo, $row['horasIndividual'], $horasRealizadasindividual, $balancoIndividual, 30, $mesAtual);
        }
        $result->execute();

        //Atualizar balanço do aluno
        $sql = "UPDATE alunos SET balancoGrupo = ?, balancoIndividual = ? WHERE id = ?";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("ddi", $balancoGrupo, $balancoIndividual, $row['id']);
        }
        $result->execute();
    }
?>