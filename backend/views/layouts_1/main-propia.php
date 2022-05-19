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
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <?= Html::csrfMetaTags() ?>
        <?= Html::csrfMetaTags() ?>
        <!--<title><?php //echo  Html::encode($this->title)    ?></title>-->
        <title><?php echo Html::encode('EDUCANDI') ?></title>
        <?php $this->head() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
        <link href="plantillaPropia/lib/bootstrap3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="plantillaPropia/estilos.css" rel="stylesheet" id="bootstrap-css">
        <!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>-->
        <!--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
        <script src="plantillaPropia/lib/jqueri111/jquery.js"></script>

        <script src="plantillaPropia/lib/bootstrap3/js/bootstrap.min.js"></script>


        <!------ Include the above in your HEAD tag ---------->

        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    </head>



    <body>
        <?php $this->beginBody() ?>


        <!--inicio de navbar cabecera-->
        <nav class="navbar navbar-fixed-top navbarFondo navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--<a class="navbar-brand categoriaMenu">Educandi</a>-->
                    <p class="categoriaMenu"><h4>Educandi</h4></p>
                </div>               
            </div>
        </nav>
        <!--inicio de navbar cabecera-->

        <!--<div class="container-fluid">-->
        <div class="row row-offcanvas row-offcanvas-right">
            <!--<div class="col-xs-12 col-sm-9 col-sm-push-2">-->
            <div class="col-xs-12 col-sm-9 col-sm-push-2">
                <p class="pull-right visible-xs">
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas" title="Toggle sidebar"><i class="fa fa-chevron-right"></i></button>
                </p>

                <div class="row">
                    <!-- Main content -->
                    <?=
                    Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                    <?= Alert::widget() ?>
                    <?= $content ?>
                    <!-- /.content -->
                </div><!--/row-->
            </div><!--/.col-xs-12.col-sm-9-->

            <!--<div class="col-xs-6 col-sm-2 col-sm-pull-9 sidebar-offcanvas fondoMenu" id="sidebar">-->
            <div class="col-xs-6 col-sm-2 col-sm-pull-9 sidebar-offcanvas fondoMenu" id="sidebar">

                <center>
                    <br>
                    <img src="imagenes/instituto/logo/logo2.png" class="img-circle"><br>
                    <?php
                    if (!Yii::$app->user->isGuest) {
                        ?>
                        <p class="categoriaMenu"><?= Yii::$app->user->identity->usuario ?></p>
                        <a href="#">
                            <?php
                            echo Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                    'Cerrar Sesión', ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm();
                            ?>
                            <?php
                        }
                        ?>
                </center>




                <!--fin cabecera de menu-->

                <!-- Inicio Menu de opciones-->

                <div class="row container">

                    <?php
                    if (!Yii::$app->user->isGuest) {
                        $usuario = Yii::$app->user->identity->usuario;
                        $menusModel = new Menu();
                        $menus = $menusModel->getMenus($usuario);

                        foreach ($menus as $data) {
                            $menuId = $data['id'];
                            echo '<ul><a data-toggle="collapse" href="#' . $menuId . '" aria-expanded="false" aria-controls="footwear" class="categoriaMenu">' . $data['nombre'] . '</a></ul>';
                            echo '<div class="collapse" id="' . $menuId . '">';
                            echo '<ul>';

                            $operaciones = $menusModel->getOperaciones($menuId);
                            foreach ($operaciones as $dataO) {
                                $operacionNo = $dataO['nombre'];
                                $operacion = $dataO['operacion'];
                                $ruta = str_replace("-index", "/index", $operacion);


                                $iden = Yii::$app->user->identity;

                                if ($iden->tienePermiso($operacion)) {
                                    echo '<li><a href="' . Url::to([$ruta]) . '"><i class="fa fa-circle-o"></i>' . $operacionNo . '</a></li>';
                                }
                            }


                           
                            echo '</ul>';
                            echo '</div>';
                        }
                    }
                    ?>







                    <!-- Fin Menu de opciones-->

                </div><!--/.sidebar-offcanvas-->
            </div><!--/row-->
            </div><!--/row-->

            <script>
                $(document).ready(function () {
                    $('[data-toggle="offcanvas"]').click(function () {
                        $('.row-offcanvas').toggleClass('active')
                    });
                });
            </script>

            <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>