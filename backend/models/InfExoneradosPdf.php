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
class InfExoneradosPdf extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
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
    private $notaExoneracion;

    public function __construct($paralelo, $alumno, $quimestre, $notaExoneracion) {

        if (!isset(\Yii::$app->user->identity->periodo_id)) {
            echo 'Su sesión expiró!!!';
            echo Html::a('Iniciar Sesión', ['/site/index']);
            die();
        }

        $this->notaExoneracion = $notaExoneracion;

        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;

        $sentencias = new SentenciasAlumnos();
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $modelParalelo->course->section0->code;

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
            'margin_bottom' => 10,
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
            //$mpdf->addPage();
        }

        //$mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function titulo_libreta() {
        if ($this->quimestre == 'q1') {
            $this->tituloQuimestre = 'PRIMER QUIMESTRE';
        } elseif ($this->quimestre == 'q2') {
            $this->tituloQuimestre = 'SEGUNDO QUIMESTRE';
        }
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%" border="0">';
        $html .= '<tr>';
        $html .= '<td align="left" width="10%"><img src="imagenes/instituto/logo/logo2.png" width="10%"></td>';
        $html .= '<td class="centrarTexto">';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo . '<br>';
        $html .= '<strong>NÓMINA DE EXONERADOS</strong>';
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
        $html .= '.conBorde{border$html .= $this->escalas();
        $html .= $this->observaciones();: 0.1px solid black;}';
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
//        $html .= $this->firmas();
        $html .= '<br>';

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
        $html .= '<td width="40%" class="bordesolido centrarTexto" rowspan=""><strong>MATERIA</strong></td>';
        
        foreach ($this->modelBloquesQ1 as $bloq1) {
            $html .= '<td class="bordesolido centrarTexto">' . $bloq1->abreviatura . '</td>';
        }

        $html .= '<td class="bordesolido centrarTexto">PROM</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>80%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto">EXAM</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>20%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>Q1</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>B4</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>B5</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>B6</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>EXONE.</strong></td>';

        $html .= '</tr>';

        foreach ($areas as $ar) {

            $materias = $sentencias->get_materias_alumno($ar['id'], $alumnoId);

            foreach ($materias as $mat) {

                $html .= '<tr>';

                $notasM = $sentenciasNotasAlumnos->get_nota_materia($mat['grupo_id']);
                $promedioExoneracion = number_format(($notasM['q1'] + $notasM['p4'] + $notasM['p5'] + $notasM['p6']) / 4, 2);

                if ($promedioExoneracion >= $this->notaExoneracion) {
                    $html .= '<td class="bordesolido tamano10">' . $mat['materia'] . '</td>';
                    //QUIMESTRE 1
                    $html .= '<td class="bordesolido centrarTexto">' . $notasM['p1'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto">' . $notasM['p2'] . '</td>';
                    if (count($this->modelBloquesQ1) > 2) {
                        $html .= '<td class="bordesolido centrarTexto">' . $notasM['p3'] . '</td>';
                    }
                    $html .= '<td class="bordesolido centrarTexto">' . $notasM['pr1'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto">' . $notasM['pr180'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto">' . $notasM['ex1'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto">' . $notasM['ex120'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['q1'] . '</td>';


                    ////////////////FIN QUIMESTRE 1 DE MATERIAS /////////////////////

                    /*                     * ***** QUIMESTRE 2*********** */
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['p4'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['p5'] . '</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">' . $notasM['p6'] . '</td>';

                    $html .= '<td class="bordesolido tamano8 centrarTexto" bgcolor="#ccff99">' . $promedioExoneracion . '</td>';
                }




                $html .= '</tr>';
            }
        }
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
