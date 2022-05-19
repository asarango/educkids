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
class InformeAprendizajeQuimestral extends \yii\db\ActiveRecord {

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    
    private $modelBloquesQ1;
    private $modelBloquesQ2;
    
    public function genera_reporte_alumno($alumno, $paralelo, $quimestre) {


        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();


        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelUso->tipo_usu_bloque;

        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();

        $totalBloques = count($modelBloques);



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


        $cabecera = $this->genera_cabecera_pdf($paralelo, $quimestre);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        //foreach ($modelAlmunos as $data) {

        $html = $this->genera_cuerpo_pdf($modelAlmunos, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $quimestre, $totalBloques);  
//
        $mpdf->WriteHTML($html);
        // $mpdf->addPage();
        //}
//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    public function genera_reporte($paralelo, $quimestre) {


        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();


        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelUso->tipo_usu_bloque;

        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();
        
        
        
        
        $this->modelBloquesQ1 = \backend\models\ScholarisBloqueActividad::find()->where([
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL',
                    'quimestre' => 'QUIMESTRE I'
                ])->orderBy('orden')
                ->all();
        
        $this->modelBloquesQ2 = \backend\models\ScholarisBloqueActividad::find()->where([
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL',
                    'quimestre' => 'QUIMESTRE '
                ])->orderBy('orden')
                ->all();

        $totalBloques = count($modelBloques);


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
                    'op_student_inscription.parallel_id' => $paralelo
                ])
                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                ->all();

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


        $cabecera = $this->genera_cabecera_pdf($paralelo, $quimestre);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        foreach ($modelAlmunos as $data) {

            $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $quimestre, $totalBloques);
//
            $mpdf->WriteHTML($html);
            $mpdf->addPage();
        }

