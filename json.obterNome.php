<?php
    // header('Content-Type: application/json');

    include('./db/conexao.php');

    // Verifica se o parâmetro "id" foi informado
    if (isset($_GET['idAluno'])) {
        // Converte o id para inteiro
        $id = (int) $_GET['idAluno'];
        
        // Prepara a query com placeholder para evitar injeção e erros
        $stmt = $con->prepare("SELECT horasGrupo, horasIndividual, IF(ano>=1 AND ano<=4, 1, IF(ano>4 AND ano<7, 2, IF(ano>6 AND ano<=9, 3, IF(ano>9 AND ano<=12, 4, IF(ano=0, 5, 'erro'))))) as ciclo FROM alunos WHERE id = ?");
        
        // Faz o bind do parâmetro
        $stmt->bind_param("i", $id);
        // Executa a query
        $stmt->execute();
        // Obtém o resultado
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Retorna o valor do pack (número de horas) usando a chave correta "horas"
            
            $dados = [
                "horasGrupo"  => $row['horasGrupo'],
                "horasIndividual"  => $row['horasIndividual'],
                "ciclo" => $row['ciclo'],
            ];

            echo json_encode($dados);
        } else {
            echo json_encode(["erro" => true]);
        }
        
        $stmt->close();
    } else {
        // Se o parâmetro "idAluno" não foi informado
        echo json_encode(["erro" => true]);
    }
?>