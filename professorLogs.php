<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 4;
    
    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }
?>
  <title>4x1 | Logs Professores</title>
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
                <h3 class="fw-bold mb-3">Logs Professores</h3>
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
                            $sql = "SELECT nome, DATE_FORMAT(dataLog, '%d-%m-%Y %H:%i:%s') AS dataLog, logFile FROM professores_logs INNER JOIN professores ON idProfessor = id WHERE ativo = 1;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    //mostra os resultados todos 
                                    echo "<tr>
                                            <td>{$row['nome']}</td>
                                            <td>{$row['logFile']}</td>
                                            <td>{$row['dataLog']}</td>
                                        </tr>";
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
