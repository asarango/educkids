<?php

namespace frontend\controllers;

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

        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $id])
                ->one();


        $modelClase = ScholarisClase::find()->where(['id' => $modelAsistencia->clase_id])->one();
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->innerJoin('op_student', 'op_student.id = scholaris_grupo_alumno_clase.estudiante_id')
                ->where(['scholaris_grupo_alumno_clase.clase_id' => $modelAsistencia->clase_id])
                ->orderBy(
                        'op_student.last_name', 'op_student.first_name', 'op_student.middle_name'
                )
                ->all();
        
        
        $modelActividades = ScholarisActividad::find()
                ->where([
                          'paralelo_id' => $modelClase->id,
                          'inicio' => date("Y-m-d")
                        ])
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


        $modelAsistencia = ScholarisAsistenciaProfesor::find()
                ->where(['id' => $asistenciaId])
                ->one();

        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where([
                    'clase_id' => $modelAsistencia->clase_id,
                    'estudiante_id' => $alumnoId
                ])
                ->one();

        $modelComportamientos = ScholarisAsistenciaComportamiento::find()->orderBy("id")->all();
        $modelCompDetalle = ScholarisAsistenciaComportamientoDetalle::find()
                ->where(['activo' => true])
                ->orderBy("id")->all();

        $modelNovedades = ScholarisAsistenciaAlumnosNovedades::find()
                ->where([
                    'asistencia_profesor_id' => $asistenciaId,
                    'grupo_id' => $modelGrupo->id
                ])
                ->all();

        return $this->render('detalle', [
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



}
