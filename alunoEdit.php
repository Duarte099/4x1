<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;
    $estouEm2 = 3;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if (adminPermissions($con, "adm_001", "insert") == 0) {
        header('Location: dashboard');
        exit();
    }

    //Obtem o id do admin via GET
    $idAluno = $_GET['idAluno'];

    //Obtem todas as informações do aluno que está a ser editado
    $sql = "SELECT * FROM alunos WHERE id = '$idAluno'";
    $result = $con->query($sql);
    //Se houver um aluno com o id recebido, guarda as informações
    if ($result->num_rows > 0) {
        $rowAluno = $result->fetch_assoc();
    }
    //Caso contrário volta para a dashboard para não dar erro
    else{
        header('Location: dashboard');
        exit();
    }
?>
    <title>4x1 | Editar Aluno</title>
    <style>
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
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                        <div>
                            <h2 class="fw-bold mb-3">Ficha do aluno</h2>
                        </div>
                    </div>
                    <form action="alunoInserir?op=save" method="POST">
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
                                        <label>CONTATO:</label>
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
                                        <input type="text" name="ano" value="<?php echo $rowAluno['ano']; ?>">
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
                                    <div class="campo" style="flex: 0 0 99.5%;">
                                        <label>DISPONIBILIDADE:</label>
                                        <input type="text" name="disponibilidade" value="<?php echo $rowAluno['disponibilidade']; ?>">
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
                                        <input type="text" name="maeTlm" value="<?php echo $rowAluno['tlmMae']; ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="campo" style="flex: 0 0 49%;">
                                        <label>PAI:</label>
                                        <input type="text" name="pai" value="<?php echo $rowAluno['nomePai']; ?>">
                                    </div>
                                    <div class="campo" style="flex: 0 0 49%;" value="<?php echo $rowAluno['tlmPai']; ?>">
                                        <label>Tlm:</label>
                                        <input type="text" name="paiTlm">
                                    </div>
                                </div>
                            </div>

                            <!-- Modalidade e Disciplinas -->
                            <div class="form-section">
                                <div class="form-row">
                                    <div class="campo" style="flex: 0 0 100%;">
                                        <label>MODALIDADE:</label>
                                        <input type="text" name="modalidade" value="<?php echo $rowAluno['modalidade']; ?>">
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
                                                                <span class='selectgroup-button'>" . $row['nome'] . "</span>
                                                            </label>";
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="horario">
                                <div class="dia-horario">
                                    <label>2ªF - <input type="number" name="2F" min="0" max="24" value="0" style="width: 50px;">h</label>
                                </div>
                                <div class="dia-horario">
                                    <label>3ªF - <input type="number" name="3F" min="0" max="24" value="0" style="width: 50px;">h</label>
                                </div>
                                <div class="dia-horario">
                                    <label>4ªF - <input type="number" name="4F" min="0" max="24" value="0" style="width: 50px;">h</label>
                                </div>
                                <div class="dia-horario">
                                    <label>5ªF - <input type="number" name="5F" min="0" max="24" value="0" style="width: 50px;">h</label>
                                </div>
                                <div class="dia-horario">
                                    <label>6ªF - <input type="number" name="6F" min="0" max="24" value="0" style="width: 50px;">h</label>
                                </div>
                                <div class="dia-horario">
                                    <label>Sab - <input type="number" name="7F" min="0" max="24" value="0" style="width: 50px;">h</label>
                            </div> -->
                            <button type="submit" class="btn btn-primary">Criar aluno</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

