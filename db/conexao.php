<?php
    global $auxLogin; // Permite acesso à variável externa

    // Configurações de conexão ao banco de dados
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = '4x1';
    // $DATABASE_HOST = '4x1.pt';
    // $DATABASE_USER = 'xpt123_4x1';
    // $DATABASE_PASS = 'nTgY}w0_fBj}';
    // $DATABASE_NAME = 'xpt123_4x1';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    $con->set_charset("utf8mb4");
    // Verificação de erro de conexão (opcional)
    if (mysqli_connect_errno()) {
        exit('Erro ao conectar ao MySQL: ' . mysqli_connect_error());
    }

    // Bloco de verificação de sessão (só executa se $auxLogin for false ou não definido)
    if (!isset($auxLogin) || $auxLogin === false) {
        //Cria uma sessão
        session_start();
        
        //Se não tiver logado não deixa entrar na página
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header('Location: index.php');
            exit();
        }

        if ($_SESSION["tipo"] == "administrador") {
            //Verificação da password para segurança das paginas
            $stmt = $con->prepare("SELECT * FROM administrador WHERE id = ? AND pass = ?");
        }
        elseif ($_SESSION["tipo"] == "professor") {
            //Verificação da password para segurança das paginas
            $stmt = $con->prepare("SELECT * FROM professores WHERE id = ? AND pass = ?");
        }
        $stmt->bind_param("is", $_SESSION['id'], $_SESSION['password']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows <= 0) {
            header('Location: index.php');
            exit();
        }
<<<<<<< HEAD

        $idAdmin = $_SESSION['id'];
        $nomeAdmin = $_SESSION['nome'];
        $emailAdmin = $_SESSION['email'];
        $imgAdmin = $_SESSION['img'];
=======
>>>>>>> 4ada4ec6c01c7620ab138b403210d9a57ba73942
    }

    //Chama as funções para serem usadas em todas as paginas
    include_once './db/functions.php';
?>