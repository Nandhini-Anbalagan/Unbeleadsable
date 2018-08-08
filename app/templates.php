<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>

<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="email-menu">
                <?php require_once('load/email-menu.php'); ?>
            </div>
            
            <div class="template-container col-lg-9 col-md-8">
                <?php require_once('load/templates.php'); ?>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>