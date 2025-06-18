<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 4;
    
    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    $stmt = $con->prepare('SELECT id FROM professores WHERE id = ?');
    $stmt->bind_param('i', $_GET['idProf']);
    $stmt->execute(); 
    $stmt->store_result();
    if ($stmt->num_rows <= 0) {
        header('Location: dashboard');
        exit();
    }
?>
  <title>Logs Professores | 4x1</title>
</head>
    <body>
        <div class="wrapper">
        <?php  
            include('./sideBar.php'); 
        ?>
            <div class="container">
                <div class="page-inner">
                    <div
                        class="d-flex justify-content-between align-items-center pt-2 pb-4"
                    >
                        <div>
                            <h3 class="fw-bold mb-3 mb-md-0">Logs Professores</h3>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table
                                        id="tabela-professores-logs"
                                        class="display table table-striped table-hover"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Professor</th>
                                                <th>Log</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Professor</th>
                                                <th>Log</th>
                                                <th>Data</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                                //query para selecionar todos os administradores
                                                $sql = "SELECT nome, DATE_FORMAT(dataLog, '%d-%m-%Y %H:%i:%s') AS dataLog, logFile FROM professores_logs INNER JOIN professores ON idProfessor = id WHERE idProfessor = " . $_GET['idProf'];
                                                $result = $con->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td><?php echo $row['nome'] ?></td>
                                                            <td><?php echo $row['logFile'] ?></td>
                                                            <td><?php echo $row['dataLog'] ?></td>
                                                        </tr>
                                                    <?php }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $("#tabela-professores-logs").DataTable({
                pageLength: 6,
                order: [[1, 'asc']],
                language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
                },
                initComplete: function () {
                    this.api()
                    .columns()
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select class="form-select"><option value=""></option></select>'
                        )
                        .appendTo($(column.footer()).empty())
                        .on("change", function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());

                            column
                            .search(val ? "^" + val + "$" : "", true, false)
                            .draw();
                        });

                        column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append(
                                '<option value="' + d + '">' + d + "</option>"
                            );
                        });
                    });
                },
            });
        </script>
        <?php 
            include('./endPage.php'); 
        ?>