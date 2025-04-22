<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;

    //Obtem o id do admin via GET
    $idAluno = $_GET['idAluno'];

    $tab = isset($_GET['tab']) ? $_GET['tab'] : '0';

    if ($tab == "pagamento") {
        $estouEm = 5;
    }

    $stmt = $con->prepare("SELECT * FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $idAluno);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowAluno = $result->fetch_assoc();
        if (isset($row['transporte']) && $row['transporte'] == 1) {
            $transporte = "checked";
        }
        else {
            $transporte = "";
        }
    } else {
        notificacao('warning', 'ID do aluno inválido.');
        header('Location: aluno.php');
        exit();
    }

    $horasRealizadasGrupo = 0;
    $horasRealizadasIndividual = 0;
    $mensalidade = 0;

    if (isset($_GET['mes'])) {
        $partes = explode("-", $_GET['mes']);
        $mes = $partes[0];
        $ano = $partes[1];
    }
    else {
        $mes = date("m");
        $ano = date("Y");
    }

    if (isset($_GET['data'])) {
        $data = $_GET['data'];
    }
    else {
        $mesAnterior = date('m') - 1;
        $anoAtual = date('Y');
        
        if ($mesAnterior == 0) {
            $mesAnterior = 12;
            $anoAtual -= 1;
        }

        // Garante que $mesAnterior tem dois dígitos
        $mesAnterior = str_pad($mesAnterior, 2, "0", STR_PAD_LEFT);
        
        $data = $mesAnterior . "-" . $anoAtual;
    }

    //Valores pagamento transporte
    $sql = "SELECT * FROM valores_pagamento WHERE id = 7;";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $valorTransporte = $row["valor"];
    }

    //Valores pagamento
    $sql = "SELECT * FROM valores_pagamento WHERE id = 9;";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $valorInscricao = $row["valor"];
    }

    if(!empty($rowAluno['dataInscricao'])){
        $mesInscricao = date('Y-m', strtotime($rowAluno['dataInscricao']));
        if ($mesInscricao == date('Y-m')) {
            $mensalidade = $mensalidade + $valorInscricao;
        }
    }

    //Pagamento
    $sql = "SELECT *, alunos_pagamentos.id as idPagamento FROM alunos_pagamentos LEFT JOIN metodos_pagamento as m ON idMetodo = m.id WHERE idAluno = $idAluno AND DATE_FORMAT(created, '%m-%Y') = '$data';";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $rowPagamento = $result->fetch_assoc();
    }

    if ($mes == date("n") && $ano == date("Y")) {
        //Horas Grupo
        $sql = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . $idAluno . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 0";
        $result = $con->query($sql);
        if ($result->num_rows >= 0) {
            $row = $result->fetch_assoc();
            $horasRealizadasGrupo = $row['horasRealizadas'];
            //Balanço Grupo
            $horasBalancoGrupo = $rowAluno['balancoGrupo'] + ($rowAluno['horasGrupo'] - $horasRealizadasGrupo);
        }

        //Horas Individuais
        $sql = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . $idAluno . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 1";
        $result = $con->query($sql);
        if ($result->num_rows >= 0) {
            $row = $result->fetch_assoc();
            $horasRealizadasIndividual = $row['horasRealizadas'];
            //Balanço Individual
            $horasBalancoIndividual = $rowAluno['balancoIndividual'] + ($rowAluno['horasIndividual'] - $horasRealizadasIndividual);
        }

        //Valor do transporte
        $result5 = $con->prepare('SELECT transporte FROM alunos WHERE id = ?');
        $result5->bind_param("i", $idAluno);
        $result5->execute();
        $result5 = $result5->get_result();
        if ($result5->num_rows > 0) {
            $row5 = $result5->fetch_assoc();
            if ($row5['transporte'] == 1) {
                $mensalidade = $mensalidade + $valorTransporte;
            }
        }

        //Mensalidade Grupo
        $result = $con->prepare('SELECT mensalidadeHorasGrupo FROM mensalidade INNER JOIN alunos ON alunos.idMensalidadeGrupo  = mensalidade.id WHERE alunos.id = ?');
        $result->bind_param("i", $idAluno);
        $result->execute();
        $result = $result->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mensalidade = $mensalidade + $row['mensalidadeHorasGrupo'];
        }

        //Mensalidade Individual
        $result = $con->prepare('SELECT mensalidadeHorasIndividual FROM mensalidade INNER JOIN alunos ON alunos.idMensalidadeIndividual = mensalidade.id WHERE alunos.id = ?');
        $result->bind_param("i", $idAluno);
        $result->execute(); 
        $result = $result->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $mensalidade = $mensalidade + $row['mensalidadeHorasIndividual'];
        }
    }
    else {
        $sql = "SELECT * FROM alunos_recibo WHERE idAluno = $idAluno AND ano = $ano AND mes = $mes";
        $result = $con->query($sql);
        //Se houver um aluno com o id recebido, guarda as informações
        if ($result->num_rows >= 0) {
            $rowRecibo = $result->fetch_assoc();
        }
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
                case 'presenca':
                    abaDesejada = '#registro-presenca-tab';
                    break;
                case 'pagamento':
                    abaDesejada = '#pagamento-tab';
                    break;
                default:
                    abaDesejada = '#editar-aluno-tab'; // Aba padrão
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
                                <a class="nav-link active" id="editar-aluno-tab" data-bs-toggle="pill" href="#editar-aluno" role="tab" aria-controls="editar-aluno" aria-selected="true">Ficha do Aluno</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="registro-presenca-tab" data-bs-toggle="pill" href="#registro-presenca" role="tab" aria-controls="registro-presenca" aria-selected="false">Calendário de presença</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="recibo-tab" data-bs-toggle="pill" href="#recibo" role="tab" aria-controls="recibo" aria-selected="false">Recibo</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="pagamento-tab" data-bs-toggle="pill" href="#pagamento" role="tab" aria-controls="pagamento" aria-selected="false">Pagamento</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="editar-aluno" role="tabpanel" aria-labelledby="editar-aluno-tab">
                                <form action="alunoInserir.php?idAluno=<?php echo $idAluno ?>&op=edit" method="POST">
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
                                                        <input type="text" name="contacto" value="<?php echo $rowAluno['contacto']; ?>">
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
                            <div class="tab-pane fade" id="registro-presenca" role="tabpanel" aria-labelledby="registro-presenca-tab">
                                <div class="ui container">
                                    <div class="ui grid">
                                        <div class="ui sixteen column">
                                            <div id="calendar"></div>
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
                                            url: 'json.obterPresencaAluno.php?idAluno='+idAluno,
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

                                        $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                                            if (e.target.getAttribute('aria-controls') === 'registro-presenca') {
                                                $('#calendar').fullCalendar('render'); // Ou 'updateSize'
                                            }
                                        });
                                    });
                                </script>
                            </div>
                            <div class="tab-pane fade" id="recibo" role="tabpanel" aria-labelledby="recibo-tab">
                                <form action="" method="GET">
                                    <div class="select-container">
                                        <input type="hidden" style="display: none;" name="idAluno" value="<?= $idAluno ?>">
                                        <input type="hidden" style="display: none;" name="tab" value="recibo">
                                        <label for="mes" class="select-label">Mês:</label>
                                        <select name="mes" id="mes" onchange="this.form.submit()">
                                        <option value="<?php echo date("n")."-".date("Y"); ?>" selected><?php echo date("n")."-".date("Y"); ?></option>
                                            <?php
                                                $sql = "SELECT DISTINCT mes, ano FROM alunos_recibo WHERE idAluno = " . $idAluno . " ORDER BY ano DESC, mes DESC;";
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
                                                <div class="campo" style="flex: 0 0 78%;">
                                                    <label>NOME:</label>
                                                    <input type="text" name="nome" readonly value="<?php echo $rowAluno['nome']; ?>">
                                                </div>
                                                <div class="campo" style="flex: 0 0 20%;">
                                                    <label>Ano:</label>
                                                    <input type="text" name="pack" id="pack" readonly value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $rowAluno['ano'];} else {echo $rowRecibo['anoAluno'];} ?>º">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS EM GRUPO:</label>
                                                    <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $rowAluno['horasGrupo'];} else {echo $rowRecibo['packGrupo'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>HORAS REALIZADAS:</label>
                                                    <input type="input" name="horasRealizadas" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasRealizadasGrupo;} else {echo $rowRecibo['horasRealizadasGrupo'];} ?>" readonly>
                                                </div>
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>BALANÇO HORAS:</label>
                                                    <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasBalancoGrupo;} else {echo $rowRecibo['horasBalancoGrupo'];} ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($rowAluno['horasIndividual'] > 0) { ?>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS INDIVIDUAIS:</label>
                                                        <input type="input" name="horasIndividuais" value="<?php echo $rowAluno['horasIndividual']; ?>" readonly >
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS REALIZADAS:</label>
                                                        <input type="input" name="horasRealizadas" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasRealizadasIndividual;} else {echo $rowRecibo['horasRealizadasIndividual'];} ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>BALANÇO HORAS:</label>
                                                        <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasBalancoIndividual;} else {echo $rowRecibo['horasBalancoIndividual'];} ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="form-section">
                                            <div class="form-row">
                                                <div class="campo" style="flex: 0 0 32%;">
                                                    <label>MENSALIDADE:</label>
                                                    <input type="input" name="mensalidade" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $mensalidade;} else {echo $rowRecibo['mensalidade'];} ?>€" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <button type="submit" class="btn btn-primary">Registrar hora</button> -->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pagamento" role="tabpanel" aria-labelledby="pagamento-tab">
                                <form action="" method="GET">
                                    <div class="select-container">
                                        <input type="hidden" style="display: none;" name="idAluno" value="<?= $idAluno ?>">
                                        <input type="hidden" style="display: none;" name="tab" value="pagamento">
                                        <label for="data" class="select-label">Data:</label>
                                        <select name="data" id="mes" onchange="this.form.submit()">
                                            <?php
                                                $sql = "SELECT DISTINCT DATE_FORMAT(created, '%m-%Y') AS data_formatada, 
                                                                MONTH(created) AS mes, 
                                                                YEAR(created) AS ano 
                                                        FROM alunos_pagamentos 
                                                        WHERE idAluno = " . $idAluno . " 
                                                        ORDER BY created DESC;";
                                                $result = $con->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo $row['data_formatada'];
                                                        echo $data;
                                                        echo "<option value=" . $row['data_formatada'] . " " . ($row['data_formatada'] == $data ? 'selected' : '') . " >" . $row['data_formatada'] ."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </form>
                                <form action="pagamentoInserir.php?id=<?php echo $rowPagamento['idPagamento'] ?>&op=save" method="POST">
                                    <div class="page-inner">
                                        <div class="container2">
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 56%;">
                                                        <label>NOME:</label>
                                                        <input type="text" name="nome" readonly value="<?php echo $rowAluno['nome']; ?>">
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 19%;">
                                                        <label>ESTADO:</label>
                                                        <input type="input" name="estado" value="<?php echo $rowPagamento['estado']; ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 19%;">
                                                        <label>MENSALIDADE:</label>
                                                        <input type="text" name="mensalidade" readonly value="<?php echo $mensalidade; ?>€">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 49%;">
                                                        <label>MÉTODO:</label>
                                                        <?php
                                                            if ($rowPagamento['estado'] == "Pago") { ?>
                                                                <input type="input" name="observacao" value="<?php echo $rowPagamento['estado']; ?>" readonly>
                                                        <?php } else { ?>
                                                            <select name="metodo" class="select-box">
                                                                <option selected value="1">Dinheiro</option>
                                                                <option value="2">MBWay</option>
                                                            </select>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 49%;">
                                                        <label>OBSERVAÇÃO:</label>
                                                        <input type="input" name="observacao" value="<?php if ($rowPagamento['estado'] == "Pago") {echo $rowPagamento['observacao'];} ?>" <?php if ($rowPagamento['estado'] == "Pago") { echo "readonly"; } ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($rowPagamento['estado'] != "Pago") { ?>
                                                <button type="submit" class="btn btn-primary">Registrar pagamento</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
            </script>
        </div>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

