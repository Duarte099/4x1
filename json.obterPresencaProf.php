<?php
    include('./db/conexao.php');

    // Verifica se o parâmetro "id" foi informado
    if (isset($_GET['idProf'])) {
        // Converte o id para inteiro
        $id = $_GET['idProf'];
        
        // Prepara a query com placeholder para evitar injeção e erros
        $stmt1 = $con->prepare("SELECT p.hora, p.dia, a.nome as nomeAluno, d.nome as nomeDisc FROM professores_presenca as p INNER JOIN alunos as a ON p.idAluno = a.id INNER JOIN disciplinas as d ON idDisciplina = d.id WHERE p.idProfessor = ?");
        // Faz o bind do parâmetro
        $stmt1->bind_param("i", $id);
        // Executa a query
        $stmt1->execute();
        // Obtém o resultado
        $result = $stmt1->get_result();
        while ($row = $result->fetch_assoc()) {
            // Retorna o valor do pack (número de horas) usando a chave correta "horas"

            $partes = explode(" - ", $row['hora']);

            $dados[] = [
                "nomeAluno" => $row['nomeAluno'],
                "nomeDisc" => $row['nomeDisc'],
                "horaInicio"  => $partes[0],
                "horaFim"  => $partes[1],
                "dia" => $row['dia'],
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($dados);
    } else {
        // Se o parâmetro "idAluno" não foi informado
        echo json_encode(["erro1"]);
    }
?>