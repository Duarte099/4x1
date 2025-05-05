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
                        id="multi-filter-select"
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
                            $sql = "SELECT id, nome, ano, dataNascimento,
                                      CASE 
                                          WHEN ano >= 1 AND ano <= 4 THEN '1º CICLO'
                                          WHEN ano > 4 AND ano < 7 THEN '2º CICLO'
                                          WHEN ano > 6 AND ano <= 9 THEN '3º CICLO'
                                          WHEN ano > 9 THEN 'SECUNDÁRIO'
                                          WHEN ano = 0 THEN 'UNIVERSIDADE'
                                      END AS ensino
                                    FROM alunos 
                                    ORDER BY 
                                      FIELD(ensino, '1º CICLO', '2º CICLO', '3º CICLO', 'SECUNDÁRIO', 'UNIVERSIDADE'), 
                                      ano ASC;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) {
                                $sql1 = "SELECT * FROM alunos_recibo WHERE idAluno = {$row['id']} AND mes = {$mesAnterior} AND ano = {$anoAtual}";
                                $result1 = $con->query($sql1);
                                if ($result1->num_rows > 0) {
                                  $row1 = $result1->fetch_assoc();
                                  if ($row1['estado'] == "Pago") {
                                    $corStatus = "2ecc71";
                                  }
                                  elseif ($row1['estado'] == "Pendente") {
                                    $corStatus = "f1c40f";
                                  }
                                  elseif ($row1['estado'] == "Em atraso") {
                                    $corStatus = "ff0000";
                                  }
                                  else {
                                    $corStatus = "";
                                  }
                                }
                                else {
                                  $corStatus = "";
                                  $row1['estado'] = "Pendente";
                                }
                                //mostra os resultados todos 
                                echo "<tr>
                                        <td>{$row['ensino']}</td>
                                        <td>{$row['nome']}</td>
                                        <td>{$row['dataNascimento']}</td>
                                        <td style=\"color: #$corStatus;\">{$row1['estado']}</td>
                                        <td>
                                          <div class=\"form-button-action\">
                                            <button
                                              type=\"button\"
                                              data-bs-toggle=\"tooltip\"
                                              onclick=\"window.location.href='alunoEdit.php?idAluno=" . $row['id'] . "&tab=recibo'\"
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
