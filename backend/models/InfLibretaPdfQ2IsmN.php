<?php

namespace backend\models;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\OpStudent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class InfLibretaPdfQ2IsmN extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $modelParalelo;
    private $quimestre;
    private $periodoId;
    private $periodoCodigo;
    private $tieneProyectos = 0;
    private $modelAlumnos;
    private $usuario;
    private $mallaId;
    private $modelBloquesQ1;
    private $modelBloquesEx1;
    private $modelBloquesQ2;
    private $modelBloquesEx2;
    private $seccion;
    private $observacion;
    private $totalDias = 0;
    private $comportamientoAutomatico = 0;
    private $tipoCalificacionProyectos = 'PROYECTOSNORMAL';
    private $tipoCalificacion;

    public function __construct($paralelo, $alumno, $quimestre) {

        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        
        /**
         * para tomar tipo de calificacion
         */
        $modelTipoCalificacion = ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->valor;
        //////// fin de tipo de calificacion /////////////

        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $this->modelParalelo->course->section0->code;

        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->mallaId = $modelMalla->malla_id;

        $this->quimestre = $quimestre;
        $this->paralelo = $paralelo;

        $this->tieneProyectos = $this->tiene_proyectos(); //llama a funcion para buscar si tiene proyectos

        $this->usuario = Yii::$app->user->identity->usuario;

        if (!$alumno > 0 || !$alumno != '') {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        } else {
            //echo 'aqui'.$alumno;
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }

        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;


        $modelComportamientoParam = ScholarisParametrosOpciones::find()->where(['codigo' => 'comportamiento'])->one();
        $this->comportamientoAutomatico = $modelComportamientoParam->valor;

        /*         * ********** para ver tipo de proyectos ******** */
        $modelTipoProyectos = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->tipoCalificacionProyectos = $modelTipoProyectos->tipo_proyectos;
        /////////////////////////////////////////////////////////////////////////////

        $this->modelBloquesQ1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();

        $this->modelBloquesEx1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'EXAMEN'
                ])->orderBy('orden')
                ->one();
        
        $this->modelBloquesQ2 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE II',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();

        $this->modelBloquesEx2 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE II',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'EXAMEN'
                ])->orderBy('orden')
                ->one();

        $diasExamen1 = $this->modelBloquesEx1->dias_laborados;
        $diasExamen2 = $this->modelBloquesEx2->dias_laborados;

        $totDiasQ1 = 0;
        foreach ($this->modelBloquesQ1 as $q1) {
            $totDiasQ1 = $totDiasQ1 + $q1->dias_laborados;
        }

        $totDiasQ2 = 0;
        foreach ($this->modelBloquesQ2 as $q2) {
            $totDiasQ2 = $totDiasQ2 + $q2->dias_laborados;
        }
        
        
        $this->totalDias = $totDiasQ1 + $totDiasQ2 + $diasExamen1 + $diasExamen2;
        $this->genera_reporte_pdf();
    }

    private function tiene_proyectos() {
        $con = Yii::$app->db;
        $query = "select 	count(i.id) as total
from 	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
where	i.parallel_id = $this->paralelo
		and c.periodo_scholaris = '$this->periodoCodigo'
		and mm.tipo = 'PROYECTOS';";
        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }

    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 3,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera();
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;



        foreach ($this->modelAlumnos as $data) {
            $html = $this->genera_cuerpo($data);
            $mpdf->WriteHTML($html);
            $mpdf->addPage();
        }

        //$mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="10%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';

        $html .= '<td class="centrarTexto">';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo . '<br>';
        $html .= '<strong>REPORTE SEGUNDO QUIMESTRE</strong><br>';
        $html .= '</td>';

        $html .= '<td align="right" width="10%">';

        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
