<?php

namespace backend\controllers;

use Yii;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionVerticalPaiDescriptores;
use backend\models\PlanificacionVerticalPaiOpciones;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PlanificacionVerticalPaiDescriptoresController extends Controller{
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
        
        ///////para tomar en cuenta la pestaña
        if(isset($_GET['pestana'])){
            $pestana = $_GET['pestana'];
        }else{
            $pestana = 'concepto_clave'; 
        }
        //// fin de la tomada en cuenta de la pestaña
        
        $planBloqueUnidadId = $_GET['unidad_id'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $bloqueUnidad       = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        $areaId             = $bloqueUnidad->planCabecera->ismAreaMateria->mallaArea->area_id;

        $courseTemplateId   = $bloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        $materiaId          = $bloqueUnidad->planCabecera->ismAreaMateria->materia_id;

        $criteriosDisponibles   = $this->consulta_disponibles($planBloqueUnidadId, $areaId, $courseTemplateId);
        $criteriosSeleccionados = $this->consulta_seleccionados($planBloqueUnidadId);

        $conceptosClaveDisponibles      = $this->consulta_conceptos_clave_disponibles($planBloqueUnidadId);
        $conceptosClaveSeleccionados    = PlanificacionVerticalPaiOpciones::find()->where([
            'plan_unidad_id' => $planBloqueUnidadId,
            'tipo' => 'concepto_clave'
        ])->all();

        $conceptosRelacionadosDisponibles   = $this->consulta_conceptos_relacionados_disponibles($materiaId, $planBloqueUnidadId);
        $conceptosRelacionadosSeleccionados = PlanificacionVerticalPaiOpciones::find()->where([
              'plan_unidad_id' => $planBloqueUnidadId,
              'tipo' => 'concepto_relacionado'
          ])->all();

        $contextoGlobalDisponibles = $this->consulta_contexto_global($planBloqueUnidadId);  
        $contextoGlobalSeleccionados = PlanificacionVerticalPaiOpciones::find()->where([
            'plan_unidad_id' => $planBloqueUnidadId,
            'tipo' => 'contexto_global'
        ])->all();

        $habilidadesDisponibles = $this->consulta_habilidades_disponibles($planBloqueUnidadId, $courseTemplateId, $periodoId);
        $habilidadesSeleccionadas = PlanificacionVerticalPaiOpciones::find()->where([
            'plan_unidad_id' => $planBloqueUnidadId,
            'tipo' => 'habilidad_enfoque'
        ])->all();

        $temario = array();

        $subtitulos = $this->selecciona_subtitulos($planBloqueUnidadId);

        foreach($subtitulos as $subtitulo){
            
            $subtitulo2 = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                'subtitulo_id' => $subtitulo['id']
            ])->orderBy('orden')->all();

            $subtitulo['subtitulos'] = $subtitulo2;
        
            array_push($temario, $subtitulo);
        }

        
         return $this->render('index1', [
             'bloqueUnidad' => $bloqueUnidad,
             'criteriosDisponibles' => $criteriosDisponibles,
             'criteriosSeleccionados' => $criteriosSeleccionados,
             'conceptosClaveDisponibles' => $conceptosClaveDisponibles,
             'conceptosClaveSeleccionados' => $conceptosClaveSeleccionados,
             'conceptosRelacionadosDisponibles' => $conceptosRelacionadosDisponibles,
             'conceptosRelacionadosSeleccionados' => $conceptosRelacionadosSeleccionados,
             'contextoGlobalDisponibles' => $contextoGlobalDisponibles,
             'contextoGlobalSeleccionados' => $contextoGlobalSeleccionados,
             'habilidadesDisponibles' => $habilidadesDisponibles,
             'habilidadesSeleccionadas' => $habilidadesSeleccionadas,
             'pestana' => $pestana,
             'temario' => $temario
         ]);
    }

    private function selecciona_subtitulos($planUnidadId){
        $con = Yii::$app->db;
        $query = "select 	id, plan_unidad_id, subtitulo, orden 
                    from 	planificacion_bloques_unidad_subtitulo
                    where	plan_unidad_id = $planUnidadId
                    order by orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_conceptos_relacionados_disponibles($materiaId, $planBloqueUnidadId){
        $con = Yii::$app->db;
        $query = "select 	id, ism_materia_id, contenido_es, contenido_en, contenido_fr, estado 
        from 	scholaris_materia_conceptos_relacionados_pai r
        where 	r.ism_materia_id = $materiaId
                and r.estado = true
                and contenido_es not in (
                    select 	contenido 
        from 	planificacion_vertical_pai_opciones 
        where 	plan_unidad_id = $planBloqueUnidadId
                and tipo = 'concepto_relacionado' 
                and contenido = r.contenido_es 
                );";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_disponibles($planBloqueUnidadId, $areaId, $courseTemplateId){
        $con = Yii::$app->db;
        $query = "select 	ic.id as criterio_id
		, ic.nombre as criterio
		, icl.nombre_espanol as criterio_detalle
		, ild.descripcion as descriptor_detalle
		, id.nombre as codigo
		, da.id as descriptor_id
from 	ism_criterio_descriptor_area da
		inner join ism_criterio ic on ic.id = da.id_criterio 
		inner join ism_criterio_literal icl on icl.id = da.id_literal_criterio 
		inner join ism_descriptores id on id.id = da.id_descriptor 
		inner join ism_literal_descriptores ild on ild.id = da.id_literal_descriptor 
where 	da.id_area = $areaId
		and id_curso = $courseTemplateId
		and da.id not in(
			select descriptor_id from planificacion_vertical_pai_descriptores where descriptor_id = da.id and plan_unidad_id = $planBloqueUnidadId
		)
order by ic.nombre, id.nombre;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_seleccionados($planBloqueUnidadId){
        $con = Yii::$app->db;
        $query = "select 	pd.id 
                                , ic.nombre as criterio 
                                , icl.nombre_espanol as codigo_idioma_alterno		
                                , id.nombre as codigo
                                ,ild.descripcion as descriptor_detalle
                from 	planificacion_vertical_pai_descriptores pd
                                inner join ism_criterio_descriptor_area maes on maes.id = pd.descriptor_id 
                                inner join ism_criterio ic on ic.id = maes.id_criterio 
                                inner join ism_criterio_literal icl on icl.id = maes.id_literal_criterio 
                                inner join ism_descriptores id on id.id = maes.id_descriptor 
                                inner join ism_literal_descriptores ild on ild.id = maes.id_literal_descriptor 
                where 	pd.plan_unidad_id = $planBloqueUnidadId order by ic.nombre;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function consulta_conceptos_clave_disponibles($planBloqueUnidadId){
        $con    = Yii::$app->db;
        $query  = "select 	id, tipo, contenido_es, contenido_en, contenido_fr, estado 
                    from 	contenido_pai_opciones op
                    where 	op.tipo = 'concepto_clave' 
                            and estado = true 
                            and contenido_es not in(
                                select contenido from planificacion_vertical_pai_opciones where 
                                contenido = op.contenido_es
                                and plan_unidad_id = $planBloqueUnidadId
                                and tipo = op.tipo
                            );";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_contexto_global($planBloqueUnidadId){
            $con    = Yii::$app->db;
            $query  = "select 	id, tipo, contenido_es, contenido_en, contenido_fr, estado 
                        from 	contenido_pai_opciones op
                        where 	op.tipo = 'contexto_global' 
                                and estado = true 
                                and contenido_es not in(
                                    select contenido from planificacion_vertical_pai_opciones where 
                                    contenido = op.contenido_es
                                    and plan_unidad_id = $planBloqueUnidadId
                                    and tipo = op.tipo
                            );";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_habilidades_disponibles($planBloqueUnidadId, $opCourseTemplateId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	c.id, c.es_titulo1, c.orden_titulo2, c.es_titulo2, c.es_subtitulo, c.es_exploracion
                , c.en_titulo1, c.en_titulo2, c.en_subtitulo, c.en_exploracion, c.fr_titulo1
                , c.fr_titulo2, c.fr_subtitulo, c.fr_exploracion 
        from 	mapa_enfoques_pai me
                inner join contenido_pai_habilidades c on c.id = me.pai_habilidad_id 
        where 	me.course_template_id = $opCourseTemplateId
                and me.periodo_id = $periodoId
                and me.estado = true
                and c.es_exploracion not in (
                                select 	contenido 
                                from 	planificacion_vertical_pai_opciones 
                                where 	plan_unidad_id = $planBloqueUnidadId 
                                        and tipo = 'habilidad_enfoque' 
                                        and contenido = c.es_exploracion
                ) order by c.es_titulo2, c.es_exploracion ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    /**
     * ASIGNA LOS CRITERIOS PAI
     */
    public function actionAsignar(){
        $planBloqueUnidadId = $_GET['plan_unidad_id'];
        $descriptorId       = $_GET['descriptor_id'];
        $pestana            = $_GET['pestana'];

        $model = new \backend\models\PlanificacionVerticalPaiDescriptores();
        $model->plan_unidad_id = $planBloqueUnidadId;
        $model->descriptor_id = $descriptorId;
        
        $model->save();
        return $this->redirect(['index1', 'unidad_id' => $planBloqueUnidadId, 'pestana' => $pestana]);
    }

    /**
     * QUITA LOS CRITERIOS PAI
     */
    public function actionQuitar(){
        $id = $_GET['id'];
        $pestana = $_GET['pestana'];
        $model = PlanificacionVerticalPaiDescriptores::findOne($id);
        $planBloqueUnidadId = $model->plan_unidad_id;
        $model->delete();
        return $this->redirect(['index1', 'unidad_id' => $planBloqueUnidadId, 'pestana' => $pestana]);
    }


    /**
     * ASIGNAR CONTENIDOS
     */
    public function actionAsignarContenido(){
        $planBloqueUnidadId = $_GET['plan_unidad_id'];
        $tipo               = $_GET['tipo'];
        $contenido          = $_GET['contenido'];
        $pestana            = $_GET['pestana'];

        $model = new PlanificacionVerticalPaiOpciones();
        $model->plan_unidad_id = $planBloqueUnidadId;
        $model->tipo = $tipo;
        $model->contenido = $contenido;
        $model->save();

        return $this->redirect(['index1',
            'unidad_id' => $planBloqueUnidadId,
            'pestana' => $pestana
        ]);
    }


    /**
     * QUITAR CONTENIDO
     */
    public function actionQuitarContenido(){
        $id = $_GET['id'];
        $pestana = $_GET['pestana'];
        $model = PlanificacionVerticalPaiOpciones::findOne($id);
        $planBloqueUnidadId = $model->plan_unidad_id;

        $model->delete();

        return $this->redirect(['index1',
            'unidad_id' => $planBloqueUnidadId,
            'pestana' => $pestana
        ]);
        
    }

}