<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\OpCourseParaleloSearch;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class DesempenoController extends Controller {
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

        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();


        $searchModel = new \backend\models\OpCourseParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPeriodo->id, $institutoId);

        $modelPeriodoOdoo = $searchModel->toma_periodo_odoo($institutoId, $modelPeriodo->id);

        $modelCursos = \backend\models\OpCourse::find()
                ->innerJoin("op_section", "op_section.id = op_course.section")
                ->where(['op_section.period_id' => $modelPeriodoOdoo['id']])
                ->all();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelCursos' => $modelCursos,
                    'periodoCodigo' => $modelPeriodo->codigo
        ]);
    }

    public function actionForm() {
        $periodo = Yii::$app->user->identity->periodo_id;
        $paralelo = $_GET['id'];
        $model = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelUso->tipo_usu_bloque;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);

        $modelMinimos = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $modelMaximos = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'excelente'])->one();

        $modelBloques = \backend\models\ScholarisBloqueActividad::find()
                ->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_uso' => $uso,
                    'tipo_bloque' => 'PARCIAL'
                ])
                ->orderBy('orden')
                ->all();
        
        if(count($modelBloques) == 4){
            $data = array(
                'p1' => 'PARCIAL 1',
                'p2' => 'PARCIAL 2',
                'ex1' => 'EXAMEN 1',
                'q1' => 'QUIMESTRE 1',
                'p4' => 'PARCIAL 3',
                'p5' => 'PARCIAL 4',
                'ex2' => 'EXAMEN 2',
                'q2' => 'QUIMESTRE 2',
                'final_ano_normal' => 'FIN DE AÑO'
            );
        }else{
            $data = array(
                'p1' => 'PARCIAL 1',
                'p2' => 'PARCIAL 2',
                'p3' => 'PARCIAL 3',
                'ex1' => 'EXAMEN 1',
                'q1' => 'QUIMESTRE 1',
                'p4' => 'PARCIAL 4',
                'p5' => 'PARCIAL 5',
                'p6' => 'PARCIAL 6',
                'ex2' => 'EXAMEN 2',
                'q2' => 'QUIMESTRE 2',
                'final_ano_normal' => 'FIN DE AÑO'
            );
        }
        
        

        return $this->render('form', [
                    'model' => $model,
                    'modelBloques' => $modelBloques,
                    'bajos' => $modelMinimos->valor,
                    'altos' => $modelMaximos->valor,
                    'data' => $data
        ]);
    }

    public function actionDetalle() {


        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $id = $_POST['id'];
        }


        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $id])
                ->one();


        $modelParaleloS = new OpCourseParaleloSearch();
        $modelMaterias = $modelParaleloS->toma_materias_paralelo($id, $modelPeriodo->codigo);

        $arrMaterias = array();
        $arrTotales = array();


        if ($_POST) {
            $campo = $_POST['parcial'];
            $operador = $_POST['operador'];
            $valor = $_POST['valor'];

            $mensaje = $this->mensaje($campo, $operador, $valor);

            foreach ($modelMaterias as $materia) {
                array_push($arrMaterias, $materia['materia']);
                $modelTotales = $modelParaleloS->toma_materias_totales($campo, $operador, $materia['id'], $valor);
                array_push($arrTotales, $modelTotales['total']);
            }
        } else {

            $modelMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

            foreach ($modelMaterias as $materia) {
                array_push($arrMaterias, $materia['materia']);
                $modelTotales = $modelParaleloS->toma_materias_totales('final_ano_normal', '<', $materia['id'], $modelMinima->valor);
                array_push($arrTotales, $modelTotales['total']);
            }
            $mensaje = $this->mensaje('final_ano_normal', '<', $modelMinima->valor);
        }



        return $this->render("detalle", [
                    "modelParalelo" => $modelParalelo,
                    "materias" => $arrMaterias,
                    "totales" => $arrTotales,
                    "mensaje" => $mensaje,
        ]);
    }

    private function mensaje($parcial, $operador, $valor) {
        $mensaje = array();

        switch ($parcial) {
            case 'final_ano_normal':
                $par = 'FIN DE AÑO';
                break;

            case 'p1':
                $par = 'PARCIAL 1';
                break;

            case 'p2':
                $par = 'PARCIAL 2';
                break;

            case 'p3':
                $par = 'PARCIAL 3';
                break;

            case 'ex1':
                $par = 'EXAMEN 1';
                break;

            case 'q1':
                $par = 'QUIMESTRE 1';
                break;

            case 'p4':
                $par = 'PARCIAL 4';
                break;

            case 'p5':
                $par = 'PARCIAL 5';
                break;

            case 'p6':
                $par = 'PARCIAL 6';
                break;

            case 'ex2':
                $par = 'EXAMEN 2';
                break;

            case 'q2':
                $par = 'QUIMESTRE 2';
                break;

            case 'final_ano_normal':
                $par = 'FIN DE AÑO';
                break;

            default:
                break;
        }

        switch ($operador) {
            case '=':
                $oper = 'ES IGUAL A ';
                break;

            case '<':
                $oper = 'ES MENOR QUE ';
                break;

            case '>':
                $oper = 'ES MAYOR QUE ';
                break;

            default:
                $oper = '';
                break;
        }


        array_push($mensaje, $par);
        array_push($mensaje, $oper);
        array_push($mensaje, $valor);

        return $mensaje;
    }

    public function actionDetalle1() {


        $paralelo = $_POST['paralelo'];
        $campo = $_POST['parcial'];
        $bajos = $_POST['bajos'];
        $altos = $_POST['altos'];

        

        $modelMinimo = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

        $usuario = \Yii::$app->user->identity->usuario;

        $modelAlumnos = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
//                ->where(["op_student_inscription.parallel_id" => $paralelo])
                ->where(["op_student_inscription.parallel_id" => $paralelo, 'op_student_inscription.inscription_state' => 'M'])
                ->orderBy("op_student.last_name", "op_student.first_name")
                ->all();

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $modelMalla = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $modelParaleloS = new OpCourseParaleloSearch();
        $modelMaterias = $modelParaleloS->toma_materias_paralelo($paralelo, $modelPeriodo->codigo);
        $modelMateriasComp = $modelParaleloS->toma_materias_paralelo_comportamiento($paralelo, $modelPeriodo->codigo);

        $mensaje = $this->mensaje($campo, '', '');
        
        ///para tipo de calificacion
        $modelTipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where([
            'scholaris_periodo_id' => $periodoId
        ])->one();

        
        if(isset($modelTipoCalificacion->codigo)>=0){
            $tipoCalificacion = $modelTipoCalificacion->codigo;
        }else{
            
        }
       
        if($tipoCalificacion == 0){ 
            new \backend\models\ProcesaNotasNormales($paralelo, ''); //invoca clase de procesamiento de notas
        }elseif($tipoCalificacion == 3){
            //new \backend\models\ProcesaNotasInterdisciplinar($paralelo, ''); //clase para interdisciplinar
        }
        else{
           echo '<h1>No se configuró tipos de calificación al periodo!</h1>';
        }

        
        /////finaliza el procesamiento de notas ////
        
        return $this->render("detalle1", [
                    "modelParalelo" => $modelParalelo,
                    "modelMaterias" => $modelMaterias,
                    "modelAlumnos" => $modelAlumnos,
                    "campo" => $campo,
                    "modelMinimo" => $modelMinimo,
                    "mensaje" => $mensaje,
                    "periodoCodigo" => $modelPeriodo->codigo,
                    "usuario" => $usuario,
                    "malla" => $modelMalla->malla_id,
                    "bajos" => $bajos,
                    "altos" => $altos,
                    "modelMateriasComp" => $modelMateriasComp,
                    "tipoCalificacion" => $tipoCalificacion
        ]);
    }

    public function actionSabanageneral() {
        
        $paralelo = $_GET['id'];
        $quimestre = 'q2';
        
        //$reporte = new \backend\models\InformeSabanaQuimestralExcel();
        $reporte = new \backend\models\InfSabanaExcel();
        $reporte->genera_reporte($paralelo, $quimestre);
        //$reporte->genera_reporte($paralelo, $quimestre);
    }

    public function actionDetallenota() {
        $alumno = $_POST['alumno'];
        $clase = $_POST['clase'];
        $campo = $_POST['campo'];
        $nota = $_POST['nota'];

        $orden = $this->get_orden_bloque($campo);

        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentencias2 = new \backend\models\SentenciasNotas();

        $sentenciasIns = new \backend\models\SentenciasRepInsumos();


        $modelClase = \backend\models\ScholarisClase::find()->where(['id' => $clase])->one();
        $seccion = $modelClase->course->section0->code;
//
        //$modelTipo = $sentencias->tipo_de_actividades_clase($alumno, $clase, $orden);
        $modelTipo = $sentencias->insumos_reporte_padre($alumno, $clase, $orden);
//        
        $modelBloque = \backend\models\ScholarisBloqueActividad::find()
                ->where([
                    'orden' => $orden,
                    'tipo_uso' => $modelClase->tipo_usu_bloque,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo
                ])
                ->one();
//        
//
        $html = "";
        $html .= '<p>' . $modelClase->materia->name . '</p>';
        $html .= '<p>' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name . '</p>';
        $html .= '<hr>';
////
        foreach ($modelTipo as $tipo) {
            $html .= '<table width="100%" class="table table-condensed table-hover">';
            $html .= '<tr>';
            $html .= ($seccion == 'PAI') ? '<td bgcolor="#07FBA6" colspan="2">' . $tipo['nombre_nacional'] . '</td>' : '<td bgcolor="#07FBA6" colspan="2">' . $tipo['nombre_nacional'] . '</td>';
//
            $modelActividades = $sentencias->actividades_clase($alumno, $clase, $tipo['grupo_numero'], $orden);
            $html .= '</tr>';
//
            $suma = 0;
            $cont = 0;
            foreach ($modelActividades as $activ) {
//
                $suma = $suma + $activ['calificacion'];
                $cont++;

                $titulo = $activ['title'];

                $html .= '<tr>';
                $html .= '<td width="">' . $titulo . '</td>';
                //echo '<td width="80%">xxx</td>';
                $html .= '<td>' . $activ['calificacion'] . '</td>';
                $html .= '</tr>';
            }
//
            $final = $suma / $cont;
            $final = $sentencias2->truncarNota($final, 2);
            $final = number_format($final, 2);

            $html .= '<tr>';
            $html .= '<td bgcolor="#F98716">PROMEDIO NORMAL:</td>';
            $html .= '<td bgcolor="#F98716">' . $final . '</td>';
            $html .= '</tr>';
//            
            $nota1 = $sentenciasIns->get_promedio_insumo($clase, $alumno, $activ['grupo_numero'], $modelBloque->id);
//            
            if ($final != $nota1) {
//                
                $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
                        ->where(['estudiante_id' => $alumno, 'clase_id' => $clase])
                        ->one();
//                
                $modelNotaRef = \backend\models\ScholarisRefuerzo::find()
                        ->where(['grupo_id' => $modelGrupo->id, 'bloque_id' => $modelBloque->id, 'orden_calificacion' => $activ['grupo_numero']])
                        ->one();

                $html .= '<tr>';
                $html .= '<td bgcolor="#F98716"><strong>REFUERZO:</strong></td>';
                $html .= '<td bgcolor="#F98716"><strong>' . $modelNotaRef->nota_refuerzo . '</strong></td>';
                $html .= '</tr>';

                $html .= '<tr>';
                $html .= '<td bgcolor="#F98716"><strong>PROMEDIO CON REFUERZO:</strong></td>';
                $html .= '<td bgcolor="#F98716"><strong>' . $nota1 . '</strong></td>';
                $html .= '</tr>';
            }

            $html .= '</table>';
        }

        $html .= '<hr>';
        $html .= '<h2>PROMEDIO PARCIAL: ' . $nota . '</h2>';

        return $html;
    }

    private function get_orden_bloque($campo) {
        switch ($campo) {
            case 'p1';
                $orden = 1;
                break;
            case 'p2';
                $orden = 2;
                break;
            case 'p3';
                $orden = 3;
                break;
            case 'ex1';
                $orden = 4;
                break;
            case 'p4';
                $orden = 5;
                break;
            case 'p5';
                $orden = 6;
                break;
            case 'p6';
                $orden = 7;
                break;
            case 'ex2';
                $orden = 8;
                break;
        }

        return $orden;
    }

    public function actionSabana() {
        $clase = $_GET['clase'];
        $this->redirect(['reporte-sabana-profesor/pdf', 'clase' => $clase]);
    }

    public function actionLibreta() {
        $alumno = $_GET['alumno'];
        $paralelo = $_GET['paralelo'];
        //$curso = $_GET['curso'];
        $campo = $_GET['campo'];

        if ($campo == 'p1' || $campo == 'p2' || $campo == 'p3' || $campo == 'q1') {
            $quimestre = 'q1';
        } else {
            $quimestre = 'q2';
        }

        $sentencias = new \backend\models\InformeAprendizajeQuimestral();
        $sentencias->genera_reporte_alumno($alumno, $paralelo, $quimestre);


//        $this->redirect(['scholaris-rep-libreta2/index',
//                            'alumno' => $alumno,
//                            'paralelo' => $paralelo,
//                            'curso' => $curso
//                ]);
    }

    public function actionInformecomportamiento() {

        $alumno = $_GET['alumno'];
        $campo = $_GET['campo'];
        $paralelo = $_GET['paralelo'];

        $bloque = $this->consultaBloque($campo, $paralelo);

        $sentencias = new \backend\models\InformeComportamientoSugerido();

        $sentencias->genera_reporte($alumno, $bloque, $paralelo);
    }

    private function consultaBloque($campo, $paralelo) {

        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        $modelClase = \backend\models\ScholarisClase::find()
                ->where(['paralelo_id' => $paralelo])
                ->one();

        switch ($campo) {
            case 'p1':
                $orden = 1;
                break;

            case 'p2':
                $orden = 2;
                break;

            case 'p3':
                $orden = 3;
                break;

            case 'ex1':
                $orden = 4;
                break;

            case 'q1':
                $orden = 3;
                break;

            case 'p4':
                $orden = 5;
                break;

            case 'p5':
                $orden = 6;
                break;

            case 'p6':
                $orden = 7;
                break;

            case 'ex2':
                $orden = 8;
                break;

            case 'q2':
                $orden = 7;
                break;
        }

        $model = \backend\models\ScholarisBloqueActividad::find()
                ->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_uso' => $modelClase->tipo_usu_bloque,
                    'orden' => $orden
                ])
                ->one();

//        echo $model->id;
//        die();

        return $model->id;
    }

    public function actionNotasprofesor() {
        $clase = $_GET['clase'];
        $campo = $_GET['campo'];
        $paralelo = $_GET['paralelo'];

        $bloqueId = $this->consultaBloque($campo, $paralelo);

//        echo $campo;
//        die();        


        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $seccion = $modelParalelo->course->section0->code;

        if ($seccion == 'PAI') {

            $reporte = new \backend\models\InformeNotasProfesorPai();
            $reporte->parcial($bloqueId, $clase);
        } else {
            $reporte = new \backend\models\InformeNotasProfesorNac();
            $reporte->parcial($bloqueId, $clase);
        }
    }

}
