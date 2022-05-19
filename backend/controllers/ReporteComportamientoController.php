<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class ReporteComportamientoController extends Controller {

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
                    ],
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
     * Lists all ScholarisActividad models.
     * @return mixed
     */
    public function actionIndex1() {

        $sentenciasRecalculo = new \backend\models\SentenciasRecalcularUltima();

        $periodo = \Yii::$app->user->identity->periodo_id;
        $instituto = \Yii::$app->user->identity->instituto_defecto;

        $sentencias = new \backend\models\SentenciasBloque();
        $sentenciasAl = new \backend\models\SentenciasAlumnos();


        $paralelo = $_GET['id'];
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        
        if (isset($_GET['parcial'])) {
            $parcialorden = $_GET['parcial'];
        } else {
            $parcialorden = 1;
        }
        
        
        $parcial = $sentencias->get_bloque_por_orden($parcialorden, $paralelo, $modelPeriodo->codigo, $instituto);
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($parcial);

        $modelBloques = \backend\models\ScholarisBloqueActividad::find()
                ->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_uso' => $modelBloque->tipo_uso,
                    'instituto_id' => $instituto
                ])
                ->orderBy('orden')
                ->all();

        $modelAlumnos = $sentenciasAl->get_alumnos_paralelo($paralelo);

        $modelActividad = $this->genera_calificaciones($paralelo, $modelBloque->id);


        //recalcula revision notas
        $sentenciasRecalculo->por_paralelo($paralelo);

        return $this->render('index', [
                    'modelParalelo' => $modelParalelo,
                    'modelBloque' => $modelBloque,
                    'modelBloques' => $modelBloques,
                    'modelAlumnos' => $modelAlumnos,
                    'modelActividad' => $modelActividad
        ]);
    }

    private function genera_calificaciones($paralelo, $bloque) {

        $resp = $this->consulta_actividad($paralelo, $bloque);

        if ($resp) {
            $total = 1;
        } else {
            $total = 0;
        }


        if ($total == 0) {

            //crear la actividad automaticamente
            $usuario = \Yii::$app->user->identity->usuario;
            $modelUasuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
            $modelTipoAc = \backend\models\ScholarisTipoActividad::find()->where(['nombre_pai' => 'ACTIVIDAD INDIVIDUAL'])->one();
            $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($bloque);
            $datosClase = $this->toma_datos_clase($paralelo);
            $fecha = date('Y-m-d H:i:s');

            $modelA = new \backend\models\ScholarisActividad();
            $modelA->create_date = $fecha;
            $modelA->write_date = $fecha;
            $modelA->create_uid = $modelUasuario->id;
            $modelA->write_uid = $modelUasuario->id;
            $modelA->title = 'CALIFICACIÓN AUTOMÁTICA';
            $modelA->descripcion = 'Calificación automática del comportamiento';
            $modelA->inicio = $modelBloque->bloque_finaliza;
            $modelA->fin = $modelBloque->bloque_finaliza;
            $modelA->tipo_actividad_id = $modelTipoAc->id;
            $modelA->bloque_actividad_id = $bloque;
            $modelA->a_peso = '100';
            $modelA->paralelo_id = $datosClase['id'];
            $modelA->materia_id = $datosClase['idmateria'];
            $modelA->calificado = 'SI';
            $modelA->tipo_calificacion = 'N';
            $modelA->hora_id = $datosClase['hora_id'];

//            print_r($modelA);
//            die();

            $modelA->save();

            $model = \backend\models\ScholarisActividad::findOne($modelA->primaryKey);

//            die();
        } else {
            $model = \backend\models\ScholarisActividad::findOne($resp['id']);
        }


        return $model;
    }

    /**
     * 
     * @param type $paralelo
     * @param type $bloque
     * @return type
     */
    private function consulta_actividad($paralelo, $bloque) {
        $con = \Yii::$app->db;
        $query = "select 	count(a.id) as total, a.id
from 	scholaris_clase c 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
		inner join scholaris_actividad a on a.paralelo_id = c.id
where	c.paralelo_id = $paralelo
		and mm.tipo = 'COMPORTAMIENTO'
		and a.bloque_actividad_id = $bloque  and a.calificado = 'SI' "
                . "group by a.id;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function toma_datos_clase($paralelo) {

        $con = \Yii::$app->db;
        $query = "select 	c.id
                                ,c.idmateria
                                ,(select 	det.hora_id 
                                        from 	scholaris_horariov2_horario hor
                                                        inner join scholaris_horariov2_detalle det on det.id = hor.detalle_id
                                        where	hor.clase_id = c.id
                                        order by det.hora_id
                                        limit 1)
                from	scholaris_clase c
                                inner join scholaris_malla_materia mm on mm.id = c.malla_materia
                where	c.paralelo_id = $paralelo
                                and mm.tipo = 'COMPORTAMIENTO';";
//        echo $query;
//        die();


        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    public function actionReporteiso() {
        print_r($_GET);
    }

    public function actionCambianota() {

        //print_r($_GET);
        $notaId = $_GET['notaId'];
        $model = \backend\models\ScholarisCalificaciones::findOne($notaId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $model->actividad->clase->paralelo_id, 'parcial' => $model->actividad->bloque->orden]);
        }
        
        $modelMotivos = \backend\models\ScholarisAsistenciaComportamientoCambionota::find()->all();

        return $this->render('cambianota', [
                    'model' => $model,
                    'modelMotivos' => $modelMotivos
        ]);
    }
    
    
    public function actionReportesugerido(){
        $alumno = $_GET['alumno'];
        $bloque = $_GET['bloque'];
        $paralelo = $_GET['paralelo'];
        
        
        $reporte = new \backend\models\InformeComportamientoSugerido();
        $reporte->genera_reporte($alumno, $bloque, $paralelo);
        
    }

}
