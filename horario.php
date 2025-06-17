<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 9;

    $disabled = "";
    $sql = "SELECT COUNT(*) AS alunos FROM alunos WHERE estado = 1 AND notHorario = 1";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['alunos'] == 1) {
            $alunos = $row['alunos'] . " aluno";
        }
        else {
            $alunos = $row['alunos'] . " alunos";
        }
        $numAlunos = $row['alunos'];
    }

    $sql = "SELECT COUNT(*) AS professores FROM professores WHERE estado = 1 AND notHorario = 1";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['professores'] == 1) {
            $professores = " e " . $row['professores'] . " professor";
        }
        else {
            $professores = " e " . $row['professores'] . " professores";
        }
        $numProfessores = $row['professores'];
    }

    if ($numAlunos + $numProfessores == 0) {
        $disabled = "disabled";
    }

    if ($_SESSION['tipo'] == "administrador") {
        $readonly = "";
    }
    else if ($_SESSION['tipo'] == "professor"){
        $readonly = "readonly";
    }
?>
  <title>4x1 | Horário</title>
  <style>
    .professor {
        text-align: center;
        font-weight: bold;
        font-size: 0.95rem;
        margin-bottom: 5px;
        color: #2c3e50;
        border-bottom: 1px solid #ccc;
        padding-bottom: 2px;
    }

    .alunos {
        list-style-type: disc;
        padding-left: 15px;
        margin: 0;
        font-size: 1rem;
        color: #555;
    }

    .alunos li {
        line-height: 1.2em;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th {
        padding:5px 5px!important 
    }

    .modal {
        --bs-modal-width:600px!important;
    }
  </style>
</head>
  <body>
    <div class="wrapper">
      <?php  
        include('./sideBar.php'); 
      ?>
        <div class="container">
            <div class="modal fade" id="modalProgresso" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-center">
                        <div class="modal-header">
                            <h5 class="modal-title">A enviar notificações...</h5>
                        </div>
                        <div class="modal-body">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-inner">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                    <li class="nav-item1">
                                        <a class="nav-link active" id="segunda-tab" data-bs-toggle="pill" href="#segunda" role="tab" aria-controls="segunda" aria-selected="true">Segunda</a>
                                    </li>
                                    <li class="nav-item1">
                                        <a class="nav-link" id="terca-tab" data-bs-toggle="pill" href="#terca" role="tab" aria-controls="terca" aria-selected="false">Terça</a>
                                    </li>
                                    <li class="nav-item1">
                                        <a class="nav-link" id="quarta-tab" data-bs-toggle="pill" href="#quarta" role="tab" aria-controls="quarta" aria-selected="false">Quarta</a>
                                    </li>
                                    <li class="nav-item1">
                                        <a class="nav-link" id="quinta-tab" data-bs-toggle="pill" href="#quinta" role="tab" aria-controls="quinta" aria-selected="false">Quinta</a>
                                    </li>
                                    <li class="nav-item1">
                                        <a class="nav-link" id="sexta-tab" data-bs-toggle="pill" href="#sexta" role="tab" aria-controls="sexta" aria-selected="false">Sexta</a>
                                    </li>
                                    <li class="nav-item1">
                                        <a class="nav-link" id="sabado-tab" data-bs-toggle="pill" href="#sabado" role="tab" aria-controls="sabado" aria-selected="false">Sábado</a>
                                    </li>
                                </ul>
                                <?php 
                                    if ($_SESSION['tipo'] == "administrador") { ?>
                                        <button type="button" class="btn btn-primary" id="meuBotao" onclick="enviarNotificacoes()" <?php echo $disabled; ?> >
                                            Notificar <?php echo $alunos; ?> <?php echo $professores; ?>.
                                        </button>
                                    <?php }
                                ?>
                            </div>
                            <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                <?php 
                                    $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta'];
                                    foreach ($dias as $dia) { 
                                        $aux = "";
                                        if ($dia == "segunda") {
                                            $aux = " show active";
                                        }?>
                                        <div class="tab-pane fade<?php echo $aux;?>" id="<?php echo $dia; ?>" role="tabpanel" aria-labelledby="<?php echo $dia; ?>-tab">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>14:00</th>
                                                            <th>14:30</th>
                                                            <th>15:00</th>
                                                            <th>15:30</th>
                                                            <th>16:00</th>
                                                            <th>16:30</th>
                                                            <th>17:00</th>
                                                            <th>17:30</th>
                                                            <th>18:00</th>
                                                            <th>18:30</th>
                                                            <th>19:00</th>
                                                            <th>19:30</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $salas = ['azul', 'branca', 'rosa', 'verde', 'bancada', 'biblioteca'];
                                                            foreach ($salas as $sala) { ?>
                                                                <tr>
                                                                    <?php 
                                                                        $horas = ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'];
                                                                        foreach ($horas as $hora) { 
                                                                            if ($_SESSION['tipo'] == "administrador") {
                                                                                $sql = "SELECT h.id, p.nome, p.id as idProfessor, h.idDisciplina, d.nome as nomeDisciplina FROM horario as h INNER JOIN professores as p ON h.idProfessor = p.id INNER JOIN disciplinas as d ON d.id = h.idDisciplina WHERE h.dia = ? AND h.sala = ? AND hora = ?";
                                                                                $stmt = $con->prepare($sql);
                                                                                $stmt->bind_param("sss", $dia, $sala, $hora);
                                                                            }
                                                                            else if ($_SESSION['tipo'] == "professor"){
                                                                                $sql = "SELECT h.id, p.nome, p.id as idProfessor, h.idDisciplina, d.nome as nomeDisciplina FROM horario as h INNER JOIN professores as p ON h.idProfessor = p.id INNER JOIN disciplinas as d ON d.id = h.idDisciplina WHERE h.dia = ? AND h.sala = ? AND hora = ? AND idProfessor = ?";
                                                                                $stmt = $con->prepare($sql);
                                                                                $stmt->bind_param("sssi", $dia, $sala, $hora, $_SESSION['id']);
                                                                            }
                                                                            $stmt->execute();
                                                                            $result = $stmt->get_result();
                                                                            if ($result->num_rows > 0) {
                                                                                while ($row = $result->fetch_assoc()) {
                                                                                    $stmt1 = $con->prepare("SELECT a.nome, a.id FROM horario_alunos as ha INNER JOIN alunos as a ON ha.idAluno = a.id WHERE idHorario = ?");
                                                                                    $stmt1->bind_param("i", $row['id']);
                                                                                    $stmt1->execute();
                                                                                    $result1 = $stmt1->get_result();
                                                                                    $alunosId = [];
                                                                                    $alunos = [];
                                                                                    while ($row1 = $result1->fetch_assoc()) {
                                                                                        $alunos[] = $row1["nome"];
                                                                                        $alunosId[] = $row1["id"];
                                                                                    }?>
                                                                                
                                                                                    <td 
                                                                                        class="celula-horario"
                                                                                        style="cursor: pointer;" 
                                                                                        data-bs-toggle="modal" 
                                                                                        data-bs-target="#editarCelulaAluno"
                                                                                        data-id="<?php echo htmlspecialchars($row['id']) ?>"
                                                                                        data-dia="<?php echo htmlspecialchars($dia) ?>"
                                                                                        data-sala="<?php echo htmlspecialchars($sala) ?>"
                                                                                        data-hora="<?php echo htmlspecialchars($hora) ?>"
                                                                                        data-idDisciplina="<?php echo htmlspecialchars($row['idDisciplina']) ?>"
                                                                                        data-nomeDisciplina="<?php echo htmlspecialchars($row['nomeDisciplina']) ?>"
                                                                                        data-idprofessor="<?php echo htmlspecialchars($row['idProfessor']) ?>"
                                                                                        data-nome="<?php echo htmlspecialchars($row['nome'], ENT_QUOTES) ?>"
                                                                                        data-alunos='<?php echo json_encode($alunos) ?>'
                                                                                        data-alunosid='<?php echo json_encode($alunosId) ?>'
                                                                                    >
                                                                                        <div class="professor"><?php echo $row["nome"]?> | <?php echo $row["nomeDisciplina"]?></div>
                                                                                        <ul class="alunos">
                                                                                            <?php foreach ($alunos as $aluno): ?>
                                                                                                <li><?php echo $aluno; ?></li>
                                                                                            <?php endforeach; ?>
                                                                                        </ul>
                                                                                    </td>
                                                                                <?php }
                                                                            }
                                                                            else {?>
                                                                                <td 
                                                                                    class="celula-horario"
                                                                                    style="cursor: pointer;" 
                                                                                    data-bs-toggle="modal" 
                                                                                    data-bs-target="#editarCelulaAluno"
                                                                                    data-id="0"
                                                                                    data-dia="<?php echo htmlspecialchars($dia) ?>"
                                                                                    data-sala="<?php echo htmlspecialchars($sala) ?>"
                                                                                    data-hora="<?php echo htmlspecialchars($hora) ?>"
                                                                                    data-idDisciplina=""
                                                                                    data-nomeDisciplina=""
                                                                                    data-idprofessor=""
                                                                                    data-nome=""
                                                                                    data-alunos='[]'
                                                                                    data-alunosid='[]'
                                                                                >   
                                                                                    <div class="professor"></div>
                                                                                    <ul class="alunos">
                                                                                        <li></li>
                                                                                    </ul>
                                                                            <?php }
                                                                        }
                                                                    ?>
                                                                </tr>
                                                            <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php }
                                ?>
                                <div class="tab-pane fade" id="sabado" role="tabpanel" aria-labelledby="sabado-tab">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>09:00</th>
                                                    <th>09:30</th>
                                                    <th>10:00</th>
                                                    <th>10:30</th>
                                                    <th>11:00</th>
                                                    <th>11:30</th>
                                                    <th>12:00</th>
                                                    <th>12:30</th>
                                                    <th>13:00</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $dia = "sabado";
                                                    $salas = ['azul', 'branca', 'rosa', 'verde', 'bancada', 'biblioteca'];
                                                    foreach ($salas as $sala) { ?>
                                                        <tr>
                                                            <?php 
                                                                $horas = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00'];
                                                                foreach ($horas as $hora) { 
                                                                    if ($_SESSION['tipo'] == "administrador") {
                                                                        $sql = "SELECT h.id, p.nome, p.id as idProfessor, h.idDisciplina, d.nome as nomeDisciplina FROM horario as h INNER JOIN professores as p ON h.idProfessor = p.id INNER JOIN disciplinas as d ON d.id = h.idDisciplina WHERE h.dia = ? AND h.sala = ? AND hora = ?";
                                                                        $stmt = $con->prepare($sql);
                                                                        $stmt->bind_param("sss", $dia, $sala, $hora);
                                                                    }
                                                                    else if ($_SESSION['tipo'] == "professor"){
                                                                        $sql = "SELECT h.id, p.nome, p.id as idProfessor, h.idDisciplina, d.nome as nomeDisciplina FROM horario as h INNER JOIN professores as p ON h.idProfessor = p.id INNER JOIN disciplinas as d ON d.id = h.idDisciplina WHERE h.dia = ? AND h.sala = ? AND hora = ? AND idProfessor = ?";
                                                                        $stmt = $con->prepare($sql);
                                                                        $stmt->bind_param("sssi", $dia, $sala, $hora, $_SESSION['id']);
                                                                    }
                                                                    $stmt->execute();
                                                                    $result = $stmt->get_result();
                                                                    if ($result->num_rows > 0) {
                                                                        $row = [];
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            $stmt1 = $con->prepare("SELECT a.nome, a.id FROM horario_alunos as ha INNER JOIN alunos as a ON ha.idAluno = a.id WHERE idHorario = ?");
                                                                            $stmt1->bind_param("i", $row['id']);
                                                                            $stmt1->execute();
                                                                            $result1 = $stmt1->get_result();

                                                                            $alunos = [];
                                                                            $row1 = [];
                                                                            while ($row1 = $result1->fetch_assoc()) {
                                                                                $alunos[] = $row1["nome"];
                                                                                $alunosId[] = $row1["id"];
                                                                            }?>
                                                                            <td 
                                                                                class="celula-horario"
                                                                                style="cursor: pointer;" 
                                                                                data-bs-toggle="modal" 
                                                                                data-bs-target="#editarCelulaAluno"
                                                                                data-id="<?php echo htmlspecialchars($row['id']) ?>"
                                                                                data-dia="<?php echo htmlspecialchars($dia) ?>"
                                                                                data-sala="<?php echo htmlspecialchars($sala) ?>"
                                                                                data-hora="<?php echo htmlspecialchars($hora) ?>"
                                                                                data-idDisciplina="<?php echo htmlspecialchars($row['idDisciplina']) ?>"
                                                                                data-nomeDisciplina="<?php echo htmlspecialchars($row['nomeDisciplina']) ?>"
                                                                                data-idprofessor="<?php echo htmlspecialchars($row['idProfessor']) ?>"
                                                                                data-nome="<?php echo htmlspecialchars($row['nome'], ENT_QUOTES) ?>"
                                                                                data-alunos='<?php echo json_encode($alunos) ?>'
                                                                                data-alunosid='<?php echo json_encode($alunosId) ?>'
                                                                            >
                                                                                <div class="professor"><?php echo $row["nome"]?> | <?php echo $row["nomeDisciplina"]?></div>
                                                                                <ul class="alunos">
                                                                                    <?php foreach ($alunos as $aluno): ?>
                                                                                        <li><?php echo $aluno; ?></li>
                                                                                    <?php endforeach; ?>
                                                                                </ul>
                                                                            </td>
                                                                        <?php }
                                                                    }
                                                                    else {?>
                                                                        <td 
                                                                            class="celula-horario"
                                                                            style="cursor: pointer;" 
                                                                            data-bs-toggle="modal" 
                                                                            data-bs-target="#editarCelulaAluno"
                                                                            data-id="0"
                                                                            data-dia="<?php echo htmlspecialchars($dia) ?>"
                                                                            data-sala="<?php echo htmlspecialchars($sala) ?>"
                                                                            data-hora="<?php echo htmlspecialchars($hora) ?>"
                                                                            data-idDisciplina=""
                                                                            data-nomeDisciplina=""
                                                                            data-idprofessor=""
                                                                            data-nome=""
                                                                            data-alunos='[]'
                                                                            data-alunosid='[]'
                                                                        >   
                                                                            <div class="professor"></div>
                                                                            <ul class="alunos">
                                                                                <li></li>
                                                                            </ul>
                                                                    <?php }
                                                                }
                                                            ?>
                                                        </tr>
                                                    <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>    
                                </div>
                            </div>
                            <div class="modal fade" id="editarCelulaAluno" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="pagamentoConfigInserir?op=editMensalidade" method="POST">
                                            <!-- Cabeçalho -->
                                            <div class="modal-header border-0">
                                                <div class="row w-100 align-items-center">
                                                    <div class="col-md-6">
                                                        <input type="text" name="dia" id="dia"
                                                            class="form-control border-0 bg-transparent fw-bold fs-5" readonly>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <input type="text" name="hora" id="hora"
                                                            class="form-control border-0 bg-transparent fw-bold fs-5" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Corpo do Modal -->
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <!-- Sala -->
                                                    <div class="col-md-6">
                                                        <label for="sala" class="form-label">Sala</label>
                                                        <input type="text" name="sala" class="form-control" required readonly>
                                                    </div>

                                                    <!-- Professor -->
                                                    <div class="col-md-6">
                                                        <label for="prof" class="form-label">Professor</label>
                                                        <input type="text" name="prof" list="datalistProfs" class="form-control" required <?= $readonly; ?>>
                                                        <datalist id="datalistProfs">
                                                            <?php
                                                            $sql = "SELECT id, nome FROM professores WHERE estado = 1 ORDER BY nome ASC;";
                                                            $result = $con->query($sql);
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<option>{$row['id']} | {$row['nome']}</option>";
                                                            }
                                                            ?>
                                                        </datalist>
                                                    </div>

                                                    <!-- Disciplina -->
                                                    <div class="col-md-6">
                                                        <label for="disciplina" class="form-label">Disciplina</label>
                                                        <?php if ($_SESSION['tipo'] == "administrador") { ?>
                                                            <select name="disciplina" class="form-control">
                                                                <?php
                                                                    $sql = "SELECT id, nome FROM disciplinas;";
                                                                    $result = $con->query($sql);
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo "<option value=\"{$row['id']}\">{$row['nome']}</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <input type="text" name="disciplina" class="form-control" readonly>
                                                        <?php } ?>
                                                    </div>

                                                    <!-- Alunos -->
                                                    <?php
                                                        for ($i = 1; $i <= 10; $i++) {
                                                            $required = $i === 1 ? "required" : "";
                                                            $style = $i <= 4 ? "" : "style='display:none;'";
                                                            echo "
                                                            <div class='col-md-6' id='aluno_$i' $style>
                                                                <label for='aluno_$i' class='form-label'>Aluno $i</label>
                                                                <input type='text' name='aluno_$i' list='datalistAlunos' class='form-control' $required $readonly>
                                                            </div>";
                                                        }
                                                    ?>
                                                    <datalist id="datalistAlunos">
                                                        <?php
                                                            $sql = "SELECT id, nome FROM alunos WHERE estado = 1 ORDER BY nome ASC;";
                                                            $result = $con->query($sql);
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<option>{$row['id']} | {$row['nome']}</option>";
                                                            }
                                                        ?>
                                                    </datalist>
                                                </div>
                                            </div>

                                            <!-- Rodapé -->
                                            <?php if ($_SESSION['tipo'] == "administrador") { ?>
                                                <div class="modal-footer border-0 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-outline-primary" onclick="adicionarAluno()">Adicionar Aluno</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function enviarNotificacoes() {
                // Mostrar o modal
                const modal = new bootstrap.Modal(document.getElementById('modalProgresso'));
                modal.show();

                // Enviar o pedido AJAX para o ficheiro correto
                fetch("horarioNotificacao.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "acao=enviar_notificacoes"
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Ver erro PHP aqui
                    window.location.href = "horario";
                })
                .catch(error => {
                    alert("Erro ao enviar notificações: " + error);
                });
            }

            document.addEventListener("DOMContentLoaded", function () {
                // Seleciona todas as células com dados
                const celulas = document.querySelectorAll(".celula-horario");

                celulas.forEach(td => {
                    td.addEventListener("click", () => {
                        const id = td.dataset.id;
                        const dia = td.dataset.dia;
                        const sala = td.dataset.sala;
                        const hora = td.dataset.hora;
                        const idDisciplina = td.dataset.iddisciplina;
                        const nomeDisciplina = td.dataset.nomedisciplina;
                        const idProfessor = td.dataset.idprofessor;
                        const nome = td.dataset.nome;
                        const alunos = JSON.parse(td.dataset.alunos);
                        const alunosId = JSON.parse(td.dataset.alunosid);

                        // Chama a função com os dados
                        preencherHorario(id, dia, sala, hora, idDisciplina, nomeDisciplina, idProfessor, nome, alunos, alunosId);
                    });
                });
            });
        </script>
        <?php if ($_SESSION['tipo'] == "administrador") { ?>
            <script>
                //Função para tornar visivel a proxima secção ao clicar no botão
                function adicionarAluno() {
                    // Seleciona a seção com base no índice fornecido
                    for (let j = 1; j < 10; j++) {
                        const aluno = document.getElementById("aluno_" + j);
                        if (aluno.style.display === "none") {
                            aluno.style.display = ""; // Torna visível 
                            return; // Encerra a função após exibir o próximo produto
                        }
                    }
                    alert("Atingiu o máximo de alunos."); // Mensagem caso todos os produtos já estejam visíveis
                }
                function preencherHorario(id, dia, sala, hora, idDisciplina, nomeDisciplina, idProfessor, nome, alunos, alunosId) {
                    aux = 0;

                    document.querySelector('#dia').value = dia;   
                    document.querySelector('#hora').value = hora;
                    document.querySelector('#editarCelulaAluno input[name="sala"]').value = sala;

                    select = document.querySelector('#editarCelulaAluno select[name="disciplina"]');
                    if ([...select.options].some(option => option.value === idDisciplina)) {
                        select.value = idDisciplina;
                    }

                    const profInput = document.querySelector('#editarCelulaAluno input[name="prof"]');
                    profInput.value = idProfessor ? `${idProfessor} | ${nome}` : "";

                    // Reset: mostra todos e limpa valores
                    for (let i = 1; i <= 10; i++) {
                        const alunoQuery = document.getElementById("aluno_" + i);
                        alunoQuery.style.display = "";
                        alunoQuery.querySelector("input").value = "";
                    }

                    // Preencher com os atuais alunos
                    alunos.forEach((aluno, index) => {
                        const alunoDiv = document.getElementById("aluno_" + (index + 1));
                        const input = alunoDiv.querySelector("input");
                        input.value = alunosId[index] + " | " + aluno;
                    });

                    // Esconder os que não foram usados
                    for (let i = alunos.length + 1; i <= 10; i++) {
                        document.getElementById("aluno_" + i).style.display = "none";
                    }

                    const form = document.querySelector('#editarCelulaAluno form');
                    form.action = `horarioInserir?op=save&idHorario=${id}`;
                }
            </script>
        <?php } else if ($_SESSION['tipo'] == "professor") { ?>
            <script> 
                function preencherHorario(id, dia, sala, hora, idDisciplina, nomeDisciplina, idProfessor, nome, alunos, alunosId) {
                    // Preencher dados principais
                    document.querySelector('.fw-mediumbold').value = dia;
                    document.querySelector('.fw-light').value = hora;
                    document.querySelector('#editarCelulaAluno input[name="sala"]').value = sala;
                    document.querySelector('#editarCelulaAluno input[name="disciplina"]').value = nomeDisciplina;

                    const profInput = document.querySelector('#editarCelulaAluno input[name="prof"]');
                    profInput.value = idProfessor ? `${idProfessor} | ${nome}` : "";

                    // Limpar e mostrar todos os campos de aluno
                    for (let i = 1; i <= 10; i++) {
                        const alunoDiv = document.getElementById("aluno_" + i);
                        if (alunoDiv) {
                            alunoDiv.style.display = "";
                            const input = alunoDiv.querySelector("input");
                            if (input) input.value = "";
                        }
                    }

                    // Preencher com os alunos recebidos
                    alunos.forEach((aluno, index) => {
                        const alunoDiv = document.getElementById("aluno_" + (index + 1));
                        if (alunoDiv) {
                            const input = alunoDiv.querySelector("input");
                            if (input) input.value = alunosId[index] + " | " + aluno;
                        }
                    });

                    // Esconder os campos extra
                    for (let i = alunos.length + 1; i <= 10; i++) {
                        const alunoDiv = document.getElementById("aluno_" + i);
                        if (alunoDiv) alunoDiv.style.display = "none";
                    }

                    // Atualizar ação do formulário
                    const form = document.querySelector('#editarCelulaAluno form');
                    form.action = `horarioInserir?op=save&idHorario=${id}`;
                }
            
                $(document).ready(function() {
                    const hoje = new Date();
                    const diaSemana = hoje.getDay(); 

                    switch (diaSemana) {
                        case 0:
                            diaDeseajado = '#segunda-tab';
                            break;
                        case 1:
                            diaDeseajado = '#segunda-tab';
                            break;
                        case 2:
                            diaDeseajado = '#terca-tab';
                            break;
                        case 3:
                            diaDeseajado = '#quarta-tab';
                            break;
                        case 4:
                            diaDeseajado = '#quinta-tab';
                            break;
                        case 5:
                            diaDeseajado = '#sexta-tab';
                            break;
                        case 6:
                            diaDeseajado = '#sabado-tab';
                            break;
                        default:
                            diaDeseajado = '#segunda-tab';
                    }

                    // Ativa a aba
                    $(diaDeseajado).tab('show');
                    const novaUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({}, document.title, novaUrl);
                });
            </script>
        <?php } ?>
    </div>
  </body>
</html>
