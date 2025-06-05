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
                            <th>Estado</th>
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
                            <th>Estado</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT pr.id as idRecibo, p.id as idProfessor, p.nome as nomeProf, pr.horasDadas1Ciclo, pr.horasDadas2Ciclo, pr.horasDadas3Ciclo, pr.horasDadasSecundario, pr.horasDadasUniversidade, pr.ano, pr.mes, pr.verificado FROM professores_recibo as pr INNER JOIN professores as p ON pr.idProfessor = p.id;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['nomeProf'] ?></td>
                                            <td><?php echo $row['horasDadas1Ciclo'] ?></td>
                                            <td><?php echo $row['horasDadas2Ciclo'] ?></td>
                                            <td><?php echo $row['horasDadas3Ciclo'] ?></td>
                                            <td><?php echo $row['horasDadasSecundario'] ?></td>
                                            <td><?php echo $row['horasDadasUniversidade'] ?></td>
                                            <td><?php echo $row['verificado'] ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <button
                                                        type="button"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-original-title="Verificar recibo"
                                                        onclick="window.location.href='recibosProfessoresVerificar.php?idRecibo=<?php echo $row['idRecibo']; ?>'"
                                                    >
                                                        <i class="fa fa-check"></i>
                                                    </button>
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
