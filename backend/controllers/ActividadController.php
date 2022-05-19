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
class ActividadController extends Controller {

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

    public function actionIndex1() {
        $semanaId = $_GET['semana_id'];
        $claseId = $_GET['clase_id'];
        $accion = $_GET['accion'];
        
        $html = '';

        switch ($accion) {
            case 'datos':
                $html .= $this->datos_semana($semanaId, $claseId);
                break;
            case 'insumos':
                $tipo = $_GET['tipo_calificacion'];
                $html .= $this->get_insumos($tipo);
                break;
            case 'crear';
                $this->crea_actividad($_GET);
        }

        return $html;
    }
    
    private function crea_actividad($get){
        $useLog = Yii::$app->user->identity->usuario;
        $user = \backend\models\ResUsers::find()->where(['login' => $useLog])->one();
        $fechaHoy       = date('Y-m-d H:i:s');
        $title          = $_GET['title'];
        $descripcion    = $_GET['descripcion'];
        $inicio         = $_GET['inicio'];
        $fin            = $_GET['inicio'];
        $tipoActividadId = $_GET['tipo_actividad_id'];
        $bloqueId       = $_GET['bloque_id'];
        $claseId        = $_GET['clase_id'];
        
        $clase = \backend\models\ScholarisClase::findOne($claseId);        
        
        $materiaId      = $clase->idmateria;
        $calificado     = $_GET['calificado'];
        $tipoCalifi     = $_GET['tipo_calificacion'];
        $tipoCalifi     = $_GET['tipo_calificacion'];
        $tareas         = $_GET['tareas'];
        
        $horas = $this->horas($inicio, $claseId);
        
        $horaId         = $horas[0]['id'];
        $original       = 0;
        $semanaId       = $_GET['semana_id'];
        
        $model = new \backend\models\ScholarisActividad();
        $model->create_date     = $fechaHoy;
        $model->write_date      = $fechaHoy;
        $model->create_uid      = $user->id;
        $model->write_uid       = $user->id;
        $model->title           = $title;
        $model->descripcion     = $descripcion;
        $model->inicio          = $inicio;
        $model->fin             = $fin;
        $model->tipo_actividad_id = $tipoActividadId;
        $model->bloque_actividad_id = $bloqueId;
        $model->paralelo_id     = $claseId;
        $model->calificado      = $calificado;
        $model->tipo_calificacion = $tipoCalifi;
        $model->tareas          = $tareas;
        $model->hora_id = $horaId;
        $model->actividad_original = $original;
        $model->semana_id       = $semanaId;
        
        $model->save();
        
        return $this->redirect(['scholaris-actividad/actividad',
                'actividad' => $model->id
            ]);
        
    }
    
    
    private function horas($fecha, $clase)
    {

        $sentencia = new \backend\models\SentenciasSql();

        //$fecha="2018-11-16" ; // fecha.
        #separas la fecha en subcadenas y asignarlas a variables
        #relacionadas en contenido, por ejemplo dia, mes y anio.

        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);

        $diaNumero = date('w', mktime(0, 0, 0, $mes, $dia, $anio));
        //donde:
        #W (mayúscula) te devuelve el número de semana
        #w (minúscula) te devuelve el número de día dentro de la semana (0=domingo, #6=sabado)

        $modelHoras = $sentencia->horasDia($clase, $diaNumero);

