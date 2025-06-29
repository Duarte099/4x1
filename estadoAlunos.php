<?php
  //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
  include('./head.php'); 

  //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
  $estouEm = 8;
?>
  <title>4x1 | Estado Alunos</title>
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
                <h3 class="fw-bold mb-3">Estado alunos</h3>
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
                            <th>Estado</th>
                            <th>Nome</th>
                            <th>Data Nascimento</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Estado</th>
                            <th>Nome</th>
                            <th>Data Nascimento</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT id, nome, dataNascimento, IF(ativo=1, \"Ativo\", \"Desativo\") as estado FROM alunos ORDER BY estado ASC;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                  <td><?php echo $row['estado'] ?></td>
                                  <td><?php echo $row['nome'] ?></td>
                                  <td><?php echo $row['dataNascimento'] ?></td>
                                  <td>
                                    <div class="form-button-action\">
                                      <button
                                        type="button"
                                        data-bs-toggle="tooltip"
                                        onclick="window.location.href='alunoEstado.php?idAluno=<?php echo $row['id']; ?>$op=save'"
                                        class="btn btn-link btn-primary btn-lg"
                                        data-original-title="Editar Aluno"
                                      >
                                        <i class="fa fa-sync-alt"></i>
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
