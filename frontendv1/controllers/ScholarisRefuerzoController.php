<?php

namespace frontend\controllers;

use Yii;
use backend\models\ScholarisRefuerzo;
use backend\models\ScholarisRefuerzoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisRefuerzoController implements the CRUD actions for ScholarisRefuerzo model.
 */
class ScholarisRefuerzoController extends Controller
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
     * Lists all ScholarisRefuerzo models.
     * @return mixed
     */
    public function actionRefuerzo()
    {
        
        
//        print_r($_GET);
        $this->ingresa_novedades($_GET['grupo'], $_GET['bloque']);
        
        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
                ->where(['id' => $_GET['grupo']])
                ->one();
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::find()->where(['id' => $_GET['bloque']])->one();
        
        $searchModel = new ScholarisRefuerzoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelGrupo->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelGrupo' => $modelGrupo,
            'modelBloque' => $modelBloque
        ]);
    }
    
    
    private function ingresa_novedades($grupo, $bloque){
        $sentencias = new \backend\models\Notas();   
        $sentencias2 = new \frontend\models\SentenciasSql();
        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['id' => $grupo])->one();        
        $modelInsumos =  $sentencias2->get_insumos($modelGrupo->clase_id, $bloque);
        
        foreach ($modelInsumos as $insumo){
            $modelRefuerzo = ScholarisRefuerzo::find()
                    ->where([
                            'grupo_id' => $grupo,
                            'bloque_id' => $bloque,
                            'orden_calificacion' => $insumo['grupo_numero'],
                        ])
                    ->one();
            
            if(!$modelRefuerzo){
                $this->ingresa_novedades_2($modelGrupo->estudiante_id, $modelGrupo->clase_id, $insumo['grupo_numero'], $bloque, $grupo);
            }            
        }        
    }
    
    private function ingresa_novedades_2($alumno, $clase, $orden,$bloque, $grupo){
        $sentencias = new \backend\models\Notas();
        $modelNota = $sentencias->get_promedio_insumo($clase, $alumno, $bloque, $orden);
        $modelMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        
        if($modelNota['calificacion'] < $modelMinima['valor']){
            $model = new ScholarisRefuerzo();
            $model->grupo_id = $grupo;
            $model->bloque_id = $bloque;
            $model->orden_calificacion = $orden;
            $model->promedio_normal = $modelNota['calificacion'];
            $model->nota_refuerzo = 0;
            $model->nota_final = 0;
            $model->save();
        }
    }

    /**
     * Displays a single ScholarisRefuerzo model.
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
     * Creates a new ScholarisRefuerzo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisRefuerzo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisRefuerzo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $sentencias = new \backend\models\Notas();
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->nota_refuerzo >= $model->promedio_normal){
                $promedio = ($model->promedio_normal + $model->nota_refuerzo)/2;
                $promedio = $sentencias->truncarNota($promedio, 2);
                $model->nota_final = $promedio;
            }else{
                $model->nota_final = $model->promedio_normal;
            }            
            $model->save();
            
            $sentencias->actualiza_parcial($model->bloque_id, $model->grupo->estudiante_id, $model->grupo->clase_id);
            
            return $this->redirect(['refuerzo', 'grupo' => $model->grupo_id, 'bloque' => $model->bloque_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisRefuerzo model.
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
     * Finds the ScholarisRefuerzo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisRefuerzo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisRefuerzo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
