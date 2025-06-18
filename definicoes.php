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
    <title>4x1 | Definições</title>
    <style>
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
            <form action="definicoesInserir?op=save" method="POST">
                <div class="page-inner">
                    <div class="d-flex justify-content-between align-items-center pt-2 pb-4" style="text-align: center;">
                        <div>
                            <h2 class="fw-bold mb-3 mb-md-0">Definições</h2>
                        </div>
                    </div>
                    <div class="container2" style="max-width: 650px;">
                        <div class="form-section">
                            <h4 style="margin-bottom: 1rem; color: #343a40;">Definições</h4>
                            <form action="definicoes" method="POST">
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