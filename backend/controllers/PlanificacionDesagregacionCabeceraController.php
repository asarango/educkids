<?php

namespace backend\controllers;

use backend\models\CurriculoMec;
use backend\models\CurriculoMecNiveles;
use Yii;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCabeceraSearch;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanificacionDesagregacionCabeceraController implements the CRUD actions for PlanificacionDesagregacionCabecera model.
 */
class PlanificacionDesagregacionCabeceraController extends Controller
{
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

    public function actionIndex()
    {
        //$niveles = \backend\models\CurriculoMecNiveles::find()->all();
        $cursos = \backend\models\OpCourseTemplate::find()->all();

        return $this->render('index', [
            'cursos' => $cursos
        ]);
    }


    public function actionListMaterias()
    {

        $cursoId = $_POST['curso_id'];
        $this->insert_cabecera($cursoId); //Inserta materias al planificacion_desagregacion_cabecera
        $asignaturas = $this->query_asignaturas_x_nivel($cursoId); //toma las asignaturas

        $html = "";

        foreach ($asignaturas as $asignatura) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . $asignatura['id'] . '</td>';            
            $html .= '<td>' . $asignatura['name'] . '</td>';
            //$html .= '<td class="text-center" style="background-color:'.$asignatura['color'].'" ></td>';
            $html .= '<td class="text-center">' . $asignatura['total_criterios_evaluacion'] . '</td>';

            $html .= '<td class="text-center">';
            $html .= '<div class="btn-group" role="group">';
            $html .= '<button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
            $html .= 'Acciones';
            $html .= '</button>';
            $html .= '<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
            $html .= '<li>';
            //$html .= Html::a('Desagregación', ['desagregacion', 'id' => $asignatura['id']], ['class' => 'dropdown-item', 'style' => 'font-size:10px']);
            $html .= Html::a('Desagregación', ['planificacion-bloques-unidad/index1', 'id' => $asignatura['id']], ['class' => 'dropdown-item', 'style' => 'font-size:10px']);
            $html .= '</li>';
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        echo $html;
    }

    private function insert_cabecera($nivelId)
    {
        $con = Yii::$app->db;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $query = "insert into planificacion_desagregacion_cabecera 
                    (ism_area_materia_id, year_from, year_to, is_active, comments, scholaris_periodo_id, estado)
                    select 	am.id , 2021, 2025, true ,'CREADO AUTOMÁTICAMENTE'
                                    ,pm.scholaris_periodo_id 
                                    ,'INICIANDO' 
                    from 	ism_malla mal
                                    inner join ism_periodo_malla pm on pm.malla_id = mal.id 
                                    inner join ism_malla_area ma on ma.periodo_malla_id = pm.id 
                                    inner join ism_area a on a.id = ma.area_id 
                                    inner join ism_area_materia am on am.malla_area_id = ma.id		
                                    inner join ism_materia m on m.id = am.materia_id 
                    where 	mal.op_course_template_id = $nivelId
                                    and pm.scholaris_periodo_id = $periodoId
                                    and am.id not in (select ism_area_materia_id  
                                                        from planificacion_desagregacion_cabecera 
                                                        where ism_area_materia_id  = am.id  
                                                                and op_course_template_id = mal.op_course_template_id  
                                                                and scholaris_periodo_id = pm.scholaris_periodo_id);";
        
        $con->createCommand($query)->execute();
    }

