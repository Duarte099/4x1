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
        $stmt = $con->prepare('SELECT id FROM alunos_recibo WHERE id = ?');
        $stmt->bind_param('i', $_GET['idRecibo']);
        $stmt->execute(); 
        $stmt->store_result();
        if ($stmt->num_rows <= 0) {
            header('Location: dashboard.php');
            exit();
        }

        $sql = "UPDATE alunos_recibo SET verificado = 1 WHERE id = ?";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("i", $_GET['idRecibo']);
            if ($result->execute()) {
                notificacao('success', 'Recibo verificado com sucesso!');
                registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " verificou o recibo [" . $_GET['idRecibo'] . "].");
            } 
            else {
                notificacao('danger', 'Erro ao verificar recibo: ' . $result->error);
            }

            $result->close();
        }
        else {
            notificacao('danger', 'Erro ao verificar recibo: ' . $result->error);
        }
    }
    else {
        header('Location: dashboard.php');
        exit();
    }
?>