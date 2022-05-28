<?php

namespace backend\controllers;

use backend\models\IsmAreaMateria;
use backend\models\KidsUnidadMicro;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KidsPcaController implements the CRUD actions for KidsPca model.
 */
class KidsExperienciaController extends Controller
{
    public function actionIndex1(){
        $experienciaId = $_GET['id'];
        $micro = KidsUnidadMicro::findOne($experienciaId);

        if(!isset($_GET['pestana'])){
            $pestana = 'datos';
        }else{
            $pestana = $_GET['pestana'];
        }

        return $this->render('index1', [
            'micro' => $micro,
            'pestana' => $pestana
        ]);
    }
}