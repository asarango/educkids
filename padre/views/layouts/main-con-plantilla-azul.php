<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use backend\models\Menu;

AppAsset::register($this);


$iden = Yii::$app->user->identity;
?>
<?php $this->beginPage() ?>



<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js">
    <head>
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" charset="<?= Yii::$app->charset ?>">
        <!-- Favicon-->
        <link rel="shortcut icon" href="img/fav.png">
        <!-- Author Meta -->
        <meta name="author" content="CodePixar">
        <!-- Meta Description -->
        <meta name="description" content="">
        <!-- Meta Keyword -->
        <meta name="keywords" content="">
        <!-- meta character set -->
        <meta charset="UTF-8">
        <!-- Site Title -->
        <title><?= Html::encode($this->title) ?></title>

        <link href="https://fonts.googleapis.com/css?family=Poppins:300,500,600" rel="stylesheet">
        <!--
        CSS
        ============================================= -->
        <link rel="stylesheet" href="padre/css/linearicons.css">
        <link rel="stylesheet" href="padre/css/owl.carousel.css">
        <link rel="stylesheet" href="padre/css/font-awesome.min.css">
        <link rel="stylesheet" href="padre/css/nice-select.css">
        <link rel="stylesheet" href="padre/css/magnific-popup.css">
        <link rel="stylesheet" href="padre/css/bootstrap.css">
        <link rel="stylesheet" href="padre/css/main.css">
        <link rel="stylesheet" href="padre/css/estilos.css">
    </head>
    <body>
        
        <?php $this->beginBody() ?>
        
        <?= $content ?>
        




        <script src="padre/js/vendor/jquery-2.2.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="padre/js/vendor/bootstrap.min.js"></script>
        <script src="padre/js/jquery.ajaxchimp.min.js"></script>
        <script src="padre/js/owl.carousel.min.js"></script>
        <script src="padre/js/jquery.nice-select.min.js"></script>
        <script src="padre/js/jquery.magnific-popup.min.js"></script>
        <script src="padre/js/main.js"></script>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
