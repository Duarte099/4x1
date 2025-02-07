<?php
    include('./db/conexao.php');
?>
<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
        <a href="dashboard.html" class="logo">
            <img
            src="assets/img/kaiadmin/logo_light.svg"
            alt="navbar brand"
            class="navbar-brand"
            height="20"
            />
        </a>
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
                    <a href="dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($estouEm == 2) ? 'active' : ''; ?> submenu">
                    <a data-bs-toggle="collapse" href="#alunos">
                        <i class="fas fa-th-list"></i>
                        <p>Alunos</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="alunos">
                        <ul class="nav nav-collapse">
                            <li <?php echo ($estouEm == 2 && $estouEm2 == 1) ? 'class="active"' : ''; ?>>
                                <a href="aluno">
                                    <span class="sub-item">Ver Alunos</span>
                                </a>
                            </li>
                            <li <?php echo ($estouEm == 2 && $estouEm2 == 2) ? 'class="active"' : ''; ?>>
                                <a href="alunoCriar">
                                <span class="sub-item">Criar Aluno</span>
                                </a>
                            </li>
                            <?php if ($estouEm == 2 && $estouEm2 == 3) { ?>
                                <li class="active">
                                    <a href="alunoEdit?idAluno=<?php echo $idAluno?>&op=edit">
                                    <span class="sub-item">Editar Aluno</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php echo ($estouEm == 3) ? 'active' : ''; ?> submenu">
                    <a data-bs-toggle="collapse" href="#professores">
                        <i class="fas fa-th-list"></i>
                        <p>Professores</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="professores">
                        <ul class="nav nav-collapse">
                            <li <?php echo ($estouEm == 3 && $estouEm2 == 1) ? 'class="active"' : ''; ?>>
                                <a href="professor">
                                    <span class="sub-item">Ver Professores</span>
                                </a>
                            </li>
                            <li <?php echo ($estouEm == 3 && $estouEm2 == 2) ? 'class="active"' : ''; ?>>
                                <a href="professorCriar">
                                <span class="sub-item">Criar Professor</span>
                                </a>
                            </li>
                            <?php if ($estouEm == 3 && $estouEm2 == 3) { ?>
                                <li class="active">
                                    <a href="professorEdit?idProfessor=<?php echo $idProfessor?>&op=edit">
                                    <span class="sub-item">Editar Professor</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
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