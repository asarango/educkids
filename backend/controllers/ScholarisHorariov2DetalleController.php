<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisHorariov2Detalle;
use backend\models\ScholarisHorariov2DetalleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisHorariov2DetalleController implements the CRUD actions for ScholarisHorariov2Detalle model.
 */
class ScholarisHorariov2DetalleController extends Controller
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
     * Lists all ScholarisHorariov2Detalle models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $cabecera = $_GET['id'];
        $modelHorario = \backend\models\ScholarisHorariov2Cabecera::findOne($cabecera);
        
        $searchModel = new ScholarisHorariov2DetalleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $cabecera);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelHorario' => $modelHorario
        ]);
    }

    /**
     * Displays a single ScholarisHorariov2Detalle model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisHorariov2Detalle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $cabecera = $_GET['cabecera'];
        $modelCabecera = \backend\models\ScholarisHorariov2Cabecera::findOne($cabecera);
        
        $model = new ScholarisHorariov2Detalle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $cabecera]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelCabecera' => $modelCabecera
        ]);
    }

    /**
     * Updates an existing ScholarisHorariov2Detalle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $model->cabecera_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisHorariov2Detalle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        $cabeceraId = $model->cabecera_id;
        
        $this->findModel($id)->delete();

        return $this->redirect(['index1','id' => $cabeceraId]);
    }

    /**
     * Finds the ScholarisHorariov2Detalle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisHorariov2Detalle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisHorariov2Detalle::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
