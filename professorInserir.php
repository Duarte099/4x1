<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        $horas = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'];
        $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $contacto = $_POST['contacto'];

        if ($op == 'save') {
            //Se o administrador não tiver permissão para criar novos alunos redireciona para a dashboard
            if (adminPermissions($con, "adm_002", "insert") == 0) {
                notificacao('warning', 'Não tens permissão para executar esta operação.');
                header('Location: dashboard.php');
                exit();
            }

            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO professores (nome, email, contacto) VALUES (?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ssi", $nome, $email, $contacto);
                if ($result->execute()) {
                    notificacao('success', 'Professor criado com sucesso!');
                } 
                else {
                    notificacao('danger', 'Erro ao criar prefessor: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar professor: ' . $result->error);
            }

            //Obtem o id do novo professor inserido
            $idProfessor = $con->insert_id;

            $sql = "SELECT id FROM disciplinas;";
            $result1 = $con->query($sql);
            if ($result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    if (isset($_POST['disciplina_'. $row['id']]) && !empty($_POST['disciplina_'. $row['id']])) {
                        $sql = "INSERT INTO professores_disciplinas (idProfessor, idDisciplina) VALUES (?, ?)";
                        $result = $con->prepare($sql);
                        if ($result) {
                            $result->bind_param("ii", $idProfessor, $row['id']);
                        }
                        $result->execute();
                    }
                }
            }

            foreach ($dias as $dia) {
                foreach ($horas as $hora) {
                    if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
                        $sql = "INSERT INTO professores_disponibilidade (idProfessor, dia, hora, disponibilidade) VALUES (?, ?, ?, ?)";
                        $result = $con->prepare($sql);
                        if ($result) {
                            $disponibilidade = 1;
                            $result->bind_param("issd", $idProfessor, $dia, $hora, $disponibilidade);
                        }
                        $result->execute();
                    }
                }
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: professor.php');
        }
        //Se a operação for edit
        elseif ($op == 'edit') {
            //Se o administrador não tiver permissões de editar um aluno então redireciona para a dashboard
            if (adminPermissions($con, "adm_005", "update") == 0) {
                notificacao('warning', 'Não tens permissão para executar esta operação.');
                header('Location: dashboard.php');
                exit();
            }

            $idProfessor = $_GET['idProf'];

            $estado = $_POST['estado'];

            if ($estado == "Ativo") {
                $estado = 1;
            }
            else {
                $estado = 0;
            }

            $stmt = $con->prepare('SELECT id FROM professores WHERE id = ?');
            $stmt->bind_param('i', $idProfessor);
            $stmt->execute(); 
            $stmt->store_result();
            if ($stmt->num_rows <= 0) {
                header('Location: dashboard.php');
                exit();
            }

            $sql = "UPDATE professores SET nome = ?, email = ?, contacto = ?, ativo = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ssidi", $nome, $email, $contacto, $estado, $idProfessor);
                if ($result->execute()) {
                    notificacao('success', 'Professor editado com sucesso!');
                } 
                else {
                    notificacao('danger', 'Erro ao editar prefessor: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao editar professor: ' . $result->error);
            }
            
            
            foreach ($dias as $dia) {
                foreach ($horas as $hora) {
                    $result2 = $con->prepare('SELECT id FROM professores_disponibilidade WHERE idProfessor = ? AND dia = ? AND hora = ?');
                    $result2->bind_param('iss', $idProfessor, $dia, $hora);
                    $result2->execute();
                    $result2->store_result();
                    $result2->fetch();
                    if ($result2->num_rows <= 0) {
                        if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
                            $sql = "INSERT INTO professores_disponibilidade (idProfessor, dia, hora, disponibilidade) VALUES (?, ?, ?, ?)";
                            $result = $con->prepare($sql);
                            if ($result) {
                                $disponibilidade = 1;
                                $result->bind_param("issd", $idProfessor, $dia, $hora, $disponibilidade);
                            }
                            $result->execute();
                        }
                    }
                    else {
                        if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
                            $disponibilidade = 1;
                        }
                        else {
                            $disponibilidade = 0;
                        }
                        $sql = "UPDATE professores_disponibilidade SET disponibilidade = ? WHERE idProfessor = ? AND dia = ? AND hora = ?";
                        $result = $con->prepare($sql);
                        if ($result) {
                            $result->bind_param("iiss", $disponibilidade, $idProfessor, $dia, $hora);
                        }
                        $result->execute();
                    }
                }
            }

            $sql1 = "SELECT id FROM disciplinas;";
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    $result4 = $con->prepare('SELECT id FROM professores_disciplinas WHERE idProfessor = ? AND idDisciplina = ?');
                    $result4->bind_param('ii', $idProfessor, $row1['id']);
                    $result4->execute();
                    $result4->store_result();
                    $result4->bind_result($idProfessorDisciplina);
                    $result4->fetch();
                    if ($result4->num_rows <= 0) {
                        if (isset($_POST['disciplina_'. $row1['id']]) && !empty($_POST['disciplina_'. $row1['id']])) {
                            $sql = "INSERT INTO professores_disciplinas (idProfessor, idDisciplina) VALUES (?, ?)";
                            $result3 = $con->prepare($sql);
                            if ($result3) {
                                $result3->bind_param("ii", $idProfessor, $row1['id']);
                            }
                            $result3->execute();
                        }
                    }
                    else if (!isset($_POST['disciplina_'. $row1['id']]) && empty($_POST['disciplina_'. $row1['id']])) {
                        $result5 = $con->prepare('DELETE FROM professores_disciplinas WHERE id = ?');
                        $result5->bind_param("i", $idProfessorDisciplina );
                        $result5->execute();
                    }
                }
            }
            header('Location: professorEdit?idProf=' . $idProfessor);
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