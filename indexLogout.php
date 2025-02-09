<?php
    //Inclui a base de dados e segurança da pagina
    include("./db/conexao.php");

    registrar_log("Administrador " . $_SESSION['name'] . "(" . $_SESSION['id'] . ") Saiu.");
    //Destroi a sessão (logout)
    session_destroy();
    //Redirect to the login page:
    header('Location: index.php');
?>