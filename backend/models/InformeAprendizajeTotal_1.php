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
class InformeAprendizajeTotal extends \yii\db\ActiveRecord {

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function genera_reporte_alumno($alumno, $paralelo, $quimestre) {


        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $malla = $modelMalla->malla_id;


        $clases = $sentencias->clases_paralelo($paralelo);

        $sentencias->procesarAreas($modelParalelo->course_id, $paralelo);


        $modelEscalasProyectos = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'PROYECTOS'
                ])
                ->orderBy('rango_minimo')
                ->all();

        $modelEscalasApro = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'APROVECHAMIENTO'
                ])
                ->orderBy('rango_minimo')
                ->all();


        $modelEscalasComportamiento = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'COMPORTAMIENTO',
                    'section_codigo' => $modelParalelo->course->section0->code
                ])
                ->orderBy('abreviatura')
                ->all();

        $modelAlmunos = OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where([
                    'op_student_inscription.parallel_id' => $paralelo,
                    'op_student_inscription.student_id' => $alumno
                ])
                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                ->one();

        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();

        $minima = $modelParametros->valor;


        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        //foreach ($modelAlmunos as $data) {

        $html = $this->genera_cuerpo_pdf($modelAlmunos, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $quimestre);
//
        $mpdf->WriteHTML($html);
        // $mpdf->addPage();
        //}
//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    public function genera_reporte($paralelo, $alumno) {


        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $malla = $modelMalla->malla_id;


        $clases = $sentencias->clases_paralelo($paralelo);

        $sentencias->procesarAreas($modelParalelo->course_id, $paralelo);


        $modelEscalasProyectos = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'PROYECTOS'
                ])
                ->orderBy('rango_minimo')
                ->all();

        $modelEscalasApro = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'APROVECHAMIENTO'
                ])
                ->orderBy('rango_minimo')
                ->all();


        $modelEscalasComportamiento = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'COMPORTAMIENTO',
                    'section_codigo' => $modelParalelo->course->section0->code
                ])
                ->orderBy('abreviatura')
                ->all();

        if ($alumno == 0) {
            $modelAlmunos = OpStudent::find()
                    ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                    ->where([
                        'op_student_inscription.parallel_id' => $paralelo
                    ])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                    ->all();
        } else {
            $modelAlmunos = OpStudent::find()->where(['id' => $alumno])->all();
        }

        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();

        $minima = $modelParametros->valor;


        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        if ($modelAlmunos > 1) {
            foreach ($modelAlmunos as $data) {
//
                $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro);
                $mpdf->WriteHTML($html);
                $mpdf->addPage();
            }
        } else {
            foreach ($modelAlmunos as $data) {
//
                $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro);
                $mpdf->WriteHTML($html);
                //$mpdf->addPage();
            }
        }


//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        $html = '';

        $html .= '<table width="100%" cellspacing="0" style="font-size:12px">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td align="center"><strong>' . $modelParalelo->institute->name . '</strong>';
        $html .= '<br><strong>AÑO LECTIVO ' . $modelPeriodo->codigo . '</strong>';
        $html .= '<br><strong>REPORTE ANUAL </strong>';
