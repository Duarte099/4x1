<?php
    $auxLogin = true;

    function adminPermissions($con, $codModule, $perm){
        $idAdminPerms = $_SESSION['id'];

        switch ($perm) {
            case "view":
                $sql = "SELECT pView, cod FROM modulos INNER JOIN administrador_modulos ON modulos.id = administrador_modulos.idModule WHERE cod = '$codModule' AND idprofessor = $idAdminPerms;";
                $result = $con->query($sql);
                if ($result->num_rows > 0) { 
                    $row = $result->fetch_assoc();
                    return $row['pView'];
                }
                break;
            case "insert":
                $sql = "SELECT pInsert, cod FROM modulos INNER JOIN administrador_modulos ON modulos.id = administrador_modulos.idModule WHERE cod = '$codModule' AND idprofessor = $idAdminPerms;";
                $result = $con->query($sql);
                if ($result->num_rows > 0) { 
                    $row = $result->fetch_assoc();
                    return $row['pInsert'];
                }
                break;
            case "update":
                $sql = "SELECT pUpdate, cod FROM modulos INNER JOIN administrador_modulos ON modulos.id = administrador_modulos.idModule WHERE cod = '$codModule' AND idprofessor = $idAdminPerms;";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {    
                    $row = $result->fetch_assoc();
                    return $row['pUpdate'];
                }
                break;
            case "delete":
                $sql = "SELECT pDelete, cod FROM modulos INNER JOIN administrador_modulos ON modulos.id = administrador_modulos.idModule WHERE cod = '$codModule' AND idprofessor = $idAdminPerms;";
                $result = $con->query($sql);
                if ($result->num_rows > 0) {    
                    $row = $result->fetch_assoc();
                    return $row['pDelete'];
                }
                break;
        }
    }

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
?>