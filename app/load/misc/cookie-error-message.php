<?php if(isset($_COOKIE['error-message'])){ ?>
    <script>
        $(document).ready(function(){
            generateNotification('<?php echo $_COOKIE['error-message']; ?>', 'bottom-right', 'error', 4000, true);
        });
    </script>
    <?php setcookie('error-message', '', time() - 54, '/'); ?>
<?php } ?>