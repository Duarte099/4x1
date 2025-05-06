<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 15;

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
        IFNULL(SUM(mensalidadeGrupo + mensalidadeIndividual + transporte + inscricao + coima), 0) AS total_dia
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
<title>4x1 | Balanço geral</title>
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
            </div>
        </div>
    </div>
    <?php  
        include('./endPage.php'); 
    ?>
    <script>

    </script>
  </body>
</html>
