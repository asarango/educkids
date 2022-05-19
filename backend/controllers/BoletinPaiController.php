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
class BoletinPaiController extends Controller
{
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
            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
                echo $this->render('/site/error',[
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
    public function actionIndex()
    {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelAreasActuales = $this->consulta_areas_actuales($periodoId, $institutoId);
        $modelAreasAnterior = $this->consulta_areas_anteriores();
        

        return $this->render('index', [
            'modelAreasActuales' => $modelAreasActuales,
            'modelAreasAnterior' => $modelAreasAnterior
        ]);
    }
    
    private function consulta_areas_actuales($periodoId, $institutoId){
        
        $con = \Yii::$app->db;
        $query = "select 	a.id, a.name
                                    ,(select count(id) as total from scholaris_criterio_boletin where area_id = a.id)
                    from 	op_course c
                                    inner join op_section s on s.id = c.section
                                    inner join scholaris_malla_curso mc on mc.curso_id = c.id
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id
                                    inner join scholaris_malla m on m.id = mc.malla_id 
                                    inner join scholaris_malla_area ma on ma.malla_id = m.id 
                                    inner join scholaris_area a on a.id = ma.area_id 
                    where 	s.code = 'PAI'
                                    and sop.scholaris_id = $periodoId
                                    and c.x_institute = $institutoId
                    group by a.id, a.name
                    order by a.id asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function consulta_areas_anteriores(){
        
        $con = \Yii::$app->db;
        $query = "select 	a.id, concat(a.id,' - ',a.name) as name
                    from 	scholaris_criterio_boletin b
                                    inner join scholaris_area a on a.id = b.area_id 
                    group by a.id, a.name
                    order by a.id ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actionCriterios(){
        $areaId = $_POST['id'];
        $criterios = $this->consulta_criterios_boletin($areaId);
        
        
        $html = \yii\helpers\Html::a('Copiar', ['copiar-criterios', 'areaId' => $areaId]);
        
        $html.= '<table class="table table-striped table-hover">';
        $html.= '<tr>';
        $html.= '<td align="center"><strong>Criterio</strong></td>';
        $html.= '<td align="center"><strong>Detalle</strong></td>';
        $html.= '<td align="center"><strong>Orden</strong></td>';
        $html.= '</tr>';
        
        foreach ($criterios as $criterio){
            $html.= '<tr>';
            $html.= '<td align="center">'.$criterio['criterio'].'</td>';
            $html.= '<td align="center">'.$criterio['detalle'].'</td>';
            $html.= '<td align="center">'.$criterio['orden'].'</td>';
            $html.= '</tr>';
        }
        
        
        $html.= '</table>';
        
        echo $html;
    }
    
    private function consulta_criterios_boletin($areaId){
        $con = \Yii::$app->db;
        $query = "select id, criterio, detalle, area_id, orden from scholaris_criterio_boletin where area_id = $areaId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 
    
    
    public function actionCopiarCriterios($areaId){
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelArea = \backend\models\ScholarisArea::findOne($areaId);
        
        
        $modelActual = $this->consulta_areas_actuales_x_id($periodoId, $institutoId, $modelArea->name);
        
        $this->inserta_criterios($areaId, $modelActual['id']);
        
        return $this->redirect(['index']);
        
        
    }
    
    private function inserta_criterios($areaAnterior, $areaActual){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_criterio_boletin (criterio, detalle, area_id, orden)
                    select criterio, detalle, $areaActual, orden from scholaris_criterio_boletin where area_id = $areaAnterior;";
        $con->createCommand($query)->execute();
    }
    
    
    private function consulta_areas_actuales_x_id($periodoId, $institutoId, $areaNombre){
        
        $con = \Yii::$app->db;
        $query = "select 	a.id, a.name                                   
                    from 	op_course c
                                    inner join op_section s on s.id = c.section
                                    inner join scholaris_malla_curso mc on mc.curso_id = c.id
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id
                                    inner join scholaris_malla m on m.id = mc.malla_id 
                                    inner join scholaris_malla_area ma on ma.malla_id = m.id 
                                    inner join scholaris_area a on a.id = ma.area_id 
                    where 	s.code = 'PAI'
                                    and sop.scholaris_id = $periodoId
                                    and c.x_institute = $institutoId
                                    and a.name = '$areaNombre'
                    group by a.id, a.name
                    order by a.id asc;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

   
    
}
