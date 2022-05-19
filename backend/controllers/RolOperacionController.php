<?php

namespace backend\controllers;

use Yii;
use backend\models\RolOperacion;
use backend\models\RolOperacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RolOperacionController implements the CRUD actions for RolOperacion model.
 */
class RolOperacionController extends Controller
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
     * Lists all RolOperacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolOperacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RolOperacion model.
     * @param integer $rol_id
     * @param integer $operacion_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($rol_id, $operacion_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($rol_id, $operacion_id),
        ]);
    }

    /**
     * Creates a new RolOperacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RolOperacion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rol_id' => $model->rol_id, 'operacion_id' => $model->operacion_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RolOperacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $rol_id
     * @param integer $operacion_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($rol_id, $operacion_id)
    {
        $model = $this->findModel($rol_id, $operacion_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rol_id' => $model->rol_id, 'operacion_id' => $model->operacion_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RolOperacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $rol_id
     * @param integer $operacion_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($rol_id, $operacion_id)
    {
        $this->findModel($rol_id, $operacion_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RolOperacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $rol_id
     * @param integer $operacion_id
     * @return RolOperacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($rol_id, $operacion_id)
    {
        if (($model = RolOperacion::findOne(['rol_id' => $rol_id, 'operacion_id' => $operacion_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
