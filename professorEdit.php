<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 3;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Obtem o id do admin via GET
    $idProfessor = $_GET['idProf'];
    $mesSelecionado = $_GET['mes'] ?? date('Y-m');
    $recibo = true;
    $tab = isset($_GET['tab']) ? $_GET['tab'] : '0';
    if ($tab == "recibo") {
        $estouEm = 3;
    }

    $stmt = $con->prepare("SELECT * FROM professores WHERE id = ?");
    $stmt->bind_param("i", $idProfessor);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowProfessor = $result->fetch_assoc();
    } else {
        notificacao('warning', 'ID do professor inválido.');
        header('Location: professor.php');
        exit();
    }

    $sql = "SELECT valor FROM valores_pagamento;";
    $result = $con->query($sql);
    $valores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $valores[] = $row['valor'];
        }
    }

    list($ano, $mes) = explode('-', $mesSelecionado);

    if ($mes == date("n") && $ano == date("Y")) {
        //Horas dadas 1 Ciclo
        $valorParcial1Ciclo = 0;
        $horasDadas1Ciclo = 0;
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano >= 1 AND a.ano <= 4 AND p.idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadas1Ciclo = $horasDadas1Ciclo + $row["duracao"];
            }
            $horasDadas1Ciclo = decimalParaHoraMinutos(minutosToValor($horasDadas1Ciclo));
            $valorParcial1Ciclo = $horasDadas1Ciclo * $valores[0];
        }

        //Horas dadas 2 Ciclo
        $valorParcial2Ciclo = 0;
        $horasDadas2Ciclo = 0;
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 4 AND a.ano < 7 AND p.idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadas2Ciclo = $horasDadas2Ciclo + $row["duracao"];
            }
            $horasDadas2Ciclo = decimalParaHoraMinutos(minutosToValor($horasDadas2Ciclo));
            $valorParcial2Ciclo = $horasDadas2Ciclo * $valores[1];
        }

        //Horas dadas 3 Ciclo
        $valorParcial3Ciclo = 0;
        $horasDadas3Ciclo = 0;
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 6 AND a.ano <= 9 AND p.idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadas3Ciclo = $horasDadas3Ciclo + $row["duracao"];
            }
            $horasDadas3Ciclo = decimalParaHoraMinutos(minutosToValor($horasDadas3Ciclo));
            $valorParcial3Ciclo = $horasDadas3Ciclo * $valores[2];
        }

        //Horas dadas secundario
        $valorParcialSecundario = 0;
        $horasDadasSecundario = 0;
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 9 AND p.idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadasSecundario = $horasDadasSecundario + $row["duracao"];
            }
            $horasDadasSecundario = decimalParaHoraMinutos(minutosToValor($horasDadasSecundario));
            $valorParcialSecundario = $horasDadasSecundario * $valores[3];
        }

        //Horas dadas Universidade
        $valorParcialUniversidade = 0;
        $horasDadasUniversidade = 0;
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano = 0 AND p.idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadasUniversidade = $horasDadasUniversidade + $row["duracao"];
            }
            $horasDadasUniversidade = decimalParaHoraMinutos(minutosToValor($horasDadasUniversidade));
            $valorParcialUniversidade = $horasDadasUniversidade * $valores[4];
        }

        $total = $valorParcial1Ciclo + $valorParcial2Ciclo + $valorParcial3Ciclo + $valorParcialSecundario + $valorParcialUniversidade; 
    }
    else {
        $sql = "SELECT * FROM professores_recibo WHERE idProfessor = $idProfessor AND mes = $mes AND ano = $ano";
        $result = $con->query($sql);
        //Se houver um aluno com o id recebido, guarda as informações
        if ($result->num_rows > 0) {
            $rowRecibo = $result->fetch_assoc();
            $total = $rowRecibo["valorParcial1Ciclo"] + $rowRecibo["valorParcial2Ciclo"] + $rowRecibo["valorParcial3Ciclo"] + $rowRecibo["valorParcialSecundario"] + $rowRecibo["valorParcialUniversidade"]; 
        }
        else {
            $recibo = false;
        }
    }
