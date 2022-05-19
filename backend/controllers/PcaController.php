<?php

namespace backend\controllers;

use backend\models\pca\FormBibliografia;
use backend\models\pca\FormObservaciones;
use backend\models\pca\FormUnidadesMicrocurriculares;
use backend\models\pca\FormEjesTransversales;
use backend\models\pca\FormObjetivosGenerales;
use backend\models\pca\FormTiempo;
use backend\models\PcaDetalle;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\pca\Pca;
use backend\models\pca\PcaPdf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PcaController extends Controller {

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

    public function actionIndex1() {
        $cabeceraId = $_GET['cabecera_id'];
        $cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);
        $pca = PcaDetalle::find()->where([
                    'desagregacion_cabecera_id' => $cabeceraId
                ])
                ->orderBy('tipo')
                ->all();

        return $this->render('index1', [
                    'cabecera' => $cabecera,
                    'pca' => $pca
        ]);
    }

    
    public function actionAjaxPcaReporte() {
        $cabeceraId = $_GET['cabecera_id'];

        $pcaReporte = new Pca($cabeceraId);

        return $pcaReporte->html;
    }

    public function actionAjaxPcaFormulario() {
        $cabeceraId = $_GET['cabecera_id'];
        $formulario = $_GET['menu'];

        if ($formulario == 'tiempo') {
            $formAjax = new FormTiempo($cabeceraId);
        }

        if ($formulario == 'objetivos_generales') {
            $formAjax = new FormObjetivosGenerales($cabeceraId);
        }
        
        if ($formulario == 'ejes_transversales') {
            $formAjax = new FormEjesTransversales($cabeceraId);
        }
        if ($formulario == 'unidades_microcurriculares') {
            $formAjax = new FormUnidadesMicrocurriculares($cabeceraId);
        }
        if ($formulario == 'observaciones') {
            $formAjax = new FormObservaciones($cabeceraId);
        }
        if ($formulario == 'bibliografia') {
            $formAjax = new FormBibliografia($cabeceraId);
        }

        return $formAjax->html;
    }

    public function actionSaveForm() {
        $cabeceraId = $_POST['cabecera_id'];
        $tipo = $_POST['tipo'];
        $contenido = $_POST['contenido'];
        $codigo = $_POST['codigo'];

        if ($tipo == 'tiempo') {
            $planCabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);
            $planCabecera->$codigo = $contenido;
            $planCabecera->save();
                
            $operacion = PlanificacionDesagregacionCabecera::findOne($cabeceraId);

            isset($operacion->semanas_trabajo) ? $semanTrabajo = $operacion->semanas_trabajo : $semanTrabajo = 0;
            isset($operacion->evaluacion_aprend_imprevistos) ? $imprevistos = $operacion->evaluacion_aprend_imprevistos : $imprevistos = 0;

            $totalSemanasClase  = $semanTrabajo-$imprevistos;
            $totalPeriodos      = $totalSemanasClase * $operacion->carga_horaria_semanal;
                        
            $operacion->total_semanas_clase = $totalSemanasClase;
            $operacion->total_periodos      = $totalPeriodos;
            $operacion->save();            

        }else{
            $pca = new PcaDetalle();
            $pca->desagregacion_cabecera_id = $cabeceraId;
            $pca->tipo                      = $tipo;
            $pca->codigo                    = $codigo;
            $pca->contenido                 = $contenido;
            $pca->estado                    = true;
            $pca->save();
        }
    }

    public function actionDeletePca(){
        $id = $_GET['id'];
        $model = PcaDetalle::findOne($id);
        $model->delete();
    }

    public function actionPcaMateria(){
        $cabeceraId = $_GET['cabecera_id'];

        $pca = new PcaPdf( $cabeceraId );
    }

}
