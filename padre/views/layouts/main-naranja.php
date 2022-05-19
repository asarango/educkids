<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use backend\models\OpInstitute;
use backend\models\ScholarisPeriodo;

if (Yii::$app->user->isGuest) {
    $instituto = '';
    $periodo = '';
}else{
    $instituto = OpInstitute::find()->where(['id' => Yii::$app->user->identity->instituto_defecto])->one();
    $periodo = ScholarisPeriodo::find()->where(['id' => Yii::$app->user->identity->periodo_id])->one();
}


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

    <head>

        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <!-- Bootstrap core CSS -->
        <link href="plantilla/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet">
        <link href="plantilla/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="plantilla/css/resume.min.css" rel="stylesheet">
        <link href="plantilla/css/misestilos.css" rel="stylesheet">

    </head>

    <body id="page-top">

        <?php $this->beginBody() ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
            <a class="navbar-brand js-scroll-trigger" href="#page-top">
                <span class="d-block d-lg-none">Clarence Taylor</span>
                <span class="d-none d-lg-block">
                    <img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="plantilla/img/profile.jpg" alt="">
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="http://localhost/sistema/backend/web/index.php?r=site%2Flogin">Administración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#experience">Experience</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#education">Education</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#skills">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#interests">Interests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#awards">Awards</a>
                    </li>
                </ul>
            </div>
        </nav>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><h1>Educandi</h1></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <?php
                            if (Yii::$app->user->isGuest) {
                                
                            }else{
                                echo '<a class="nav-link" href="#"><h3> '.$instituto->name.' <small>( '.$periodo->nombre.')</small></h3></a>';
                            }
                        ?>
                        
                    </li>
                </ul>
                <?php
                        if (Yii::$app->user->isGuest) {
                            //echo Html::a('Login', ['login']);
                        } else {
                            echo Html::beginForm(['/site/logout'], 'post', ['data-ajax' => false])
                            . Html::submitButton(
                                    'Logout (' . Yii::$app->user->identity->usuario . ')', ['class' => 'btn btn-outline-success logout']
                            )
                            . Html::endForm();
                        }
                        ?>
                
            </div>
        </nav>

        
            <?=
            Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])
            ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        

        <!-- Bootstrap core JavaScript -->
        <script src="plantilla/vendor/jquery/jquery.min.js"></script>
        <script src="plantilla/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="plantilla/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for this template -->
        <script src="plantilla/js/resume.min.js"></script>

<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
