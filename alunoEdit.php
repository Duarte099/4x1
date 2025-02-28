<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;
    $estouEm2 = 3;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if (adminPermissions($con, "adm_001", "view") == 0) {
        header('Location: dashboard');
        exit();
    }

    //Obtem o id do admin via GET
    $idAluno = $_GET['idAluno'];

    $tab = isset($_GET['tab']) ? $_GET['tab'] : '0';

    //Obtem todas as informações do aluno que está a ser editado
    $sql = "SELECT * FROM alunos WHERE alunos.id = '$idAluno'";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $rowAluno = $result->fetch_assoc();
        if (isset($row['transporte']) && $row['transporte'] == 1) {
            $transporte = "checked";
        }
        else {
            $transporte = "";
        }
    }
    //Caso contrário volta para a dashboard para não dar erro
    else{
        header('Location: dashboard');
        exit();
    }

    $mesAtual = isset($_GET['mes']) ? $_GET['mes'] : date("m-Y");

    //HORAS EM GRUPO
    $sql = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = $idAluno AND DATE_FORMAT(dia, '%m-%Y') = '$mesAtual'";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows >= 0) {
        $row = $result->fetch_assoc();
        $horasRealizadas = $row['horasRealizadas'];
    }
    else{
        $horasRealizadas = 0;
    }
?>
    <title>4x1 | Editar Aluno</title>
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
    <script>
        $(window).on('load', function() {
            setTimeout(function() {
                $('#calendar').fullCalendar('render');
            }, 500); // Tempo suficiente para o carregamento da página
        });
    </script>
