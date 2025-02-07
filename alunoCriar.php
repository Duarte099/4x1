<?php
    include('./head.php'); 

    $estouEm = 2;
    $estouEm2 = 2;

    if (adminPermissions($con, "adm_001", "insert") == 0) {
        header('Location: dashboard');
        exit();
    }
?>
<title>4x1 | Criar Aluno</title>
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
                                    <input type="text" name="nome">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 64%;">
                                    <label>MORADA:</label>
                                    <input type="text" name="morada">
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>LOCALIDADE:</label>
                                    <input type="text" name="localidade">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 31%;">
                                    <label>CÓDIGO POSTAL:</label>
                                    <input type="text" name="codigoPostal">
                                </div>
                                <div class="campo" style="flex: 0 0 32%;">
                                    <label>NIF:</label>
                                    <input type="number" name="NIF">
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>DATA DE NASCIMENTO:</label>
                                    <input type="date" name="dataNascimento">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 64%;">
                                    <label>EMAIL:</label>
                                    <input type="email" name="email">
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>CONTATO:</label>
                                    <input type="text" name="contacto">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 78%;">
                                    <label>ESCOLA:</label>
                                    <input type="text" name="escola">
                                </div>
                                <div class="campo" style="flex: 0 0 20%;">
                                    <label>ANO:</label>
                                    <input type="text" name="ano">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 78%;">
                                    <label>CURSO:</label>
                                    <input type="text" name="curso">
                                </div>
                                <div class="campo" style="flex: 0 0 20%;">
                                    <label>TURMA:</label>
                                    <input type="text" name="turma">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 99.5%;">
                                    <label>DISPONIBILIDADE:</label>
                                    <input type="text" name="disponibilidade">
                                </div>
                            </div>      
                        </div>

                        <!-- Seção de pais -->
                        <div class="form-section">
                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 49%;">
                                    <label>MÃE:</label>
                                    <input type="text" name="mae">
                                </div>
                                <div class="campo" style="flex: 0 0 49%;">
                                    <label>Tlm:</label>
                                    <input type="text" name="maeTlm">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 49%;">
                                    <label>PAI:</label>
                                    <input type="text" name="pai">
                                </div>
                                <div class="campo" style="flex: 0 0 49%;">
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
                                    <input type="text" name="modalidade">
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
                                                    echo "<label class='selectgroup-item'>
                                                            <input type='checkbox' name='disciplina_" . $row['id'] . "' value='" . $row['nome'] . "' class='selectgroup-input' />
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