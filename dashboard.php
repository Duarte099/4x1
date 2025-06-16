<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 1;

    $_SESSION['testes'] = true;
?>
<title>4x1 | Dashboard</title>
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
                        <h3 class="fw-bold mb-3">Dashboard</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Alunos</p>
                                            <h4 class="card-title">
                                                <?php 
                                                    $sql = "SELECT COUNT(*) AS numeroAlunos FROM alunos WHERE ativo = 1";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo $row['numeroAlunos'];
                                                        }
                                                    } else {
                                                        echo "0";
                                                    }
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-info bubble-shadow-small">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Professores</p>
                                            <h4 class="card-title">
                                                <?php 
                                                    $sql = "SELECT COUNT(*) AS numeroProfessores FROM professores WHERE ativo = 1";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo $row['numeroProfessores'];
                                                        }
                                                    } else {
                                                        echo "0";
                                                    }
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-success bubble-shadow-small">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Horas totais de explicações</p>
                                            <h4 class="card-title">
                                                <?php 
                                                    $minutosTotais = 0;
                                                    $sql = "SELECT duracao FROM alunos_presenca as ap INNER JOIN alunos as a ON ap.idAluno = a.id WHERE ativo = 1";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $minutosTotais += $row['duracao'];
                                                        }
                                                        echo decimalParaHoraMinutos(minutosToValor($minutosTotais)) . "h";
                                                    } else {
                                                        echo "0";
                                                    }
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-icon">
                                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col col-stats ms-3 ms-sm-0">
                                        <div class="numbers">
                                            <p class="card-category">Alunos totais</p>
                                            <h4 class="card-title">
                                                <?php 
                                                    $sql = "SELECT COUNT(*) AS numeroAlunos FROM alunos";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo $row['numeroAlunos'];
                                                        }
                                                    } else {
                                                        echo "0";
                                                    }
                                                ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Horas dadas por ensino</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="multipleLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Horas dadas pelos professores</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
							<div class="card-header">
								<div class="d-flex align-items-center">
									<h4 class="card-title">Testes</h4>
									<button
										class="btn btn-primary btn-round ms-auto"
                                        onclick="window.location.href='testes.php';"
									>
										<i class="fa fa-plus"></i>
										Adicionar teste
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table
										id="tabela-testes"
										class="display table table-striped table-hover"
									>
										<thead>
											<tr>
												<th>Aluno</th>
                                                <th>Ensino</th>
												<th>Disciplina</th>
												<th>Dia</th>
											</tr>
										</thead>
										<tbody>
											<?php
												//query para selecionar todos os administradores
												$sql = " SELECT 
                                                            a.nome AS nomeAluno, 
                                                            d.nome AS nomeDisciplina, 
                                                            dia,
                                                            IF(a.ano>=1 AND a.ano<=4, \"1º CICLO\", IF(a.ano>4 AND a.ano<7, \"2º CICLO\", IF(a.ano>6 AND a.ano<=9, \"3º CICLO\", IF(a.ano>9 AND a.ano<=12, \"SECUNDÁRIO\", IF(a.ano=0, \"UNIVERSIDADE\", \"ERRO\"))))) as ensino
                                                        FROM 
                                                            alunos_testes 
                                                        INNER JOIN alunos AS a ON a.id = idAluno 
                                                        INNER JOIN disciplinas AS d ON d.id = idDisciplina 
                                                        WHERE dia >= CURDATE()
                                                        ORDER BY dia ASC
                                                    ";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) {
														?>
															<tr>
																<td><?php echo $row['nomeAluno'] ?></td>
                                                                <td><?php echo $row['ensino'] ?></td>
																<td><?php echo $row['nomeDisciplina'] ?></td>
																<td><?php echo $row['dia'] ?></td>
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
								<div class="d-flex align-items-center">
									<h4 class="card-title">Pagamentos em atraso</h4>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table
										id="tabela-pagamentos-atrasados"
										class="display table table-striped table-hover"
									>
										<thead>
											<tr>
												<th>Aluno</th>
                                                <th>Ensino</th>
												<th>Valor</th>
												<th>Data</th>
											</tr>
										</thead>
										<tbody>
											<?php
                                                $dataAnterior = new DateTime('first day of last month');
                                                $mes = $dataAnterior->format('n');
                                                $ano = $dataAnterior->format('Y');
												//query para selecionar todos os administradores
												$sql = " SELECT ar.*,
                                                            a.nome AS nomeAluno,
                                                            IF(a.ano>=1 AND a.ano<=4, \"1º CICLO\", IF(a.ano>4 AND a.ano<7, \"2º CICLO\", IF(a.ano>6 AND a.ano<=9, \"3º CICLO\", IF(a.ano>9 AND a.ano<=12, \"SECUNDÁRIO\", IF(a.ano=0, \"UNIVERSIDADE\", \"ERRO\"))))) as ensino
                                                        FROM alunos_recibo as ar
                                                        INNER JOIN alunos AS a ON a.id = ar.idAluno
                                                        WHERE ar.pagoEm = '0000-00-00 00:00:00' AND NOT (
                                                                ar.mes = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
                                                                AND ar.ano = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                                                            )
                                                ";
												$result = $con->query($sql);
												if ($result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) {
														?>
															<tr>
																<td><?php echo $row['nomeAluno'] ?></td>
                                                                <td><?php echo $row['ensino'] ?></td>
																<td><?php echo $row['mensalidadeGrupo'] + $row['mensalidadeIndividual'] + $row['transporte'] + $row['inscricao'] + $row['coima']?></td>
																<td data-order="<?php echo $row['ano'] ?>-<?php echo $row['mes'] ?>"><?php echo $row['mes'] ?>-<?php echo $row['ano'] ?></td>
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
    
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        fetch('json.obterHorasPorCiclo.php')
            .then(response => response.json())
            .then(data => {
                // Organizar os dados para o gráfico
                const dias = [];
                const ciclos = {
                    '1º CICLO': [],
                    '2º CICLO': [],
                    '3º CICLO': [],
                    'SECUNDÁRIO': [],
                    'UNIVERSIDADE': []
                };

                // Preencher os ciclos de ensino com os valores de horas
                data.forEach(row => {
                    if (!dias.includes(row.dia)) {
                        dias.push(row.dia);
                    }
                    if (row.ensino === '1º CICLO') {
                        ciclos['1º CICLO'].push(row.horas);
                    } else if (row.ensino === '2º CICLO') {
                        ciclos['2º CICLO'].push(row.horas);
                    } else if (row.ensino === '3º CICLO') {
                        ciclos['3º CICLO'].push(row.horas);
                    } else if (row.ensino === 'SECUNDÁRIO') {
                        ciclos['SECUNDÁRIO'].push(row.horas);
                    } else if (row.ensino === 'UNIVERSIDADE') {
                        ciclos['UNIVERSIDADE'].push(row.horas);
                    }
                });

                var multipleLineChart = document.getElementById("multipleLineChart").getContext("2d");
            
                var myMultipleLineChart = new Chart(multipleLineChart, {
                    type: "line",
                    data: {
                        labels: [
                            "Segunda",
                            "Terça",
                            "Quarta",
                            "Quinta",
                            "Sexta",
                            "Sábado",
                        ],
                        datasets: [
                            {
                                label: "1 Ciclo",
                                borderColor: "#FFD966",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#FFD966",
                                pointBorderWidth: 2,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 1,
                                pointRadius: 4,
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: ciclos['1º CICLO'],
                            },
                            {
                                label: "2 Ciclo",
                                borderColor: "#6FA8DC",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#6FA8DC",
                                pointBorderWidth: 2,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 1,
                                pointRadius: 4,
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: ciclos['2º CICLO'],
                            },
                            {
                                label: "3 Ciclo",
                                borderColor: "#8E7CC3",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#8E7CC3",
                                pointBorderWidth: 2,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 1,
                                pointRadius: 4,
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: ciclos['3º CICLO'],
                            },
                            {
                                label: "Secundario",
                                borderColor: "#E06666",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#E06666",
                                pointBorderWidth: 2,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 1,
                                pointRadius: 4,
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: ciclos['SECUNDÁRIO'],
                            },
                            {
                                label: "Universidade",
                                borderColor: "#6AA84F",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#6AA84F",
                                pointBorderWidth: 2,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 1,
                                pointRadius: 4,
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: ciclos['UNIVERSIDADE'],
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: "top",
                        },
                        tooltips: {
                            bodySpacing: 4,
                            mode: "nearest",
                            intersect: 0,
                            position: "nearest",
                            xPadding: 10,
                            yPadding: 10,
                            caretPadding: 10,
                        },
                        layout: {
                            padding: { left: 15, right: 15, top: 15, bottom: 15 },
                        },
                    },
                });
            })
        .catch(error => console.error('Erro ao buscar os dados:', error));

        fetch('json.obterHorasPorProfessor.php')
            .then(response => response.json())
            .then(data => {
                const nomesProfessores = [];
                const horasPorProfessor = [];

                data.forEach(row => {
                    nomesProfessores.push(row.professor);
                    horasPorProfessor.push(parseFloat(row.horas));
                });

                const barChart = document.getElementById("barChart").getContext("2d");

                const myBarChart = new Chart(barChart, {
                    type: "bar",
                    data: {
                        labels: nomesProfessores,
                        datasets: [{
                            label: "Horas lecionadas este mês",
                            backgroundColor: "rgb(23, 125, 255)",
                            borderColor: "rgb(23, 125, 255)",
                            data: horasPorProfessor
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: false // opcional: esconder eixo X
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Horas"
                                }
                            }
                        },
                        plugins: {
                            datalabels: {
                                anchor: "center",
                                align: "center", // <- isto centraliza horizontalmente
                                rotation: 90,     // <- sem rotação = texto na horizontal
                                color: "white",
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                formatter: function(value, context) {
                                    return nomesProfessores[context.dataIndex];
                                }
                            },
                            legend: {
                                display: true
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    },
                    plugins: [ChartDataLabels] // <- ativa o plugin
                });
            })
        .catch(error => console.error('Erro ao buscar os dados:', error));
    </script>
    <script>
        $("#tabela-pagamentos-atrasados").DataTable({
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
    </script>
    <script>
        $("#tabela-testes").DataTable({
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
    </script>
    <?php 
        include('./endPage.php');
    ?>
  </body>
</html>
