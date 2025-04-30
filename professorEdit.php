<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 3;
    $estouEm2 = 3;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Obtem o id do admin via GET
    $idProfessor = $_GET['idProf'];
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

    function minutosToValor($minutos){
        // Conversão para horas e minutos
        $horas = intdiv($minutos, 60);

        $minutosRestantes = $minutos % 60;

        // Conversão para horas decimais
        return $minutos / 60;
    }

    $sql = "SELECT valor FROM valores_pagamento;";
    $result = $con->query($sql);
    $valores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $valores[] = $row['valor'];
        }
    }

    if (isset($_GET['mes'])) {
        $partes = explode("-", $_GET['mes']);
        $mes = $partes[0];
        $ano = $partes[1];
    }
    else {
        $mes = date("m");
        $ano = date("Y");
    }

    if ((int)$mes === (int)date("m") && (int)$ano === (int)date("Y")) {
        //Horas dadas 1 Ciclo
        $valorParcial1Ciclo = 0;
        $horasDadas1Ciclo = 0;
        $sql = "SELECT duracao
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano >= 1 AND a.ano <= 4 AND idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadas1Ciclo = $horasDadas1Ciclo + $row["duracao"];
            }
            $horasDadas1Ciclo = minutosToValor($horasDadas1Ciclo);
            $valorParcial1Ciclo = $horasDadas1Ciclo * $valores[0];
        }

        //Horas dadas 2 Ciclo
        $valorParcial2Ciclo = 0;
        $horasDadas2Ciclo = 0;
        $sql = "SELECT duracao
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 4 AND a.ano < 7 AND idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadas2Ciclo = $horasDadas2Ciclo + $row["duracao"];
            }
            $horasDadas2Ciclo = minutosToValor($horasDadas2Ciclo);
            $valorParcial2Ciclo = $horasDadas2Ciclo * $valores[1];
        }

        //Horas dadas 3 Ciclo
        $valorParcial3Ciclo = 0;
        $horasDadas3Ciclo = 0;
        $sql = "SELECT duracao
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 6 AND a.ano <= 9 AND idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadas3Ciclo = $horasDadas3Ciclo + $row["duracao"];
            }
            $horasDadas3Ciclo = minutosToValor($horasDadas3Ciclo);
            $valorParcial3Ciclo = $horasDadas3Ciclo * $valores[2];
        }

        //Horas dadas secundario
        $valorParcialSecundario = 0;
        $horasDadasSecundario = 0;
        $sql = "SELECT duracao
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 9 AND idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadasSecundario = $horasDadasSecundario + $row["duracao"];
            }
            $horasDadasSecundario = minutosToValor($horasDadasSecundario);
            $valorParcialSecundario = $horasDadasSecundario * $valores[3];
        }

        //Horas dadas Universidade
        $valorParcialUniversidade = 0;
        $horasDadasUniversidade = 0;
        $sql = "SELECT duracao
                FROM professores_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano = 0 AND idProfessor = $idProfessor;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $horasDadasUniversidade = $horasDadasUniversidade + $row["duracao"];
            }
            $horasDadasUniversidade = minutosToValor($horasDadasUniversidade);
            $valorParcialUniversidade = $horasDadasUniversidade * $valores[4];
        }

        $total = $valorParcial1Ciclo + $valorParcial2Ciclo + $valorParcial3Ciclo + $valorParcialSecundario + $valorParcialUniversidade; 
    }
    else {
        $sql = "SELECT * FROM professores_recibo WHERE idProfessor = $idProfessor AND mes = $mes AND ano = $ano";
        $result = $con->query($sql);
        //Se houver um aluno com o id recebido, guarda as informações
        if ($result->num_rows >= 0) {
            $rowRecibo = $result->fetch_assoc();
        }
    }
