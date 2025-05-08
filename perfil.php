<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 1;
    if ($_SESSION["tipo"] == "professor") {
        $stmt = $con->prepare("SELECT * FROM professores WHERE id = ?");

        $sql = "SELECT valor FROM valores_pagamento;";
        $result = $con->query($sql);
        $valores = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $valores[] = $row['valor'];
            }
        }

        $mesSelecionado = $_GET['mes'] ?? date('Y-m');
        list($ano, $mes) = explode('-', $mesSelecionado);

        if ($mes == date("n") && $ano == date("Y")) {
            //Horas dadas 1 Ciclo
            $valorParcial1Ciclo = 0;
            $horasDadas1Ciclo = 0;
            $sql = "SELECT duracao
                    FROM alunos_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano >= 1 AND a.ano <= 4 AND p.idProfessor = {$_SESSION["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadas1Ciclo = $horasDadas1Ciclo + $row["duracao"];
                }
                $horasDadas1Ciclo = minutosToValor($horasDadas1Ciclo);
                $valorParcial1Ciclo = $horasDadas1Ciclo * $valores[0];
            }

            //Horas dadas 2 Ciclo
            $valorParcial2Ciclo = 0;
            $horasDadas2Ciclo = 0;
            $sql = "SELECT duracao
                    FROM alunos_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 4 AND a.ano < 7 AND p.idProfessor= {$_SESSION["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadas2Ciclo = $horasDadas2Ciclo + $row["duracao"];
                }
                $horasDadas2Ciclo = minutosToValor($horasDadas2Ciclo);
                $valorParcial2Ciclo = $horasDadas2Ciclo * $valores[1];
            }

            //Horas dadas 3 Ciclo
            $valorParcial3Ciclo = 0;
            $horasDadas3Ciclo = 0;
            $sql = "SELECT duracao
                    FROM alunos_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 6 AND a.ano <= 9 AND p.idProfessor= {$_SESSION["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadas3Ciclo = $horasDadas3Ciclo + $row["duracao"];
                }
                $horasDadas3Ciclo = minutosToValor($horasDadas3Ciclo);
                $valorParcial3Ciclo = $horasDadas3Ciclo * $valores[2];
            }

            //Horas dadas secundario
            $valorParcialSecundario = 0;
            $horasDadasSecundario = 0;
            $sql = "SELECT duracao
                    FROM alunos_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 9 AND p.idProfessor= {$_SESSION["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadasSecundario = $horasDadasSecundario + $row["duracao"];
                }
                $horasDadasSecundario = minutosToValor($horasDadasSecundario);
                $valorParcialSecundario = $horasDadasSecundario * $valores[3];
            }

            //Horas dadas Universidade
            $valorParcialUniversidade = 0;
            $horasDadasUniversidade = 0;
            $sql = "SELECT duracao
                    FROM alunos_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano = 0 AND p.idProfessor= {$_SESSION["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadasUniversidade = $horasDadasUniversidade + $row["duracao"];
                }
                $horasDadasUniversidade = minutosToValor($horasDadasUniversidade);
                $valorParcialUniversidade = $horasDadasUniversidade * $valores[4];
            }

            $total = $valorParcial1Ciclo + $valorParcial2Ciclo + $valorParcial3Ciclo + $valorParcialSecundario + $valorParcialUniversidade; 
        }
        else {
            $sql = "SELECT * FROM professores_recibo WHERE p.idProfessor= {$_SESSION["id"]} AND mes = $mes AND ano = $ano";
            $result = $con->query($sql);
            //Se houver um aluno com o id recebido, guarda as informações
            if ($result->num_rows >= 0) {
                $rowRecibo = $result->fetch_assoc();
            }
        }
    }
    else {
        $stmt = $con->prepare("SELECT * FROM administrador WHERE id = ?");
    }
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowPerfil = $result->fetch_assoc();
    } 

    
