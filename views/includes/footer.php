<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/MOTO247-Manila/assets/js/jquery-3.7.1.js"></script>
    <script src="/MOTO247-Manila/assets/js/custom.js"></script>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

    <!--ALERTIFY JS -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>
    <script>
        // Alertify JS
        
         alertify.set('notifier','position', 'top-right');
        <?php 
            if(isset($_SESSION['message'])): {
            ?>
            alertify.success('<?php echo $_SESSION['message']; ?>');
            <?php
            unset($_SESSION['message']);} endif; ?>        
           
        
        </script>

</body>
</html>