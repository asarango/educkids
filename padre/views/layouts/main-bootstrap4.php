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
//$usuario = Yii::$app->user->identity->usuario;
//die();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">



        <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="planti/estilos.css" rel="stylesheet" id="bootstrap-css">        
        <link href="planti/estilosMenu.css" rel="stylesheet" id="bootstrap-css">        



        <!------ Include the above in your HEAD tag ---------->

        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">



        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <link rel="stylesheet" href="DataTables/datatables.min">

        <?php // $this->head() ?>
    </head>
    <body class="body">
        <?php $this->beginBody() ?>

        <div class="row cabFondo">
            <div class="">
                <div class="">
                    <img src="imagenes/educandi/arreglagologo.png" width="100px" class="margen-izq-10">
                </div>
            </div>
        </div>

        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">

                <img src="imagenes/instituto/logo/logo2.png" class="img-thumbnail" width="50px">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">


                        <?php
                        if (!Yii::$app->user->isGuest) {
                            $usuario = Yii::$app->user->identity->usuario;
                            $menusModel = new Menu();
                            $menus = $menusModel->getMenus($usuario);

                            foreach ($menus as $data) {
                                $menuId = $data['id'];
                                
                                echo '<li class="nav-item dropdown">';
                                
                                echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                echo $data['nombre'];
                                echo '</a>';
                                
                                echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                                
                                $operaciones = $menusModel->getOperaciones($menuId);
                                foreach ($operaciones as $dataO) {
                                    $operacionNo = $dataO['nombre'];
                                    $operacion = $dataO['operacion'];
                                    $ruta = str_replace("-index", "/index", $operacion);


                                    $iden = Yii::$app->user->identity;

                                    if ($iden->tienePermiso($operacion)) {
                                        //echo '<li><a href="' . Url::to([$ruta]) . '"><i class="fa fa-circle-o"></i>' . $operacionNo . '</a></li>';
                                        echo '<a class="dropdown-item" href="'.Url::to([$ruta]).'">'.$operacionNo.'</a>';
                                    }
                                }
                                
                                echo '</div>';
                                
                                echo '</li>';
                                
                            }
                        }
                        ?>



                        

                    </ul>

                    <?php
                    if (!Yii::$app->user->isGuest) {
                        ?>
                        <p class=""><?= Yii::$app->user->identity->usuario ?>
                            <a href="#">
                                <?php
                                echo Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                        'Salir', ['class' => 'btn btn-link logout']
                                )
                                . Html::endForm();
                                ?>
                                <?php
                            }
                            ?>
                            </a>
                    </p>

                    <!--                <form class="form-inline my-2 my-lg-0">
                                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                                    </form>-->
                </div>            
            </nav>
            <hr>
        </div>




        <?=
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ])
        ?>
        <?= Alert::widget() ?>
        <?= $content ?> 






        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Educandi <?= date('Y') ?></p>

                <p class="pull-right">Desarrollado por <a href="http://www.zabyca.com:8069/"><strong>Zabyca Asociados CIA. LTDA</strong></a></p>
            </div>
        </footer>





        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>