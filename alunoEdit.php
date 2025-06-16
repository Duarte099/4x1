<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;

    $idAluno = $_GET['idAluno'];
    $tab = isset($_GET['tab']) ? $_GET['tab'] : '0';
    $botao = true;

    $stmt = $con->prepare("SELECT *, a.ano as anoAluno, a.transporte as tAluno FROM alunos as a WHERE a.id = ?");
    $stmt->bind_param("i", $idAluno);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowAluno = $result->fetch_assoc();
    } else {
        notificacao('warning', 'ID do aluno inválido.');
        header('Location: aluno.php');
        exit();
    }

    if (isset($_GET['idRecibo'])) {
        $idRecibo = $_GET['idRecibo'];
        $stmt1 = $con->prepare("SELECT ar.id, ar.idAluno, ar.anoAluno, ar.packGrupo, ar.horasRealizadasGrupo, ar.horasBalancoGrupo, ar.mensalidadeGrupo, ar.packIndividual, ar.horasRealizadasIndividual, ar.horasBalancoIndividual, ar.mensalidadeIndividual, ar.transporte, ar.inscricao, ar.pago, ar.verificado, ar.notificacao, ar.notificadoEm, ar.ano, ar.mes, m.metodo FROM alunos_recibo as ar LEFT JOIN metodos_pagamento as m ON ar.idMetodo = m.id WHERE ar.id = ?");
        $stmt1->bind_param("i", $idRecibo);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        if ($result1->num_rows > 0) {
            $rowRecibo = $result1->fetch_assoc();
            $mensalidade = $rowRecibo['mensalidadeGrupo'] + $rowRecibo['mensalidadeIndividual'] + $rowRecibo['inscricao'] + $rowRecibo['transporte'];
            //Se tiver verificado
            if ($rowRecibo['verificado'] == 1) {
                if ($rowRecibo['notificacao'] == 1) {
                    $data_limite = (new DateTime($rowRecibo['notificadoEm']))->modify('+7 days');
                    $data_hoje = new DateTime();
                    if ($rowRecibo['pago'] == 1) {
                        $rowRecibo['estado'] = "Pago";
                        $corPagamento = "2ecc71";
                        $botao = false;
                    }
                    elseif ($data_hoje > $data_limite) {
                        $rowRecibo['estado'] = "Em atraso";
                        $corPagamento = "ff0000";
                    }
                    else {
                        $rowRecibo['estado'] = "Pendente";
                        $corPagamento = "f1c40f";
                    }
                }
                else {
                    $rowRecibo['estado'] = "À espera de ser notificado";
                    $corPagamento = "007BFF";
                    $botao = false;
                }
            }
            //Se não tiver verificado
            else {
                $rowRecibo['estado'] = "À espera de verificação";
                $corPagamento = "007BFF";
                $botao = false;
            }
        }
        else {
            notificacao('warning', 'ID do recibo inválido.');
            header('Location: aluno.php');
            exit();
        }
    }
