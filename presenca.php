<?php
  //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
  include('./head.php'); 

  //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
  $estouEm = 4;

  //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
  if (adminPermissions($con, "adm_003", "view") == 0) {
      header('Location: dashboard');
      exit();
  }
?>
  <title>4x1 | Registro de presença</title>
  <style>
    /* Style the tab */
    .tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
      background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
      background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-top: none;
    }
</style>
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
                <h3 class="fw-bold mb-3">Registro de presença</h3>
              </div>
            </div>
            <div class="col-md-12">
              <div class="tab">
                <?php 
                  $disciplinaIndex = 0;
                  $sql = "SELECT d.nome, d.id
                            FROM disciplinas AS d 
                            INNER JOIN professores_disciplinas AS pd ON d.id = pd.idDisciplina 
                            INNER JOIN professores AS p ON pd.idProfessor = p.id 
                            WHERE p.id = $idAdmin;";
                  $result = $con->query($sql);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      if ($disciplinaIndex == 0) {
                        $disciplinaIndex = 1;
                        $defaultId = 'id="defaultOpen"';
                      } else {
                        $defaultId = '';
                      }
                      ?>
                        <button class="tablinks" onclick="openCity(event, '<?php echo $row['nome']; ?>')" <?php echo $defaultId; ?>> <?php echo $row['nome']; ?></button>
                      <?php
                    }
                  }
                ?>
              </div>
              <?php
                $sql = "SELECT d.nome, d.id
                          FROM disciplinas AS d 
                          INNER JOIN professores_disciplinas AS pd ON d.id = pd.idDisciplina 
                          INNER JOIN professores AS p ON pd.idProfessor = p.id 
                          WHERE p.id = $idAdmin;";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $idDisciplina = $row['id'];
                    $sql1 = "SELECT a.id, a.nome, a.ano, a.dataNascimento,
                              IF(a.ano >= 1 AND a.ano <= 4, '1º CICLO',
                                  IF(a.ano > 4 AND a.ano < 7, '2º CICLO',
                                      IF(a.ano > 6 AND a.ano <= 9, '3º CICLO', 'SECUNDÁRIO e OUTROS')
                                  )
                              ) AS ensino
                            FROM alunos AS a
                            INNER JOIN alunos_disciplinas AS ad ON a.id = ad.idAluno
                            INNER JOIN professores_disciplinas AS pd ON ad.idDisciplina = pd.idDisciplina
                            WHERE pd.idProfessor = $idAdmin AND ad.idDisciplina = $idDisciplina
                            ORDER BY a.ativo DESC, (a.ano = 0), a.ano ASC;";
                    $result1 = $con->query($sql1);
                    if ($result1->num_rows > 0) {
                      ?>
                      <div id="<?php echo $row['nome']; ?>" class="tabcontent">
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
                                while ($row1 = $result1->fetch_assoc()) {
                                  echo "<tr>
                                      <td>{$row1['nome']}</td>
                                      <td>{$row1['ensino']}</td>
                                      <td>{$row1['dataNascimento']}</td>
                                      <td>
                                        <div class=\"form-button-action\">
                                          <button
                                            type=\"button\"
                                            data-bs-toggle=\"tooltip\"
                                            onclick=\"window.location.href='alunoEdit?idAluno=" . $row['id'] . "&op=edit'\"
                                            class=\"btn btn-link btn-primary btn-lg\"
                                            data-original-title=\"Editar Aluno\"
                                          >
                                            <i class=\"fa fa-edit\"></i>
                                          </button>
                                        </div>
                                      </td>
                                  </tr>";
                                }
                              ?>    
                              </tbody>
                            </table>
                          </div>
                        </div>
                      <?php
                    }
                  }
                }
              ?>
              <!-- <div id="Matemática" class="tabcontent">
                <h3>London</h3>
                <p>London is the capital city of England.</p>
              </div>

              <div id="Português" class="tabcontent">
                <h3>Paris</h3>
                <p>Paris is the capital of France.</p> 
              </div>

              <div id="Inglês" class="tabcontent">
                <h3>Tokyo</h3>
                <p>Tokyo is the capital of Japan.</p>
              </div>

              <div id="Espanhol" class="tabcontent">
                <h3>Tokyo</h3>
                <p>Tokyo is the capital of Japan.</p>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php  
      include('./endPage.php'); 
    ?>
    <script>
      function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
      }
      // Get the element with id="defaultOpen" and click on it
      document.getElementById("defaultOpen").click();
    </script>
  </body>
</html>
