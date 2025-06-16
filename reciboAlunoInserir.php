<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];
        $idRecibo = $_GET['idRecibo'];

        $stmt = $con->prepare('SELECT ar.*, a.nome FROM alunos_recibo as ar INNER JOIN alunos as a ON ar.idAluno = a.id WHERE ar.id = ?');
        $stmt->bind_param('i', $idRecibo);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            header('Location: dashboard.php');
            notificacao('warning', 'ID do recibo invàlido.');
            exit();
        }
        else {
            $rowRecibo = $result->fetch_assoc();
        }

        if ($op == 'edit') {
            $horasRealizadasGrupo = isset($_POST['horasRealizadasGrupo']) ? $_POST['horasRealizadasGrupo'] : 0;
            $horasBalancoGrupo = isset($_POST['horasBalancoGrupo']) ? $_POST['horasBalancoGrupo'] : $rowRecibo['horasBalancoGrupo'];
            $mensalidadeGrupo = isset($_POST['mensalidadeGrupo']) ? $_POST['mensalidadeGrupo'] : 0;

            $horasRealizadasIndividual = isset($_POST['horasRealizadasIndividual']) ? $_POST['horasRealizadasIndividual'] : 0;
            $horasBalancoIndividual = isset($_POST['horasBalancoIndividual']) ? $_POST['horasBalancoIndividual'] : $rowRecibo['horasBalancoIndividual'];
            $mensalidadeIndividual = isset($_POST['mensalidadeIndividual']) ? $_POST['mensalidadeIndividual'] : 0;

            if ($horasRealizadasGrupo > 0 && $horasRealizadasIndividual > 0) {
                $sql = "UPDATE alunos_recibo SET horasRealizadasGrupo = ?, horasBalancoGrupo = ?, mensalidadeGrupo = ?, horasRealizadasIndividual = ?, horasBalancoIndividual = ?, mensalidadeIndividual = ? WHERE id = ?";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("idiidii", $horasRealizadasGrupo, $horasBalancoGrupo, $mensalidadeGrupo, $horasRealizadasIndividual, $horasBalancoIndividual, $mensalidadeIndividual, $idRecibo);
                }
                else {
                    notificacao('danger', 'Erro ao alterar recibo: ' . $result->error);
                }
            }
            elseif ($horasRealizadasGrupo > 0 && $horasRealizadasIndividual == 0) {
                $sql = "UPDATE alunos_recibo SET horasRealizadasGrupo = ?, horasBalancoGrupo = ?, mensalidadeGrupo = ? WHERE id = ?";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("idii", $horasRealizadasGrupo, $horasBalancoGrupo, $mensalidadeGrupo, $idRecibo);
                }
                else {
                    notificacao('danger', 'Erro ao alterar recibo: ' . $result->error);
                }
            }
            elseif ($horasRealizadasGrupo == 0 && $horasRealizadasIndividual > 0) {
                $sql = "UPDATE alunos_recibo SET horasRealizadasIndividual = ?, horasBalancoIndividual = ?, mensalidadeIndividual = ? WHERE id = ?";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("idii", $horasRealizadasIndividual, $horasBalancoIndividual, $mensalidadeIndividual, $idRecibo);
                }
                else {
                    notificacao('danger', 'Erro ao alterar recibo: ' . $result->error);
                }
            }

            if ($result->execute()) {
                notificacao('success', 'Recibo alterado com sucesso!');
                registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " editou o recibo do aluno [" . $idAluno . "]" . $rowRecibo["nome"] . ".");
            }
            else {
                notificacao('danger', 'Erro ao alterar recibo: ' . $result->error);
            }

            $result->close();
            //header('Location: alunoEdit.php?idAluno=' . $rowRecibo['idAluno'] . '&tab=recibos');
        }
        else {
            // notificacao('warning', 'Operação inválida.');
            // header('Location: dashboard.php');
            // exit();
        }
    }
    else {
        // header('Location: dashboard.php');
        // exit();
    }
?>