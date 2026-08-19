<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/notify/js/notifIt.js"></script>
<script src="assets/plugins/notify/js/notifit-custom.js"></script>
<script src="assets/js/custom.js"></script>
<script>
(function () {
    document.querySelectorAll('[data-qs-toggle-pass]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-qs-toggle-pass');
            var input = id ? document.getElementById(id) : btn.parentElement.querySelector('input');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
})();
</script>
<?php include dirname(__DIR__, 2) . '/inc/cookie-banner.php'; ?>
