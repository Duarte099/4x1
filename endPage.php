<!-- Core JS Files -->
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Service Worker -->
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

<!-- Notificações -->
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