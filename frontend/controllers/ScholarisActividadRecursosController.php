<?php

namespace frontend\controllers;

use Yii;
use backend\models\ScholarisActividadRecursos;
use backend\models\ScholarisActividadRecursosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisActividadRecursosController implements the CRUD actions for ScholarisActividadRecursos model.
 */
class ScholarisActividadRecursosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisActividadRecursos models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $actividadId = $_GET['id'];
        $modelActividad = \backend\models\ScholarisActividad::findOne($actividadId);
        
        $modelRecursos = ScholarisActividadRecursos::find()
                ->where(['actividad_id' => $actividadId])
                ->all();
        
        $model = new ScholarisActividadRecursos();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $actividadId]);
        }
        
        
        return $this->render('index', [
                'modelActividad' => $modelActividad,
                'model' => $model,
                'modelRecursos' => $modelRecursos
                ]);
        
        
    }
    
    public function actionEliminar(){
        $id = $_GET['id'];
        $model = \backend\models\ScholarisActividadRecursos::findOne($id);
        $actividadId = $model->actividad_id;
        $model->delete();
        return $this->redirect(['index1','id'=>$actividadId]);
    }

}
