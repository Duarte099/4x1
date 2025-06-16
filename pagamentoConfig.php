<?php
	//inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
	include('./head.php'); 

	//variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
	$estouEm = 14;

  	if ($_SESSION["tipo"] == "professor") {
		notificacao('warning', 'Não tens permissão para aceder a esta página.');
		header('Location: dashboard.php');
		exit();
	}
?>
  <title>Configuração pagamentos | 4x1</title>
</head>
  <body>
    <div class="wrapper">
      <?php  
        include('./sideBar.php'); 
      ?>
        <div class="container">
          	<div class="page-inner">
				<div class="row">
					<div class="col-md-7">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-center">
									<h4 class="card-title">Configuração mensalidades alunos</h4>
									<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#criarMensalidade">
										<i class="fa fa-plus"></i>
										Adicionar mensalidade
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="modal fade" id="criarMensalidade" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header border-0">
												<h5 class="modal-title">
													<span class="fw-mediumbold"> Nova</span>
													<span class="fw-light"> Mensalidade </span>
												</h5>
											</div>
											<div class="modal-body">
												<form action="pagamentoConfigInserir.php?op=save" method="POST">
													<div class="row">
														<div class="col-md-12">
															<div class="form-group form-group-default">
																<label>Ano</label>
																<input type="number" name="ano" class="form-control" value="0" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Horas Grupo</label>
																<input type="number" name="horasGrupo" value="0" class="form-control" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Mensalidade Grupo</label>
																<input type="number" name="mensGrupo" value="0" class="form-control" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Horas Individuais</label>
																<input  type="number" name="horasInd" value="0" class="form-control" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Mensalidade Individual</label>
																<input type="number" name="mensInd" value="0" class="form-control" required>
															</div>
														</div>
													</div>
													<div class="modal-footer border-0">
														<button type="submit" class="btn btn-primary">
														Adicionar
														</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table
										id="tabela-alunos-pagamentos-config"
										class="display table table-striped table-hover"
									>
										<thead>
											<tr>
												<th>Ano</th>
												<th>Horas Grupo</th>
												<th>Mensalidade Grupo</th>
												<th>Horas Individuais</th>
												<th>Mensalidade Individual</th>
												<th style="width: 10%">Ação</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Ano</th>
												<th>Horas Grupo</th>
												<th>Mensalidade Grupo</th>
												<th>Horas Individuais</th>
												<th>Mensalidade Individual</th>
											</tr>
										</tfoot>
										<tbody>
											<?php
												//query para selecionar todos os administradores
												$sql = "SELECT id, ano, horasGrupo, horasIndividual, mensalidadeHorasGrupo, mensalidadeHorasIndividual FROM mensalidade;";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) { ?>
														<tr>
															<td><?php echo $row['ano'] ?></td>
															<td><?php echo $row['horasGrupo'] ?></td>
															<td><?php echo $row['mensalidadeHorasGrupo'] ?></td>
															<td><?php echo $row['horasIndividual'] ?></td>
															<td><?php echo $row['mensalidadeHorasIndividual'] ?></td>
															<td>
																<div class="form-button-action">
																	<button
																	type="button"
																	class="btn btn-link btn-primary btn-lg"
																	data-original-title="Inserir Mensalidade"
																	data-bs-toggle="modal" 
																	data-bs-target="#editarMensalidade"
																	onclick="preencherMensalidade(
																		<?php echo $row['id'] ?>, 
																		<?php echo $row['ano'] ?>, 
																		<?php echo $row['horasGrupo'] ?>, 
																		<?php echo $row['mensalidadeHorasGrupo'] ?>, 
																		<?php echo $row['horasIndividual'] ?>, 
																		<?php echo $row['mensalidadeHorasIndividual'] ?>
																	)">
																		<i class="fa fa-edit"></i>
																	</button>
																	<a 
																		onclick="mensalidadeDelete(<?php echo $row['id']; ?>)"
																		class="btn btn-link btn-primary btn-lg"
																		data-bs-toggle="tooltip"
																		data-bs-placement="top"
																		title="Eliminar mensalidade"
																	>
																		<i class="fa fa-times"></i>
																	</a>
																</div>
															</td>
														</tr>
													<?php }
												}
											?>
										</tbody>
									</table>
								</div>
								<script>
									function mensalidadeDelete(id) {
										//Faz uma pergunta e guarda o resultado em result
										const result = confirm("Tem a certeza que deseja eliminar esta mensalidade?");
										//Se tiver respondido que sim
										if (result) {
											//redireciona para a pagina fichaTrabalhoDelete e manda o id da ficha a ser deletada por GET
											window.location.href = "pagamentoConfigInserir.php?op=deleteMensalidade&idMensalidade=" + id;
										}
									}
								</script>
								<div class="modal fade" id="editarMensalidade" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header border-0">
												<h5 class="modal-title">
													<span class="fw-mediumbold"> Editar</span>
													<span class="fw-light"> Mensalidade </span>
												</h5>
											</div>
											<div class="modal-body">
												<form action="pagamentoConfigInserir.php?op=editMensalidade" method="POST">
													<div class="row">
														<div class="col-md-12">
															<div class="form-group form-group-default">
																<label>Ano</label>
																<input type="number" name="ano" class="form-control" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Horas Grupo</label>
																<input type="number" name="horasGrupo" class="form-control">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Mensalidade Grupo</label>
																<input type="number" name="mensGrupo" class="form-control">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Horas Individuais</label>
																<input  type="number" name="horasInd" class="form-control">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Mensalidade Individual</label>
																<input type="number" name="mensInd" class="form-control">
															</div>
														</div>
													</div>
													<div class="modal-footer border-0">
														<button type="submit" class="btn btn-primary">
														Guardar Alterações
														</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Configuração pagamentos</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table
										id="tabela-profs-pagamentos-config"
										class="display table table-striped table-hover"
									>
										<thead>
											<tr>
												<th>Tipo</th>
												<th>Valor</th>
												<th style="width: 10%">Ação</th>
											</tr>
										</thead>
										<tbody>
											<?php
												//query para selecionar todos os administradores
												$sql = "SELECT pv.id, nome, valor FROM valores_pagamento as pv LEFT JOIN ensino ON idEnsino = ensino.id;";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) { ?>
														<tr>
															<td><?php echo $row['nome']; ?></td>
															<td><?php echo $row['valor']; ?>€</td>
															<td>
																<div class="form-button-action">
																	<button
																		type="button"
																		class="btn btn-link btn-primary btn-lg"
																		data-original-title="Editar pagamento"
																		data-bs-toggle="modal" 
																		data-bs-target="#editarPagamento"
																		onclick="preencherPagamento(
																			<?php echo $row['id']; ?>, 
																			'<?php echo addslashes($row['nome']); ?>',
																			<?php echo $row['valor']; ?>
																		)"
																	>
																	<i class="fa fa-edit"></i>
																	</button>
																</div>
															</td>
														</tr>
													<?php }
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="modal fade" id="editarPagamento" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header border-0">
											<h5 class="modal-title">
												<span class="fw-mediumbold"> Editar</span>
												<span class="fw-light"> Pagamento </span>
											</h5>
										</div>
										<div class="modal-body">
											<form action="pagamentoConfigInserir.php?op=editPagamento" method="POST">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group form-group-default">
															<label>Ensino</label>
															<input type="text" name="ensino" class="form-control" readonly>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group form-group-default">
															<label>Valor</label>
															<input type="number" step="0.01" min="0" name="valor" class="form-control" required>
														</div>
													</div>
												</div>
												<div class="modal-footer border-0">
													<button type="submit" class="btn btn-primary">
													Guardar Alterações
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
        	</div>
      	</div>
    </div>
	<script>
		function preencherMensalidade(id, ano, horasGrupo, mensalidadeGrupo, horasIndividuais, mensalidadeInd) {
			// Preencher os campos do formulário
			document.querySelector('#editarMensalidade input[name="ano"]').value = ano;
			document.querySelector('#editarMensalidade input[name="horasGrupo"]').value = horasGrupo;
			document.querySelector('#editarMensalidade input[name="mensGrupo"]').value = mensalidadeGrupo;
			document.querySelector('#editarMensalidade input[name="horasInd"]').value = horasIndividuais;
			document.querySelector('#editarMensalidade input[name="mensInd"]').value = mensalidadeInd;

			// Alterar a action do formulário
			const form = document.querySelector('#editarMensalidade form');
			form.action = `pagamentoConfigInserir.php?op=editMensalidade&idMensalidade=${id}`;
		}
		function preencherPagamento(id, ensino, valor) {
			// Preencher os campos do formulário
			document.querySelector('#editarPagamento input[name="ensino"]').value = ensino;
			document.querySelector('#editarPagamento input[name="valor"]').value = valor;

			// Alterar a action do formulário
			const form = document.querySelector('#editarPagamento form');
			form.action = `pagamentoConfigInserir.php?op=editPagamento&idPagamento=${id}`;
		}
	</script>
	<script>
        $("#tabela-alunos-pagamentos-config").DataTable({
            pageLength: 6,
            order: [
				[0, 'asc'],
				[1, 'asc']
			],
            language: {
              url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
            },
			columnDefs: [
                { targets: 5, orderable: false }
            ],
            initComplete: function () {
                this.api()
                .columns()
                .every(function () {
                    var column = this;
                    var select = $(
                        '<select class="form-select"><option value=""></option></select>'
                    )
                    .appendTo($(column.footer()).empty())
                    .on("change", function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column
                        .search(val ? "^" + val + "$" : "", true, false)
                        .draw();
                    });

                    column
                    .data()
                    .unique()
                    .sort()
                    .each(function (d, j) {
                        select.append(
                            '<option value="' + d + '">' + d + "</option>"
                        );
                    });
                });
            },
        });

		$("#tabela-profs-pagamentos-config").DataTable({
            pageLength: 7,
            order: [[0, 'asc']],
            language: {
              url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
            },
			columnDefs: [
                { targets: 5, orderable: false }
            ],
            initComplete: function () {
                this.api()
                .columns()
                .every(function () {
                    var column = this;
                    var select = $(
                        '<select class="form-select"><option value=""></option></select>'
                    )
                    .appendTo($(column.footer()).empty())
                    .on("change", function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column
                        .search(val ? "^" + val + "$" : "", true, false)
                        .draw();
                    });

                    column
                    .data()
                    .unique()
                    .sort()
                    .each(function (d, j) {
                        select.append(
                            '<option value="' + d + '">' + d + "</option>"
                        );
                    });
                });
            },
        });
	</script>
    <?php 
        include('./endPage.php');
    ?>
  	</body>
</html>
