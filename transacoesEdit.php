<?php
    //inclui o head que inclui as páginas de js necessárias, a base de dados e segurança da página
    include('./head.php'); 

    //variável para indicar à sideBar que página esta aberta para ficar como ativa na sideBar
    $estouEm = 13;

    //Verifica se o administrador tem acesso para aceder a esta pagina, caso contrario redericiona para a dashboard
    if ($_SESSION["tipo"] == "professor") {
        notificacao('warning', 'Não tens permissão para aceder a esta página.');
        header('Location: dashboard.php');
        exit();
    }

    //Obtem o id do admin via GET
    $idTransacao = $_GET['idTransacao'];
    $stmt = $con->prepare("SELECT * FROM transacoes WHERE id = ?");
    $stmt->bind_param("i", $idTransacao);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rowTransacao = $result->fetch_assoc();
    } else {
        notificacao('warning', 'ID de transação inválido.');
        header('Location: transacoes.php');
        exit();
    }
?>
    <title>Editar Transação | 4x1</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css'>
    <style>
        .fc-day-header[data-date="0"],
        .fc-day[data-date$="-0"] {
            display: none;
        }

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
            <div class="container">
                <form action="transacoesInserir?idTransacao=<?php echo $idTransacao ?>&op=edit" method="POST">
                    <div class="page-inner">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4" style="text-align: center;">
                            <div>
                                <h2 class="fw-bold mb-3">Editar Transação</h2>
                            </div>
                        </div>
                        <div class="container2">
                            <div class="form-section">
                                <div class="form-row">
                                    <div class="campo" style="flex: 0 0 64%;">
                                        <label>DESCRIÇÃO:</label>
                                        <input type="input" name="descricao" value="<?php echo $rowTransacao['descricao']; ?>">
                                    </div>
                                    <div class="campo" style="flex: 0 0 34%;">
                                        <label>VALOR:</label>
                                        <input type="number" step="0.01" min="0" name="valor" value="<?php echo $rowTransacao['valor']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-section">
                                <div class="form-row">
                                    <div class="campo" style="flex: 0 0 49%;">
                                        <label>CATEGORIA:</label>
                                        <select name="categoria" class="select-box">
                                            <?php
                                                $sql = "SELECT id, nome, tipo FROM categorias;";
                                                $result = $con->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        if ($rowTransacao['idCategoria'] = $row['id']) { ?>
                                                            <option selected value="<?php echo $row['id']; ?>"><?php echo $row['tipo']; ?> | <?php echo $row['nome']; ?></option>
                                                        <?php } 
                                                        else {
                                                        ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['tipo']; ?> | <?php echo $row['nome']; ?></option>
                                                        <?php }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar alterações</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php include('./endPage.php'); ?>
    </body>
    </html>

