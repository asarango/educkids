<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisClase;
use backend\models\ScholarisClaseSearch;
use backend\models\ScholarisPeriodo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisClaseController implements the CRUD actions for ScholarisClase model.
 */
class ScholarisClaseAuxController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//    public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//            
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
//                echo $this->render('/site/error',[
//                   'message' => "Acceso denegado. No puede ingresar a este sitio !!!", 
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Creates a new ScholarisClase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisClase();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisClase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate() {
        
        $sentencias = new \backend\models\SentenciasClase();
        $periodoId = Yii::$app->user->identity->periodo_id;

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $model = $this->findModel($id);

            $modelMalla = \backend\models\ScholarisMallaCurso::find()
                    ->where(['curso_id' => $model->idcurso])
                    ->one();

            $modelGrupo = $sentencias->get_alumnos_clase($id, $periodoId);
            
            
            $modelDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();
            $modelHoras = $sentencias->get_horas_horario($model->asignado_horario);                        
            
            return $this->render('update', [
                        'model' => $model,
                        'modelMalla' => $modelMalla,
                        'modelGrupo' => $modelGrupo,
                        'modelDias' => $modelDias,
                        'modelHoras' => $modelHoras
            ]);
        } else {
            if ($_POST) {
                $id = $_POST['id'];
                $model = $this->findModel($id);

                
                $model->idprofesor = $_POST['profesor'];
                $model->peso = $_POST['peso'];
                $model->periodo_scholaris = $_POST['periodo'];
                $model->promedia = $_POST['promedia'];
                $model->asignado_horario = $_POST['horario'];
                $model->tipo_usu_bloque = $_POST['comparte'];
                $model->todos_alumnos = $_POST['todos'];
                $model->malla_materia = $_POST['matMalla'];
                
                $modelMallaMateria = \backend\models\ScholarisMallaMateria::findOne($_POST['matMalla']);
//                $model->idmateria = $_POST['materiaClase'];
                $model->idmateria = $modelMallaMateria->materia_id;
                
                $model->paralelo_id = $_POST['paralelo'];
                $model->materia_curriculo_codigo = $_POST['matCurriculo'];
                $model->codigo_curso_curriculo = $_POST['curCurriculo'];
                $model->save();

                $modelMalla = \backend\models\ScholarisMallaCurso::find()
                        ->where(['curso_id' => $model->idcurso])
                        ->one();

                $modelGrupo = $sentencias->get_alumnos_clase($id, $periodoId);
                
                $modelDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();
                $modelHoras = $sentencias->get_horas_horario($model->asignado_horario); 

                return $this->render('update', [
                            'model' => $model,
                            'modelMalla' => $modelMalla,
                            'modelGrupo' => $modelGrupo,
                            'modelDias' => $modelDias,
                            'modelHoras' => $modelHoras
                ]);
            }
        }
    }

    protected function findModel($id) {
        if (($model = ScholarisClase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionQuitar(){
        $sentencias = new \backend\models\SentenciasClase();
        $sentencias->quitar_clase_horario($_GET['clase'], $_GET['detalle']);

        return $this->redirect(['update','id' => $_GET['clase']]);
    }
    
    public function actionAsignar(){               
        
            $sentencias = new \backend\models\SentenciasClase();
            $model = \backend\models\ScholarisHorariov2Detalle::find()
                    ->where([
                            'cabecera_id' => $_GET['cabecera'],
                            'hora_id' => $_GET['hora'],
                            'dia_id' => $_GET['dia'],
                        ])
                    ->one();

            $sentencias->asignar_clase_horario($_GET['clase'], $model->id);
            return $this->redirect(['update','id' => $_GET['clase']]);
                
    }

}
