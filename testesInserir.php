<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação
        $op = $_GET['op'];

        if ($op == 'save') {
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

            $stmt = $con->prepare('SELECT nome FROM alunos WHERE id = ?');
            $stmt->bind_param('i', $idAluno);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                notificacao('warning', 'ID do aluno inválido.');
                header('Location: testes.php');
                exit();
            }

            $dia = $_POST['dia'];

            $sql = "INSERT INTO alunos_testes (idAluno, idDisciplina, dia) VALUES (?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("iis", $idAluno, $idDisciplina, $dia);
                if ($result->execute()) {
                    notificacao('success', 'Teste registrado com sucesso!');
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou um teste para o aluno [" . $idAluno . "]" . $row["nome"] . ".");
                    }
                    else {
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " registrou um teste para o aluno [" . $idAluno . "]" . $row["nome"] . ".");
                    }
                } 
                else {
                    notificacao('danger', 'Erro ao inserir teste: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao inserir teste: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: testes.php');
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