        return $modelHoras;
    }
    
    private function get_insumos($tipo){
        $html = '';
        
        if($tipo == 'N'){
            $insumos = \backend\models\ScholarisTipoActividad::find()
                    ->where(['tipo' => $tipo])
                    ->orderBy('orden')
                    ->all();
            
            foreach ($insumos as $insumo){
                $html .= '<option value="'.$insumo->id.'">'.$insumo->nombre_nacional.'</option>';
            }
            
        }else{
            $insumos = \backend\models\ScholarisTipoActividad::find()
                    ->where(['tipo' => $tipo])
                    ->orderBy('orden')
                    ->all();
            foreach ($insumos as $insumo){
                $html .= '<option value="'.$insumo->id.'">'.$insumo->nombre_nacional.'</option>';
            }
        }
                
        
        return $html;
    }

    private function datos_semana($semanaId, $claseId) {        
        $semana = \backend\models\ScholarisBloqueSemanas::findOne($semanaId);        
        $modelClase = \backend\models\ScholarisClase::findOne($claseId);                

        $html = '';
        $html .= '<p>';
        $html .= '<h4><b>' . $semana->nombre_semana . ':</b><small> del ' . $semana->fecha_inicio . ' al ' . $semana->fecha_finaliza . '</small></h4>';
        $html .= '</p>';
        $html .= '<hr>';
        
        $html .= '<div class="card shadow p-3" style="border: solid 1px #0a1f8f">';
        $html .= '<div class="row">';
            $detailWeek = $this->get_detail_week($semanaId, $claseId);
            
            if($detailWeek){
                foreach ($detailWeek['disponibilidad'] as $dispo){
                    $html .= '<div class="col text-center">';
                    $html .= $dispo['dia'].'<br>';
                    $html .= $dispo['fecha'].'<br>';
                    //$html .= '<span class="badge bg-secondary"> '.$dispo['total_actividades'].'</span><br>';
                    $html .= '<button class="bg-primero zoom" '
                            . 'style="border: 1px solid #eee;border-radius: 50%; padding: 5px; width:45px"'
                            . 'onclick="show_form(\''.$dispo['fecha'].'\', \''.$dispo['dia'].'\')">'
                            . '<h5>'.$dispo['total_actividades'].'</h5>'
                            . '</button>';
                                       
                    if ($modelClase->paralelo->course->section0->code == 'PAI') {
                        $html.= 'PAI';
                    }
                                        
                    $html .= '</div>';
                }
            }
        $html .= '</div>';        
        $html .= '</div>';        

        return $html;
    }

    private function get_detail_week($weekId, $classId) {

        $data = array();

        //$modelClase = ScholarisClase::findOne($classId); 
        $modelSemana = \backend\models\ScholarisBloqueSemanas::findOne($weekId);

        $bloqueId = $modelSemana->bloque_id;

        $sentencias = new \backend\models\SentenciasSql();

        $fechasDisponibles = $sentencias->fechasDisponiblesSemana($modelSemana->fecha_inicio, $modelSemana->fecha_finaliza, $classId, $bloqueId, $weekId);

        $disponibilidad = array();

        foreach ($fechasDisponibles as $dispo) {
            $totalActividades = $this->get_cantidad_actividades($dispo['fecha'], $classId);
            $dispo['total_actividades'] = $totalActividades;

            array_push($disponibilidad, $dispo);
        }

        $data = array(
            'week' => $modelSemana,
            'disponibilidad' => $disponibilidad
        );

        return $data;
    }

    private function get_cantidad_actividades($fecha, $claseId) {
        $periodoId = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	sum(total_actividades) as total_actividades 
                    from 	dw_total_actividades_paralelo
                    where	paralelo_id in (
                    select 	cla.paralelo_id
                    from 	scholaris_grupo_alumno_clase gru
                            inner join scholaris_clase cla on cla.id = gru.clase_id 
                            inner join scholaris_periodo per on per.codigo = cla.periodo_scholaris
                            inner join op_course cur on cur.id = cla.idcurso 
                    where 	per.id = $periodoId
                            and gru.estudiante_id in (
                                                            select 	 estudiante_id 										
                                                from	scholaris_grupo_alumno_clase g
                                                        inner join scholaris_clase c on c.id = g.clase_id 
                                                where 	clase_id = $claseId
                            )
                    group by cla.paralelo_id, cur.name
                    ) and fecha_presentacion >= '$fecha' and fecha_presentacion <= '$fecha';";

        $res = $con->createCommand($query)->queryOne();

        isset($res['total_actividades']) ? $total = $res['total_actividades'] : $total = 0;
        return $total;
    }

}
