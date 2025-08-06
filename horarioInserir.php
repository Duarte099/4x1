<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php');

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['idHorario'])) {
        //Obtem o operação
        $op = $_GET['op'];

        if ($op == 'save') {
            $partes = explode(" | ", $_POST['prof']);
            $idProfessor = $partes[0];

            $partes = explode(" | ", $_POST['disciplina']);
            $idDisciplina = $partes[0];

            $idHorario = $_GET['idHorario'];
            if ($idHorario == 0) {
                $stmt = $con->prepare('SELECT * FROM professores WHERE id = ?');
                $stmt->bind_param('i', $idProfessor);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows < 0) {
                    notificacao('warning', 'Professor inválido.');
                    header('Location: horario');
                    exit();
                }

                $sql = "INSERT INTO horario (idProfessor, idDisciplina, dia, sala, hora) VALUES (?, ?, ?, ?, ?)";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("iisss", $idProfessor, $_POST['disciplina'], $_POST['dia'], $_POST['sala'], $_POST['hora']);
                    if ($result->execute()) {
                        notificacao('success', 'Hora alterada com sucesso!');
                        $idHorario = $con->insert_id;

                        //Marcar o professor como precisando de notificação
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
                                    header('Location: horario');
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

                                //Marcar o aluno como precisando de notificação
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
                        header('Location: horario?dia=segunda');
                        break;
                    case 'terca':
                        header('Location: horario?dia=terca');
                        break;
                    case 'quarta':
                        header('Location: horario?dia=quarta');
                        break;
                    case 'quinta':
                        header('Location: horario?dia=quinta');
                        break;
                    case 'sexta':
                        header('Location: horario?dia=sexta');
                        break;
                    case 'sabado':
                        header('Location: horario?dia=sabado');
                        break;
                    default:
                        header('Location: horario');
                }
            }
            else {
                //Buscar o estado atual do horário antes de alterar
                $stmt = $con->prepare('SELECT idProfessor, idDisciplina, dia, sala, hora FROM horario WHERE id = ?');
                $stmt->bind_param('i', $idHorario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $horarioAtual = $resultado->fetch_assoc();
                $stmt->close();

                //Inserir professor novo 
                if ($horarioAtual['idProfessor'] != $idProfessor) {
                    
                    $sql = "UPDATE horario SET idProfessor = ? WHERE id = ?;";
                    $result = $con->prepare($sql);
                    if ($result) {
                        $result->bind_param("ii", $idProfessor, $idHorario);
                        if (!$result->execute()) {
                            notificacao('warning', 'Erro ao atualizar professor!');
                        }
                    }

                    //Notificar o professor antigo e o novo professor
                    $stmtNotificarProf = $con->prepare('UPDATE professores SET notHorario = 1 WHERE id IN (?, ?)');
                    $stmtNotificarProf->bind_param('ii', $idProfessor, $horarioAtual['idProfessor']);
                    $stmtNotificarProf->execute();
                    $stmtNotificarProf->close();
                }

                //Inserir nova disciplina
                if ($horarioAtual['idDisciplina'] != $idDisciplina) {
                    $sql = "UPDATE horario SET idProfessor = ? WHERE id = ?;";
                    $result = $con->prepare($sql);
                    if ($result) {
                        $result->bind_param("ii", $idProfessor, $idHorario);
                        if (!$result->execute()) {
                            notificacao('warning', 'Erro ao atualizar disciplina!');
                        }
                    }
                }

                //Inserir alunos
                for ($i = 1; $i < 10; $i++) {
                    if (isset($_POST['aluno_' . $i]) && !empty($_POST['aluno_' . $i])) {
                        $partes = explode(" | ", $_POST['aluno_' . $i]);
                        $idAluno = $partes[0];

                        //Verifica se o id do aluno recebido é valido
                        $stmt = $con->prepare("SELECT * FROM alunos WHERE id = ?");
                        $stmt->bind_param("i", $idAluno);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows <= 0) {
                            notificacao('warning', 'Aluno ' .  $_POST['aluno_' . $i] . ' inválido.');
                            header('Location: horario');
                            exit();
                        }

                        //Seleciona o aluno ja existente
                        $stmt = $con->prepare('SELECT * FROM horario_alunos WHERE idHorario = ? AND alunoIndex = ?');
                        $stmt->bind_param('ii', $idHorario, $i);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        $alunosAtuais = $resultado->fetch_assoc();
                        //Se houver aluno atualmente
                        if ($resultado->num_rows > 0) {
                            //Se o aluno atual for diferente do recebido
                            if ($idAluno != $alunosAtuais['idAluno']) {
                                //Atualiza o aluno para o inserido
                                $sql = "UPDATE horario_alunos SET idAluno = ? WHERE alunoIndex = ? AND idHorario = ?;";
                                $result = $con->prepare($sql);
                                if ($result) {
                                    $result->bind_param("iii", $idAluno, $i, $idHorario);
                                    if (!$result->execute()) {
                                        notificacao('warning', 'Erro ao inserir aluno ' .  $_POST['aluno_' . $i] . ' .');
                                    }
                                    else {
                                        //Notifica o antigo e o novo aluno
                                        $stmtNotificarAluno = $con->prepare('UPDATE alunos SET notHorario = 1 WHERE id IN (?, ?)');
                                        $stmtNotificarAluno->bind_param('ii', $idAluno, $alunosAtuais['idAluno']);
                                        $stmtNotificarAluno->execute();
                                        $stmtNotificarAluno->close();
                                    }
                                }
                            }
                        }
                        //Se nao existir aluno ainda
                        else {
                            //Insere o aluno e notifica-o
                            $stmt = $con->prepare('INSERT INTO horario_alunos (idHorario, alunoIndex, idAluno) VALUES (?, ?, ?)');
                            if ($stmt) {
                                $stmt->bind_param("iii", $idHorario, $i, $idAluno);
                                if (!$stmt->execute()) {
                                    notificacao('warning', 'Erro ao inserir aluno ' .  $_POST['aluno_' . $i] . ' .');
                                }
                                else {
                                    $stmtNotificarAluno = $con->prepare('UPDATE alunos SET notHorario = 1 WHERE id = ?');
                                    $stmtNotificarAluno->bind_param('i', $idAluno);
                                    $stmtNotificarAluno->execute();
                                    $stmtNotificarAluno->close();
                                }
                            }
                        }
                    }
                    elseif (isset($_POST['aluno_' . $i]) && empty($_POST['aluno_' . $i])) {
                        $stmt = $con->prepare('SELECT * FROM horario_alunos WHERE idHorario = ? AND alunoIndex = ?');
                        $stmt->bind_param('ii', $idHorario, $i);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        //Se houver aluno atualmente
                        if ($resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();
                            $stmt = $con->prepare('DELETE FROM horario_alunos WHERE id = ?');
                            $stmt->bind_param('i', $row['id']);
                            if ($stmt->execute()) {
                                $stmtNotificarAluno = $con->prepare('UPDATE alunos SET notHorario = 1 WHERE id = ?');
                                $stmtNotificarAluno->bind_param('i', $row['idAluno']);
                                $stmtNotificarAluno->execute();
                                $stmtNotificarAluno->close();
                            }
                        }
                    }
                }

                switch ($_POST['dia']) {
                    case 'segunda':
                        header('Location: horario?dia=segunda');
                        break;
                    case 'terca':
                        header('Location: horario?dia=terca');
                        break;
                    case 'quarta':
                        header('Location: horario?dia=quarta');
                        break;
                    case 'quinta':
                        header('Location: horario?dia=quinta');
                        break;
                    case 'sexta':
                        header('Location: horario?dia=sexta');
                        break;
                    case 'sabado':
                        header('Location: horario?dia=sabado');
                        break;
                    default:
                        header('Location: horario');
                }
            }
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