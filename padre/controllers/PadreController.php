<?php

namespace padre\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class PadreController extends Controller {

    /**
     * {@inheritdoc}
     */
//     public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//    public function beforeAction($action) {
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



    public function actionAlumno($id, $paralelo) {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        

        $sentencia = new \backend\models\OpStudent();

        $modelBase = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'base'])
                ->one();

        $db = $modelBase->nombre;

        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student.id' => $id, "op_student_inscription.parallel_id" => $paralelo])
                ->one();

        $modelCurso = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $deuda = $sentencia->tiene_deuda($modelAlumno->id);
        
        //provisional
        $deuda = false;
        

        $modelFotos = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'fotos1'])
                ->one();
        
        $modelFotosPath2 = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'fotos2'])
                ->one();

        $tipoCalificacion = \backend\models\ScholarisQuimestreTipoCalificacion::find()
                ->where(['codigo' => 'covid19'])
                ->all();
        
        $parametroCalifica = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'tipocalif'
        ])->one();
        
        $modelRevisaNotas = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'muestranotas'])->one();
        
        $novedades = $this->consulta_novedades($id, $modelPeriodo->codigo);
        

        if ($deuda == true) {
            $mensaje = 'Usted no puede verificar la información de su representado, '
                    . 'le pedimos se acerque a la institución';
            return $this->redirect(['mensaje',
                        'mensaje' => $mensaje,
                        'alumno' => $modelAlumno->id,
                        'paralelo' => $paralelo,
                        'modelFotos' => $modelFotos,
                        'tipoCalificacion' => $tipoCalificacion
            ]);
        } {
            return $this->render('alumno', [
                        'modelAlumno' => $modelAlumno,
                        'modelCurso' => $modelCurso,
                        'db' => $db,
                        'modelFotos' => $modelFotos,
                        'modelFotosPath2' => $modelFotosPath2,
                        'tipoCalificacion' => $tipoCalificacion,
                        'parametroCalifica' => $parametroCalifica,
                        'modelRevisaNotas' => $modelRevisaNotas,
                        'novedades' => $novedades
            ]);
        }
    }
    
    
    private function consulta_novedades($alumnoId, $periodoCodigo){
        $db = Yii::$app->db;
        $query = "select 	n.id 
                                ,p.fecha, p.hora_ingresa 
                                ,d.nombre, d.codigo 
                                ,m.name as materia
                                ,concat(f.x_first_name, ' ',f.last_name) as profesor
                                ,n.observacion 
                from 	scholaris_asistencia_alumnos_novedades n
                                inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
                                inner join scholaris_clase c on c.id = g.clase_id 
                                inner join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id 
                                inner join scholaris_asistencia_profesor p on p.id = n.asistencia_profesor_id
                                inner join scholaris_materia m on m.id = c.idmateria
                                inner join op_faculty f on f.id = c.idprofesor 
                where 	g.estudiante_id = $alumnoId
                                and c.periodo_scholaris = '$periodoCodigo';";
        $res = $db->createCommand($query)->queryAll();
        return $res;
    }
    

    public function actionMensaje() {
        $mensaje = $_GET['mensaje'];
        $alumno = $_GET['alumno'];
        $paralelo = $_GET['paralelo'];

        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student.id' => $alumno, "op_student_inscription.parallel_id" => $paralelo])
                ->one();
        $modelCurso = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        return $this->render('mensaje', [
                    'mensaje' => $mensaje,
                    'modelAlumno' => $modelAlumno,
                    'modelCurso' => $modelCurso
        ]);
    }
    
    public function actionLibretas(){   
      
        $paralelo = $_GET['paralelo'];
        $quimestre = $_GET['quimestre'];
        $reporte = $_GET['reporte'];
        $alumno = $_GET['alumno'];
        
        
        switch ($reporte){
            
            case 'LIBRETAQ1V1':
                
                $libretaImprimir = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'codigolibleta'])->one(); 
                
                if($libretaImprimir->valor == 'aqia'){
//                    $reporte = new \backend\models\InfLibretaPdfQ1IsmN($paralelo, $alumno, $quimestre);
                    $reporte = new \backend\models\InfLibretaPdfQ2IsmN($paralelo, $alumno, $quimestre);
                }else{
                    $reporte = new \backend\models\InfLibretaPdfQ2V1($paralelo, $alumno, $quimestre);
                }
                
                break;
       
        }
        
        
    }

    public function actionNotas($id, $paralelo) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        $sentenciaRec = new \backend\models\SentenciasRecalcularUltima();
        
        
