<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 10;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }
?>
  <title>4x1 | Recibos alunos</title>
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
                    <h3 class="fw-bold mb-3">Recibos alunos</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="recibosNotificacao.php" class="btn btn-primary btn-round">Notificar alunos</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="tabela-alunos-recibos"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Aluno</th>
                            <th>Horas grupo</th>
                            <th>Horas realizadas grupo</th>
                            <th>Horas individual</th>
                            <th>Horas realizadas individual</th>
                            <th>Estado verificação</th>
                            <th>Estado notificação</th>
                            <th>Estado pagamento</th>
                            <th>Data</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Aluno</th>
                            <th>Horas grupo</th>
                            <th>Horas realizadas grupo</th>
                            <th>Horas individual</th>
                            <th>Horas realizadas individual</th>
                            <th>Estado verificação</th>
                            <th>Estado notificação</th>
                            <th>Estado pagamento</th>
                            <th>Data</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT * FROM (
                                    SELECT 
                                        ar.id AS idRecibo, 
                                        a.id AS idAluno, 
                                        a.nome AS nomeAluno, 
                                        ar.packGrupo, 
                                        ar.horasRealizadasGrupo, 
                                        ar.packIndividual, 
                                        ar.horasRealizadasIndividual, 
                                        ar.mes, 
                                        ar.ano, 
                                        ar.verificado,
                                        ar.notificacao,
                                        ar.pago,
                                        0 AS prioridade
                                    FROM 
                                        alunos_recibo AS ar
                                    INNER JOIN 
                                        alunos AS a ON ar.idAluno = a.id
                                    WHERE 
                                        ar.verificado = 0

                                    UNION ALL
                                    SELECT 
                                        ar.id AS idRecibo, 
                                        a.id AS idAluno, 
                                        a.nome AS nomeAluno, 
                                        ar.packGrupo, 
                                        ar.horasRealizadasGrupo, 
                                        ar.packIndividual, 
                                        ar.horasRealizadasIndividual, 
                                        ar.mes, 
                                        ar.ano, 
                                        ar.verificado,
                                        ar.notificacao,
                                        ar.pago,
                                        1 AS prioridade
                                    FROM 
                                        alunos_recibo AS ar
                                    INNER JOIN 
                                        alunos AS a ON ar.idAluno = a.id
                                    WHERE 
                                        ar.verificado = 1
                                        AND ar.ano = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                                        AND ar.mes = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                                ) AS recibos
                                ORDER BY 
                                    prioridade ASC,
                                    ano DESC,
                                    mes DESC;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if ($row['verificado'] == 1) {
                                        $row['verificado'] = "Verificado";
                                        $corVerificacao = "2ecc71";
                                    }
                                    else {
                                        $row['verificado'] = "Pendente";
                                        $corVerificacao = "ff0000";
                                    }
                                    if ($row['notificacao'] == 1) {
                                        $row['notificacao'] = "Notificado";
                                        $corNotificacao = "2ecc71";
                                    }
                                    else {
                                        $row['notificacao'] = "Pendente";
                                        $corNotificacao = "ff0000";
                                    }
                                    if ($row['pago'] == 1) {
                                        $row['pago'] = "Pago";
                                        $corPagamento = "2ecc71";
                                    }
                                    else {
                                        $row['pago'] = "Pendente";
                                        $corPagamento = "ff0000";
                                    }
                                    ?>
                                        <tr>
                                            <td><?php echo $row['nomeAluno'] ?></td>
                                            <td><?php echo $row['packGrupo'] ?></td>
                                            <td><?php echo $row['horasRealizadasGrupo'] ?></td>
                                            <td><?php echo $row['packIndividual'] ?></td>
                                            <td><?php echo $row['horasRealizadasIndividual'] ?></td>
                                            <td style="color: #<?php echo $corVerificacao; ?>"><?php echo $row['verificado'] ?></td>
                                            <td style="color: #<?php echo $corNotificacao; ?>"><?php echo $row['notificacao'] ?></td>
                                            <td style="color: #<?php echo $corPagamento; ?>"><?php echo $row['pago'] ?></td>
                                            <td><?php echo $row['mes'] ?>-<?php echo $row['ano'] ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <?php if ($row['verificado'] == "Pendente") { ?>
                                                        <a
                                                            href="recibosAlunosVerificar.php?idRecibo=<?php echo $row['idRecibo']; ?>"
                                                            class="btn btn-link btn-primary btn-lg"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Verificar recibo"
                                                        >
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <a
                                                        href="alunoEdit.php?idAluno=<?php echo $row['idAluno']; ?>&mes=<?php echo $row['mes']; ?>-<?php echo $row['ano']; ?>&tab=recibo"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Editar recibo"
                                                    >
                                                        <i class="fa fa-edit"></i>
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
    <?php   
      include('./endPage.php'); 
    ?>
    <script>
        $("#tabela-alunos-recibos").DataTable({
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
