<?php
    include('./db/conexao.php');

    $numAlunosAniversario = 0;

    $stmt = $con->prepare("SELECT COUNT(*) as numAlunos FROM alunos WHERE ativo = 1 AND DATE_FORMAT(dataNascimento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowAniversario = $result->fetch_assoc();
        $numAlunosAniversario = $rowAniversario['numAlunos'];
    }
?>
<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        
        <div class="logo-header" data-background-color="dark">
            <a href="dashboard.php" class="logo">
                <img src="./images/LogoBranco4x1.png" alt="navbar brand" class="navbar-brand" height="45">
                <!-- <img src="./images/logoBranco.png" alt="navbar brand" class="navbar-brand" height="20"> -->
                <!-- <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20"> -->
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item <?php echo ($estouEm == 1) ? 'active' : ''; ?>">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 2) ? 'active' : ''; ?>">
                    <a href="aluno.php">
                        <i class="fas fa-user-graduate"></i>
                        <p>Alunos</p>
                        <?php if ($numAlunosAniversario > 0) { ?>
                            <span class="badge badge-success"><?php echo $numAlunosAniversario; ?></span>
                        <?php } ?>
                    </a>
                </li>
                <?php if ($_SESSION["tipo"] == "administrador") { ?>
                    <li class="nav-item <?php echo ($estouEm == 3) ? 'active' : ''; ?>">
                        <a href="professor.php">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <p>Professores</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($estouEm == 4) ? 'active' : ''; ?>">
                        <a href="professorLogs.php">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Logs Professores</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item <?php echo ($estouEm == 5) ? 'active' : ''; ?>">
                    <a href="presenca.php">
                        <i class="fas fa-calendar-check"></i>
                        <p>Registro de Presença</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 6) ? 'active' : ''; ?>">
                    <a href="testes.php">
                        <i class="fas fa-file-alt"></i>
                        <p>Registro de Testes</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 7) ? 'active' : ''; ?>">
                    <a href="pagamentoEstado.php">
                        <i class="fas fa-euro-sign"></i>
                        <p>Estado Pagamentos</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 8) ? 'active' : ''; ?>">
                    <a href="estadoAlunos.php">
                        <i class="fas fa-user-check"></i>
                        <p>Estado Alunos</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 9) ? 'active' : ''; ?>">
                    <a href="horario.php">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Horário</p>
                    </a>
                </li>
                <?php if ($_SESSION["tipo"] == "administrador") { ?>
                    <li class="nav-item <?php echo ($estouEm == 10) ? 'active' : ''; ?>">
                        <a href="admin.php">
                            <i class="fas fa-user-cog"></i>
                            <p>Administradores</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($estouEm == 11) ? 'active' : ''; ?>">
                        <a href="adminLogs.php">
                            <i class="fas fa-file-signature"></i>
                            <p>Logs Administradores</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($estouEm == 12) ? 'active' : ''; ?>">
                        <a href="pagamentoConfig.php">
                            <i class="fas fa-cogs"></i>
                            <p>Configurações Pagamento</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($estouEm == 13) ? 'active' : ''; ?>">
                        <a href="transacoes.php">
                            <i class="fas fa-exchange-alt"></i>
                            <p>Transações</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($estouEm == 14) ? 'active' : ''; ?>">
                        <a href="despesas.php">
                            <i class="fas fa-receipt"></i>
                            <p>Despesas e Categorias</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($estouEm == 15) ? 'active' : ''; ?>">
                        <a href="balancoGeral.php">
                            <i class="fas fa-chart-pie"></i>
                            <p>Balanço Geral</p>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
<div class="main-panel">
    <div class="main-header">
        <div class="main-header-logo">
        <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="index.html" class="logo">
                <img
                    src="assets/img/kaiadmin/logo_light.svg"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="20"
                />
                </a>
            </div>
        <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <nav
        class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
                <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                    <li class="nav-item topbar-user dropdown hidden-caret">
                        <a
                            class="dropdown-toggle profile-pic"
                            data-bs-toggle="dropdown"
                            href="#"
                            aria-expanded="false"
                        >
                            <div class="avatar-sm">
                                <img
                                src="<?php echo $_SESSION['img']; ?>"
                                alt="..."
                                class="avatar-img rounded-circle"
                                />
                            </div>
                            <span class="profile-username">
                                <span class="fw-bold"><?php echo $_SESSION['nome']; ?></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg">
                                        <img
                                            src="<?php echo $_SESSION['img']; ?>"
                                            alt="image profile"
                                            class="avatar-img rounded"
                                        />
                                        </div>
                                        <div class="u-text">
                                        <h4><?php echo $_SESSION['nome']; ?></h4>
                                        <p class="text-muted"><?php echo $_SESSION['email']; ?></p>
                                        <a
                                            href="perfil.php"
                                            class="btn btn-xs btn-secondary btn-sm"
                                            >Ver Perfil</a
                                        >
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="definicoes.php">Definições</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="indexLogout.php">Logout</a>
                                </li>
                            </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    <!-- End Navbar -->
    </div>