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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="adminlte/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="adminlte/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="adminlte/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        
        <!--<link href="planti/estilos.css" rel="stylesheet" id="bootstrap-css">-->        
        
        <link rel="stylesheet" href="adminlte/dist/css/skins/_all-skins.min.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="adminlte/bower_components/morris.js/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="adminlte/bower_components/jvectormap/jquery-jvectormap.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini" style="font-size: 11px">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img src="imagenes/educandi/logocasaeducandi.png" width="30px"></span>
                    <!-- logo for regular state and mobile devices -->
              <!--      <span class="logo-lg"><b>Admin</b>LTE</span>-->
                    <img src="imagenes/educandi/logo10px.png" class="">
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            

                            <!-- User Account: style can be found in dropdown.less -->
                            <?php
                            if (!Yii::$app->user->isGuest) {

                                $usuario = Yii::$app->user->identity->usuario;
                                $modelUser = backend\models\ResUsers::find()->where(['login' => $usuario])->one();
                                $modelUserEducandi = backend\models\Usuario::find()->where(['usuario' => $usuario])->one();
                                ?>
                                <li class="dropdown user user-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <img src="adminlte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                        <span class="hidden-xs" style="font-\size: 8px"><?= Yii::$app->user->identity->usuario ?></span>                                    
                                    </a>
                                    <ul class="dropdown-menu">
                                        <!-- User image -->
                                        <li class="user-header">
                                            <img src="adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                            <p>
                                                <?php
                                                isset($modelUser->partner->name) ? $nombreusuario = $modelUser->partner->name : $nombreusuario = Yii::$app->user->identity->usuario;
                                                 echo $nombreusuario.' - ' .$modelUserEducandi->rol->rol;
                                                 ?>
                                                <small>Desde 2019</small>
                                            </p>
                                        </li>

                                        <!-- Menu Footer-->
                                        <li class="user-footer">
                                            <div class="pull-left">
                                                <!--<a href="#" class="btn btn-default btn-flat">Profile</a>-->
                                            </div>
                                            <div class="pull-right">
                                                <?=
                                                Html::a('Salir', ['/site/logout'], ['data' => [
                                                        'method' => 'post',
                                                        'params' => ['derp' => 'herp'], // <- extra level
                                                    ], 'class' => 'btn btn-default btn-flat'])
                                                ?>
                                                <!--<a href="#" class="btn btn-default btn-flat">Salir</a>-->
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>

                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <?php
                            if (!Yii::$app->user->isGuest) {
                                ?>
                                <p style="font-size: 8px"><?= Yii::$app->user->identity->usuario ?></p>
                                <?php
                            }
                            ?>

                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- search form -->
                    <!--                    <form action="#" method="get" class="sidebar-form">-->
                    <div class="">
                        <?=
                        Html::a('<h6>CERRAR SESIÓN</h6>', ['/site/logout'], ['data' => [
                                'method' => 'post',
                                'params' => ['derp' => 'herp'], // <- extra level                                        
                            ], 'class' => 'btn btn-link btn-block'])
                        ?>
                    </div>
                    <!--                    </form>-->
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MENÚ PRINCIPAL</li>


                        <?php
                        if (!Yii::$app->user->isGuest) {
                            $usuario = Yii::$app->user->identity->usuario;
                            $menusModel = new Menu();
                            $menus = $menusModel->getMenus($usuario);

                            foreach ($menus as $data) {
                                $menuId = $data['id'];                              
                                isset($data['icono']) ? $icono = $data['icono'] : $icono = '<i class="fa fa-laptop"></i>'; 
                                                                
                                ?>
                                <li class="treeview">
                                    <a href="#">
                                        <?= $icono ?>
                                        <span style="font-size: 10px"><?= $data['nombre'] ?></span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu">

                                        <?php
                                        $operaciones = $menusModel->getOperaciones($menuId);
                                        foreach ($operaciones as $dataO) {
                                            $operacionNo = $dataO['nombre'];
                                            $operacion = $dataO['operacion'];
                                            $ruta = str_replace("-index", "/index", $operacion);


                                            $iden = Yii::$app->user->identity;

                                            if ($iden->tienePermiso($operacion)) {
                                                echo '<li><a href="' . Url::to([$ruta]) . '" style="font-size: 10px"><i class="fa fa-circle-o text-aqua""></i> ' . $operacionNo . '</a></li>';
                                            }
                                        }
                                        ?>


                                    </ul>
                                </li>
                                <?php
                            }
                        }
                        ?>
                        <!--                        <li class="header">LABELS</li>
                                                <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
                                                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
                                                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>-->
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
            <!--    <section class="content-header">
                  <h1>
                    Dashboard
                    <small>Control panel</small>
                  </h1>
                  <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Dashboard</li>
                  </ol>
                </section>-->

                <!-- Main content -->
                <section class="content animate__animated animate__fadeIn">
                    
                    <div class="row">
                        <?php
                                    if (!Yii::$app->user->isGuest) {
                                        $institutoId = $iden->instituto_defecto;
                                        $periodoId = $iden->periodo_id;
                                        $modelInstituto = backend\models\OpInstitute::find()->where(['id' => $institutoId])->one();
                                        $modelPeriodo = backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
                                        echo '<img src="imagenes/instituto/logo/logo2.png" width="30px"> ';
                                        echo '<strong>'.$modelInstituto->name . ' - ' . $modelPeriodo->codigo.'</strong>';
                                    }
                                    ?>
                    </div>
                    
                    <div class="row">
                        <?=
                        Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ])
                        ?>
                    </div>

                    <div class="row">
                        <?= Alert::widget() ?>
                    </div>

                    <div class="row">                
                        <?= $content ?>                            
                    </div>

                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.4.0
                </div>
                <strong>Copyright &copy; 2019-2021 <a href="#">Zabyca Asociados CIA. LTDA.</a>.</strong> All rights
                reserved.
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Create the tabs -->
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Nada que mostrar</h3>
                        <!--                        <ul class="control-sidebar-menu">
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                        
                                                            <div class="menu-info">
                                                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                        
                                                                <p>Will be 23 on April 24th</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="menu-icon fa fa-user bg-yellow"></i>
                        
                                                            <div class="menu-info">
                                                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                        
                                                                <p>New phone +1(800)555-1234</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
                        
                                                            <div class="menu-info">
                                                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                        
                                                                <p>nora@example.com</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="menu-icon fa fa-file-code-o bg-green"></i>
                        
                                                            <div class="menu-info">
                                                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                        
                                                                <p>Execution time 5 seconds</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>-->
                        <!-- /.control-sidebar-menu -->

                        <!--<h3 class="control-sidebar-heading">Tasks Progress</h3>-->
                        <!--                        <ul class="control-sidebar-menu">
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <h4 class="control-sidebar-subheading">
                                                                Custom Template Design
                                                                <span class="label label-danger pull-right">70%</span>
                                                            </h4>
                        
                                                            <div class="progress progress-xxs">
                                                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <h4 class="control-sidebar-subheading">
                                                                Update Resume
                                                                <span class="label label-success pull-right">95%</span>
                                                            </h4>
                        
                                                            <div class="progress progress-xxs">
                                                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <h4 class="control-sidebar-subheading">
                                                                Laravel Integration
                                                                <span class="label label-warning pull-right">50%</span>
                                                            </h4>
                        
                                                            <div class="progress progress-xxs">
                                                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <h4 class="control-sidebar-subheading">
                                                                Back End Framework
                                                                <span class="label label-primary pull-right">68%</span>
                                                            </h4>
                        
                                                            <div class="progress progress-xxs">
                                                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>-->
                        <!-- /.control-sidebar-menu -->

                    </div>
                    <!-- /.tab-pane -->
                    <!-- Stats tab content -->
                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <!-- /.tab-pane -->
                    <!-- Settings tab content -->
                    <div class="tab-pane" id="control-sidebar-settings-tab">

                            <h3 class="control-sidebar-heading">Configuraciones</h3>

                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    <?php
                                    echo Html::a('<h6 style="color: white"><strong>Cambiar Clave</strong></h6>', ['/profesor-inicio/cambiarclave'], [
                                        'data' => [
                                            'method' => 'post',
                                            'params' => ['derp' => 'herp']
                                        ]
                                    ]);
                                    ?>
                                </label>                           
                            </div>
                            <!-- /.form-group -->                                                        
                    </div>
                    <!-- /.tab-pane -->
                </div>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->

        <!-- jQuery 3 -->
        <script src="adminlte/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="adminlte/bower_components/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.7 -->
        <script src="adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Morris.js charts -->
        <script src="adminlte/bower_components/raphael/raphael.min.js"></script>
        <script src="adminlte/bower_components/morris.js/morris.min.js"></script>
        <!-- Sparkline -->
        <script src="adminlte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
        <!-- jvectormap -->
        <script src="adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="adminlte/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
        <!-- daterangepicker -->
        <script src="adminlte/bower_components/moment/min/moment.min.js"></script>
        <script src="adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- datepicker -->
        <script src="adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- Slimscroll -->
        <script src="adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="adminlte/bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="adminlte/dist/js/adminlte.min.js"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="adminlte/dist/js/pages/dashboard.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="adminlte/dist/js/demo.js"></script>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>