<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'save') {
            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO transacoes (idCategoria, descricao, valor) VALUES (?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("isd", $_POST["categoria"], $_POST["descricao"], $_POST["valor"]);
                if ($result->execute()) {
                    //Obtem o id do novo professor inserido
                    $idTransacao = $con->insert_id;
                    notificacao('success', 'Transação criada com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " criou a transaçõ [" . $idTransacao . "]" . $_POST["descricao"] . ".");
                } 
                else {
                    notificacao('danger', 'Erro ao criar transação: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar transação: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: transacoes.php');
        }
        //Se a operação for edit
        elseif ($op == 'edit') {
            $idTransacao = $_GET['idTransacao'];

            $stmt = $con->prepare('SELECT id FROM transacoes WHERE id = ?');
            $stmt->bind_param('i', $idTransacao);
            $stmt->execute(); 
            $stmt->store_result();
            if ($stmt->num_rows <= 0) {
                notificacao('warning', 'ID de transação inválido.');
                header('Location: dashboard.php');
                exit();
            }

            $sql = "UPDATE transacoes SET idCategoria = ?, descricao = ?, valor = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ssdi", $_POST["categoria"], $_POST["descricao"], $_POST["valor"], $idTransacao);
                if ($result->execute()) {
                    notificacao('success', 'Transação editada com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou a transação [" . $idTransacao . "]" . $_POST["descricao"] . ".");
                } 
                else {
                    notificacao('danger', 'Erro ao editar transação: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao editar transação: ' . $result->error);
            }
            header('Location: transacoes.php');
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