?>
    <title>4x1 | Editar Aluno</title>
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
                case 'explicacoes':
                    abaDesejada = '#explicacoes-tab';
                    break;
                case 'recibo':
                    abaDesejada = '#recibo-tab';
                    break;
                case 'pagamento':
                    abaDesejada = '#pagamento-tab';
                    break;
                case 'editRecibo':
                    abaDesejada = '#editRecibo-tab';
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
                                <a class="nav-link" id="explicacoes-tab" data-bs-toggle="pill" href="#explicacoes" role="tab" aria-controls="explicacoes" aria-selected="false">Explicações</a>
                            </li>
                            <li class="nav-item1">
                                <a class="nav-link" id="recibos-tab" data-bs-toggle="pill" href="#recibos" role="tab" aria-controls="recibos" aria-selected="false">Recibos</a>
                            </li>
                            <?php if (isset($_GET['tab']) && $_GET['tab'] == "pagamento") { ?>
                                <li class="nav-item1">
                                    <a class="nav-link" id="pagamento-tab" data-bs-toggle="pill" href="#pagamento" role="tab" aria-controls="pagamento" aria-selected="false">Pagamento</a>
                                </li>
                            <?php } ?>
                            <?php if (isset($_GET['tab']) && $_GET['tab'] == "editRecibo" && $_SESSION['tipo'] == "administrador") { ?>
                                <li class="nav-item1">
                                    <a class="nav-link" id="editRecibo-tab" data-bs-toggle="pill" href="#editRecibo" role="tab" aria-controls="editRecibo" aria-selected="false">Editar recibo</a>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="editar-aluno" role="tabpanel" aria-labelledby="editar-aluno-tab">
                                <div class="col-12 col-md-10 col-lg-8 mx-auto">
                                    <form action="alunoInserir.php?idAluno=<?php echo $idAluno ?>&op=edit" method="POST" id="formEdit" class="formEdit">
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
                                        </div> -->
                                        <div class="page-inner">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="nome" class="form-label">Nome:</label>
                                                    <input type="text" class="form-control" name="nome" value="<?php echo $rowAluno['nome']; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="morada" class="form-label">Morada:</label>
                                                    <input type="text" class="form-control" name="morada" id="email" value="<?php echo $rowAluno['morada']; ?>" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="localidade" class="form-label">Localidade:</label>
                                                    <input type="text" class="form-control" name="localidade" value="<?php echo $rowAluno['localidade']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="codigoPostal" class="form-label">Còdigo postal:</label>
                                                    <input type="input" class="form-control" name="codigoPostal" value="<?php echo $rowAluno['codigoPostal']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="NIF" class="form-label">NIF:</label>
                                                    <input type="number" class="form-control" name="NIF" min="0" max="999999999" value="<?php echo $rowAluno['nif']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="dataNascimento" class="form-label">Data nascimento:</label>
                                                    <input type="date" class="form-control" name="dataNascimento" value="<?php echo $rowAluno['dataNascimento']; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="email" class="form-label">Email:</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo $rowAluno['email']; ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="contacto" class="form-label">Contacto:</label>
                                                    <input type="tel" id="contacto" class="form-control" name="contacto" value="<?php echo $rowAluno['contacto']; ?>">
                                                    <input type="hidden" name="contacto" id="contactoHidden">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="escola" class="form-label">Escola:</label>
                                                    <input type="text" class="form-control" name="escola" value="<?php echo $rowAluno['escola']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="ano" class="form-label">
                                                        Ano: 
                                                        <span 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="right" 
                                                            title="Universidade: 0"
                                                            style="cursor: pointer; color: #0d6efd;"
                                                        >
                                                            ?
                                                        </span>
                                                    </label>
                                                    <input type="number" class="form-control" name="ano" min="0" max="12" value="<?php echo $rowAluno['anoAluno']; ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="curso" class="form-label">Curso:</label>
                                                    <input type="curso" class="form-control" name="curso" value="<?php echo $rowAluno['curso']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="turma" class="form-label">Turma:</label>
                                                    <input type="text" class="form-control" name="turma" value="<?php echo $rowAluno['turma']; ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="modalidade" class="form-label">Modalidade:</label>
                                                    <input type="text" class="form-control" name="modalidade" value="<?php echo $rowAluno['modalidade']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="ano" class="form-label">Horas grupo:</label>
                                                    <input type="number" class="form-control" name="horasGrupo" min="0" value="<?php echo $rowAluno['horasGrupo']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="horasIndividual" class="form-label">Horas individuais:</label>
                                                    <input type="number" class="form-control" name="horasIndividual" min="0" value="<?php echo $rowAluno['horasIndividual']; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="transporte" class="form-label">Transporte:</label>
                                                    <select class="form-control" name="transporte" >
                                                        <option value='1' <?php if ($rowAluno['tAluno'] == 1) { echo "selected"; }?>>Sim</option>
                                                        <option value='0' <?php if ($rowAluno['tAluno'] == 0) { echo "selected"; }?>>Não</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label" for="estado">Estado:</label>
                                                    <select class="form-control" name="estado">
                                                        <option value='1' <?php if ($rowAluno['ativo'] == 1) { echo "selected"; }?>>Ativo</option>
                                                        <option value='0' <?php if ($rowAluno['ativo'] == 0) { echo "selected"; }?>>Inativo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="mae" class="form-label">Mãe:</label>
                                                    <input type="text" class="form-control" name="mae" value="<?php echo $rowAluno['nomeMae']; ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="maeTlm" class="form-label">Contacto mãe:</label>
                                                    <input type="tel" class="form-control" id="maeTlm" name="maeTlm" value="<?php echo $rowAluno['tlmMae']; ?>">
                                                    <input type="hidden" name="maeTlm" id="maeTlmHidden">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="pai" class="form-label">Pai:</label>
                                                    <input type="text" class="form-control" name="pai" value="<?php echo $rowAluno['nomePai']; ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="paiTlm" class="form-label">Contacto pai:</label>
                                                    <input type="tlm" class="form-control" id="paiTlm" name="paiTlm" value="<?php echo $rowAluno['tlmPai']; ?>">
                                                    <input type="hidden" name="paiTlm" id="paiTlmHidden">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
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
                                                    id="tabela-aluno-explicacoes"
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
                                                            $sql = "SELECT p.nome as nomeProfessor, d.nome as nomeDisciplina, individual, duracao, DATE_FORMAT(dia, '%d-%m-%Y') as dia FROM alunos_presenca as ap INNER JOIN alunos as a ON ap.idAluno = a.id INNER JOIN professores as p ON ap.idProfessor = p.id INNER JOIN disciplinas as d ON ap.idDisciplina = d.id WHERE a.id = $idAluno;";
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
                            <div class="tab-pane fade" id="recibos" role="tabpanel" aria-labelledby="recibos-tab">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table
                                                    id="tabela-aluno-recibos"
                                                    class="display table table-striped table-hover"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>Horas grupo</th>
                                                            <th>Horas realizadas grupo</th>
                                                            <th>Horas individuais</th>
                                                            <th>Horas realizadas individual</th>
                                                            <th>Estado verificação</th>
                                                            <th>Estado notificação</th>
                                                            <th>Estado pagamento</th>
                                                            <th>Método pagamento</th>
                                                            <th>Total</th>
                                                            <th>Mes</th>
                                                            <th>Ano</th>
                                                            <th>Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Horas grupo</th>
                                                            <th>Horas realizadas grupo</th>
                                                            <th>Horas individuais</th>
                                                            <th>Horas realizadas individual</th>
                                                            <th>Estado verificação</th>
                                                            <th>Estado notificação</th>
                                                            <th>Estado pagamento</th>
                                                            <th>Método pagamento</th>
                                                            <th>Total</th>
                                                            <th>Mes</th>
                                                            <th>Ano</th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <?php
                                                            if ($_SESSION['tipo'] == "professor") {
                                                                $sql = "SELECT ar.id, ar.idAluno, ar.packGrupo, ar.horasRealizadasGrupo, ar.horasBalancoGrupo, ar.mensalidadeGrupo, ar.packIndividual, ar.horasRealizadasIndividual, ar.horasBalancoIndividual, ar.mensalidadeIndividual, ar.transporte, ar.inscricao, ar.pago, ar.verificado, ar.notificacao, ar.notificadoEm, ar.ano, ar.mes, m.metodo FROM alunos_recibo as ar INNER JOIN alunos as a ON ar.idAluno = a.id LEFT JOIN metodos_pagamento as m ON ar.idMetodo = m.id WHERE a.id = $idAluno AND ar.pago = 0;";
                                                            }
                                                            else {
                                                                $sql = "SELECT ar.id, ar.idAluno, ar.packGrupo, ar.horasRealizadasGrupo, ar.horasBalancoGrupo, ar.mensalidadeGrupo, ar.packIndividual, ar.horasRealizadasIndividual, ar.horasBalancoIndividual, ar.mensalidadeIndividual, ar.transporte, ar.inscricao, ar.pago, ar.verificado, ar.notificacao, ar.notificadoEm, ar.ano, ar.mes, m.metodo FROM alunos_recibo as ar INNER JOIN alunos as a ON ar.idAluno = a.id LEFT JOIN metodos_pagamento as m ON ar.idMetodo = m.id WHERE a.id = $idAluno;";
                                                            }
                                                            $result = $con->query($sql);
                                                            if ($result->num_rows > 0) {
                                                                while ($row = $result->fetch_assoc()) {
                                                                    if ($row['verificado'] == 1) {
                                                                        $row['verificado'] = "Verificado";
                                                                        $corVerificacao = "2ecc71";
                                                                        if ($row['notificacao'] == 1) {
                                                                            $row['notificacao'] = "Notificado";
                                                                            $corNotificacao = "2ecc71";
                                                                            $data_limite = (new DateTime($row['notificadoEm']))->modify('+7 days');
                                                                            $data_hoje = new DateTime();
                                                                            if ($row['pago'] == 1) {
                                                                                $row['pago'] = "Pago";
                                                                                $corPagamento = "2ecc71";
                                                                            }
                                                                            elseif ($data_hoje > $data_limite) {
                                                                                $row['pago'] = "Em atraso";
                                                                                $corPagamento = "ff0000";
                                                                            }
                                                                            else {
                                                                                $row['pago'] = "Pendente";
                                                                                $corPagamento = "f1c40f";
                                                                            }
                                                                        }
                                                                        else {
                                                                            $row['notificacao'] = "Pendente";
                                                                            $corNotificacao = "f1c40f";
                                                                            $row['pago'] = "À espera de ser notificado";
                                                                            $corPagamento = "007BFF";
                                                                        }
                                                                    }
                                                                    //Se não tiver verificado
                                                                    else {
                                                                        $row['verificado'] = "Pendente";
                                                                        $corVerificacao = "f1c40f";
                                                                        $row['notificacao'] = "À espera de verificação";
                                                                        $corNotificacao = "007BFF";
                                                                        $row['pago'] = "À espera de verificação";
                                                                        $corPagamento = "007BFF";
                                                                    }?>
                                                                    <tr>
                                                                        <td><?php echo $row['packGrupo'] ?></td>
                                                                        <td><?php echo $row['horasRealizadasGrupo'] ?></td>
                                                                        <td><?php echo $row['packIndividual'] ?></td>
                                                                        <td><?php echo $row['horasRealizadasIndividual'] ?></td>
                                                                        <td style="color: #<?php echo $corVerificacao; ?>"><?php echo $row['verificado'] ?></td>
                                                                        <td style="color: #<?php echo $corNotificacao; ?>"><?php echo $row['notificacao'] ?></td>
                                                                        <td style="color: #<?php echo $corPagamento; ?>"><?php echo $row['pago'] ?></td>
                                                                        <td><?php echo $row['metodo'] ?></td>
                                                                        <td><?php echo $row['mensalidadeGrupo'] + $row['mensalidadeIndividual'] + $row['inscricao'] + $row['transporte'] ?>€</td>
                                                                        <td><?php echo $row['mes'] ?></td>
                                                                        <td><?php echo $row['ano'] ?></td>
                                                                        <td style="padding: 0px 0px !important;">
                                                                            <div class="form-button-action">
                                                                                <?php if ($row['verificado'] == "Pendente") { ?>
                                                                                    <a
                                                                                        href="recibosAlunosVerificar.php?idRecibo=<?php echo $row['id']; ?>"
                                                                                        class="btn btn-link btn-primary btn-lg"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-placement="top"
                                                                                        title="Verificar recibo"
                                                                                    >
                                                                                        <i class="fa fa-check"></i>
                                                                                    </a>
                                                                                <?php } 
                                                                                if ($row["pago"] != "Pago" && $row["notificacao"] == "Notificado") { ?>
                                                                                    <a
                                                                                        href="pagamentoInserir.php?idRecibo=<?php echo $row['id']; ?>"
                                                                                        class="btn btn-link btn-primary btn-lg"
                                                                                        data-bs-toggle="tooltip"
                                                                                        data-bs-placement="top"
                                                                                        title="Registrar pagamento"
                                                                                    >
                                                                                        <i class="fa-credit-card"></i>
                                                                                    </a>
                                                                                <?php }?>
                                                                                <a
                                                                                    href="alunoEdit.php?idAluno=<?php echo $row['idAluno']; ?>&idRecibo=<?php echo $row['id']; ?>&tab=editRecibo"
                                                                                    class="btn btn-link btn-primary btn-lg"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Editar recibo"
                                                                                >
                                                                                    <i class="fa fa-edit"></i>
                                                                                </a>
                                                                                <a
                                                                                    href="reciboImpressao.php?idRecibo=<?php echo $row['id']; ?>"
                                                                                    class="btn btn-link btn-primary btn-lg"
                                                                                    data-bs-toggle="tooltip"
                                                                                    data-bs-placement="top"
                                                                                    title="Imprimir recibo"
                                                                                >
                                                                                    <i class="fa-solid fa-print"></i>
                                                                                </a>
                                                                            </div>
                                                                        </td>   
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
                            <?php if ($_SESSION['tipo'] == "administrador") { ?> 
                                <div class="tab-pane fade" id="editRecibo" role="tabpanel" aria-labelledby="editRecibo-tab">
                                    <div class="page-inner">
                                        <form action="pagamentoInserir.php?idAluno=<?php echo $idAluno ?>&ano=<?php echo $ano ?>&mes=<?php echo $mes ?>&op=save" method="POST" id="formEdit" class="formEdit">
                                            <div class="container2">
                                                <div class="form-section">
                                                    <div class="form-section">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="nome" class="form-label">Nome:</label>
                                                                <input type="text" class="form-control" name="nome" value="<?php echo $rowAluno['nome']; ?>" disabled>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="morada" class="form-label">Ano:</label>
                                                                <input type="text" class="form-control" name="ano" value="<?php echo $rowRecibo['anoAluno']; ?>º" disabled>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="data" class="form-label">Data:</label>
                                                                <input type="text" class="form-control" name="data" value="<?php echo $rowRecibo['mes']; ?>-<?php echo $rowRecibo['ano']; ?>" disabled>
                                                            </div>
                                                        </div>
                                                        <?php if ($rowRecibo['packGrupo'] > 0 || $rowRecibo['horasRealizadasGrupo'] > 0) {?>
                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="horasGrupo" class="form-label">Horas grupo:</label>
                                                                    <input type="text" class="form-control" name="horasGrupo" value="<?php echo $rowRecibo['packGrupo']; ?>" disabled>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="horasRealizadasGrupo" class="form-label">Horas realizadas:</label>
                                                                    <input type="text" class="form-control" name="horasRealizadasGrupo" value="<?php echo $rowRecibo['horasRealizadasGrupo']; ?>">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="horasBalancoGrupo" class="form-label">Horas balanço:</label>
                                                                    <input type="text" class="form-control" name="horasBalancoGrupo" value="<?php echo $rowRecibo['horasBalancoGrupo']; ?>">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="mensalidadeGrupo" class="form-label">Mensalidade:</label>
                                                                    <input type="text" class="form-control" name="mensalidadeGrupo" value="<?php echo $rowRecibo['mensalidadeGrupo']; ?>">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($rowRecibo['packIndividual'] > 0 || $rowRecibo['horasRealizadasIndividual'] > 0) {?>
                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="horasIndividual" class="form-label">Horas individual:</label>
                                                                    <input type="text" class="form-control" name="horasIndividual" value="<?php echo $rowRecibo['packIndividual']; ?>" disabled>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="horasRealizadasIndividual" class="form-label">Horas realizadas:</label>
                                                                    <input type="text" class="form-control" name="horasRealizadasIndividual" value="<?php echo $rowRecibo['horasRealizadasIndividual']; ?>">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="horasBalancoIndividual" class="form-label">Horas balanço:</label>
                                                                    <input type="text" class="form-control" name="horasBalancoIndividual" value="<?php echo $rowRecibo['horasBalancoIndividual']; ?>">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="mensalidadeIndividual" class="form-label">Mensalidade:</label>
                                                                    <input type="text" class="form-control" name="mensalidadeIndividual" value="<?php echo $rowRecibo['mensalidadeIndividual']; ?>">
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if ($rowRecibo['packIndividual'] > 0 || $rowRecibo['packGrupo'] > 0) {?>
                                                            <div class="row mb-3">
                                                                <div class="col-md-3">
                                                                    <label for="mensalidade" class="form-label">Total:</label>
                                                                    <input type="text" class="form-control" name="mensalidade" value="<?php echo $mensalidade; ?>" disabled>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100 mt-3">Guardar alterações</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
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
            <script>
                $("#tabela-aluno-explicacoes").DataTable({
                    pageLength: 6,
                    order: [[1, 'asc']],
                    language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
                    },
                    initComplete: function () {
                        this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on("change", function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column
                                .search(val ? "^" + val + "$" : "", true, false)
                                .draw();
                            });

                            column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append(
                                    '<option value="' + d + '">' + d + "</option>"
                                );
                            });
                        });
                    },
                });

                $("#tabela-aluno-recibos").DataTable({
                    pageLength: 6,
                    language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-PT.json"
                    },
                    initComplete: function () {
                        this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            var select = $(
                                '<select class="form-select"><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on("change", function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column
                                .search(val ? "^" + val + "$" : "", true, false)
                                .draw();
                            });

                            column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append(
                                    '<option value="' + d + '">' + d + "</option>"
                                );
                            });
                        });
                    },
                });
            </script>
        </div>
        <?php 
            include('./endPage.php');
        ?>
    </body>
</html>