//        new \backend\models\ProcesaNotasNormales($paralelo, ''); //Realiza el proceso de notas para libreta        
        new \backend\models\ProcesaNotasNormales($paralelo, $id); //Realiza el proceso de notas para libreta        
        
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPerido = \backend\models\ScholarisPeriodo::findOne($periodo);


        $modelNotas = $sentencias->get_notas_clases_normales_optativas($id);
        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student.id' => $id, "op_student_inscription.parallel_id" => $paralelo])
                ->one();

        $modelCurso = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $seccion = $modelCurso->course->section0->code;

        $modelRepoLib = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'firmalib'])->one();
        
        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelUso->tipo_usu_bloque;
                
        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
            'tipo_uso' => $uso,
            'scholaris_periodo_codigo' => $modelPerido->codigo,
            'tipo_bloque' => 'PARCIAL'
        ])->orderBy('orden')
          ->all();
        
        $modelBloquesQ1 = \backend\models\ScholarisBloqueActividad::find()->where([
            'tipo_uso' => $uso,
            'scholaris_periodo_codigo' => $modelPerido->codigo,
            'tipo_bloque' => 'PARCIAL',
            'quimestre' => 'QUIMESTRE I'
        ])->orderBy('orden')
          ->all();
        
        $modelBloquesQ2 = \backend\models\ScholarisBloqueActividad::find()->where([
            'tipo_uso' => $uso,
            'scholaris_periodo_codigo' => $modelPerido->codigo,
            'tipo_bloque' => 'PARCIAL',
            'quimestre' => 'QUIMESTRE II'
        ])->orderBy('orden')
          ->all();
        
        
        /**
         * para tomar tipo de calificacion
         */
        $modelTipoCalificacion = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();
        $tipoCalificacion = $modelTipoCalificacion->valor;
        //////// fin de tipo de calificacion /////////////
        
        $tieneProyectos = $this->tiene_proyectos($modelCurso->id, $modelPerido->codigo); //llama a funcion para buscar si tiene proyectos
        
        /*         * ********** para ver tipo de proyectos ******** */
        $modelTipoProyectos = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $modelCurso->course_id])->one();
        $tipoCalificacionProyectos = $modelTipoProyectos->tipo_proyectos;
        /////////////////////////////////////////////////////////////////////////////
        

        return $this->render('notas', [
                    'modelAlumno' => $modelAlumno,
                    'modelCurso' => $modelCurso,
//                    'modelAreas' => $modelAreas,
                    'modelNotas' => $modelNotas,
                    'seccion' => $seccion,
                    'modelRepoLib' => $modelRepoLib,
                    'modelBloques' => $modelBloques,
                    'modelBloquesQ1' => $modelBloquesQ1,
                    'modelBloquesQ2' => $modelBloquesQ2,
                    'tipoCalificacion' => $tipoCalificacion,
                    'tieneProyectos' => $tieneProyectos,
                    'tipoCalificacionProyectos' => $tipoCalificacionProyectos
        ]);
    }
    
    private function tiene_proyectos($paralelo, $periodoCodigo) {
        $con = Yii::$app->db;
        $query = "select 	count(i.id) as total
                    from 	op_student_inscription i
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                    where	i.parallel_id = $paralelo
                                    and c.periodo_scholaris = '$periodoCodigo'
		and mm.tipo = 'PROYECTOS';";
        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }
    
    
    public function actionNotasCovid($id, $paralelo){
        
        $sentenciasClase = new \backend\models\SentenciasClase();
        
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPerido = \backend\models\ScholarisPeriodo::findOne($periodo);
        
        new \backend\models\ProcesaNotasNormales($paralelo, $id);
        
        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student.id' => $id, "op_student_inscription.parallel_id" => $paralelo])
                ->one();

        $modelCurso = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();


        $modelClases = $sentenciasClase->consulta_materias_normales($id);
        
        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelUso->tipo_usu_bloque;
               
        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
            'tipo_uso' => $uso,
            'scholaris_periodo_codigo' => $modelPerido->codigo,
        ])->orderBy('orden')
          ->all();
        
        $modelCalificacionAutoma = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'covidautoma'
        ])->one();
        
        if($modelCalificacionAutoma){
            $automatico = $modelCalificacionAutoma->valor;
        }else{
            $automatico = 0;
        }
