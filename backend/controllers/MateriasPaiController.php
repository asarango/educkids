<?php

namespace backend\controllers;

use backend\models\ContenidoPaiHabilidades;
use backend\models\mapaenfoques\AjaxMapaEnfoque;
use backend\models\MapaEnfoquesPai;
use backend\models\ScholarisMateria;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class MateriasPaiController extends Controller{

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
        $materias = $this->consulta_materias_pai();
        return $this->render('index', [
            'materias' => $materias
        ]);
    }

    private function consulta_materias_pai(){
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;
        $con            = Yii::$app->db;
        $query = "select 	mat.id
                            ,mat.name as materia
                            ,mat.language_code 
                            ,mat.last_name 
                    from 	scholaris_malla m
                            inner join scholaris_malla_area ma on ma.malla_id = m.id 
                            inner join scholaris_malla_materia mm on mm.malla_area_id = ma.id
                            inner join scholaris_malla_curso mc on mc.malla_id = m.id
                            inner join op_course cu on cu.id = mc.curso_id 		
                            inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = cu.period_id 
                            inner join op_section se on se.id = cu.section
                            inner join scholaris_materia mat on mat.id = mm.materia_id 
                    where 	sop.scholaris_id = $periodoId
                            and se.code = 'PAI'
                            and cu.x_institute = $institutoId
                    group by mat.id
                            ,mat.name
                            ,mat.language_code 
                            ,mat.last_name
                    order by mat.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    } 

    public function actionMapaEnfoques(){ //Accion para tomar las habilidades
        $materiaId = $_GET['materia_id'];
        $materia = ScholarisMateria::findOne($materiaId);    
        $habilidades = $this->consulta_habilidades();
        $this->procesa_hablidades_pai_a_cursos($materiaId);

        return $this->render('mapa-enfoques',[
            'habilidades' => $habilidades,
            'materia' => $materia
        ]);
    }


    private function consulta_habilidades(){
        $con = Yii::$app->db;
        $query = "select 	orden_titulo2, es_titulo2 
                    from 	contenido_pai_habilidades 
                    group by orden_titulo2, es_titulo2 order by orden_titulo2;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function procesa_hablidades_pai_a_cursos($materiaId){
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;

        $habilidades    = ContenidoPaiHabilidades::find()->all();
        $cursosPai      = $this->consulta_cursos_pai($periodoId, $institutoId);
        
        foreach($habilidades as $habilidad){
            foreach($cursosPai as $curso){
                $model = MapaEnfoquesPai::find()->where([
                    'periodo_id'         => $periodoId,
                    'course_template_id' => $curso['id'],
                    'pai_habilidad_id'   => $habilidad->id 
                ])->one();
                if(!isset($model)){                    
                    $mapaEnfoque = new MapaEnfoquesPai();
                    $mapaEnfoque->periodo_id            = $periodoId;
                    $mapaEnfoque->course_template_id    = $curso['id'];
                    $mapaEnfoque->pai_habilidad_id      = $habilidad->id;
                    $mapaEnfoque->save();
                }
            }
        }        
    }

    private function consulta_cursos_pai($periodoId, $institutoId){
        
        $con            = Yii::$app->db;
        $query = "select 	t.id, t.name 		
        from 	op_course c
                inner join op_section s on s.id = c.section
                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id 
                inner join op_course_template t on t.id = c.x_template_id 
        where 	sop.scholaris_id = $periodoId
                and s.code = 'PAI'
                and c.x_institute = $institutoId
        order by t.next_course_id desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionAjaxMapaEnfoque(){
        $materiaId      = $_GET['materia_id'];       
        $habilidadOrden = $_GET['habilidad_orden'];   
        $periodoId      = Yii::$app->user->identity->periodo_id;
        $institutoId    = Yii::$app->user->identity->instituto_defecto;

        $cursosPai      = $this->consulta_cursos_pai($periodoId, $institutoId);    
        $html = new AjaxMapaEnfoque($materiaId, $habilidadOrden, $cursosPai, $periodoId);
        return $html->html;
    }    

    public function actionActivaInactiva(){
        $id = $_GET['id'];
        $model = MapaEnfoquesPai::findOne($id);

        if($model->estado == true){
            $model->estado = false;
        }else{
            $model->estado = true;
        }

        $model->save();
    }

}