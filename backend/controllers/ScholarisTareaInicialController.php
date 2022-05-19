<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisTareaInicial;
use backend\models\ScholarisTareaInicialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ScholarisTareaInicialController implements the CRUD actions for ScholarisTareaInicial model.
 */
class ScholarisTareaInicialController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisTareaInicial models.
     * @return mixed
     */
    public function actionIndex1() {

        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];

        $modelClase = \backend\models\ScholarisClase::findOne($clase);

        $searchModel = new ScholarisTareaInicialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $clase, $quimestre);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelClase' => $modelClase,
                    'quimestre' => $quimestre
        ]);
    }

    /**
     * Displays a single ScholarisTareaInicial model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {



        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Detalle de Archivo ",
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new ScholarisTareaInicial model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate()
    {
        
        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        $fecha = date('Ymdhis');
        
        
        $model = new ScholarisTareaInicial();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->fecha_entrega = $model->fecha_entrega.' 23:59:59';

                $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'nombre_archivo');
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);


                if (!empty($imagenSubida)) {
                    $path = '../web/imagenes/instituto/archivos-profesor/';
                    $model->nombre_archivo = $fecha . $model->nombre_archivo . $imagenSubida->name;
                    $model->tipo_material = 'ARCHIVO';
                    $model->save();
                    $imagenSubida->saveAs($path . $model->nombre_archivo);
                }


            $model->save();
            
            return $this->redirect(['index1', 'clase' => $clase, 'quimestre' => $quimestre]);
        }

        return $this->render('create', [
            'model' => $model,
            'clase' => $clase,
            'quimestre' => $quimestre
        ]);
    }
    
    
    public function actionCreate1() {

        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        $fecha = date('Ymdhis');


        $request = Yii::$app->request;
        $model = new ScholarisTareaInicial();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear Tarea",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'clase' => $clase,
                        'quimestre' => $quimestre
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Grabar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $model->fecha_entrega = $model->fecha_entrega.' 23:59:59';

                $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'nombre_archivo');
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);


                if (!empty($imagenSubida)) {
                    $path = '../web/imagenes/instituto/archivos-profesor/';
                    $model->nombre_archivo = $fecha . $model->nombre_archivo . $imagenSubida->name;
                    $model->tipo_material = 'ARCHIVO';
                    $model->save();
                    $imagenSubida->saveAs($path . $model->nombre_archivo);
                }



                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear nuevo Archivo",
                    'content' => '<span class="text-success">Creado correctamente!!!</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    //Html::a('Crear Más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Crear nueva Tarea",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'clase' => $clase,
                        'quimestre' => $quimestre
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Grabar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
        }
    }
    
    
    public function actionCrearvideo() {
        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        $fecha = date('Ymdhis');
        
        
        $model = new ScholarisTareaInicial();

        if ($model->load(Yii::$app->request->post())) {
            
           $model->nombre_archivo = 'Video conferencia';
                    $model->tipo_material = 'VIDEOCONFERENCIA';
                    $model->save();
            
            return $this->redirect(['index1', 'clase' => $clase, 'quimestre' => $quimestre]);
        }

        return $this->render('crearvideo', [
            'model' => $model,
            'clase' => $clase,
            'quimestre' => $quimestre
        ]);
    }
    
    
    
    public function actionCrearvideo1() {

        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        $fecha = date('Ymdhis');


        $request = Yii::$app->request;
        $model = new ScholarisTareaInicial();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear Videoconferencia",
                    'content' => $this->renderAjax('crearvideo', [
                        'model' => $model,
                        'clase' => $clase,
                        'quimestre' => $quimestre
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Grabar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {



//                $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'nombre_archivo');
//                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
//
//
//                if (!empty($imagenSubida)) {
//                    $path = '../web/imagenes/instituto/archivos-profesor/';
                    $model->nombre_archivo = 'Video conferencia';
                    $model->tipo_material = 'VIDEOCONFERENCIA';
                    $model->save();
//                    $imagenSubida->saveAs($path . $model->nombre_archivo);
//                }



                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear nuevo Archivo",
                    'content' => '<span class="text-success">Creado correctamente!!!</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    //Html::a('Crear Más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Crear nueva videoconferencia",
                    'content' => $this->renderAjax('crearvideo', [
                        'model' => $model,
                        'clase' => $clase,
                        'quimestre' => $quimestre
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Grabar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('crearvideo', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing ScholarisTareaInicial model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    
    
    public function actionUpdate($id) {
        
        $fecha = date('Ymdhis');

        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $nombreArchivo = $model->nombre_archivo;

        if ($model->load(Yii::$app->request->post())) {
            $model->nombre_archivo = $nombreArchivo;
            $model->save();
//           if (\yii\web\UploadedFile::getInstance($model, 'nombre_archivo')) {
//                    $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'nombre_archivo');
//                    $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
//
//
//                    if (!empty($imagenSubida)) {
//                        $path = '../web/imagenes/instituto/archivos-profesor/';
//                        $model->nombre_archivo = $fecha . $model->nombre_archivo . $imagenSubida->name;
//                        $model->save();
//                        $imagenSubida->saveAs($path . $model->nombre_archivo);
//                    }
//                }else{
////                    $model->nombre_archivo = $nombreArchivo;
//                    $model->save();
//                }
            
            return $this->redirect(['index1', 'clase' => $model->clase_id, 'quimestre' => $model->quimestre_codigo]);
        }

        return $this->render('update', [
            'model' => $model,
            'clase' => $model->clase_id,
            'quimestre' => $model->quimestre_codigo
        ]);
    }
    
    public function actionUpdate1($id) {

        $fecha = date('Ymdhis');

        $request = Yii::$app->request;
        $model = $this->findModel($id);
        
        $nombreArchivo = $model->nombre_archivo;

        if ($request->isAjax) {

            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Modificar #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'clase' => $model->clase_id,
                        'quimestre' => $model->quimestre_codigo,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                if (\yii\web\UploadedFile::getInstance($model, 'nombre_archivo')) {
                    $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'nombre_archivo');
                    $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);


                    if (!empty($imagenSubida)) {
                        $path = '../web/imagenes/instituto/archivos-profesor/';
                        $model->nombre_archivo = $fecha . $model->nombre_archivo . $imagenSubida->name;
                        $model->save();
                        $imagenSubida->saveAs($path . $model->nombre_archivo);
                    }
                }else{
                    $model->nombre_archivo = $nombreArchivo;
                    $model->save();
                }



                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "ScholarisTareaInicial #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Modificar #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                            'model' => $model,
                ]);
            }
        }
    }
    
    
    
    public function actionVideoconferencia(){
        $id = $_GET['id'];
        
        $model = \backend\models\ScholarisTareaInicial::findOne($id);
//        
//        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'clase' => $model->clase_id, 
                                    'quimestre' => $model->quimestre_codigo]);
        }
//        
//        
        return $this->render('videoconferencia',[
            'model' => $model
        ]);
        
    }
    
    

    /**
     * Delete an existing ScholarisTareaInicial model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEliminar($id) {
        
        $model = $this->findModel($id);
        $clase = $model->clase_id;
        $quimestre = $model->quimestre_codigo;
        
        $modelEntregas = \backend\models\ScholarisTareaInicialResuelta::find()->where(['tarea_inicial_id' => $id])->all();
        if(count($modelEntregas) > 0){
            return $this->render('eliminar',[
                'model' => $model,
                'modelEntregas' => $modelEntregas
            ]);
        } else {
            $model->delete();       
            return $this->redirect(['index1', 'clase' => $clase, 'quimestre' => $quimestre]);
        }
        
    }
    
    public function actionEjecutaEliminar(){
        $tareaId = $_GET['tarea_id'];
        $model = $this->findModel($tareaId);
        $clase = $model->clase_id;
        $quimestre = $model->quimestre_codigo;
        
        $con = Yii::$app->db;
        $query = "delete from scholaris_tarea_inicial_resuelta where tarea_inicial_id = $tareaId;";
        $con->createCommand($query)->execute();
        
        $model->delete();
        
        return $this->redirect(['index1', 'clase' => $clase, 'quimestre' => $quimestre]);
        
    }
    
    
    public function actionDelete1($id) {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing ScholarisTareaInicial model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete() {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the ScholarisTareaInicial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisTareaInicial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisTareaInicial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDescargar() {
        $archivoId = $_GET['id'];

        $model = ScholarisTareaInicial::findOne($archivoId);
        $archivoNombre = $model->nombre_archivo;

        $path = "../../backend/web/imagenes/instituto/archivos-profesor/" . $archivoNombre;

        return $this->render('descargar', ['path' => $path]);

        //return \Yii::$app->response->sendFile($path . $archivoNombre);
    }
    
    
    public function actionRecibir(){
                
        $archivoId = $_GET['id'];
        $quimestre = $_GET['quimestre'];
        
        $modelArchivo = \backend\models\ScholarisTareaInicial::findOne($archivoId);
        $modelClase = \backend\models\ScholarisClase::findOne($modelArchivo->clase_id);
        $modelQuimestre = \backend\models\ScholarisQuimestre::find()->where(['codigo' => $quimestre]);
        
        $modelAlumnos = $this->toma_alumnos_archivos($archivoId, $modelClase->id);
        
        
        return $this->render('recibir',[
            'modelClase' => $modelClase,
            'modelQuimestre' => $modelQuimestre,
            'modelAlumnos' => $modelAlumnos,
            'modelArchivo' => $modelArchivo
        ]);
        
    }
    
    private function toma_alumnos_archivos($tarea, $clase){
        $con = Yii::$app->db;
        $query = "select 	r.id
                                    ,r.archivo 
                                    ,s.last_name 
                                    ,s.first_name 
                                    ,s.middle_name 
                                    ,r.observacion_profesor
                    from 	scholaris_grupo_alumno_clase g  
                                    inner join op_student s on s.id = g.estudiante_id
                                    left join scholaris_tarea_inicial_resuelta r on g.estudiante_id  = r.alumno_id
                                                            and r.tarea_inicial_id = $tarea
                                    left join scholaris_tarea_inicial i on i.id = r.tarea_inicial_id 				
                    where 	g.clase_id = $clase
                    order by s.last_name, s.first_name, s.middle_name;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actionDescargaral(){
        
        $archivoNombre = $_GET['archivo'];

        $path = "../../backend/web/imagenes/instituto/archivos-profesor/" . $archivoNombre;

        return $this->render('descargar', ['path' => $path]);
    }
    
    public function actionUpdateobservacion(){
        $archivoId = $_GET['id'];
        $model = \backend\models\ScholarisTareaInicialResuelta::findOne($archivoId);
        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['recibir', 'id' => $model->tarea_inicial_id, 
                                    'quimestre' => $model->tareaInicial->quimestre_codigo]);
        }
        
        
        return $this->render('updateobservacion',[
            'model' => $model
        ]);
        
    }

}
