<?php

use yii\helpers\Html;

$modelRutaGraficos = backend\models\ScholarisParametrosOpciones::find()
        ->where(['codigo' => 'graficos'])
        ->one();
$rutaGraficos = $modelRutaGraficos->nombre;

echo '<pre>';
print_r($estadisticas);


?>


