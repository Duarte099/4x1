<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'save') {
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO administrador (nome, email, pass) VALUES (?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sss", $_POST['nome'], $_POST['email'], $passwordHash);
                if ($result->execute()) {
                    notificacao('success', 'Administrador criado com sucesso!');
                    registrar_log("O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " criou o administrador [" . $idAdmin . "]" . $_POST['nome'] . ".");
                } 
                else {
                    notificacao('danger', 'Erro ao criar administrador: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar administrador: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: admin.php');
        }
        //Se a operação for edit
        elseif ($op == 'edit') {
            $idAdmin = $_GET['idAdmin'];

            if ($_POST['estado'] == "Ativo") {
                $_POST['estado'] = 1;
            }
            else {
                $_POST['estado'] = 0;
            }

            //Se a password e a imagem não tiverem vazios então insere altera tudo 
            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE administrador SET nome = '{$_POST['nome']}', email = '{$_POST['email']}', pass = '$passwordHash', active = {$_POST['estado']} WHERE id = $idAdmin;";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("sss", $_POST['nome'], $_POST['email'], $passwordHash);
                    if ($result->execute()) {
                        notificacao('success', 'Administrador editado com sucesso!');
                    } 
                    else {
                        notificacao('danger', 'Erro ao editar administrador: ' . $result->error);
                    }
    
                    $result->close();
                }
                else {
                    notificacao('danger', 'Erro ao editar administrador: ' . $result->error);
                }
            }
            //senão altera tudo menos a password e a imagem
            else {
                $sql = "UPDATE administrador SET nome = {$_POST['nome']}, email = {$_POST['email']}, active = {$_POST['status']} WHERE id = $idAdmin;";
                $result = $con->prepare($sql);
                if ($result) {
                    $result->bind_param("sss", $_POST['nome'], $_POST['email'], $passwordHash);
                    if ($result->execute()) {
                        notificacao('success', 'Administrador editado com sucesso!');
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o administrador [" . $idAdmin . "]" . $_POST['nome'] . ".");
                    } 
                    else {
                        notificacao('danger', 'Erro ao editar administrador: ' . $result->error);
                    }
    
                    $result->close();
                }
                else {
                    notificacao('danger', 'Erro ao editar administrador: ' . $result->error);
                }
            }

            header('Location: admin.php');
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