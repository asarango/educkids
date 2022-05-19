<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisLeccionario;
use backend\models\ScholarisLeccionarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisLeccionarioController implements the CRUD actions for ScholarisLeccionario model.
 */
class ScholarisLeccionarioController extends Controller
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
     * Lists all ScholarisLeccionario models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $paralelo = $_GET['paralelo'];
                
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        
        $searchModel = new ScholarisLeccionarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $paralelo);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelParalelo' => $modelParalelo
        ]);
    }

    /**
     * Displays a single ScholarisLeccionario model.
     * @param integer $paralelo_id
     * @param string $fecha
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($paralelo_id, $fecha)
    {
        return $this->render('view', [
            'model' => $this->findModel($paralelo_id, $fecha),
        ]);
    }

    /**
     * Creates a new ScholarisLeccionario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $paralelo = $_GET['paralelo'];
        
        
        $model = new ScholarisLeccionario();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'paralelo' => $model->paralelo_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'paralelo' => $paralelo
        ]);
    }
        

    /**
     * Updates an existing ScholarisLeccionario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $paralelo_id
     * @param string $fecha
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($paralelo_id, $fecha)
    {
        $model = $this->findModel($paralelo_id, $fecha);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'paralelo' => $model->paralelo_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisLeccionario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $paralelo_id
     * @param string $fecha
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($paralelo_id, $fecha)
    {
        $this->findModel($paralelo_id, $fecha)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisLeccionario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $paralelo_id
     * @param string $fecha
     * @return ScholarisLeccionario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($paralelo_id, $fecha)
    {
        if (($model = ScholarisLeccionario::findOne(['paralelo_id' => $paralelo_id, 'fecha' => $fecha])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
