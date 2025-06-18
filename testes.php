<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 6;
?>
    <title>Registrar teste | 4x1</title>
    <style>
        .card {
            min-height: 100vh !important;
        }

        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
    <body>
        <div class="wrapper">
            <?php 
                include('./sideBar.php'); 
            ?>
            <div class="container">
                <div class="card">
                    <div class="col-12 col-md-10 col-lg-8 mx-auto">
                        <form action="testesInserir?op=save" method="POST">
                            <div class="container2">
                                <div class="page-inner">
                                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                                        <div>
                                            <h2 class="fw-bold mb-3">Registar teste</h2>
                                        </div>
                                    </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="nome" class="form-label">Aluno:</label>
                                                <input type="text" name="nome" class="form-control" list="datalistNomes" required>
                                                <datalist id='datalistNomes'>
                                                    <?php
                                                        //Obtem todas as referencias dos produtos que estao ativos
                                                        $sql = "SELECT id, nome FROM alunos WHERE estado = 1 ORDER BY nome ASC;";
                                                        $result = $con->query($sql);
                                                        if ($result->num_rows > 0) {
                                                        //Percorre todos os produtos e adiciona-os como opção na dataList
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option>$row[id] | $row[nome]</option>";
                                                        }
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="dia" class="form-label">Dia:</label>
                                                <input type="date" class="form-control" name="dia" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="disciplina" class="form-label">Disciplina:</label>
                                                <select class="form-control" name="disciplina">
                                                    <?php 
                                                    if ($_SESSION['tipo'] == "professor") {
                                                        $sql = "SELECT d.nome, d.id
                                                        FROM disciplinas AS d 
                                                        INNER JOIN professores_disciplinas AS pd ON d.id = pd.idDisciplina 
                                                        INNER JOIN professores AS p ON pd.idProfessor = p.id 
                                                        WHERE p.id = {$_SESSION['id']};";
                                                    }
                                                    elseif ($_SESSION['tipo'] == "administrador") {
                                                        $sql = "SELECT d.nome, d.id FROM disciplinas AS d;";
                                                    }
                                                    
                                                    $result = $con->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) { 
                                                        echo "<option value='disciplina_" . $row['id'] . "'>". $row['nome'] . "</option>";  
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Registrar teste</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            include('./endPage.php'); 
        ?>