<?php
        //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;
    $estouEm2 = 2;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if (adminPermissions($con, "adm_001", "insert") == 0) {
        header('Location: dashboard.php');
        exit();
    }
?>
    <title>4x1 | Criar Aluno</title>
    <style>
        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .titulo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .campo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
        }

        .campo label {
            font-weight: bold;
            white-space: nowrap;
        }

        input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #000;
            padding: 0.3rem;
            font-size: 1rem;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .horario {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
            margin: 2rem 0;
        }

        .dia-horario {
            text-align: center;
        }

        .dia-horario input {
            text-align: center;
        }

        .declaracao {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #000;
            text-align: center;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .horario {
                grid-template-columns: repeat(2, 1fr);
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
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                    <div>
                        <h2 class="fw-bold mb-3">Ficha do aluno</h2>
                    </div>
                </div>
                <form action="alunoInserir.php?op=save" method="POST">
                    <div class="container2">
                        <div class="grid">
                            <div class="campo">
                                <label>NOME:</label>
                                <input type="text" name="nome">
                            </div>
                            <div class="campo">
                                <label>LOCALIDADE:</label>
                                <input type="text" name="localidade">
                            </div>
                            
                            <div class="campo">
                                <label>MORADA:</label>
                                <input type="text" name="morada">
                            </div>
                            <div class="campo">
                                <label>DATA DE NASCIMENTO:</label>
                                <input type="date" name="dataNascimento">
                            </div>
                            
                            <div class="campo">
                                <label>CÓDIGO POSTAL:</label>
                                <input type="text" name="codigoPostal">
                            </div>
                            <div class="campo">
                                <label>NIF:</label>
                                <input type="number" name="NIF">
                            </div>
                        </div>

                        <div class="grid">
                            <div class="campo">
                                <label>EMAIL:</label>
                                <input type="email" name="email">
                            </div>
                            <div class="campo">
                                <label>CONTATO:</label>
                                <input type="text" name="contacto">
                            </div>
                            <div class="campo">
                                <label>ESCOLA:</label>
                                <input type="text" name="escola">
                            </div>
                            <div class="campo">
                                <label>ANO:</label>
                                <input type="text" name="ano">
                            </div>
                            <div class="campo">
                                <label>CURSO:</label>
                                <input type="text" name="curso">
                            </div>
                            <div class="campo">
                                <label>TURMA:</label>
                                <input type="text" name="turma">
                            </div>
                            <div class="grid full-width">
                                <div class="campo full-width">
                                    <label>DISPONIBILIDADE:</label>
                                    <input type="text" name="disponibilidade">
                                </div>
                            </div>
                        </div>
                        <div class="grid">
                            <div class="campo">
                                <label>MÃE:</label>
                                <input type="text" name="mae">
                            </div>
                            <div class="campo">
                                <label>Tlm:</label>
                                <input type="text" name="maeTlm" readonly>
                            </div>
                            
                            <div class="campo">
                                <label>PAI:</label>
                                <input type="text" name="pai">
                            </div>
                            <div class="campo">
                                <label>Tlm:</label>
                                <input type="text" name="paiTlm" readonly>
                            </div>
                        </div>

                        <div class="grid full-width">
                            <div class="campo full-width">
                                <label>MODALIDADE:</label>
                                <input type="text" name="modalidade">
                            </div>
                            
                            <div class="campo full-width">
                                <label>DISCIPLINAS:</label>
                                <div class="selectgroup selectgroup-pills">
                                    <?php 
                                        $sql = "SELECT id, nome FROM disciplinas ;";
                                        $result = $con->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<label class=\"selectgroup-item\">
                                                        <input
                                                            type=\"checkbox\"
                                                            name=\"disciplina_" . $row['id'] . "\"
                                                            value=\"" . $row['nome'] . "\"
                                                            class=\"selectgroup-input\"
                                                        />
                                                        <span class=\"selectgroup-button\">" . $row['nome'] . "</span>
                                                    </label>";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="horario">

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
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Criar aluno</button>
                </form>
            </div>
        </div>
    </div>
    <?php
        include('./endPage.php');
    ?>
</body>

</html>