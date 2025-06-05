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
                            <th>Aluno</th>
                            <th>Horas grupo</th>
                            <th>Horas realizadas grupo</th>
                            <th>Horas individual</th>
                            <th>Horas realizadas individual</th>
                            <th>Estado</th>
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
                            <th>Estado</th>
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
                                        $corStatus = "2ecc71";
                                    }
                                    else {
                                        $row['verificado'] = "Pendente";
                                        $corStatus = "ff0000";
                                    }
                                    ?>
                                        <tr>
                                            <td><?php echo $row['nomeAluno'] ?></td>
                                            <td><?php echo $row['packGrupo'] ?></td>
                                            <td><?php echo $row['horasRealizadasGrupo'] ?></td>
                                            <td><?php echo $row['packIndividual'] ?></td>
                                            <td><?php echo $row['horasRealizadasIndividual'] ?></td>
                                            <td style="color: #<?php echo $corStatus; ?>"><?php echo $row['verificado'] ?></td>
                                            <td><?php echo $row['mes'] ?>-<?php echo $row['ano'] ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <?php if ($row['verificado'] == "Pendente") { ?>
                                                        <button
                                                            type="button"
                                                            class="btn btn-link btn-primary btn-lg"
                                                            data-original-title="Editar transação"
                                                            onclick="window.location.href='recibosAlunosVerificar.php?idRecibo=<?php echo $row['idRecibo']; ?>'"
                                                        >
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    <?php } ?>
                                                    <button
                                                        type="button"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-original-title="Editar transação"
                                                        onclick="window.location.href='alunoEdit.php?idAluno=<?php echo $row['idAluno']; ?>&mes=<?php echo $row['mes']; ?>-<?php echo $row['ano']; ?>&tab=recibo'"
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
