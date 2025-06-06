<?php
	//inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
	include('./head.php'); 

	//variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
	$estouEm = 16;

  	if ($_SESSION["tipo"] == "professor") {
		notificacao('warning', 'Não tens permissão para aceder a esta página.');
		header('Location: dashboard.php');
		exit();
	}

	function getMesesPorDespesa($con, $idDespesa) {
		$meses = [];
		$sql = "SELECT mes FROM despesas_meses WHERE idDespesa = ?";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("i", $idDespesa);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_assoc()) {
			$meses[] = $row['mes'];
		}
		return $meses;
	}
?>
  <title>Despesas e categorias | 4x1</title>
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
										data-bs-target="#criarDespesa"
									>
										<i class="fa fa-plus"></i>
										Adicionar despesa
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="modal fade" id="criarDespesa" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header border-0">
												<h5 class="modal-title">
													<span class="fw-mediumbold"> Nova</span>
													<span class="fw-light"> Despesa </span>
												</h5>
											</div>
											<div class="modal-body">
												<form action="despesasInserir.php?op=saveDespesa" method="POST">
													<div class="row">
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Despesa</label>
																<input type="input" name="despesa" class="form-control" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Valor</label>
																<input type="number" step="0.01" min="0" name="valor" value="0" class="form-control" required>
															</div>
														</div>
														<div class="col-md-12">
															<?php 
																for ($i=1; $i < 13; $i++) { ?>
																	<label class='selectgroup-item'>
																		<input type='checkbox' name='disciplina_<?php echo $i; ?>' value='<?php echo $i; ?>' class='selectgroup-input'/>
																		<span class='selectgroup-button' style="padding: 5px"><?php echo $i; ?></span>
																	</label>
																<?php }
															?>
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
										id="tabela-despesas"
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
																		<a
																			type="button"
																			data-bs-toggle="modal"
																			data-bs-target="#editarDespesa"
																			title=""
																			class="btn btn-link btn-primary btn-lg"
																			data-original-title="Edit Task"
																			onclick='preencherDespesa(
																				<?php echo $row["id"]; ?>,
																				"<?php echo addslashes($row["despesa"]); ?>",
																				<?php echo $row["valor"]; ?>,
																				<?php
																					$meses = getMesesPorDespesa($con, $row["id"]);
																					echo htmlspecialchars(json_encode($meses), ENT_QUOTES, "UTF-8");
																				?>
																			)'
																		>
																			<i class="fa fa-edit"></i>
																		</a>
																		<a
																			onclick="deleteDespesa(<?php echo $row['id']; ?>)"
																			class="btn btn-link btn-primary btn-lg"
																			data-bs-toggle="tooltip"
																			data-bs-placement="top"
																			title="Eliminar despesa"
																		>
																			<i class="fa fa-times"></i>
																		</a>
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
								<div class="modal fade" id="editarDespesa" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header border-0">
												<h5 class="modal-title">
													<span class="fw-mediumbold"> Nova</span>
													<span class="fw-light"> Despesa </span>
												</h5>
											</div>
											<div class="modal-body">
												<form action="despesasInserir.php?op=saveDespesa" method="POST" >
													<div class="row">
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Despesa</label>
																<input type="input" name="despesa" class="form-control" value="0" required>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group form-group-default">
																<label>Valor</label>
																<input type="number" step="0.01" min="0" name="valor" value="0" class="form-control" required>
															</div>
														</div>
														<div class="col-md-12">
															<?php 
																for ($i=1; $i < 13; $i++) { ?>
																	<label class='selectgroup-item'>
																		<input type='checkbox' name='despesa_<?php echo $i; ?>' value='<?php echo $i; ?>' class='selectgroup-input'/>
																		<span class='selectgroup-button' style="padding: 5px"><?php echo $i; ?></span>
																	</label>
																<?php }
															?>
														</div>
													</div>
													<div class="modal-footer border-0">
														<button type="submit" class="btn btn-primary">
														Guardar alterações
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
					<div class="modal fade" id="criarCategoria" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header border-0">
									<h5 class="modal-title">
										<span class="fw-mediumbold"> Nova</span>
										<span class="fw-light"> Categoria </span>
									</h5>
								</div>
								<div class="modal-body">
									<form action="despesasInserir.php?op=saveCategoria" method="POST">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group form-group-default">
													<label>Categoria</label>
													<input type="input" name="categoria" class="form-control" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-group-default">
													<label>Tipo</label>
													<select name="tipo" class="select-box">
														<option value="credito">Crédito</option>
														<option value="debito">Débito</option>
													</select>
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
					<div class="col-md-6">
						<div class="card">
							<div class="card-header">
								<div class="d-flex align-items-center">
									<h4 class="card-title">Categorias</h4>
									<button
										class="btn btn-primary btn-round ms-auto"
										data-bs-toggle="modal"
										data-bs-target="#criarCategoria"
									>
										<i class="fa fa-plus"></i>
										Adicionar categoria
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table
										id="tabela-categorias"
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
																			data-bs-toggle="modal"
																			title=""
																			class="btn btn-link btn-primary btn-lg"
																			data-original-title="Edit Task"
																			data-bs-target="#editarCategoria"
																			onclick='preencherCategoria(
																				<?php echo $row["id"]; ?>,
																				"<?php echo addslashes($row["nome"]); ?>",
																				"<?php echo $row["tipo"]; ?>"
																			)'
																		>
																			<i class="fa fa-edit"></i>
																		</button>
																		<a
																			onclick="deleteCategoria(<?php echo $row['id']; ?>)"
																			class="btn btn-link btn-primary btn-lg"
																			data-bs-toggle="tooltip"
																			data-bs-placement="top"
																			title="Eliminar categoria"
																		>
																			<i class="fa fa-times"></i>
																		</a>
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
					<div class="modal fade" id="editarCategoria" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header border-0">
									<h5 class="modal-title">
										<span class="fw-mediumbold"> Editar</span>
										<span class="fw-light"> Categoria </span>
									</h5>
								</div>
								<div class="modal-body">
									<form action="despesasInserir.php?op=editCategoria" method="POST">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group form-group-default">
													<label>Categoria</label>
													<input type="input" name="categoria" class="form-control" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group form-group-default">
													<label>Tipo</label>
													<select name="tipo" class="select-box">
														<option value="credito">Crédito</option>
														<option value="debito">Débito</option>
													</select>
												</div>
											</div>
										</div>
										<div class="modal-footer border-0">
											<button type="submit" class="btn btn-primary">
											Guardar alterações
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
	<script>
		function preencherDespesa(id, despesa, valor, meses) {
			// Preencher os campos
			document.querySelector('#editarDespesa input[name="despesa"]').value = despesa;
			document.querySelector('#editarDespesa input[name="valor"]').value = valor;

			// Desmarcar todas as checkboxes primeiro
			for (let i = 1; i <= 12; i++) {
				const checkbox = document.querySelector(`#editarDespesa input[name="despesa_${i}"]`);
				if (checkbox) checkbox.checked = false;
			}

			// Marcar as que vieram no array
			meses.forEach(id => {
				const checkbox = document.querySelector(`#editarDespesa input[name="despesa_${id}"]`);
				if (checkbox) checkbox.checked = true;
			});

			// Atualizar action do formulário
			const form = document.querySelector('#editarDespesa form');
			form.action = `despesasInserir.php?op=editDespesa&idDespesa=${id}`;
		}

		function deleteDespesa(id) {
			//Faz uma pergunta e guarda o resultado em result
			const result = confirm("Tem a certeza que deseja eliminar esta despesa?");
			//Se tiver respondido que sim
			if (result) {
				//redireciona para a pagina fichaTrabalhoDelete e manda o id da ficha a ser deletada por GET
				window.location.href = "despesasInserir.php?op=deleteDespesa&idDespesa=" + id;
			}
		}

		function preencherCategoria(id, nome, tipo) {
			// Preencher os campos do formulário
			document.querySelector('#editarCategoria input[name="categoria"]').value = nome;
			document.querySelector('#editarCategoria select[name="tipo"]').value = tipo;

			// Alterar a action do formulário com o ID
			const form = document.querySelector('#editarCategoria form');
			form.action = `despesasInserir.php?op=editCategoria&idCategoria=${id}`;
		}

		function deleteCategoria(id) {
			//Faz uma pergunta e guarda o resultado em result
			const result = confirm("Tem a certeza que deseja eliminar esta categoria?");
			//Se tiver respondido que sim
			if (result) {
				//redireciona para a pagina fichaTrabalhoDelete e manda o id da ficha a ser deletada por GET
				window.location.href = "despesasInserir.php?op=deleteCategoria&idCategoria=" + id;
			}
		}
	</script>
	<script>
        $("#tabela-categorias").DataTable({
            pageLength: 6,
            order: [[1, 'asc']],
            language: {
              url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
            },
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

		$("#tabela-despesas").DataTable({
          pageLength: 5,
        });
    </script>
    
  	</body>
</html>
