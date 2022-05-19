<?php

namespace backend\controllers;

use backend\models\helpers\HelperGeneral;
use backend\models\Nee;
use backend\models\NeeDetalle;
use backend\models\NeeXClase;
use backend\models\NeeXOpcion; 
use backend\models\OpInstitute;
use backend\models\services\WebServicesUrls;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class NeeController extends Controller
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
        $usuarioLog = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $estudiantes = $this->consulta_estudiantes($periodoId, $usuarioLog);
        $nees = $this->consulta_nee($periodoId, $usuarioLog);

        return $this->render('index', [
            'estudiantes' => $estudiantes,
            'nee' => $nees
        ]);
    }

    private function consulta_estudiantes($scholarisPeriodoId, $usuarioLog)
    {
        $con = Yii::$app->db;
        $query = "select 	s.id 
		,concat(s.last_name, ' ',s.first_name,' ',s.middle_name) as student
from 	res_users u
		inner join op_faculty f on f.partner_id = u.partner_id
		inner join scholaris_clase c on c.idprofesor = f.id
		inner join scholaris_periodo p on p.codigo = c.periodo_scholaris
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join op_student_inscription i on i.student_id = g.estudiante_id 
		inner join op_student s on s.id = i.student_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 		
			and sop.scholaris_id = p.id 
where 	u.login = '$usuarioLog'
		and p.id = $scholarisPeriodoId
order by s.last_name, s.first_name, s.middle_name ;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function consulta_nee($scholarisPeriodoId, $usuarioLog)
    {
        $con = Yii::$app->db;
        $query = "select 	nee.id 
		,concat(s.last_name, ' ',s.first_name,' ',s.middle_name) as student
		,nee.created_at 
from 	res_users u
		inner join op_faculty f on f.partner_id = u.partner_id
		inner join scholaris_clase c on c.idprofesor = f.id
		inner join scholaris_periodo p on p.codigo = c.periodo_scholaris
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join op_student_inscription i on i.student_id = g.estudiante_id 
		inner join op_student s on s.id = i.student_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 		
			and sop.scholaris_id = p.id 
		inner join nee on nee.student_id = i.student_id 
where 	u.login = '$usuarioLog'
		and p.id = $scholarisPeriodoId
order by s.last_name, s.first_name, s.middle_name ;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function actionCreate()
    {

        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $ahora = date('Y-m-d H:i:s');
        $studentId = $_POST['id'];

        $nee = Nee::find()->where(['student_id' => $studentId])->one();

        if (!$nee) {
            $model = new Nee();
            $model->student_id = $studentId;
            $model->scholaris_periodo_id = $periodoId;
            $model->created_at = $ahora;
            $model->created = $usuario;
            $model->save();
        }

        $modelNee =  Nee::find()->where(['student_id' => $studentId])->one();
        return $this->redirect(['ficha', 'nee_id' => $modelNee->id, 'pestana' => 'datos_estudiante']);
    }

    public function actionFicha()
    {

        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $neeId = $_GET['nee_id'];

        if(!isset($_GET['pestana'])){
            $pestana = 'datos_estudiante';
        }else{
            $pestana = $_GET['pestana'];
        }


        $model = Nee::findOne($neeId);
        // inicio info obtenida desde el web service
        $odooService = new WebServicesUrls('odoo');
        $dataJson = $odooService->consumir_servicio($odooService->url.'/nee/'.$model->student_id);
        $student = json_decode($dataJson);
				
        //fin obtenida desde el web service

        //Obteniendo las edades
		$ageFamily = array();
        $helper = new HelperGeneral();
        $birthDate = $student->data_student[0]->birth_date;
        $edad = $helper->calcular_edad($birthDate);

        $ageStudent =  "{$edad->format('%Y')} años y {$edad->format('%m')} meses"; // Aplicamos un formato al objeto resultante de la funcion	
		$ageFamily['student'] = $ageStudent;
		
		if($student->data_parents[0]->x_state == 'madre'){
			$edadMama = $helper->calcular_edad($student->data_parents[0]->x_birth_date);
			$ageMom	 = "{$edadMama->format('%Y')} años y {$edadMama->format('%m')} meses"; 
			$ageFamily['madre'] =  $ageMom;
		}
		
		if($student->data_parents[0]->x_state == 'padre'){
			$edadPapa = $helper->calcular_edad($student->data_parents[0]->x_birth_date);
			$ageDad	 = "{$edadPapa->format('%Y')} años y {$edadPapa->format('%m')} meses"; 
			$ageFamily['padre'] =  $ageDad;
		}elseif(isset($student->data_parents[1]->x_state) == 'padre'){
			$edadPapa = $helper->calcular_edad($student->data_parents[1]->x_birth_date);
			$ageDad	 = "{$edadPapa->format('%Y')} años y {$edadPapa->format('%m')} meses"; 
			$ageFamily['padre'] =  $ageDad;
		}
		       
        //FInal de la edadedade

        $instituto      = OpInstitute::findOne($institutoId);
        $materiasSelect = $this->consulta_materias_estudiante($model->student_id, $institutoId);
        $materiasNee    = NeeXClase::find()->where(['nee_id' => $neeId])->all();

        $this->ingresa_opciones($neeId); //Ingresa las opciones en vacio
        // $opciones5 = $this->consulta_seccion_5($neeId);
        // $opciones6 = $this->consulta_seccion_6($neeId);

        $detalle = NeeDetalle::find()->where(['nee_id' => $neeId])->all();

        $historial = Nee::find()->where([
            '<>', 'scholaris_periodo_id', $periodoId
        ])->all();

         return $this->render('ficha', [
             'model' => $model,
             'student' => $student,
             'age_family' => $ageFamily,
             'instituto' => $instituto,
             'materiasSelect' => $materiasSelect,
             'materiasNee' => $materiasNee,
             'detalle' => $detalle,
            //  'opciones5' => $opciones5,
            //  'opciones6' => $opciones6,
             'historial' => $historial,
             'pestana' => $pestana             
         ]);
    }

    private function consulta_materias_estudiante($studentId, $periodoId){
        $con = Yii::$app->db;
        $query = "select 	c.id as clase_id
                            ,m.name as materia
                    from 	scholaris_grupo_alumno_clase g
                            inner join scholaris_clase c on c.id = g.clase_id
                            inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                            inner join scholaris_materia m on m.id = c.idmateria 
                    where 	g.estudiante_id = $studentId
                            and p.id = $periodoId
                            and c.id not in (
                                select clase_id from nee_x_clase where clase_id = c.id and fecha_finaliza is null			
                            )
                    order by m.name;";
            $res = $con->createCommand($query)->queryAll();
            return $res;
    }

    private function ingresa_opciones($neeId) {
        $usuarioLog = Yii::$app->user->identity->usuario;
        $con = Yii::$app->db;
        $query = "insert into nee_detalle (nee_id, opcion_codigo, categoria, contenido, es_seleccionado, created_at, created, updated_at, updated)
                    select 	$neeId
                            , op.codigo, op.categoria, op.nombre, false, current_timestamp, '$usuarioLog', current_timestamp, '$usuarioLog'
                    from 	nee_opciones op
                    where 	op.codigo not in (select 	opcion_codigo 
                                                from 	nee_detalle
                                                where 	nee_id = $neeId
                                                        and opcion_codigo = op.codigo) 
                            and op.estado = true
                    order by op.categoria, op.orden;";
        $con->createCommand($query)->execute();
    }


    public function consulta_seccion_5($neeId){
        $con = Yii::$app->db;
        $query = "select 	xo.id 
                        ,o.codigo 
                        ,o.seccion 
                        ,o.orden 
                        ,o.nombre 
                        ,o.estado 
                        ,xo.contenido 
                from 	nee_x_opcion xo
                        inner join nee_opciones o on o.id = xo.opcion_id 
                where 	xo.nee_id = $neeId
                        and o.seccion = 5
                order by o.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function consulta_seccion_6($neeId){
        $con = Yii::$app->db;
        $query = "select 	xo.id 
                            ,o.codigo 
                            ,o.seccion 
                            ,o.orden 
                            ,o.nombre 
                            ,o.estado 
                            ,xo.es_seleccionado 
                    from 	nee_x_opcion xo
                            inner join nee_opciones o on o.id = xo.opcion_id 
                    where 	xo.nee_id = $neeId
                            and o.seccion in (61,62)
                    order by o.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }





    public function actionInsertClass(){

        $neeId      = $_POST["nee_id"];
        $claseId    = $_POST["clase_id"];
        $grado      = 1;
        $diagnostico_inicia = 'Aquí su diagnóstico';
        $fechaHoy   = date("Y-m-d");        

        $model = new NeeXClase();
        $model->nee_id = $neeId;
        $model->clase_id = $claseId;
        $model->grado_nee = $grado;
        $model->fecha_inicia = $fechaHoy;
        $model->diagnostico_inicia = $diagnostico_inicia;
        $model->save();

        return $this->redirect(['ficha', 
            'nee_id' => $neeId,
            'pestana' => 'fecha_elab'
        ]);

    }

    public function actionUpdateClass(){
        $id         = $_POST['id'];
        $gradoNee   = $_POST['grado_nee'];
        $diagInicia = $_POST['diagnostico_inicia'];
        $fechaFinal = $_POST['fecha_finaliza'];
        $diagFinali = $_POST['diagnostico_finaliza'];
        
        $model = NeeXClase::findOne($id);
        $model->grado_nee = $gradoNee;
        $model->diagnostico_inicia = $diagInicia;
        $model->fecha_finaliza = $fechaFinal;
        $model->diagnostico_finaliza = $diagFinali;
        $model->save();

        return $this->redirect(['ficha', 
            'nee_id' => $model->nee_id,
            'pestana' => 'fecha_elab'
        ]);
    }


    public function actionUpdateSections(){
        $id         = $_POST['id'];
        $contenido  = $_POST['contenido'];
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date("Y-m-d H:i:s");

        $model = NeeXOpcion::findOne($id);
        
        if($model->opcion->seccion == 5){
            $model->contenido = $contenido;
        }else{
            $model->es_seleccionado = $contenido;
        }

        $model->updated_at = $fechaHoy;
        $model->updated = $usuarioLog;
        $model->save();

        return $this->redirect(['ficha',
            'nee_id' => $model->nee_id,
            'pestana' => 'informe_psicopedagogico'
        ]);

    }

    public function actionUpdateSections6(){
                
        $id         = $_POST['id'];
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date("Y-m-d H:i:s");

        $model = NeeXOpcion::findOne($id);
        
        if($model->es_seleccionado == 0){
            $cambio = 1;
        }else{
            $cambio = 0;
        }

        $model->es_seleccionado = $cambio;
        $model->updated_at = $fechaHoy;
        $model->updated = $usuarioLog;
        
        $model->save();       
    }
}
