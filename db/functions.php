<?php
    $auxLogin = true;
    
    function registrar_log($user, $mensagem) {
        include('./db/conexao.php');

        if ($user == "admin") {
            // Inserir na tabela de logs
            $query = "INSERT INTO administrador_logs (idAdministrador, logFile) VALUES (?, ?)";
        }
        elseif ($user == "prof") {
            $query = "INSERT INTO professores_logs (idProfessor, logFile) VALUES (?, ?)";
        }

        $stmt = $con->prepare($query);
        $stmt->bind_param('is', $_SESSION['id'], $mensagem);

        $stmt->execute();
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
?>