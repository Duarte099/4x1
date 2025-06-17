<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    $id = $_GET['idAluno'];
    
    $stmt = $con->prepare("SELECT estado, nome FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['estado'] == 1) {
            $sql1 = "UPDATE alunos SET estado = ? WHERE id = ?";
            $result1 = $con->prepare($sql1);
            $estado = 0;
            if ($result1) {
                $result1->bind_param("ii", $estado, $id);

                if ($result1->execute()) {
                    notificacao('success', 'Aluno atualizado com sucesso!');
                    if (!empty($detalhes)) {
                        registrar_log($con, "Editar aluno", "id: " . $id . ", estado: 'ativo' => 'inativo'");
                    }
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
        elseif ($row['estado'] == 0) {
            $sql1 = "UPDATE alunos SET estado = ? WHERE id = ?";
            $result1 = $con->prepare($sql1);
            $ativo = 1;
            if ($result1) {
                $result1->bind_param("ii", $ativo, $id);

                if ($result1->execute()) {
                    notificacao('success', 'Aluno atualizado com sucesso!');
                    if (!empty($detalhes)) {
                        registrar_log($con, "Editar aluno", "id: " . $id . ", estado: 'inativo' => 'ativo'");
                    }
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