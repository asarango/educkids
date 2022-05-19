<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ScholarisArchivosprofesor */

$this->title = 'Update Scholaris Archivosprofesor: ' . $model->id;
?>
<div class="scholaris-archivosprofesor-update">

    <h1><?= Html::encode($this->title) ?></h1>    
   
    <?= $this->render('scholaris-actividad/actividad', [
        'id' => $id,
    ]) ?> 

   

</div>
