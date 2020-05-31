<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo($this->get_site_title()); ?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo(PROJECTROOT) ?>css/bootstrap.min.css" rel="stylesheet">
<!--    <link href="css/font-awesome.min.css" rel="stylesheet">-->
    <link href="<?php echo(PROJECTROOT) ?>css/custom.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo(PROJECTROOT) ?>js/jQuery-3.5.1.min.js"></script>
    <script src="<?php echo(PROJECTROOT) ?>js/bootstrap.min.js"></script>
    <?php echo $this->content('head'); ?>
</head>
    <body>
    <?php echo $this->content('body'); ?>
    </body>
</html>