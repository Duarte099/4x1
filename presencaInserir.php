<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação
        $op = $_GET['op'];

        if ($op == 'save') {
            //Se o administrador não tiver permissão para criar novos alunos redireciona para a dashboard
            if (adminPermissions($con, "adm_004", "insert") == 0) {
                notificacao('warning', 'Não tens permissão para aceder a esta página.');
                header('Location: dashboard.php');
                exit();
            }

            $partes = explode(" | ", $_POST['nome']);
            $idAluno = $partes[0];

            $sql = "SELECT id FROM disciplinas;";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($_POST['disciplina'] == 'disciplina_' . $row['id']) {
                        $idDisciplina = $row['id'];
                    }
                }
            }

            $anoLetivo = $_POST['anoLetivo'];
            $hora = $_POST['hora'];
            $dia = $_POST['dia'];
            if (isset($_POST['individual']) && $_POST['individual'] == "on") {
                $individual = 1;
            } else {
                $individual = 0;
            }

            $sql = "INSERT INTO alunos_presenca (idAluno, idDisciplina, idProfessor, anoLetivo, hora, dia, individual, criado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("iiisssii", $idAluno, $idDisciplina, $idAdmin, $anoLetivo, $hora, $dia, $individual, $idAdmin);
                if ($result->execute()) {
                    notificacao('success', 'Presença registrada com sucesso!');
                } 
                else {
                    notificacao('danger', 'Erro ao inserir presença: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao inserir presença: ' . $result->error);
            }

            $sql = "INSERT INTO professores_presenca (idProfessor, idAluno, idDisciplina, anoLetivo, hora, dia, criado_por) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("iiisssi", $idAdmin, $idAluno, $idDisciplina, $anoLetivo, $hora, $dia, $idAdmin);
            }
            $result->execute();

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: presenca.php');
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