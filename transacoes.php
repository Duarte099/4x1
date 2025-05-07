<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 13;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }
?>
  <title>4x1 | Transações</title>
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
                <h3 class="fw-bold mb-3">Transações</h3>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="transacoesCriar.php" class="btn btn-primary btn-round">Nova transação</a>
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
                            <th>Transação</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th style="width: 10%">Ação</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Transação</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php
                            //query para selecionar todos os administradores
                            $sql = "SELECT t.id, nome, tipo, descricao, valor, DATE_FORMAT(data, '%d-%m-%Y') AS data FROM transacoes as t LEFT JOIN categorias as c ON t.idCategoria = c.id;";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['nome'] ?></td>
                                            <td><?php echo $row['tipo'] ?></td>
                                            <td><?php echo $row['descricao'] ?></td>
                                            <td><?php echo $row['valor'] ?></td>
                                            <td><?php echo $row['data'] ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <button
                                                        type="button"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        data-original-title="Editar transação"
                                                        onclick="window.location.href='transacoesEdit.php?idTransacao=<?php echo $row['id']; ?>'"
                                                        >
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button 
                                                            type="button" 
                                                            data-bs-toggle="tooltip" 
                                                            id="alert_demo_7"
                                                            class="btn btn-link btn-danger" 
                                                            onclick="transacaoDelete(<?php echo $row['id']; ?>)"
                                                        >
                                                            <i class="fa fa-times"></i>
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
    <script>    
        function transacaoDelete(id) {
            //Faz uma pergunta e guarda o resultado em result
            const result = confirm("Tem a certeza que deseja eliminar esta transação?");
            //Se tiver respondido que sim
            if (result) {
                //redireciona para a pagina fichaTrabalhoDelete e manda o id da ficha a ser deletada por GET
                window.location.href = "transacoesDelete.php?op=delete&idTransacao=" + id;
            }
        }
    </script>
    <?php   
      include('./endPage.php'); 
    ?>
  </body>
</html>
