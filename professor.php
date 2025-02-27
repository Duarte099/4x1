<?php
  //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
  include('./head.php'); 

  //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
  $estouEm = 3;
  $estouEm2 = 1;

  //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
  if (adminPermissions($con, "adm_002", "view") == 0) {
      header('Location: dashboard');
      exit();
  }
?>
  <title>4x1 | Professores</title>
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
                <h3 class="fw-bold mb-3">Professores</h3>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="professorCriar" class="btn btn-primary btn-round">Adicionar professor</a>
              </div>
            </div>
            <div class="input-icon">
              <input
                style="width: 20%;"
                type="text"
                class="form-control"
                placeholder="Pesquisar por..."
              />
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table
                      id="add-row"
                      class="display table table-striped table-hover"
                    >
                      <thead>
                        <tr>
                          <th>Nome</th>
                          <th>Email</th>
                          <th>Contacto</th>
                          <th style="width: 10%">Ação</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          //query para selecionar todos os administradores
                          $sql = "SELECT id, nome, email, contacto FROM professores ORDER BY ativo DESC;";
                          $result = $con->query($sql);
                          if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              //mostra os resultados todos 
                              echo "<tr>
                                      <td>{$row['nome']}</td>
                                      <td>{$row['email']}</td>
                                      <td>{$row['contacto']}</td>
                                      <td>
                                        <div class=\"form-button-action\">
                                          <button
                                            type=\"button\"
                                            data-bs-toggle=\"tooltip\"
                                            onclick=\"window.location.href='professorEdit?idProf=" . $row['id'] . "'\"
                                            class=\"btn btn-link btn-primary btn-lg\"
                                            data-original-title=\"Editar Professor\"
                                          >
                                            <i class=\"fa fa-edit\"></i>
                                          </button>
                                        </div>
                                      </td>
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
