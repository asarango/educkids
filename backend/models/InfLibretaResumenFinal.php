<?php

namespace backend\models;

use Yii;
use yii\helpers\Html;
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
class InfLibretaResumenFinal extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $modelParalelo;
    private $quimestre;
    private $periodoId;
    private $periodoCodigo;
    private $modelAlumnos;
    private $usuario;
    private $mallaId;
    private $modelBloquesQ1;
    private $modelBloquesQ2;
    private $modelBloquesEx1;
    private $seccion;
    private $observacion;
    private $totalDias = 0;
    private $tituloQuimestre;
    private $tipoCalificacion;
    private $tieneProyectos;

    public function __construct($paralelo, $alumno, $quimestre) {

        if (!isset(\Yii::$app->user->identity->periodo_id)) {
            echo 'Su sesión expiró!!!';
            echo Html::a('Iniciar Sesión', ['/site/index']);
            die();
        }

        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;

        $sentencias = new SentenciasAlumnos();
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->modelParalelo = $modelParalelo;
        $this->seccion = $modelParalelo->course->section0->code;
        
        //// verifica si tiene proyectos
        $this->tieneProyectos = $this->tiene_proyectos_escolares();

        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();
        $this->mallaId = $modelMalla->malla_id;

        $this->quimestre = $quimestre;
        $this->paralelo = $paralelo;

        /** tipo de calificacion * */
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        ///// fin de tippo de calificacion /////

        $this->titulo_libreta();

        $this->usuario = Yii::$app->user->identity->usuario;

        if (!$alumno > 0 || !$alumno != '') {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        } else {
            //echo 'aqui'.$alumno;
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }

        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;


        $this->modelBloquesQ1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();

        $this->modelBloquesQ2 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE II',
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

        $diasExamen = $this->modelBloquesEx1->dias_laborados;

        $totalDiasQ1 = 0;
        $totalDiasQ2 = 0;

        foreach ($this->modelBloquesQ1 as $q1) {
            $totalDiasQ1 = $totalDiasQ1 + $q1->dias_laborados;
        }

        foreach ($this->modelBloquesQ2 as $q2) {
            $totalDiasQ2 = $totalDiasQ2 + $q2->dias_laborados;
        }

        if ($this->quimestre == 'q1') {
            $this->totalDias = $totalDiasQ1;
        } else {
            $this->totalDias = $totalDiasQ1 + $totalDiasQ2;
        }
        
        $this->totalDias = $this->totalDias + $diasExamen;
        $this->genera_reporte_pdf();
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

    private function titulo_libreta() {
        if ($this->quimestre == 'q1') {
            $this->tituloQuimestre = 'PRIMER QUIMESTRE';
        } elseif ($this->quimestre == 'q2') {
            $this->tituloQuimestre = 'INFORME FINAL DE CALIFICACIONES';
        }
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/logo/logo2.png" width="80px" width="10%"></td>';
        $html .= '<td class="centrarTexto">';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo . '<br>';
        $html .= '<strong>INFORME DE APRENDIZAJE Y COMPORTAMENTAL</strong><br>';
        $html .= '<strong>' . $this->tituloQuimestre . '</strong>';
        $html .= '</td>';
        $html .= '<td align="right" width="10%">';

        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function genera_cuerpo($arregloAlumno) {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid #000;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

        $html .= '<table class="tamano10" width="100%">';
        $html .= '<tr>';
        $html .= '<td><strong>ESTUDIANTE: </strong>' . $arregloAlumno['last_name'] . ' ' . $arregloAlumno['first_name'] . ' ' . $arregloAlumno['middle_name'] . '</td>';
        $html .= '<td align="right"><strong>CURSO: </strong>' . $modelParalelo->course->name . '"' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->procesa_asignaturas($arregloAlumno['id']);
        //$html .= $this->procesa_faltas_atrasos($arregloAlumno['id']);
        $html .= $this->escalas();
//        $html .= $this->observaciones();
        $html .= $this->firmas();

        return $html;
    }
    
    /***
     * Metodo parta verificar si tiene proyectos escolares
     */
    private function tiene_proyectos_escolares(){
        $cursoId = $this->modelParalelo->course_id;        
        $modelTieneProyectos = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $cursoId])->one();        
        return $modelTieneProyectos->tipo_proyectos;        
    } 

    private function firmas() {
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = OpInstitute::findOne($institutoId);

        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>_______________________________________</strong></td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor="">Tutor(a)</td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '</tr>';

        $html .= '</table>';

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

        $html .= '<table class="tamano8 bordesolido" width="100%" cellspacing="0" cellpadding="4">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto bordesolido"><strong>EQUIVALENCIA DE COMPORTAMIENTO</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESDE</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>HASTA</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESCRIPCIÓN</strong></td>';
        $html .= '</tr>';
        $comportamientos = $this->escalas_comportamiento();
        foreach ($comportamientos as $comp) {
            $html .= '<tr>';

            $html .= '<td class="centrarTexto bordesolido">' . $comp['abreviatura'] . '</td>';
            $html .= '<td class="centrarTexto bordesolido">' . $comp['rango_minimo'] . '</td>';
            $html .= '<td class="centrarTexto bordesolido">' . $comp['rango_maximo'] . '</td>';
            $html .= '<td class="bordesolido">' . $comp['descripcion'] . '</td>';

            $html .= '</tr>';
        }
        $html .= '</table>';


        $html .= '<br>';

        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="4">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto bordesolido"><strong>APROVECHAMIENTO</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>EQUIVALENCIA</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESDE</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>HASTA</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESCRIPCIÓN</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto bordesolido" rowspan="4">GENERAL</td>';
        $aprovechamiento = $this->escalas_aprovechamiento();

        $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[0]['abreviatura'] . '</td>';
        $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[0]['rango_minimo'] . '</td>';
        $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[0]['rango_maximo'] . '</td>';
        $html .= '<td class="bordesolido">' . $aprovechamiento[0]['descripcion'] . '</td>';
        $html .= '</tr>';

        for ($i = 1; $i < count($aprovechamiento); $i++) {
            $html .= '<tr>';
            $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[$i]['abreviatura'] . '</td>';
            $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[$i]['rango_minimo'] . '</td>';
            $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[$i]['rango_maximo'] . '</td>';
            $html .= '<td class="bordesolido">' . $aprovechamiento[$i]['descripcion'] . '</td>';
            $html .= '</tr>';
        }


        if ($this->seccion != 'BACHILLERATO') {
            $html .= '<tr>';
            $html .= '<td class="centrarTexto bordesolido" rowspan="4">PROYECTOS EDUCATIVOS</td>';
            $aprovechamiento = $this->escalas_proyectos();

            $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[0]['abreviatura'] . '</td>';
            $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[0]['rango_minimo'] . '</td>';
            $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[0]['rango_maximo'] . '</td>';
            $html .= '<td class="bordesolido">' . $aprovechamiento[0]['descripcion'] . '</td>';
            $html .= '</tr>';

            for ($i = 1; $i < count($aprovechamiento); $i++) {
                $html .= '<tr>';
                $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[$i]['abreviatura'] . '</td>';
                $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[$i]['rango_minimo'] . '</tdAPROVECHAMIENTO>';
                $html .= '<td class="centrarTexto bordesolido">' . $aprovechamiento[$i]['rango_maximo'] . '</td>';
                $html .= '<td class="bordesolido">' . $aprovechamiento[$i]['descripcion'] . '</td>';
                $html .= '</tr>';
            }
        }





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
        $html .= '<td class="centrarTexto tamano10 bordesolido" rowspan="2"><strong>ASISTENCIA</strong></td>';


        $sumaAtrasos = 0;
        $sumaJustificadas = 0;
        $sumaInjustificadas = 0;

        $sumaAtrasosQ2 = 0;
        $sumaJustificadasQ2 = 0;
        $sumaInjustificadasQ2 = 0;
        foreach ($this->modelBloquesQ1 as $q1) {
            $novedades = $sentencias->get_novedad($alumnoId, $q1->id);
//            print_r($novedades);
//            die();
            $sumaAtrasos = $sumaAtrasos + $novedades[9];
            $sumaJustificadas = $sumaJustificadas + $novedades[10];
            $sumaInjustificadas = $sumaInjustificadas + $novedades[11];
        }

        if ($this->quimestre == 'q2') {

            foreach ($this->modelBloquesQ2 as $q2) {
                $novedades = $sentencias->get_novedad($alumnoId, $q2->id);

                $sumaAtrasosQ2 = $sumaAtrasosQ2 + $novedades[9];
                $sumaJustificadasQ2 = $sumaJustificadasQ2 + $novedades[10];
                $sumaInjustificadasQ2 = $sumaInjustificadasQ2 + $novedades[11];
            }
        }

        $a = $sumaAtrasos + $sumaAtrasosQ2;
        $j = $sumaJustificadas + $sumaJustificadasQ2;
        $in = $sumaInjustificadas + $sumaInjustificadasQ2;

        $this->observacion = $sentencias->consulta_observacion($this->quimestre, $alumnoId);

        //$this->observacion =  $novedades[12];


        $html .= '<td class="bordesolido centrarTexto tamano10">Atrasos</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Faltas Justificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Faltas Injustificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Presente</td>';
        $html .= '</tr>';
        $html .= '<tr>';

        $html .= '<td class="bordesolido centrarTexto tamano10">' . $a . '</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">' . $j . '</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">' . $in . '</td>';

        $presente = $this->totalDias - ($j + $in);
        $html .= '<td class="bordesolido centrarTexto tamano10">' . $presente . '</td>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function procesa_asignaturas($alumnoId) {

        $sentencias = new SentenciasRepLibreta2();

        if ($this->tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new AlumnoNotasNormales();
        } elseif ($this->tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
        } elseif ($this->tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }

        $areas = $sentencias->get_areas_alumno($alumnoId, "'NORMAL','OPTATIVAS'");
                
        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>MATERIA</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>Q1</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>Q2</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>FQ</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>R1</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>R2</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>FR</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>SUP</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>REM</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>GRA</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="1"><strong>FINAL</strong></td>';
        


        $html .= '</tr>';
        

        foreach ($areas as $ar) {
            if ($ar['se_imprime'] == true) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido">' . $ar['area'] . '</td>';

                $notasArea = $sentenciasNotasAlumnos->get_nota_area($ar['area_id'], $alumnoId, $this->paralelo, $this->usuario);     
                
//                echo '<pre>';
//                print_r($notasArea);
//                die();

                if ($ar['promedia'] == 1) {

//                    if($alumnoId == 2934 && $ar['area_id'] == 11){
//                        echo $alumnoId;
//                        print_r($notasArea);
//                        die();
//                    }
                    
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['q1'] . '</td>';                   
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['q2'] . '</td>';                    
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['final_ano_normal'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['mejora_q1'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['mejora_q2'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['final_con_mejora'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">-</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">-</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">-</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasArea['final_total'] . '</td>';
                    
                    
                } else {
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
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

            $materias = $sentencias->get_materias_alumno($ar['id'], $alumnoId);
            
            foreach ($materias as $mat) {
                if ($mat['se_imprime'] == 1) {
                    
                    if($mat['promedia']){
                        $asterisco = '';
                    }else{
                        $asterisco = '* ';
                    }
                    
                    $html .= '<tr>';

                    $html .= '<td class="bordesolido tamano10">' . $asterisco.$mat['materia'] . '</td>';

                    $notasM = $sentenciasNotasAlumnos->get_nota_materia($mat['grupo_id']);
                    

                    //QUIMESTRE 1
                    
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['q1'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['q2'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['final_ano_normal'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['mejora_q1'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['mejora_q2'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['final_con_mejora'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['supletorio'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['remedial'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['gracia'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['final_total'] . '</td>';

                    $html .= '</tr>';
                }
            }
        }



        /*         * **************** PROMEDIOS ********************* */
        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano8"><strong>PROMEDIOS: </strong></td>';

        $promedios = $sentenciasNotasAlumnos->get_promedio_alumno($alumnoId, $this->paralelo, $this->usuario);
        
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>' . number_format($promedios['q1'],2) . '</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>' . number_format($promedios['q2'],2) . '</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>' . number_format($promedios['final_ano_normal'],2) . '</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>' . number_format($promedios['final_total'],2) . '</strong></td>';

//        if ($this->seccion != 'BACHILLERATO' || $this->seccion == 'DIPL') {
        
        if ($this->tieneProyectos != 'NOTIENE') {
            /*             * * INICIO DE PROYECTOS ** */
            $html .= '<tr>';
            $proyectos = new MecProcesaMaterias();
            $proyQ1 = $proyectos->get_proyectos($alumnoId, $this->paralelo, 'q1');

            //[$quimestre]['abreviatura']

            $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea"><strong>PROYECTOS ESCOLARES:</strong></td>';

            if (count($this->modelBloquesQ1) > 2) {
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p3']['abreviatura'] . '</td>';
            } else {
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p2']['abreviatura'] . '</td>';
            }

            if (count($this->modelBloquesQ1) > 2) {
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p6']['abreviatura'] . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p6']['abreviatura'] . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p6']['abreviatura'] . '</td>';
            } else {
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p5']['abreviatura'] . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p5']['abreviatura'] . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">-</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['p5']['abreviatura'] . '</td>';
            }
            $html .= '</tr>';
            /*             * * FIN DE PROYECTOS *** */
        }

        /*         * * INICIA COMPORTAMIENTO ** */
        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea"><strong>COMPORTAMIENTO:</strong></td>';
        $notas = new ComportamientoProyectos($alumnoId, $this->paralelo);
        $notaC = $notas->arrayNotasComp;

       
        if (count($this->modelBloquesQ1) > 2) {
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p3'] . '</td>';
        } else {
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p2'] . '</td>';
        }

       
        if (count($this->modelBloquesQ1) > 2) {
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p6'] . '</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p6'] . '</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p6'] . '</td>';
        } else {
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p5'] . '</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p5'] . '</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">-</td>';
            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['p5'] . '</td>';
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
        return $res['abreviatura'];
    }

    private function devuelve_nota_promedio($arrayNotaF, $campo) {

        $html = '';
        foreach ($arrayNotaF as $nf) {
            if ($nf['bloque'] == $campo) {
                $nota = $nf['nota'];
                $html .= '<td class="bordesolido centrarTexto"><strong>' . $nota . '</strong></td>';
            }
        }

        if ($campo == 'final_ano_normal') {
            $equivalencia = $this->homologa_aprovechamiento($nota);
            $html .= '<td class="bordesolido centrarTexto"><strong>' . $equivalencia . '</strong></td>';
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

        isset($res['calificacion']) ? $califi = $res['calificacion'] : $califi = 0;

        $nota = $sentencias->homologa_comportamiento($califi, $this->seccion);

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

    private function consulta_notas_finales($alumnoId) {

        $con = Yii::$app->db;
        $query = "select 	bloque, nota 	 
                    from 	scholaris_proceso_promedios
                    where	usuario = '$this->usuario'
                                    and paralelo_id = $this->paralelo
                                    and alumno_id = $alumnoId
                                    --and bloque in ('q1','q2')
                    order by bloque;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();
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

    private function get_nota_materia($alumnoId, $materiaId) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, clase_id, materia_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_materias
                    where	usuario = '$this->usuario'
                                    and materia_id = $materiaId
                                    and alumno_id = $alumnoId
                            and paralelo_id = $this->paralelo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
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

    private function busca_nota_area($alumnoId, $areaId) {
        $con = \Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_areas
                    where 	alumno_id = $alumnoId
                                    and paralelo_id = $this->paralelo
                                    and usuario = '$this->usuario'
                                    and area_id = $areaId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_areas($alumnoId) {
        $con = \Yii::$app->db;
        $query = "select 	a.id 
                                ,a.name as area 
                                ,pa.promedia 
                                ,pa.imprime 
                from 	scholaris_proceso_areas pa
                                inner join scholaris_area a on a.id = pa.area_id
                                inner join scholaris_malla_area ma on ma.area_id = a.id 
                where	alumno_id = $alumnoId
                                and usuario = '$this->usuario'
                                and paralelo_id = $this->paralelo
                                and ma.malla_id = $this->mallaId
                group by a.id,a.name, ma.orden, pa.promedia 
                                ,pa.imprime
                order by ma.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
