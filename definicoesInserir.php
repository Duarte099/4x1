<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação
        $op = $_GET['op'];

        if ($op == 'save') {
            if ($_SESSION['tipo'] == "professor") {
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
            }
            elseif ($_SESSION['tipo'] == "administrador") {
                for ($i=1; $i < 5; $i++) { 
                    if (isset($_POST['cronjob_' . $i]) && $_POST['cronjob_' . $i] == "on") {
                        $cronjob = 1;
                    } else {
                        $cronjob = 0;
                    }
                    $sql = "UPDATE cronjobs SET estado = ? WHERE id = ?;";
                    $result = $con->prepare($sql);
                    if ($result) {
                        $result->bind_param("ii", $cronjob, $i);
                        if ($result->execute()) {
                            registrar_log("admin", "O administrador [{$_SESSION['id']}] {$_SESSION['nome']} alterou as definições!");
                            notificacao('success', "Definições alteradas com sucesso!");
                        }
                        else {
                            notificacao('success', "Erro ao alterar definições!");
                        }
                    }
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