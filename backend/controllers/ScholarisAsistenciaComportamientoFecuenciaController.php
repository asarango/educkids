<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisAsistenciaComportamientoFecuencia;
use backend\models\ScholarisAsistenciaComportamientoFecuenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisAsistenciaComportamientoFecuenciaController implements the CRUD actions for ScholarisAsistenciaComportamientoFecuencia model.
 */
class ScholarisAsistenciaComportamientoFecuenciaController extends Controller
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
     * Lists all ScholarisAsistenciaComportamientoFecuencia models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $id = $_GET['id'];
        
        $modelDetalle = \backend\models\ScholarisAsistenciaComportamientoDetalle::findOne($id);
        
        $searchModel = new ScholarisAsistenciaComportamientoFecuenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelDetalle' => $modelDetalle
        ]);
    }

    /**
     * Displays a single ScholarisAsistenciaComportamientoFecuencia model.
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
     * Creates a new ScholarisAsistenciaComportamientoFecuencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $detalleId = $_GET['id'];
        
        $model = new ScholarisAsistenciaComportamientoFecuencia();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $detalleId]);
        }

        return $this->render('create', [
            'model' => $model,
            'detalleId' => $detalleId
        ]);
    }

    /**
     * Updates an existing ScholarisAsistenciaComportamientoFecuencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $model->detalle_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisAsistenciaComportamientoFecuencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisAsistenciaComportamientoFecuencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisAsistenciaComportamientoFecuencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisAsistenciaComportamientoFecuencia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
