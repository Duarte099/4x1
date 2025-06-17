<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'save') {

            // $horas = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'];
            // $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            
            $dataInscricao = date('Y-m-d');

            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO alunos (nome, localidade, morada, dataNascimento, codigoPostal, NIF, email, contacto, escola, ano, curso, turma, horasGrupo, horasIndividual, transporte, nomeMae, tlmMae, nomePai, tlmPai, modalidade, dataInscricao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sssssssssissiiissssss", $_POST['nome'], $_POST['localidade'], $_POST['morada'], $_POST['dataNascimento'], $_POST['codigoPostal'], $_POST['NIF'], $_POST['email'], $_POST['contacto'], $_POST['escola'], $_POST['ano'], $_POST['curso'], $_POST['turma'], $_POST['horasGrupo'], $_POST['horasIndividual'], $_POST['transporte'], $_POST['nomeMae'], $_POST['maeTlm'], $_POST['nomePai'], $_POST['paiTlm'], $_POST['modalidade'], $dataInscricao);
                if ($result->execute()) {
                    //Obtem o id do novo aluno inserido
                    $idAluno = $con->insert_id;
                    notificacao('success', 'Aluno criado com sucesso!');
                    registrar_log($con, "Criar aluno", 
                        "nome: '{$_POST['nome']}', localidade: '{$_POST['localidade']}', morada: '{$_POST['morada']}', " .
                        "dataNascimento: '{$_POST['dataNascimento']}', codigoPostal: '{$_POST['codigoPostal']}', NIF: '{$_POST['NIF']}', " .
                        "email: '{$_POST['email']}', contacto: '{$_POST['contacto']}', escola: '{$_POST['escola']}', ano: '{$_POST['ano']}', " .
                        "curso: '{$_POST['curso']}', turma: '{$_POST['turma']}', horasGrupo: '{$_POST['horasGrupo']}', " .
                        "horasIndividual: '{$_POST['horasIndividual']}', transporte: '{$_POST['transporte']}', " .
                        "nomeMae: '{$_POST['nomeMae']}', maeTlm: '{$_POST['maeTlm']}', nomePai: '{$_POST['nomePai']}', " .
                        "paiTlm: '{$_POST['paiTlm']}', modalidade: '{$_POST['modalidade']}', dataInscricao: '$dataInscricao'"
                    );
                }
                else {
                    notificacao('danger', 'Erro ao criar aluno: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar aluno: ' . $result->error);
            }

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

            // foreach ($dias as $dia) {
            //     foreach ($horas as $hora) {
            //         if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
            //             $sql = "INSERT INTO alunos_disponibilidade (idAluno, dia, hora, disponibilidade) VALUES (?, ?, ?, ?)";
            //             $result = $con->prepare($sql);
            //             if ($result) {
            //                 $disponibilidade = 1;
            //                 $result->bind_param("issd", $idAluno, $dia, $hora, $disponibilidade);
            //             }
            //             $result->execute();
            //         }
            //     }
            // }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: aluno.php');
        }
        //Se a operação for edit
        elseif ($op == 'edit') {

            // $horas = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'];
            // $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

            $dados_novos = [ 'nome' => $_POST['nome'],'localidade' => $_POST['localidade'],'morada' => $_POST['morada'],'dataNascimento' => $_POST['dataNascimento'],'codigoPostal' => $_POST['codigoPostal'],'NIF' => $_POST['NIF'],'email' => $_POST['email'],'contacto' => $_POST['contacto'],'escola' => $_POST['escola'],'ano' => $_POST['ano'],'curso' => $_POST['curso'],'turma' => $_POST['turma'],'horasGrupo' => $_POST['horasGrupo'],'horasIndividual' => $_POST['horasIndividual'],'transporte' => $_POST['transporte'],'nomeMae' => $_POST['nomeMae'],'maeTlm' => $_POST['maeTlm'],'nomePai' => $_POST['nomePai'],'paiTlm' => $_POST['paiTlm'],'modalidade' => $_POST['modalidade'],'dataInscricao' => $dataInscricao,            ];

            $dataInscricao = date('Y-m-d');
            $idAluno = $_GET['idAluno'];

            $stmt = $con->prepare('SELECT * FROM alunos WHERE id = ?');
            $stmt->bind_param('i', $idAluno);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                header('Location: dashboard.php');
                exit();
            }
            else {
                $rowAluno = $result->fetch_assoc();
            }

            $sql = "UPDATE alunos SET nome = ?, localidade = ?, morada = ?, dataNascimento = ?, codigoPostal = ?, NIF = ?, email = ?, contacto = ?, escola = ?, ano = ?, curso = ?, turma = ?, horasGrupo = ?, horasIndividual = ?, transporte = ?, nomeMae = ?, tlmMae = ?, nomePai = ?, tlmPai = ?, modalidade = ?, estado = ?, dataInscricao = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sssssssssissiiisssssiis", $_POST['nome'], $_POST['localidade'], $_POST['morada'], $_POST['dataNascimento'], $_POST['codigoPostal'], $_POST['NIF'], $_POST['email'], $_POST['contacto'], $_POST['escola'], $_POST['ano'], $_POST['curso'], $_POST['turma'], $_POST['horasGrupo'], $_POST['horasIndividual'], $_POST['transporte'], $_POST['nomeMae'], $_POST['maeTlm'], $_POST['nomePai'], $_POST['paiTlm'], $_POST['modalidade'], $_POST['estado'], $dataInscricao, $idAluno);
                
                if ($result->execute()) {
                    notificacao('success', 'Aluno editado com sucesso!');

                    $detalhes = gerar_detalhes_alteracoes($dados_antigos, $dados_novos);
                    if (!empty($detalhes)) {
                        registrar_log($con, "Editar aluno", "id: {$rowAluno['id']}, $detalhes");
                    }
                } 
                else {
                    notificacao('danger', 'Erro ao editar aluno: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao editar aluno: ' . $result->error);
            }
            
            // foreach ($dias as $dia) {
            //     foreach ($horas as $hora) {
            //         $result2 = $con->prepare('SELECT id FROM alunos_disponibilidade WHERE idAluno = ? AND dia = ? AND hora = ?');
            //         $result2->bind_param('iss', $idAluno, $dia, $hora);
            //         $result2->execute();
            //         $result2->store_result();
            //         $result2->bind_result($idAlunoDisponibilidade);
            //         $result2->fetch();
            //         if ($result2->num_rows <= 0) {
            //             if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
            //                 $sql = "INSERT INTO alunos_disponibilidade (idAluno, dia, hora, disponibilidade) VALUES (?, ?, ?, ?)";
            //                 $result = $con->prepare($sql);
            //                 if ($result) {
            //                     $disponibilidade = 1;
            //                     $result->bind_param("issd", $idAluno, $dia, $hora, $disponibilidade);
            //                 }
            //                 $result->execute();
            //             }
            //         }
            //         else {
            //             if (isset($_POST["disponibilidade_" . $dia . "_" . $hora . ""]) && $_POST["disponibilidade_" . $dia . "_" . $hora . ""] == "on") {
            //                 $disponibilidade = 1;
            //             }
            //             else {
            //                 $disponibilidade = 0;
            //             }
            //             $sql = "UPDATE alunos_disponibilidade SET disponibilidade = ? WHERE idAluno = ? AND dia = ? AND hora = ?";
            //             $result = $con->prepare($sql);
            //             if ($result) {
            //                 $result->bind_param("iiss", $disponibilidade, $idAluno, $dia, $hora);
            //             }
            //             $result->execute();
            //         }
            //     }
            // }

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
            //header('Location: alunoEdit.php?idAluno=' . $idAluno);
            header('Location: aluno.php');
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