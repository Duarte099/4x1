        <!-- Core JS Files -->
        <script src="assets/js/core/popper.min.js"></script>
        <script src="assets/js/core/bootstrap.min.js"></script>

        <!-- jQuery Scrollbar -->
        <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

        <!-- Chart.js e plugins -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

        <!-- jQuery Sparkline -->
        <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

        <!-- Chart Circle -->
        <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

        <!-- Datatables -->
        <script src="assets/js/plugin/datatables/datatables.min.js"></script>

        <!-- Bootstrap Notify -->
        <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

        <!-- Sweet Alert -->
        <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

        <!-- Kaiadmin JS -->
        <script src="assets/js/kaiadmin.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

        <!-- Service Worker -->
        <script>
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker.register("/sw.js").catch(() => {});
            }
        </script>

        <!-- Swipe Sidebar -->
        <script>
            let touchStartX = 0;
            let touchEndX = 0;

            document.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            document.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipeGesture();
            }, false);

            function handleSwipeGesture() {
                const limiteMinimo = 50;
                const margemEsquerda = 50;

                if (touchStartX < margemEsquerda && touchEndX - touchStartX > limiteMinimo) {
                    const botaoSidebar = document.querySelector('.btn.btn-toggle.sidenav-toggler');
                    if (botaoSidebar) {
                        botaoSidebar.click();
                    }
                }
            }
        </script>
    </body>
</html>
