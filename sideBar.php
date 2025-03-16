<?php
    include('./db/conexao.php');
?>
<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <!-- src="./images/logo4x1BrancoSemFundo.png" -->
        <div class="logo-header" data-background-color="dark">
            <a href="dashboard.php" class="logo">
                <img
                alt="navbar brand"
                class="navbar-brand"
                height="20"
                />
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
                        <i class="fas fa-home"></i>
                        <p>Alunos</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 3) ? 'active' : ''; ?>">
                    <a href="professor.php">
                        <i class="fas fa-home"></i>
                        <p>Professores</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 4) ? 'active' : ''; ?>">
                    <a href="presenca.php">
                        <i class="fas fa-home"></i>
                        <p>Registro de presença</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 5) ? 'active' : ''; ?>">
                    <a href="presenca.php">
                        <i class="fas fa-home"></i>
                        <p>Pagamentos</p>
                    </a>
                </li>
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
        class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
        >
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
                    src="<?php echo $imgAdmin; ?>"
                    alt="..."
                    class="avatar-img rounded-circle"
                    />
                </div>
                <span class="profile-username">
                    <span class="fw-bold"><?php echo $nomeAdmin; ?></span>
                </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                    <div class="user-box">
                        <div class="avatar-lg">
                        <img
                            src="<?php echo $imgAdmin; ?>"
                            alt="image profile"
                            class="avatar-img rounded"
                        />
                        </div>
                        <div class="u-text">
                        <h4><?php echo $nomeAdmin; ?></h4>
                        <p class="text-muted"><?php echo $emailAdmin; ?></p>
                        <a
                            href="profile.html"
                            class="btn btn-xs btn-secondary btn-sm"
                            >Ver Perfil</a
                        >
                        </div>
                    </div>
                    </li>
                    <li>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Perfil</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Definições</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="indexLogout">Logout</a>
                    </li>
                </div>
                </ul>
            </li>
            </ul>
        </div>
        </nav>
    <!-- End Navbar -->
    </div>