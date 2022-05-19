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

    $periodId = Yii::$app->user->identity->periodo_id;
    $period = \backend\models\ScholarisPeriodo::findOne($periodId);

//    print_r($periodId);
//die();
}
?>
<?php $this->beginPage() ?>


<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" >
        <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!--        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />-->

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

        <!--<link rel="stylesheet" href="ISM/main/assets/css/style.css">-->
        <link rel="stylesheet" href="ISM/main/hamburger/style.css">
        <link rel="stylesheet" href="ISM/main/menuvertical/style.css">
        <link rel="stylesheet" href="css/ismStyles.css" />
        <link rel="stylesheet" href="css/coloresPersonalizados.css" />
        <script src="ISM/main/signature/signature_pad.js"></script>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php // $this->head()  ?>
    </head>

    <body class="animate__animated animate__fadeIn"
          style="font-size:14px; font-family: sans-serif; color: #898b8d">
              <?php $this->beginBody() ?>
        <header>


            <nav class="nav" id="nav-menu">
                <ion-icon name="close" class="header__close" id="close-menu"></ion-icon>

                <ul class="nav__list">

                    <li class="nav__item">
                        <a href="#" class="nav__link" onclick="showMenu()">
                            <ion-icon name="menu" class="header__toggle1" id="toggle-menu"></ion-icon>
                        </a>
                    </li>
                    <li class="nav__item"><a href="<?= Url::to(["site/index"]) ?>" class="nav__link">Inicio</a></li>


                    <li class="nav__item">
                        <?=
                        Html::a("SALIR ( $usuario )", ['/site/logout'], ['data' => [
                                'method' => 'post',
                                'params' => ['derp' => 'herp'], // <- extra level
                            ], 'class' => 'nav__link'])
                        ?>
                    </li>

                    <li class="nav__item">
                      <text class="text-color-t nav_link"><strong>Te encuentras trabajando en el periodo: <?= $period->nombre ?></strong></text>
                    </li>

                    <li class="nav__item">
                      <a href="<?= Url::to(['mensajes/index1']) ?>" type="button" class="btn btn-link position-relative">
                          <i class="fas fa-bell" style="font-size:24px; color: #898b8d"></i>
                          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                                style="background-color: #9e28b5">
                            <?php //echo count(consumo_servicio_mensajes()) ?>
                            <span class="visually-hidden">unread messages</span>
                          </span>
                      </a>
                    </li>


                </ul>
            </nav>
            <div class="logo">
                <img src="ISM/main/assets/img/logo.png" alt="">
                <!--                <a href="" class="header__logo">Ay-Projx</a>-->
            </div>
        </header>
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

                    echo Html::a($icono . ' ' . $data['nombre'], ['site/get-sub-menu', 'menu_id' => $data['id']],
                            ['class' => 'btn-menu']
                    );
                    echo '<hr>';
                    ?>


                    <?php
                }
            }
            ?>
        </div>

        <div class="container-fluid">


            <!-- <section>

                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <p class="text-color-t"><strong>Te encuentras trabajando en el periodo: <?= $period->nombre ?></strong></p>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div id="divSubMenu">
                            <!--aqui los items cols de submenu viene desde ajax de la funcion showSubmenu()-->
                      <!--  </div>
                    </div>
                </div>
            </section> -->

            <section id="section-content" style="display: none;">
                <div class="container-fluid m-2">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </section>


        </div>

        <div class="m-5"></div>
        <br>
        <br>
        <br>
        <br>

        <footer class="fixed-bottom">

            <div class="logo">
                <img src="ISM/main/assets/img/ism.jpg" class="" alt="">
                Designed and built with all the love in the world by the ISM team with the help of our contributors.
            </div>

            <div style="font-size: 10px">Iconos diseñados por <a href="https://www.flaticon.es/autores/ariefstudio" title="ariefstudio" target="_blank">ariefstudio</a>
                from <a href="https://www.flaticon.es/" title="Flaticon" target="_blank">www.flaticon.es</a></div>

        </footer>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="ISM/main/assets/js/main.js"></script>
        <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>


        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>


<script>

                $("document").ready(function () {

                    $("#section-loading").hide();
                    $("#section-content").show();
                });


                function showMenu() {
                    if ($("#divMenu").is(':visible')) {
                        $("#divMenu").hide();
                    } else {
                        $("#divMenu").show();
                    }
                    //
                }




</script>


<?php
 function consumo_servicio_mensajes(){
  $userLog = \Yii::$app->user->identity->usuario;

  //consultando web service de academico
  $service = new backend\models\services\WebServicesUrls('academico');
  $dataJson = $service->consumir_servicio($service->url.'/unread/'.$userLog);
  $messages = json_decode($dataJson);
  // fin de proceso web service academico

  return $messages->data;

}

 ?>
