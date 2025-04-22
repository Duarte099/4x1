<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_GET['op'] == 'delete' && isset($_GET['idTransacao'])) {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'delete') {
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

            //query sql para inserir os dados do aluno
            $sql = "DELETE FROM transacoes WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("i", $idTransacao);
                if ($result->execute()) {
                    notificacao('success', 'Transação eliminada com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " eliminou a transação [" . $idTransacao . "].");
                }
                else {
                    notificacao('danger', 'Erro ao eliminar transação: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao eliminar transação: ' . $result->error);
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