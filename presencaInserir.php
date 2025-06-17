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

            $sql = "SELECT nome FROM disciplinas WHERE id = $_POST['disciplina'];";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nomeDisciplina = $row['nome'];
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
                header('Location: presenca.php');
                exit();
            }

            $hora = $_POST['hora'];
            $dia = $_POST['dia'];
            if (isset($_POST['individual']) && $_POST['individual'] == "on") {
                $individual = 1;
            } else {
                $individual = 0;
            }
            $idProfessor = $_SESSION['id'];

            $sql = "INSERT INTO alunos_presenca (idAluno, idDisciplina, duracao, dia, individual) VALUES (?, ?, ?, ?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("iiiisi", $idAluno, $idDisciplina, $idProfessor, $hora, $dia, $individual);
                if ($result->execute()) {
                    $idPresenca = $con->insert_id;
                    notificacao('success', 'Presença registrada com sucesso!');
                    registrar_log($con, "Criar presença", "id: " . $idPresenca . ", aluno: " . $row["nome"] . ", disciplina: " . $nomeDisciplina . ", duracao: " . $_POST['hora'] . ", dia: " . $_POST['dia'] . ", individual: " . $_POST['individual']);
                } 
                else {
                    notificacao('danger', 'Erro ao inserir presença: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao inserir presença: ' . $result->error);
            }

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