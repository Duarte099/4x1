<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];
        $idAluno = $_GET['idAluno'];

        $stmt = $con->prepare('SELECT * FROM alunos WHERE id = ?');
        $stmt->bind_param('i', $idAluno);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            header('Location: dashboard.php');
            notificacao('warning', 'ID do aluno invàlido.');
            exit();
        }
        else {
            $row = $result->fetch_assoc();
        }

        if ($op == 'save') {
            $mes = $_GET['mes'];
            $ano = $_GET['ano'];
            $observacao = $_POST['observacao'];
            $idMetodo = $_POST['metodo'];
            $pagoEm = date("Y-m-d H:i:s");
            $idProfessor = $_SESSION['id'];
            if ($_SESSION['tipo'] == "administrador") {
                $idProfessor = 8;
            }

            $stmt = $con->prepare('SELECT * FROM alunos_recibo WHERE idAluno = ? AND mes = ? AND ano = ?');
            $stmt->bind_param('iii', $idAluno, $mes, $ano);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $rowRecibo = $result->fetch_assoc();
            }

            $sql = "UPDATE alunos_recibo SET idMetodo = ?, observacao = ?, pagoEm = ?, idProfessor = ? WHERE idAluno = ? AND ano = ? AND mes = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("issiiii", $idMetodo, $observacao, $pagoEm, $idProfessor, $idAluno, $ano, $mes);
                if ($result->execute()) {
                    notificacao('success', 'Pagamento registrado com sucesso!');
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou o pagamento do aluno [" . $idAluno . "]" . $row["nome"] . ".");
                    }
                    else {
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou o pagamento do aluno [" . $idAluno . "]" . $row["nome"] . ".");
                    }
                    transacao($con, 1, "Pagamento do aluno {$row["nome"]}", $rowRecibo['mensalidadeGrupo'] + $rowRecibo['mensalidadeIndividual'] + $rowRecibo['inscricao'] + $rowRecibo['transporte'] + $rowRecibo['coima']);
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
            header('Location: pagamentoEstado.php');
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