//        $html .= '<br><strong>' . $corresponde . '</strong>';
        $html .= '</td>';
        $html .= '<td align="center" width="20%"></td>';
        $html .= '<tr>';
        $html .= '</table>';


        return $html;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= 'Elaborado por: ' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro) {

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.1px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano6{
                    font-size: 6px;
                  }
                  
                  .tamano8{
                    font-size: 8px;
                  }
                  
                .tamano10{
                    font-size: 9px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }

                    ';
        $html .= '</style>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>ESTUDIANTE: ' . $data->last_name . ' ' . $data->first_name . ' ' . $data->middle_name . '</strong></td>';
        $html .= '<td class="tamano10 derechaTexto"><strong>CURSO: ' . $modelParalelo->course->xTemplate->name . ' "' . $modelParalelo->name . '"</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->genera_cuerpo_pdf_detalle_materias($data->id, $malla, $paralelo,
                $modelEscalasProyectos, $modelEscalasComportamiento,
                $minima, $modelEscalasApro);


        return $html;
    }

    public function genera_cuerpo_pdf_detalle_materias($alumno, $malla, $paralelo, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro) {
        $html = '';
        $html .= '<table width="100%" cellpadding="2" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto">MATERIA</td>';

        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">P1</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">P2</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">P3</td>';

        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">PR</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">80%</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">EX1</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">20%</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">Q1</td>';

        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">P4</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">P5</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">P6</td>';

        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">PR</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">80%</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">EX2</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">20%</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">Q2</td>';

        $html .= '<td class="tamano10 conBorde centrarTexto">PROMEDIO</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">OBSERVACIONES</td>';
        $html .= '</tr>';

        $html .= $this->asignaturas($alumno, $malla, $minima, $paralelo);

        $html .= '</table>';

        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $paralelo);
        $html .= $this->firmas($alumno, $paralelo);

        return $html;
    }

    private function asignaturas($alumno, $malla, $minima, $paralelo) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new InformeCalculoNotas();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();

        $modelMallaArea = $sentencias->get_areas_alumno($alumno, 'NORMAL');

        $modelBloque = ScholarisBloqueActividad::find()
                ->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_uso' => $modelMalla->tipo_uso
                ])
                ->andFilterWhere(['IN', 'tipo_bloque', ['PARCIAL', 'EXAMEN']])
                ->orderBy("orden")
                ->all();

        $colorBajo = "#F7BEB2";
        $colorSin = "";


        $html = '';

        foreach ($modelMallaArea as $area) {

            if ($area['se_imprime'] == true) {

                $html .= '<tr bgcolor="#F4F3F2">';
                if ($area['promedia'] == true) {
                    $html .= '<td class="tamano8 conBorde"><strong>' . $area['area'] . '</strong></td>';
                } else {
                    $html .= '<td class="tamano8 conBorde"><strong>* ' . $area['area'] . '</strong></td>';
                }

                $notas = $sentencias->get_nota_por_area($alumno, $usuario, $area['id']);

                if ($area['promedia'] == true) {

                    $html .= $this->formatea_nota($notas['p1'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['p2'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['p3'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['pr1'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['pr180'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['ex1'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['ex120'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['q1'], $minima, 'si');

                    $html .= $this->formatea_nota($notas['p4'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['p5'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['p6'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['pr2'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['pr280'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['ex2'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['ex220'], $minima, 'si');
                    $html .= $this->formatea_nota($notas['q2'], $minima, 'si');
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
                    //$homol = $this->homologa_promedio($notas['q2']);
                    //$html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $homol['abreviatura'] . '</strong></td>';
                } else {
                    $html .= '<td class="tamano8 conBorde" colspan="18"></td>';
                }

                $html .= "</tr>";
            }

            $html .= $this->materias($area['id'], $alumno, $usuario, $minima, $paralelo, $modelPeriodo->codigo);
        }

        $html .= $this->aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima);
        $html .= $this->proyectos($alumno, $usuario, $modelBloque, $malla, 'q2');
        $html .= $this->comportamiento($alumno, $usuario, $modelBloque, $malla, 'q2');

        return $html;
    }

    private function formatea_nota($nota, $minima, $imprimirNota) {
        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';

        if ($imprimirNota == 'si') {
            $html .= $nota < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $nota . '</strong></td>' :
                    '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $nota . '</strong></td>';
        } else {
            $html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
        }



        return $html;
    }

    private function homologa_promedio($nota) {
        if ($nota) {
            $nota = $nota;
        } else {
            $nota = 0;
        }
        $con = Yii::$app->db;
        $query = "select 	abreviatura, descripcion 
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'APROVECHAMIENTO'
                                    and scholaris_periodo = '2019-2020'
                                    and $nota between rango_minimo and rango_maximo;";
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    private function materias($area, $alumno, $usuario, $minima, $paralelo, $periodoCodigo) {

        $sentenciaNotas = new \backend\models\InformeCalculoNotas();
        $sentencia = new \backend\models\SentenciasRepLibreta2();
        $modelMaterias = $sentencia->get_materias_alumno($area, $alumno);


        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';

        foreach ($modelMaterias as $clase) {
            if ($clase['se_imprime'] == true) {
                $html .= '<tr>';

                if ($clase['promedia'] == true) {
                    $html .= '<td class="tamano8 conBorde">   ' . $clase['materia'] . '</td>';
                } else {
                    $html .= '<td class="tamano8 conBorde">   *' . $clase['materia'] . '</td>';
                }


//                $notas = $sentencia->get_notas_por_materia($clase['clase_id'], $alumno);
                $notas = $sentenciaNotas->calcula_nota($alumno, $clase['materia_id'], $periodoCodigo);

                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex120'] . '</td>';
                $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>';

                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                $html .= $notas['ex2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
                $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';
                $html .= $notas['final_ano_normal'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['final_ano_normal'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['final_ano_normal'] . '</strong></td>';


                $observacion = $this->observacion_nota($notas['final_ano_normal']);

                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $observacion . '</td>';


                $html .= '</tr>';
            }
        }

        return $html;
    }

    private function observacion_nota($nota) {

        $modelMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $modelRemedial = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaRemed'])->one();

        if (($nota < $modelMinima->valor) && $nota >= $modelRemedial->valor) {
            $observacion = 'SUPLETORIO';
        } elseif ($nota >= $modelMinima->valor) {
            $observacion = '';
        } else {
            $observacion = 'REMEDIAL';
        }

        return $observacion;
    }

    public function aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $sentenciasInforme = new InformeCalculoNotas();

        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>PROMEDIOS:</strong></td>';

        $notas = $sentencias->get_notas_finales($alumno, $usuario, $malla);

        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>';
        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>';
        $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>';
        $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
        $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex120'] . '</td>';
        $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>';

        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>';
        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>';
        $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p6'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p6'] . '</strong></td>';
        $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
        $html .= $notas['ex2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex2'] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
        $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q2'] . '</strong></td>';
        $html .= $notas['final_ano_normal'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['final_ano_normal'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['final_ano_normal'] . '</strong></td>';
        $html .= '<td class="conBorde"></td>';


        $html .= '</tr>';

        return $html;
    }

    public function proyectos($alumno, $usuario, $modelBloque, $malla, $quimestre) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $modelProyectos = ScholarisMallaArea::find()
                        ->where([
                            'malla_id' => $malla,
                            'tipo' => 'PROYECTOS'
                        ])->one();


        if (isset($modelProyectos)) {
            $html = '';
            $html .= '<tr>';

            $html .= '<td class="tamano8 conBorde">PROYECTOS ESCOLARES:</td>';

            $notas = $sentencias->get_notas_cualitativas($alumno, $usuario, $malla, 'PROYECTOS');
            $p1 = $sentencias->homologaProyectos($notas['p1']);
            $p2 = $sentencias->homologaProyectos($notas['p2']);
            $p3 = $sentencias->homologaProyectos($notas['p3']);
            $pr1 = $sentencias->homologaProyectos($notas['pr1']);
            $pr180 = $sentencias->homologaProyectos($notas['pr180']);
            $ex1 = $sentencias->homologaProyectos($notas['ex1']);
            $ex120 = $sentencias->homologaProyectos($notas['ex120']);
            $q1 = $sentencias->homologaProyectos($notas['q1']);

            $p4 = $sentencias->homologaProyectos($notas['p4']);
            $p5 = $sentencias->homologaProyectos($notas['p5']);
            $p6 = $sentencias->homologaProyectos($notas['p6']);
            $pr2 = $sentencias->homologaProyectos($notas['pr2']);
            $pr280 = $sentencias->homologaProyectos($notas['pr280']);
            $ex2 = $sentencias->homologaProyectos($notas['ex2']);
            $ex220 = $sentencias->homologaProyectos($notas['ex220']);
            $q2 = $sentencias->homologaProyectos($notas['q2']);

            $final_ano_normal = $sentencias->homologaProyectos($notas['final_ano_normal']);



            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';

            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            //$html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';

            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';



            $html .= '</tr>';

            return $html;
        } else {
            return '';
        }
    }

    public function comportamiento($alumno, $usuario, $modelBloque, $malla, $quimestre) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde">COMPORTAMIENTO:</td>';

        $notas = $sentencias->get_notas_finales_comportamiento($alumno);


        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';

        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';

        $html .= '</tr>';

        return $html;
    }

    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $paralelo) {

        $sentenciasFaltas = new SentenciasFaltas();

        $html = '';
        $html .= '<br>';

        /**
         * FALTAS Y ATRASOS
         */
        $datosFaltas = $sentenciasFaltas->devuelve_faltas_a_libreta($alumno, 'q1', $paralelo);
        $datosFaltas2 = $sentenciasFaltas->devuelve_faltas_a_libreta($alumno, 'q2', $paralelo);

        $atrasos = $datosFaltas[0] + $datosFaltas2[0];
        $justifica = $datosFaltas[1] + $datosFaltas2[1];
        $injustic = $datosFaltas[2] + $datosFaltas2[2];
        $totalAsistidos = $datosFaltas[3] + $datosFaltas2[3];

        $presentes = $totalAsistidos - ($justifica + $injustic);

        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" rowspan="2">ASISTENCIA</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Atraso</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Justificada</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Injustificada</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Presente</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $atrasos . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $justifica . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $injustic . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $presentes . '</td>';
        $html .= '</tr>';
        $html .= '</table>';


        /*         * *
         * ESCALAS comportamiento
         */
        $html .= '<br>';
        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="5" class="tamano8">';

        $html .= '<tr>';

        $html .= '<td width="20%" valign="top" class="conBorde">';
        $html .= '<p><strong><u>EQUIVALENCIA DE APROVECHAMIENTO</u></strong></p>';

        foreach ($modelEscalasApro as $proy) {
            $html .= '<p><strong>' . $proy['abreviatura'] . ' de ' . $proy['rango_minimo'] . ' a ' . $proy['rango_maximo'] . '</strong></p>';
            $html .= '<p>' . $proy['descripcion'] . '</p>';
        }
        $html .= '</td>';

        $html .= '<td width="30%" valign="top" class="conBorde">';
        $html .= '<p><strong><u>EQUIVALENCIA DE PROYECTOS</u></strong></p>';
        foreach ($modelEscalasProyectos as $proyectos) {
            $html .= '<p><strong>' . $proyectos['abreviatura'] . ' de ' . $proyectos['rango_minimo'] . ' a ' . $proyectos['rango_maximo'] . '</strong></p>';
            $html .= '<p>' . $proyectos['descripcion'] . '</p>';
        }
        $html .= '</td>';

        $html .= '<td width="50%" valign="top" class="conBorde">';
        $html .= '<p><strong><u>EQUIVALENCIA DE COMPORTAMIENTO</u></strong></p>';

        foreach ($modelEscalasComportamiento as $comp) {

            $html .= '<p><strong>' . $comp['abreviatura'] . ' de ' . $comp['rango_minimo'] . ' a ' . $comp['rango_maximo'] . '</strong></p>';
            $html .= '<p>' . $comp['descripcion'] . '</p>';
        }
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table >';


        /*
          $html .= '<br>';
          $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
          $html .= '<tr>';
          $html .= '<td class="centrarTexto conBorde" colspan="">EQUIVALENCIA DE COMPORTAMIENTO</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">DESDE</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">HASTA</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">DESCRIPCIÓN</td>';
          $html .= '</tr>';

          foreach ($modelEscalasComportamiento as $comp) {
          $html .= '<tr>';
          $html .= '<td class="conBorde centrarTexto">' . $comp['abreviatura'] . '</td>';
          $html .= '<td class="conBorde">' . $comp['rango_minimo'] . '</td>';
          $html .= '<td class="conBorde">' . $comp['rango_maximo'] . '</td>';
          $html .= '<td class="conBorde">' . $comp['descripcion'] . '</td>';
          $html .= '</tr>';
          }

          $html .= '</table>';

         */

        /*         * *
         * ESCALAS APROVECHAMIENTO y proyectos
         */
        /* $html .= '<br>';

          $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
          $html .= '<tr>';
          $html .= '<td class="centrarTexto conBorde" colspan="">APROVECHAMIENTO</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">EQUIVALENCIA</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">DESDE</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">HASTA</td>';
          $html .= '<td class="centrarTexto conBorde" colspan="">DESCRIPCIÓN</td>';
          $html .= '</tr>';
          $html .= '<tr>';
          $html .= '<td class="centrarTexto conBorde" rowspan="5">GENERAL</td>';
          $html .= '</tr>';


          foreach ($modelEscalasApro as $proy) {
          $html .= '<tr>';
          $html .= '<td class="conBorde" align="center">' . $proy['abreviatura'] . '</td>';
          $html .= '<td class="conBorde centrarTexto">' . $proy['rango_minimo'] . '</td>';
          $html .= '<td class="conBorde centrarTexto">' . $proy['rango_maximo'] . '</td>';
          $html .= '<td class="conBorde">' . $proy['descripcion'] . '</td>';
          $html .= '</tr>';
          }

          $html .= '<tr>';
          $html .= '<td class="centrarTexto conBorde" rowspan="5">PROYECTOS EDUCATIVOS</td>';
          $html .= '</tr>';

          foreach ($modelEscalasProyectos as $proyectos) {
          $html .= '<tr>';
          $html .= '<td class="conBorde" align="center">' . $proyectos['abreviatura'] . '</td>';
          $html .= '<td class="conBorde centrarTexto">' . $proyectos['rango_minimo'] . '</td>';
          $html .= '<td class="conBorde centrarTexto">' . $proyectos['rango_maximo'] . '</td>';
          $html .= '<td class="conBorde">' . $proyectos['descripcion'] . '</td>';
          $html .= '</tr>';
          }

          $html .= '</table>'; */
        return $html;
    }

    private function firmas($alumno, $paralelo) {
        $sentencias = new SentenciasBloque();

        $bloqueId = $sentencias->get_bloque_por_campo($alumno, 'q2', $paralelo);

        $modelParalelo = OpCourseParalelo::findOne($paralelo);

        $modelTutor = ScholarisClase::find()
                ->innerJoin("scholaris_malla_materia mm", "mm.id = scholaris_clase.malla_materia")
                ->where(['paralelo_id' => $paralelo, 'mm.tipo' => 'COMPORTAMIENTO'])
                ->one();

        $modelObservacion = ScholarisFaltasYAtrasosParcial::find()->where([
                    'alumno_id' => $alumno,
                    'bloque_id' => $bloqueId
                ])->one();

        $html = '';

        $html .= '<br>';


        if ($modelObservacion) {
            $html .= '<p class="tamano8"><strong>OBSERVACIÓN:</strong> <u>' . $modelObservacion->observacion . '</u></p>';
        } else {
            $html .= '<p class="tamano8"><strong>OBSERVACIÓN:</strong> __________________________________________________________________________________________________________________________________________________________</p>';
            $html .= '<p class="tamano8">________________________________________________________________________________________________________________________________________________________________________________</p>';
        }


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
            $modelCoordinador = ScholarisCoordinadores::find()->where(['course_id' => $modelParalelo->course->id])->one();

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
            $html .= '<br><br>';

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

}
