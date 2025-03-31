<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        $id = $_GET['id'];

        if ($op == 'save') {
            //Se o administrador não tiver permissão para criar novos alunos redireciona para a dashboard.php
            if (adminPermissions($con, "adm_004", "insert") == 0) {
                notificacao('warning', 'Não tens permissão para aceder a esta página.');
                header('Location: dashboard.php');
                exit();
            }

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