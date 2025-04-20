<?php
    //Inclui a base de dados e segurança da pagina
    include("./db/conexao.php");
    //Destroi a sessão (logout)
    session_destroy();
    //Redirect to the login page:
    header('Location: index.php');
?>