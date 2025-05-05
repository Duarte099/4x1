<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação
        $op = $_GET['op'];

        if ($op == 'save') {
            if (isset($_POST['notificacoesHorario']) && $_POST['notificacoesHorario'] == "on") {
                $definicaoHorario = 1;
                $aux = "ativou";
            } else {
                $definicaoHorario = 0;
                $aux = "desativou";
            }

            $sql = "UPDATE professores SET defNotHorario = ? WHERE id = ?;";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ii", $definicaoHorario, $_SESSION['id']);
                if ($result->execute()) {
                    registrar_log("prof", "O professor [{$_SESSION['id']}] {$_SESSION['nome']} {$aux} as notificações do horário!");
                    notificacao('success', "Definições alteradas com sucesso!");
                }
                else {
                    notificacao('success', "Erro ao alterar definições!");
                }
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: definicoes.php');
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