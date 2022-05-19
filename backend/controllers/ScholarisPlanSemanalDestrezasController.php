<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisPlanSemanalDestrezas;
use backend\models\ScholarisPlanSemanalDestrezasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisPlanSemanalDestrezasController implements the CRUD actions for ScholarisPlanSemanalDestrezas model.
 */
class ScholarisPlanSemanalDestrezasController extends Controller
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
     * Lists all ScholarisPlanSemanalDestrezas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScholarisPlanSemanalDestrezasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisPlanSemanalDestrezas model.
     * @param integer $curso_id
     * @param integer $faculty_id
     * @param integer $semana_id
     * @param integer $comparte_valor
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($curso_id, $faculty_id, $semana_id, $comparte_valor)
    {
        return $this->render('view', [
            'model' => $this->findModel($curso_id, $faculty_id, $semana_id, $comparte_valor),
        ]);
    }

    /**
     * Creates a new ScholarisPlanSemanalDestrezas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $curso = $_GET['curso_id'];
        $profesor = $_GET['faculty_id'];
        $semana = $_GET['semana_id'];
        $uso = $_GET['comparte_valor']; 
        $observacion = $_GET['observacion']; 
        
        $model = new ScholarisPlanSemanalDestrezas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plan-semanal/destrezas', 'id' => $observacion, 'facultyId' => $profesor]);
        }

        return $this->render('create', [
            'curso' => $curso,
            'profesor' => $profesor,
            'semana' => $semana,
            'uso' => $uso,
            'observacion' => $observacion,
            'model' => $model
        ]);
    }

    /**
     * Updates an existing ScholarisPlanSemanalDestrezas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $curso_id
     * @param integer $faculty_id
     * @param integer $semana_id
     * @param integer $comparte_valor
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($curso_id, $faculty_id, $semana_id, $comparte_valor, $observacion)
    {
        $model = $this->findModel($curso_id, $faculty_id, $semana_id, $comparte_valor);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['plan-semanal/destrezas', 'id' => $observacion, 'facultyId' => $model->faculty_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'observacion' => $observacion
        ]);
    }

    /**
     * Deletes an existing ScholarisPlanSemanalDestrezas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $curso_id
     * @param integer $faculty_id
     * @param integer $semana_id
     * @param integer $comparte_valor
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($curso_id, $faculty_id, $semana_id, $comparte_valor)
    {
        $this->findModel($curso_id, $faculty_id, $semana_id, $comparte_valor)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisPlanSemanalDestrezas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $curso_id
     * @param integer $faculty_id
     * @param integer $semana_id
     * @param integer $comparte_valor
     * @return ScholarisPlanSemanalDestrezas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($curso_id, $faculty_id, $semana_id, $comparte_valor)
    {
        if (($model = ScholarisPlanSemanalDestrezas::findOne(['curso_id' => $curso_id, 'faculty_id' => $faculty_id, 'semana_id' => $semana_id, 'comparte_valor' => $comparte_valor])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
