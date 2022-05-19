<?php

namespace backend\controllers;

use Yii;
use backend\models\Rol;
use backend\models\RolSearch;
use backend\models\Operacion;
use backend\models\RolOperacion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * RolController implements the CRUD actions for Rol model.
 */
class ExamenesExtrasController extends Controller {

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

    /**
     * Lists all Rol models.
     * @return mixed
     */
    public function actionIndex() {
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $periodoId = \Yii::$app->user->identity->periodo_id;


        $modelCursos = $this->consulta_cursos($periodoId, $institutoId);


        return $this->render('index', [
                    'modelCursos' => $modelCursos
        ]);
    }

    private function consulta_cursos($periodoId, $institutoId) {
        $con = \Yii::$app->db;
        $query = "select 	c.id 
                                ,c.name as curso
                from 	scholaris_curso_imprime_libreta cl
                                inner join op_course c on c.id = cl.curso_id 
                                inner join op_section s on s.id = c.section
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                where 	sop.scholaris_id = $periodoId
                                and c.x_institute = $institutoId 
                                and cl.rinde_supletorio = 1
                order by c.name;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function actionReporte() {

        $cursoId = $_POST['curso'];
        $conCedulas = $_POST['concedulas'];
        $periodoId = \Yii::$app->user->identity->periodo_id;


        $modelParalelos = \backend\models\OpCourseParalelo::find()->where([
                    'course_id' => $cursoId
                ])->all();

        $modelTipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $periodoId])->one();
        $tipoCalificacion = $modelTipoCalificacion->codigo;

        if ($tipoCalificacion == 0) {
            foreach ($modelParalelos as $paralelo) {
                new \backend\models\ProcesaNotasNormales($paralelo->id, '');
            }
        }

        new \backend\models\InfExamenesExtrasPdf($cursoId, $conCedulas);
    }

}
