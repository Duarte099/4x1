<?php
    include('./db/conexao.php');
    require "/home/xpt123/vendor/autoload.php";

    use Dompdf\Dompdf;
    use Dompdf\Options;
    $url = 'https://api-4x1-whatsapp-production.up.railway.app/enviarMensagem';
    $mensagem = "";
    $contacto = "";
    $nomesMes = [1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    //RECIBO ALUNOS
    $sql1 = "SELECT a.nome, a.horasGrupo, a.horasIndividual, a.tlmMae, a.tlmPai, ar.id, ar.anoAluno, ar.ano, ar.mes, mensalidadeGrupo, mensalidadeIndividual, ar.transporte, ar.inscricao, horasRealizadasIndividual, horasRealizadasGrupo, horasBalancoIndividual, horasBalancoGrupo FROM alunos_recibo as ar INNER JOIN alunos as a ON ar.idAluno = a.id WHERE a.ativo = 1 AND ar.verificado = 1 AND ar.notificacao = 0";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $mensalidade = $row1['mensalidadeGrupo'] + $row1['mensalidadeIndividual'] + $row1['transporte'] + $row1['inscricao'];
            $horasRealizadasIndividualFormatado = decimalParaHoraMinutos(minutosToValor($row1['horasRealizadasIndividual']));
            $horasRealizadasGrupoFormatado = decimalParaHoraMinutos(minutosToValor($row1['horasRealizadasGrupo']));
            $balancoIndividualFormatado = decimalParaHoraMinutos(minutosToValor($row1['horasBalancoIndividual']));
            $balancoGrupoFormatado = decimalParaHoraMinutos(minutosToValor($row1['horasBalancoGrupo']));

            if (!empty($row1['tlmMae'])){
                $contacto = $row1['tlmMae'];
            }
            elseif (!empty($row1['tlmPai'])) {
                $contacto = $row1['tlmPai'];           
            }
            else{
                break;
            }
            $contacto = str_replace("+", "", $contacto);
            $mensagem = "*Olá!* 👋\n\nSegue em anexo o recibo do aluno *" . $row1['nome'] . "* relativo ao mês de *" . $nomesMes[$row1['mes']] . "* *" . $row1['ano'] . "*.";
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
                        <td><img src="https://admin.4x1.pt/images/LogoPreto4x1.png" height="50"></td>
                        <td class="center">
                            <h4>AVISO de PAGAMENTO relativo ao mês de <strong>{$nomesMes[$row1['mes']]} {$row1['ano']}</strong></h4>
                        </td>
                    </tr>
                </table>
            
                <br>
            
                <table>
                    <tr>
                        <td class="bold">Aluno: {$row1['nome']}</td>
                        <td class="bold" style="text-align: right;">Ano: {$row1['anoAluno']}º</td>
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
                        <td class="mensalidade" rowspan="3">{$row1['mensalidadeGrupo']}€</td>
                    </tr>
                    <tr>
                        <td class="bold">Horas Realizadas</td>
                        <td class="bold">Balanço Das Horas</td>
                    </tr>
                    <tr>
                        <td>{$horasRealizadasGrupoFormatado}</td>
                        <td>{$balancoGrupoFormatado}</td>
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
                        <td class="mensalidade" rowspan="3">{$row1['mensalidadeIndividual']}€</td>
                    </tr>
                    <tr>
                        <td class="bold">Horas Realizadas</td>
                        <td class="bold">Balanço Das Horas</td>
                    </tr>
                    <tr>
                        <td>{$horasRealizadasIndividualFormatado}</td>
                        <td>{$balancoIndividualFormatado}</td>
                    </tr>
                </table>
            HTML;
            }
            
            // TABELA TOTAL
            $html .= <<<HTML
                <table class="total-table">
            HTML;
            if ($row1['inscricao'] > 0) {
                $html .= "<tr><td class='label'>Inscrição</td><td>{$row1['inscricao']}€</td></tr>";
            }
            if ($row1['transporte'] > 0) {
                $html .= "<tr><td class='label'>Transporte</td><td>{$row1['transporte']}€</td></tr>";
            }
            
            $html .= "<tr><td class='label'>Total:</td><td>{$mensalidade}€</td></tr>";
            
            $html .= <<<HTML
                </table>
            
                <div class="small-text">
                    <p>O pagamento deve ser efetuado 7 dias após receber este recibo, após o qual será aplicada uma coima de 10 €.</p>
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
            $filepath = "/home/xpt123/admin/uploads/recibos/" . $filename;
            file_put_contents($filepath, $output);

            $fileData = new CURLFile($filepath);
            //'number' => $contacto,
            $data = [
                'number'=> '351916985740',
                'message' => $mensagem,
                'apiKey' => '5e_Z.4y5Zo$$',
                'fileUrl' => 'https://admin.4x1.pt/uploads/recibos/' . $filename
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            if ($response === false) {
                // Se ocorreu erro na cURL
                echo 'Erro a enviar mensagem ao aluno ' . $row1['nome'] . ': ' . curl_error($ch);
            } else {
                // Se a requisição foi bem-sucedida, verificar código de status HTTP
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                if ($httpCode == 200) {
                    $sql = "UPDATE alunos_recibo SET notificacaco = 1 WHERE id = ?";
                    $result = $con->prepare($sql);
                    if ($result) {
                        $result->bind_param("i", $row1['id']);
                        $result->execute();
                        $result->close();
                    }
                }
                else {
                    echo 'Erro a enviar mensagem ao aluno ' . $row1['nome'] . ': ' . $httpCode;
                }
            }
            
            curl_close($ch);
        }
    }
?>