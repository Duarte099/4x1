<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 12;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Obtem o id do admin via GET
    $idAdmin = $_GET['idAdmin'];

    $stmt = $con->prepare("SELECT * FROM administrador WHERE id = ?");
    $stmt->bind_param("i", $idAdmin);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowAdmin = $result->fetch_assoc();
    } else {
        notificacao('warning', 'ID do administrador inválido.');
        header('Location: admin.php');
        exit();
    }
?>
    <title>4x1 | Editar Administrador</title>
    <style>
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
                    <div class="card-body">
                        <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="editar-admin-tab" data-bs-toggle="pill" href="#editar-admin" role="tab" aria-controls="editar-admin" aria-selected="true">Ficha do Administrador</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="perms-admin-tab" data-bs-toggle="pill" href="#perms-admin" role="tab" aria-controls="perms-admin" aria-selected="false">Permissões do administrador</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="editar-admin" role="tabpanel" aria-labelledby="editar-admin-tab">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-10 col-lg-8 mx-auto">
                                        <form action="adminInserir?idAdmin=<?php echo $idAdmin ?>&op=edit" method="POST" onsubmit="return verificarPasswords(event, '<?php echo $rowAdmin['email']; ?>')">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="nome" class="form-label">Nome:</label>
                                                    <input type="text" class="form-control" name="nome" value="<?php echo $rowAdmin['nome']; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email" class="form-label">Email:</label>
                                                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $rowAdmin['email']; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="estado" class="form-label">Estado:</label>
                                                    <select class="form-control" name="estado" required>
                                                        <option <?php if ($rowAdmin['active'] == 1) { echo "selected"; }?> value="1">Ativo</option>
                                                        <option <?php if ($rowAdmin['active'] == 0) { echo "selected"; }?> value="0">Desativado</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">Password:</label>
                                                    <input type="password" class="form-control" name="password" id="password">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="passwordConfirm" class="form-label">Confirmar Password:</label>
                                                    <input type="password" class="form-control" name="passwordConfirm" id="passwordConfirm">
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-left">
                                                <button type="submit" class="btn btn-primary px-5">Guardar alterações</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="perms-admin" role="tabpanel" aria-labelledby="perms-admin-tab">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function verificarPasswords(e, emailAntigo) {
                e.preventDefault();
                const password = document.getElementById("password").value;
                const confirm = document.getElementById("passwordConfirm").value;
                const emailAdmin = document.getElementById("email").value;

                let erro = 0;

                if (password !== confirm) {
                    $.notify({
                        message: 'As palavras passes não coincidem!',
                        title: 'Notificação',
                        icon: 'fa fa-info-circle',
                    }, {
                        type: 'warning',
                        placement: {
                            from: 'top',
                            align: 'right'
                        },
                        delay: 3000
                    });
                    erro += 1;
                }

                if (erro === 0 && emailAntigo !== emailAdmin) {
                    $.ajax({
                        url: 'json.obterEmails.php',
                        type: 'GET',
                        success: function(response) 
                        {
                            var data = JSON.parse(response);
                            for (let i = 0; i < data.length; i++) {
                                if (data[i].email === emailAdmin ) {
                                    $.notify({
                                        message: 'Esse email já existe no sistema.',
                                        title: 'Notificação',
                                        icon: 'fa fa-info-circle',
                                    }, {
                                        type: 'warning',
                                        placement: {
                                            from: 'top',
                                            align: 'right'
                                        },
                                        delay: 3000
                                    });

                                    erro += 1;
                                    break;
                                }
                            }
                            if (erro === 0) {
                                e.target.submit();
                            }
                        },
                        error: function() {
                            console.error('Erro ao buscar os emails.');
                        }
                    });
                }
            }
        </script>
        <?php include('./endPage.php'); ?>
    </body>
</html>