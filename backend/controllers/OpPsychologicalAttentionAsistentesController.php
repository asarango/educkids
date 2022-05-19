<?php

namespace backend\controllers;

use Yii;
use backend\models\OpPsychologicalAttentionAsistentes;
use backend\models\OpPsychologicalAttentionAsistentesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OpPsychologicalAttentionAsistentesController implements the CRUD actions for OpPsychologicalAttentionAsistentes model.
 */
class OpPsychologicalAttentionAsistentesController extends Controller
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
     * Lists all OpPsychologicalAttentionAsistentes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpPsychologicalAttentionAsistentesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OpPsychologicalAttentionAsistentes model.
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
     * Creates a new OpPsychologicalAttentionAsistentes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $attentionId = $_GET['attentionId'];
        $userLogged  = Yii::$app->user->identity->usuario;
        $modelUser   = \backend\models\ResUsers::find()->where(['login' => $userLogged])->one();
        
        $model = new OpPsychologicalAttentionAsistentes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/op-psychological-attention/update', 'id' => $attentionId]);
        }

        return $this->render('create', [
            'model' => $model,
            'attentionId' => $attentionId,
            'userId' => $modelUser->id
        ]);
    }

    /**
     * Updates an existing OpPsychologicalAttentionAsistentes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userLogged  = Yii::$app->user->identity->usuario;
        $modelUser   = \backend\models\ResUsers::find()->where(['login' => $userLogged])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/op-psychological-attention/update', 'id' => $model->psychological_attention_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'userId' => $modelUser->id
        ]);
    }

    /**
     * Deletes an existing OpPsychologicalAttentionAsistentes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();

        return $this->redirect(['/op-psychological-attention/update','id' => $model->psychological_attention_id]);
    }

    /**
     * Finds the OpPsychologicalAttentionAsistentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpPsychologicalAttentionAsistentes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpPsychologicalAttentionAsistentes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
