<?php
    //Inclui a conexão à base de dados na página
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    //Caso a variavel op nao esteja declarado e o metodo não seja post volta para a página da dashboard
    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //Obtem o operação 
        $op = $_GET['op'];

        if ($op == 'save') {
            $stmt = $con->prepare("SELECT * FROM categorias WHERE id = ?");
            $stmt->bind_param("i", $_POST['categoria']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID da categoria inválido.');
                header('Location: transacoesCriar');
                exit();
            }
            else{
                $rowTransacao = $result->fetch_assoc();
            }
            //query sql para inserir os dados do aluno
            $sql = "INSERT INTO transacoes (idCategoria, descricao, valor) VALUES (?, ?, ?)";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("isd", $_POST["categoria"], $_POST["descricao"], $_POST["valor"]);
                if ($result->execute()) {
                    //Obtem o id do novo professor inserido
                    $idTransacao = $con->insert_id;
                    notificacao('success', 'Transação criada com sucesso!');
                    registrar_log($con, "Criar transação", "id: " . $idTransacao . ", categoria: " . $rowTransacao['nome'] . ", tipo: " . $rowTransacao['tipo'] . ", descrição: " . $_POST['descricao'] . ", valor: " . $_POST['valor']);
                } 
                else {
                    notificacao('danger', 'Erro ao criar transação: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao criar transação: ' . $result->error);
            }

            //Após tudo ser concluido redireciona para a página dos alunos
            header('Location: transacoes');
        }
        //Se a operação for edit
        elseif ($op == 'edit') {
            $idTransacao = $_GET['idTransacao'];

            $stmt = $con->prepare('SELECT t.*, c.nome, c.tipo FROM transacoes as t INNER JOIN categorias as c ON t.idCategoria = c.id WHERE t.id = ?');
            $stmt->bind_param('i', $idTransacao);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID de transação inválido.');
                header('Location: dashboard');
                exit();
            }
            else {
                $rowTransacao = $result->fetch_assoc();
            }

            $stmt = $con->prepare('SELECT nome, tipo FROM categorias WHERE id = ?');
            $stmt->bind_param('i', $_POST['categoria']);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                notificacao('warning', 'ID da categoria inválido.');
                header('Location: transacoesEdit');
                exit();
            }
            else {
                $rowCategoria = $result->fetch_assoc();
            }

            $sql = "UPDATE transacoes SET idCategoria = ?, descricao = ?, valor = ? WHERE id = ?";
            $result = $con->prepare($sql);
            if ($result) {
                $result->bind_param("ssdi", $_POST["categoria"], $_POST["descricao"], $_POST["valor"], $idTransacao);
                if ($result->execute()) {
                    notificacao('success', 'Transação editada com sucesso!');
                    $detalhes = gerar_detalhes_alteracoes(
                        $rowTransacao,
                        [
                            'nome' => $rowCategoria['nome'],
                            'tipo' => $rowCategoria['tipo'],
                            'descricao' => $_POST['descricao'],
                            'valor' => $_POST['valor'],
                        ]
                    );
                    if (!empty($detalhes)) {
                        registrar_log($con, "Editar transação", "id: " . $idTransacao . ", " . $detalhes);
                    }
                } 
                else {
                    notificacao('danger', 'Erro ao editar transação: ' . $result->error);
                }

                $result->close();
            }
            else {
                notificacao('danger', 'Erro ao editar transação: ' . $result->error);
            }
            header('Location: transacoes');
        }
        else {
            notificacao('warning', 'Operação inválida.');
            header('Location: dashboard');
            exit();
        }
    }
    else {
        header('Location: dashboard');
        exit();
    }
?>