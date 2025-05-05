<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        
        //Se a operação for edit
        if ($op == 'edit') {
            // Nem password nem imagem foram alteradas
            if ($_SESSION["tipo"] == "professor") {
                echo 1;
                $sql = "UPDATE professores SET nome = ?, email = ?, contacto = ? WHERE id = ?";
            }
            else{
                echo 2; 
                $sql = "UPDATE administrador SET nome = ?, email = ? WHERE id = ?";
            }
            $stmt = $con->prepare($sql);
            if ($stmt) {
                if ($_SESSION["tipo"] == "professor") {
                    echo 3;
                    $stmt->bind_param("sssi", $_POST['nome'], $_POST['email'], $_POST["contacto"], $_SESSION["id"]);
                }
                else{
                    echo 4;
                    $stmt->bind_param("ssi", $_POST['nome'], $_POST['email'], $_SESSION["id"]);
                }
                
                if ($stmt->execute()) {
                    echo 5;
                    notificacao('success', 'Perfil atualizado com sucesso!');
                    if ($_SESSION["tipo"] == "professor") {
                        registrar_log("prof", "O professor [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                    }
                    else{
                        registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " atualizou o seu perfil");
                    }
                } else {
                    notificacao('danger', 'Erro ao atualizar perfil: ' . $stmt->error);
                }
                $stmt->close();
            } else {
                notificacao('danger', 'Erro ao atualizar perfil: ' . $con->error);
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