<?php
namespace backend\models\helpers;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Condiciones extends ActiveRecord{
    

    //condiciones de aprobacion y bloque
    public function aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado){
        if( ($bloqueIsOpen == 0) || 
            ($cabeceraEstado == 'APROBADO' || $cabeceraEstado == 'EN_COORDINACION') ||
            ($bloqueEsConfigurado == 'configurado')){
            return false;
        }else{
            return true;
        }
    }
}


?>