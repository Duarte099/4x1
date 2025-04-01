<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    $id = $_GET['idAluno'];

    $sql = "SELECT ativo FROM alunos WHERE id = $id;";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['ativo'] == 1) {
            $sql1 = "UPDATE alunos SET ativo = ? WHERE id = ?";
            $result1 = $con->prepare($sql1);
            $ativo = 0;
            if ($result1) {
                $result1->bind_param("ii", $ativo, $id);

                if ($result1->execute()) {
                    notificacao('success', 'Aluno atualizado com sucesso!');
                } 
                else {
                    notificacao('danger', 'Erro ao editar aluno: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao editar aluno: ' . $result->error);
            }
        }
        elseif ($row['ativo'] == 0) {
            $sql1 = "UPDATE alunos SET ativo = ? WHERE id = ?";
            $result1 = $con->prepare($sql1);
            $ativo = 1;
            if ($result1) {
                $result1->bind_param("ii", $ativo, $id);

                if ($result1->execute()) {
                    notificacao('success', 'Aluno atualizado com sucesso!');
                } 
                else {
                    notificacao('danger', 'Erro ao editar aluno: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao editar aluno: ' . $result->error);
            }
        }
        header('Location: estadoAlunos.php');
    }
    else {
        notificacao('warning', 'ID do aluno inválido.');
        header('Location: estadoAlunos.php');
        exit();
    }
?>