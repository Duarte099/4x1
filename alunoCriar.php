<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 2;
?>
    <title>4x1 | Criar Aluno</title>
    <style>
        .card {
            min-height: 100vh !important;
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
                        <form action="alunoInserir.php?op=save" method="POST" id="formEdit" class="formEdit">
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
                            </div> -->
                            <div class="page-inner">
                                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                                    <div>
                                        <h2 class="fw-bold mb-3">Ficha do aluno</h2>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="nome" class="form-label">Nome:</label>
                                        <input type="text" class="form-control" name="nome" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="morada" class="form-label">Morada:</label>
                                        <input type="text" class="form-control" name="morada" id="email" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="localidade" class="form-label">Localidade:</label>
                                        <input type="text" class="form-control" name="localidade">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="codigoPostal" class="form-label">Código postal:</label>
                                        <input type="input" class="form-control" name="codigoPostal" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="NIF" class="form-label">NIF:</label>
                                        <input type="number" class="form-control" name="NIF" min="0" max="999999999">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="dataNascimento" class="form-label">Data nascimento:</label>
                                        <input type="date" class="form-control" name="dataNascimento" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="contacto" class="form-label">Contacto:</label>
                                        <input type="tel" id="contacto" class="form-control" name="contacto">
                                        <input type="hidden" name="contacto" id="contactoHidden">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="escola" class="form-label">Escola:</label>
                                        <input type="text" class="form-control" name="escola">
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
                                        <input type="number" class="form-control" name="ano" min="0" max="12" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="curso" class="form-label">Curso:</label>
                                        <input type="curso" class="form-control" name="curso">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="turma" class="form-label">Turma:</label>
                                        <input type="text" class="form-control" name="turma">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="modalidade" class="form-label">Modalidade:</label>
                                        <input type="text" class="form-control" name="modalidade">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="ano" class="form-label">Horas grupo:</label>
                                        <input type="number" class="form-control" name="horasGrupo" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="horasIndividual" class="form-label">Horas individuais:</label>
                                        <input type="number" class="form-control" name="horasIndividual" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="transporte" class="form-label">Transporte:</label>
                                        <select class="form-control" name="transporte" >
                                            <option value='1'>Sim</option>
                                            <option value='0'>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="mae" class="form-label">Mãe:</label>
                                        <input type="text" class="form-control" name="mae">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="maeTlm" class="form-label">Contacto mãe:</label>
                                        <input type="tel" class="form-control" id="maeTlm" name="maeTlm">
                                        <input type="hidden" name="maeTlm" id="maeTlmHidden">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="pai" class="form-label">Pai:</label>
                                        <input type="text" class="form-control" name="pai">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="paiTlm" class="form-label">Contacto pai:</label>
                                        <input type="tlm" class="form-control" id="paiTlm" name="paiTlm">
                                        <input type="hidden" name="paiTlm" id="paiTlmHidden">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="form-label">Disciplinas:</label>
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
                                <!-- <label>DISPONIBILIDADE:</label>
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addRowModal"
                                    style="padding: 3px;"
                                >
                                    DISPONIBILIDADE
                                </button> -->
                                <button type="submit" class="btn btn-primary">Criar aluno</button>
                            </div>
                        </form>
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
        </script>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

