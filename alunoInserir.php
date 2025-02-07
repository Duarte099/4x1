<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'save') {
            //Se o administrador não tiver permissão para criar novos alunos redireciona para a dashboard
            if (adminPermissions($con, "adm_001", "insert") == 0) {
                header('Location: dashboard');
                exit();
            }

            //Obtem os dados inseridos via POST
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
            $nomeMae = $_POST['mae'];
            $tlmMae = $_POST['maeTlm'];
            $nomePai = $_POST['pai'];
            $tlmPai = $_POST['paiTlm'];
            $modalidade = $_POST['modalidade'];

            // $stmt = $con->prepare('SELECT id FROM encarregadoeducacao WHERE nome = ? AND contacto = ?');
            // $stmt->bind_param('ss', $_POST['mae'], $_POST['maeTlm']);
            // $stmt->execute(); 
            // $stmt->store_result();
            // if ($stmt->num_rows > 0) {
            //     $stmt->bind_result($idMae);
            //     $stmt->fetch();
            // }

            // $stmt = $con->prepare('SELECT id FROM encarregadoeducacao WHERE nome = ? AND contacto = ?');
            // $stmt->bind_param('ss', $_POST['pai'], $_POST['paiTlm']);
            // $stmt->execute(); 
            // $stmt->store_result();
            // if ($stmt->num_rows > 0) {
            //     $stmt->bind_result($idPai);
            //     $stmt->fetch();
            // }

            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO alunos (nome, localidade, morada, dataNascimento, codigoPostal, NIF, email, contacto, escola, ano, curso, turma, nomeMae, tlmMae, nomePai, tlmPai, modalidade) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sssssisisisssisis", $nome, $localidade, $morada, $dataNascimento, $codigoPostal, $NIF, $email, $contacto, $escola, $ano, $curso, $turma, $nomeMae, $tlmMae, $nomePai, $tlmpai, $modalidade);
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

            //redireciona para a pagina de edit do administrador que acabou de alterar
            header('Location: adminEdit?idAdmin=' . $idAdmin);
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