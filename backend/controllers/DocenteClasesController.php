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
class DocenteClasesController extends Controller
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
    
    public function actionDetalleClase(){
        $html = '';
        
        $claseId    = $_GET['clase_id'];
        $accion     = $_GET['accion'];
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $html .= $this->get_bloques($claseId, $periodoId);
        
        return $html;
        
                
    }
    
    private function get_bloques($claseId, $periodoId){
                
        $con = Yii::$app->db;
        $query = "select 	b.id as bloque_id
                                    ,b.name as bloque
                                    ,b.orden 
                                    ,b.abreviatura
                                    ,case 
                                            when b.hasta <= current_timestamp then 'cerrado' else 'abierto'
                                    end as estado
                    from 	scholaris_clase c
                                    inner join scholaris_bloque_actividad b on b.tipo_uso = c.tipo_usu_bloque
                                    inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                    where 	c.id = $claseId
                                    and p.id = $periodoId 
                                    and c.es_activo = true
                    order  by b.orden ;";
        
        $bloques = $con->createCommand($query)->queryAll();        
        
        $clase = \backend\models\ScholarisClase::findOne($claseId);        
        
        $html = '';
        $html.= '<h3 style="margin: 10px"><b>'.$clase->ismAreaMateria->materia->nombre.'</b><small> '.$clase->paralelo->course->name. ' - '. $clase->paralelo->name .'</small></h3>';
        
        
        $html .= '<table style="margin: 10px">';        
        $html .= '<tr>';
        foreach ($bloques as $bloque){
            if($bloque['estado'] == 'cerrado'){
                $color = "#ab0a3d";
                $title = 'CERRADO';
            }else{
                $color = "#65b2e8";
                $title = 'ABIERTO';
            }
            $html .= '<td class="text-center">'
                    . '<a class="p-2" href="#" '
                            . 'style="border: solid 1px #ccc; border-radius: 50%; background-color: '.$color.'; color: #fff" '
                            . 'title="'.$title.'"'
                            . 'onclick="muestra_informacion_bloque('.$claseId.', '.$bloque['bloque_id'].',\'informacion\')">'
                    .$bloque['abreviatura'].'</a></td>';
        }        
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    
    public function actionDetalleBloque(){
        $claseId    = $_GET['clase_id'];
        $bloqueId   = $_GET['bloque_id'];
        $accion   = $_GET['accion'];
        
        $html = '';
        
        switch ($accion){
            case 'informacion':
                $html .= $this->get_informacion_bloque($bloqueId, $claseId);
                break;
            case 'calificadas':
                $html .= $this->get_actividades($bloqueId, $claseId, 'SI');
                break;
            case 'nocalificadas':
                $html .= $this->get_actividades($bloqueId, $claseId, 'NO');
                break;
        }
        
        return $html;
    }
    
    
    private function get_informacion_bloque($bloqueId, $claseId){
        $html = '';
        
        $bloque = \backend\models\ScholarisBloqueActividad::findOne($bloqueId);
        $html .= '<div class="animate__animated animate__bounce">';
        $html .= '<p class="p-2"><b>Te encuentras trabajando en: </b>'. $bloque->name .' | <b>Inicia </b>'. $bloque->desde.'</p>';
        
        $html .= '<p class="p-2">';
        $html .= '<a href="#" onclick="muestra_informacion_bloque('.$claseId.', '.$bloqueId.',\'calificadas\')">'
                . '<i class="fas fa-traffic-light" style="color: #0a1f8f"> Calificadas</i></a>';
        $html .= ' <b>|</b> ';
        $html .= '<a href="#" onclick="muestra_informacion_bloque('.$claseId.', '.$bloqueId.',\'nocalificadas\')">'
                .'<i class="fas fa-traffic-light" style="color: #ab0a3d"> No calificadas</i></a>';
        $html .= '</p>';
        $html .= '</div>';
        
        return $html;
    }
    
    private function get_actividades($bloqueId, $claseId, $tipoCalificacion){
        
        if($tipoCalificacion == 'SI'){
            $tipoCalif = 'ACTIVIDADES CALIFICADAS';
            $color = "#0a1f8f";
        }else{
            $tipoCalif = 'ACTIVIDADES NO CALIFICADAS';
            $color = "#ab0a3d";
        }
             
        $html = '';
                
        $bloque = \backend\models\ScholarisBloqueActividad::findOne($bloqueId);
        $fechaHoy   = date('Y-m-d H:s:i');
        $hasta      = $bloque->hasta;
        
        $fechaHoy > $hasta ? $estado = 'cerrado' : $estado = 'abierto';
        
        $html .= '<p style="color: '.$color.'"><b><u>'.$tipoCalif.'</u></b> | ';
        
        if($estado == 'abierto'){
            $html .= \yii\helpers\Html::a('<span class="badge rounded-pill" '
                    . 'style="background-color: #65b2e8">'
                            . '<i class="fas fa-plus-circle"></i> Crear actividad</span>', ['scholaris-actividad/create',
                        'claseId' => $claseId,
                        'bloqueId' => $bloqueId,
                        'calificado' => $tipoCalificacion
                    ],
                    //['target' => '_blank']
                    );
            
        }else{
            $html .= 'El bloque se encuentra cerrado!!!';
        }
        $html .= '</p>';
        
        $html .= '<div class="table table-responsive">';
        $html .= '<table class="table table-hover table-condensed">';
        $html .= '<thead>';
        $html .= '<tr style="background-color: '.$color.'; color: #fff">';        
        $html .= '<th>#</th>';        
        $html .= '<th>Semana</th>';        
        $html .= '<th>Código</th>';        
        $html .= '<th>Título</th>';        
        $html .= '<th>Tipo</th>';        
        $html .= '<th>Presentación</th>';        
        $html .= '<th colspan="2" class="text-center">Acciones</th>';        
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        $actividades = $this->consulta_actividades($bloqueId, $claseId, $tipoCalificacion);
        $i = 0;        
        foreach ($actividades as $actividad){
            $i++;
            $html .= '<tr>';
            $html .= '<td>'.$i.'</td>';
            $html .= '<td>'.$actividad['nombre_semana'].'</td>';
            $html .= '<td>'.$actividad['actividad_id'].'</td>';
            $html .= '<td>'.$actividad['title'].'</td>';
            $html .= '<td>'.$actividad['tipo_calificacion'].'</td>';
            $html .= '<td>'.$actividad['inicio'].'</td>';
            $html .= '<td class="text-center">';
            $html .= \yii\helpers\Html::a('<i class="fas fa-cogs text-segundo"> Actualizar</i>', ['scholaris-actividad/actividad',
                'actividad' => $actividad['actividad_id']
            ],['title' => 'Actualizar']);            
            $html .= '</td>';
            
//            $html .= '<td class="text-center">';
//            $html .= \yii\helpers\Html::a('<i class="fas fa-cogs"></i>', ['scholaris-actividad/create',
//                'clase_id' => $claseId,
//                'bloque_id' => $bloqueId
//            ],['title' => 'Actualizar']);
//            $html .= '</td>';
            $html .= '</tr>';
        }
        
        $html .= '<tbody>';        
        $html .= '</table>';
        $html .= '</div>';
        
        return $html;
    }
        
    
    private function get_no_calificadas($bloqueId, $claseId){
        $html = '';
        $html .= 'No Calificafas';
        
        return $html;
    }
    
    private function consulta_actividades($bloqueId, $claseId, $calificado){
        $con = Yii::$app->db;
        $query = "select 	s.id as semana_id 
                                    ,s.nombre_semana
                                    ,a.id as actividad_id
                                    ,a.title 
                                    ,a.tipo_calificacion 
                                    ,a.inicio 
                    from	scholaris_actividad a
                                    inner join scholaris_bloque_semanas s on s.id = a.semana_id 
                    where 	a.paralelo_id = $claseId
                                    and a.bloque_actividad_id = $bloqueId
                                    and a.calificado = '$calificado'
                    order by s.semana_numero ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
}