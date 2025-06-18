<?php
    include("./db/conexao.php");
    registrar_log($con, "Logout", "Utilizador saiu do sistema.");
    session_destroy();
    header('Location: index');
?>