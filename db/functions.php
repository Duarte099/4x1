<?php
    $auxLogin = true;
    
    function registrar_log($con, $acao, $detalhes) {
        $query = "INSERT INTO logs (idUtilizador, tipoUtilizador, acao, detalhes, ip) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('issss', $_SESSION['id'], $_SESSION['tipo'], $acao, $detalhes, $_SERVER['REMOTE_ADDR']);

        $stmt->execute();
    }

    function gerar_detalhes_alteracoes($originais, $novos, $campos_excluir = []) {
        $alteracoes = [];

        foreach ($novos as $chave => $valorNovo) {
            if (in_array($chave, $campos_excluir)) continue; // ignora campos que não interessam

            if (isset($originais[$chave]) && $originais[$chave] != $valorNovo) {
                $valorAntigo = $originais[$chave];
                $alteracoes[] = "$chave: '$valorAntigo' => '$valorNovo'";
            }
        }

        return implode(", ", $alteracoes);
    }

    function notificacao($tipo, $mensagem) {
        $_SESSION['notificacao'] = [
            'tipo' => $tipo,
            'mensagem' => $mensagem
        ];
    }

    function transacao($con, $idCategoria, $descricao, $valor) {
        $query = "INSERT INTO transacoes (idCategoria, descricao, valor) VALUES (?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('isd', $idCategoria, $descricao, $valor);

        $stmt->execute();
    }

    function minutosToValor($minutos){
        // Conversão para horas e minutos
        $horas = intdiv($minutos, 60);

        $minutosRestantes = $minutos % 60;

        // Conversão para horas decimais
        return $minutos / 60;
    }

    function decimalParaHoraMinutos($horasDecimais) {
        $horas = floor($horasDecimais); // Parte inteira = horas
        $minutos = round(($horasDecimais - $horas) * 60); // Parte decimal convertida em minutos

        // Corrigir caso raro em que minutos = 60 (ex: 1.999 → 2h 0min)
        if ($minutos == 60) {
            $horas += 1;
            $minutos = 0;
        }

        // Retorna como string no formato hh:mm
        return sprintf('%d.%02d', $horas, $minutos);
    }

    function sendEmail($destinatario, $assunto, $corpo, $linkFicheiro = null) {
        // Carregar PHPMailer
        require_once __DIR__ . '/../PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->Port = 587;
            $mail->Host = "mail.4x1.pt";
            $mail->Username = "geral@4x1.pt";
            $mail->Password = "nTgY}w0_fBj}";

            // Configurações do remetente
            $mail->setFrom("geral@4x1.pt", "4x1 | CENTRO DE ESTUDO");
            $mail->addReplyTo("geral@4x1.pt", "4x1 | CENTRO DE ESTUDO");

            // Destinatário
            $mail->addAddress($destinatario);

            // Configurações da mensagem
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $assunto;
            $mail->Body = $corpo;

            // Adicionar anexo se fornecido
            if ($linkFicheiro && file_exists($linkFicheiro)) {
                $mail->addAttachment($linkFicheiro);
            }

            // Enviar email
            $mail->send();
            return true;

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
?>