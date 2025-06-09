<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;

    $idAluno = $_GET['idAluno'];
    $mesSelecionado = $_GET['mes'] ?? date('Y-m');
    $tab = isset($_GET['tab']) ? $_GET['tab'] : '0';
    $recibo = true;
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
            $result6->bind_param('ii', $rowAluno['anoAluno'], $rowAluno['horasGrupo']);
            $result6->execute();
            $result6 = $result6->get_result();
            if ($result6->num_rows > 0) {
                $row6 = $result6->fetch_assoc();
                $rowRecibo['mensalidadeGrupo'] = $row6['mensalidadeHorasGrupo'];
            }
        }
        if ($rowAluno['horasIndividual'] > 0) {
            $result6 = $con->prepare('SELECT mensalidadeHorasIndividual FROM mensalidade WHERE ano = ? AND horasIndividual = ?');
            $result6->bind_param('ii', $rowAluno['anoAluno'], $rowAluno['horasIndividual']);
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
        $sql = "SELECT * FROM alunos_recibo as a LEFT JOIN metodos_pagamento as m ON a.idMetodo = m.id WHERE idAluno = $idAluno AND ano = $ano AND mes = $mes";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            $rowRecibo = $result->fetch_assoc();
            $mensalidade = $rowRecibo['mensalidadeGrupo'] + $rowRecibo['mensalidadeIndividual'] + $rowRecibo['inscricao'] + $rowRecibo['transporte'];
            //Se tiver verificado
            if ($rowRecibo['verificado'] == 1) {
                if ($rowRecibo['notificacao'] == 1) {
                    $data_limite = (new DateTime($rowRecibo['notificadoEm']))->modify('+7 days');
                    $data_hoje = new DateTime();
                    if ($rowRecibo['estado'] == 1) {
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
            $recibo = false;
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
                case 'recibo':
                    abaDesejada = '#recibo-tab';
                    break;
                case 'explicacoes':
                    abaDesejada = '#explicacoes-tab';
                    break;
                case 'presenca':
                    abaDesejada = '#registro-presenca-tab';
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
                                <a class="nav-link" id="recibo-tab" data-bs-toggle="pill" href="#recibo" role="tab" aria-controls="recibo" aria-selected="false">Recibo</a>
                            </li>
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
                                                    id="tabela-alunos-explicacoes"
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
                            <div class="tab-pane fade" id="recibo" role="tabpanel" aria-labelledby="recibo-tab">
                                <?php
                                    $stmt = $con->prepare("SELECT ar.mes, ar.ano, a.id FROM alunos as a INNER JOIN alunos_recibo as ar ON ar.idAluno = a.id WHERE a.id = $idAluno AND CURDATE() > DATE_ADD(data_registo, INTERVAL 7 DAY)");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        while($row5 = $result->fetch_assoc()){?>
                                            <div class="alert alert-danger d-flex align-items-center">
                                                <i class="bi bi-cake2-fill me-2"></i>
                                                <div>
                                                    O pagamento relativo ao mês <?php echo $row5['mes']; ?> de <?php echo $row5['ano']; ?> está em atraso. <a href="alunoEdit.php?idAluno=<?php echo $idAluno ?>&mes=<?php echo $row5['ano']?>-<?php echo $row5['mes']?>&tab=recibo">Ver mais</a>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                ?>
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
                                                            <label for="estado" class="form-label">Estado:</label>
                                                            <input type="text" class="form-control" name="estado" value="<?php echo $rowRecibo['estado']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <?php if ($botao == true) { ?>
                                                        <div class="form-section">
                                                            <div class="row mb-3">
                                                                <div class="col-md-4">
                                                                    <label for="metodo" class="form-label">Método:</label>
                                                                    <?php if ($rowRecibo['estado'] == "Pago") { ?>
                                                                        <input type="text" class="form-control" name="metodo" value="<?php echo $rowRecibo['metodo']; ?>" disabled>
                                                                    <?php } else { ?>
                                                                        <select name="metodo" class="form-control">
                                                                            <option selected value="1">Dinheiro</option>
                                                                            <option value="2">MBWay</option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label for="observacao" class="form-label">Observação:</label>
                                                                    <input type="text" class="form-control" name="observacao" value="<?php if ($rowRecibo['estado'] == "Pago") {echo $rowRecibo['observacao'];} ?>" <?php if ($rowRecibo['estado'] == "Pago") { echo "readonly"; } ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if ($_SESSION["tipo"] == "administrador") {?>
                                                            <div class="form-section">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-3">
                                                                        <label for="horasGrupo" class="form-label">Horas grupo:</label>
                                                                        <input type="text" class="form-control" name="horasGrupo" value="<?php echo $rowRecibo['horasGrupo']; ?>" disabled>
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
                                                                <div class="row mb-3">
                                                                    <div class="col-md-3">
                                                                        <label for="horasIndividual" class="form-label">Horas individual:</label>
                                                                        <input type="text" class="form-control" name="horasIndividual" value="<?php echo $rowRecibo['horasIndividual']; ?>" disabled>
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
                                                                <div class="row mb-3">
                                                                    <div class="col-md-3">
                                                                        <label for="mensalidade" class="form-label">Total:</label>
                                                                        <input type="text" class="form-control" name="mensalidade" value="<?php echo $mensalidade; ?>" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }?>
                                                    <?php } if ($_SESSION["tipo"] == "professor") {?>
                                                        <!-- Tabela de Horas de Grupo -->
                                                        <?php if ($rowAluno['horasGrupo'] > 0): ?>
                                                            <div class="table-responsive mb-4">
                                                                <table class="table table-bordered text-center align-middle">
                                                                    <tr class="table-secondary">
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
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Tabela de Horas Individuais -->
                                                        <?php if ($rowAluno['horasIndividual'] > 0): ?>
                                                            <div class="table-responsive mb-4">
                                                                <table class="table table-bordered text-center align-middle">
                                                                    <tr class="table-secondary">
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
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Tabela de Extras + Total -->
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered text-center align-middle">
                                                                <?php if($rowRecibo['transporte'] > 0): ?>
                                                                    <tr>
                                                                        <td colspan="4" class="text-end fw-bold">Transporte:</td>
                                                                        <td><?= $rowRecibo['transporte'] ?>€</td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                                <?php if($rowRecibo['inscricao'] > 0): ?>
                                                                    <tr>
                                                                        <td colspan="4" class="text-end fw-bold">Inscrição:</td>
                                                                        <td><?= $rowRecibo['inscricao'] ?>€</td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                                <tr class="table-light fw-bold">
                                                                    <td colspan="4" class="text-end">Total:</td>
                                                                    <td><?= $mensalidade ?>€</td>
                                                                </tr>
                                                            </table>
                                                        </div>

                                                        <?php if($botao == true): ?>
                                                            <button type="submit" class="btn btn-primary w-100 mt-3">Registrar pagamento</button>
                                                        <?php endif; ?>
                                                        <?php if($_SESSION['tipo'] == "administrador"): ?>
                                                            <button type="submit" class="btn btn-primary w-100 mt-3">Guardar alterações</button>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php 
                                                    }
                                                ?>
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
                $("#tabela-alunos-explicacoes").DataTable({
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
            </script>
        </div>
        
    </body>
    </html>

