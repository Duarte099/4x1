<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_GET['op'] == 'delete' && isset($_GET['idTransacao'])) {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'delete') {
            $idTransacao = $_GET['idTransacao'];

            $stmt = $con->prepare('SELECT t.*, c.nome, c.tipo FROM transacoes as t INNER JOIN categorias as c ON t.idCategoria = c.id WHERE t.id = ?');
            $stmt->bind_param('i', $idTransacao);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID de transação inválido.');
                header('Location: dashboard');
                exit();
            }
            else {
                $rowTransacao = $result->fetch_assoc();
            }

            //query sql para inserir os dados do aluno
            $sql = "DELETE FROM transacoes WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("i", $idTransacao);
                if ($result->execute()) {
                    notificacao('success', 'Transação eliminada com sucesso!');
                    registrar_log($con, "Eliminar transação", "id: " . $idTransacao . ", categoria: " . $rowTransacao['nome'] . ", tipo: " . $rowTransacao['tipo'] . ", descrição: " . $rowTransacao['descricao'] . ", valor: " . $rowTransacao['valor'] . ", data: " . $rowTransacao['data']);
                }
                else {
                    notificacao('danger', 'Erro ao eliminar transação: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao eliminar transação: ' . $result->error);
            }
            header('Location: transacoes');
        }
        else {
            notificacao('warning', 'Operação inválida.');
            header('Location: dashboard');
            exit();
        }
    }
    else {
        header('Location: dashboard');
        exit();
    }
?>