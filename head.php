<?php 
    if (!isset($login) || $login === false) {
        include('./db/conexao.php'); 
    }
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <link rel="icon" href="./images/Icon4x1.png" type="image/x-icon" />

    <!-- SEO & Redes Sociais -->
    <meta property="og:title" content="Centro de Estudo 4x1" />
    <meta property="og:description" content="Explicações, apoio escolar e muito mais." />
    <meta property="og:image" content="https://admin.4x1.pt/images/LogoBranco4x1.png" />
    <meta property="og:url" content="https://admin.4x1.pt" />

    <!-- Web App iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="4x1 Centro de Estudo" />

    <!-- Ícones & Manifest -->
    <link rel="apple-touch-icon" href="/images/icon4x1_192x192.png" />
    <link rel="apple-touch-startup-image" href="/images/icon4x1_512x512.png" />
    <link rel="manifest" href="/manifest.json" />

    <!-- Fonts and icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- jQuery (necessário para notificações e outros plugins dependentes) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- WebFont -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- Notificações (se existirem) -->
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
                <?php unset($_SESSION['notificacao']); ?>
            <?php endif; ?>
        });
    </script>

    <script>
        let vaiSairDoSite = true;

        // Clica num link <a> dentro do site
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                vaiSairDoSite = false;
            });
        });

        // Submete um formulário <form>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                vaiSairDoSite = false;
            });
        });

        // Clica num botão com ação interna
        document.querySelectorAll('button').forEach(botao => {
            botao.addEventListener('click', function () {
                vaiSairDoSite = false;
            });
        });

        // Protege chamadas JS internas (ex: window.location)
        window.addEventListener('click', () => {
            vaiSairDoSite = false;
        });

        // Antes de sair da página
        window.addEventListener('beforeunload', function () {
            if (vaiSairDoSite) {
                navigator.sendBeacon('indexLogout.php', dados);
            }
        });
    </script>

    <!-- intl-tel-input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>