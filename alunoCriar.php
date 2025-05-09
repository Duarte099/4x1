<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;
?>
    <title>4x1 | Criar Aluno</title>
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
            <form action="alunoInserir.php?op=save" method="POST" id="formEdit" class="formEdit">
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
                            <h2 class="fw-bold mb-3">Ficha do aluno</h2>
                        </div>
                    </div>
                    <div class="container2">
                        <div class="form-section">
                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 99.5%;">
                                    <label>NOME:</label>
                                    <input type="text" name="nome" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 64%;">
                                    <label>MORADA:</label>
                                    <input type="text" name="morada" required>
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>LOCALIDADE:</label>
                                    <input type="text" name="localidade">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 31%;">
                                    <label>CÓDIGO POSTAL:</label>
                                    <input type="text" name="codigoPostal" required>
                                </div>
                                <div class="campo" style="flex: 0 0 32%;">
                                    <label>NIF:</label>
                                    <input type="number" name="NIF" min="0" max="999999999">
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>DATA DE NASCIMENTO:</label>
                                    <input type="date" name="dataNascimento" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 64%;">
                                    <label>EMAIL:</label>
                                    <input type="email" name="email">
                                </div>
                                <div class="campo" style="flex: 0 0 34%;">
                                    <label>CONTATO:</label>
                                    <input type="tel" id="contacto" name="contacto">
                                    <input type="hidden" name="contacto" id="contactoHidden">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 78%;">
                                    <label>ESCOLA:</label>
                                    <input type="text" name="escola">
                                </div>
                                <div class="campo" style="flex: 0 0 20%;">
                                    <label>ANO:</label>
                                    <input type="number" name="ano" min="0" max="12" value="0" required>
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
                                    <input type="number" name="horasGrupo" min="0">
                                </div>
                                <div class="campo" style="flex: 0 0 23%;">
                                    <label>HORAS INDIVIDUAL:</label>
                                    <input type="number" name="horasIndividual" min="0">
                                </div>
                                <div class="campo" style="flex: 0 0 10%;">
                                    <label>TRANSPORTE:</label>
                                    <input class="form-check-input" type="checkbox" name="transporte" style="width: 25px; height: 25px; padding: 5px; border: 1px solid #ccc;" id="flexCheckDefault">
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
                                    <input type="tel" id="maeTlm" name="maeTlm">
                                    <input type="hidden" name="maeTlm" id="maeTlmHidden">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="campo" style="flex: 0 0 49%;">
                                    <label>PAI:</label>
                                    <input type="text" name="pai">
                                </div>
                                <div class="campo" style="flex: 0 0 49%;">
                                    <label>Tlm:</label>
                                    <input type="tel" id="paiTlm" name="paiTlm">
                                    <input type="hidden" name="paiTlm" id="paiTlmHidden">
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
                                                            <input type='checkbox' name='disciplina_" . $row['id'] . "' value='" . $row['nome'] . "'  class='selectgroup-input' />
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
        </script>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

