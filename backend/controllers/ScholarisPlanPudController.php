<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisPlanPud;
use backend\models\ScholarisPlanPudSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisPlanPudController implements the CRUD actions for ScholarisPlanPud model.
 */
class ScholarisPlanPudController extends Controller
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
     * Lists all ScholarisPlanPud models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $clase = $_GET['id'];
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $searchModel = new ScholarisPlanPudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $clase);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelClase' => $modelClase
        ]);
    }

    /**
     * Displays a single ScholarisPlanPud model.
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
     * Creates a new ScholarisPlanPud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $clase = $_GET['clase'];
        $modelClase = \backend\models\ScholarisClase::findOne($clase); 
        
        $model = new ScholarisPlanPud();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $modelClase->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelClase' => $modelClase
        ]);
    }

    /**
     * Updates an existing ScholarisPlanPud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelClase = \backend\models\ScholarisClase::findOne($model->clase_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelClase' => $modelClase
        ]);
    }

    /**
     * Deletes an existing ScholarisPlanPud model.
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
     * Finds the ScholarisPlanPud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisPlanPud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisPlanPud::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    public function actionCopiar(){
        
        $sentencias = new \backend\models\SentenciasPud();
        
        $clase = $_GET['clase'];
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $model = $sentencias->get_puds_aprobados($modelClase->idcurso, $modelClase->idmateria, $clase);
        
        return $this->render('copiar', [
            'modelClase' => $modelClase,
            'model' => $model
        ]);
        
    }
    
    public function actionCopiaejecutar(){
        //print_r($_GET);
        $pudId = $_GET['pudId'];
        $clase = $_GET['clase'];
//        $modelPudAntiguo = ScholarisPlanPud::findOne($pudId);
        
        $primaryKey = $this->ingresa_cabecera_pud($pudId, $clase);    
        $this->ingresa_detalle_pud($pudId, $primaryKey);
        $this->ingresa_actividades_pud($pudId, $primaryKey);
        
        $this->redirect(['scholaris-plan-pud/index1','id' => $clase]);
        
    }
    
    private function ingresa_cabecera_pud($pudId, $clase){
        
        $usuario = \Yii::$app->user->identity->usuario;
        $fecha = date('Y-m-d H:i:s');
        
        $modelOriginal = ScholarisPlanPud::findOne($pudId);
        $model = new ScholarisPlanPud();
        $model->clase_id = $clase;
        $model->bloque_id = $modelOriginal->bloque_id;
        $model->titulo = $modelOriginal->titulo;
        $model->fecha_inicio = $modelOriginal->fecha_inicio;
        $model->fecha_finalizacion = $modelOriginal->fecha_finalizacion;
        $model->objetivo_unidad = $modelOriginal->objetivo_unidad;
        $model->ac_necesidad_atendida = $modelOriginal->ac_necesidad_atendida;
        $model->ac_adaptacion_aplicada = $modelOriginal->ac_adaptacion_aplicada;
        $model->ac_responsable_dece = $modelOriginal->ac_responsable_dece;
        $model->bibliografia = $modelOriginal->bibliografia;
        $model->observaciones = $modelOriginal->observaciones;
        $model->quien_revisa_id = $modelOriginal->quien_revisa_id;
        $model->quien_aprueba_id = $modelOriginal->quien_aprueba_id;
        $model->estado = $modelOriginal->estado;
        $model->creado_por = $usuario;
        $model->creado_fecha = $fecha;
        $model->actualizado_por = $usuario;
        $model->actualizado_fecha = $fecha;
        $model->pud_original = $modelOriginal->id;
        $model->save();
        
        return $model->getPrimaryKey();
    }
    
    private function ingresa_detalle_pud($pudAnterior, $pudNuevo){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_plan_pud_detalle(pud_id, tipo, codigo, contenido, pertenece_a_codigo, estado)
                    select $pudNuevo, tipo, codigo, contenido, pertenece_a_codigo, estado from scholaris_plan_pud_detalle where pud_id = $pudAnterior;";
        $con->createCommand($query)->execute();
    }
    
    private function ingresa_actividades_pud($pudAnterior, $pudNuevo){
               
        
        $modelDestrezasNuevas = \backend\models\ScholarisPlanPudDetalle::find()
                ->where([
                            'pud_id' => $pudNuevo,
                            'tipo' => 'destreza'
                        ])
                ->all();
        
        foreach ($modelDestrezasNuevas as $des){
            $this->copiar_la_actividad($des->codigo, $pudAnterior, $des->pud->clase_id, $des->id);
        }
        
    }
    
    private function copiar_la_actividad($codigoDestrezaNueva,$pudAntiguo, $claseNueva, $destrezaId){
        
        $usuario = \Yii::$app->user->identity->usuario;
        $modelUser = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
        $fecha = date('Y-m-d H:i:s');
        
        
        $con = \Yii::$app->db;
        $query = "insert into scholaris_actividad(create_date, write_date, create_uid, write_uid
		, title, inicio, fin, tipo_actividad_id, bloque_actividad_id
		, paralelo_id, materia_id
		, calificado, tipo_calificacion, hora_id
		, actividad_original, momento_id, momento_detalle
		, destreza_id, formativa_sumativa, con_nee, grado_nee, observacion_nee)
select 	'$fecha', '$fecha', $modelUser->id, $modelUser->id
		, title, inicio, fin, tipo_actividad_id, bloque_actividad_id
		, $claseNueva, materia_id
		, calificado, tipo_calificacion, hora_id
		, a.id, momento_id, momento_detalle
		, $destrezaId, formativa_sumativa,con_nee, grado_nee, observacion_nee
from 	scholaris_plan_pud_detalle d
		inner join scholaris_actividad a on a.destreza_id = d.id
where	d.codigo = '$codigoDestrezaNueva'
		and pud_id = $pudAntiguo;";
        $con->createCommand($query)->execute();
    }
    
    
    
}
