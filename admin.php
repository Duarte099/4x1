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
?>
  <title>Administradores | 4x1</title>
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
                <h3 class="fw-bold mb-3">Administradores</h3>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="adminCriar.php" class="btn btn-primary btn-round">Adicionar administrador</a>
              </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="tabela-administradores"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Estado</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Estado</th>
                            <th>Nome</th>
                            <th>Email</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT id, nome, email, estado, adminMor FROM administrador;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if ($row['estado'] == 1) {
                                        $row['estado'] = "Ativo";
                                    }
                                    else {
                                        $row['estado'] = "Inativo";
                                    }    
                                    ?>
                                        <tr>
                                            <td><?php echo $row['estado'] ?></td>
                                            <td><?php echo $row['nome'] ?></td>
                                            <td><?php echo $row['email'] ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <?php if ($row['adminMor'] == 0){ ?>
                                                        <a
                                                            href="adminEdit.php?idAdmin=<?php echo $row['id']; ?>"
                                                            class="btn btn-link btn-primary btn-lg"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Editar administrador"
                                                        >
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <a
                                                        href="adminLogs.php?idAdmin=<?php echo $row['id'] ?>"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Logs administrador"
                                                    >
                                                        <i class="fa-file-alt"></i>
                                                    </a>
                                                </div>
                                            </td>   
                                        </tr>
                                    <?php
                                }
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
    
    <script>
        $("#tabela-administradores").DataTable({
            pageLength: 6,
            order: [
                [0, 'asc'],
                [1, 'asc']
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
            },
            columnDefs: [
                { targets: 3, orderable: false }
            ],
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
  </body>
</html>