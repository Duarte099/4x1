<?php
    include('./admin/db/conexao.php');
    require __DIR__ . "/vendor/autoload.php";

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $url = 'http://localhost:3000/enviarMensagem';
    $mensagem = "";
    $notificacao = 0;
    $contacto = "";

    $dataAnterior = new DateTime('first day of last month');
    $mes = $dataAnterior->format('n');
    $ano = $dataAnterior->format('Y');
    $nomesMes = [1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    $nomeMes = $nomesMes[$mes];

    //RECIBO ALUNOS
    $sql1 = "SELECT * FROM alunos WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows >= 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $mensalidade = 0;
            $mensalidadeGrupo = 0;
            $mensalidadeIndividual = 0;
            $valorInscricao = 0;
            $valorTransporte = 0;

            if (!empty($row1['tlmMae'])){
                $contacto = $row1['tlmMae'];
            }
            elseif (!empty($row1['tlmPai'])) {
                $contacto = $row1['tlmPai'];           
            }
            $contacto = str_replace("+", "", $contacto);
            $mensagem = "*Olá!* 👋\n\nSegue em anexo o recibo do aluno *" . $row1['nome'] . "* relativo ao mês de *" . $nomeMes . "* *" . $ano . "*.";

            //Mensalidades grupo e individual
            if ($row1['horasGrupo'] > 0) {
                $result6 = $con->prepare('SELECT mensalidadeHorasGrupo FROM mensalidade WHERE ano = ? AND horasGrupo = ?');
                $result6->bind_param('ii', $row1['ano'], $row1['horasGrupo']);
                $result6->execute();
                $result6 = $result6->get_result();
                if ($result6->num_rows > 0) {
                    $row6 = $result6->fetch_assoc();
                    $mensalidadeGrupo = $row6['mensalidadeHorasGrupo'];
                }
            }
            if ($row1['horasIndividual'] > 0) {
                $result6 = $con->prepare('SELECT mensalidadeIndividual FROM mensalidade WHERE ano = ? AND horasIndividual = ?');
                $result6->bind_param('ii', $row1['ano'], $row1['horasIndividual']);
                $result6->execute();
                $result6 = $result6->get_result(); 
                if ($result6->num_rows > 0) {
                    $row6 = $result6->fetch_assoc();
                    $mensalidadeIndividual = $row6['mensalidadeIndividual'];
                }
            }

            //Horas Grupo Realizadas
            $horasRealizadasGrupo = 0;
            $sql2 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 0";
            $result2 = $con->query($sql2);
            if ($result2->num_rows >= 0) {
                $row2 = $result2->fetch_assoc();
                $horasRealizadasGrupo = $row2['horasRealizadas'];
            }

            //Horas Individuais Realizadas
            $horasRealizadasIndividual = 0;
            $sql3 = "SELECT COUNT(*) AS horasRealizadas FROM alunos_presenca WHERE idAluno = " . (int) $row1['id'] . " AND MONTH(dia) = $mes AND YEAR(dia) = $ano AND individual = 1";
            $result3 = $con->query($sql3);
            if ($result3->num_rows >= 0) {
                $row3 = $result3->fetch_assoc();
                $horasRealizadasIndividual = $row3['horasRealizadas'];
            }

            //Balanço Grupo
            $balancoGrupo = $row1['balancoGrupo'] + ($row1['horasGrupo'] - $horasRealizadasGrupo);

            //Balanço Individual
            $balancoIndividual = $row1['balancoIndividual'] + ($row1['horasIndividual'] - $horasRealizadasIndividual);

            //Valor do transporte
            if ($row1['transporte'] == 1) {
                //Valores pagamento transporte
                $sql = "SELECT * FROM valores_pagamento WHERE id = 7;";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $valorTransporte = $row["valor"];
                }
            }

            //Valor da inscrição
            if(!empty($row1['dataInscricao'])){
                $mesInscricao = date('Y-m', strtotime($row1['dataInscricao']));
                if ($mesInscricao == date('Y-m')) {
                    //Valores pagamento
                    $sql = "SELECT * FROM valores_pagamento WHERE id = 9;";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $valorInscricao = $row["valor"];
                    }
                }
            }

            $mensalidade = $mensalidadeGrupo + $mensalidadeIndividual + $valorInscricao + $valorTransporte;

            //Inserir Recibo
            $sql4 = "INSERT INTO alunos_recibo (idAluno, anoAluno, packGrupo, horasRealizadasGrupo, horasBalancoGrupo, packIndividual, horasRealizadasIndividual, horasBalancoIndividual, ano, mes, mensalidadeGrupo, mensalidadeIndividual, inscricao, transporte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result4 = $con->prepare($sql4);
            if ($result4) {
                $result4->bind_param("iiiddiddiiiiii", $row1['id'], $row1['ano'], $row1['horasGrupo'], $horasRealizadasGrupo, $balancoGrupo, $row1['horasIndividual'], $horasRealizadasIndividual, $balancoIndividual, $ano, $mes, $mensalidadeGrupo, $mensalidadeIndividual, $valorInscricao, $valorTransporte);
                $result4->execute();
            }

            //Atualizar balanço do aluno
            $sql5 = "UPDATE alunos SET balancoGrupo = ?, balancoIndividual = ? WHERE id = ?";
            $result5 = $con->prepare($sql5);
            if ($result5) {
                $result5->bind_param("ddi", $balancoGrupo, $balancoIndividual, $row1['id']);
                $result5->execute();
            }

            // Configurações do Dompdf
            $options = new Options;
            $options->setChroot(__DIR__);
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $dompdf->setPaper("A4", "landscape");

            // Definir os dias e horas
            $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta'];
            $salas = ['azul', 'branca', 'rosa', 'verde', 'bancada', 'biblioteca'];
            $horas = ['14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'];

            $html = <<<HTML
            <!DOCTYPE html>
            <html lang="pt">
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 13px;
                    }
                    .container {
                        width: 600px;
                        margin: auto;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .center {
                        text-align: center;
                    }
                    .bold {
                        font-weight: bold;
                    }
                    .top-table td {
                        padding: 5px;
                    }
                    .horas-table {
                        border: 1px solid black;
                        margin-bottom: 15px;
                    }
                    .horas-table td {
                        border: 1px solid black;
                        padding: 6px;
                        text-align: center;
                    }
                    .categoria {
                        font-weight: bold;
                        background-color: #f9f9f9;
                        text-align: left;
                        width: 30%;
                    }
                    .mensalidade {
                        font-weight: bold;
                        width: 20%;
                    }
                    .total-table td {
                        padding: 6px 10px;
                        font-weight: bold;
                        text-align: right;
                        border: 1px solid black;
                    }
                    .total-table .label {
                        text-align: left;
                        width: 80%;
                    }
                    .small-text {
                        font-size: 11px;
                        margin-top: 10px;
                    }
                    .footer {
                        margin-top: 15px;
                        font-size: 11px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
            <div class="container">
                <table class="top-table">
                    <tr>
                        <td><img src="./images/LogoPreto4x1.png" height="50"></td>
                        <td class="center">
                            <h4>AVISO de PAGAMENTO relativo ao mês de <strong>{$nomeMes} {$ano}</strong></h4>
                        </td>
                    </tr>
                </table>
            
                <br>
            
                <table>
                    <tr>
                        <td class="bold">Aluno: {$row1['nome']}</td>
                        <td class="bold" style="text-align: right;">Ano: {$row1['ano']}º</td>
                    </tr>
                </table>
            
                <br>
            HTML;
            
            // HORAS DE GRUPO
            if ($row1['horasGrupo'] > 0) {
                $html .= <<<HTML
                <table class="horas-table">
                    <tr>
                        <td class="categoria" rowspan="3">Horas Grupo<br><span style="font-size:12px;">{$row1['horasGrupo']} Horas</span></td>
                        <td colspan="2" class="bold">HORAS CONTABILIZADAS</td>
                        <td class="mensalidade" rowspan="3">{$mensalidadeGrupo}€</td>
                    </tr>
                    <tr>
                        <td class="bold">Horas Realizadas</td>
                        <td class="bold">Balanço Das Horas</td>
                    </tr>
                    <tr>
                        <td>{$horasRealizadasGrupo}</td>
                        <td>{$balancoGrupo}</td>
                    </tr>
                </table>
            HTML;
            }
            
            // HORAS INDIVIDUAIS
            if ($row1['horasIndividual'] > 0) {
                $html .= <<<HTML
                <table class="horas-table">
                    <tr>
                        <td class="categoria" rowspan="3">Horas Individuais<br><span style="font-size:12px;">{$row1['horasIndividual']} Horas</span></td>
                        <td colspan="2" class="bold">HORAS CONTABILIZADAS</td>
                        <td class="mensalidade" rowspan="3">{$mensalidadeIndividual}€</td>
                    </tr>
                    <tr>
                        <td class="bold">Horas Realizadas</td>
                        <td class="bold">Balanço Das Horas</td>
                    </tr>
                    <tr>
                        <td>{$horasRealizadasIndividual}</td>
                        <td>{$balancoIndividual}</td>
                    </tr>
                </table>
            HTML;
            }
            
            // TABELA TOTAL
            $html .= <<<HTML
                <table class="total-table">
            HTML;
            if ($valorInscricao > 0) {
                $html .= "<tr><td class='label'>Inscrição</td><td>{$valorInscricao}€</td></tr>";
            }
            if ($valorTransporte > 0) {
                $html .= "<tr><td class='label'>Transporte</td><td>{$valorTransporte}€</td></tr>";
            }
            
            $html .= "<tr><td class='label'>Total:</td><td>{$mensalidade}€</td></tr>";
            
            $html .= <<<HTML
                </table>
            
                <div class="small-text">
                    <p>O pagamento deve ser efetuado até ao dia 8, após o qual será aplicada uma coima de 10 €.</p>
                    <p>Qualquer dúvida e/ou esclarecimento por favor contacte a diretora pedagógica - 966 915 259.</p>
                </div>
            
                <div class="footer">
                    <strong>CENTRO DE ESTUDO 4x1</strong> | Alameda Arnaldo Gama nº 161, 4795-001 Vila das Aves | Email: geral@4x1.pt
                </div>
            </div>
            </body>
            </html>
            HTML;

            // Carregar o HTML no Dompdf
            $dompdf->loadHtml($html);

            // Renderizar o PDF
            $dompdf->render();

            // Adicionar título ao PDF
            $dompdf->addInfo("Title", "Recibo " . $row1['nome']);

            // Opcional: guardar o ficheiro no servidor
            $output = $dompdf->output();

            $filename = "recibo_{$row1['nome']}_". date("d-m-y_H-i-s") . ".pdf";
            $filepath = __DIR__ . "/uploads/recibos/" . $filename;
            file_put_contents($filepath, $output);

            $fileData = new CURLFile($filepath);

            $data = [
                'number' => $contacto,
                'message' => $mensagem,
                'apiKey' => '5e_Z.4y5Zo$$',
                'file' => $fileData
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            if ($response === false) {
                // Se ocorreu erro na cURL
                notificacao('danger', 'Erro a enviar mensagem ao aluno ' . $row1['nome'] . ': ' . curl_error($ch));
                $notificacao++;
            } else {
                // Se a requisição foi bem-sucedida, verificar código de status HTTP
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                if ($httpCode == 200) {
                    
                }
                else {
                    $notificacao++;
                    notificacao('danger', 'Erro a enviar mensagem ao aluno ' . $row1['nome'] . ': ' . $httpCode);
                }
            }
            
            curl_close($ch);
        }
        if ($notificacao == 0) {
            notificacao('success', 'Alunos notificados com sucesso!');
        }
    }

    $sql = "SELECT valor FROM valores_pagamento;";
    $result = $con->query($sql);
    $valores = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $valores[] = $row['valor'];
        }
    }

    function minutosToValor($minutos){
        // Conversão para horas e minutos
        $horas = intdiv($minutos, 60);

        $minutosRestantes = $minutos % 60;

        // Conversão para horas decimais
        return $minutos / 60;
    }

    //RECIBO PROFESSORES
    $sql1 = "SELECT * FROM professores WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows >= 0) {
        while ($row1 = $result1->fetch_assoc()) {
            //Horas dadas 1 Ciclo
            $valorParcial1Ciclo = 0;
            $horasDadas1Ciclo = 0;
            $sql = "SELECT duracao
                    FROM alunos_presenca AS p
                    INNER JOIN alunos AS a ON a.id = p.idAluno
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano >= 1 AND a.ano <= 4 AND p.idProfessor = {$row1['id']};";
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
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 4 AND a.ano < 7 AND p.idProfessor = {$row1["id"]};";
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
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 6 AND a.ano <= 9 AND p.idProfessor = {$row1["id"]};";
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
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano > 9 AND p.idProfessor = {$row1["id"]};";
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
                    WHERE MONTH(p.dia) = $mes AND YEAR(p.dia) = $ano AND a.ano = 0 AND p.idProfessor = {$row1["id"]};";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $horasDadasUniversidade = $horasDadasUniversidade + $row["duracao"];
                }
                $horasDadasUniversidade = minutosToValor($horasDadasUniversidade);
                $valorParcialUniversidade = $horasDadasUniversidade * $valores[4];
            }
    
            $total = $valorParcial1Ciclo + $valorParcial2Ciclo + $valorParcial3Ciclo + $valorParcialSecundario + $valorParcialUniversidade; 
            
            // //Inserir Recibo
            // $sql4 = "INSERT INTO `professores_recibo`(`idProfessor`, `horasDadas1Ciclo`, `valorUnitario1Ciclo`, `valorParcial1Ciclo`, `horasDadas2Ciclo`, `valorUnitario2Ciclo`, `valorParcial2Ciclo`, `horasDadas3Ciclo`, `valorUnitario3Ciclo`, `valorParcial3Ciclo`, `horasDadasSecundario`, `valorUnitarioSecundario`, `valorParcialSecundario`, `horasDadasUniversidade`, `valorUnitarioUniversidade`, `valorParcialUniversidade`, `total`, `ano`, `mes`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            // $result4 = $con->prepare($sql4);
            // if ($result4) {
            //     $result4->bind_param("iiiiiiiiiiiiiiiiiii", $row1['id'], $horasDadas1Ciclo, $valores[0], $valorParcial1Ciclo, $horasDadas2Ciclo, $valores[1], $valorParcial2Ciclo, $horasDadas3Ciclo, $valores[2], $valorParcial3Ciclo,  $horasDadasSecundario, $valores[3], $valorParcialSecundario, $horasDadasUniversidade, $valores[4], $valorParcialUniversidade, $total, $ano, $mes);
            //     $result4->execute();
            // }
        }
    }
?>