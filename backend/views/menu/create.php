<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = 'Creando Menú';
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create" style="padding-left: 40px; padding-right: 40px">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><h1><?= Html::encode($this->title) ?></h1></h3>
        </div>
        <div class="panel-body">

            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>


        </div>
    </div>    
</div>
