<?php 
    //inclui a base de dados e segurança da página
    include('./db/conexao.php'); 
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport"
    />
    <meta name="theme-color" content="#1a2035">

    <meta property="og:title" content="Centro de Estudo 4x1">
    <meta property="og:description" content="Explicações, apoio escolar e muito mais.">
    <meta property="og:image" content="https://admin.4x1.pt/images/LogoBranco4x1.png">
    <meta property="og:url" content="https://admin.4x1.pt">

    <!-- Permitir "web app" no iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="4x1 Centro de Estudo">

    <!-- Ícone que aparece no ecrã principal -->
    <link rel="apple-touch-icon" href="/images/icon4x1_192x192.png">
    <link rel="apple-touch-startup-image" href="/images/icon4x1_512x512.png">

    <link rel="manifest" href="/manifest.json">
    <link
        rel="icon"
        href="./images/Icon4x1.png"
        type="image/x-icon"
    />
    
    <!-- Estilos do intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
            families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
            ],
            urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
            sessionStorage.fonts = true;
        },
        });
    </script>

    <script>
        $(document).ready(function () {
            <?php if (isset($_SESSION['notificacao'])): ?>
                $.notify({
                    message: '<?php echo addslashes($_SESSION['notificacao']['mensagem']); ?>',
                    title: 'Notificação',
                    icon: 'fa fa-info-circle',
                }, {
                    type: '<?php echo $_SESSION['notificacao']['tipo']; ?>',
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    delay: 2000
                });

                <?php unset($_SESSION['notificacao']); // Limpa a notificação após exibir ?>
            <?php endif; ?>
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />