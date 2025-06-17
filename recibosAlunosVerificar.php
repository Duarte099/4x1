<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $con->prepare('SELECT id FROM alunos_recibo WHERE id = ?');
        $stmt->bind_param('i', $_GET['idRecibo']);
        $stmt->execute(); 
        $stmt->store_result();
        if ($stmt->num_rows <= 0) {
            header('Location: dashboard');
            exit();
        }

        $sql = "UPDATE alunos_recibo SET verificado = 1 WHERE id = ?";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("i", $_GET['idRecibo']);
            if ($result->execute()) {
                notificacao('success', 'Recibo verificado com sucesso!');
                registrar_log($con, "Editar recibo", "id: " . $_GET['idRecibo'] . ", verificado: 0 => 1");
            } 
            else {
                notificacao('danger', 'Erro ao verificar recibo: ' . $result->error);
            }

            $result->close();
        }
        else {
            notificacao('danger', 'Erro ao verificar recibo: ' . $result->error);
        }
        header('Location: recibosAlunos');
    }
    else {
        header('Location: dashboard');
        exit();
    }
?>