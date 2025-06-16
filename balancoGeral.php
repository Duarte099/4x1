<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 17;

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Saldo total
    $stmt = $con->prepare("SELECT IFNULL(SUM(CASE
                                        WHEN tipo = 'credito' THEN valor
                                        WHEN tipo = 'debito' THEN -valor
                                        ELSE 0
                                    END), 0) AS saldo_total
                            FROM transacoes as t
                            INNER JOIN categorias as c ON t.idCategoria = c.id");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $saldoTotal = $row['saldo_total'];
    }
    else {
        $saldoTotal = 0;
    }

    //Lucro mês atual
    $stmt = $con->prepare("SELECT
        IFNULL(SUM(CASE WHEN tipo = 'credito' THEN valor ELSE 0 END), 0) AS total_creditos,
        IFNULL(SUM(CASE WHEN tipo = 'debito' THEN valor ELSE 0 END), 0) AS total_debitos,
        IFNULL(SUM(CASE WHEN tipo = 'credito' THEN valor ELSE -valor END), 0) AS lucro
        FROM transacoes as t
        INNER JOIN categorias as c ON t.idCategoria = c.id
        WHERE MONTH(data) = MONTH(CURDATE()) AND YEAR(data) = YEAR(CURDATE())
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lucroMesAtual = $row['lucro'];
    }
    else {
        $lucroMesAtual = 0;
    }

    //Total Creditos no mes atual
    $stmt = $con->prepare("SELECT
        IFNULL(SUM(valor), 0) AS total_creditos
        FROM transacoes as t
        INNER JOIN categorias as c ON t.idCategoria = c.id
        WHERE tipo = 'credito'
        AND MONTH(data) = MONTH(CURDATE())
        AND YEAR(data) = YEAR(CURDATE());
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalCredito = $row['total_creditos'];
    }
    else {
        $totalCredito= 0;
    }

    //Total Debitos no mes atual
    $stmt = $con->prepare("SELECT 
        IFNULL(SUM(valor), 0) AS total_debitos
        FROM transacoes as t
        INNER JOIN categorias as c ON t.idCategoria = c.id
        WHERE tipo = 'debito'
        AND MONTH(data) = MONTH(CURDATE())
        AND YEAR(data) = YEAR(CURDATE());
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalDebito = $row['total_debitos'];
    }
    else {
        $totalDebito= 0;
    }

    //Total pagamentos hoje
    $stmt = $con->prepare("SELECT
        IFNULL(SUM(mensalidadeGrupo + mensalidadeIndividual + transporte + inscricao), 0) AS total_dia
        FROM alunos_recibo
        WHERE DATE(pagoEm) = CURDATE()
        AND idMetodo = 1;
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalDia = $row['total_dia'];
    }
    else {
        $totalDia= 0;
    }
?>
<title>Balanço geral | 4x1</title>
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
                        <h3 class="fw-bold mb-3">Balanço geral</h3>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
                    <div class="col">
                        <div class="card card-stats card-success card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-wallet"></i> <!-- Ícone para saldo -->
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Saldo total</p>
                                            <h4 class="card-title"><?php echo $saldoTotal; ?>€</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card card-stats card-primary card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-dollar-sign"></i> <!-- Ícone para lucro -->
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Lucro mês atual</p>
                                            <h4 class="card-title"><?php echo $lucroMesAtual; ?>€</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card card-stats card-info card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-arrow-up"></i> <!-- Ícone para crédito -->
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Crédito mês atual</p>
                                            <h4 class="card-title"><?php echo $totalCredito; ?>€</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card card-stats card-danger card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-arrow-down"></i> <!-- Ícone para débito -->
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Débitos mês atual</p>
                                            <h4 class="card-title"><?php echo $totalDebito; ?>€</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card card-stats card-warning card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-clock"></i> <!-- Ícone para "a receber" -->
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">A receber hoje</p>
                                            <h4 class="card-title"><?php echo $totalDia; ?>€</h4>
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
                                <div class="card-title">Lucro por mês</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="lineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Creditos e debitos ano atual</div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="multipleLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        fetch('json.obterLucroPorMes.php')
            .then(response => response.json())
            .then(dados => {
                const lucroPorMes = Array(12).fill(0);

                dados.forEach(item => {
                    const indiceMes = item.mes - 1;
                    lucroPorMes[indiceMes] = parseFloat(item.lucro);
                });

                var lineChart = document.getElementById("lineChart").getContext("2d");

                var myLineChart = new Chart(lineChart, {
                    type: "line",
                    data: {
                        labels: [
                            "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                            "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
                        ],
                        datasets: [
                            {
                                label: "Lucro",
                                borderColor: "#1d7af3",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#1d7af3",
                                pointBorderWidth: 2,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 1,
                                pointRadius: 4,
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: lucroPorMes,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: "bottom",
                            labels: {
                                padding: 10,
                                fontColor: "#1d7af3",
                            },
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
        .catch(error => {console.error('Erro ao obter os dados:', error);});
        
        fetch('json.obterCreditoDebitoPorMes.php')
            .then(response => response.json())
            .then(data => {
                const meses = [
                    "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                    "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
                ];

                const creditos = data.map(m => parseFloat(m.total_creditos));
                const debitos = data.map(m => parseFloat(m.total_debitos));

                const ctx = document.getElementById("multipleLineChart").getContext("2d");

                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: meses,
                        datasets: [
                            {
                                label: "Crédito",
                                borderColor: "#48abf7",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#48abf7",
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: creditos,
                            },
                            {
                                label: "Débito",
                                borderColor: "#f25961",
                                pointBorderColor: "#FFF",
                                pointBackgroundColor: "#f25961",
                                backgroundColor: "transparent",
                                fill: true,
                                borderWidth: 2,
                                data: debitos,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "top"
                            },
                            tooltip: {
                                mode: "index",
                                intersect: false,
                                padding: 10,
                            }
                        },
                        layout: {
                            padding: 15,
                        }
                    }
                });
            })
        .catch(error => {console.error("Erro ao carregar os dados do gráfico:", error);});
    </script>
    <?php 
        include('./endPage.php');
    ?>
  </body>
</html>