//        
        /** tipo de calificacion * */
        $modelTipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $periodo])->one();
        $tipoCalificacion = $modelTipoCalificacion->codigo;
        ///// fin de tippo de calificacion ///// 

        return $this->render('notas-covid', [
                    'modelAlumno' => $modelAlumno,
                    'modelCurso' => $modelCurso,
                    'modelClases' => $modelClases,
                    'modelBloques' => $modelBloques,
                    'modelPeriodo' => $modelPerido,
                    'automatico' => $automatico,
                    'tipoCalificacion' => $tipoCalificacion
        ]);
    }
    

    public function actionInformeaprendizaje() {

        $alumno = $_GET['alumno'];
        $paralelo = $_GET['paralelo'];
        $quimestre = $_GET['campo'];

        $sentencias = new \backend\models\InformeAprendizajeQuimestral();
        $sentencias->genera_reporte_alumno($alumno, $paralelo, $quimestre);
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

    public function actionActividades() {
        
        

        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentencias2 = new \backend\models\SentenciasNotas();

        $sentenciasIns = new \backend\models\SentenciasRepInsumos();


        $alumno = $_GET['alumno'];
        $clase = $_GET['clase'];
        $orden = $_GET['orden'];
        $nota = $_GET['nota'];

        $modelClase = \backend\models\ScholarisClase::find()->where(['id' => $clase])->one();
        $seccion = $modelClase->course->section0->code;

        
        //$modelTipo = $sentencias->tipo_de_actividades_clase($alumno, $clase, $orden);
        $modelTipo = $sentencias->insumos_reporte_padre($alumno, $clase, $orden);

        $modelBloque = \backend\models\ScholarisBloqueActividad::find()
                ->where([
                    'orden' => $orden,
                    'tipo_uso' => $modelClase->tipo_usu_bloque,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo
                ])
                ->one();

//        $modelTipoQuim = \backend\models\ScholarisQuimestreTipoCalificacion::find()
//                ->innerJoin("scholaris_quimestre q", "q.id = scholaris_quimestre_tipo_calificacion.quimestre_id")
//                ->where(['q.codigo' => $modelBloque->quimestre, 'scholaris_quimestre_tipo_calificacion.codigo' => 'covid19'])
//                ->one();

        $modelTipoQuim = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where([
            'scholaris_periodo_id' => $periodo
        ])->one();
        
                
        $html = "";
        
        if ($modelTipoQuim->codigo == 0) {
//
            foreach ($modelTipo as $tipo) {
                $html .= '<table width="100%" class="table table-condensed table-hover">';
                $html .= '<tr>';
                $html .= ($seccion == 'PAI') ? '<td bgcolor="#07FBA6" colspan="2">' . $tipo['nombre_nacional'] . '</td>' : '<td bgcolor="#07FBA6" colspan="2">' . $tipo['nombre_nacional'] . '</td>';

                $modelActividades = $sentencias->actividades_clase($alumno, $clase, $tipo['grupo_numero'], $orden);
                $html .= '</tr>';



                $suma = 0;
                $cont = 0;
                foreach ($modelActividades as $activ) {

                    $suma = $suma + $activ['calificacion'];
                    $cont++;

                    $titulo = $activ['title'];

                    $html .= '<tr>';
                    $html .= '<td width="">' . $titulo . '</td>';
                    //echo '<td width="80%">xxx</td>';
                    $html .= '<td>' . $activ['calificacion'] . '</td>';
                    $html .= '</tr>';
                }
                if ($cont == 0) {
                    $cont = 1;
                }
                $final = $suma / $cont;
                $final = $sentencias2->truncarNota($final, 2);
                $final = number_format($final, 2);

                $html .= '<tr>';
                $html .= '<td bgcolor="#F98716">PROMEDIO NORMAL:</td>';
                $html .= '<td bgcolor="#F98716">' . $final . '</td>';
                $html .= '</tr>';



                $nota1 = $sentenciasIns->get_promedio_insumo($clase, $alumno, $activ['grupo_numero'], $modelBloque->id);

                if ($final != $nota1) {

                    $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
                            ->where(['estudiante_id' => $alumno, 'clase_id' => $clase])
                            ->one();

                    $modelNotaRef = \backend\models\ScholarisRefuerzo::find()
                            ->where(['grupo_id' => $modelGrupo->id, 'bloque_id' => $modelBloque->id, 'orden_calificacion' => $activ['grupo_numero']])
                            ->one();


//                $html .= '<td bgcolor="#F98716"><strong>' . $nota1 . '</strong></td>';

                    $html .= '<tr>';
                    $html .= '<td bgcolor="#F98716"><strong>REFUERZO:</strong></td>';
                    if (isset($modelNotaRef->nota_refuerzo)) {
                        $html .= '<td bgcolor="#F98716"><strong>' . $modelNotaRef->nota_refuerzo . '</strong></td>';
                    } else {
                        $html .= '<td bgcolor="#F98716"><strong>0.00</strong></td>';
                    }

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
        } else {

            $sentenciaAl = new \backend\models\SentenciasAlumnos();
            $modelInscription = $sentenciaAl->get_alumno_inscription($alumno);
            $modelPortafolio = \backend\models\ScholarisCalificacionCovid19::find()->where([
                        'inscription_id' => $modelInscription['inscripcion_id'],
                        'tipo_quimestre_id' => $modelTipoQuim->id
                    ])
                    ->one();


            $html .= '<p><strong>CALIFICACIÓN DESDE PORTAFOLIO</strong></p>';
            $html .= '<table width="100%" class="table table-condensed table-hover table-bordered table-striped">';
            $html .= '<tr>';
            $html .= '<td><strong>CALIFICACIÓN</strong></td>';
            $html .= '<td><strong>VALOR</strong></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td>Portafolio:</td>';
            $html .= '<td>' . $modelPortafolio->portafolio . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td>Contenido:</td>';
            $html .= '<td>' . $modelPortafolio->contenido . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td>Presentación:</td>';
            $html .= '<td>' . $modelPortafolio->presentacion . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td>Familia:</td>';
            $html .= '<td>' . $modelPortafolio->padre . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td><strong>Total:</strong></td>';
            $html .= '<td><strong>' . $modelPortafolio->total . '</strong></td>';
            $html .= '</tr>';


            $html .= '</table>';
        }
        
        return $html;
        
    }

    public function actionListaactividades($id, $paralelo) {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $sentencias = new \backend\models\SentenciasPadre();

//        $modelActi = $sentencias->actividades_hijo($id, $modelPeriodo->codigo);

        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(["op_student_inscription.parallel_id" => $paralelo, 'op_student_inscription.student_id' => $id])
                ->one();

        return $this->render('actividades', [
                    'modelAlumno' => $modelAlumno,
//                    'modelActi' => $modelActi,
                    'paralelo' => $paralelo
        ]);
    }

    public function actionDetalleActividades() {
        $id = $_GET['id'];
        $paralelo = $_GET['paralelo'];
        $tiempo = $_GET['tiempo'];


        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $sentencias = new \backend\models\SentenciasPadre();

        $modelActi = $sentencias->actividades_hijo($id, $modelPeriodo->codigo, $tiempo);

        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(["op_student_inscription.parallel_id" => $paralelo, 'op_student_inscription.student_id' => $id])
                ->one();

        return $this->render('detalle-actividades', [
                    'modelAlumno' => $modelAlumno,
                    'modelActi' => $modelActi,
                    'paralelo' => $paralelo
        ]);
    }

    public function actionComportamiento($id, $paralelo) {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $sentencias = new \backend\models\SentenciasPadre();

        $modelActi = $sentencias->comportamiento_hijo($id, $modelPeriodo->codigo);

        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(["op_student_inscription.parallel_id" => $paralelo, 'op_student_inscription.student_id' => $id])
                ->one();

        return $this->render('comportamiento', [
                    'modelAlumno' => $modelAlumno,
                    'modelActi' => $modelActi,
                    'paralelo' => $paralelo
        ]);
    }

    public function actionActividaddetalle() {
        $actividadId = $_GET['actividadId'];
        $alumnoId = $_GET['alumnoId'];
        $paralelo = $_GET['paralelo'];

        $modelAlumno = \backend\models\OpStudentInscription::find()
                ->where([
                    'student_id' => $alumnoId,
                    'parallel_id' => $paralelo
                ])
                ->one();

        $modelActividad = \backend\models\ScholarisActividad::findOne($actividadId);
        

        //$modelArchivos = \frontend\models\ScholarisArchivosprofesor::find()->where(['idactividad' => $actividadId])->all();
        $modelArchivos = \backend\models\ScholarisArchivosprofesor::find()->where(['idactividad' => $actividadId])->all();

        $modelFormDeber = new \backend\models\ScholarisActividadDeber();

        $modelArchivosAl = \backend\models\ScholarisActividadDeber::find()->where([
                    'actividad_id' => $actividadId,
                    'alumno_id' => $alumnoId
                ])->all();

        $modelCalificacion = \backend\models\ScholarisCalificaciones::find()
                ->where([
                    'idalumno' => $alumnoId,
                    'idactividad' => $actividadId
                ])
                ->one();

        return $this->render('actividaddetalle', [
                    'modelAlumno' => $modelAlumno,
                    'modelActividad' => $modelActividad,
                    'modelArchivos' => $modelArchivos,
                    'modelFormDeber' => $modelFormDeber,
                    'modelArchivosAl' => $modelArchivosAl,
                    'modelCalificacion' => $modelCalificacion
        ]);
    }

    public function actionDescargar($ruta) {
        $path = "../../backend/web/imagenes/instituto/archivos-profesor/";

        return \Yii::$app->response->sendFile($path . $ruta);
    }

    public function actionNotasinicial($id, $paralelo) {

        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student.id' => $id, "op_student_inscription.parallel_id" => $paralelo])
                ->one();

        $modelCurso = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $mensaje = '';

        return $this->render('notasinicial', [
                    'mensaje' => $mensaje,
                    'modelAlumno' => $modelAlumno,
                    'modelCurso' => $modelCurso
        ]);
    }
    
    
    public function actionInformeaprendizajeinicial(){
        
        $paralelo = $_GET['paralelo'];
        $quimestre = $_GET['quimestre'];
        $reporte = $_GET['reporte'];
        $alumno = $_GET['alumno'];
        
        switch ($reporte){
            case 'q1inicial':
                $reporte = new \backend\models\InformeAprendizajeIniciales();
                $reporte->genera_reporte($paralelo, $quimestre,$alumno);
                break;
        }
    }

    public function actionListaactividadesinicial() {
        // print_r($_GET);
        $alumnoId = $_GET['id'];
        $paralelo = $_GET['paralelo'];

        $modelAlumno = \backend\models\OpStudent::findOne($alumnoId);
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelActividades = $this->get_actividiades_alumno($alumnoId, $paralelo);

        $hoy = date('Y-m-d H:i:s');


        return $this->render('actividadesinicial', [
                    'modelAlumno' => $modelAlumno,
                    'modelParalelo' => $modelParalelo,
                    'modelActividades' => $modelActividades,
                    'hoy' => $hoy
        ]);
    }

    private function get_actividiades_alumno($alumno, $paralelo) {
        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);

        $con = \Yii::$app->db;
        $query = "select 	i.id, m.name as materia
		,f.last_name 
		,f.x_first_name 
		,f.middle_name 
		,i.titulo 
		,quimestre_codigo 
		,creado_fecha 
		,fecha_inicio 
		,fecha_entrega 
                ,tipo_material
                ,link_videoconferencia
                ,respaldo_videoconferencia
		,(
			select 	count(id) 
			from 	scholaris_tarea_inicial_resuelta 
			where 	tarea_inicial_id = i.id
					and alumno_id = $alumno
		) as total_archivos
                ,(
			select 	count(id) 
			from 	scholaris_tarea_inicial_resuelta 
			where 	tarea_inicial_id = i.id
					and alumno_id = $alumno
		) as total_archivos
                ,( select count(id) 
                    from scholaris_tarea_inicial_resuelta 
                    where tarea_inicial_id = i.id 
                            and alumno_id = $alumno
                            and observacion_profesor is not null
                            ) as observacion_profesor
from 	scholaris_tarea_inicial i
		inner join scholaris_clase c on c.id = i.clase_id 
		inner join scholaris_materia m on m.id = c.idmateria 
		inner join op_faculty f on f.id = c.idprofesor 
where 	c.periodo_scholaris = '$modelPeriodo->codigo' 
        and c.paralelo_id = $paralelo
order by i.quimestre_codigo desc
		,i.fecha_entrega asc;";

//        echo $query;
//        die();


        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionFormulariosubir() {
        // print_r($_GET);
        $actividad = $_GET['actividadId'];
        $alumno = $_GET['alumnoId'];
        $fecha = date('YmdHis');

        $model = new \backend\models\ScholarisTareaInicialResuelta();
        $modelAlumno = \backend\models\OpStudent::findOne($alumno);
        $modelActividad = \backend\models\ScholarisTareaInicial::findOne($actividad);


        if ($model->load(Yii::$app->request->post())) {


            $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'archivo');
            $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);


            if (!empty($imagenSubida)) {
                $path = '../../backend/web/imagenes/instituto/archivos-profesor/';
                $model->archivo = $alumno . $fecha . $model->archivo . $imagenSubida->name;

                $model->save();
                $imagenSubida->saveAs($path . $model->archivo);

                return $this->redirect(['listaactividadesinicial',
                            'id' => $alumno,
                            'paralelo' => $modelActividad->clase->paralelo_id
                ]);
            }


            //return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('formulariosubir', [
                    'modelActividad' => $modelActividad,
                    'modelAlumno' => $modelAlumno,
                    'model' => $model
        ]);
    }

    public function actionDescargainicial() {

        $actividadId = $_GET['actividadId'];
        $model = \backend\models\ScholarisTareaInicial::findOne($actividadId);

        $ruta = $model->nombre_archivo;

        $path = "../../backend/web/imagenes/instituto/archivos-profesor/";

        return \Yii::$app->response->sendFile($path . $ruta);
    }

    public function actionSubirpadrenormal() {

        $alumno = $_POST['ScholarisActividadDeber']['alumno_id'];
        $actividad = $_POST['ScholarisActividadDeber']['actividad_id'];
        $creadoPor = $_POST['ScholarisActividadDeber']['creado_por'];
        $creadoFecha = $_POST['ScholarisActividadDeber']['creado_fecha'];
        $actualizadoPor = $_POST['ScholarisActividadDeber']['actualizado_por'];
        $actualizadoFecha = $_POST['ScholarisActividadDeber']['actualizado_fecha'];

        $fecha = date('YmdHis');

        $model = new \backend\models\ScholarisActividadDeber();

        $modelActividad = \backend\models\ScholarisActividad::findOne($actividad);


        $imagenSubida = \yii\web\UploadedFile::getInstance($model, 'archivo');
        $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);


        if (!empty($imagenSubida)) {
            $path = '../../backend/web/imagenes/instituto/archivos-profesor/';

            $model->actividad_id = $actividad;
            $model->alumno_id = $alumno;
            $model->archivo = $fecha . $alumno . $model->archivo . $imagenSubida->name;
            $model->creado_por = $creadoPor;
            $model->creado_fecha = $creadoFecha;
            $model->actualizado_por = $actualizadoPor;
            $model->actualizado_fecha = $actualizadoFecha;


            if ($imagenSubida->saveAs($path . $model->archivo)) {
                $model->save();
            }
        }


        return $this->redirect(['actividaddetalle',
                    'actividadId' => $actividad,
                    'alumnoId' => $alumno,
                    'paralelo' => $modelActividad->clase->paralelo_id
        ]);
    }

    public function actionEliminardeber() {
        $deberId = $_GET['id'];

        $modelDeber = \backend\models\ScholarisActividadDeber::findOne($deberId);

        $paralelo = $modelDeber->actividad->clase->paralelo_id;
        $alumno = $modelDeber->alumno_id;
        $actividad = $modelDeber->actividad_id;

        $modelDeber->delete();
        return $this->redirect(['actividaddetalle',
                    'actividadId' => $actividad,
                    'alumnoId' => $alumno,
                    'paralelo' => $paralelo
        ]);
    }

    public function actionObservacionesprf() {
        //print_r($_GET);

        $alumno = $_GET['alumno'];
        $tareaId = $_GET['actividad'];

        $model = \backend\models\ScholarisTareaInicialResuelta::find()
                ->where([
                    'tarea_inicial_id' => $tareaId,
                    'alumno_id' => $alumno
                ])
                ->one();

        echo '<p>' . $model->observacion_profesor . '</p>';
    }

    public function actionCalificacionpadre() {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasBloque = new \backend\models\SentenciasBloque();
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $alumno = $_GET['id'];
        $paralelo = $_GET['paralelo'];
        $hoy = date("Y-m-d H:i:s");

        $modelAlumno = \backend\models\OpStudent::findOne($alumno);
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        
        $modelTipoCalif = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'tipocalif'
        ])->one();

        
        
        if($modelTipoCalif->valor == 1){
            $modelNotas = $this->notas_interdisciplinares($alumno, $modelPeriodo->codigo, $modelUso->tipo_usu_bloque);
            return $this->render('calificacioninterdisciplinar', [
                    'modelAlumno' => $modelAlumno,
                    'modelParalelo' => $modelParalelo,
                    'modelTipoCalif' => $modelTipoCalif,
                    'modelNotas' => $modelNotas,
                    'hoy' => $hoy
        ]);
        }elseif($modelTipoCalif->valor == 2){            
            $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $modelParalelo->id])->one();
            
            $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
                        'tipo_uso' => $modelUso->tipo_usu_bloque,
                        'scholaris_periodo_codigo' => $modelPeriodo->codigo                
                    ])
                    ->orderBy('orden')
                    ->all();
            
            return $this->render('calificaciondisciplinar', [
                    'modelAlumno' => $modelAlumno,
                    'modelParalelo' => $modelParalelo,
                    'modelTipoCalif' => $modelTipoCalif,
                    'modelBloques' => $modelBloques,
                    'hoy' => $hoy
        ]);
        }
        
        
        
        
    }
    
    
    private function notas_interdisciplinares($alumno, $peridoCodigo, $uso){
        $con = Yii::$app->db;
        $query = "select 	b.id 
                                    ,b.name
                                    ,b.hasta 
                                    ,b.hasta - interval '5 days' as desde
                                    ,(select 	par.nota
                    from 	scholaris_calificaciones_parcial par
                                    inner join scholaris_grupo_alumno_clase gru on gru.id = par.grupo_id 
                                    inner join scholaris_clase cla on cla.id = gru.clase_id 
                                    inner join scholaris_malla_materia mal on mal.id = cla.malla_materia 
                    where	par.bloque_id = b.id 
                                    and gru.estudiante_id = $alumno
                                    and cla.periodo_scholaris = '$peridoCodigo'
                                    and par.codigo_que_califica = 'padre'
                                    and mal.tipo = 'COMPORTAMIENTO') as nota
                                    ,(select 	gru.id 
                    from 	scholaris_calificaciones_parcial par
                                    inner join scholaris_grupo_alumno_clase gru on gru.id = par.grupo_id 
                                    inner join scholaris_clase cla on cla.id = gru.clase_id 
                                    inner join scholaris_malla_materia mal on mal.id = cla.malla_materia 
                    where	par.bloque_id = b.id 
                                    and gru.estudiante_id = $alumno
                                    and cla.periodo_scholaris = '$peridoCodigo'
                                    and par.codigo_que_califica = 'padre'
                                    and mal.tipo = 'COMPORTAMIENTO') as grupo_id
                    from	scholaris_bloque_actividad b		
                    where	b.scholaris_periodo_codigo = '$peridoCodigo'
                                    and tipo_uso = '$uso'
                                    and tipo_bloque = 'PARCIAL' 
                    order by b.orden ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function actionCalificarparcial(){
        if(isset($_GET['parcial'])){
        
        $bloqueId = $_GET['parcial'];        
        $alumnoId = $_GET['alumnoId'];
        
        if(isset($_GET['grupo_id'])){
            $grupoId = $_GET['grupo_id'];
        }else{
            $periodoId = Yii::$app->user->identity->periodo_id;
            $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
            $grupoId = $this->busca_clase_de_comportamiento($alumnoId, $modelPeriodo->codigo);
            
            //crea el nuevo registro si no existe
            $model = new \backend\models\ScholarisCalificacionesParcial();
            $model->bloque_id = $bloqueId;
            $model->grupo_id = $grupoId;
            $model->codigo_que_califica = 'padre';
            $model->quien_califica = 'padre';
            $model->tipo_calificacion = 'covid2019';
            $model->clase_usada = 'covid2019';
            $model->save();            
        }
        
        $modelAlumno = \backend\models\ScholarisGrupoAlumnoClase::findOne($grupoId);
        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::findOne($grupoId);
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($modelGrupo->clase->paralelo_id);
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($bloqueId);
        $modelRubrica = \backend\models\ScholarisRubricasCalificaciones::find()
                    ->where(['quien_aplica' => 'padre'])
                    ->orderBy(['valor' => SORT_DESC])
                    ->all();
        
        
         return $this->render('calificarparcial',[ 
                'modelAlumno' => $modelAlumno,
                'modelParalelo' => $modelParalelo,
                'modelBloque' => $modelBloque,
                'modelRubrica' => $modelRubrica
            ]);
        
        }
        
        if($_POST){
            
            $rubrica = $_POST['rubrica_calificacion'];
            $grupoId = $_POST['grupoId'];
            $bloqueId = $_POST['bloqueId'];
            
            $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::findOne($grupoId);
            
            $modelRubrica = \backend\models\ScholarisRubricasCalificaciones::findOne($rubrica);
            
            $model = \backend\models\ScholarisCalificacionesParcial::find()->where([
                'bloque_id' => $bloqueId,
                'grupo_id' => $grupoId,
                'codigo_que_califica' => 'padre'
            ])->one();

            $model->nota = $modelRubrica->valor;
            
            $model->save();
            
            return $this->redirect(['calificacionpadre',
                'id' => $modelGrupo->estudiante_id,
                'paralelo' => $modelGrupo->clase->paralelo_id
            ]);
            
        }
        
        
       
        
        
    }
    
    private function busca_clase_de_comportamiento($alumnoId, $periodoCodigo){
        $con = Yii::$app->db;
        $query = "select 	gru.id 
from 	scholaris_grupo_alumno_clase gru 
		inner join scholaris_clase cla on cla.id = gru.clase_id 
		inner join scholaris_malla_materia mal on mal.id = cla.malla_materia 
where	gru.estudiante_id = $alumnoId
		and cla.periodo_scholaris = '$periodoCodigo'		
		and mal.tipo = 'COMPORTAMIENTO';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res['id'];
    }

//    public function actionCalificacionpadre() {  NO BORRAR PORQUE ES DE LA CALIFICACION QUIMESTRAL
//
//        if (isset($_GET['id'])) {
//            $alumno = $_GET['id'];
//            $paralelo = $_GET['paralelo'];
//
//            $modelAlumno = \backend\models\OpStudent::findOne($alumno);
//            $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
//            $modelInscription = \backend\models\OpStudentInscription::find()->where([
//                        'student_id' => $alumno,
//                        'parallel_id' => $paralelo
//                    ])->one();
//
//            $tipoCalificacion = $this->califica_padre($alumno, $paralelo, $modelInscription->id);
//
//            $modelRubrica = \backend\models\ScholarisRubricasCalificaciones::find()
//                    ->where(['quien_aplica' => 'padre'])
//                    ->orderBy(['valor' => SORT_DESC])
//                    ->all();
//
//            return $this->render('calificacionpadre', [
//                        'modelAlumno' => $modelAlumno,
//                        'modelParalelo' => $modelParalelo,
//                        'tipoCalificacion' => $tipoCalificacion,
//                        'modelRubrica' => $modelRubrica
//            ]);
//        }
//
//
//
//        if ($_POST) {
//
//            $sentenciasCovid = new \backend\models\SentenciasCovid19();
//
//            $alumno = $_POST['id'];
//            $paralelo = $_POST['paralelo'];
//            $calificacion = $_POST['rubrica_calificacion'];
//            $tipoQuimestre = $_POST['quimestre'];
//
//            $modelInscription = \backend\models\OpStudentInscription::find()->where(['student_id' => $alumno, 'parallel_id' => $paralelo])->one();
//
//            $model = new \backend\models\ScholarisQuimestreCalificacion();
//            $model->inscription_id = $modelInscription->id;
//            $model->quimestre_calificacion_id = $tipoQuimestre;
//            $model->rubrica_id = $calificacion;
//            $model->save();
//
//            //$sentenciasCovid->calcula_total_quimestre($modelInscription->id, $tipoQuimestre);
//            $modelCalificaCovid = \backend\models\ScholarisCalificacionCovid19::find()->where([
//                        'inscription_id' => $modelInscription->id,
//                        'tipo_quimestre_id' => $tipoQuimestre
//                    ])->one();
//
//            if ($modelCalificaCovid) {
//                $modelCalificaCovid->padre = $model->rubrica->valor;
//                $modelCalificaCovid->save();
//                $sentenciasCovid->calcula_total_quimestre($modelInscription->id, $tipoQuimestre);
//            }
//
//            return $this->redirect(['calificacionpadre', 'id' => $alumno, 'paralelo' => $paralelo]);
//        }
//    }

    private function califica_padre($alumno, $paralelo, $inscriptionId) {
        $con = Yii::$app->db;
        $query = "select 	t.id
		,q.nombre
                                    ,t.id as tipo_quimestre_id
		,r.valor
		,r.descripcion
from	scholaris_quimestre_tipo_calificacion t
inner join scholaris_quimestre q on q.id = t.quimestre_id
		left join scholaris_quimestre_calificacion c on c.quimestre_calificacion_id = t.id
                                    and c.inscription_id = $inscriptionId
		left join scholaris_rubricas_calificaciones r on r.id = c.rubrica_id
		left join op_student_inscription i on i.id = c.inscription_id
					and i.student_id = $alumno
					and i.parallel_id = $paralelo
where	t.codigo = 'covid19'
order by q.orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionReportepai() {
        $reporte = new \backend\models\InformePai();

        //print_r($_GET);
        $alumno = $_GET['alumno'];
        $quimestre = $_GET['quimestre'];
        $paralelo = $_GET['paralelo'];

        $reporte->genera_reporte($alumno, $quimestre, $paralelo);
    }

    public function actionReportetotal() {
        $reporte = new \backend\models\InformeAprendizajeTotal();

        //print_r($_GET);
        $alumno = $_GET['alumno'];
        $paralelo = $_GET['paralelo'];

        $reporte->genera_reporte($paralelo, $alumno);
    }

    public function actionReportetotal2() {
        $reporte = new \backend\models\InformeAprendizajeTotal2();

        //print_r($_GET);
        $alumno = $_GET['alumno'];
        $paralelo = $_GET['paralelo'];

        $reporte->genera_reporte($paralelo, $alumno);
    }
    
    
    public function actionReporteresumen() {

        //print_r($_GET);
        $alumno = $_GET['alumno'];
        $paralelo = $_GET['paralelo'];
        
        $reporte = new \backend\models\InfLibretaResumenFinal($paralelo, $alumno, 'q2');
        //$reporte->genera_reporte_alumno($alumno, 'q2', $paralelo);
        
    }
    
    
    
    
    public function actionCalificarparcialdisciplinar(){
        $alumno = $_GET['alumnoId'];
        $paralelo = $_GET['paraleloId'];
        $bloque = $_GET['bloqueId'];
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelAlumno = \backend\models\OpStudent::findOne($alumno);
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($bloque);
        
        $this->ingresa_notas_vacias($alumno, $modelPeriodo->codigo, $bloque, $modelBloque->codigo_tipo_calificacion);
        
        $modelNotas = $this->toma_notas_disciplinar($bloque, $alumno, $modelPeriodo->codigo);
        
        return $this->render('calificaciondisciplinarnotas',[
            'modelAlumno' => $modelAlumno,
            'modelParalelo' => $modelParalelo,
            'modelBloque' => $modelBloque,
            'modelNotas' => $modelNotas
        ]);
        
    }
    
    private function toma_notas_disciplinar($bloque, $alumno, $periodoCodigo){
        $con = \Yii::$app->db;
        $query = "select 	c.id as clase_id 
		,m.name as materia
		,p.nota 
                ,g.id as grupo_id
from	scholaris_grupo_alumno_clase g
		inner join op_student s on s.id = g.estudiante_id 
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
		left join scholaris_calificaciones_parcial p on p.grupo_id = g.id
			 and p.bloque_id = $bloque 
                         and p.quien_califica = 'padre'
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodoCodigo'
		and mm.tipo in ('NORMAL','OPTATIVAS')
order by m.name;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function ingresa_notas_vacias($alumno, $periodoCodigo, $bloque, $tipoBloqueCalificacion){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_calificaciones_parcial (bloque_id, grupo_id, codigo_que_califica, quien_califica, tipo_calificacion, clase_usada)
                    select 	$bloque, g.id as grupo_id 
                                    ,'padre', 'padre','$tipoBloqueCalificacion','$tipoBloqueCalificacion'
                    from	scholaris_grupo_alumno_clase g
                                    inner join op_student s on s.id = g.estudiante_id 
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_materia m on m.id = c.idmateria 
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                    where 	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$periodoCodigo'
                                    and mm.tipo in ('NORMAL','OPTATIVAS')
                                    and g.id not in (select grupo_id from scholaris_calificaciones_parcial where grupo_id = g.id and bloque_id = $bloque)";
        $con->createCommand($query)->execute();
        
    }
    
    public function actionCambiaNotaAjaxPadre(){
              
        $nota = $_POST['nota'];
        $bloqueId = $_POST['bloqueId'];
        $grupoId = $_POST['grupoId'];
        
        $model = \backend\models\ScholarisCalificacionesParcial::find()->where([
            'bloque_id' => $bloqueId,
            'grupo_id' => $grupoId,
            'codigo_que_califica' => 'padre',
        ])->one();
        
//      echo '<pre>';  
//print_r($model);
//die();
        
        $model->nota = $nota;
        
        $model->save();
    }

}
