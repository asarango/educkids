<?php

namespace backend\controllers;

use backend\models\kids\ScriptsKids;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class KidsMenuController extends Controller{

    public function actionIndex1(){

        $script = new ScriptsKids();
        $class = $script->get_class_teacher();

        return $this->render('index', [
            'class' => $class
        ]);
    }

}