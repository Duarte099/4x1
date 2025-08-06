<?php
    include('./db/conexao.php'); 

    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard');
        exit();
    }

    if (isset($_GET['op']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_GET['op'] == 'save') {
            $sql1 = "SELECT pr.id, p.nome, valorParcial1Ciclo, valorParcial2Ciclo, valorParcial3Ciclo, valorParcialSecundario, valorParcialUniversidade FROM professores as p INNER JOIN professores_recibo as pr ON pr.idProfessor = p.id WHERE pr.verificado = 1 AND pr.pago = 0;";
            $result1 = $con->query($sql1);
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    $sql = "UPDATE professores_recibo SET pago = 1 WHERE id = " . $row['id'];
                    $result = $con->prepare($sql);
                    if ($result) {
                        if ($result->execute()) {
                            notificacao('success', 'Pagamentos registrados com sucesso!');
                            transacao($con, 4, "Pagamento do professor " . $row1['nome'], $row1['valorParcial1Ciclo'] + $row1['valorParcial2Ciclo'] + $row1['valorParcial3Ciclo'] + $row1['valorParcialSecundario'] + $row1['valorParcialUniversidade']);
                        }
                        else {
                            notificacao('danger', 'Erro ao registrar pagamentos: ' . $result->error);
                        }

                        $result->close();
                    }
                    else {
                        notificacao('danger', 'Erro ao registrar pagamentos: ' . $result->error);
                    }
                }
            }

            header('Location: recibosProfessores');
        }
        else {
            notificacao('warning', 'Operação inválida.');
            header('Location: dashboard');
            exit();
        }
    }
    else {
        header('Location: dashboard');
        exit();
    }
?>