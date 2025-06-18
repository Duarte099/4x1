<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 5;
?>
    <title>Registrar presença | 4x1</title>
    <style>
        .card {
            min-height: 100vh !important;
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

        .big-checkbox {
            width: 1.5em;
            height: 1.5em;
            transform: translateY(4px); /* opcional: alinha melhor com o label */
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
                    <div class="col-12 col-md-10 col-lg-8 mx-auto">
                        <form action="presencaInserir?op=save" method="POST">
                            <div class="container2">
                                <div class="page-inner">
                                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                                        <div>
                                            <h2 class="fw-bold mb-3">Registrar presença</h2>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="nome" class="form-label">Aluno:</label>
                                            <input type="text" class="form-control" name="nome" list="datalistNomes" oninput="atualizarCampos(this)" required>
                                            <datalist id='datalistNomes'>
                                                <?php
                                                    //Obtem todas as referencias dos produtos que estao ativos
                                                    $sql = "SELECT id, nome FROM alunos WHERE estado = 1 ORDER BY nome ASC;";
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                    //Percorre todos os produtos e adiciona-os como opção na dataList
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option>$row[id] | $row[nome]</option>";
                                                    }
                                                    }
                                                ?>
                                            </datalist>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="ano" class="form-label">Horas grupo:</label>
                                            <input type="text" class="form-control" name="horasGrupo" id="horasGrupo" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="horasIndividual" class="form-label">Horas individuais:</label>
                                            <input type="text" class="form-control" name="horasIndividual" id="horasIndividual" disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="disciplina" class="form-label">Disciplina:</label>
                                            <select class="form-control" name="disciplina">
                                                <?php 
                                                    if ($_SESSION['tipo'] == "professor") {
                                                        $sql = "SELECT d.nome, d.id
                                                        FROM disciplinas AS d 
                                                        INNER JOIN professores_disciplinas AS pd ON d.id = pd.idDisciplina 
                                                        INNER JOIN professores AS p ON pd.idProfessor = p.id 
                                                        WHERE p.id = {$_SESSION['id']};";
                                                    }
                                                    elseif ($_SESSION['tipo'] == "administrador") {
                                                        $sql = "SELECT d.nome, d.id FROM disciplinas AS d;";
                                                    }
                                                    
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) { 
                                                            echo "<option value=" . $row['id'] . ">". $row['nome'] . "</option>";  
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="hora" class="form-label">Duração:</label>
                                            <select class="form-control" name="hora">
                                                <option value="15">15 min</option>
                                                <option value="30">30 min</option>
                                                <option value="45">45 min</option>
                                                <option value="60">60 min</option>
                                                <option value="75">75 min</option>
                                                <option value="90">90 min</option>
                                                <option value="105">105 min</option>
                                                <option value="120">120 min</option>
                                                <option value="150">150 min</option>
                                                <option value="180">180 min</option>
                                                <option value="210">210 min</option>
                                                <option value="240">240 min</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="dia" class="form-label">Dia:</label>
                                            <input type="date" name="dia" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="individual" class="form-label">Tipo:</label>
                                            <select class="form-control" name="individual" id="individual" disabled required>
                                                <option value="1" >Individual</option>
                                                <option value="0" selected>Grupo</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Registrar presença</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
        <script>
            function atualizarCampos(input) {
                // O valor do input é o ID do aluno, conforme definido nas opções do datalist
                var partes = input.value.split(" | ");
                var alunoId = partes[0];

                const select = document.getElementById("individual");
                const horasGrupoInput = document.getElementById("horasGrupo");
                const horasIndividualInput = document.getElementById("horasIndividual");

                // Limpa e desativa o select inicialmente
                select.innerHTML = '';
                select.disabled = true;

                if (alunoId !== "") {
                    // Realiza a requisição AJAX para obter os dados do aluno
                    $.ajax({
                        url: 'json.obterNome.php',
                        type: 'GET',
                        data: { idAluno: alunoId },
                        success: function(response) {
                            let data;

                            try {
                                data = JSON.parse(response);
                            } catch (e) {
                                console.error('Resposta inválida:', response);
                                return;
                            }

                            if (data === "erro") {
                                horasGrupoInput.value = "";
                                horasIndividualInput.value = "";
                            } else {
                                horasGrupoInput.value = data.horasGrupo;
                                horasIndividualInput.value = data.horasIndividual;

                                // Atualizar o select conforme as horas
                                if (data.horasGrupo > 0) {
                                    const optGrupo = document.createElement("option");
                                    optGrupo.value = "grupo";
                                    optGrupo.text = "Grupo";
                                    select.appendChild(optGrupo);
                                }

                                if (data.horasIndividual > 0) {
                                    const optIndividual = document.createElement("option");
                                    optIndividual.value = "individual";
                                    optIndividual.text = "Individual";
                                    select.appendChild(optIndividual);
                                }

                                // Só ativa se houver pelo menos uma opção
                                if (select.options.length > 0) {
                                    select.disabled = false;
                                }
                            }
                        },
                        error: function() {
                            console.error('Erro ao buscar o nome.');
                        }
                    });
                } else {
                    // Limpa campos se o input for inválido
                    horasGrupoInput.value = "";
                    horasIndividualInput.value = "";
                }
            }
        </script>
        <?php 
            include('./endPage.php'); 
        ?>