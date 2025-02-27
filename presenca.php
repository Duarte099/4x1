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
    <title>4x1 | Resgistrar presença</title>
    <style>
        h1 {
            text-align: center;
            color: #343a40;
        }

        table {
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            border: 2px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #dee2e6;
            /* padding: 10px; */
            text-align: center;
        }

        th {
            padding: 10px;
            background-color: #f2f2f2;
            color: #343a40;
        }

        .highlight {
            background-color: #f8f9fa;
        }

        .special {
            background-color: #f0f0f0;
        }

        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        /* Formulário estruturado com flexbox */
        .form-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            gap: 10px;
        }

        .campo {
            flex: 1;
            min-width: 150px;
        }

        .campo label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Ajusta a largura e altura para ocupar o TD inteiro */
        #selectgroup-item {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0; /* Remove qualquer padding extra */
            margin: 0;
        }

        /* Oculta a checkbox padrão */
        #electgroup-input {
            display: none;
        }

        /* Estiliza o botão (span) para ocupar todo o espaço do TD */
        .selectgroup-button {
            display: block;
            width: 100%;
            height: 100%;
            text-align: center;
            background-color: white; /* Cor padrão */
            border: 1px solid #ccc;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
            box-sizing: border-box; /* Garante que o padding não afete o tamanho total */
        }

        /* Muda a cor quando a checkbox está selecionada */
        #selectgroup-input:checked + .selectgroup-button {
            background-color: blue;
            color: white;
            font-weight: bold;
        }

        select {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px;
            font-size: 16px;
            color: #343a40;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .campo {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
    <body>
        <div class="wrapper">
          <?php 
              include('./sideBar.php'); 
          ?>
          <form action="presencaInserir?op=save" method="POST">
            <div class="page-inner">
              <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                  <div>
                      <h2 class="fw-bold mb-3">Registar presença</h2>
                  </div>
              </div>
              <div class="container2">
                  <div class="form-section">
                      <div class="form-row">
                          <div class="campo" style="flex: 0 0 56%;">
                            <label>NOME:</label>
                            <input type="text" name="nome" list="datalistNomes" oninput="atualizarCampos(this)">
                            <datalist id='datalistNomes'>
                              <?php
                                //Obtem todas as referencias dos produtos que estao ativos
                                $sql = "SELECT id, nome FROM alunos;";
                                $result = $con->query($sql);
                                if ($result->num_rows > 0) {
                                  //Percorre todos os produtos e adiciona-os como opção na dataList
                                  while ($row = $result->fetch_assoc()) {
                                      echo "<option>$row[id] | $row[nome]</option>";
                                  }
                                }
                              ?>
                            </datalist>
                          </div>
                          <div class="campo" style="flex: 0 0 20%;">
                            <label>Pack:</label>
                            <input type="text" name="pack" id="pack" readonly>
                          </div>
                          <div class="campo" style="flex: 0 0 20%;">
                            <label>Ano Letivo:</label>
                            <input type="text" name="anoLetivo" readonly value="<?php 
                            if (date('m') < 9) 
                            {
                              echo date('Y') - 1 . "/" . date('Y');
                            } else {
                              echo date('Y') . "/" . date('Y') + 1;
                            }?>">
                          </div>
                      </div>

                      <div class="form-row" id="disciplinasContainer" style="display: none;">
                        <div class="campo" style="flex: 0 0 99.5%;">
                          <label>DISCIPLINA:</label>
                          <select
                            class="form-select form-control"
                            id="defaultSelect"
                            style="border: 1px solid #ccc;"
                            name="disciplina"
                          >
                            <?php 
                              $sql = "SELECT d.nome, d.id
                                FROM disciplinas AS d 
                                INNER JOIN professores_disciplinas AS pd ON d.id = pd.idDisciplina 
                                INNER JOIN professores AS p ON pd.idProfessor = p.id 
                                WHERE p.id = $idAdmin;";
                              $result = $con->query($sql);
                              if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { 
                                  echo "<option value='disciplina_" . $row['id'] . "'>". $row['nome'] . "</option>";  
                                }
                              }
                            ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-row">
                          <div class="campo" style="flex: 0 0 49%;">
                              <label>HORA:</label>
                              <select
                                class="form-select form-control"
                                id="defaultSelect"
                                style="border: 1px solid #ccc;"
                                name="hora"
                              >
                                <option>09:30 - 10:30</option>
                                <option>10:00 - 11:00</option>
                                <option>10:30 - 11:30</option>
                                <option>11:00 - 12:00</option>
                                <option>11:30 - 12:30</option>
                                <option>12:00 - 13:00</option>
                                <option>14:30 - 15:30</option>
                                <option>15:00 - 16:00</option>
                                <option>15:30 - 16:30</option>
                                <option>16:00 - 17:00</option>
                                <option>16:30 - 17:30</option>
                                <option>17:00 - 18:00</option>
                                <option>17:30 - 18:30</option>
                                <option>18:00 - 19:00</option>
                                <option>18:30 - 19:30</option>
                                <option>19:00 - 20:00</option>
                              </select>
                          </div>
                          <div class="campo" style="flex: 0 0 49%;">
                              <label>DIA:</label>
                              <input type="date" name="dia" value="<?php echo date('Y-m-d'); ?>">
                          </div>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Registrar hora</button>
              </div>
            </div>
            <script>
              function atualizarCampos(input) {
                // O valor do input é o ID do aluno, conforme definido nas opções do datalist
                var partes = input.value.split(" | ");
                var alunoId = partes[0];
                
                // Se um valor for selecionado (não vazio)
                if(alunoId !== "") {
                  // Realiza a requisição AJAX para obter os dados do aluno
                  $.ajax({
                    url: 'json.obterNome.php',
                    type: 'GET',
                    data: { idAluno: alunoId},
                    success: function(response) {
                      var data = JSON.parse(response);
                      if (data == "erro") {
                        document.getElementById("pack").value = "";
                        document.getElementById("disciplinasContainer").style.display = "none";
                      }
                      else{
                        document.getElementById("pack").value = data.pack;
                        if (data.ciclo == 1) {
                          document.getElementById("disciplinasContainer").style.display = "none";
                        } else {
                          document.getElementById("disciplinasContainer").style.display = "block";
                        }
                      }
                    },
                    error: function() {
                      console.error('Erro ao buscar o nome.');
                    }
                  });
                } else {
                  // Se não houver um ID válido, limpa o campo Pack
                  document.getElementById("pack").value = "";
                  document.getElementById("disciplinasContainer").style.display = "none";
                }
              }
            </script>
          </form>
        </div>
        <?php 
            include('./endPage.php'); 
        ?>
    </body>
    </html>