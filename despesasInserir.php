<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    if (isset($_GET['op']) && $_GET['op'] == 'deleteDespesa' && isset($_GET['idDespesa'])) {
        $idDespesa = $_GET['idDespesa'];
        $stmt = $con->prepare('SELECT * FROM despesas WHERE id = ?');
        $stmt->bind_param('i', $idDespesa);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            notificacao('warning', 'ID de despesa inválido.');
            header('Location: dashboard.php');
            exit();
        }

        $sql = "DELETE FROM despesas WHERE id = ?";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("i", $idDespesa);
            if ($result->execute()) {
                notificacao('success', 'Despesa eliminada com sucesso!');
                registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " eliminou a despesa [" . $idDespesa . "].");
            } 
            else {
                notificacao('danger', 'Erro ao eliminar despesa: ' . $result->error);
            }
            $result->close();
        }
        else {
            notificacao('danger', 'Erro ao eliminar despesa: ' . $result->error);
        }
        header('Location: despesas.php');
        exit();
    }

    if (isset($_GET['op']) && $_GET['op'] == 'deleteCategoria' && isset($_GET['idCategoria'])) {
        $idCategoria = $_GET['idCategoria'];
        $stmt = $con->prepare('SELECT * FROM categorias WHERE id = ?');
        $stmt->bind_param('i', $idCategoria);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            notificacao('warning', 'ID de categoria inválido.');
            header('Location: dashboard.php');
            exit();
        }

        $sql = "DELETE FROM categorias WHERE id = ?";
        $result = $con->prepare($sql);
        if ($result) {
            $result->bind_param("i", $idCategoria);
            if ($result->execute()) {
                notificacao('success', 'Categoria eliminada com sucesso!');
                registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " eliminou a categoria [" . $idCategoria . "].");
            } 
            else {
                notificacao('danger', 'Erro ao eliminar categoria: ' . $result->error);
            }
            $result->close();
        }
        else {
            notificacao('danger', 'Erro ao eliminar categoria: ' . $result->error);
        }
        header('Location: despesas.php');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard.php
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'saveDespesa') {
            $sql = "INSERT INTO despesas (despesa, valor) VALUES (?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sd", $_POST['despesa'], $_POST['valor']);
                if ($result->execute()) {
                    $idDespesa = $con->insert_id;
                    notificacao('success', 'Despesa inserida com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " criou a despesa [" . $idDespesa . "].");

                    for ($i=1; $i < 13; $i++) { 
                        if (isset($_POST['despesa_'. $i]) && !empty($_POST['despesa_'. $i])) {
                            $sql = "INSERT INTO despesas_meses (idDespesa, mes) VALUES (?, ?)";
                            $result = $con->prepare($sql);
                            if ($result) {
                                $result->bind_param("ii", $idDespesa, $i);
                            }
                            $result->execute();
                        }
                    }   
                } 
                else {
                    notificacao('danger', 'Erro ao criar despesa: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar despesa: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: despesas.php');
        }
        elseif ($op == 'editDespesa') {
            $idDespesa = $_GET['idDespesa'];
            $stmt = $con->prepare('SELECT * FROM despesas WHERE id = ?');
            $stmt->bind_param('i', $idDespesa);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID de despesa inválido.');
                header('Location: dashboard.php');
                exit();
            }

            $sql = "UPDATE despesas SET despesa = ?, valor = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("sdi", $_POST['despesa'], $_POST['valor'], $idDespesa);
                if ($result->execute()) {
                    notificacao('success', 'Despesa alterada com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " alterou a despesa [" . $idDespesa . "].");

                    for ($i=1; $i < 13 ; $i++) { 
                        $result4 = $con->prepare('SELECT id FROM despesas_meses WHERE idDespesa = ? AND mes = ?');
                        $result4->bind_param('ii', $idDespesa, $i);
                        $result4->execute();
                        $result4->store_result();
                        $result4->bind_result($idDespesaMes);
                        $result4->fetch();
                        if ($result4->num_rows <= 0) {
                            if (isset($_POST['despesa_'. $i]) && !empty($_POST['despesa_'. $i])) {
                                $sql = "INSERT INTO despesas_meses (idDespesa, mes) VALUES (?, ?)";
                                $result3 = $con->prepare($sql);
                                if ($result3) {
                                    $result3->bind_param("ii", $idDespesa, $i);
                                }
                                $result3->execute();
                            }
                        }
                        else if (!isset($_POST['despesa_'. $i]) && empty($_POST['despesa_'. $i])) {
                            $result5 = $con->prepare('DELETE FROM despesas_meses WHERE id = ?');
                            $result5->bind_param("i", $idDespesaMes );
                            $result5->execute();
                        }
                    }
                } 
                else {
                    notificacao('danger', 'Erro ao alterar despesa: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao alterar despesa: ' . $result->error);
            }
            header('Location: despesas.php');
        }
        elseif ($op == 'saveCategoria') {
            $sql = "INSERT INTO categorias (nome, tipo) VALUES (?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ss", $_POST['categoria'], $_POST['tipo']);
                if ($result->execute()) {
                    $idCategoria = $con->insert_id;
                    notificacao('success', 'Despesa inserida com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " criou a categoria [" . $idCategoria . "].");
                } 
                else {
                    notificacao('danger', 'Erro ao criar categoria: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar categoria: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: despesas.php');
        }
        elseif ($op == 'editCategoria') {
            $idCategoria = $_GET['idCategoria'];
            $stmt = $con->prepare('SELECT * FROM categorias WHERE id = ?');
            $stmt->bind_param('i', $idCategoria);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID de categoria inválido.');
                header('Location: dashboard.php');
                exit();
            }

            $sql = "UPDATE categorias SET nome = ?, tipo = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ssi", $_POST['categoria'], $_POST['tipo'], $idCategoria);
                if ($result->execute()) {
                    notificacao('success', 'Categoria alterada com sucesso!');
                    registrar_log("admin", "O administrador [" . $_SESSION["id"] . "]" . $_SESSION["nome"] . " alterou a categoria [" . $idCategoria . "].");
                } 
                else {
                    notificacao('danger', 'Erro ao alterar categoria: ' . $result->error);
                }
                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao alterar categoria: ' . $result->error);
            }
            header('Location: despesas.php');
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