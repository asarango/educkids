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

        <!--<link href="plantir2/style.css" rel="stylesheet" id="bootstrap-css">-->
        <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">-->
        <link rel="stylesheet" type="text/css" href="DataTables/datatables.css">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

        <link rel="stylesheet" href="plantillav1/main/css/styles.css">

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php // $this->head() ?>
    </head>
    <body class="">
        <?php $this->beginBody() ?>


        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #357ca5; color: white;">
            <a class="navbar-brand" href="#">
                <img src="plantillav1/main/images/logoblanco.png" width="80px">
            </a>
            <a class="navbar-brand" href="#">
                <img src="plantillav1/main/images/institutologo.png" width="80px">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                </ul>
                <div class="form-inline my-2 my-lg-0">
                    <?php
                    if (!Yii::$app->user->isGuest) {

                        $user = Yii::$app->user->identity->usuario;
                        $modelUser = \backend\models\ResPartner::find()->where([
                                    'numero_identificacion' => $user,
                                    'company_type' => 'person'
                                ])->one();


                        echo Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                                $modelUser->name . '<br><i class="fas fa-door-open text-warning">Salir</i>', ['class' => 'btn btn-link tamano12', 'style' => 'color: white; font-size:12px']
                        )
                        . Html::endForm();
                    }
                    ?>
                </div>
            </div>
        </nav>


        <div class="">

            <?php
            echo $content
            ?>


            <footer>



                <nav class="navbar navbar-expand-lg navbar-light fixed-bottom" style="background-color: #333333; color: white;">
                    <a class="navbar-brand" href="#">
                        <p class="pull-left" style="color: white; font-size: 12px">&copy; Educandi <?= date('Y') ?></p>
                    </a>

                    <a class="navbar-brand" href="#" style="font-size: 12px; color: white">
                        <p class="pull-right" style="font-size: 12px"> / Desarrollado por 
                            <a href="http://www.zabyca.com" class="alinearDerecha" target="_blank"><strong>Zabyca Asociados CIA. LTDA</strong></a>
                        </p>  
                    </a>                    
                </nav>                
            </footer>
        </div>



        <script src="jquery/jqueryslim331.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <!--<script src="boostrap4/js/bootstrap.min.js"></script>-->




        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
