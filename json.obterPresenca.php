<?php
    include('./db/conexao.php');

    // Verifica se o parâmetro "id" foi informado
    if (isset($_GET['idAluno'])) {
        // Converte o id para inteiro
        $id = $_GET['idAluno'];
        
        // Prepara a query com placeholder para evitar injeção e erros
        $stmt1 = $con->prepare("SELECT a.hora, a.dia, p.nome as nomeProf, d.nome as nomeDisc FROM alunos_presenca as a INNER JOIN professores as p ON a.idProfessor = p.id INNER JOIN disciplinas as d ON idDisciplina = d.id WHERE a.idAluno = ?");
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
                "nomeProf" => $row['nomeProf'],
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