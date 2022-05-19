<?php

namespace backend\controllers;

use backend\models\CurriculoMec;
use backend\models\CurriculoMecAsignatutas;
use backend\models\CurriculoMecNiveles;
use backend\models\OpCourse;
use backend\models\ScholarisMateria;
use backend\models\IsmAreaMateria;
use Yii;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCabeceraSearch;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\diplomaphpv\Pdf;
use backend\models\pudpai\Pdf as PudpaiPdf;
use backend\models\Usuario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PlanificacionBloquesUnidadController extends Controller{
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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

    public function beforeAction($action)
    {
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

    public function actionIndex1(){
        $institutoId        = Yii::$app->user->identity->instituto_defecto;
        $usuarioLogueado    = Yii::$app->user->identity->usuario;
        $usuario = Usuario::findOne($usuarioLogueado);
        $perfil = $usuario->rol->rol;        

        $cabeceraId = $_GET['id'];
        $cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId); //toma datos de la cabecera
        $opCourseTemplateId = $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        //$materia = ScholarisMateria::findOne($cabecera->scholaris_materia_id);// toma datos de la materia scholaris
        $materia = IsmAreaMateria::findOne($cabecera->ismAreaMateria->materia_id);// toma datos de la materia scholaris

        $seccion = $this->consultar_section($opCourseTemplateId, 
                                            $cabecera->scholaris_periodo_id, $institutoId);

        $this->inserta_bloques($cabeceraId); //Inserta los bloques para la planificacion de las unidades
        $unidades = PlanificacionBloquesUnidad::find()
                    ->where(['plan_cabecera_id' => $cabeceraId])
                    ->orderBy('curriculo_bloque_id')
                    ->all(); 
             
        return $this->render('index', [
            'materia'   => $materia,
            'unidades'  => $unidades,
            'cabecera'  => $cabecera,
            'seccion'   => $seccion,
            'perfil'    => $perfil
        ]);
    } 

    private function consultar_section($templateId, $scholarisPeriodoId, $institutoId){
        $con = Yii::$app->db;
        $query = "select 	s.code 
        from 	op_course c
                inner join op_period op on op.id = c.period_id 
                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = c.period_id 
                inner join op_section s on s.id = c.section
        where 	c.x_template_id = $templateId
                and sop.scholaris_id = $scholarisPeriodoId
                and c.x_institute = $institutoId";
        $res = $con->createCommand($query)->queryOne();
        return $res['code'];
    }

    private function inserta_bloques($cabeceraId){
        $con = Yii::$app->db;
        $query = "insert into planificacion_bloques_unidad(curriculo_bloque_id, plan_cabecera_id, unit_title, settings_status, is_open)
                    select 	b.id
                            ,$cabeceraId
                            ,'sin titulo'
                            ,'no-configurado'
                            ,true
                    from 	curriculo_mec_bloque b
                    where 	id not in (select 	curriculo_bloque_id 
                    from 	planificacion_bloques_unidad
                    where 	plan_cabecera_id = $cabeceraId
                            and curriculo_bloque_id = b.id)
                            and b.is_active = true
                    order by b.code;";
        $con->createCommand($query)->execute();
    }


    public function actionUpdate(){        

        $unidadId = $_GET['unidad_id'];
        $model = PlanificacionBloquesUnidad::findOne($unidadId);
        $modelSubtitulo = PlanificacionBloquesUnidadSubtitulo::find()->where([
            'plan_unidad_id' => $unidadId
        ])->all();

        if($model->load(Yii::$app->request->post()) ){            

            $model->save();

            return $this->redirect(['index1', 'id' => $model->plan_cabecera_id]);
        }            

        return $this->render('update', [
            'model' => $model,
            'modelSubtitulo' => $modelSubtitulo
        ]);
    }

    public function actionContenido(){
        $planUnidadId = $_GET['unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $subtitulos = PlanificacionBloquesUnidadSubtitulo::find()->where([
            'plan_unidad_id' => $planUnidadId
        ])
        ->orderBy('orden')
        ->all();

        $model = new PlanificacionBloquesUnidadSubtitulo();
        if($model->load(Yii::$app->request->post())){      
            
            $model->experiencias = $_POST['experiencia'];
            $model->evaluacion_formativa = $_POST['evaluacion'];
            $model->diferenciacion = $_POST['diferenciacion'];            
            $model->save();
            return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);
        }

        return $this->render('contenido', [
            'planUnidad' => $planUnidad,
            'subtitulos' => $subtitulos,
            'model' => $model
        ]);

    }

    public function actionDeleteSubtitle(){
        $id = $_GET['id'];
        $model = PlanificacionBloquesUnidadSubtitulo::findOne($id);
        $planUnidadId = $model->plan_unidad_id;

        $model->delete();

        return $this->redirect(['contenido', 
            'unidad_id' => $planUnidadId
        ]);
    }

    public function actionUpdateSubtitle(){
        $id = $_POST['PlanificacionBloquesUnidadSubtitulo']['id'];
        $subtitulo = $_POST['PlanificacionBloquesUnidadSubtitulo']['subtitulo'];
        $orden = $_POST['PlanificacionBloquesUnidadSubtitulo']['orden'];

        $experiencias =  $_POST['experiencia_update'];
        $evaluaciones =  $_POST['evaluacion_update'];
        $diferenciacion =  $_POST['diferenciacion_update'];
        
        $model = PlanificacionBloquesUnidadSubtitulo::findOne($id);
        $model->subtitulo = $subtitulo;
        $model->orden = $orden;
        $model->experiencias = $experiencias;
        $model->evaluacion_formativa = $evaluaciones;
        $model->diferenciacion = $diferenciacion;
        $model->save();

        return $this->redirect(['contenido', 'unidad_id' => $model->plan_unidad_id]);

    }

    public function actionCreateSubtitle2(){
        
        $subtituloId            = $_POST['PlanificacionBloquesUnidadSubtitulo2']['subtitulo_id'];
        $subtitulo2Contenido    = $_POST['PlanificacionBloquesUnidadSubtitulo2']['contenido'];
        $subtitulo2Orden        = $_POST['PlanificacionBloquesUnidadSubtitulo2']['orden'];
        $planUnidadId           = $_POST['PlanificacionBloquesUnidadSubtitulo2']['plan_unidad_id'];
        
        $model = new PlanificacionBloquesUnidadSubtitulo2();
        $model->subtitulo_id = $subtituloId;
        $model->contenido = $subtitulo2Contenido;
        $model->orden = $subtitulo2Orden;
        $model->save();

        return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);

    }

    public function actionDeleteSubtitle2(){
        $id = $_GET['id'];
        $model = PlanificacionBloquesUnidadSubtitulo2::findOne($id);
        $planUnidadId = $model->subtitulo->plan_unidad_id;
        $model->delete();

        return $this->redirect(['contenido', 'unidad_id' => $planUnidadId]);
    }

    public function actionEnviaCoordinador(){
        
        $cabeceraId = $_GET['cabecera_id'];
        $hoy = date('Y-m-d H:i:s');

        $planCabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);

        if($planCabecera->estado == 'DEVUELTO'){
            $planCabecera->fecha_de_cambios = $hoy;
        }

        $planCabecera->estado = 'EN_COORDINACION';
        $planCabecera->coordinador_user = 'admin';
        $planCabecera->fecha_envio_coordinador = $hoy;
        $planCabecera->save();
        
        return $this->redirect(['index1', 'id' => $cabeceraId]);

    }

    public function actionAbrirBloque(){
        $planUnidadId = $_GET['plan_unidad_id'];        

        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $planUnidad->is_open = true;
        $planUnidad->pud_status = false;
        $planUnidad->settings_status = 'en-proceso';
        $planUnidad->save();

        $cabecera = PlanificacionDesagregacionCabecera::findOne($planUnidad->plan_cabecera_id);
        $cabecera->estado = 'DEVUELTO';
        $cabecera->save();

        return $this->redirect(['index1', 'id' => $planUnidad->plan_cabecera_id]);
    }


    /****PARA GENERAR PDF DE PLAN vertical DE DIPLOMA */
    public function actionPdfPv(){
        $cabeceraId = $_GET['cabecera_id'];
        new Pdf($cabeceraId);
    }
    /****FIN PARA GENERAR PDF DE PLAN HORIZONTAL DE DIPLOMA */

}