    private function query_asignaturas_x_nivel($nivelId)
    {
        $con = Yii::$app->db;
        $query = "select 	cab.id
		,m.nombre as name
		,count(cri.id) as total_criterios_evaluacion
                from 	planificacion_desagregacion_cabecera cab 
                                inner join ism_area_materia iam on iam.id = cab.ism_area_materia_id 
                                inner join ism_malla_area ia on ia.id = iam.malla_area_id 
                                inner join ism_periodo_malla ipm on ipm.id = ia.periodo_malla_id 
                                inner join ism_malla im on im.id = ipm.malla_id
                                inner join ism_materia m on m.id = iam.materia_id 
                                left join planificacion_desagregacion_criterios_evaluacion cri on cri.criterio_evaluacion_id = cab.id 
                where 	im.op_course_template_id = $nivelId
                group by cab.id ,m.nombre;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionDesagregacion()
    {
        //$cabeceraId = $_GET['cabecera_id'];
        $unidadId   = $_GET['unidad_id'];

        $bloqueUnidad = \backend\models\PlanificacionBloquesUnidad::findOne($unidadId);
        //$desagregacion = \backend\models\PlanificacionDesagregacionCabecera::findOne($cabeceraId);

        $criterios = \backend\models\PlanificacionDesagregacionCriteriosEvaluacion::find()->where(
            ['bloque_unidad_id' => $unidadId]
        )->all();

        $materia = \backend\models\IsmMateria::findOne($bloqueUnidad->planCabecera->ismAreaMateria->materia_id);        

        $criteriosNoUsados  = $this->query_criterios_evaluacion($bloqueUnidad->planCabecera->ismAreaMateria->asignatura_curriculo_id, 
                                                                $bloqueUnidad->planCabecera->ismAreaMateria->curso_curriculo_id, 
                                                                $unidadId);
        $criteriosUsados    = $this->query_usuados($unidadId);
        return $this->render('desagregacion', [
            'criterios'     => $criterios,
            'bloqueUnidad' => $bloqueUnidad,
            'criteriosNoUsados' => $criteriosNoUsados,
            'criteriosUsados' => $criteriosUsados,
            'materia' => $materia
        ]);
    }

    private function query_criterios_evaluacion($asignaturaId, $nivelId, $unidadId)
    {
        $con = Yii::$app->db;
        $query = "select 	id, asignatura_id, subnivel_id, reference_type, code, description, is_essential, order_block, aux_1, aux_2, belongs_to 
                    from 	curriculo_mec c
                    where 	c.asignatura_id = $asignaturaId
                            and c.subnivel_id = $nivelId
                            and reference_type = 'evaluacion'
                            and c.id not in(select 	criterio_evaluacion_id 
                                            from 	planificacion_desagregacion_criterios_evaluacion
                                            where 	bloque_unidad_id = $unidadId
                                                    and criterio_evaluacion_id = c.id)
                    order by code;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function query_usuados($unidadId)
    {
        $con = Yii::$app->db;
        $query = "select 	e.id ,c.code 
                            ,c.description 
                            ,count(d.id) as total_destrezas
                    from 	planificacion_desagregacion_criterios_evaluacion e 
                            inner join curriculo_mec c ON c.id = e.criterio_evaluacion_id 
                            left join planificacion_desagregacion_criterios_destreza d on d.desagregacion_evaluacion_id = e.id 
                    where 		e.bloque_unidad_id = $unidadId
                    group by e.id ,c.code 
                            ,c.description
                    order by 	c.code;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionAsignar()
    {
        $unidadId = $_GET['unidad_id'];
        $criterioId = $_GET['criterio_id'];

        $model = new \backend\models\PlanificacionDesagregacionCriteriosEvaluacion();
        $model->bloque_unidad_id = $unidadId;
        $model->criterio_evaluacion_id = $criterioId;
        $model->is_active = true;
        $model->save();

        return $this->redirect([
            'desagregacion',
            'unidad_id' => $unidadId
        ]);
    }

    public function actionQuitar()
    {
        $id = $_GET['id'];
        $model = \backend\models\PlanificacionDesagregacionCriteriosEvaluacion::findOne($id);
        $unidadId = $model->bloque_unidad_id;
        $model->delete();

        return $this->redirect([
            'desagregacion',
            'unidad_id' => $unidadId
        ]);
    }


    /**
     * ACCION QUE MUSTRAS LAS DESTREZAS ELEGIDAS PARA EL CRITERIO
     * DE EVALUACIÓN
     */
    public function actionDestrezasDetalle()
    {
        $criterioEvaluacionId = $_GET['criterio_evaluacion_id'];
        $criterioEvaluacion = \backend\models\PlanificacionDesagregacionCriteriosEvaluacion::findOne($criterioEvaluacionId);
        $bloqueUnidadId = $criterioEvaluacion->bloque_unidad_id;

        $destrezas = $this->consulta_destrezas($criterioEvaluacion->criterioEvaluacion->code, $bloqueUnidadId);

        $destrezasGroup = $this->get_destrezas_por_criterio($criterioEvaluacionId);

        $cursoId = $criterioEvaluacion->bloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;

        $course = \backend\models\OpCourseTemplate::findOne($cursoId);
        
        return $this->render('destrezas-detalle', [
            'criterioEvaluacion' => $criterioEvaluacion,
            'destrezas' => $destrezas,
            'destrezasGroup' => $destrezasGroup,
            'course' => $course
        ]);
    }

    private function consulta_destrezas($criterioCode, $bloqueUnidadId){
        $con    = Yii::$app->db;
        $query  = "select 	id, asignatura_id, subnivel_id, reference_type, code, description, is_essential, order_block, aux_1, aux_2, belongs_to 
        from 	curriculo_mec c
        where 	belongs_to = '$criterioCode'
                and reference_type = 'destrezas'
                and id not in (
            select 	curriculo_destreza_id 
        from 	planificacion_desagregacion_criterios_destreza des
                inner join planificacion_desagregacion_criterios_evaluacion cre on cre.id = des.desagregacion_evaluacion_id
        where 	des.curriculo_destreza_id = c.id
                and cre.bloque_unidad_id = $bloqueUnidadId
                );";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionIngresaDestreza()
    {
        $destrezaId = $_POST['destreza_id'];
        $criterioId = $_POST['criterio_id'];
        $criEvaluacion = \backend\models\PlanificacionDesagregacionCriteriosEvaluacion::findOne($criterioId); //Para tomar la cabecera que llega a obtener el nivelId
        $opCourseId = $criEvaluacion->bloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        $this->add_destrezas($opCourseId, $criterioId, $destrezaId); //agrega las destrezas a la tabla
    }

    private function add_destrezas($cursoId, $criterioId, $destrezaId)
    {
        $destreza = \backend\models\CurriculoMec::find()->where(['id' => $destrezaId])->one();        

            $modelDestreza = \backend\models\PlanificacionDesagregacionCriteriosDestreza::find()->where([
                'desagregacion_evaluacion_id'   => $criterioId,
                'curriculo_destreza_id'         => $destrezaId,
                'course_template_id'            => $cursoId
            ])->one();

            if (!isset($modelDestreza)) {
                $model = new \backend\models\PlanificacionDesagregacionCriteriosDestreza();
                $model->desagregacion_evaluacion_id     = $criterioId;
                $model->curriculo_destreza_id           = $destrezaId;
                $model->course_template_id              = $cursoId;
                $model->opcion_desagregacion            = 'ORIGINAL';
                $model->content                         = $destreza->description;
                $model->is_active                       = true;
                $model->save();
            }
    }

    private function get_destrezas_por_criterio($palnCriteriosId)
    {
        $con = Yii::$app->db;
        $query = "select 	curriculo_destreza_id, c.description, c.code, c.is_essential
                    from 	planificacion_desagregacion_criterios_destreza d
                            inner join curriculo_mec c on c.id = d.curriculo_destreza_id
                    where 	d.desagregacion_evaluacion_id = $palnCriteriosId
                    group  by d.curriculo_destreza_id, c.description, c.code, c.is_essential
                    order by d.curriculo_destreza_id;";                    
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



    public function actionChange(){
        $id = $_GET['id'];        
        $modelDestreza = \backend\models\PlanificacionDesagregacionCriteriosDestreza::findOne($id);

        if($modelDestreza->load(Yii::$app->request->post()) && $modelDestreza->save()){
            return $this->redirect(['destrezas-detalle', 
                'criterio_evaluacion_id' => $modelDestreza->desagregacion_evaluacion_id
            ]);
        }

        return $this->render('change', [
            'modelDestreza' => $modelDestreza 
        ]);

    }


    public function actionDeletePlanDestreza(){
        $curriculoDestrezaId    = $_GET['id'];
        $criterioEvaluacionId   = $_GET['criterioEvaluacionId'];

        $con = Yii::$app->db;
        $query = "delete from planificacion_desagregacion_criterios_destreza where curriculo_destreza_id = $curriculoDestrezaId";
        $con->createCommand($query)->execute();

        return $this->redirect(['destrezas-detalle', 'criterio_evaluacion_id' => $criterioEvaluacionId]);
    } 
}
