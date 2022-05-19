<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisFirmasReportes;
use backend\models\ScholarisFirmasReportesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisFirmasReportesController implements the CRUD actions for ScholarisFirmasReportes model.
 */
class ScholarisFirmasReportesController extends Controller
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
     * Lists all ScholarisFirmasReportes models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        
        $searchModel = new ScholarisFirmasReportesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $institutoId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisFirmasReportes model.
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
     * Creates a new ScholarisFirmasReportes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        
        $modelTemplates = \backend\models\OpCourseTemplate::find()->all();
        
        $model = new ScholarisFirmasReportes();
        
        $modelInstitutos = \backend\models\OpInstitute::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelTemplates' => $modelTemplates,
            'modelInstitutos' => $modelInstitutos
        ]);
    }

    /**
     * Updates an existing ScholarisFirmasReportes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $modelInstitutos = \backend\models\OpInstitute::find()->all();
        
        $modelTemplates = \backend\models\OpCourseTemplate::find()->all();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelTemplates' => $modelTemplates,
            'modelInstitutos' => $modelInstitutos
        ]);
    }

    /**
     * Deletes an existing ScholarisFirmasReportes model.
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
     * Finds the ScholarisFirmasReportes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisFirmasReportes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisFirmasReportes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