</head>
    <body>
        <div class="wrapper">
            <?php
                include('./sideBar.php'); 
            ?>
            <div class="container">
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'editarAluno')" id="defaultOpen">Ficha do Aluno</button>
                    <button class="tablinks" onclick="openTab(event, 'registroPresenca')" id="defaultOpen">Calendário de presença</button>
                    <button class="tablinks" onclick="openTab(event, 'recibo')" id="defaultOpen">Recibo</button>
                </div>
                <div id="editarAluno" class="tabcontent">
                    <form action="alunoInserir?idAluno=<?php echo $idAluno ?>&op=edit" method="POST">
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
                                                    $sql = "SELECT disponibilidade FROM alunos_disponibilidade WHERE idAluno = ? AND dia = ? AND hora = ?";
                                                    $result = $con->prepare($sql);
                                                    $result->bind_param('iss', $idAluno, $dia, $hora);
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
                            <div class="container2">
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 99.5%;">
                                            <label>NOME:</label>
                                            <input type="text" name="nome" value="<?php echo $rowAluno['nome']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 64%;">
                                            <label>MORADA:</label>
                                            <input type="text" name="morada" value="<?php echo $rowAluno['morada']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 34%;">
                                            <label>LOCALIDADE:</label>
                                            <input type="text" name="localidade" value="<?php echo $rowAluno['localidade']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 31%;">
                                            <label>CÓDIGO POSTAL:</label>
                                            <input type="text" name="codigoPostal" value="<?php echo $rowAluno['codigoPostal']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 32%;">
                                            <label>NIF:</label>
                                            <input type="number" name="NIF" value="<?php echo $rowAluno['nif']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 34%;">
                                            <label>DATA DE NASCIMENTO:</label>
                                            <input type="date" name="dataNascimento" value="<?php echo $rowAluno['dataNascimento']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 64%;">
                                            <label>EMAIL:</label>
                                            <input type="email" name="email" value="<?php echo $rowAluno['email']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 34%;">
                                            <label>CONTACTO:</label>
                                            <input type="number" name="contacto" value="<?php echo $rowAluno['contacto']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 78%;">
                                            <label>ESCOLA:</label>
                                            <input type="text" name="escola" value="<?php echo $rowAluno['escola']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 20%;">
                                            <label>ANO:</label>
                                            <input type="number" name="ano" value="<?php echo $rowAluno['ano']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 78%;">
                                            <label>CURSO:</label>
                                            <input type="text" name="curso" value="<?php echo $rowAluno['curso']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 20%;">
                                            <label>TURMA:</label>
                                            <input type="text" name="turma" value="<?php echo $rowAluno['turma']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 23%;">
                                            <label>DISPONIBILIDADE:</label>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addRowModal"
                                                style="padding: 3px;"
                                            >
                                                <!-- <i class="fa fa-up-right-from-square"> -->
                                                DISPONIBILIDADE
                                            </button>
                                        </div>
                                        <div class="campo" style="flex: 0 0 23%;">
                                            <label>HORAS GRUPO:</label>
                                            <input type="number" name="horasGrupo" min="0" value="<?php echo $rowAluno['horasGrupo']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 23%;">
                                            <label>HORAS INDIVIDUAL:</label>
                                            <input type="number" name="horasIndividual" min="0" value="<?php echo $rowAluno['horasIndividual']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 10%;">
                                            <label>TRANSPORTE:</label>
                                            <input class="form-check-input" type="checkbox" name="transporte" style="width: 25px; height: 25px; padding: 5px; border: 1px solid #ccc;" id="flexCheckDefault" <?php echo $transporte; ?>>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seção de pais -->
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 49%;">
                                            <label>MÃE:</label>
                                            <input type="text" name="mae" value="<?php echo $rowAluno['nomeMae']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 49%;">
                                            <label>Tlm:</label>
                                            <input type="number" name="maeTlm" value="<?php echo $rowAluno['tlmMae']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 49%;">
                                            <label>PAI:</label>
                                            <input type="text" name="pai" value="<?php echo $rowAluno['nomePai']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 49%;">
                                            <label>Tlm:</label>
                                            <input type="number" name="paiTlm" value="<?php echo $rowAluno['tlmPai']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Modalidade e Disciplinas -->
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 78%;">
                                            <label>MODALIDADE:</label>
                                            <input type="text" name="modalidade" value="<?php echo $rowAluno['modalidade']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 20%;">
                                            <label>ESTADO:</label>
                                            <select name="estado" class="select-box">
                                                <option <?php if ($rowAluno['ativo'] == 1) { echo "selected"; }?> value="Ativo">Ativo</option>
                                                <option <?php if ($rowAluno['ativo'] == 0) { echo "selected"; }?> value="Desativado">Desativado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 100%;">
                                            <label>DISCIPLINAS:</label>
                                            <div class="selectgroup selectgroup-pills">
                                                <?php 
                                                    $sql = "SELECT id, nome FROM disciplinas;";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $sql = "SELECT * FROM alunos_disciplinas WHERE idAluno = ? AND idDisciplina = ?";
                                                            $result1 = $con->prepare($sql);
                                                            $result1->bind_param('ii', $idAluno, $row['id']);
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
                                <button type="submit" class="btn btn-primary">Guardar alterações</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="registroPresenca" class="tabcontent">
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
                        const idAluno = urlParams.get('idAluno');

                        const presenca = [];
                        const results = [];

                        $.ajax({
                            url: 'json.obterPresenca.php?idAluno='+idAluno,
                            type: 'POST',
                            success: function(data) {
                                results.push(...data);
                                var eventos = [];
                                if (results.length > 0) {
                                    results.forEach((result) => {
                                        eventos.push({
                                            title: result.nomeDisc + ' | ' + result.nomeProf,
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
                    });
                </script>
                <div id="recibo" class="tabcontent">
                    <form action="" method="GET">
                        <div class="select-container">
                            <input type="hidden" style="display: none;" name="idAluno" value="<?= $idAluno ?>">
                            <input type="hidden" style="display: none;" name="tab" value="1">
                            <label for="mes" class="select-label">Mês:</label>
                            <select name="mes" id="mes" onchange="this.form.submit()">
                                <?php
                                    $sql = "SELECT DISTINCT DATE_FORMAT(dia, '%m-%Y') AS mes FROM alunos_presenca WHERE idAluno = $idAluno ORDER BY dia DESC;";
                                    $result = $con->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value=\"{$row['mes']}\" " . ($row['mes'] == $mesAtual ? 'selected' : '') . ">{$row['mes']}</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            const urlParams = new URLSearchParams(window.location.search);
                            if (urlParams.has('tab')) {
                                openTab(event, 'recibo');
                            }
                        });
                    </script>
                    <form action="alunoInserir?idAluno=<?php echo $idAluno ?>&op=edit" method="POST">
                        <div class="page-inner">
                            <div class="container2">
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 78%;">
                                            <label>NOME:</label>
                                            <input type="text" name="nome" list="datalistNomes" readonly value="<?php echo $rowAluno['nome']; ?>">
                                        </div>
                                        <div class="campo" style="flex: 0 0 20%;">
                                            <label>Ano:</label>
                                            <input type="text" name="pack" id="pack" readonly value="<?php echo $rowAluno['ano'] . "º"; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="campo" style="flex: 0 0 32%;">
                                            <label>HORAS EM GRUPO:</label>
                                            <input type="input" name="horasGrupo" value="<?php echo $rowAluno['horasGrupo']; ?>" readonly>
                                        </div>
                                        <div class="campo" style="flex: 0 0 32%;">
                                            <label>HORAS REALIZADAS:</label>
                                            <input type="input" name="horasRealizadas" value="<?php echo $horasRealizadas; ?>" readonly>
                                        </div>
                                        <div class="campo" style="flex: 0 0 32%;">
                                            <label>BALANÇO HORAS:</label>
                                            <input type="input" name="horasBalanco" value="<?php ; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($rowAluno['horasIndividual'] == 1) { ?>
                                    <div class="form-section">
                                        <div class="form-row">
                                            <div class="campo" style="flex: 0 0 32%;">
                                                <label>HORAS INDIVIDUAIS:</label>
                                                <input type="input" name="horasIndividuais" value="<?php echo $rowAluno['horasIndividual']; ?>" readonly >
                                            </div>
                                            <div class="campo" style="flex: 0 0 32%;">
                                                <label>HORAS REALIZADAS:</label>
                                                <input type="input" name="horasRealizadas" value="<?php ; ?>" readonly>
                                            </div>
                                            <div class="campo" style="flex: 0 0 32%;">
                                                <label>BALANÇO HORAS:</label>
                                                <input type="input" name="horasBalanco" value="<?php ; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <button type="submit" class="btn btn-primary">Registrar hora</button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                function atualizarCampos(input) {
                    // O valor do input é o ID do aluno, conforme definido nas opções do datalist
                    var partes = input.value.split(" | ");
                    var alunoId = partes[0];
                    
                    // Se um valor for selecionado (não vazio)
                    if(alunoId !== "") {
                    // Realiza a requisição AJAX para obter os dados do aluno
                    $.ajax({
                        url: 'json.obterNome.php',
                        type: 'GET',
                        data: { idAluno: alunoId},
                        success: function(response) {
                        var data = JSON.parse(response);
                        if (data == "erro") {
                            document.getElementById("pack").value = "";
                            document.getElementById("disciplinasContainer").style.display = "none";
                        }
                        else{
                            document.getElementById("pack").value = data.pack + " horas";
                            if (data.ciclo == 1) {
                            document.getElementById("disciplinasContainer").style.display = "none";
                            } else {
                            document.getElementById("disciplinasContainer").style.display = "block";
                            }
                        }
                        },
                        error: function() {
                        console.error('Erro ao buscar o nome.');
                        }
                    });
                    } else {
                    // Se não houver um ID válido, limpa o campo Pack
                    document.getElementById("pack").value = "";
                    document.getElementById("disciplinasContainer").style.display = "none";
                    }
                }

                function openTab(evt, tabName) {
                    var i, tabcontent, tablinks;
                    const urlParams = new URLSearchParams(window.location.search);
                    
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    
                    document.getElementById(tabName).style.display = "block";
                    if (!urlParams.has('tab')) {
                        evt.currentTarget.className += " active";
                    }
                }
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.has('tab')) {
                    document.getElementById("defaultOpen").click();
                }
            </script>
        </div>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

