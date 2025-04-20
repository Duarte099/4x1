<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        $id = $_GET['id'];

        $stmt = $con->prepare('SELECT nome FROM alunos WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute(); 
        $stmt->store_result();
        if ($stmt->num_rows <= 0) {
            header('Location: dashboard.php');
            exit();
        }
        else {
            $row = $stmt->fetch_assoc();
        }

        if ($op == 'save') {
            $mesAnterior = date('m');
            $anoAtual = date('Y');

            if ($mesAnterior == 0) {
                $mesAnterior = 12;
                $anoAtual -= 1;
            }

            $mensalidade = str_replace("€", "", $_POST['mensalidade']);
            $observacao = $_POST['observacao'];
            $idMetodo = $_POST['metodo'];
            $estado = "Pago";
            $pagoEm = date("Y-m-d H:i:s");

            $sql = "UPDATE alunos_pagamentos SET mensalidade = ?, idMetodo = ?, idProfessor = ?, estado = ?, pagoEm = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("iiissi", $mensalidade, $idMetodo, $idAdmin, $estado, $pagoEm, $id);
                if ($result->execute()) {
                    notificacao('success', 'Pagamento registrado com sucesso!');
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou o pagamento do aluno [" . $id . "]" . $row["nome"] . ".");
                    }
                    else {
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou o pagamento do aluno [" . $id . "]" . $row["nome"] . ".");
                    }
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