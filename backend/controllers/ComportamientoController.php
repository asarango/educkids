<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ScholarisClase;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisAsistenciaProfesor;
use app\models\ScholarisAsistenciaComportamiento;
use app\models\ScholarisAsistenciaComportamientoDetalle;
use backend\models\PlanificacionOpciones;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use backend\models\ScholarisActividad;
use backend\models\ScholarisAsistenciaClaseTema;

/**
 * ScholarisAsistenciaProfesorController implements the CRUD actions for ScholarisAsistenciaProfesor model.
 */
class ComportamientoController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }

   
    public function actionIndex($id) {

        $con = Yii::$app->db;
        $fechaDesde = date("Y-m-d").' 00:00:00';
        $fechaHasta = date("Y-m-d").' 23:59:59';       
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $id])
                ->one();

        $modelClase = ScholarisClase::find()->where(['id' => $modelAsistencia->clase_id])->one();
               
        $query = "select sgac.id,sgac.clase_id,sgac.estudiante_id,os.last_name,os.first_name,os.middle_name 
                            from op_student os, scholaris_grupo_alumno_clase sgac 
                            where os.id = sgac.estudiante_id
                            and sgac.clase_id = $modelAsistencia->clase_id order by os.last_name,os.first_name,os.middle_name;";

        $modelGrupo = $con->createCommand($query)->queryAll();

        

        $modelActividades = ScholarisActividad::find()
                ->where([
                          'paralelo_id' => $modelClase->id,
                          //'inicio' => date("Y-m-d")
                        ])
                ->andWhere(['between', 'inicio', $fechaDesde, $fechaHasta])
                ->all();
        
        $modelTemas = ScholarisAsistenciaClaseTema::find()
                ->where(['asistencia_profesor_id' => $id])
                ->all();
        

        return $this->render('index', [
                    'modelClase' => $modelClase,
                    'modelGrupo' => $modelGrupo,
                    'modelAsistencia' => $modelAsistencia,
                    'modelActividades' => $modelActividades,
                    'modelTemas' => $modelTemas,
        ]);
    }

    public function actionDetalle($alumnoId, $asistenciaId) {
        


        $model = new ScholarisAsistenciaAlumnosNovedades();
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();
        
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where([
                    'clase_id' => $modelAsistencia->clase_id,
                    'estudiante_id' => $alumnoId
                ])
                ->one();

        $modelComportamientos = \backend\models\ScholarisAsistenciaComportamiento::find()->orderBy("id")->all();
        $modelCompDetalle = \backend\models\ScholarisAsistenciaComportamientoDetalle::find()
                ->where(['activo' => true])
                ->orderBy("id")->all();

        $modelNovedades = ScholarisAsistenciaAlumnosNovedades::find()
                ->where([
                    'asistencia_profesor_id' => $asistenciaId,
                    'grupo_id' => $modelGrupo->id
                ])
                ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['detalle', 'alumnoId' => $modelGrupo->estudiante_id, 'asistenciaId' => $asistenciaId]);
        }
                       

        return $this->render('detalle', [
                    'model' => $model,
                    'modelNovedades' => $modelNovedades,
                    'modelAsistencia' => $modelAsistencia,
                    'modelComportamientos' => $modelComportamientos,
                    'modelCompDetalle' => $modelCompDetalle,
                    'modelGrupo' => $modelGrupo,
        ]);
    }

    public function actionAsignar($asistenciaId, $detalleId, $grupoId) {
        $model = new ScholarisAsistenciaAlumnosNovedades();
        
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();
        
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where(['id' => $grupoId])
                ->one();
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['detalle', 'alumnoId' => $modelGrupo->estudiante_id, 'asistenciaId' => $asistenciaId]);
        }

        return $this->render('asignar', [
                    'model' => $model,
                    'modelAsistencia' => $modelAsistencia,
                    'modelGrupo' => $modelGrupo,
                    'detalleId' => $detalleId,
        ]);
    }
    
    
    public function actionNuevotema($asistenciaId){
        $model = new ScholarisAsistenciaClaseTema();
        
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();
        
        
        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index', 'id' => $asistenciaId]);
        }else{
            return $this->render('nuevotema',[
                'model' => $model,
                'modelAsistencia' => $modelAsistencia
            ]);
        }
        
    }

    
    /**
     * Elimina la novedad del estudiante con respecto a las novedades cometidas
     */
    public function actionQuitar($novedadId){
        $model = ScholarisAsistenciaAlumnosNovedades::find()->where(['id' => $novedadId])->one();
        $alumnoId = $model->grupo->estudiante_id;
        $model->delete();
        return $this->redirect([
            'detalle',
            'alumnoId' => $alumnoId,
            'asistenciaId' => $model->asistencia_profesor_id
        ]);
    }    

    /**
     * Elimina el tema tratado en clase
     */
    public function actionQuitartema($id){
        $model = ScholarisAsistenciaClaseTema::find()->where(['id' => $id])->one();
        $asistenciaId = $model->asistencia_profesor_id;
        
        $model->delete();

        return $this->redirect([
            'index',
            'id' => $asistenciaId
        ]);        
    }

    /**
     * Genera falta automatica, con codigo 1CH, Falta injustificada hora clase 
     */
    public function actionFaltaAutoEstudiante(){       
       $asistenciaId = $_GET['idClase'];
       $alumnoId = $_GET['idAlumno'];
       $idFalta = '';
       $obsFalta ='';       

       //extraccion los parametros para faltas automaticas
       $modelOp = PlanificacionOpciones::find()->where([
           'tipo'=>'FALTA_A_CLASES'
       ])->asArray()->all();
       $idFalta = $modelOp[0]['opcion'];//id
       $obsFalta = $modelOp[1]['opcion'];//obs

       //extraccion id de grupo 
       $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();

       $modelGrupo = ScholarisGrupoAlumnoClase::find()
            ->where([
                'estudiante_id' => $alumnoId,
                'clase_id'=>$modelAsistencia->clase_id
                ])
            ->one();                 
    
       //guardamos la falta automatica
       $model = new ScholarisAsistenciaAlumnosNovedades();
       $model->asistencia_profesor_id=$asistenciaId;
       $model->comportamiento_detalle_id=$idFalta;
       $model->observacion = $obsFalta;
       $model->grupo_id = $modelGrupo->id;
       $model->save();       
    }
    /**
     * Elimina falta  automatica, con codigo 1CH, Falta injustificada hora clase 
     */
    public function actionBorrarFaltaAutoEstudiante(){       
        $asistenciaId = $_GET['idClase'];
        $alumnoId = $_GET['idAlumno'];
        $idFalta = '';
        $obsFalta ='';
        //extraigo los parametros para faltas automaticas
        $modelOp = PlanificacionOpciones::find()->where([
            'tipo'=>'FALTA_A_CLASES'
        ])->asArray()->all();
        $idFalta = $modelOp[0]['opcion'];//id
        $obsFalta = $modelOp[1]['opcion'];//obs
        //extraigo id de grupo 
        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                 ->where(['id' => $asistenciaId])
                 ->one();
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                 ->where([
                     'estudiante_id' => $alumnoId,
                     'clase_id'=>$modelAsistencia->clase_id
                     ])
                 ->one();             
        //eliminamos  la falta
        $model =ScholarisAsistenciaAlumnosNovedades::find()->where([
            'asistencia_profesor_id'=>$asistenciaId,
            'comportamiento_detalle_id'=>$idFalta,
            'grupo_id'=>$modelGrupo->id
        ])->one();
        $model->delete(); 
       
     }





}
