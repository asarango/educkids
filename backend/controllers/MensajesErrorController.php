<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//use yii\filters\AccessControl;


/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class MensajesErrorController extends Controller {

    public function actionError() {
        $mensaje = $_GET['mensaje'];
        
        
        return $this->render('index',['mensaje' => $mensaje]);
    }

}
