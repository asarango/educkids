<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisPlanPudDetalle;
use backend\models\ScholarisPlanPudDetalleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * ScholarisPlanPudDetalleController implements the CRUD actions for ScholarisPlanPudDetalle model.
 */
class ScholarisPlanPudDetalleController extends Controller {

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
     * Lists all ScholarisPlanPudDetalle models.
     * @return mixed
     */
    public function actionIndex1() {

        $pudId = $_GET['id'];
        $modelPud = \backend\models\ScholarisPlanPud::findOne($pudId);
        $modelClase = \backend\models\ScholarisClase::findOne($modelPud->clase_id);
        $modelAsignCurr = \backend\models\GenAsignaturas::find()->where(['codigo' => $modelClase->materia_curriculo_codigo])->one();
        
        $modelReporte = \backend\models\ScholarisParametrosOpciones::find()
        ->where(['codigo' => 'repopud'])
        ->one();

        $searchModel = new ScholarisPlanPudDetalleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $pudId);

        $modelDestrezas = $this->toma_destrezas_pci($modelPud->clase->codigo_curso_curriculo, $pudId, $modelAsignCurr->codigo);
        
        $modelActividades = \backend\models\ScholarisActividad::find()
                ->where(['paralelo_id' => $modelPud->clase_id, 'bloque_actividad_id' => $modelPud->bloque_id])
                ->orderBy('inicio','calificado')
                ->all();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelPud' => $modelPud,
                    'modelDestrezas' => $modelDestrezas,
                    'modelActividades' => $modelActividades,
                    'modelReporte' => $modelReporte
        ]);
    }

    /**
     * Displays a single ScholarisPlanPudDetalle model.
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
     * Creates a new ScholarisPlanPudDetalle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisPlanPudDetalle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisPlanPudDetalle model.
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
     * Deletes an existing ScholarisPlanPudDetalle model.
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
     * Finds the ScholarisPlanPudDetalle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisPlanPudDetalle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisPlanPudDetalle::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function toma_destrezas_pci($codigoCurso, $pudId, $codigoMat) {
        $con = Yii::$app->db;
        $query = "select des.id, destreza_codigo, concat(destreza_codigo,' : ',destreza_detalle) as destreza_detalle, destreza_codigo
                    from 	scholaris_plan_pci_evaluacion_destrezas des
                    inner join scholaris_plan_pci_evaluacion eva on eva.id = des.evaluacion_id
                    inner join scholaris_plan_pci pci on pci.id = eva.pci_id
                    where	curso_subnivel_codigo = '$codigoCurso' "
                . "and pci.materia_curriculo_codigo = '$codigoMat' " 
                . "and destreza_codigo not in (select codigo from scholaris_plan_pud_detalle where pud_id = $pudId and tipo = 'destreza');";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionRegistradestreza() {
        $codigo = $_POST['codigo'];
        $pudId = $_POST['pud'];

//        $modelDestreza = \backend\models\ScholarisPlanPciEvaluacionDestrezas::find()->where(['destreza_codigo' => $codigo])->one();
        $modelDestreza = \backend\models\ScholarisPlanPciEvaluacionDestrezas::findOne($codigo);

        $model = new ScholarisPlanPudDetalle();
        $model->pud_id = $pudId;
        $model->tipo = 'destreza';
        $model->codigo = $modelDestreza->destreza_codigo;
        $model->contenido = $modelDestreza->destreza_detalle;
        $model->pertenece_a_codigo = $modelDestreza->destreza_codigo;
        $model->estado = 'construyendose';
        $model->save();


        $modelDestrezaCurr = \backend\models\CurCurriculo::find()
                //->where(['codigo' => $codigo])
                ->where(['codigo' => $modelDestreza->destreza_codigo])
                ->one();
        $modelEvaluacionCurr = \backend\models\CurCurriculo::find()->where(['codigo' => $modelDestrezaCurr->codigo])->one();

        $modelCri = new ScholarisPlanPudDetalle();
        $modelCri->pud_id = $pudId;
        $modelCri->tipo = 'evaluacion';
        $modelCri->contenido = $modelEvaluacionCurr->detalle;
        $modelCri->pertenece_a_codigo = $modelDestreza->destreza_codigo;
        $modelCri->estado = 'construyendose';
        $modelCri->save();
    }

    public function actionMuestradestreza() {
        $pud = $_GET['pud'];
        $modelPud = \backend\models\ScholarisPlanPud::findOne($pud);
        $model = ScholarisPlanPudDetalle::find()->where(['pud_id' => $pud, 'tipo' => 'destreza'])->all();
        
        $html = '';

        foreach ($model as $data) {


            $html .= '<div class="panel panel-primary" style="font-size:10px">';
            $html .= '<div class="panel-heading">';
            $html .= '<h3 class="panel-title">' . $data->codigo . ' ' . $data->contenido . '</h3>';
            
            $html .= '<hr>';
            if($modelPud->estado == 'APROBADO'){
                
            }else{
                $html .= Html::a('', ['eliminadestreza', 'destreza' => $data->id], ['class' => 'btn btn-danger glyphicon glyphicon-trash']);
                $html .= Html::a('', ['editardestreza', 'destreza' => $data->id], ['class' => 'btn btn-default glyphicon glyphicon-pencil']);
            }
            
            $html .= '</div>';
            $html .= '<div class="panel-body">';
            $html .= $this->muestra_detalle_destrezas($data->id);
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }
    
    
    
    private function muestra_detalle_destrezas($destrezaId){
              
        $model = ScholarisPlanPudDetalle::findOne($destrezaId);
                
        $html = '';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-2 text-center">EJE TRANSVERSAL</div>';
        $html .= '<div class="col-md-2 text-center">INDAGACIÓN PERSONAL<br>(EXPLORO Y CONOZCO)</div>';
        $html .= '<div class="col-md-2 text-center">CONSTRUCCIÓN PARTICIPATIVA<br>(COMPRENDO)</div>';
        $html .= '<div class="col-md-2 text-center">CONSOLIDACIÓN DEL CONOCIMIENTO<br>(APLICO LO APRENDIDO)</div>';
        $html .= '<div class="col-md-2 text-center">ACTIVACIÓN DEL CONOCIMIENTO<br>(ME COMPROMETO)</div>';
        $html .= '<div class="col-md-2 text-center">RECURSOS</div>';
        $html .= '</div>';
        
        $eje = $this->datos_detalle_destreza($model->codigo, $model->pud_id, 'eje');
        
        $exploro = $this->datos_momentos_academicos('exploro', $destrezaId);
        $aplico = $this->datos_momentos_academicos('aplico', $destrezaId);
        $comprometo = $this->datos_momentos_academicos('comprometo', $destrezaId);
        $comprendo = $this->datos_momentos_academicos('comprendo', $destrezaId);

        $recurso = $this->datos_detalle_destreza($model->codigo, $model->pud_id, 'recurso');
        $evaluacion = $this->datos_detalle_destreza($model->codigo, $model->pud_id, 'evaluacion');
        $indicador = $this->datos_detalle_destreza($model->codigo, $model->pud_id, 'indicador');
        $tecnicas = $this->datos_detalle_destreza($model->codigo, $model->pud_id, 'tecnicas');
        
                
        $html .= '<div class="row">';
        $html .= '<div class="col-md-2 text-center"><h3>'.$eje.'</h3></div>';
        
        if($exploro){
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-ok"></span></h3></div>';
        }else{
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-remove"></span></h3></div>';
        }
        
        if($comprendo){
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-ok"></span></h3></div>';
        }else{
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-remove"></span></h3></div>';
        }
        
        if($aplico){
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-ok"></span></h3></div>';
        }else{
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-remove"></span></h3></div>';
        }
        
        if($comprometo){
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-ok"></span></h3></div>';
        }else{
            $html .= '<div class="col-md-2 text-center"><h3><span class="glyphicon glyphicon-remove"></span></h3></div>';
        }
        
        $html .= '<div class="col-md-2 text-center"><h3>'.$recurso.'</h3></div>';
        
        $html .= '</div>';
        
        $html .= '<hr>';
        
        
        $html .= '<div class="row">';
        $html .= '<div class="col-md-4 text-center">CRITERIO DE EVALUACION</div>';
        $html .= '<div class="col-md-4 text-center">INDICADORES PARA LA EVALUACION DEL CRITERIO</div>';
        $html .= '<div class="col-md-4 text-center">TIPOS, TÉCNICAS E INSTRUMENTOS</div>';        
        $html .= '</div>';
        
        $html .= '<div class="row">';
        
        if($evaluacion){
            $html .= '<div class="col-md-4 text-center"><h3><span class="glyphicon glyphicon-ok"></span></h3></div>';
        }else{
            $html .= '<div class="col-md-4 text-center"><h3><span class="glyphicon glyphicon-remove"></span></h3></div>';
        }
        
        $html .= '<div class="col-md-4 text-center"><h3>'.$indicador.'</h3></div>';
        $html .= '<div class="col-md-4 text-center"><h3>'.$tecnicas.'</h3></div>';
        
        $html .= '</div>';
        
        return $html;
    }
    
    
    private function datos_detalle_destreza($codigo, $pudId, $tipo){
        $con = Yii::$app->db;
        $query = "select tipo
                                    ,count(tipo) as total
                    from 	scholaris_plan_pud_detalle 
                    where 	pertenece_a_codigo = '$codigo'
                                    and tipo = '$tipo'
                                    and pud_id = $pudId
                    group by tipo
                    order by tipo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        if($res){
            return $res['total'];
        }else{
            return 0;
        }
        
        
    }
    
    private function datos_momentos_academicos($codigo, $detalleId){
        $con = Yii::$app->db;
        $query = "select 	count(a.id) as total
from 	scholaris_actividad a
		inner join scholaris_momentos_academicos m on m.id = a.momento_id
where 	a.destreza_id = $detalleId
		and m.codigo = '$codigo';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        
                
        if($res){
            return $res['total'];
        }else{
            return 0;
        }
    }


    

    public function actionEliminadestreza() {
        $destrezaId = $_GET['destreza'];
        
        $modelDestreza = ScholarisPlanPudDetalle::findOne($destrezaId);
        $pudId = $modelDestreza->pud_id;
        
        ScholarisPlanPudDetalle::deleteAll(['pud_id' => $pudId, 'pertenece_a_codigo' => $modelDestreza->codigo]);
        
        return $this->redirect(['index1', 'id' => $pudId]);
        
    }

    public function actionEditardestreza() {
        $detallePud = $_GET['destreza'];
       
        $model = ScholarisPlanPudDetalle::findOne($detallePud);
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($model->pud->bloque_id);
        
        $searchModel = new \backend\models\ScholarisActividadSearch();
        $dataProvider = $searchModel->porDestreza(Yii::$app->request->queryParams, $detallePud);

        return $this->render('editar', [
                    'model' => $model,
                    'modelBloque' => $modelBloque,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionIngresardetalles() {

        $pud = $_POST['pud'];

        $model = new ScholarisPlanPudDetalle();
        $model->pud_id = $pud;
        $model->tipo = $_POST['tipo'];
        $model->contenido = $_POST['contenido'];
        $model->pertenece_a_codigo = $_POST['codigo'];
        $model->estado = 'construyendose';

        $model->save();
    }

    public function actionModificardetalles() {
        $id = $_POST['id'];
        $contenido = $_POST['contenido'];
        $model = ScholarisPlanPudDetalle::findOne($id);


        $model->contenido = $contenido;
        $model->save();
    }

    public function actionRegistraselecciones() {
//        print_r($_POST);
//        
//        die();
        $id = $_POST['id'];
        $codigo = $_POST['codigo'];
        $contenido = $_POST['contenido'];
        $opcion = $_POST['opcion'];

        if ($opcion == 'eje') {
            
            
            $model = new ScholarisPlanPudDetalle();
            $model->pud_id = $id;
            $model->tipo = $opcion;
            $model->contenido = $contenido;
            $model->pertenece_a_codigo = $codigo;
            $model->estado = 'construyendose';
          
            
            $model->save();
        }else if($opcion == 'indicador'){
            $modelCurInd = \backend\models\CurCurriculo::find()->where(['codigo' => $contenido])->one();
            
            $model = new ScholarisPlanPudDetalle();
            $model->pud_id = $id;
            $model->tipo = $opcion;
            $model->contenido = $modelCurInd->detalle;
            $model->pertenece_a_codigo = $codigo;
            $model->estado = 'construyendose';
            $model->save();
        }else if($opcion == 'tecnicas'){
            $model = new ScholarisPlanPudDetalle();
            $model->pud_id = $id;
            $model->tipo = $opcion;
            $model->contenido = $contenido;
            $model->pertenece_a_codigo = $codigo;
            $model->estado = 'construyendose';
            $model->save();
        }else if($opcion == 'tipos'){
            $model = new ScholarisPlanPudDetalle();
            $model->pud_id = $id;
            $model->tipo = $opcion;
            $model->contenido = $contenido;
            $model->pertenece_a_codigo = $codigo;
            $model->estado = 'construyendose';
            $model->save();
        }else if($opcion == 'instrumentos'){
            $model = new ScholarisPlanPudDetalle();
            $model->pud_id = $id;
            $model->tipo = $opcion;
            $model->contenido = $contenido;
            $model->pertenece_a_codigo = $codigo;
            $model->estado = 'construyendose';
            $model->save();
        }
        else if($opcion == 'recurso'){
            $model = new ScholarisPlanPudDetalle();
            $model->pud_id = $id;
            $model->tipo = $opcion;
            $model->contenido = $contenido;
            $model->pertenece_a_codigo = $codigo;
            $model->estado = 'construyendose';
            $model->save();
        }
    }
    
    public function actionMustraejes(){
        $id = $_GET['id'];
        $modelDestreza = ScholarisPlanPudDetalle::findOne($id);
        
        $model = ScholarisPlanPudDetalle::find()->where(['pud_id' => $modelDestreza->pud_id, 'tipo' => 'eje', 'pertenece_a_codigo' => $modelDestreza->codigo])->all();
        $html = '';
        $html .= '<ul>';
        
        foreach ($model as $data){            
            $html .= '<li>';
            $html .= Html::a('', ['eliminaseleccion', 'id' => $data->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
            $html .= $data->contenido;
            $html .= '</li>';
        }
                
        $html .= '</ul>';
        
        return $html;
    }
    
    
    public function actionMuestraindicadores(){
        $id = $_GET['id'];
        $modelDestreza = ScholarisPlanPudDetalle::findOne($id);
        
        $model = ScholarisPlanPudDetalle::find()->where(['pud_id' => $modelDestreza->pud_id, 'tipo' => 'indicador', 'pertenece_a_codigo' => $modelDestreza->codigo])->all();
        $html = '';
        $html .= '<ul>';
        
        foreach ($model as $data){            
            $html .= '<li>';
            $html .= Html::a('', ['eliminaseleccion', 'id' => $data->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
            $html .= $data->contenido;
            $html .= '</li>';
        }
                
        $html .= '</ul>';
        
        return $html;
    }
    
    
    public function actionMuestratecnicas(){
        $id = $_GET['id'];
        $opcion = $_GET['opcion'];
        $modelDestreza = ScholarisPlanPudDetalle::findOne($id);
        
        $model = ScholarisPlanPudDetalle::find()->where([
                        'pud_id' => $modelDestreza->pud_id, 
                        'tipo' => $opcion,
                        'pertenece_a_codigo' => $modelDestreza->codigo
                ])->all();
        $html = '';
        $html .= '<ul>';
        
        foreach ($model as $data){            
            $html .= '<li>';
            $html .= Html::a('', ['eliminaseleccion', 'id' => $data->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
            $html .= $data->contenido;
            $html .= '</li>';
        }
                
        $html .= '</ul>';
        
        return $html;
    }
    
    
    public function actionMuestrarecursos(){
        $id = $_GET['id'];
        $modelDestreza = ScholarisPlanPudDetalle::findOne($id);
        
        $model = ScholarisPlanPudDetalle::find()->where(['pud_id' => $modelDestreza->pud_id, 'tipo' => 'recurso', 'pertenece_a_codigo' => $modelDestreza->codigo])->all();
        $html = '';
        $html .= '<ul>';
        
        foreach ($model as $data){            
            $html .= '<li>';
            $html .= Html::a('', ['eliminaseleccion', 'id' => $data->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
            $html .= $data->contenido;
            $html .= '</li>';
        }
                
        $html .= '</ul>';
        
        return $html;
    }
    
    public function actionEliminaseleccion(){
        $id = $_GET['id'];
              
        $model = ScholarisPlanPudDetalle::findOne($id);
        $destrezaCodigo = $model->pertenece_a_codigo;
        
        
        
        $modelDestreza = ScholarisPlanPudDetalle::find()->where([
                'codigo' => $destrezaCodigo, 
                'tipo' => 'destreza',
                'pud_id' => $model->pud_id])->one();
        $destrezaId = $modelDestreza->id;
        
        $model->delete();
        
        return $this->redirect(['editardestreza', 'destreza' => $modelDestreza->id]);
              
    }
    
    
    public function actionCreateactividad(){
        $sentencias = new \frontend\models\SentenciasSql();
        
        $destreza =$_GET['id'];
        
        $modelDestreza = ScholarisPlanPudDetalle::findOne($destreza);
        
        $model = new \backend\models\ScholarisActividad();
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($modelDestreza->pud->bloque_id);
        
        $modelHorarios = $sentencias->fechasDisponibles($modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelDestreza->pud->clase_id, $modelBloque->id);
        
        
        if ($model->load(Yii::$app->request->post())) {
            $horaAsignada = $sentencias->hora_asignada_automaticamente($modelDestreza->pud->clase_id, $model->inicio);
            
            $model->hora_id = $horaAsignada;
            $model->fin = $model->inicio;
            $model->descripcion = $model->momento_detalle;
            $model->save();
            $primary = $model->getPrimaryKey();
//            return $this->redirect(['editardestreza', 'destreza' => $model->destreza_id]);
            return $this->redirect(['scholaris-actividad-recursos/index1', 'id' => $primary]);
        }
        
        
        return $this->render('crear-actividad',[
                    'modelDestreza' => $modelDestreza,
                    'model' => $model,
                    'modelHorarios' => $modelHorarios
                ]
                );
        
        
    }
    
    public function actionActualizar(){
        
        $sentencias = new \frontend\models\SentenciasSql();
        $model = \backend\models\ScholarisActividad::findOne($_GET['id']);
                
        $destreza =$model->destreza_id;
        
        $modelDestreza = ScholarisPlanPudDetalle::findOne($destreza);
        
//        $model = new \backend\models\ScholarisActividad();
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($modelDestreza->pud->bloque_id);
        
        $modelHorarios = $sentencias->fechasDisponibles($modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelDestreza->pud->clase_id, $modelBloque->id);
        
        
        if ($model->load(Yii::$app->request->post())) {
//            $horaAsignada = $sentencias->hora_asignada_automaticamente($modelDestreza->pud->clase_id, $model->inicio);
//            
//            $model->hora_id = $horaAsignada;
//            $model->fin = $model->inicio;

            $model->save();
            return $this->redirect(['editardestreza', 'destreza' => $model->destreza_id]);
        }
        
        
        return $this->render('crear-actividad',[
                    'modelDestreza' => $modelDestreza,
                    'model' => $model,
                    'modelHorarios' => $modelHorarios
                ]
                );
        
        
    }
    
    public function actionBorrar(){
        
        if(isset($_GET['id'])){
            $actividadId = $_GET['id'];
        }elseif(isset($_POST['id'])){
            $actividadId = $_POST['id'];
        }
        
        
        $modelActividad = \backend\models\ScholarisActividad::findOne($actividadId);
        if(isset($_POST['id'])){
            $modelActividad->delete();
            return $this->redirect(['editardestreza', 'destreza' => $modelActividad->destreza_id]);
        }
        $modelCalificaciones = \backend\models\ScholarisCalificaciones::find()
                ->where(['idactividad' => $actividadId])
                ->all();
        if(count($modelCalificaciones) > 0){
            return $this->render('borrar',[
                'modelActividad' => $modelActividad,
                'modelCalificaciones' => $modelCalificaciones
                ]);           
        }else{
            $modelActividad->delete();
            return $this->redirect(['editardestreza', 'destreza' => $modelActividad->destreza_id]);            
        }
        
    }
    
    public function actionRevisar(){
        
        $sentencias = new \backend\models\SentenciasPud();
        
        $pudId = $_GET['pudId'];
        $estadoNuevo = 'REVISIONC';
        
        $sentencias->cambia_estado($pudId, $estadoNuevo);
        
        return $this->redirect(["index1",'id' => $pudId]);
        
    }
    
    public function actionActualizaperiodos(){
        //print_r($_POST);
        $destrezaId = $_POST['id'];
        $periodos = $_POST['contenido'];
        
        $model = ScholarisPlanPudDetalle::findOne($destrezaId);
        $model->cantidad_periodos;
        
        $model->cantidad_periodos = $periodos;
        //echo $model->cantidad_periodos;
        //die();
        $model->save();
        
        //return $this->redirect(["index1",'id' => $destrezaId]);
    }
    

}
