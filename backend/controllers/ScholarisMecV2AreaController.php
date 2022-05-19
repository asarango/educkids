<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2Area;
use backend\models\ScholarisMecV2AreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisMecV2AreaController implements the CRUD actions for ScholarisMecV2Area model.
 */
class ScholarisMecV2AreaController extends Controller
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
     * Lists all ScholarisMecV2Area models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $malla = $_GET['id'];
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($malla);
        
        $modelAreas = ScholarisMecV2Area::find()->where(['malla_id' => $malla])->all();
        

        return $this->render('index', [
            'modelMalla' => $modelMalla,
            'modelAreas' => $modelAreas
        ]);
    }

    /**
     * Displays a single ScholarisMecV2Area model.
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
     * Creates a new ScholarisMecV2Area model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $malla = $_GET['mallaId'];
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($malla);
        
        $model = new ScholarisMecV2Area();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $malla]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelMalla' => $modelMalla
        ]);
    }

    /**
     * Updates an existing ScholarisMecV2Area model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisMecV2Area model.
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
     * Finds the ScholarisMecV2Area model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMecV2Area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisMecV2Area::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