?>
    <title>4x1 | Editar Professor</title>
    <style>
        .card {
            min-height: 100vh !important;
        }
        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: white !important;
            opacity: 1 !important;
            border: 1px solid #ced4da !important; /* igual ao form-control */
        }
    </style>
    <script>
        $(document).ready(function() {
            // Obtém o parâmetro da URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab'); // Exemplo: ?tab=recibo

            // Define qual aba abrir com base no parâmetro
            let abaDesejada;
            switch (tabParam) {
                case 'recibo':
                    abaDesejada = '#recibo-tab';
                    break;
                default:
                    abaDesejada = '#editar-prof-tab'; // Aba padrão
            }

            // Ativa a aba
            $(abaDesejada).tab('show');
        });
    </script>
</head>
    <body>
        <div class="wrapper">
            <?php 
                include('./sideBar.php'); 
            ?>
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                            <li class="nav-item1">
                                <a class="nav-link active" id="editar-prof-tab" data-bs-toggle="pill" href="#editar-prof" role="tab" aria-controls="editar-prof" aria-selected="true">Ficha do Profesor</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="explicacoes-tab" data-bs-toggle="pill" href="#explicacoes" role="tab" aria-controls="explicacoes" aria-selected="false">Explicações</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="recibo-tab" data-bs-toggle="pill" href="#recibo" role="tab" aria-controls="recibo" aria-selected="false">Recibo</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="editar-prof" role="tabpanel" aria-labelledby="editar-prof-tab">
                                <div class="col-12 col-md-10 col-lg-8 mx-auto">
                                    <form action="professorInserir.php?idProf=<?php echo $idProfessor ?>&op=edit" id="formEdit" method="POST" onsubmit="return verificarPasswords(event, '<?php echo $rowProfessor['email']; ?>')">
                                        <!-- <div
                                            class="modal fade"
                                            id="addRowModal"
                                            tabindex="-1"
                                            role="dialog"
                                            aria-hidden="true"
                                        >
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <h1>DISPONIBILIDADE</h1>
                                                    <table>
                                                        <tr>
                                                            <th>Segunda</th>
                                                            <th>Terça</th>
                                                            <th>Quarta</th>
                                                            <th>Quinta</th>
                                                            <th>Sexta</th>
                                                            <th>Sábado</th>
                                                        </tr>
                                                        <?php 
                                                            $horas = ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'];
                                                            $horasFDS = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00'];
                                                            $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
                                                        
                                                            for ($i = 1; $i <= 11; $i++) {
                                                                echo "<tr>";
                                                                foreach ($dias as $dia) {
                                                                    $hora = ($dia == 'Sábado') ? ($horasFDS[$i] ?? '') : ($horas[$i] ?? '');
                                                                    $disponibilidade = 0;
                                                                    $sql = "SELECT disponibilidade FROM professores_disponibilidade WHERE idProfessor = ? AND dia = ? AND hora = ?";
                                                                    $result = $con->prepare($sql);
                                                                    $result->bind_param('iss', $idProfessor, $dia, $hora);
                                                                    $result->execute(); 
                                                                    $result->store_result();
                                                                    $result->bind_result($disponibilidade);
                                                                    $result->fetch();
                                                                    if ($disponibilidade == 1) {
                                                                        $estado = "checked=\"\"";
                                                                    }
                                                                    else{
                                                                        $estado = "";
                                                                    }

                                                                    if ($hora) {
                                                                        echo "<td>
                                                                                <label class='selectgroup-item' id='selectgroup-item'>
                                                                                    <input type=\"checkbox\" name=\"disponibilidade_" . $dia . "_" . $hora . "\" class=\"selectgroup-input\" id=\"selectgroup-input\" " . $estado . "/>
                                                                                    <span class='selectgroup-button' id='selectgroup-button'>$hora</span>
                                                                                </label>
                                                                            </td>";
                                                                    } else {
                                                                        echo "<td></td>";
                                                                    }
                                                                }
                                                                echo "</tr>";
                                                            }
                                                        ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="page-inner">
                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label for="nome" class="form-label">Nome:</label>
                                                    <input type="text" class="form-control" name="nome" value="<?php echo $rowProfessor['nome']; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email" class="form-label">Email:</label>
                                                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $rowProfessor['email']; ?>" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="contacto" class="form-label">Contacto:</label>
                                                    <input type="tel" id="contacto" class="form-control" name="contacto" value="<?php echo $rowProfessor['contacto']; ?>" required>
                                                    <input type="hidden" name="contacto" id="contactoHidden">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="password" class="form-label">Password:</label>
                                                    <input type="password" class="form-control" name="password" id="password">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="passwordConfirm" class="form-label">Confirmar password:</label>
                                                    <input type="password" class="form-control" name="passwordConfirm" id="passwordConfirm">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="estado" class="form-label">Estado:</label>
                                                    <select class="form-control" name="estado" >
                                                        <option value='1' <?php if ($rowProfessor['ativo'] == 1) { echo "selected"; }?>>Ativo</option>
                                                        <option value='0' <?php if ($rowProfessor['ativo'] == 0) { echo "selected"; }?>>Inativo</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Disciplinas -->
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label>DISCIPLINAS:</label>
                                                    <div class="selectgroup selectgroup-pills">
                                                        <?php 
                                                            $sql = "SELECT id, nome FROM disciplinas;";
                                                            $result = $con->query($sql);
                                                            if ($result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $sql = "SELECT * FROM professores_disciplinas WHERE idProfessor = ? AND idDisciplina = ?";
                                                                    $result1 = $con->prepare($sql);
                                                                    $result1->bind_param('ii', $idProfessor, $row['id']);
                                                                    $result1->execute(); 
                                                                    $result1->store_result();
                                                                    if ($result1->num_rows > 0) {
                                                                        $estado = "checked=\"\"";
                                                                    }
                                                                    else{
                                                                        $estado = "";
                                                                    }
                                                                    echo "<label class='selectgroup-item'>
                                                                            <input type='checkbox' name='disciplina_" . $row['id'] . "' value='" . $row['nome'] . "' class='selectgroup-input' " . $estado . " />
                                                                            <span class='selectgroup-button' style=\"padding: 5px\">" . $row['nome'] . "</span>
                                                                        </label>";
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-5">
                                                    <label>ENSINO:</label>
                                                    <div class="selectgroup selectgroup-pills">
                                                        <?php 
                                                            $sql = "SELECT id, nome FROM ensino WHERE id < 6;";
                                                            $result = $con->query($sql);
                                                            if ($result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    $sql = "SELECT * FROM professores_ensino WHERE idProfessor = ? AND idEnsino = ?";
                                                                    $result1 = $con->prepare($sql);
                                                                    $result1->bind_param('ii', $idProfessor, $row['id']);
                                                                    $result1->execute(); 
                                                                    $result1->store_result();
                                                                    if ($result1->num_rows > 0) {
                                                                        $estado = "checked=\"\"";
                                                                    }
                                                                    else{
                                                                        $estado = "";
                                                                    }
                                                                    echo "<label class='selectgroup-item'>
                                                                            <input type='checkbox' name='ensino_" . $row['id'] . "' value='" . $row['nome'] . "' class='selectgroup-input' " . $estado . " />
                                                                            <span class='selectgroup-button' style=\"padding: 5px\">" . $row['nome'] . "</span>
                                                                        </label>";
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <label>DISPONIBILIDADE:</label>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addRowModal"
                                            >
                                                DISPONIBILIDADE
                                            </button> -->
                                            <button type="submit" class="btn btn-primary">Guardar alterações</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="explicacoes" role="tabpanel" aria-labelledby="explicacoes-tab">
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
                                                            <th>Professor</th>
                                                            <th>Disciplina</th>
                                                            <th>Duração(h)</th>
                                                            <th>Tipo</th>
                                                            <th>Dia</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Professor</th>
                                                            <th>Disciplina</th>
                                                            <th>Duração(h)</th>
                                                            <th>Tipo</th>
                                                            <th>Dia</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                            //query para selecionar todos os administradores
                                                            $sql = "SELECT p.nome as nomeProfessor, d.nome as nomeDisciplina, individual, duracao, DATE_FORMAT(dia, '%d-%m-%Y') as dia FROM alunos_presenca as ap INNER JOIN alunos as a ON ap.idAluno = a.id INNER JOIN professores as p ON ap.idProfessor = p.id INNER JOIN disciplinas as d ON ap.idDisciplina = d.id WHERE p.id = $idProfessor;";
                                                            $result = $con->query($sql);
                                                            if ($result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) { 
                                                                    if ($row['individual'] == 1) {
                                                                        $tipo = "Individual";
                                                                    }
                                                                    else {
                                                                        $tipo = "Grupo";
                                                                    }?>
                                                                    <tr>
                                                                        <td><?php echo $row['nomeProfessor'] ?></td>
                                                                        <td><?php echo $row['nomeDisciplina'] ?></td>
                                                                        <td><?php echo decimalParaHoraMinutos(minutosToValor($row['duracao'])) ?></td>
                                                                        <td><?php echo $tipo ?></td>
                                                                        <td><?php echo $row['dia'] ?></td>
                                                                    </tr>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="recibo" role="tabpanel" aria-labelledby="recibo-tab">
                                <form action="" method="GET">
                                    <div class="select-container">
                                        <input type="hidden" style="display: none;" name="idProf" value="<?= $idProfessor ?>">
                                        <input type="hidden" style="display: none;" name="tab" value="recibo">
                                        
                                        <label for="mes" class="form-label mb-0 me-2">Data:</label>
                                        <input type="month" name="mes" id="mes" value="<?= $mesSelecionado ?>" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                                    </div>
                                </form>
                                <div class="page-inner">
                                    <div class="container2">
                                        <?php if ($recibo == true): ?>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label for="nome" class="form-label">Nome:</label>
                                                    <input type="text" name="nome" class="form-control" readonly value="<?php echo $rowProfessor['nome']; ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="horasGrupo" class="form-label">Horas 1º Ciclo:</label>
                                                    <input type="input" name="horasGrupo" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas1Ciclo;} else {echo decimalParaHoraMinutos($rowRecibo['horasDadas1Ciclo']);} ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorUnitario" class="form-label">Valor unitário:</label>
                                                    <input type="input" name="valorUnitario" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[0];} else {echo $rowRecibo['valorUnitario1Ciclo'];} ?>€" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="horasBalanco" class="form-label">Valor parcial:</label>
                                                    <input type="input" name="horasBalanco" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial1Ciclo;} else {echo $rowRecibo['valorParcial1Ciclo'];} ?>€" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="horas2Ciclo" class="form-label">Horas 2º Ciclo:</label>
                                                    <input type="input" name="horasGrupo" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas2Ciclo;} else {echo decimalParaHoraMinutos($rowRecibo['horasDadas2Ciclo']);} ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorUnitario2Ciclo" class="form-label">Valor unitário:</label>
                                                    <input type="input" name="valorUnitario" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[1];} else {echo $rowRecibo['valorUnitario2Ciclo'];} ?>€" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorParcial2Ciclo" class="form-label">Valor parcial:</label>
                                                    <input type="input" name="horasBalanco" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial2Ciclo;} else {echo $rowRecibo['valorParcial2Ciclo'];} ?>€" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="horas3Ciclo" class="form-label">Horas 3º Ciclo:</label>
                                                    <input type="input" name="horasGrupo" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas3Ciclo;} else {echo decimalParaHoraMinutos($rowRecibo['horasDadas3Ciclo']);} ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorUnitario3Ciclo" class="form-label">Valor unitário:</label>
                                                    <input type="input" name="valorUnitario" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[2];} else {echo $rowRecibo['valorUnitario3Ciclo'];} ?>€" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorParcial3Ciclo" class="form-label">Valor parcial:</label>
                                                    <input type="input" name="horasBalanco" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial3Ciclo;} else {echo $rowRecibo['valorParcial3Ciclo'];} ?>€" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="horasSecundario" class="form-label">Horas Secundário:</label>
                                                    <input type="input" name="horasGrupo" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadasSecundario;} else {echo decimalParaHoraMinutos($rowRecibo['horasDadasSecundario']);} ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorUnitarioSecundario" class="form-label">Valor unitário:</label>
                                                    <input type="input" name="valorUnitario" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[3];} else {echo $rowRecibo['valorUnitarioSecundario'];} ?>€" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorParcialSecundario" class="form-label">Valor parcial:</label>
                                                    <input type="input" name="horasBalanco" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcialSecundario;} else {echo $rowRecibo['valorParcialSecundario'];} ?>€" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="horasUniversidade" class="form-label">Horas Universidade:</label>
                                                    <input type="input" name="horasGrupo" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadasUniversidade;} else {echo decimalParaHoraMinutos($rowRecibo['horasDadasUniversidade']);} ?>" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorUnitarioUniversidade" class="form-label">Valor unitário:</label>
                                                    <input type="input" name="valorUnitario" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[4];} else {echo $rowRecibo['valorUnitarioUniversidade'];} ?>€" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="valorParcialUniversidade" class="form-label">Valor parcial:</label>
                                                    <input type="input" name="horasBalanco" class="form-control" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcialUniversidade;} else {echo $rowRecibo['valorParcialUniversidade'];} ?>€" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="total" class="form-label">Total:</label>
                                                    <input type="input" name="total" class="form-control" value="<?php echo $total; ?>€" readonly>
                                                </div>
                                            </div>

                                        <?php else: ?>
                                            <p>Sem recibo nesta data.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
            <script>
                const input = document.querySelector("#contacto");
                const hiddenInput = document.querySelector("#contactoHidden");
                const iti = window.intlTelInput(input, {
                    initialCountry: "pt",
                    preferredCountries: ["pt", "br", "fr", "gb"],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });
                // Ao submeter o formulário, atualiza o campo hidden
                document.querySelector("#formEdit").addEventListener("submit", function () {
                    hiddenInput.value = iti.getNumber();
                });

                function verificarPasswords(e, emailAntigo) {
                    e.preventDefault();
                    const password = document.getElementById("password").value;
                    const confirm = document.getElementById("passwordConfirm").value;
                    const emailAdmin = document.getElementById("email").value;

                    let erro = 0;

                    if (password !== confirm) {
                        $.notify({
                            message: 'As palavras passes não coincidem!',
                            title: 'Notificação',
                            icon: 'fa fa-info-circle',
                        }, {
                            type: 'warning',
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            delay: 3000
                        });
                        erro += 1;
                    }

                    if (erro === 0 && emailAntigo !== emailAdmin) {
                        $.ajax({
                            url: 'json.obterEmails.php',
                            type: 'GET',
                            success: function(response) 
                            {
                                var data = JSON.parse(response);
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].email === emailAdmin ) {
                                        $.notify({
                                            message: 'Esse email já existe no sistema.',
                                            title: 'Notificação',
                                            icon: 'fa fa-info-circle',
                                        }, {
                                            type: 'warning',
                                            placement: {
                                                from: 'top',
                                                align: 'right'
                                            },
                                            delay: 3000
                                        });

                                        erro += 1;
                                        break;
                                    }
                                }
                                if (erro === 0) {
                                    e.target.submit();
                                }
                            },
                            error: function() {
                                console.error('Erro ao buscar os emails.');
                            }
                        });
                    }
                    else{
                        e.target.submit();
                    }
                }
            </script>
        </div>
        <?php 
            include('./endPage.php');
        ?>
    </body>
    </html>

