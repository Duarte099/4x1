<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 3;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if (adminPermissions($con, "adm_002", "insert") == 0) {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }
?>
    <title>4x1 | Criar Professor</title>
    <style>
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
</head>
    <body>
        <div class="wrapper">
            <?php 
                include('./sideBar.php'); 
            ?>
            <form action="professorInserir?op=save" method="POST">
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

                                            if ($hora) {
                                                echo "<td>
                                                        <label class='selectgroup-item' id='selectgroup-item'>
                                                            <input type=\"checkbox\" name=\"disponibilidade_" . $dia . "_" . $hora . "\" class=\"selectgroup-input\" id=\"selectgroup-input\"/>
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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                        <div>
                            <h2 class="fw-bold mb-3">Ficha do professor</h2>
                        </div>
                    </div>
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
                                    <label>EMAIL:</label>
                                    <input type="text" name="email">
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>CONTACTO:</label>
                                    <input type="text" name="contacto">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 98%;">
                                    <label>DISPONIBILIDADE:</label>
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addRowModal"
                                    >
                                        <!-- <i class="fa fa-up-right-from-square"> -->
                                        DISPONIBILIDADE
                                    </button>
                                </div>
                            </div>     
                        </div>

                        <!-- Disciplinas -->
                        <div class="form-section">
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
                                                            <span class='selectgroup-button' style=\"padding: 5px\">" . $row['nome'] . "</span>
                                                        </label>";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Criar Professor</button>
                    </div>
                </div>
            </form>
        </div>
        <?php 
            include('./endPage.php'); 
        ?>
    </body>
    </html>

