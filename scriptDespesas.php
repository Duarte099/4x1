<?php
    $categoria = 3;
    include('./db/conexao.php');

    $sql1 = "SELECT * FROM despesas";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $sql2 = "SELECT * FROM despesas_meses WHERE idDespesa = {$row1['id']}";
            $result2 = $con->query($sql2);
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    if ($row2['mes'] == date('n')) {
                        $sql3 = "INSERT INTO `transacoes`('idCategoria', 'descricao', 'valor') VALUES (?, ?, ?);";
                        $result3 = $con->prepare($sql3);
                        if ($result3) {
                            $result3->bind_param("iss", $categoria, $row1['despesa'], $row1['valor']);
                            $result3->execute();
                        }
                    }
                }
            }
        }
    }
?>