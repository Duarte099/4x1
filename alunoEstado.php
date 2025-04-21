<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    $id = $_GET['idAluno'];
    
    $stmt = $con->prepare("SELECT ativo, nome FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
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
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor " . $_SESSION["nome"] . " atualizou o estado do aluno [" . $id . "]" . $row['nome'] . " de inativo para ativo");
                    }
                    else {
                        registrar_log("admin", "O administrador " . $_SESSION["nome"] . " atualizou o estado do aluno [" . $id . "]" . $row['nome'] . " de inativo para ativo");
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
        elseif ($row['ativo'] == 0) {
            $sql1 = "UPDATE alunos SET ativo = ? WHERE id = ?";
            $result1 = $con->prepare($sql1);
            $ativo = 1;
            if ($result1) {
                $result1->bind_param("ii", $ativo, $id);

                if ($result1->execute()) {
                    notificacao('success', 'Aluno atualizado com sucesso!');
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o estado do aluno [" . $id . "]" . $row['nome'] . " de ativo para inativo");
                    }
                    else {
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o estado do aluno [" . $id . "]" . $row['nome'] . " de ativo para inativo");
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