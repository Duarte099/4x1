<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 1;

    $stmt = $con->prepare('SELECT defNotHorario FROM professores WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute(); 
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowProf = $result->fetch_assoc();
        if ($rowProf['defNotHorario'] == 1) {
            $notificacaoHorario = "checked";
        }
        else {
            $notificacaoHorario = "";
        }
    }

    $cronjob = [];
    $stmt = $con->prepare('SELECT id, estado FROM cronjobs');
    $stmt->execute(); 
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            if ($row['estado'] == 1) {
                $cronjob[$row['id']] = "checked";
            }
            else {
                $cronjob[$row['id']] = "";
            }
        }
    }
?>
    <title>Definições | 4x1</title>
    <style>
        h1 {
            text-align: center;
            color: #343a40;
        }

        table {
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            border: 2px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #dee2e6;
            /* padding: 10px; */
            text-align: center;
        }

        th {
            padding: 10px;
            background-color: #f2f2f2;
            color: #343a40;
        }

        .highlight {
            background-color: #f8f9fa;
        }

        .special {
            background-color: #f0f0f0;
        }

        .container2 {
            background: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        /* Formulário estruturado com flexbox */
        .form-section {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            gap: 10px;
        }

        .campo {
            flex: 1;
            min-width: 150px;
        }

        .campo label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Ajusta a largura e altura para ocupar o TD inteiro */
        #selectgroup-item {
            display: block;
            width: 100%;
            height: 100%;
            padding: 0; /* Remove qualquer padding extra */
            margin: 0;
        }

        /* Oculta a checkbox padrão */
        #electgroup-input {
            display: none;
        }

        /* Estiliza o botão (span) para ocupar todo o espaço do TD */
        .selectgroup-button {
            display: block;
            width: 100%;
            height: 100%;
            text-align: center;
            background-color: white; /* Cor padrão */
            border: 1px solid #ccc;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
            box-sizing: border-box; /* Garante que o padding não afete o tamanho total */
        }

        /* Muda a cor quando a checkbox está selecionada */
        #selectgroup-input:checked + .selectgroup-button {
            background-color: blue;
            color: white;
            font-weight: bold;
        }

        select {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 4px;
            font-size: 16px;
            color: #343a40;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            .campo {
                flex: 0 0 100%;
            }
        }
    </style>
</head>
    <body>
        <div class="wrapper">
            <?php 
                include('./sideBar.php'); 
            ?>
            <form action="definicoesInserir.php?op=save" method="POST">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                        <div>
                            <h2 class="fw-bold mb-3">Definições</h2>
                        </div>
                    </div>
                    <div class="container2" style="max-width: 650px;">
                        <div class="form-section">
                            <h4 style="margin-bottom: 1rem; color: #343a40;">Definições</h4>
                            <form action="definicoes.php" method="POST">
                                <?php if ($_SESSION['tipo'] == "professor") {?>
                                    <div class="form-row">
                                        <div class="campo" style="flex: 1 1 100%;">
                                            <label class="form-check-label" style="display: flex; align-items: center;">
                                                <input class="form-check-input" type="checkbox" name="notificacoesHorario" style="margin-right: 10px;" <?php echo $notificacaoHorario; ?>>
                                                Receber notificações de novo horário por whatsapp
                                            </label>
                                        </div>
                                    </div>
                                <?php } if ($_SESSION['tipo'] == "administrador") { ?>
                                    <div class="form-row">
                                        <div class="campo" style="flex: 1 1 100%;">
                                            <label class="form-check-label" style="display: flex; align-items: center;">
                                                <input class="form-check-input" type="checkbox" name="cronjob_1" style="margin-right: 10px;" <?php echo $cronjob[1]; ?>>
                                                Enviar email todos os meses para o seguro com a lista dos alunos.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="campo" style="flex: 1 1 100%;">
                                            <label class="form-check-label" style="display: flex; align-items: center;">
                                                <input class="form-check-input" type="checkbox" name="cronjob_2" style="margin-right: 10px;" <?php echo $cronjob[2]; ?>>
                                                Gerar recibos de todos os alunos e professores ativos e envia-los para os alunos.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="campo" style="flex: 1 1 100%;">
                                            <label class="form-check-label" style="display: flex; align-items: center;">
                                                <input class="form-check-input" type="checkbox" name="cronjob_3" style="margin-right: 10px;" <?php echo $cronjob[3]; ?>>
                                                Alterar o ano dos alunos todos os anos no fim do ano letivo.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="campo" style="flex: 1 1 100%;">
                                            <label class="form-check-label" style="display: flex; align-items: center;">
                                                <input class="form-check-input" type="checkbox" name="cronjob_4" style="margin-right: 10px;" <?php echo $cronjob[4]; ?>>
                                                Inserir transações de despesas
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="form-row" style="justify-content: flex-end; margin-top: 1.5rem;">
                                    <button type="submit" class="btn btn-primary" style="padding: 8px 18px; border-radius: 6px;">Guardar Definições</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php 
            include('./endPage.php'); 
        ?>
    </body>
</html>