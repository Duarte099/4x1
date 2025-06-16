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

        if ($op == 'save') {
            $observacao = $_POST['observacao'];
            $idMetodo = $_POST['metodo'];
            $pagoEm = date("Y-m-d H:i:s");
            $idProfessor = $_SESSION['id'];
            if ($_SESSION['tipo'] == "administrador") {
                $idProfessor = 8;
            }

            $sql = "UPDATE alunos_recibo SET idMetodo = ?, observacao = ?, pagoEm = ?, idProfessor = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("issii", $idMetodo, $observacao, $pagoEm, $idProfessor, $idRecibo);
                if ($result->execute()) {
                    notificacao('success', 'Pagamento registrado com sucesso!');
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou o pagamento do aluno [" . $idAluno . "]" . $rowRecibo["nome"] . ".");
                    }
                    else {
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou o pagamento do aluno [" . $idAluno . "]" . $rowRecibo["nome"] . ".");
                    }
                    transacao($con, 1, "Pagamento do aluno {$row["nome"]}", $rowRecibo['mensalidadeGrupo'] + $rowRecibo['mensalidadeIndividual'] + $rowRecibo['inscricao'] + $rowRecibo['transporte']);
                }
                else {
                    notificacao('danger', 'Erro ao registrar pagamento: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao registrar pagamento: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: alunoEdit.php?idAluno=' . $rowRecibo['idAluno'] . '&tab=recibos');
        }
        else {
            notificacao('warning', 'Operação inválida.');
            header('Location: dashboard.php');
            exit();
        }
    }
    else {
        header('Location: dashboard.php');
        exit();
    }
?>