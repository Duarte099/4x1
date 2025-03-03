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
        $localidade = $_POST['localidade'];
        $morada = $_POST['morada'];
        $dataNascimento = $_POST['dataNascimento'];
        $codigoPostal = $_POST['codigoPostal'];
        $NIF = $_POST['NIF'];
        $email = $_POST['email'];
        $contacto = $_POST['contacto'];
        $escola = $_POST['escola'];
        $ano = $_POST['ano'];
        $curso = $_POST['curso'];
        $turma = $_POST['turma'];
        $horasGrupo = $_POST['horasGrupo'];
        $horasIndividual = $_POST['horasIndividual'];
        $nomeMae = $_POST['mae'];
        $tlmMae = $_POST['maeTlm'];
        $nomePai = $_POST['pai'];
        $tlmPai = $_POST['paiTlm'];
        $modalidade = $_POST['modalidade'];

        if (isset($_POST['transporte']) && $_POST['transporte'] == "on") {
            $transporte = 1;
        } else {
            $transporte = 0;
        }

        if ($op == 'save') {
            //Se o administrador não tiver permissão para criar novos alunos redireciona para a dashboard
            if (adminPermissions($con, "adm_001", "insert") == 0) {
                header('Location: dashboard');
                exit();
            }

            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO alunos (nome, localidade, morada, dataNascimento, codigoPostal, NIF, email, contacto, escola, ano, curso, turma, horasGrupo, horasIndividual, transporte, nomeMae, tlmMae, nomePai, tlmPai, modalidade) VALUES (?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sssssisisissiiisisis", $nome, $localidade, $morada, $dataNascimento, $codigoPostal, $NIF, $email, $contacto, $escola, $ano, $curso, $turma, $horasGrupo, $horasIndividual, $transporte, $nomeMae, $tlmMae, $nomePai, $tlmPai, $modalidade);
            }
            $result->execute();

            //Obtem o id do novo aluno inserido
            $idAluno = $con->insert_id;

            $sql = "SELECT id FROM disciplinas;";
            $result1 = $con->query($sql);
            if ($result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    if (isset($_POST['disciplina_'. $row['id']]) && !empty($_POST['disciplina_'. $row['id']])) {
                        $sql = "INSERT INTO alunos_disciplinas (idAluno, idDisciplina) VALUES (?, ?)";
                        $result = $con->prepare($sql);
                        if ($result) {
                            $result->bind_param("ii", $idAluno, $row['id']);
                        }
                        $result->execute();
                    }
                }
            }

            foreach ($dias as $dia) {
                foreach ($horas as $hora) {
                    if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
                        $sql = "INSERT INTO alunos_disponibilidade (idAluno, dia, hora, disponibilidade) VALUES (?, ?, ?, ?)";
                        $result = $con->prepare($sql);
                        if ($result) {
                            $disponibilidade = 1;
                            $result->bind_param("issd", $idAluno, $dia, $hora, $disponibilidade);
                        }
                        $result->execute();
                    }
                }
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: aluno');
        }
        //Se a operação for edit
        elseif ($op == 'edit') {
            //Se o administrador não tiver permissões de editar um aluno então redireciona para a dashboard
            if (adminPermissions($con, "adm_005", "update") == 0) {
                header('Location: dashboard');
                exit();
            }

            $idAluno = $_GET['idAluno'];

            $estado = $_POST['estado'];

            if ($estado == "Ativo") {
                $estado = 1;
            }
            else {
                $estado = 0;
            }

            $stmt = $con->prepare('SELECT id FROM alunos WHERE id = ?');
            $stmt->bind_param('i', $idAluno);
            $stmt->execute(); 
            $stmt->store_result();
            if ($stmt->num_rows <= 0) {
                header('Location: dashboard');
                exit();
            }

            $sql = "UPDATE alunos SET nome = ?, localidade = ?, morada = ?, dataNascimento = ?, codigoPostal = ?, NIF = ?, email = ?, contacto = ?, escola = ?, ano = ?, curso = ?, turma = ?, horasGrupo = ?, horasIndividual = ?, transporte = ?, nomeMae = ?, tlmMae = ?, nomePai = ?, tlmPai = ?, modalidade = ?, ativo = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sssssisisissiiisisisii", $nome, $localidade, $morada, $dataNascimento, $codigoPostal, $NIF, $email, $contacto, $escola, $ano, $curso, $turma, $horasGrupo, $horasIndividual, $transporte, $nomeMae, $tlmMae, $nomePai, $tlmPai, $modalidade, $estado, $idAluno);
            }
            $result->execute();
            
            
            foreach ($dias as $dia) {
                foreach ($horas as $hora) {
                    $result2 = $con->prepare('SELECT id FROM alunos_disponibilidade WHERE idAluno = ? AND dia = ? AND hora = ?');
                    $result2->bind_param('iss', $idAluno, $dia, $hora);
                    $result2->execute();
                    $result2->store_result();
                    $result2->bind_result($idAlunoDisponibilidade);
                    $result2->fetch();
                    if ($result2->num_rows <= 0) {
                        if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
                            $sql = "INSERT INTO alunos_disponibilidade (idAluno, dia, hora, disponibilidade) VALUES (?, ?, ?, ?)";
                            $result = $con->prepare($sql);
                            if ($result) {
                                $disponibilidade = 1;
                                $result->bind_param("issd", $idAluno, $dia, $hora, $disponibilidade);
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
                        $sql = "UPDATE alunos_disponibilidade SET disponibilidade = ? WHERE idAluno = ? AND dia = ? AND hora = ?";
                        $result = $con->prepare($sql);
                        if ($result) {
                            $result->bind_param("iiss", $disponibilidade, $idAluno, $dia, $hora);
                        }
                        $result->execute();
                    }
                }
            }

            $sql1 = "SELECT id FROM disciplinas;";
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    $result4 = $con->prepare('SELECT id FROM alunos_disciplinas WHERE idAluno = ? AND idDisciplina = ?');
                    $result4->bind_param('ii', $idAluno, $row1['id']);
                    $result4->execute();
                    $result4->store_result();
                    $result4->bind_result($idAlunoDisciplina);
                    $result4->fetch();
                    if ($result4->num_rows <= 0) {
                        if (isset($_POST['disciplina_'. $row1['id']]) && !empty($_POST['disciplina_'. $row1['id']])) {
                            $sql = "INSERT INTO alunos_disciplinas (idAluno, idDisciplina) VALUES (?, ?)";
                            $result3 = $con->prepare($sql);
                            if ($result3) {
                                $result3->bind_param("ii", $idAluno, $row1['id']);
                            }
                            $result3->execute();
                        }
                    }
                    else if (!isset($_POST['disciplina_'. $row1['id']]) && empty($_POST['disciplina_'. $row1['id']])) {
                        $result5 = $con->prepare('DELETE FROM alunos_disciplinas WHERE id = ?');
                        $result5->bind_param("i", $idAlunoDisciplina );
                        $result5->execute();
                    }
                }
            }
            header('Location: alunoEdit?idAluno=' . $idAluno);
        }
        else {
            header('Location: dashboard');
            exit();
        }
    }
    else {
        header('Location: dashboard');
        exit();
    }
?>