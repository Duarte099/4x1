<?php
	//inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
	include('./head.php'); 

	//variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
	$estouEm = 12;

  	if ($_SESSION["tipo"] == "professor") {
		notificacao('warning', 'Não tens permissão para aceder a esta página.');
		header('Location: dashboard.php');
		exit();
	}
?>
  <title>4x1 | Configuração pagamentos</title>
</head>
  <body>
    <div class="wrapper">
      <?php  
        include('./sideBar.php'); 
      ?>
        <div class="container">
          	<div class="page-inner">
				<div class="row">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-center">
									<h4 class="card-title">Despesas</h4>
									<button
										class="btn btn-primary btn-round ms-auto"
										data-bs-toggle="modal"
										data-bs-target="#addRowModal"
									>
										<i class="fa fa-plus"></i>
										Adcionar despesa
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table
										id="add-row"
										class="display table table-striped table-hover"
									>
										<thead>
										<tr>
											<th>Despesa</th>
											<th>Valor</th>
											<th style="width: 10%">Action</th>
										</tr>
										</thead>
										<tbody>
											<?php
												//query para selecionar todos os administradores
												$sql = "SELECT id, despesa, valor FROM despesas;";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) {
														?>
															<tr>
																<td><?php echo $row['despesa'] ?></td>
																<td><?php echo $row['valor'] ?>€</td>
																<td>
																	<div class="form-button-action">
																		<button
																		type="button"
																		data-bs-toggle="tooltip"
																		title=""
																		class="btn btn-link btn-primary btn-lg"
																		data-original-title="Edit Task"
																		>
																		<i class="fa fa-edit"></i>
																		</button>
																		<button
																		type="button"
																		data-bs-toggle="tooltip"
																		title=""
																		class="btn btn-link btn-danger"
																		data-original-title="Remove"
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
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Multi Filter Select</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table
										id="multi-filter-select"
										class="display table table-striped table-hover"
									>
										<thead>
											<tr>
												<th>Nome</th>
												<th>Tipo</th>
												<th>Ação</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Nome</th>
												<th>Tipo</th>
												<th>Ação</th>
											</tr>
										</tfoot>
										<tbody>
											<?php
												//query para selecionar todos os administradores
												$sql = "SELECT id, nome, tipo FROM categorias;";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) {
														?>
															<tr>
																<td><?php echo $row['nome'] ?></td>
																<td><?php echo $row['tipo'] ?></td>
																<td>
																	<div class="form-button-action">
																		<button
																		type="button"
																		data-bs-toggle="tooltip"
																		title=""
																		class="btn btn-link btn-primary btn-lg"
																		data-original-title="Edit Task"
																		>
																		<i class="fa fa-edit"></i>
																		</button>
																		<button
																		type="button"
																		data-bs-toggle="tooltip"
																		title=""
																		class="btn btn-link btn-danger"
																		data-original-title="Remove"
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
    <?php   
      include('./endPage.php'); 
    ?>
  	</body>
</html>