?>
    <title>4x1 | Editar Professor</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css'>
    <style>
        .fc-day-header[data-date="0"],
        .fc-day[data-date$="-0"] {
            display: none;
        }

        h1 {
            text-align: center;
            color: #343a40;
        }

        table {
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            border: 2px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #dee2e6;
            /* padding: 10px; */
            text-align: center;
        }

        th {
            padding: 10px;
            background-color: #f2f2f2;
            color: #343a40;
        }

        .highlight {
            background-color: #f8f9fa;
        }

        .special {
            background-color: #f0f0f0;
        }

        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        /* Formulário estruturado com flexbox */
        .form-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            gap: 10px;
        }

        .campo {
            flex: 1;
            min-width: 150px;
        }

        .campo label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Ajusta a largura e altura para ocupar o TD inteiro */
        #selectgroup-item {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0; /* Remove qualquer padding extra */
            margin: 0;
        }

        /* Oculta a checkbox padrão */
        #electgroup-input {
            display: none;
        }

        /* Estiliza o botão (span) para ocupar todo o espaço do TD */
        .selectgroup-button {
            display: block;
            width: 100%;
            height: 100%;
            text-align: center;
            background-color: white; /* Cor padrão */
            border: 1px solid #ccc;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
            box-sizing: border-box; /* Garante que o padding não afete o tamanho total */
        }

        /* Muda a cor quando a checkbox está selecionada */
        #selectgroup-input:checked + .selectgroup-button {
            background-color: blue;
            color: white;
            font-weight: bold;
        }

        select {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px;
            font-size: 16px;
            color: #343a40;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .campo {
                flex: 0 0 100%;
            }
        }
    </style>
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
                                <a class="nav-link" id="registro-presenca-tab" data-bs-toggle="pill" href="#registro-presenca" role="tab" aria-controls="registro-presenca" aria-selected="false">Calendário de presença</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="recibo-tab" data-bs-toggle="pill" href="#recibo" role="tab" aria-controls="recibo" aria-selected="false">Recibo</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="editar-prof" role="tabpanel" aria-labelledby="editar-prof-tab">
                                <form action="professorInserir?idProf=<?php echo $idProfessor ?>&op=edit" method="POST" onsubmit="return verificarPasswords()">
                                    <div
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
                                    </div>
                                    <div class="page-inner">
                                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                                            <div>
                                                <h2 class="fw-bold mb-3">Ficha do professor</h2>
                                            </div>
                                        </div>
                                        <div class="container2">
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 99.5%;">
                                                        <label>NOME:</label>
                                                        <input type="text" name="nome" value="<?php echo $rowProfessor['nome']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 64%;">
                                                        <label>EMAIL:</label>
                                                        <input type="email" name="email" value="<?php echo $rowProfessor['email']; ?>">
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 34%;">
                                                        <label>CONTACTO:</label>
                                                        <input type="tel" id="contacto" name="contacto" value="<?php echo $rowProfessor['contacto']; ?>">
                                                        <input type="hidden" name="contacto" id="contactoHidden">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 31%;">
                                                        <label>PASSWORD:</label>
                                                        <input type="text" name="password" id="password">
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 31%;">
                                                        <label>CONFIRMAR PASSWORD:</label>
                                                        <input type="text" name="passwordConfirm" id="passwordConfirm">
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 34%;">
                                                        <label>ESTADO:</label>
                                                        <select name="estado" class="select-box">
                                                            <option <?php if ($rowProfessor['ativo'] == 1) { echo "selected"; }?> value="Ativo">Ativo</option>
                                                            <option <?php if ($rowProfessor['ativo'] == 0) { echo "selected"; }?> value="Desativado">Desativado</option>
                                                        </select>
                                                    </div>
                                                </div>     
                                            </div>

                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 64%;">
                                                        <label>DISPONIBILIDADE:</label>
                                                        <button
                                                            type="button"
                                                            class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addRowModal"
                                                        >
                                                            <!-- <i class="fa fa-up-right-from-square"> -->
                                                            DISPONIBILIDADE
                                                        </button>
                                                    </div>
                                                </div>     
                                            </div>

                                            <!-- Disciplinas -->
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 100%;">
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
                                            </div>

                                            <!-- Ensino -->
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 100%;">
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
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar alterações</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="registro-presenca" role="tabpanel" aria-labelledby="registro-presenca-tab">
                                <div class="ui container">
                                    <div class="ui grid">
                                        <div class="ui sixteen column">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Carregar scripts na ordem correta -->
                            <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
                            <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
                            <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/pt.js'></script>

                            <script>
                                $(document).ready(function() {
                                    const urlParams = new URLSearchParams(window.location.search);
                                    const idProf = urlParams.get('idProf');

                                    const presenca = [];
                                    const results = [];

                                    $.ajax({
                                        url: 'json.obterPresencaProf.php?idProf='+idProf,
                                        type: 'POST',
                                        success: function(data) {
                                            results.push(...data);
                                            var eventos = [];
                                            if (results.length > 0) {
                                                results.forEach((result) => {
                                                    eventos.push({
                                                        title: result.nomeAluno + ' | ' + result.nomeDisc,
                                                        start: result.dia + 'T' + result.horaInicio,
                                                        end: result.dia + 'T' + result.horaFim,
                                                    });
                                                });
                                            }
                                            $('#calendar').fullCalendar({
                                                header: {
                                                    left: 'prev,next today',
                                                    center: 'title',
                                                    right: 'month,agendaWeek,agendaDay'
                                                },
                                                locale: 'pt', // Definir idioma para português
                                                firstDay: 1, // Iniciar a semana na segunda-feira
                                                hiddenDays: [0], // Esconder os domingos
                                                navLinks: true,
                                                editable: false,
                                                eventStartEditable: false,
                                                eventDurationEditable: false, 
                                                eventLimit: true,
                                                slotLabelFormat: 'HH:mm',
                                                events: eventos,
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            $('#calendar').fullCalendar({
                                                header: {
                                                    left: 'prev,next today',
                                                    center: 'title',
                                                    right: 'month,agendaWeek,agendaDay'
                                                },
                                                locale: 'pt',
                                                firstDay: 1,
                                                hiddenDays: [0],
                                                navLinks: true,
                                                editable: false,
                                                eventStartEditable: false,
                                                eventDurationEditable: false, 
                                                eventLimit: true,
                                                slotLabelFormat: 'HH:mm',
                                            });
                                        }
                                    });
                                    $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                                        if (e.target.getAttribute('aria-controls') === 'registro-presenca') {
                                            $('#calendar').fullCalendar('render'); // Ou 'updateSize'
                                        }
                                    });
                                });
                            </script>
                            <div class="tab-pane fade" id="recibo" role="tabpanel" aria-labelledby="recibo-tab">
                                <form action="" method="GET">
                                    <div class="select-container">
                                        <input type="hidden" style="display: none;" name="idProf" value="<?= $idProfessor ?>">
                                        <input type="hidden" style="display: none;" name="tab" value="1">
                                        <label for="mes" class="select-label">Mês:</label>
                                        <select name="mes" id="mes" onchange="this.form.submit()">
                                            <option value="<?php echo date("n")."-".date("Y"); ?>" selected><?php echo date("n")."-".date("Y"); ?></option>
                                            <?php
                                                $sql = "SELECT DISTINCT mes, ano FROM professores_recibo WHERE idProfessor = " . $idProfessor . " ORDER BY ano DESC, mes DESC;";
                                                $result = $con->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value=" . $row['mes'] . "-" . $row['ano'] . " " . ($row['mes'] == $mes && $row['ano'] == $ano ? 'selected' : '') . ">" . $row['mes'] . "-" . $row['ano'] ."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </form>
                                <div class="page-inner">
                                    <div class="container2">
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 98%;">
                                                    <label>NOME:</label>
                                                    <input type="text" name="nome" readonly value="<?php echo $rowProfessor['nome']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS 1º CICLO:</label>
                                                    <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas1Ciclo;} else {echo $rowRecibo['horasDadas1Ciclo'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR UNITÁRIO:</label>
                                                    <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[0];} else {echo $rowRecibo['valorUnitario1Ciclo'];} ?>€" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR PARCIAL:</label>
                                                    <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial1Ciclo;} else {echo $rowRecibo['valorParcial1Ciclo'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS 2º CICLO:</label>
                                                    <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas2Ciclo;} else {echo $rowRecibo['horasDadas2Ciclo'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR UNITÁRIO:</label>
                                                    <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[1];} else {echo $rowRecibo['valorUnitario2Ciclo'];} ?>€" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR PARCIAL:</label>
                                                    <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial2Ciclo;} else {echo $rowRecibo['valorParcial2Ciclo'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS 3º CICLO:</label>
                                                    <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas3Ciclo;} else {echo $rowRecibo['horasDadas3Ciclo'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR UNITÁRIO:</label>
                                                    <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[2];} else {echo $rowRecibo['valorUnitario3Ciclo'];} ?>€" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR PARCIAL:</label>
                                                    <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial3Ciclo;} else {echo $rowRecibo['valorParcial3Ciclo'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS SECUNDÁRIO:</label>
                                                    <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadasSecundario;} else {echo $rowRecibo['horasDadasSecundario'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR UNITÁRIO:</label>
                                                    <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[3];} else {echo $rowRecibo['valorUnitarioSecundario'];} ?>€" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR PARCIAL:</label>
                                                    <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcialSecundario;} else {echo $rowRecibo['valorParcialSecundario'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS UNIVERSIDADE:</label>
                                                    <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadasUniversidade;} else {echo $rowRecibo['horasDadasUniversidade'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR UNITÁRIO:</label>
                                                    <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[4];} else {echo $rowRecibo['valorUnitarioUniversidade'];} ?>€" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>VALOR PARCIAL:</label>
                                                    <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcialUniversidade;} else {echo $rowRecibo['valorParcialUniversidade'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>TOTAL:</label>
                                                    <input type="input" name="total" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $total;} else {echo $rowRecibo['total'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <button type="submit" class="btn btn-primary">Registrar hora</button> -->
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

                function verificarPasswords() {
                    const password = document.getElementById("password").value;
                    const confirm = document.getElementById("passwordConfirm").value;

                    if (password === confirm) {
                        return true;
                    } else {
                        $.notify({
                            message: 'As palavras passes não coincidem!',
                            title: 'Notificação',
                            icon: 'fa fa-info-circle',
                        }, {
                            type: 'danger',
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            delay: 2000
                        });

                        return false;
                    }
                }
            </script>
        </div>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

