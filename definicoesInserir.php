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
                        if ($_SESSION['defNotHorario'] != $definicaoHorario) {
                            registrar_log($con, "Editar definições", "defNotHorario: {$_SESSION['defNotHorario']} => $definicaoHorario");
                        }
                        notificacao('success', "Definições alteradas com sucesso!");
                        $_SESSION['defNotHorario'] = $definicaoHorario;
                    }
                    else {
                        notificacao('danger', "Erro ao alterar definições!");
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
                            notificacao('success', "Definições alteradas com sucesso!");
                        }
                        else {
                            notificacao('danger', "Erro ao alterar definições!");
                        }
                    }
                }
                $detalhes = gerar_detalhes_alteracoes(
                    $rowTeste,
                    [
                        'cronjobSeguro' => $_POST['cronjob_1'],
                        'cronjobRecibos' => $_POST['cronjob_2'],
                        'cronjobNovoAnoLetivo' => $_POST['cronjob_3'],
                        'cronjobDespesas' => $_POST['cronjob_4'],
                    ]
                );
                if (!empty($detalhes)) {
                    echo "teste";
                    registrar_log($con, "Editar definições", $detalhes);
                }
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            //header('Location: definicoes');
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