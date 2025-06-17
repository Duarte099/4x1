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
?>
    <title>4x1 | Nova Transação</title>
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
                    <div class="col-12 col-md-10 col-lg-8 mx-auto">
                        <form action="transacoesInserir?op=save" method="POST">
                            <div class="container2">
                                <div class="page-inner">
                                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                                        <div>
                                            <h2 class="fw-bold mb-3">Nova Transação</h2>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label for="descricao" class="form-label">Descrição:</label>
                                            <input type="input" name="descricao" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="valor" class="form-label">Valor:</label>
                                            <input type="number" class="form-control" step="0.01" min="0" name="valor" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="categoria" class="form-label">Categoria:</label>
                                            <select name="categoria" class="form-control">
                                                <?php
                                                    $sql = "SELECT id, nome, tipo FROM categorias;";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) { ?>
                                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['tipo']; ?> | <?php echo $row['nome']; ?></option>
                                                            <?php 
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Criar transação</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>

