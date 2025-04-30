<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php');

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['idHorario'])) {
        //Obtem o operação
        $op = $_GET['op'];

        if ($op == 'save') {
            $partes = explode(" | ", $_POST['prof']);
            $idProfessor = $partes[0];
            $id = $_GET['idHorario'];
            if ($id == 0) {
                $stmt = $con->prepare('SELECT * FROM professores WHERE id = ?');
                $stmt->bind_param('i', $idProfessor);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows < 0) {
                    notificacao('warning', 'Professor inválido.');
                    header('Location: horario.php');
                    exit();
                }

                $sql = "INSERT INTO horario (idProfessor, idDisciplina, dia, sala, hora) VALUES (?, ?, ?, ?, ?)";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("iisss", $idProfessor, $_POST['disciplina'], $_POST['dia'], $_POST['sala'], $_POST['hora']);
                    if ($result->execute()) {
                        notificacao('success', 'Hora alterada com sucesso!');
                        $idHorario = $con->insert_id;

                        // --- NOVO: Marcar o professor como precisando de notificação ---
                        $stmtNotificarProf = $con->prepare('UPDATE professores SET notHorario = 1 WHERE id = ?');
                        $stmtNotificarProf->bind_param('i', $idProfessor);
                        $stmtNotificarProf->execute();
                        $stmtNotificarProf->close();

                        for ($i = 1; $i < 10; $i++) {
                            if (isset($_POST['aluno_' . $i]) && !empty($_POST['aluno_' . $i])) {
                                $partes = explode(" | ", $_POST['aluno_' . $i]);
                                $idAluno = $partes[0];
                                $stmt = $con->prepare('SELECT * FROM alunos WHERE id = ?');
                                $stmt->bind_param('i', $idAluno);
                                $stmt->execute();
                                $stmt->store_result();
                                if ($stmt->num_rows < 0) {
                                    notificacao('warning', 'Aluno inválido.');
                                    header('Location: horario.php');
                                    exit();
                                }

                                $sql = "INSERT INTO horario_alunos (idHorario, alunoIndex, idAluno) VALUES (?, ?, ?)";
                                $result = $con->prepare($sql);
                                if ($result) {
                                    $result->bind_param("iii", $idHorario, $i, $idAluno);
                                    $result->execute();
                                    $result->close();
                                } else {
                                    notificacao('danger', 'Erro ao inserir aluno no horário: ' . $result->error);
                                }

                                // --- NOVO: Marcar o aluno como precisando de notificação ---
                                $stmtNotificarAluno = $con->prepare('UPDATE alunos SET notHorario = 1 WHERE id = ?');
                                $stmtNotificarAluno->bind_param('i', $idAluno);
                                $stmtNotificarAluno->execute();
                                $stmtNotificarAluno->close();
                            }
                        }
                    } else {
                        notificacao('danger', 'Erro ao alterar hora: ' . $result->error);
                    }
                } else {
                    notificacao('danger', 'Erro ao alterar hora: ' . $result->error);
                }

                switch ($_POST['dia']) {
                    case 'segunda':
                        header('Location: horario.php?dia=segunda');
                        break;
                    case 'terca':
                        header('Location: horario.php?dia=terca');
                        break;
                    case 'quarta':
                        header('Location: horario.php?dia=quarta');
                        break;
                    case 'quinta':
                        header('Location: horario.php?dia=quinta');
                        break;
                    case 'sexta':
                        header('Location: horario.php?dia=sexta');
                        break;
                    case 'sabado':
                        header('Location: horario.php?dia=sabado');
                        break;
                    default:
                        header('Location: horario.php');
                }
            }
            else {
                // 1. Buscar o estado atual do horário antes de alterar
                $stmt = $con->prepare('SELECT idProfessor, dia, sala, hora FROM horario WHERE id = ?');
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $horarioAtual = $resultado->fetch_assoc();
                $stmt->close();

                // 2. Atualizar o horário (o teu código normal)
                $sql = "UPDATE horario SET idProfessor = ?, idDisciplina = ?, dia = ?, sala = ?, hora = ? WHERE id = ?;";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("iisssi", $idProfessor, $_POST['disciplina'], $_POST['dia'], $_POST['sala'], $_POST['hora'], $id);
                    if ($result->execute()) {
                        notificacao('success', 'Horário editado com sucesso!');
                        
                        // 3. Verificar se mudou alguma coisa no horário
                        $houveAlteracao = false;

                        if ($horarioAtual['idProfessor'] != $idProfessor || 
                            $horarioAtual['dia'] != $_POST['dia'] ||
                            $horarioAtual['sala'] != $_POST['sala'] ||
                            $horarioAtual['hora'] != $_POST['hora']) {
                            $houveAlteracao = true;
                        }

                        // 4. Se houve alterações, marcar o professor para notificação
                        if ($houveAlteracao) {
                            $stmt = $con->prepare('UPDATE professores SET notHorario = 1 WHERE id = ?');
                            $stmt->bind_param('i', $idProfessor);
                            $stmt->execute();
                            $stmt->close();
                        }

                        // 5. Agora continuares o teu FOR dos alunos normalmente
                        for ($i = 1; $i < 10; $i++) {
                            if (isset($_POST['aluno_' . $i])) {
                                $partes = explode(" | ", $_POST['aluno_' . $i]);
                                $idAluno = $partes[0];

                                if (!empty($idAluno)) {
                                    // Atualizar ou inserir no horario_alunos (o teu código normal)
                                    $stmt = $con->prepare('SELECT * FROM horario_alunos WHERE alunoIndex = ? AND idHorario = ?');
                                    $stmt->bind_param('ii', $i, $id);
                                    $stmt->execute();
                                    $stmt->store_result();

                                    if ($stmt->num_rows > 0) {
                                        // Já existe, atualizar
                                        $sqlUpdateAluno = "UPDATE horario_alunos SET idAluno = ? WHERE idHorario = ? AND alunoIndex = ?";
                                        $updateAluno = $con->prepare($sqlUpdateAluno);
                                        $updateAluno->bind_param("iii", $idAluno, $id, $i);
                                        $updateAluno->execute();
                                        $updateAluno->close();
                                    } else {
                                        // Não existe, inserir
                                        $sqlInsertAluno = "INSERT INTO horario_alunos (idHorario, alunoIndex, idAluno) VALUES (?, ?, ?)";
                                        $insertAluno = $con->prepare($sqlInsertAluno);
                                        $insertAluno->bind_param("iii", $id, $i, $idAluno);
                                        $insertAluno->execute();
                                        $insertAluno->close();
                                    }

                                    // --- NOVO: marcar este aluno para ser notificado ---
                                    $stmtUpdateNot = $con->prepare('UPDATE alunos SET notHorario = 1 WHERE id = ?');
                                    $stmtUpdateNot->bind_param('i', $idAluno);
                                    $stmtUpdateNot->execute();
                                    $stmtUpdateNot->close();
                                }
                            }
                        }
                    } 
                    else {
                        notificacao('danger', 'Erro ao editar horário: ' . $result->error);
                    }
                }
                else {
                    notificacao('danger', 'Erro ao editar horário: ' . $result->error);
                }

                switch ($_POST['dia']) {
                    case 'segunda':
                        header('Location: horario.php?dia=segunda');
                        break;
                    case 'terca':
                        header('Location: horario.php?dia=terca');
                        break;
                    case 'quarta':
                        header('Location: horario.php?dia=quarta');
                        break;
                    case 'quinta':
                        header('Location: horario.php?dia=quinta');
                        break;
                    case 'sexta':
                        header('Location: horario.php?dia=sexta');
                        break;
                    case 'sabado':
                        header('Location: horario.php?dia=sabado');
                        break;
                    default:
                        header('Location: horario.php');
                }
            }
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