<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisPlanPca;
use backend\models\ScholarisPlanPcaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisPlanPcaController implements the CRUD actions for ScholarisPlanPca model.
 */
class ScholarisPlanPcaController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all ScholarisPlanPca models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ScholarisPlanPcaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $periodoId = Yii::$app->user->identity->periodo_id;
        $listCursos = \backend\models\OpCourse::find()
                ->innerJoin("op_section s", "s.id = op_course.section")
                ->innerJoin("scholaris_op_period_periodo_scholaris sop", "sop.op_id = s.period_id")
                ->where(['sop.scholaris_id' => $periodoId])
                ->all();
        

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'listCursos' => $listCursos,
        ]);
    }

    /**
     * Displays a single ScholarisPlanPca model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisPlanPca model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $periodoId = Yii::$app->user->identity->periodo_id;

        $model = new ScholarisPlanPca();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
                    'model' => $model,
                    'periodoId' => $periodoId
        ]);
    }
    
    
    

    /**
     * Updates an existing ScholarisPlanPca model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisPlanPca model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisPlanPca model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisPlanPca the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisPlanPca::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    public function actionDetalle(){
        $pca_id = $_GET['id'];
        $modelPca = $this->findModel($pca_id);
        
        
        $modelObjetivosGenerales = \backend\models\ScholarisPlanPcaDetalle::find()
                ->where(['pca_id' => $pca_id, 'tipo' => 'objgeneral'])
                ->all();
        
        $modelObjetivosArea = \backend\models\ScholarisPlanPcaDetalle::find()
                ->where(['pca_id' => $pca_id, 'tipo' => 'objarea'])
                ->all();
        
        $modelEjes = \backend\models\ScholarisPlanPcaDetalle::find()
                ->where(['pca_id' => $pca_id, 'tipo' => 'ejes'])
                ->all();
        
        $modelActitud = \backend\models\ScholarisPlanPcaDetalle::find()
                ->where(['pca_id' => $pca_id, 'tipo' => 'actitud'])
                ->all();
        
        return $this->render('detalle',[
            'modelPca' => $modelPca,
            'modelObjetivosGenerales' => $modelObjetivosGenerales,
            'modelObjetivosArea' => $modelObjetivosArea,
            'modelEjes' => $modelEjes,
            'modelActitud' => $modelActitud,
        ]);
    }
    
    public function actionObjetivos(){
//        print_r($_POST);
        
        $usuario = Yii::$app->user->identity->usuario;
        $fecha = date("Y-m-d H:i:s");
        
        $objetivo = $_POST['objetivo'];
        $pca = $_POST['pca'];                
        $modelObjetivo = \backend\models\CurCurriculo::findOne($objetivo);
        
        
        $model = new \backend\models\ScholarisPlanPcaDetalle();
        $model->pca_id = $pca;
        $model->tipo = $modelObjetivo->tipo_referencia;
        $model->codigo = $modelObjetivo->codigo;
        $model->detalle = $modelObjetivo->detalle;
        $model->creado_por = $usuario;
        $model->creado_fecha = $fecha;
        $model->actualizado_por = $usuario;
        $model->actualizado_fecha = $fecha;          
        
        $model->save();
        
        return $this->redirect(['detalle', 'id' => $pca]);
        
    }
    
    
    public function actionUnidades(){

        $pca = $_GET['pca'];
        $modelPca = ScholarisPlanPca::findOne($pca);
        
        $modelUnidades = \backend\models\ScholarisPlanPcaUnidades::find()
                ->where(['pca_id' => $pca])
                ->all();                
        
        $modelObjetivos = \backend\models\CurCurriculo::find()
                ->select(['id',"concat(codigo,' ',substring(detalle,0,30),'...') as codigo"])
                ->where(['tipo_referencia' => 'objgeneral', 'materia_id' => $modelPca->malla_materia_curriculo_id])
                ->all();
        
        $modelDestrezas = \backend\models\CurCurriculo::find()
                ->select(['id',"concat(codigo,' ',substring(detalle,0,30),'...') as codigo"])
                ->where(['tipo_referencia' => 'destrezas', 'materia_id' => $modelPca->malla_materia_curriculo_id])
                ->all();
        
        $modelCriterioIndica = \backend\models\CurCurriculo::find()
                ->select(['id',"concat(codigo,' ',substring(detalle,0,30),'...') as codigo"])
                ->where(['in','tipo_referencia', ['evaluacion','indicador'], 'materia_id' => $modelPca->malla_materia_curriculo_id])
                ->all();
        
        $modelOrientaciones = \backend\models\CurCurriculo::find()
                ->select(['id',"concat(codigo,' ',substring(detalle,0,30),'...') as codigo"])
                ->where(['in','tipo_referencia', ['orientacion'], 'materia_id' => $modelPca->malla_materia_curriculo_id])
                ->all();
        
        return $this->render('unidades',[
            'modelPca' => $modelPca,
            'modelUnidades' => $modelUnidades,
            'modelObjetivos' => $modelObjetivos,
            'modelDestrezas' => $modelDestrezas,
            'modelCriterioIndica' => $modelCriterioIndica,
            'modelOrientaciones' => $modelOrientaciones,
        ]);
    }
    
    
    public function actionCreaunidades(){
        
        $pca = $_POST['pca'];
        $unidad = $_POST['unidad'];
        $semanas = $_POST['semanas'];
        $imprevistos = $_POST['imprevistos'];
        $periodos = $_POST['periodos'];
        
        
        $model = new \backend\models\ScholarisPlanPcaUnidades();
        $model->pca_id = $pca;
        $model->unidad = $unidad;
        $model->semanas_destinadas = $semanas;
        $model->periodos_semanales = $periodos;
        $model->periodos_inprevistos = $imprevistos;
        
        $model->save();
        return $this->redirect(['unidades', 'pca' => $pca]);
        
    }
    
    public function actionCreadesarrollo(){
        
//        print_r($_POST);
//        die();
        
        $unidad = $_POST['unidad'];
        $objetivo = $_POST['objetivo'];
        
        $modelObj = \backend\models\CurCurriculo::findOne($objetivo);
        $modelUnidad = \backend\models\ScholarisPlanPcaUnidades::findOne($unidad);
        
        $model = new \backend\models\ScholarisPlanPcaUnidadesDetalle();
        $model->unidad_id = $unidad;
        $model->tipo_referencia = $modelObj->tipo_referencia;
        $model->codigo = $modelObj->codigo;
        $model->detalle = $modelObj->detalle;
        
        $model->save();
        
        return $this->redirect(['unidades', 'pca' => $modelUnidad->pca_id]);
        
    }
    
    
    public function actionEliminar(){
        $detalle = $_GET['detalle'];               
        $model = \backend\models\ScholarisPlanPcaUnidadesDetalle::findOne($detalle);
        $pca = $model->unidad->pca_id;
        
        $model->delete();
        
        return $this->redirect(['unidades','pca' => $pca]);
    }
    
    public function actionEliminarunidad(){
        $unidad = $_GET['unidad'];
        $model = \backend\models\ScholarisPlanPcaUnidades::findOne($unidad);
        
        $pca = $model->pca_id;
        $model->delete();
        
        return $this->redirect(['unidades','pca' => $pca]);        
    }

}
