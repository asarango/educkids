<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Educandi-Portal';
?>


<div class="site-index">

    <div class="main-wrapper-first">
        <div class="hero-area relative">



            <header>
                <div class="container">
                    <div class="header-wrap">
                        <div class="header-top d-flex justify-content-between align-items-center">
                            <div class="logo">
                                <!--<a href="#"><img src="padre/img/logo.png" alt=""></a>-->
                                <a href="#"><img src="imagenes/educandi/arreglagologo.png" alt="" width="125"></a>

                            </div>
                            <div class="main-menubar d-flex align-items-center">
                                <nav class="hide">
<!--                                    <a href="padre/index.html">Home</a>
                                    <a href="padre/generic.html">Generic</a>
                                    <a href="padre/elements.html">Elements</a>-->
                                </nav>
                                <div class="menu-bar"><span class="lnr lnr-menu"></span></div>
                            </div>
                        </div>
                    </div>
                </div>



            </header>                                
            <div class="banner-area">
                <div class="container">
                    <div class="row height align-items-center">
                        <div class="col-lg-7">
                            <div class="banner-content">
                                <h1 class="text-white text-uppercase mb-10"><?= $modelInstituto->name ?> 

<?php
if (!Yii::$app->user->isGuest) {
    ?>
                                        <p class="categoriaMenu"><?= Yii::$app->user->identity->usuario ?></p>

                                    </h1>
                                    <p class="text-white mb-30">Sistema de gestion académica Educandi, portal de padre de familia</p>
                                    <!--<a href="#" class="primary-btn d-inline-flex align-items-center"><span class="mr-10">Get Started</span><span class="lnr lnr-arrow-right"></span></a>-->
    <?php
    echo Html::beginForm(['/site/logout'], 'post')
    . Html::submitButton(
            'Cerrar Sesión', ['class' => 'btn btn-outline-success']
    )
    . Html::endForm();
    ?>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
        <!-- Start Feature Area -->
        <section class="featured-area">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="section-title text-center">
                            <h2 class="text-white">Estudiantes</h2>
                            <p class="text-white">Detalle de desempeño académico y comportamental</p>
                        </div>
                    </div>
                </div>


                <!--mis hijos-->
                <div class="row">

                    <?php
                    foreach ($hijos as $hijo) {
                        
                        $id = $hijo['id'];
                        $paraleloId = $hijo['paralelo_id'];
                        
                       ?>
                    <div class="col">
                        <div class="single-feature">
                            <!--<div class="thumb" style="background: url(padre/img/t1.jpg);"></div>-->
                            <div class="thumb" style="background: url(imagenes/instituto/padre/nino.png);"></div>
                            <div class="desc text-center mt-30">
                                <h4 class="text-white">
                                    <?=
                        
                                        $hijo['last_name'].' '.$hijo['first_name'].' '.$hijo['middle_name']
                        
                                    ?>
                                    
                                </h4>
                                <p class="text-white"><?= $hijo['curso'].' "'. $hijo['paralelo'].'" '.$hijo['seccion']?></p>
                                <a href="<?= Url::to(['padre/alumno', 'id' => $id, 'paralelo' => $paraleloId]) ?>" class="primary-btn"><span>Ver detalle</span></a>
                                
                              
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </section>
        <!-- End Feature Area -->


        <!-- Start Footer Widget Area -->
        <section class="footer-widget-area">
<!--            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="single-widget">
                            <div class="desc">
                                <h6 class="title">Address</h6>
                                <p>56/8, panthapath, west <br> dhanmondi, kalabagan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-widget">
                            <div class="desc">
                                <h6 class="title">Email Address</h6>
                                <div class="contact">
                                    <a href="mailto:info@dataarc.com">info@dataarc.com</a> <br>
                                    <a href="mailto:support@dataarc.com">support@dataarc.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="single-widget">
                            <div class="desc">
                                <h6 class="title">Phone Number</h6>
                                <div class="contact">
                                    <a href="tel:1545">012 4562 982 3612</a> <br>
                                    <a href="tel:54512">012 6321 956 4587</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
            <footer>
                <div class="container">
                    <div class="footer-content d-flex justify-content-between align-items-center flex-wrap">
                        <div class="logo">
                            <a href="index.html"><img src="img/logo.png" alt=""></a>
                        </div>
                        <div class="copy-right-text">Copyright &copy; 2018-2025  |  Todos los drechos reservados a <a href="#">Zabyca Asociados S.A. </a> Diseñado por <a href="http://www.zabyca.com:8069/en_US/" target="_blank">Zabyca</a></div>
                        <div class="footer-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-dribbble"></i></a>
                            <a href="#"><i class="fa fa-behance"></i></a>
                        </div>
                    </div>
                </div>
            </footer>
        </section>
        <!-- End Footer Widget Area -->

    </div>



</div>