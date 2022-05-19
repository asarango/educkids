<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class PlanSemanalController extends Controller {
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
//            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
//                echo $this->render('/site/error', [
//                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        if ($_POST) {
            if ($_POST['semana'] == '' || $_POST['semana'] == NULL) {
                $condicion = '';
//                echo 'ola';
//                die();
            } else {
                $condicion = "and s.nombre_semana = '" . $_POST['semana'] . "'";
            }
        } else {
            $condicion = '';
        }

        $sentencias = new \backend\models\SentenciasPlanSemanal();

        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $resUser = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
        $modelFaculty = \backend\models\OpFaculty::find()
                        ->where(['partner_id' => $resUser->partner_id])->one();
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()
                        ->where(['id' => $periodoId])->one();
        $this->ingresa_semanas($resUser->id, $modelPeriodo->codigo, $modelFaculty->id);


        $model = $sentencias->get_plan($resUser->id, $modelPeriodo->codigo, $condicion);

        return $this->render('index', [
                    'model' => $model,
                    'modelFaculty' => $modelFaculty
        ]);
    }

    private function ingresa_semanas($usuarioId, $periodoCodigo, $profesorId) {
        $con = Yii::$app->db;
        $query = "insert into scholaris_bloque_semanas_observacion(semana_id, comparte_bloque, usuario, creado_fecha, creado_por, actualizado_fecha, actualizado_por)
        select 	s.id
                ,cast(a.tipo_uso as integer) as tipo_uso
                ,$usuarioId as usuario
                ,current_timestamp
                ,$usuarioId
                ,current_timestamp
                ,$usuarioId
        from 	scholaris_bloque_semanas s
                inner join scholaris_bloque_actividad a on a.id = s.bloque_id
        where	a.tipo_uso in (select 	tipo_usu_bloque
        from 	scholaris_clase c
        where 	c.idprofesor = $profesorId
                and periodo_scholaris = '$periodoCodigo'
                and s.id not in (
                select 	semana_id
                from 	scholaris_bloque_semanas_observacion
                where	semana_id = s.id
                        and usuario = $usuarioId
                )
        group by c.tipo_usu_bloque)
                and a.scholaris_periodo_codigo = '$periodoCodigo';";
        $con->createCommand($query)->execute();
    }

    public function actionObservacion() {
        $id = $_GET['id'];
        $model = \backend\models\ScholarisBloqueSemanasObservacion::findOne($id);

        $modelComparte = \backend\models\ScholarisBloqueComparte::find()
                ->where(['valor' => $model->semana->bloque->tipo_uso])
                ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('observacion', [
                    'model' => $model,
                    'modelComparte' => $modelComparte
        ]);
    }

    public function actionPdf() {
        $id = $_GET['id'];
        $facultyId = $_GET['facultyId'];
        $sentenciasRepo = new \backend\models\ReportePlanSemanal();

        $reporte = $sentenciasRepo->genera_reporte($id,$facultyId);
    }

    public function actionDestrezas() {
        //print_r($_GET);
        $semanaId = $_GET['id'];
        $facultyId = $_GET['facultyId'];
        $modelObservacion = \backend\models\ScholarisBloqueSemanasObservacion::findOne($semanaId);
        $uso = $modelObservacion->semana->bloque->tipo_uso;

        $modelClase = \backend\models\ScholarisClase::find()
                ->where(['idprofesor' => $facultyId, 'tipo_usu_bloque' => $uso])
                ->all();

        $modelFaculty = \backend\models\OpFaculty::findOne($facultyId);

        $modelComparte = \backend\models\ScholarisBloqueComparte::find()->where(['valor' => $uso])->one();


        return $this->render('destrezas', [
                    'modelClase' => $modelClase,
                    'modelFaculty' => $modelFaculty,
                    'modelObservacion' => $modelObservacion,
                    'modelComparte' => $modelComparte,
                    'uso' => $uso
        ]);
    }

    public function actionCreardestrezas() {
        $curso = $_GET['curso'];
        $profesor = $_GET['faculty_id'];
        $semana = $_GET['semana_id'];
        $uso = $_GET['uso'];
        $observacion = $_GET['observacionId'];

        $model = \backend\models\ScholarisPlanSemanalDestrezas::find()
                ->where([
                    'curso_id' => $curso,
                    'faculty_id' => $profesor,
                    'semana_id' => $semana,
                    'comparte_valor' => $uso,
                ])
                ->one();
        
        if ($model) {
            return $this->redirect(['scholaris-plan-semanal-destrezas/update',
                
                'curso_id' => $curso,
                'faculty_id' => $profesor,
                'semana_id' => $semana,
                'comparte_valor' => $uso,
                'observacion' => $observacion
            ]);
        } else {
            //$model = new \backend\models\ScholarisPlanSemanalDestrezas();
            
            return $this->redirect(["scholaris-plan-semanal-destrezas/create", 
                        'curso_id' => $curso,
                        'faculty_id' => $profesor,
                        'semana_id' => $semana,
                        'comparte_valor' => $uso,
                        'observacion' => $observacion
            ]);
        }
    }

}
