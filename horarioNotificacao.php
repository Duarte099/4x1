<?php
    include("./db/conexao.php");

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    require "/home/xpt123/vendor/autoload.php";

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $url = 'https://api-4x1-whatsapp-production.up.railway.app/enviarMensagem';
    $mensagem = "";
    $notificacao = 0;
    $contacto = "";

    $sql1 = "SELECT * FROM alunos WHERE ativo = 1 AND notHorario = 1 AND id = 156;";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            if (!empty($row1['contacto']) && ($row1['ano'] > 4 || $row1['ano'] == 0)) {
                $contacto = $row1['contacto'];
                $mensagem = "*Olá!* 👋\n\nO teu horário foi atualizado.\n\nPara qualquer dúvida ou esclarecimento, por favor contacta a diretora pedagógica:\n📞 *966 539 965*\n\n📍 *Centro de Estudo 4x1*\nAlameda Arnaldo Gama nº 161\n4765-001 Vila das Aves\n✉️ geral@4x1.pt";
            }
            else if (!empty($row1['tlmMae'])){
                $contacto = $row1['tlmMae'];
                $mensagem = "*Olá!* 👋\n\nO horário do aluno *" . $row1['nome'] . "* foi atualizado.\n\nPara qualquer dúvida ou esclarecimento, por favor contacte a diretora pedagógica:\n📞 *966 539 965*\n\n📍 *Centro de Estudo 4x1*\nAlameda Arnaldo Gama nº 161\n4765-001 Vila das Aves\n✉️ geral@4x1.pt";
            }
            elseif (!empty($row1['tlmPai'])) {
                $contacto = $row1['tlmPai'];
                $mensagem = "*Olá!* 👋\n\nO horário do aluno *" . $row1['nome'] . "* foi atualizado.\n\nPara qualquer dúvida ou esclarecimento, por favor contacte a diretora pedagógica:\n📞 *966 539 965*\n\n📍 *Centro de Estudo 4x1*\nAlameda Arnaldo Gama nº 161\n4765-001 Vila das Aves\n✉️ geral@4x1.pt";
            }
            $contacto = str_replace("+", "", $contacto);

            // Configurações do Dompdf
            $options = new Options;
            $options->setChroot(__DIR__);
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $dompdf->setPaper("A4", "landscape");

            // Definir os dias e horas
            $salas = ['azul', 'branca', 'rosa', 'verde', 'bancada', 'biblioteca'];

            // Gerar o conteúdo HTML do PDF
            $html = '
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body {
                            font-family: DejaVu Sans, sans-serif;
                            font-size: 12px;
                        }
                        table {
                            border-collapse: collapse;
                            width: 100%;
                        }
                        th, td {
                            border: 1px solid black;
                            text-align: center;
                            padding: 8px;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        td {
                            height: 40px;
                        }
                    </style>
                </head>
                <body>

                <h2 style="text-align: center;">Horário Semanal</h2>

                <table>
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Segunda</th>
                            <th>Terça</th>
                            <th>Quarta</th>
                            <th>Quinta</th>
                            <th>Sexta</th>
                            <th>Hora</th>
                            <th>Sábado</th>
                        </tr>
                    </thead>
                    <tbody>';

                $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta'];

                $horasSabado = [
                    '09:00', '09:30',
                    '10:00', '10:30',
                    '11:00', '11:30',
                    '12:00', '12:30',
                    '13:00'
                ];

                $horasSemana = [
                    '14:00', '14:30',
                    '15:00', '15:30',
                    '16:00', '16:30',
                    '17:00', '17:30',
                    '18:00', '18:30',
                    '19:00', '19:30'
                ];

                // Primeira parte - só sábado
                foreach ($horasSabado as $hora) {
                    $html .= "<tr>";
                    $html .= "<td></td>"; // sem hora no início
                    foreach ($diasSemana as $_) {
                        $html .= "<td></td>"; // células vazias para dias da semana
                    }
                    $html .= "<td><strong>$hora</strong></td>"; // hora antes de sábado

                    // Coluna do sábado
                    $stmt = $con->prepare("SELECT h.id, h.sala, p.nome, d.nome as nomeDisc, p.nome as professor
                        FROM horario h
                        INNER JOIN professores p ON h.idProfessor = p.id
                        INNER JOIN horario_alunos ha ON ha.idHorario = h.id
                        INNER JOIN disciplinas d ON h.idDisciplina = d.id
                        WHERE ha.idAluno = ? AND h.dia = 'sabado' AND h.hora = ?");
                    $stmt->bind_param("is", $row1['id'], $hora);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $rowHorario = $result->fetch_assoc();
                        $sala = htmlspecialchars($rowHorario['sala']);
                        $disciplina = htmlspecialchars($rowHorario['nomeDisc']);
                        $professor = htmlspecialchars($rowHorario['professor']);

                        $html .= "<td style='background-color: #e8f5e9;'><strong>$disciplina</strong><br>$professor<br><em>Sala: $sala</em></td>";
                    } else {
                        $html .= "<td></td>";
                    }

                    $stmt->close();
                    $html .= "</tr>";
                }

                // Segunda parte - dias da semana
                foreach ($horasSemana as $hora) {
                    $html .= "<tr>";
                    $html .= "<td><strong>$hora</strong></td>";

                    foreach ($diasSemana as $dia) {
                        $stmt = $con->prepare("SELECT h.id, h.sala, p.nome, d.nome as nomeDisc, p.nome as professor
                            FROM horario h
                            INNER JOIN professores p ON h.idProfessor = p.id
                            INNER JOIN horario_alunos ha ON ha.idHorario = h.id
                            INNER JOIN disciplinas d ON h.idDisciplina = d.id
                            WHERE ha.idAluno = ? AND h.dia = ? AND h.hora = ?");
                        $stmt->bind_param("iss", $row1['id'], $dia, $hora);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $rowHorario = $result->fetch_assoc();
                            $sala = htmlspecialchars($rowHorario['sala']);
                            $disciplina = htmlspecialchars($rowHorario['nomeDisc']);
                            $professor = htmlspecialchars($rowHorario['professor']);

                            $html .= "<td style='background-color: #e8f5e9;'><strong>$disciplina</strong><br>$professor<br><em>Sala: $sala</em></td>";
                        } else {
                            $html .= "<td></td>";
                        }

                        $stmt->close();
                    }

                    $html .= "<td><strong>$hora</strong></td>"; // hora antes de sábado
                    $html .= "<td></td>"; // sábado vazio

                    $html .= "</tr>";
                }

                $html .= '
                    </tbody>
                </table>

                </body>
                </html>';

            // Carregar o HTML no Dompdf
            $dompdf->loadHtml($html);

            // Renderizar o PDF
            $dompdf->render();

            // Adicionar título ao PDF
            $dompdf->addInfo("Title", "Horário " . $row1['nome']);

            // Opcional: guardar o ficheiro no servidor
            $output = $dompdf->output();

            $filename = "horario_{$row1['nome']}_". date("d-m-y_H-i-s") . ".pdf";
            $filepath = __DIR__ . "/uploads/horarios/" . $filename;
            file_put_contents($filepath, $output);

            $data = [
                'number' => $contacto,
                'message' => $mensagem,
                'apiKey' => '5e_Z.4y5Zo$$',
                'fileUrl' => 'https://admin.4x1.pt/uploads/horarios/' . $filename
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
                    $sql3 = "UPDATE alunos SET notHorario = ? WHERE id = ?;";
                    $result3 = $con->prepare($sql3);
                    if ($result3) {
                        $notHorario = 0;
                        $result3->bind_param("di", $notHorario, $row1['id']);
                        $result3->execute();
                        $result3->close();
                    }
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
    $sql1 = "SELECT * FROM professores WHERE ativo = 1 AND notHorario = 1 AND defNotHorario = 1 AND id = 14;";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $contacto = str_replace("+", "", $row1['contacto']);
            $mensagem = "*Olá!* 👋\n\nO seu horário foi atualizado - https://admin.4x1.pt/horario.php .\n\nPara qualquer dúvida ou esclarecimento, por favor contacte a diretora pedagógica:\n📞 *966 539 965*\n\n📍 *Centro de Estudo 4x1*\nAlameda Arnaldo Gama nº 161\n4765-001 Vila das Aves\n✉️ geral@4x1.pt";
            
            $data = [
                'number' => $contacto,
                'message' => $mensagem,
                'apiKey' => '5e_Z.4y5Zo$$'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            if ($response === false) {
                // Se ocorreu erro na cURL
                notificacao('danger', 'Erro a enviar mensagem ao professor ' . $row1['nome'] . ': ' . curl_error($ch));
                $notificacao++;
            } else {
                // Se a requisição foi bem-sucedida, verificar código de status HTTP
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                if ($httpCode == 200) {
                    $sql3 = "UPDATE professores SET notHorario = ? WHERE id = ?;";
                    $result3 = $con->prepare($sql3);
                    if ($result3) {
                        $notHorario = 0;
                        $result3->bind_param("di", $notHorario, $row1['id']);
                        $result3->execute();
                        $result3->close();
                    }
                }
                else {
                    $notificacao++;
                    notificacao('danger', 'Erro a enviar mensagem ao professor ' . $row1['nome'] . ': ' . $httpCode);
                }
            }
            curl_close($ch);
        }
    }
    header('horario.php');
?>
