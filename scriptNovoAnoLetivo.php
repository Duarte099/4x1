<?php
    include('/home/xpt123/admin/db/conexao.php');

    // INCREMENTAR NOVO ANO
    $sql1 = "SELECT * FROM alunos WHERE ativo = 1";
    $result1 = $con->query($sql1);
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            // Inserir Pagamento com a data do mês anterior
            $sql4 = "UPDATE alunos SET ano = ? WHERE id = ?";
            $result4 = $con->prepare($sql4);
            $ano = $row1["ano"] + 1;
            if ($result4) {
                $result4->bind_param("ii", $ano, $row1['id']);
                $result4->execute();
            }
        }
    }
?>