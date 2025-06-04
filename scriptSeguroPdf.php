<?php 
    $auxLogin = true;
    $cronjob = true;
    include('/home/xpt123/admin/db/conexao.php');
    $aux1 = 1;
    $sql = "SELECT nome, DATE_FORMAT(dataNascimento, '%d.%m.%Y') as dataNascimento FROM alunos WHERE ano >= 1 AND ano <= 4 AND ativo = 1;";
    $result1 = $con->query($sql);
    $alunos_1ciclo = "";
    
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $alunos_1ciclo .= "
            <tr>
                <td>{$aux1}</td>
                <td>{$row['nome']}</td>
                <td>{$row['dataNascimento']}</td>
            </tr>";
            $aux1++;
        }
    }
    
    $aux2 = 1;
    $sql = "SELECT nome, DATE_FORMAT(dataNascimento, '%d.%m.%Y') as dataNascimento FROM alunos WHERE ano>4 AND ano<7 AND ativo = 1;";
    $result1 = $con->query($sql);
    $alunos_2ciclo = "";
    
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $alunos_2ciclo .= "
            <tr>
                <td>{$aux2}</td>
                <td>{$row['nome']}</td>
                <td>{$row['dataNascimento']}</td>
            </tr>";
            $aux2++;
        }
    }
    
    $aux3 = 1;
    $sql = "SELECT nome, DATE_FORMAT(dataNascimento, '%d.%m.%Y') as dataNascimento FROM alunos WHERE ano>6 AND ano<=9 AND ativo = 1;";
    $result1 = $con->query($sql);
    $alunos_3ciclo = "";
    
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $alunos_3ciclo .= "
            <tr>
                <td>{$aux3}</td>
                <td>{$row['nome']}</td>
                <td>{$row['dataNascimento']}</td>
            </tr>";
            $aux3++;
        }
    }
    
    $aux4 = 1;
    $sql = "SELECT nome, DATE_FORMAT(dataNascimento, '%d.%m.%Y') as dataNascimento FROM alunos WHERE ano > 9 AND ano <= 12 AND ativo = 1;";
    $result1 = $con->query($sql);
    $alunos_secundario = "";
    
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $alunos_secundario .= "
            <tr>
                <td>{$aux4}</td>
                <td>{$row['nome']}</td>
                <td>{$row['dataNascimento']}</td>
            </tr>";
            $aux4++;
        }
    }
    
    $aux5 = 1;
    $sql = "SELECT nome, DATE_FORMAT(dataNascimento, '%d.%m.%Y') as dataNascimento FROM alunos WHERE ano = 0 AND ativo = 1;";
    $result1 = $con->query($sql);
    $alunos_universidade = "";
    
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $alunos_universidade .= "
            <tr>
                <td>{$aux5}</td>
                <td>{$row['nome']}</td>
                <td>{$row['dataNascimento']}</td>
            </tr>";
            $aux5++;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/gutenberg-css@0.6">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ccc;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            background-color: #ddd;
            padding: 5px;
        }
    </style>
</head>
<body>
    
    <h1>LISTAGEM DOS ALUNOS</h1>
    
    <table>
        <tbody>
            <tr>
                <td colspan="3" class="section-title">1º CICLO</td>
            </tr>
            <?php echo $alunos_1ciclo; ?>
            <tr>
                <td colspan="3" class="section-title">2º CICLO</td>
            </tr>
            <?php echo $alunos_2ciclo; ?>
            <tr>
                <td colspan="3" class="section-title">3º CICLO</td>
            </tr>
            <?php echo $alunos_3ciclo; ?>
            <tr>
                <td colspan="3" class="section-title">SECUNDÁRIO</td>
            </tr>
            <?php echo $alunos_secundario; ?>
            <tr>
                <td colspan="3" class="section-title">UNIVERSIDADE</td>
            </tr>
            <?php echo $alunos_universidade; ?>
        </tbody>
    </table>
    
</body>
</html>