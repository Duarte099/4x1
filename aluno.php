<?php
  //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
  include('./head.php'); 

  //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
  $estouEm = 2;

  //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
  if (adminPermissions($con, "adm_001", "view") == 0) {
      notificacao('warning', 'Não tens permissão para aceder a esta página.');
      header('Location: dashboard.php');
      exit();
  }
?>
  <title>4x1 | Alunos</title>
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
                <h3 class="fw-bold mb-3">Alunos</h3>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="alunoCriar.php" class="btn btn-primary btn-round">Adicionar aluno</a>
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
                          <th>Ensino</th>
                          <th>Data Nascimento</th>
                          <th style="width: 10%">Ação</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          //query para selecionar todos os administradores
                          $sql = "SELECT id, nome, ano, dataNascimento, IF(ano>=1 AND ano<=4, \"1º CICLO\", IF(ano>4 AND ano<7, \"2º CICLO\", IF(ano>6 AND ano<=9, \"3º CICLO\", \"SECUNDÁRIO e OUTROS\"))) as ensino FROM alunos ORDER BY ativo DESC,(ano = 0), ano ASC;";
                          $result = $con->query($sql);
                          if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              //mostra os resultados todos 
                              echo "<tr>
                                      <td>{$row['nome']}</td>
                                      <td>{$row['ensino']}</td>
                                      <td>{$row['dataNascimento']}</td>
                                      <td>
                                        <div class=\"form-button-action\">
                                          <button
                                            type=\"button\"
                                            data-bs-toggle=\"tooltip\"
                                            onclick=\"window.location.href='alunoEdit.php?idAluno=" . $row['id'] . "'\"
                                            class=\"btn btn-link btn-primary btn-lg\"
                                            data-original-title=\"Editar Aluno\"
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
