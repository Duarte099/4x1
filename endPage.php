<!--   Core JS Files   -->
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Chart JS -->
<script src="assets/js/plugin/chart.js/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<!-- Datatables -->
<script src="assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
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

<!-- Sweet Alert -->
<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin JS -->
<script src="assets/js/kaiadmin.min.js"></script>

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
    if ("serviceWorker" in navigator) {
      navigator.serviceWorker
        .register("/sw.js")
        .then(() => {
          //console.log("Service Worker registado com sucesso!");
        })
        .catch((error) => {
          //console.error("Erro ao registar o Service Worker:", error);
        });
    }
</script>