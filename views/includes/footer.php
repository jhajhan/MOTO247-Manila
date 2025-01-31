<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/MOTO247-Manila/assets/js/jquery-3.7.1.js"></script>
    <script src="/MOTO247-Manila/assets/js/custom.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <!--ALERTIFY JS -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
    <script>
        // Alertify JS
        <?php if(isset($_SESSION['message'])): ?>
            alertify.set('notifier','position', 'top-right');
            alertify.success('<?php echo $_SESSION['message']; ?>');
        <?php endif; ?>
        </script>

</body>
</html>