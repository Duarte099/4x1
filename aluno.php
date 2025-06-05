<?php
  //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
  include('./head.php'); 

  //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
  $estouEm = 2;
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
            <?php 
              $stmt = $con->prepare("SELECT * FROM alunos WHERE ativo = 1 AND DATE_FORMAT(dataNascimento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')");
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0) {
                  while($rowAluno = $result->fetch_assoc()){ 
                      $hoje = new DateTime();
                      $nascimento = new DateTime($rowAluno['dataNascimento']);
                      $idade = $nascimento->diff($hoje)->y;
                    ?>
                    <div id="aniversariosHoje" class="alert alert-success d-flex align-items-center" style="display: none;">
                      <i class="bi bi-cake2-fill me-2"></i>
                      <div id="textoAniversariosHoje">
                        O aluno <?php echo $rowAluno['nome']; ?> faz <?php echo $idade; ?> anos.
                      </div>
                    </div>
                  <?php }
              }
            ?>
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
                            <th>Ensino</th>
                            <th>Nome</th>
                            <th>Data Nascimento</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Ensino</th>
                            <th>Nome</th>
                            <th>Data Nascimento</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT id, nome, ano, DATE_FORMAT(dataNascimento, '%d-%m-%Y') as dataNascimento, horasGrupo, horasIndividual, IF(ano>=1 AND ano<=4, \"1º CICLO\", IF(ano>4 AND ano<7, \"2º CICLO\", IF(ano>6 AND ano<=9, \"3º CICLO\", IF(ano>9 AND ano<=12, \"SECUNDÁRIO\", IF(ano=0, \"UNIVERSIDADE\", \"ERRO\"))))) as ensino FROM alunos WHERE ativo = 1 ORDER BY (ano = 0), ano ASC;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) {
                                //mostra os resultados todos
                                echo "<tr>
                                        <td>{$row['ensino']}</td>
                                        <td>{$row['nome']}</td>
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
