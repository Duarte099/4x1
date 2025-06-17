<?php
    $auxLogin = true;
    
    function registrar_log($con, $acao, $detalhes) {
        if ($_SESSION['tipo'] == "administrador") {
            $tipo = "administrador";
        }
        elseif ($_SESSION['tipo'] == "professor") {
            $tipo = "professor";
        }
        else {
            $tipo = "utilizador";
        }
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = "INSERT INTO logs (idUtilizador, tipoUtilizador, acao, detalhes, ip) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('issss', $_SESSION['id'], $tipo, $acao, $detalhes, $ip);

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
?>