<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 15;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    //Obtem o id do admin via GET
    $idTransacao = $_GET['idTransacao'];
    $stmt = $con->prepare("SELECT * FROM transacoes WHERE id = ?");
    $stmt->bind_param("i", $idTransacao);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowTransacao = $result->fetch_assoc();
    } else {
        notificacao('warning', 'ID de transação inválido.');
        header('Location: transacoes');
        exit();
    }
?>  
    <title>4x1 | Editar Transação</title>
    <style>
        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            min-height: 100vh !important;
        }
    </style>
</head>
    <body>
        <div class="wrapper">
            <?php 
                include('./sideBar.php'); 
            ?>
            <div class="container">
                <div class="card">
                    <form action="transacoesInserir?idTransacao=<?php echo $idTransacao ?>&op=edit" method="POST">
                        <div class="page-inner">
                            <div class="d-flex align-items-left align-items-md-center flex-row pt-2 pb-4" style="text-align: center;">
                                <div>
                                    <h2 class="fw-bold mb-3">Editar Transação</h2>
                                </div>
                            </div>
                            <div class="container2">
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 64%;">
                                            <label>DESCRIÇÃO:</label>
                                            <input type="input" name="descricao" value="<?php echo $rowTransacao['descricao']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 34%;">
                                            <label>VALOR:</label>
                                            <input type="number" step="0.01" min="0" name="valor" value="<?php echo $rowTransacao['valor']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 49%;">
                                            <label>CATEGORIA:</label>
                                            <select name="categoria" class="select-box">
                                                <?php
                                                    $sql = "SELECT id, nome, tipo FROM categorias;";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            if ($rowTransacao['idCategoria'] == $row['id']) { ?>
                                                                <option selected value="<?php echo $row['id']; ?>"><?php echo $row['tipo']; ?> | <?php echo $row['nome']; ?></option>
                                                            <?php } 
                                                            else {
                                                            ?>
                                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['tipo']; ?> | <?php echo $row['nome']; ?></option>
                                                            <?php }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar alterações</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php 
            include('./endPage.php'); 
        ?>

