<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    if (isset($_GET['op']) && $_GET['op'] == 'deleteMensalidade' && isset($_GET['idMensalidade'])) {
        $idMensalidade = $_GET['idMensalidade'];
        $stmt = $con->prepare('SELECT * FROM mensalidade WHERE id = ?');
        $stmt->bind_param('i', $idMensalidade);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            notificacao('warning', 'ID de mensalidade inválido.');
            header('Location: dashboard');
            exit();
        }
        else {
            $rowMensalidade = $result->fetch_assoc();
        }

        $sql = "DELETE FROM mensalidade WHERE id = ?";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("i", $idMensalidade);
            if ($result->execute()) {
                notificacao('success', 'Mensalidade eliminada com sucesso!');
                registrar_log($con, "Eliminar mensalidade", "id: " . $idMensalidade . ", ano: " . $rowMensalidade['ano'] . ", horasGrupo: " . $rowMensalidade['horasGrupo'] . ", horasIndividual: " . $rowMensalidade['horasIndividual'] . ", mensalidadeHorasGrupo: " . $rowMensalidade['mensalidadeHorasGrupo'] . ", mensalidadeHorasIndividual: " . $rowMensalidade['mensalidadeHorasIndividual']);
            } 
            else {
                notificacao('danger', 'Erro ao eliminar mensalidade: ' . $result->error);
            }
            $result->close();
        }
        else {
            notificacao('danger', 'Erro ao eliminar mensalidade: ' . $result->error);
        }
        header('Location: pagamentoConfig');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'save') {
            $stmt = $con->prepare('SELECT * FROM mensalidade WHERE ano = ? AND horasGrupo = ? AND horasIndividual = ?');
            $stmt->bind_param('iii', $_POST["ano"], $_POST["horasGrupo"], $_POST["horasInd"]);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                notificacao('warning', 'Essa mensalidade já existe.');
                header('Location: pagamentoConfig');
                exit();
            }
            else {
                $sql = "INSERT INTO mensalidade (ano, horasGrupo, horasIndividual, mensalidadeHorasGrupo, mensalidadeHorasIndividual) VALUES (?, ?, ?, ?, ?)";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("iiiii", $_POST['ano'], $_POST['horasGrupo'], $_POST['horasInd'], $_POST['mensGrupo'], $_POST['mensInd']);
                    if ($result->execute()) {
                        $idMensalidade = $con->insert_id;
                        notificacao('success', 'Mensalidade inserida com sucesso!');
                        registrar_log($con, "Criar mensalidade", "id: " . $idMensalidade . ", ano: " . $_POST['ano'] . ", horasGrupo: " . $_POST['horasGrupo'] . ", horasIndividual: " . $_POST['horasInd'] . ", mensalidadeHorasGrupo: " . $_POST['mensGrupo'] . ", mensalidadeHorasIndividual: " . $_POST['mensInd']);
                    } 
                    else {
                        notificacao('danger', 'Erro ao criar mensalidade: ' . $result->error);
                    }

                    $result->close();
                }
                else {
                    notificacao('danger', 'Erro ao criar mensalidade: ' . $result->error);
                }
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: pagamentoConfig');
        }
        elseif ($op == 'editMensalidade') {
            $idMensalidade = $_GET['idMensalidade'];
            $stmt = $con->prepare('SELECT * FROM mensalidade WHERE id = ?');
            $stmt->bind_param('i', $idMensalidade);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID de mensalidade inválido.');
                header('Location: dashboard');
                exit();
            }
            else {
                $rowMensalidade = $result->fetch_assoc();
            }

            $sql = "UPDATE mensalidade SET ano = ?, horasGrupo = ?, horasIndividual = ?, mensalidadeHorasGrupo = ?, mensalidadeHorasIndividual = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("iiiiii", $_POST['ano'], $_POST['horasGrupo'], $_POST['horasInd'], $_POST['mensGrupo'], $_POST['mensInd'], $idMensalidade);
                if ($result->execute()) {
                    notificacao('success', 'Mensalidade alterada com sucesso!');
                    $detalhes = gerar_detalhes_alteracoes(
                        $rowMensalidade,
                        [
                            'ano' => $_POST['ano'],
                            'horasGrupo' => $_POST['horasGrupo'],
                            'horasIndividual' => $_POST['horasInd'],
                            'mensalidadeHorasGrupo' => $_POST['mensGrupo'],
                            'mensalidadeHorasIndividual' => $_POST['mensInd'],
                        ]
                    );
                    if (!empty($detalhes)) {
                        registrar_log($con, "Editar mensalidade", "id: " . $idMensalidade . ", " . $detalhes);
                    }
                } 
                else {
                    notificacao('danger', 'Erro ao alterar mensalidade: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao alterar mensalidade: ' . $result->error);
            }
            header('Location: pagamentoConfig');
        }
        elseif ($op == 'editPagamento') {
            $idPagamento = $_GET['idPagamento'];

            $stmt = $con->prepare('SELECT pv.id, nome, valor, ensino.nome as nomeEnsino FROM valores_pagamento as pv INNER JOIN ensino ON idEnsino = ensino.id WHERE pv.id = ?');
            $stmt->bind_param('i', $idPagamento);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID de pagamento inválido.');
                header('Location: dashboard');
                exit();
            }
            else {
                $rowPagamento = $result->fetch_assoc();
            }

            $sql = "UPDATE valores_pagamento SET valor = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("di", $_POST['valor'], $idPagamento);
                if ($result->execute()) {
                    notificacao('success', 'Pagamento alterado com sucesso!');
                    $detalhes = gerar_detalhes_alteracoes(
                        $rowPagamento,
                        [
                            'valor' => $_POST['valor'],
                        ]
                    );
                    if (!empty($detalhes)) {
                        registrar_log($con, "Editar pagamento", "id: " . $idPagamento . ", tipo: " . $rowPagamento['nomeEnsino'] . ", " . $detalhes);
                    }
                }
                else {
                    notificacao('danger', 'Erro ao alterar pagamento: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao alterar pagamento: ' . $result->error);
            }
            header('Location: pagamentoConfig');
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