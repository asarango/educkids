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
$institutoId = Yii::$app->user->identity->instituto_defecto;
$modelIns = \backend\models\OpInstitute::find()->where(['id' => $institutoId])->one();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="bootstrap4/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="padre1/css/estilos.css" rel="stylesheet" id="bootstrap-css">
        <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
        <link rel="stylesheet" type="text/css" href="DataTables/datatables.css">
 
        


        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php // $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <nav class="navbar navbar-expand-lg navbar-light  fondoBanner">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="#"><img src="imagenes/educandi/arreglagologo.png" width="100px"></a>
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0 alinearCentro">

                    <!--                    <li class="nav-item">
                                            <a class="nav-link" href="#">Link</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" href="#">Disabled</a>
                                        </li>-->
                </ul>
                
                    <?= $modelIns->name ?> <span class="sr-only">(current)</span>
                
            </div>
        </nav>
        
        
        <div class="alert alert-dark" role="alert">
            <div class="row">
                <div class="col">
                    <?php
                    if (!Yii::$app->user->isGuest) {
                        ?>

                        
                            <?php
                            echo Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                    Yii::$app->user->identity->usuario . ' (Salir)', ['class' => 'btn btn-link tamano10P']
                            )
                            . Html::endForm();
                            ?>
                        <?php
                    }
                    ?>
                </div>
                
<!--                <div class="col">
                    
                        <a href="#">
                            <?php
//                            echo Html::beginForm(['/site/cambiar'], 'post')
//                            . Html::submitButton(
//                                    'Cambiar contraseña', ['class' => 'btn btn-link tamano10P']
//                            )
//                            . Html::endForm();
                            ?>
                        </a>
                       
                </div>-->
                
            </div>
        </div>



        <?php echo $content ?>




        <footer class="footerP">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <p class="pull-left">&copy; Educandi <?= date('Y') ?></p>
                    </div>
                    
                    <div class="col">
                      <p class="pull-right">Desarrollado por <a href="http://www.zabyca.com:8069/"><strong>Zabyca Asociados CIA. LTDA</strong></a></p>  
                    </div>
                </div>
            </div>
        </footer>


        <script src="jquery/jqueryslim331.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <!--<script src="boostrap4/js/bootstrap.min.js"></script>-->
        



        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