?>
    <title>4x1 | Perfil</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css'>
    <style>
    .container {
        background-color: white;
        border-radius: 10px;
        margin: 40px auto;
        max-width: 1000px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        padding: 20px;
    }

    form {
        display: flex;
        flex-direction: row;
        width: 100%;
    }

    .profile-photo {
        background-color: #f1f1f1;
        width: 35%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        box-sizing: border-box;
    }

    .profile-photo img {
        width: 300px;
        height: 300px;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid #ccc;
        margin-bottom: 15px;
    }

    .profile-photo input[type="file"] {
        margin-top: 10px;
    }

    .profile-form {
        width: 65%;
        padding: 40px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .profile-form h2 {
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="input"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-group input[type="submit"] {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        cursor: pointer;
    }

    .form-group input[type="submit"]:hover {
        background-color: #0056b3;
    }

    /* Garantir que as tabs não quebram o layout */
    .tab-content .tab-pane {
        padding-top: 20px;
    }

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

    @media (max-width: 768px) {
        form {
            flex-direction: column;
        }

        .profile-photo,
        .profile-form {
            width: 100%;
        }

        .profile-photo img {
            width: 150px;
            height: 150px;
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
                    <div class="card-body">
                        <?php if($_SESSION['tipo'] == "professor") { ?>
                            <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="perfil-tab" data-bs-toggle="pill" href="#perfil" role="tab" aria-controls="perfil" aria-selected="true">Perfil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="esti-ganhos-tab" data-bs-toggle="pill" href="#esti-ganhos" role="tab" aria-controls="esti-ganhos" aria-selected="false">Estimativa ganhos</a>
                                </li>
                            </ul>
                        <?php } ?>
                        <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">
                                <form action="perfilInserir?op=edit" method="POST" id="formEdit" onsubmit="return verificarPasswords()" enctype="multipart/form-data">
                                    <div class="profile-photo">
                                        <img id="preview" src="<?php echo $_SESSION["img"] ?>">
                                        <input type="file" name="foto" accept="image/*" onchange="previewImage(event)">
                                    </div>
                                    <div class="profile-form">
                                        <h2>Perfil</h2>
                                        <div class="form-group">
                                            <label for="nome">Nome</label>
                                            <input type="text" id="nome" name="nome" value="<?php echo $rowPerfil["nome"] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" name="email" value="<?php echo $rowPerfil["email"] ?>" required>
                                        </div>
                                        <?php if($_SESSION["tipo"] == "professor") { ?>
                                            <div class="form-group">
                                                <label for="contacto">Contacto</label>
                                                <input type="tel" id="contacto" name="contacto" value="<?php echo $rowPerfil['contacto']; ?>" required>
                                                <input type="hidden" name="contacto" id="contactoHidden">
                                            </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label for="pass">Password</label>
                                            <input type="input" id="password" name="pass">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmar">Confirmar Password</label>
                                            <input type="input" id="passwordConfirm" name="confirmar">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" value="Guardar">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php if($_SESSION['tipo'] == "professor") { ?>
                                <div class="tab-pane fade" id="esti-ganhos" role="tabpanel" aria-labelledby="esti-ganhos-tab">
                                    <form action="" method="GET">
                                        <div class="select-container">
                                            <input type="hidden" style="display: none;" name="tab" value="1">
                                            
                                            <label for="mes" class="form-label mb-0 me-2">Data:</label>
                                            <input type="month" name="mes" id="mes" value="<?= $mesSelecionado ?>" class="form-control" style="width: 200px;" onchange="this.form.submit()">
                                        </div>
                                    </form>
                                    <div class="page-inner">
                                        <div class="container2">
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS 1º CICLO:</label>
                                                        <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas1Ciclo;} else {echo $rowRecibo['horasDadas1Ciclo'];} ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR UNITÁRIO:</label>
                                                        <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[0];} else {echo $rowRecibo['valorUnitario1Ciclo'];} ?>€" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR PARCIAL:</label>
                                                        <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial1Ciclo;} else {echo $rowRecibo['valorParcial1Ciclo'];} ?>€" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS 2º CICLO:</label>
                                                        <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas2Ciclo;} else {echo $rowRecibo['horasDadas2Ciclo'];} ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR UNITÁRIO:</label>
                                                        <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[1];} else {echo $rowRecibo['valorUnitario2Ciclo'];} ?>€" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR PARCIAL:</label>
                                                        <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial2Ciclo;} else {echo $rowRecibo['valorParcial2Ciclo'];} ?>€" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS 3º CICLO:</label>
                                                        <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadas3Ciclo;} else {echo $rowRecibo['horasDadas3Ciclo'];} ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR UNITÁRIO:</label>
                                                        <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[2];} else {echo $rowRecibo['valorUnitario3Ciclo'];} ?>€" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR PARCIAL:</label>
                                                        <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcial3Ciclo;} else {echo $rowRecibo['valorParcial3Ciclo'];} ?>€" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS SECUNDÁRIO:</label>
                                                        <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadasSecundario;} else {echo $rowRecibo['horasDadasSecundario'];} ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR UNITÁRIO:</label>
                                                        <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[3];} else {echo $rowRecibo['valorUnitarioSecundario'];} ?>€" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR PARCIAL:</label>
                                                        <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcialSecundario;} else {echo $rowRecibo['valorParcialSecundario'];} ?>€" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>HORAS UNIVERSIDADE:</label>
                                                        <input type="input" name="horasGrupo" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $horasDadasUniversidade;} else {echo $rowRecibo['horasDadasUniversidade'];} ?>" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR UNITÁRIO:</label>
                                                        <input type="input" name="valorUnitario" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valores[4];} else {echo $rowRecibo['valorUnitarioUniversidade'];} ?>€" readonly>
                                                    </div>
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>VALOR PARCIAL:</label>
                                                        <input type="input" name="horasBalanco" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $valorParcialUniversidade;} else {echo $rowRecibo['valorParcialUniversidade'];} ?>€" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-section">
                                                <div class="form-row">
                                                    <div class="campo" style="flex: 0 0 32%;">
                                                        <label>TOTAL:</label>
                                                        <input type="input" name="total" value="<?php if ($mes == date("n") && $ano == date("Y")) {echo $total;} else {echo $rowRecibo['total'];} ?>€" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <button type="submit" class="btn btn-primary">Registrar hora</button> -->
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
            <script>
                const input = document.querySelector("#contacto");
                const hiddenInput = document.querySelector("#contactoHidden");
                const iti = window.intlTelInput(input, {
                    initialCountry: "pt",
                    preferredCountries: ["pt", "br", "fr", "gb"],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });

                // Ao submeter o formulário, atualiza o campo hidden
                document.querySelector("#formEdit").addEventListener("submit", function () {
                    hiddenInput.value = iti.getNumber();
                });

                function verificarPasswords() {
                    const password = document.getElementById("password").value;
                    const confirm = document.getElementById("passwordConfirm").value;

                    if (password === confirm) {
                        return true;
                    } else {
                        $.notify({
                            message: 'As palavras passes não coincidem!',
                            title: 'Notificação',
                            icon: 'fa fa-info-circle',
                        }, {
                            type: 'danger',
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            delay: 2000
                        });

                        return false;
                    }
                }

                function previewImage(event) {
                    const reader = new FileReader();
                    reader.onload = function () {
                        const output = document.getElementById('preview');
                        output.src = reader.result;
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            </script>
        </div>
        <?php include('./endPage.php'); ?>
    </body>
</html>

