<?php 
    if (!isset($login) || $login === false) {
        include('./db/conexao.php'); 
    }
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="theme-color" content="#1a2035">

    <!-- SEO & Redes Sociais -->
    <meta property="og:title" content="Centro de Estudo 4x1">
    <meta property="og:description" content="Explicações, apoio escolar e muito mais.">
    <meta property="og:image" content="https://admin.4x1.pt/images/LogoBranco4x1.png">
    <meta property="og:url" content="https://admin.4x1.pt">

    <!-- Web App iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="4x1 Centro de Estudo">

    <!-- Ícones & Manifest -->
    <link rel="icon" href="./images/Icon4x1.png" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/images/icon4x1_192x192.png">
    <link rel="apple-touch-startup-image" href="/images/icon4x1_512x512.png">
    <link rel="manifest" href="/manifest.json">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- jQuery (deve ser o primeiro script) -->
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
                urls: ["assets/css/fonts.min.css"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- Bootstrap Core -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- Chart.js (tem de vir antes de plugins como datalabels) -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <!-- DataTables (requer jQuery previamente carregado) -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- intl-tel-input (requer jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

    <!-- Outros Plugins -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Script principal do template -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Service Worker -->
    <script>
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker
                .register("/sw.js")
                .catch(() => {});
        }
    </script>

    <!-- Notificações PHP → JS -->
    <script>
        $(document).ready(function () {
            <?php if (isset($_SESSION['notificacao'])): ?>
                $.notify({
                    message: '<?php echo addslashes($_SESSION['notificacao']['mensagem']); ?>',
                    title: 'Notificação',
                    icon: 'fa fa-info-circle'
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