//        $html .= '<hr>';

        return $html;
    }

    private function genera_cuerpo($arregloAlumno) {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid #000;}';
        $html .= '.tamano8{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

        $html .= '<table class="tamano8" width="100%">';
        $html .= '<tr>';
        $html .= '<td><strong>ESTUDIANTE: </strong>' . $arregloAlumno['last_name'] . ' ' . $arregloAlumno['first_name'] . ' ' . $arregloAlumno['middle_name'] . '</td>';
        $html .= '<td align="right"><strong>CURSO: </strong>' . $modelParalelo->course->name . '"' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->procesa_asignaturas($arregloAlumno['id']);
        $html .= $this->procesa_faltas_atrasos($arregloAlumno['id']);
        $html .= $this->escalas();
        $html .= $this->observaciones();
        $html .= $this->firmas();



        return $html;
    }

    private function firmas() {
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = OpInstitute::findOne($institutoId);

        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';


        $firma = 0;

        $modelFirmas = ScholarisParametrosOpciones::find()->where(['codigo' => 'firmalib'])->one();

        if ($modelFirmas) {
            $firma = $modelFirmas->valor;
        }

        if ($firma == 1) {
            $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
            $html .= '<tr>';
            $html .= '<td width="40%" class="centrarTexto">_______________________________</td>';
//            $html .= '<td width="20%" class=""></td>';
//            $html .= '<td width="40%" class="centrarTexto">_______________________________</td>';
            $html .= '</tr>';

            $html .= '<tr>';
//            $html .= '<td width="40%" class="centrarTexto">COORDINADOR(A)</td>';
//            $html .= '<td width="20%" class=""></td>';
            $html .= '<td width="40%" class="centrarTexto">TUTOR(A)</td>';
            $html .= '</tr>';
            $html .= '</table>';
        } elseif ($firma == 2) {

            $modelTutor = ScholarisClase::find()
                    ->innerJoin("scholaris_malla_materia mm", "mm.id = scholaris_clase.malla_materia")
                    ->where(['paralelo_id' => $this->paralelo, 'mm.tipo' => 'COMPORTAMIENTO'])
                    ->one();
            $modelCoordinador = ScholarisCoordinadores::find()->where(['course_id' => $this->modelParalelo->course->id])->one();

            $html .= '<table width="100%" height="300" cellpadding="0" cellspacing="0" class="tamano8">';
//            $html .= '<tr>';
//            $html .= '<td width="45%" class="centrarTexto"><strong>_________________________________________</strong></td>';
//            $html .= '<td width="10%" class=""></td>';
//            $html .= '<td width="45%" class="centrarTexto"><strong>_________________________________________</strong></td>';
//            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelCoordinador->titulo . ' ' . $modelCoordinador->nombre . '</strong></td>';
            $html .= '<td width="10%" class=""></td>';
            $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelTutor->profesor->x_first_name . ' ' . $modelTutor->profesor->last_name . '</strong></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td width="45%" class="centrarTexto"><strong>COORDINADOR(A)</strong></td>';
            $html .= '<td width="10%" class=""></td>';
            $html .= '<td width="45%" class="centrarTexto"><strong>TUTOR(A)</strong></td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '';

            $html .= '<div class="centrarTexto"><img src="imagenes/instituto/logo/sellolibreta.png" width="100px"></div>';
        } else {
            $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
            $html .= '<tr>';
            $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
//        $html .= '<td width="34%" class=""></td>';
//        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td width="33%" class="centrarTexto">Tutor (a)</td>';
//        $html .= '<td width="34%" class=""></td>';
//        $html .= '<td width="33%" class="centrarTexto">TUTORÍA</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }



        return $html;
    }

    private function observaciones() {
        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td><strong>OBSERVACIONES: </strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="" bgcolor="#eaeaea">' . $this->observacion . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function escalas() {

        $html = '';
        $html .= '<br>';



        $html .= '<table class="tamano8" width="100%" cellspacing="4" cellpadding="4">';
        $html .= '<tr>';
        $html .= '<td valign="top" class=" bordesolido"><strong>EQUIVALENCIA DE APROVECHAMIENTO</strong><br>';
        $aprovechamiento = $this->escalas_aprovechamiento();
        foreach ($aprovechamiento as $aprov) {
            $html .= '<strong>' . $aprov['abreviatura'] . ' de ' . $aprov['rango_minimo'] . ' a ' . $aprov['rango_maximo'] . '</strong><br>';
            $html .= $aprov['descripcion'] . '<br>';
        }

        $html .= '</td>';

        $html .= '<td valign="top" class=" bordesolido"><strong>EQUIVALENCIA DE PROYECTOS</strong><br>';
        $aprovechamiento = $this->escalas_proyectos();
        foreach ($aprovechamiento as $aprov) {
            $html .= '<strong>' . $aprov['abreviatura'] . ' de ' . $aprov['rango_minimo'] . ' a ' . $aprov['rango_maximo'] . '</strong><br>';
            $html .= $aprov['descripcion'] . '<br>';
        }

        $html .= '</td>';

        $html .= '<td valign="top" class=" bordesolido"><strong>EQUIVALENCIA DE COMPORTAMIENTO</strong><br>';
        $comportamiento = $this->escalas_comportamiento();
        foreach ($comportamiento as $aprov) {
            $html .= '<strong>' . $aprov['abreviatura'] . ' de ' . $aprov['rango_minimo'] . ' a ' . $aprov['rango_maximo'] . '</strong><br>';
            $html .= $aprov['descripcion'] . '<br>';
        }
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function escalas_aprovechamiento() {
        $con = Yii::$app->db;
        $query = "select 	 abreviatura 
                                    ,descripcion
                                    ,rango_minimo
                                    ,rango_maximo
                    from 	scholaris_tabla_escalas_homologacion
                    where 	scholaris_periodo = '$this->periodoCodigo'
                                    and corresponde_a = 'APROVECHAMIENTO'
                    order by rango_maximo desc;";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function escalas_proyectos() {
        $con = Yii::$app->db;
        $query = "select 	 abreviatura 
                                    ,descripcion
                                    ,rango_minimo
                                    ,rango_maximo
                    from 	scholaris_tabla_escalas_homologacion
                    where 	scholaris_periodo = '$this->periodoCodigo'
                                    and corresponde_a = 'PROYECTOS'
                    order by rango_maximo desc;";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function escalas_comportamiento() {
        $con = Yii::$app->db;
        $query = "select 	 abreviatura
                                 ,rango_minimo
                                 ,rango_maximo
                                            ,descripcion 
                            from 	scholaris_tabla_escalas_homologacion
                            where 	scholaris_periodo = '$this->periodoCodigo'
                                            and section_codigo = '$this->seccion'
                                            and corresponde_a = 'COMPORTAMIENTO'
                            order by abreviatura;";

        $res = $con->createCommand($query)->queryAll();



        return $res;
    }

    private function procesa_faltas_atrasos($alumnoId) {
        $html = '';

        $sentencias = new \backend\models\SentenciasFaltas();

        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto tamano8 bordesolido" rowspan="2"><strong>ASISTENCIA</strong></td>';



        $sumaAtrasos1 = 0;
        $sumaJustificadas1 = 0;
        $sumaInjustificadas1 = 0;
        foreach ($this->modelBloquesQ1 as $q1) {
            $novedades1 = $sentencias->get_novedad($alumnoId, $q1->id);
//            print_r($novedades);
//            die();
            $sumaAtrasos1 = $sumaAtrasos1 + $novedades1[9];
            $sumaJustificadas1 = $sumaJustificadas1 + $novedades1[10];
            $sumaInjustificadas1 = $sumaInjustificadas1 + $novedades1[11];
        }
        
        $sumaAtrasos2 = 0;
        $sumaJustificadas2 = 0;
        $sumaInjustificadas2 = 0;
        foreach ($this->modelBloquesQ2 as $q2) {
            $novedades2 = $sentencias->get_novedad($alumnoId, $q2->id);
//            print_r($novedades);
//            die();
            $sumaAtrasos2 = $sumaAtrasos2 + $novedades2[9];
            $sumaJustificadas2 = $sumaJustificadas2 + $novedades2[10];
            $sumaInjustificadas2 = $sumaInjustificadas2 + $novedades2[11];
        }

        $this->observacion = $novedades2[12];


        $sumaAtrasos        = $sumaAtrasos1 + $sumaAtrasos2;
        $sumaJustificadas   = $sumaJustificadas1 + $sumaJustificadas2;
        $sumaInjustificadas = $sumaInjustificadas1 + $sumaInjustificadas2;
        
        
        
        $html .= '<td class="bordesolido centrarTexto tamano8">Atrasos</td>';
        $html .= '<td class="bordesolido centrarTexto tamano8">Faltas Justificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano8">Faltas Injustificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano8">Presente</td>';
        $html .= '</tr>';
        $html .= '<tr>';

        $html .= '<td class="bordesolido centrarTexto tamano8">' . $sumaAtrasos . '</td>';
        $html .= '<td class="bordesolido centrarTexto tamano8">' . $sumaJustificadas . '</td>';
        $html .= '<td class="bordesolido centrarTexto tamano8">' . $sumaInjustificadas . '</td>';


        $presente = $this->totalDias - ($sumaJustificadas + $sumaInjustificadas);
        $html .= '<td class="bordesolido centrarTexto tamano8">' . $presente . '</td>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function procesa_asignaturas($alumnoId) {

//        $sentenciasNotasAlumnos = new NotasAlumnos($this->paralelo, $this->quimestre, $alumnoId);
        
        $sentenciasProcesa = new ProcesaNotas();

        $areas = $sentenciasProcesa->get_areas($alumnoId, $this->tipoCalificacion, $this->paralelo, $this->usuario, $this->mallaId);

        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto"><strong>MATERIA</strong></td>';

        foreach ($this->modelBloquesQ1 as $bloq1) {
            $html .= '<td class="bordesolido centrarTexto">' . $bloq1->abreviatura . '</td>';
        }

        $html .= '<td class="bordesolido centrarTexto">PR</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>80%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto">EXAMEN</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>20%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" bgcolor=""><strong>PROMEDIO</strong></td>';
        
        foreach ($this->modelBloquesQ2 as $bloq2) {
            $html .= '<td class="bordesolido centrarTexto">' . $bloq2->abreviatura . '</td>';
        }
        
        $html .= '<td class="bordesolido centrarTexto">PR</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>80%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto">EXAMEN</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>20%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" bgcolor=""><strong>PROMEDIO</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" bgcolor=""><strong>FINAL</strong></td>';

        
        $html .= '<td class="bordesolido centrarTexto" bgcolor=""><strong>OBSERVACIONES</strong></td>';

        $html .= '</tr>';

        foreach ($areas as $ar) {

            if ($ar['promedia'] == 1) {
                $asterisco = '';
            } else {
                $asterisco = '   * ';
            }

            if ($ar['se_imprime'] == true) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido"><strong>' . $asterisco . $ar['area'] . '</strong></td>';
                //$notasArea = $sentenciasProcesa->busca_nota_area($alumnoId, $ar['id']);
                $notasArea = $sentenciasProcesa->busca_nota_area($alumnoId, $ar['id'], $this->tipoCalificacion, $this->paralelo, $this->usuario);
                
                if ($ar['promedia'] == 1) {                    
                    $html .= '<td class="bordesolido centrarTexto tamano8">' . $notasArea['p1'] . '</td>';                        
                    $html .= '<td class="bordesolido centrarTexto tamano8">' . $notasArea['p2'] . '</td>';                     

                    if (count($this->modelBloquesQ1) > 2) {
                                $html .= '<td class="bordesolido centrarTexto tamano8">' . $notasArea['p3'] . '</td>';
                    }
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['pr1'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['pr180'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['ex1'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['ex120'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['q1'] . '</strong></td>';
                    
                    
                    //quimestre 2
                    $html .= '<td class="bordesolido centrarTexto tamano8">' . $notasArea['p4'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8">' . $notasArea['p5'] . '</td>';
                    if (count($this->modelBloquesQ2) > 2) {
                        $html .= '<td class="bordesolido centrarTexto tamano8">' . $notasArea['p6'] . '</td>';
                    }
                    
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['pr2'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['pr280'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['ex2'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['ex220'] . '</strong></td>';
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['q2'] . '</strong></td>';
                    
                    $html .= '<td class="bordesolido centrarTexto tamano8"><strong>' . $notasArea['final_ano_normal'] . '</strong></td>';
                    
                    
                    $equivalencia = $this->homologa_aprovechamiento($notasArea['final_ano_normal']);
                    $html .= '<td class="bordesolido tamano8 centrarTexto">' . $equivalencia . '</td>';

                } else {
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">--</td>';
                }


                $html .= '</tr>';
            }

            $materias = $sentenciasProcesa->get_materias_x_area($alumnoId, $ar['id'], $this->periodoCodigo);

            foreach ($materias as $mat) {

                if ($mat['promedia'] == 1) {
                    $asterisco = '';
                } else {
                    $asterisco = '   * ';
                }

                $html .= '<tr>';
                $html .= '<td class="bordesolido tamano8">' . $asterisco . $mat['materia'] . '</td>';
                $notasM = $sentenciasProcesa->get_nota_materia($alumnoId, $mat['materia_id'], $this->tipoCalificacion, $mat['grupo_id']);
                
                
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['p1'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['p2'] . '</td>';

                if (count($this->modelBloquesQ1) > 2) {
                    $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['p3'] . '</td>';
                }
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['pr1'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['pr180'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['ex1'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['ex120'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['q1'] . '</td>';
                
                
                ////    quimestre 2
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['p4'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['p5'] . '</td>';
                if (count($this->modelBloquesQ2) > 2) {
                    $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['p6'] . '</td>';
                }
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['pr2'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['pr280'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['ex2'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['ex220'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['q2'] . '</td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $notasM['final_ano_normal'] . '</td>';



                $equivalencia = $this->homologa_aprovechamiento($notasM['final_ano_normal']);
                $html .= '<td class="bordesolido tamano8 centrarTexto">' . $equivalencia . '</td>';

                $html .= '</tr>';
            }
        }

        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano8"><strong>PROMEDIOS: </strong></td>';
        
        $notaF = $sentenciasProcesa->consulta_notas_finales($alumnoId, $this->tipoCalificacion, $this->paralelo, $this->usuario);

        
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['p1'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['p2'] . '</strong></td>';
        if (count($this->modelBloquesQ1) > 2) {
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['p3'] . '</strong></td>';
        }
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['pr1'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['pr180'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['ex1'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['ex120'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['q1'] . '</strong></td>';
        
        
        ///quimstre 2
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['p4'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['p5'] . '</strong></td>';
        if (count($this->modelBloquesQ2) > 2) {
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['p6'] . '</strong></td>';
        }
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['pr2'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['pr280'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['ex2'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['ex220'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['q2'] . '</strong></td>';
        $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notaF['final_ano_normal'] . '</strong></td>';
        
        
        $equivalencia = $this->homologa_aprovechamiento($notaF['final_ano_normal']);
        $html .= '<td class="bordesolido tamano8 centrarTexto">' . $equivalencia . '</td>';

        $html .= '</tr>';
        


        $notasCompProy = $this->consulta_comportamientos_y_proyectos($alumnoId);
        if ($this->tieneProyectos > 0) {

            $proyectos = new ComportamientoProyectos($alumnoId, $this->paralelo);
            


            if ($this->tipoCalificacionProyectos == 'PROYECTOSBLOQUE') {
                
                $area = $proyectos->get_area_proyectos_bloque();
            
                $html .= '<tr>';
                $html .= '<td class="bordesolido tamano8"><strong>PROYECTOS ESCOLARES: </strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['p1'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['p2'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['p3'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['pr1'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['ex1'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['q1'] . '</strong></td>';
                
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['p4'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['p5'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['p6'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['pr2'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['ex2'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['q2'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $area[0]['q2'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '</tr>';
                
                $materiasProy = $proyectos->consulta_materias_proyectos();
                
                foreach ($materiasProy as $proy){
                    $html .= '<tr>';
                        $html .= '<td class="bordesolido tamano8">'.$proy['materia'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['p1'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['p2'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['p3'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['pr1'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">-</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['ex1'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">-</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['q1'].'</td>';
                        
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['p4'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['p5'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['p6'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['pr2'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">-</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['ex2'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">-</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['q2'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">'.$proy['q2'].'</td>';
                        $html .= '<td class="bordesolido tamano8 centrarTexto">-</td>';
                    $html .= '</tr>';
                }
                
                
            } elseif ($this->tipoCalificacionProyectos == 'PROYECTOSNORMAL') {
                
                $html .= '<tr>';
                $html .= '<td class="bordesolido tamano8"><strong>PROYECTOS ESCOLARES: </strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['p1']['abreviatura'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['p2']['abreviatura'] . '</strong></td>';
                if (count($this->modelBloquesQ1) > 2) {
                    $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['p3']['abreviatura'] . '</strong></td>';
                }
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['q1']['abreviatura'] . '</strong></td>';
                
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['p4']['abreviatura'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['p5']['abreviatura'] . '</strong></td>';
                if (count($this->modelBloquesQ1) > 2) {
                    $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['p6']['abreviatura'] . '</strong></td>';
                }
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['q2']['abreviatura'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $proyectos->arrayNotasProy[0]['q2']['abreviatura'] . '</strong></td>';
                $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
                $html .= '</tr>';
            }
            
        }

        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano8"><strong>COMPORTAMIENTO: </strong></td>';


        ////COMPORTAMIENTO
        /////
        /////

        if ($this->comportamientoAutomatico == 0) {



            $compoP1 = $this->consulta_comportamientos_parciales($alumnoId, 1);
            $compoP2 = $this->consulta_comportamientos_parciales($alumnoId, 2);

            if (count($this->modelBloquesQ1) > 2) {
                $compoP3 = $this->consulta_comportamientos_parciales($alumnoId, 3);
            }


            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compoP1 . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compoP2 . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $notasCompProy['comportamiento_notaq1'] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
        } else {
            $comportamiento = new SentenciasRepLibreta2();

            $compo = $comportamiento->get_notas_finales_comportamiento($alumnoId);
//            echo '<pre>';
//            print_r($compo);
//            die();
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[0] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[1] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[2] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[2] . '</strong></td>';
            
            //quimestre 2
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[3] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[4] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[5] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[5] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>' . $compo[5] . '</strong></td>';
            $html .= '<td class="bordesolido tamano8 centrarTexto"><strong>-</strong></td>';
        }

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function homologa_aprovechamiento($nota) {
        
        isset($nota) ? $nota = $nota : $nota = 0;
        
        $con = Yii::$app->db;
        $query = "select 	abreviatura 
from 	scholaris_tabla_escalas_homologacion
where 	$nota between rango_minimo and rango_maximo
		and scholaris_periodo = '$this->periodoCodigo'
		and corresponde_a = 'APROVECHAMIENTO';";
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['abreviatura']) ? $nota = $res['abreviatura'] : $nota = '-';
        
        return $nota;
    }

    private function devuelve_nota_promedio($arrayNotaF, $campo) {

        $html = '';
        foreach ($arrayNotaF as $nf) {
            if ($nf['bloque'] == $campo) {
                $html .= '<td class="bordesolido centrarTexto"><strong>' . $nf['nota'] . '</strong></td>';
                if ($campo == 'q1') {
                    $equivalencia = $this->homologa_aprovechamiento($nf['nota']);
                    $html .= '<td class="bordesolido centrarTexto"><strong>' . $equivalencia . '</strong></td>';
                }
            }
        }
        return $html;
    }

    private function consulta_comportamientos_parciales($alumnoId, $orden) {

        $sentencias = new Notas();

        $modelInscription = OpStudentInscription::find()->where([
                    'student_id' => $alumnoId,
                    'parallel_id' => $this->paralelo
                ])->one();

        $con = Yii::$app->db;
        $query = "select 	calificacion 
from 	scholaris_califica_comportamiento c
		inner join scholaris_bloque_actividad b on b.id = c.bloque_id
where	c.inscription_id = $modelInscription->id
		and b.orden = $orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        $nota = $sentencias->homologa_comportamiento($res['calificacion'], $this->seccion);

        return $nota;
    }

    private function consulta_comportamientos_y_proyectos($alumnoId) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, comportamiento_notaq1, comportamiento_notaq2, proyectos_notaq1, proyectos_notaq2 
from 	scholaris_proceso_comportamiento_y_proyectos
where	paralelo_id = $this->paralelo
		and alumno_id = $alumnoId
                and usuario = '$this->usuario';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }


    private function encuentra_nota_materia($campoParcial, $notasM) {
        $respuesta = 0;
        $i = 0;
        foreach ($notasM as $nota) {
            if ($nota['bloque'] == $campoParcial) {
                $respuesta = $nota['nota'];
            }
        }

        return $respuesta;
    }

    

    private function get_materias_x_area($alumnoId, $areaId) {
        $con = \Yii::$app->db;
        $query = "select 	c.id as clase_id 
		,c.idmateria as materia_id
		,m.name as materia
		,mm.promedia 
		,mm.se_imprime 
		,mm.total_porcentaje 
from 	scholaris_clase c
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
		inner join scholaris_materia m on m.id = c.idmateria 
where 	ma.area_id = $areaId
		and g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$this->periodoCodigo';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

//    private function busca_nota_area($alumnoId, $areaId) {
//        $con = \Yii::$app->db;
//        $query = "select 	usuario, paralelo_id, alumno_id, area_id, porcentaje, promedia, imprime, bloque, nota 
//                    from 	scholaris_proceso_areas
//                    where 	alumno_id = $alumnoId
//                                    and paralelo_id = $this->paralelo
//                                    and usuario = '$this->usuario'
//                                    and area_id = $areaId;";
//        $res = $con->createCommand($query)->queryAll();
//        return $res;
//    }

//    private function get_areas($alumnoId) {
//        $con = \Yii::$app->db;
//        $query = "select 	a.id 
//                                ,a.name as area 
//                                ,pa.promedia 
//                                ,pa.imprime 
//                from 	scholaris_proceso_areas pa
//                                inner join scholaris_area a on a.id = pa.area_id
//                                inner join scholaris_malla_area ma on ma.area_id = a.id 
//                where	alumno_id = $alumnoId
//                                and usuario = '$this->usuario'
//                                and paralelo_id = $this->paralelo
//                                and ma.malla_id = $this->mallaId
//                group by a.id,a.name, ma.orden, pa.promedia 
//                                ,pa.imprime
//                order by ma.orden;";
//        $res = $con->createCommand($query)->queryAll();
//        return $res;
//    }

}
