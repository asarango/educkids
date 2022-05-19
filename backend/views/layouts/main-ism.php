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

if (!Yii::$app->user->isGuest) {

    $usuario = Yii::$app->user->identity->usuario;
    $modelUser = backend\models\ResUsers::find()->where(['login' => $usuario])->one();
    $modelUserEducandi = backend\models\Usuario::find()->where(['usuario' => $usuario])->one();
}
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">                


        <link rel="icon" type="image/png" href="ISM/login/favicon.ico"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" >
        <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

        <!--mis css-->
        <link rel="stylesheet" href="css/ismStyles.css" />
        <link rel="stylesheet" href="ISM/main/hamburger/style.css" />

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="animate__animated animate__fadeIn" 
          style="font-size: 14px; font-family: sans-serif; color: #898b8d">
              <?php $this->beginBody() ?>


        <div class="container-fluid">
            <div class="row" style="padding: 15px; border-bottom: solid #898b8d 1px">
                <div class="col-lg-2 col-md-2">
                    <img src="ISM/main/images/logo.png" width="120px"/>
                </div>             

                <div class="col-lg-1 col-md-1 row align-items-center">
                    <?=
                    Html::a('Inicio', ['/site/index'], ['class' => 'link'])
                    ?>                                

                </div>

                <div class="col-lg-1 col-md-1 row align-items-center">
                    <a href="#" onclick="showMenu()" class="link">Menú</a>                                            
                </div>                        

                <div class="col-lg-8 col-md-8 row text-right align-items-center">
                    <?=
                    Html::a("SALIR ( $usuario )", ['/site/logout'], ['data' => [
                            'method' => 'post',
                            'params' => ['derp' => 'herp'], // <- extra level
                        ], 'class' => 'link'])
                    ?>
                </div>                        
            </div>

            <div class="row card menu animate__animated animate__fadeIn " id="divMenu">
                <a href="#" onclick="showMenu()" class="btn-menu"><strong>OCULTAR</strong></a> 
                <hr>

                <?php
                if (!Yii::$app->user->isGuest) {
                    $usuario = Yii::$app->user->identity->usuario;
                    $menusModel = new Menu();
                    $menus = $menusModel->getMenus($usuario);

                    foreach ($menus as $data) {
                        $menuId = $data['id'];
                        isset($data['icono']) ? $icono = $data['icono'] : $icono = '<i class="fas fa-laptop-code"></i>';
                        ?>
                        <a href="#" class="btn-menu"><?= $icono . ' ' . $data['nombre'] ?></a><hr>
                        <?php
//                    echo $icono;
//                    echo '<span style="font-size: 10px">' . $data['nombre'] . '</span>';
                    }
                }
                ?>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 text-right" id="divSubMenu">
                    
                    
                    
                    
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

<script>
                    function showMenu() {
                        if ($("#divMenu").is(':visible')) {
                            $("#divMenu").hide();
                        } else {
                            $("#divMenu").show();
                        }
                        //
                    }
</script>