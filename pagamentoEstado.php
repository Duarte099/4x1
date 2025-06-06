<?php
  //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
  include('./head.php'); 

  //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
  $estouEm = 7;
?>
  <title>4x1 | Estado Pagamentos</title>
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
                <h3 class="fw-bold mb-3">Estado Pagamentos</h3>
              </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="tabela-pagamentos-atrasados"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Ensino</th>
                            <th>Nome</th>
                            <th>Data Nascimento</th>
                            <th>Estado do pagamento</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Ensino</th>
                            <th>Nome</th>
                            <th>Data Nascimento</th>
                            <th>Estado do pagamento</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            $mesAnterior = date('m') - 1;
                            $anoAtual = date('Y');

                            if ($mesAnterior == 0) {
                              $mesAnterior = 12;
                              $anoAtual -= 1;
                            }
                            //query para selecionar todos os administradores
                            $sql = "SELECT *, IF(a.ano>=1 AND a.ano<=4, \"1º CICLO\", IF(a.ano>4 AND a.ano<7, \"2º CICLO\", IF(a.ano>6 AND a.ano<=9, \"3º CICLO\", IF(a.ano>9 AND a.ano<=12, \"SECUNDÁRIO\", IF(a.ano=0, \"UNIVERSIDADE\", \"ERRO\"))))) as ensino, 
                                        CASE 
                                            WHEN pagoEm = '0000-00-00 00:00:00' AND DAY(CURDATE()) > 8 THEN 'Atrasado'
                                            WHEN pagoEm != '0000-00-00 00:00:00' THEN 'Pago'
                                            WHEN pagoEm = '0000-00-00 00:00:00' AND DAY(CURDATE()) < 8 THEN 'Pendente'
                                            ELSE 'Outro'
                                        END AS estado
                                    FROM alunos_recibo as ar 
                                    INNER JOIN alunos as a ON idAluno = a.id 
                                    WHERE mes = {$mesAnterior} AND ar.ano = {$anoAtual}";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if ($row['estado'] == "Pago") {
                                        $corStatus = "2ecc71";
                                    }
                                    elseif ($row['estado'] == "Pendente") {
                                        $corStatus = "f1c40f";
                                    }
                                    elseif ($row['estado'] == "Atrasado") {
                                        $corStatus = "ff0000";
                                    }
                                    else {
                                        $corStatus = "";
                                    } ?>
                                <tr>
                                    <td><?php echo $row['ensino'] ?></td>
                                    <td><?php echo $row['nome'] ?></td>
                                    <td><?php echo $row['dataNascimento'] ?></td>
                                    <td style="color: #$corStatus;"><?php echo $row['estado'] ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a
                                                href="alunoEdit.php?idAluno=<?php echo $row['id'] ?>&tab=recibo"
                                                class="btn btn-link btn-primary btn-lg"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Ver recibo"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
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
    
    <script>
        $("#tabela-pagamentos-atrasados").DataTable({
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
