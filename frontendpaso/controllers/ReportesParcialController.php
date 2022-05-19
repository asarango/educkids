<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;
use backend\models\OpCourse;
use backend\models\ScholarisOpPeriodPeriodoScholaris;
use backend\models\OpCourseParalelo;
use backend\models\ScholarisClase;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class ReportesParcialController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//              'class' => AccessControl::className(),
//                'rules' => [
//                  [
//                      'allow' => true,
//                      'roles' => ['@'],
//                  ]  
//                ],
//            ],
//            
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//     public function beforeAction($action) {
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
     * Lists all PlanPlanificacion models.
     * @return mixed
     */
    public function actionIndex() {

        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $periodo = ScholarisOpPeriodPeriodoScholaris::find()
                ->innerJoin("scholaris_periodo", "scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
                ->innerJoin("op_period", "op_period.id = scholaris_op_period_periodo_scholaris.op_id")
                ->where(["scholaris_periodo.id" => $periodoId, "op_period.institute" => $institutoId])
                ->one();


        $modelCursos = OpCourse::find()
                ->innerJoin("op_section", "op_section.id = op_course.section")
                ->where(['op_section.period_id' => $periodo, "x_institute" => $institutoId])
                ->all();
//
        return $this->render('index', [
                    'modelCursos' => $modelCursos,
        ]);
    }

    public function actionParalelos() {
        $curso = $_POST['id'];

        $modelParalelo = OpCourseParalelo::find()
                ->where(['course_id' => $curso])
                ->orderBy("name")
                ->all();
        
        $listData = ArrayHelper::map($modelParalelo, 'id', 'name');
        

        echo Select2::widget([
            'name' => 'paralelo',
            'value' => 0,
            'data' => $listData,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione paralelo',
                'onchange' => 'mostrarBloque(this,"' . Url::to(['bloque']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
    }
    
    
    public function actionBloque(){
        $paraleloId = $_POST['id'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $modelClase = ScholarisClase::find()
                ->where(['paralelo_id' => $paraleloId])
                ->limit(1)
                ->one();
        $modelPeriodo = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();
        
        
        $modelBloque = ScholarisBloqueActividad::find()
                ->where(['tipo_uso' => $modelClase->tipo_usu_bloque, "scholaris_periodo_codigo" => $modelPeriodo->codigo])
                ->orderBy("orden")
                ->all();
        
        $listData = ArrayHelper::map($modelBloque, 'id', 'name');
        
        return Select2::widget([
            'name' => 'bloque',
            'value' => 0,
            'data' => $listData,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione bloque',
                'onchange' => 'mostrarDetalle(this,'.$paraleloId.');',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        
    }
    
    public function actionReporte(){
        //echo 'en el reporte';        
        
        return $this->redirect([$_POST['reporte'],
                'curso' => $_POST['curso'],
                'paralelo' => $_POST['paralelo'],
                'bloque' => $_POST['bloque']                
            ]);
        
    }
    
    public function actionPromedios(){
        
    }
    
    
    

}
