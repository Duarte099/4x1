<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 13;
    
    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    $stmt = $con->prepare("SELECT * FROM administrador WHERE id = ?");
    $stmt->bind_param("i", $_GET['idAdmin']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows <= 0) {
        notificacao('warning', 'ID do administrador inválido.');
        header('Location: dashboard.php');
        exit();
    }
?>
  <title>Logs Administradores | 4x1</title>
</head>
  <body>
    <div class="wrapper">
      <?php  
        include('./sideBar.php'); 
      ?>
        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Logs Administradores</h3>
              </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="tabela-administradores-logs"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Administrador</th>
                            <th>Log</th>
                            <th>Data</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Administrador</th>
                            <th>Log</th>
                            <th>Data</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT nome, DATE_FORMAT(dataLog, '%d-%m-%Y %H:%i:%s') AS dataLog, logFile FROM administrador_logs INNER JOIN administrador ON idAdministrador = id WHERE idAdministrador = ;" . $_GET['idAdmin'];
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
    </div>
    <?php   
      include('./endPage.php'); 
    ?>
    <script>
        $("#tabela-administradores-logs").DataTable({
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
  </body>
</html>