//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo, $quimestre) {
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);


        if ($quimestre == 'q1' || $quimestre == 'q2') {
            $corresponde = $quimestre == 'q1' ? 'PRIMER QUIMESTRE' : 'SEGUNDO QUIMESTRE';
            $informe = 'INFORME QUIMESTRAL DE APRENDIZAJE';
        } else {
            $informe = 'INFORME PARCIAL DE APRENDIZAJE';
            switch ($quimestre) {
                case 'p1':
                    $corresponde = 'PARCIAL 1';
                    break;

                case 'p2':
                    $corresponde = 'PARCIAL 2';
                    break;

                case 'p3':
                    $corresponde = 'PARCIAL 3';
                    break;

                case 'p4':
                    $corresponde = 'PARCIAL 4';
                    break;

                case 'p5':
                    $corresponde = 'PARCIAL 5';
                    break;

                case 'p6':
                    $corresponde = 'PARCIAL 6';
                    break;
            }
        }


        $html = '';



        $html .= '<table width="100%" cellspacing="0" style="font-size:12px">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td align="center"><strong>' . $modelParalelo->institute->name . '</strong>';
        $html .= '<br><strong>AÑO LECTIVO ' . $modelPeriodo->codigo . '</strong>';
        $html .= '<br><strong>' . $informe . '</strong>';
        $html .= '<br><strong>' . $corresponde . '</strong>';
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

    private function genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $quimestre, $totalBloques) {

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
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }

                    ';
        $html .= '</style>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano10">ESTUDIANTE: ' . $data->last_name . ' ' . $data->first_name . ' ' . $data->middle_name . '</td>';
        $html .= '<td class="tamano10 derechaTexto">CURSO: ' . $modelParalelo->course->xTemplate->name . ' "' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->genera_cuerpo_pdf_detalle_materias($data->id, $malla, $paralelo,
                $modelEscalasProyectos, $modelEscalasComportamiento,
                $minima, $modelEscalasApro, $quimestre, $totalBloques);


        return $html;
    }

    public function genera_cuerpo_pdf_detalle_materias($alumno, $malla, $paralelo, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $quimestre, $totalBloques) {
        $html = '';




        $html .= '<table width="100%" cellpadding="2" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2">MATERIA</td>';
        if ($totalBloques == 6) {
            $html .= '<td class="tamano10 conBorde centrarTexto" colspan="5">PARCIALES</td>';
        } else {
            $html .= '<td class="tamano10 conBorde centrarTexto" colspan="4">PARCIALES</td>';
        }

        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="2">EVALUACIÓN</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2">PROMEDIO</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2">EQUIVALENCIA</td>';
        $html .= '</tr>';
        $html .= '<tr>';

        if ($quimestre == 'p1' || $quimestre == 'p2' || $quimestre == 'p3' || $quimestre == 'q1') {

            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">1ER PARCIAL</td>';
            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">2DO PARCIAL</td>';
            if ($totalBloques == 6) {
                $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">3ER PARCIAL</td>';
            }
        } else {
            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">4TO PARCIAL</td>';
            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">5TO PARCIAL</td>';
            if ($totalBloques == 6) {
                $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">6TO PARCIAL</td>';
            }
        }

        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">PROM</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">80%</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">EXÁMEN</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">20%</td>';
        $html .= '</tr>';

        $html .= $this->asignaturas($alumno, $malla, $minima, $quimestre, $paralelo, $totalBloques);

        $html .= '</table>';


//
        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $quimestre, $paralelo);

        $html .= $this->firmas($alumno, $quimestre, $paralelo);

        return $html;
    }

    private function asignaturas($alumno, $malla, $minima, $quimestre, $paralelo, $totalBloques) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new InformeCalculoNotas();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();

        $modelMallaArea = $sentencias->get_areas_alumno($alumno, "'NORMAL'");

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

                    switch ($quimestre) {
                        case 'p1':
                            $html .= $this->formatea_nota($notas['p1'], $minima, 'si');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota(0, $minima, 'no');
                            }
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            break;

                        case 'p2':
                            $html .= $this->formatea_nota($notas['p1'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['p2'], $minima, 'si');

                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota(0, $minima, 'no');
                            }
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            break;


                        case 'p3':
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota($notas['p1'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['p2'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['p3'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['pr1'], $minima, 'no');
                                $html .= $this->formatea_nota($notas['pr180'], $minima, 'no');

                                $html .= $this->formatea_nota(0, $minima, 'no');
                                $html .= $this->formatea_nota(0, $minima, 'no');
                                $html .= $this->formatea_nota(0, $minima, 'no');
                                $html .= $this->formatea_nota(0, $minima, 'no');
                            }

                            break;

                        case 'q1':
                            $html .= $this->formatea_nota($notas['p1'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['p2'], $minima, 'si');
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota($notas['p3'], $minima, 'si');
                            }

                            $html .= $this->formatea_nota($notas['pr1'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['pr180'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['ex1'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['ex120'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['q1'], $minima, 'si');
                            $homol = $this->homologa_promedio($notas['q1']);
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $homol['abreviatura'] . '</strong></td>';
                            break;

                        case 'p4':
                            $html .= $this->formatea_nota($notas['p4'], $minima, 'si');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota(0, $minima, 'no');
                            }
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            break;

                        case 'p5':
                            $html .= $this->formatea_nota($notas['p4'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['p5'], $minima, 'si');
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota(0, $minima, 'no');
                            }
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            $html .= $this->formatea_nota(0, $minima, 'no');
                            break;

                        case 'p6':
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota($notas['p4'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['p5'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['p6'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['pr2'], $minima, 'si');
                                $html .= $this->formatea_nota($notas['pr280'], $minima, 'si');

                                $html .= $this->formatea_nota(0, $minima, 'no');
                                $html .= $this->formatea_nota(0, $minima, 'no');
                                $html .= $this->formatea_nota(0, $minima, 'no');
                                $html .= $this->formatea_nota(0, $minima, 'no');
                            }
                            break;

                        case 'q2':
                            $html .= $this->formatea_nota($notas['p4'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['p5'], $minima, 'si');
                            if ($totalBloques == 6) {
                                $html .= $this->formatea_nota($notas['p6'], $minima, 'si');
                            }
                            $html .= $this->formatea_nota($notas['pr2'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['pr280'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['ex2'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['ex220'], $minima, 'si');
                            $html .= $this->formatea_nota($notas['q2'], $minima, 'si');
                            $homol = $this->homologa_promedio($notas['q2']);
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $homol['abreviatura'] . '</strong></td>';
                            break;
                    }


//
//                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>';

                    $html .= "</tr>";
                }
            }

            $html .= $this->materias($area['id'], $alumno, $usuario, $minima, $quimestre, $paralelo, $modelPeriodo->codigo, $totalBloques);
        }

        $html .= $this->aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima, $quimestre, $totalBloques);
        $html .= $this->proyectos($alumno, $usuario, $modelBloque, $malla, $quimestre, $totalBloques);
        $html .= $this->comportamiento($alumno, $usuario, $modelBloque, $malla, $quimestre, $totalBloques);

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

    private function materias($area, $alumno, $usuario, $minima, $quimestre, $paralelo, $periodoCodigo, $totalBloques) {

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

//

                switch ($quimestre) {
                    case 'p1':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        if ($totalBloques == 6) {
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        }
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p2':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                        if ($totalBloques == 6) {
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        }
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p3':
                        if ($totalBloques == 6) {
                            $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                            $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                            $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                            $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        }
                        break;

                    case 'q1':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                        if ($totalBloques == 6) {
                            $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                        }
                        $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                        $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex120'] . '</td>';
                        $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>';

                        if ($notas['q1']) {
                            $homol = $this->homologa_promedio($notas['q1']);
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $homol['abreviatura'] . '</strong></td>';
                        } else {
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>S/C</strong></td>';
                        }




                        break;



                    case 'p4':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        if ($totalBloques == 6) {
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        }
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p5':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                        if ($totalBloques == 6) {
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        }
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p6':
                        if ($totalBloques == 6) {
                            $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                            $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                            $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                            $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        }
                        break;

                    case 'q2':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                        if ($totalBloques == 6) {
                            $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                        }
                        $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                        $html .= $notas['ex2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
                        $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';
                        if ($notas['q2']) {
                            $homol = $this->homologa_promedio($notas['q2']);
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $homol['abreviatura'] . '</strong></td>';
                        } else {
                            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>S/C</strong></td>';
                        }
                        break;
                }

                $html .= '</tr>';
            }
        }

        return $html;
    }

    public function aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima, $quimestre, $totalBloques) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $sentenciasInforme = new InformeCalculoNotas();

        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>PROMEDIOS:</strong></td>';

        $notas = $sentencias->get_notas_finales($alumno, $usuario, $malla);


        switch ($quimestre) {
            case 'p1':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo
                        . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1']
                        . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>'
                        . $notas['p1'] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p2':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>';
                $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p3':
                if ($totalBloques == 6) {
                    $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>';
                    $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>';
                    $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>';
                    $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr180'] . '</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                }
                break;

            case 'q1':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>';
                $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>';
                if ($totalBloques == 6) {
                    $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>';
                }
                $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex120'] . '</td>';
                $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;



            case 'p4':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p5':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>';
                $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p6':
                if ($totalBloques == 6) {
                    $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>';
                    $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>';
                    $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p6'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p6'] . '</strong></td>';
                    $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr2'] . '</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr280'] . '</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                }
                break;

            case 'q2':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                if ($totalBloques == 6) {
                    $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                }
                $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                $html .= $notas['ex2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
                $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;
        }

        $html .= '</tr>';

        return $html;
    }

    public function proyectos($alumno, $usuario, $modelBloque, $malla, $quimestre, $totalBloques) {
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


            switch ($quimestre) {
                case 'p1':
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;

                case 'p2':
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;

                case 'p3':
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    }
                    break;

                case 'q1':
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';

                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
//                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">'.$ex1['abreviatura'].'</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
//                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $q1['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;





                case 'p4':
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;

                case 'p5':
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;

                case 'p6':
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;

                case 'q2':
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
                    if ($totalBloques == 6) {
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
                    }
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
//                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $ex2['abreviatura'] .'</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
//                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    break;
            }




            $html .= '</tr>';

            return $html;
        } else {
            return '';
        }
    }

    public function comportamiento($alumno, $usuario, $modelBloque, $malla, $quimestre, $totalBloques) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde">COMPORTAMIENTO:</td>';

        $notas = $sentencias->get_notas_finales_comportamiento($alumno);

        switch ($quimestre) {
            case 'p1':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p2':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p3':
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                }
                break;

            case 'q1':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
                if ($totalBloques == 6) {
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;


            case 'p4':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                if($totalBloques == 6){
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p5':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
                if($totalBloques == 6){
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p6':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
                if($totalBloques == 6){
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'q2':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
                if($totalBloques == 6){
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
                }
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;
        }



        $html .= '</tr>';

        return $html;
    }

    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $quimestre, $paralelo) {

        $sentenciasFaltas = new SentenciasFaltas();

        $html = '';
        $html .= '<br>';

        /**
         * FALTAS Y ATRASOS
         */
        $datosFaltas = $sentenciasFaltas->devuelve_faltas_a_libreta($alumno, $quimestre, $paralelo);

        $presentes = $datosFaltas[3] - ($datosFaltas[1] + $datosFaltas[2]);

        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" rowspan="2">ASISTENCIA</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Atraso</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Justificada</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Injustificada</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Presente</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[0] . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[1] . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[2] . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $presentes . '</td>';
        $html .= '</tr>';
        $html .= '</table>';


        /*         * *
         * ESCALAS comportamiento
         */
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



        /*         * *
         * ESCALAS APROVECHAMIENTO y proyectos
         */
        $html .= '<br>';

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

        $html .= '</table>';
        return $html;
    }

    private function firmas($alumno, $quimestre, $paralelo) {

        $sentencias = new SentenciasBloque();

        $bloqueId = $sentencias->get_bloque_por_campo($alumno, $quimestre, $paralelo);


        $modelObservacion = ScholarisFaltasYAtrasosParcial::find()->where([
                    'alumno_id' => $alumno,
                    'bloque_id' => $bloqueId
                ])->one();

        $html = '';

        $html .= '<br>';

        if ($modelObservacion) {
            $html .= '<p>OBSERVACIÓN: <u>' . $modelObservacion->observacion . '</u></p>';
        } else {
            $html .= '<p>OBSERVACIÓN: ________________________________________________________________________________________</p>';
            $html .= '<p>________________________________________________________________________________________________________</p>';
        }


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
