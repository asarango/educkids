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



        <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
        <link href="planti/lib/bootstrap3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="planti/estilos.css" rel="stylesheet" id="bootstrap-css">        
        <script src="planti/lib/jqueri111/jquery.js"></script>

        <script src="planti/lib/bootstrap3/js/bootstrap.min.js"></script>


        <!------ Include the above in your HEAD tag ---------->

        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">



        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <link rel="stylesheet" href="DataTables/datatables.min">

        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="row">
            <div class="">
                <div class="cabFondo">
                    <img src="imagenes/educandi/arreglagologo.png" width="100px" class="margen-izq-10">
                    <?php
                        if (!Yii::$app->user->isGuest) {
                            $institutoId = $iden->instituto_defecto;
                            $periodoId = $iden->periodo_id;                                                        
                            $modelInstituto = backend\models\OpInstitute::find()->where(['id' => $institutoId])->one();                                                                                   
                            $modelPeriodo = backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
                            
                            echo $modelInstituto->name.' - '.$modelPeriodo->codigo;
                        }
                    ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-2 menu-margen-izq">
                <nav class="navbar" role="navigation">

                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse">


                        <center>
                            <br>
                            <img src="imagenes/instituto/logo/logo2.png" width="100" height="100" class="img-circle"><br>
                            <?php
                            if (!Yii::$app->user->isGuest) {
                                ?>
                                <p class="categoriaMenu"><?= Yii::$app->user->identity->usuario ?></p>
                                <a href="#">
                                    <?php
                                    echo Html::beginForm(['/profesor-inicio/cambiarclave'], 'post')
                                    . Html::submitButton(
                                            'Cambiar clave', ['class' => 'btn btn-link']
                                    )
                                    . Html::endForm();


                                    echo Html::beginForm(['/site/logout'], 'post')
                                    . Html::submitButton(
                                            'Cerrar Sesión', ['class' => 'btn btn-link logout']
                                    )
                                    . Html::endForm();
                                    ?>
                                    <?php
                                }
                                ?>


                                <div id="mensTotal"></div>

                        </center>
                        <hr>
                        <div class="panel-heading"><center>-- MENÚ PRINCIPAL --</center></div>
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

                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

                    </div><!-- /.navbar-collapse -->
                </nav>


            </div><!--/end left column-->


            <div class="col-md-10">


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
                <p class="pull-left">&copy; Educandi <?= date('Y') ?></p>

                <p class="pull-right">Desarrollado por <a href="http://www.zabyca.com:8069/"><strong>Zabyca Asociados CIA. LTDA</strong></a></p>
            </div>
        </footer>


        <script type="text/javascript">


            window.navigator = window.navigator || {};

            function load_unseen_notification(view = '')
            {
                var url = "<?= Url::to(["/scholaris-mensaje1/total"]) ?>";
                $.ajax({
                    url: url,
                    method: "GET",

                    dataType: "json",
                    success:
//                                    function (data)
//                            {
////                        $('#mensTotal').html(data.notification);
//                        if (data.unseen_notification > 0)
//                        {
//                            $('.count').html(data.unseen_notification);
//                        }
                            function (response) {
                                $("#mensTotal").html(response);
                                
                                if(response > 10){
                                    console.log(response);
                                    navigator.vibrate(1000);
                                }
                                
                            }
                            
                            
                            
                   
                });
            }

            load_unseen_notification();

//                    setInterval(load_unseen_notification, 3600000);//una hora
            setInterval(load_unseen_notification, 36000000);//3 segundos

        </script>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
