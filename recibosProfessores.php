<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 11;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }
?>
  <title>4x1 | Recibos professores</title>
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
                    <h3 class="fw-bold mb-3">Recibos professores</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="pagamentoProfessorInserir.php" class="btn btn-primary btn-round">Assumir pagamentos</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="multi-filter-select"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Professor</th>
                            <th>Horas 1ºCiclo</th>
                            <th>Horas 2ºCiclo</th>
                            <th>Horas 3ºCiclo</th>
                            <th>Horas Secundário</th>
                            <th>Horas Universidade</th>
                            <th>Estado Verificação</th>
                            <th>Estado Pagamento</th>
                            <th>Data</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Professor</th>
                            <th>Horas 1ºCiclo</th>
                            <th>Horas 2ºCiclo</th>
                            <th>Horas 3ºCiclo</th>
                            <th>Horas Secundário</th>
                            <th>Horas Universidade</th>
                            <th>Estado Verificação</th>
                            <th>Estado Pagamento</th>
                            <th>Data</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT * FROM (
                                        SELECT 
                                            pr.id AS idRecibo, 
                                            p.id AS idProfessor, 
                                            p.nome AS nomeProf, 
                                            pr.horasDadas1Ciclo, 
                                            pr.horasDadas2Ciclo, 
                                            pr.horasDadas3Ciclo, 
                                            pr.horasDadasSecundario, 
                                            pr.horasDadasUniversidade, 
                                            pr.ano, 
                                            pr.mes, 
                                            pr.verificado,
                                            0 AS prioridade
                                        FROM 
                                            professores_recibo AS pr
                                        INNER JOIN 
                                            professores AS p ON pr.idProfessor = p.id
                                        WHERE 
                                            pr.verificado = 0

                                        UNION ALL
                                        SELECT 
                                            pr.id AS idRecibo, 
                                            p.id AS idProfessor, 
                                            p.nome AS nomeProf, 
                                            pr.horasDadas1Ciclo, 
                                            pr.horasDadas2Ciclo, 
                                            pr.horasDadas3Ciclo, 
                                            pr.horasDadasSecundario, 
                                            pr.horasDadasUniversidade, 
                                            pr.ano, 
                                            pr.mes, 
                                            pr.verificado,
                                            1 AS prioridade
                                        FROM 
                                            professores_recibo AS pr
                                        INNER JOIN 
                                            professores AS p ON pr.idProfessor = p.id
                                        WHERE 
                                            pr.verificado = 1
                                            AND pr.ano = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                                            AND pr.mes = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
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
                                        $corStatusVerificacao = "2ecc71";
                                        if ($row['pago'] == 1) {
                                            $row['pago'] = "Pago";
                                            $corStatusPagamento = "2ecc71";
                                        }
                                    }
                                    else {
                                        $row['verificado'] = "Pendente";
                                        $corStatusVerificacao = "ff0000";
                                        $row['pago'] = "Pendente";
                                        $corStatusPagamento = "ff0000";
                                    }
                                    ?>
                                        <tr>
                                            <td><?php echo $row['nomeProf'] ?></td>
                                            <td><?php echo $row['horasDadas1Ciclo'] ?></td>
                                            <td><?php echo $row['horasDadas2Ciclo'] ?></td>
                                            <td><?php echo $row['horasDadas3Ciclo'] ?></td>
                                            <td><?php echo $row['horasDadasSecundario'] ?></td>
                                            <td><?php echo $row['horasDadasUniversidade'] ?></td>
                                            <td style="color: #<?php echo $corStatusVerificacao; ?>"><?php echo $row['verificado'] ?></td>
                                            <td style="color: #<?php echo $corStatusPagamento; ?>"><?php echo $row['pago'] ?></td>
                                            <td><?php echo $row['mes'] ?>-<?php echo $row['anp'] ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <?php if ($row['verificado'] == "Pendente") { ?>
                                                        <button
                                                            type="button"
                                                            class="btn btn-link btn-primary btn-lg"
                                                            data-original-title="Verificar recibo"
                                                            onclick="window.location.href='recibosProfessoresVerificar.php?idRecibo=<?php echo $row['idRecibo']; ?>'"
                                                        >
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    <?php } ?>
                                                    <button
                                                        type="button"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-original-title="Editar recibo"
                                                        onclick="window.location.href='professorEdit.php?idProf=<?php echo $row['idProfessor']; ?>&mes=<?php echo $row['mes']; ?>-<?php echo $row['ano']; ?>&tab=recibo'"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
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
  </body>
</html>
