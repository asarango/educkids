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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--<link href="bootstrap3/css/bootstrap.min.css" rel="stylesheet" media="screen">-->

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <style>
            nav.navbar ul.nav li {
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;                
            }

            /* cambiar el color de fondo a la barra */
            nav.navbar {
                background-color: #CCCCCC;                
            }
        </style>

    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">


            <nav class="navbar navbar-default" role="navigation">
                <!-- El logotipo y el icono que despliega el menú se agrupan
                     para mostrarlos mejor en los dispositivos móviles -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Desplegar navegación</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="imagenes/educandi/logo2.png" width="100px"></a>
                </div>

                <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                     otro elemento que se pueda ocultar al minimizar la barra -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    
                    <?php
                    if (!Yii::$app->user->isGuest) {
                        $usuario = Yii::$app->user->identity->usuario;
                        $menusModel = new Menu();
                        $menus = $menusModel->getMenus($usuario);

//                        echo '<li class="nav-item active">
//                                <a class="nav-link" href="'.Url::to(['site/index']).'">INICIO<span class="sr-only">(current)</span></a>
//                              </li>';

                        foreach ($menus as $data) {
                            $menuId = $data['id'];
                            ?>
                            <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                    <?= $data['nombre'] ?><b class="caret"></b>
                                </a>
                                <?php
                                $operaciones = $menusModel->getOperaciones($menuId);
                                ?>
                                
                                <!--<div class="dropdown-menu" aria-labelledby="navbarDropdown">-->                                        
                                <ul class="dropdown-menu">
                                    <?php
                                    foreach ($operaciones as $dataO) {
                                        $operacionNo = $dataO['nombre'];
                                        $operacion = $dataO['operacion'];
                                        $ruta = str_replace("-index", "/index", $operacion);


                                        $iden = Yii::$app->user->identity;

                                        if ($iden->tienePermiso($operacion)) {
                                            echo '<li><a class="dropdown-item" href="' . Url::to([$ruta]) . '">' . $operacionNo . '</a></li>';
                                        }
                                    }
                                    ?>

                                </ul>                                                               



                            </li>
                            </ul>
                            <?php
                        }
                    }
                    ?>

                    <ul class="nav navbar-nav navbar-right">

                        <?php
                        if (Yii::$app->user->isGuest) {
                            ?>
                            <a href="<?= Url::to(['site/index']); ?>" class="btn btn-outline-primary">Login</a>                
                            <?php
                        } else {
                            echo Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                    'Logout (' . Yii::$app->user->identity->usuario . ')', ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm();
                        }
                        ?>

                        
                        
                    </ul>
                </div>
            </nav>






            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>


        <!--<script src="jquery/jquery2.js"></script>-->
        <!--<script src="bootstrap3/js/bootstrap.min"></script>-->

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
