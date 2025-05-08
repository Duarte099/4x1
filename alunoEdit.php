<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;

    $idAluno = $_GET['idAluno'];
    $mesSelecionado = $_GET['mes'] ?? date('Y-m');
    $tab = isset($_GET['tab']) ? $_GET['tab'] : '0';
    $recibo = true;
    $valorCoima = 0;
    $botao = true;


    if ($tab == "recibo") {
        $estouEm = 2;
    }

    $stmt = $con->prepare("SELECT *, a.ano as anoAluno, a.transporte as tAluno FROM alunos as a LEFT JOIN alunos_recibo as ar ON ar.idAluno = a.id WHERE a.id = ?");
    $stmt->bind_param("i", $idAluno);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowAluno = $result->fetch_assoc();
        if (isset($rowAluno['tAluno']) && $rowAluno['tAluno'] == 1) {
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

    list($ano, $mes) = explode('-', $mesSelecionado);

    if ($mes == date("n") && $ano == date("Y")) {
        $botao = false;
        $mensalidade = 0;
        $rowRecibo['mensalidadeGrupo'] = 0;
        $rowRecibo['mensalidadeIndividual'] = 0;
        $rowRecibo['horasRealizadasGrupo'] = 0;
        $rowRecibo['horasRealizadasIndividual'] = 0;
        $rowRecibo['transporte'] = 0;
        $rowRecibo['inscricao'] = 0;
        $rowRecibo['anoAluno'] = $rowAluno['anoAluno'];
        $rowRecibo['packGrupo'] = $rowAluno['horasGrupo'];
        $rowRecibo['estado'] = "Pendente";
        $rowRecibo['coima'] = 0;
        $totalMinutos = 0;

        //Horas Grupo
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND idAluno = $idAluno AND individual = 0;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalMinutos = $totalMinutos + $row["duracao"];
            }
            $rowRecibo['horasRealizadasGrupo'] = minutosToValor($totalMinutos);
        }
        $rowRecibo['horasBalancoGrupo'] = $rowAluno['balancoGrupo'] + ($rowAluno['horasGrupo'] - $rowRecibo['horasRealizadasGrupo']);

        //Horas Individuais
        $sql = "SELECT duracao
                FROM alunos_presenca AS p
                INNER JOIN alunos AS a ON a.id = p.idAluno
                WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND idAluno = $idAluno AND individual = 1;";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalMinutos = $totalMinutos + $row["duracao"];
            }
            $rowRecibo['horasRealizadasIndividual'] = minutosToValor($totalMinutos);
        }
        $rowRecibo['horasBalancoIndividual'] = $rowAluno['balancoIndividual'] + ($rowAluno['horasIndividual'] - $rowRecibo['horasRealizadasIndividual']);

        //Valores pagamento transporte
        $sql = "SELECT * FROM valores_pagamento WHERE id = 7;";
        $result = $con->query($sql);
        //Se houver um aluno com o id recebido, guarda as informações
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $valorTransporte = $row["valor"];
        }

        //Valores pagamento inscrição
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
                $rowRecibo['inscricao'] = $valorInscricao;
            }
        }

        if ($rowAluno['transporte'] == 1) {
            $mensalidade = $mensalidade + $valorTransporte;
            $rowRecibo['transporte'] = $valorTransporte;
        }

        //Mensalidades grupo e individual
        if ($rowAluno['horasGrupo'] > 0) {
            $result6 = $con->prepare('SELECT mensalidadeHorasGrupo FROM mensalidade WHERE ano = ? AND horasGrupo = ?');
            $result6->bind_param('ii', $rowAluno['ano'], $rowAluno['horasGrupo']);
            $result6->execute();
            $result6 = $result6->get_result();
            if ($result6->num_rows > 0) {
                $row6 = $result6->fetch_assoc();
                $rowRecibo['mensalidadeGrupo'] = $row6['mensalidadeHorasGrupo'];
            }
        }
        if ($rowAluno['horasIndividual'] > 0) {
            $result6 = $con->prepare('SELECT mensalidadeHorasIndividual FROM mensalidade WHERE ano = ? AND horasIndividual = ?');
            $result6->bind_param('ii', $rowAluno['ano'], $rowAluno['horasIndividual']);
            $result6->execute();
            $result6 = $result6->get_result(); 
            if ($result6->num_rows > 0) {
                $row6 = $result6->fetch_assoc();
                $rowRecibo['mensalidadeIndividual'] = $row6['mensalidadeHorasIndividual'];
            }
        }
        $mensalidade = $rowRecibo['mensalidadeGrupo'] + $rowRecibo['mensalidadeIndividual'] + $rowRecibo['inscricao'] + $rowRecibo['transporte'];
    }
    else {
        $sql = "SELECT * FROM alunos_recibo as a INNER JOIN metodos_pagamento as m ON a.idMetodo = m.id WHERE idAluno = $idAluno AND ano = $ano AND mes = $mes";
        $result = $con->query($sql);
        //Se houver um aluno com o id recebido, guarda as informações
        if ($result->num_rows > 0) {
            $rowRecibo = $result->fetch_assoc();
            $mensalidade = $rowRecibo['mensalidadeGrupo'] + $rowRecibo['mensalidadeIndividual'] + $rowRecibo['inscricao'] + $rowRecibo['transporte'] + $rowRecibo['coima'];
        }
        else {
            $recibo = false;
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

        table {
        width: 100%;
        margin-bottom: 20px;
        }

        table td, table th {
        padding: 8px;
        font-size: 15px;
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
                                <a class="nav-link" id="recibo-tab" data-bs-toggle="pill" href="#recibo" role="tab" aria-controls="recibo" aria-selected="false">Recibo</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="editar-aluno" role="tabpanel" aria-labelledby="editar-aluno-tab">
                                <form action="alunoInserir.php?idAluno=<?php echo $idAluno ?>&op=edit" method="POST" id="formEdit" class="formEdit">
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
                                                        <input type="tel" id="contacto" name="contacto" value="<?php echo $rowAluno['contacto']; ?>">
                                                        <input type="hidden" name="contacto" id="contactoHidden">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 78%;">
                                                        <label>ESCOLA:</label>
                                                        <input type="text" name="escola" value="<?php echo $rowAluno['escola']; ?>">
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 20%;">
                                                        <label>ANO:</label>
                                                        <input type="number" name="ano" value="<?php echo $rowAluno['anoAluno']; ?>">
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
                                                        <input type="tel" id="maeTlm" name="maeTlm" value="<?php echo $rowAluno['tlmMae']; ?>">
                                                        <input type="hidden" name="maeTlm" id="maeTlmHidden">
                                                    </div>     
                                                </div>

                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 49%;">
                                                        <label>PAI:</label>
                                                        <input type="text" name="pai" value="<?php echo $rowAluno['nomePai']; ?>">
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 49%;">
                                                        <label>Tlm:</label>
                                                        <input type="tel" id="paiTlm" name="paiTlm" value="<?php echo $rowAluno['tlmPai']; ?>">
                                                        <input type="hidden" name="paiTlm" id="paiTlmHidden">
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
                            <div class="tab-pane fade" id="recibo" role="tabpanel" aria-labelledby="recibo-tab">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <form action="" method="GET" class="d-flex align-items-center">
                                        <input type="hidden" name="idAluno" value="<?= $idAluno ?>">
                                        <input type="hidden" name="tab" value="recibo">

                                        <label for="mes" class="form-label mb-0 me-2">Data:</label>
                                        <input type="month" name="mes" id="mes" value="<?= $mesSelecionado ?>" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="page-inner">
                                    <form action="pagamentoInserir.php?idAluno=<?php echo $idAluno ?>&ano=<?php echo $ano ?>&mes=<?php echo $mes ?>&op=save" method="POST" id="formEdit" class="formEdit">
                                        <div class="container2">
                                            <?php if ($recibo == true): ?>
                                                <div class="form-section">
                                                    <div class="form-section">
                                                        <div class="form-row">
                                                            <div class="campo" style="flex: 0 0 56%;">
                                                                <label>NOME:</label>
                                                                <input type="text" name="nome" readonly value="<?php echo $rowAluno['nome']; ?>">
                                                            </div>
                                                            <div class="campo" style="flex: 0 0 20%;">
                                                                <label>Ano:</label>
                                                                <input type="text" name="pack" id="pack" readonly value="<?php echo $rowRecibo['anoAluno']; ?>º">
                                                            </div>
                                                            <div class="campo" style="flex: 0 0 20%;">
                                                                <label>ESTADO:</label>
                                                                <input type="input" name="estado" value="<?php echo $rowRecibo['estado']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($botao == true) { ?>
                                                        <div class="form-section">
                                                            <div class="form-row">
                                                                <div class="campo" style="flex: 0 0 49%;">
                                                                    <label>MÉTODO:</label>
                                                                    <?php if ($rowRecibo['estado'] == "Pago") { ?>
                                                                        <input type="input" name="metodo" value="<?php echo $rowRecibo['metodo']; ?>" readonly>
                                                                    <?php } else { ?>
                                                                        <select name="metodo" class="select-box">
                                                                            <option selected value="1">Dinheiro</option>
                                                                            <option value="2">MBWay</option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="campo" style="flex: 0 0 49%;">
                                                                    <label>OBSERVAÇÃO:</label>
                                                                    <input type="input" name="observacao" value="<?php if ($rowRecibo['estado'] == "Pago") {echo $rowRecibo['observacao'];} ?>" <?php if ($rowRecibo['estado'] == "Pago") { echo "readonly"; } ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- Tabela de Horas de Grupo -->
                                                    <?php if ($rowAluno['horasGrupo'] > 0): ?>
                                                        <table>
                                                            <tr>
                                                            <th rowspan="2">Horas Grupo</th>
                                                            <td rowspan="2" style="width: 100px;"><?= $rowRecibo['packGrupo'] ?></td>
                                                            <th colspan="2">HORAS CONTABILIZADAS</th>
                                                            <th rowspan="2">Mensalidade<br><strong><?= $rowRecibo['mensalidadeGrupo'] ?>€</strong></th>
                                                            </tr>
                                                            <tr>
                                                            <td>Horas Realizadas<br><strong><?= $rowRecibo['horasRealizadasGrupo'] ?></strong></td>
                                                            <td>Balanço Das Horas<br><strong><?= $rowRecibo['horasBalancoGrupo'] ?></strong></td>
                                                            </tr>
                                                        </table>
                                                    <?php endif; ?>

                                                    <!-- Espaço entre tabelas -->
                                                    <div style="height: 20px;"></div>

                                                    <!-- Tabela de Horas Individuais -->
                                                    <?php if ($rowAluno['horasIndividual'] > 0): ?>
                                                        <table>
                                                            <tr>
                                                            <th rowspan="2">Horas Individuais</th>
                                                            <td rowspan="2" style="width: 100px;"><?= $rowAluno['horasIndividual'] ?> <br> Horas</td>
                                                            <th colspan="2">HORAS CONTABILIZADAS</th>
                                                            <th rowspan="2">Mensalidade<br><strong><?= $rowRecibo['mensalidadeIndividual'] ?>€</strong></th>
                                                            </tr>
                                                            <tr>
                                                            <td>Horas Realizadas<br><strong><?= $rowRecibo['horasRealizadasIndividual'] ?></strong></td>
                                                            <td>Balanço Das Horas<br><strong><?= $rowRecibo['horasBalancoIndividual'] ?></strong></td>
                                                            </tr>
                                                        </table>
                                                    <?php endif; ?>

                                                    <!-- Extras + Total -->
                                                    <table style="margin-top: 20px;">
                                                        <?php if($rowRecibo['transporte'] > 0): ?>
                                                            <tr>
                                                                <td colspan="4" style="text-align: right; font-weight: bold;">Transporte:</td>
                                                                <td style="text-align: center;"><?= $rowRecibo['transporte'] ?>€</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php if($rowRecibo['inscricao'] > 0): ?>
                                                            <tr>
                                                                <td colspan="4" style="text-align: right; font-weight: bold;">Inscrição:</td>
                                                                <td style="text-align: center;"><?= $rowRecibo['inscricao'] ?>€</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php if($rowRecibo['coima'] > 0): ?>
                                                            <tr>
                                                                <td colspan="4" style="text-align: right; font-weight: bold;">Coima:</td>
                                                                <td style="text-align: center;"><?= $rowRecibo['coima'] ?>€</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <tr style="background-color: #e9ecef;">
                                                            <td colspan="4" style="text-align: right; font-weight: bold;">Total:</td>
                                                            <td style="text-align: center; font-weight: bold;">
                                                            <?= $mensalidade ?>€
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <?php if($rowRecibo['estado'] != "Pago" && $botao == true) { ?>
                                                        <button type="submit" class="btn btn-primary">Registrar pagamento</button>
                                                    <?php } ?>
                                                </div>
                                            <?php else: ?>
                                                <p>Sem recibo nesta data.</p>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
            <script>
                const input1 = document.querySelector("#contacto");
                const hiddenInput1 = document.querySelector("#contactoHidden");
                const iti1 = window.intlTelInput(input1, {
                    initialCountry: "pt",
                    preferredCountries: ["pt", "br", "fr", "gb"],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });
                const input2 = document.querySelector("#maeTlm");
                const hiddenInput2 = document.querySelector("#maeTlmHidden");
                const iti2 = window.intlTelInput(input2, {
                    initialCountry: "pt",
                    preferredCountries: ["pt", "br", "fr", "gb"],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });
                const input3 = document.querySelector("#paiTlm");
                const hiddenInput3 = document.querySelector("#paiTlmHidden");
                const iti3 = window.intlTelInput(input3, {
                    initialCountry: "pt",
                    preferredCountries: ["pt", "br", "fr", "gb"],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });

                // Ao submeter o formulário, atualiza o campo hidden
                document.querySelector("#formEdit").addEventListener("submit", function () {
                    hiddenInput1.value = iti1.getNumber();
                    hiddenInput2.value = iti2.getNumber();
                    hiddenInput3.value = iti3.getNumber();
                });

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

