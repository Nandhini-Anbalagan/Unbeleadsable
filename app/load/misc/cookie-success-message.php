<?php if(isset($_COOKIE['success-message'])){ ?>
    <script>
        $(document).ready(function(){
            generateNotification('<?php echo $_COOKIE['success-message']; ?>', 'bottom-right', 'success', 2500, true);
        });
    </script>
    <?php setcookie('success-message', '', time() - 54, '/'); ?>
<?php } ?>