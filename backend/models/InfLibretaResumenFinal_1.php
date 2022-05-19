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
class InfLibretaResumenFinal extends \yii\db\ActiveRecord {
    
    private $tipoCalificacion;
    private $periodoId;
    private $periodoCodigo;
    private $usuario;
    

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function genera_reporte_alumno($alumno, $quimestre, $paralelo) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        
        /** inicio de busqueda de usuario conectado **/
        $this->usuario = Yii::$app->user->identity->usuario;
        /** fin de busqueda de usuario conectado**/
        
        /** asiganciones de periodo **/
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $this->periodoId])->one();
        $this->periodoCodigo = $modelPeriodo->codigo;
        /** Fin de asignaciones de periodo **/
        
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $malla = $modelMalla->malla_id;


        $clases = $sentencias->clases_paralelo($paralelo);


        /*** inicia tipo de calificacion ***/ 
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        
        ////// finaliza tipo de calificacion //////
        
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
                    'op_student_inscription.student_id' => $alumno,
//                    'op_student_inscription.inscription_state' => 'M'
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

            $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro);

            $mpdf->WriteHTML($html);
//            $mpdf->addPage();
        }

        $mpdf->SetFooter($pie);

        $mpdf->Output('LibretaResumenFinal' . "curso" . '.pdf', 'D');
        exit;
    }

    public function genera_reporte($paralelo, $quimestre = 'q2') {


        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        
        /** inicio de busqueda de usuario conectado **/
        $this->usuario = Yii::$app->user->identity->usuario;
        /** fin de busqueda de usuario conectado**/
        
        /** asiganciones de periodo **/
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $this->periodoId])->one();
        $this->periodoCodigo = $modelPeriodo->codigo;
        /** Fin de asignaciones de periodo **/
        
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $malla = $modelMalla->malla_id;


        $clases = $sentencias->clases_paralelo($paralelo);


        /*** inicia tipo de calificacion ***/ 
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        
        ////// finaliza tipo de calificacion //////
        
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

            $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro);

            $mpdf->WriteHTML($html);
            $mpdf->addPage();
        }

        $mpdf->SetFooter($pie);

        $mpdf->Output('LibretaResumenFinal' . "curso" . '.pdf', 'D');
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
        $html .= '<br><strong>INFORME DE APRENDIZAJE FINAL</strong>';
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
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .colorPlomo{
                    background-color:#c9cfcb;
                }
                
                .colorFinal{
                    background-color:#8ccaa0;
                }

                    ';
        $html .= '</style>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>ESTUDIANTE: </strong>' . $data->last_name . ' ' . $data->first_name . ' ' . $data->middle_name . '</td>';
        $html .= '<td class="tamano10 derechaTexto"><strong>CURSO: </strong>' . $modelParalelo->course->xTemplate->name . ' "' . $modelParalelo->name . '"</td>';
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
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">MATERIA</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="">Q1</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="">Q2</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto colorPlomo" colspan="">FQ</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">R1</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">R2</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto colorPlomo" rowspan="">FR</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">SUP</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">REM</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">GRA</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto colorFinal" rowspan="">FINAL</td>';
        $html .= '</tr>';

        $html .= $this->asignaturas($alumno, $malla, $minima, $paralelo);

        $html .= '</table>';


//
        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, 'q2', $paralelo);
////        
//        $html .= $this->firmas($alumno, 'q2', $paralelo);

        return $html;
    }

    private function asignaturas($alumno, $malla, $minima, $paralelo) {
        
        $sentencias = new \backend\models\SentenciasRepLibreta2();
        //$sentenciasNotas = new InformeCalculoNotas();
        
        if($this->tipoCalificacion == 0){
            $sentenciasAlNotas = new AlumnoNotasNormales();
        }elseif($this->tipoCalificacion == 2){
            $sentenciasAlNotas = new AlumnoNotasDisciplinar();   
        }elseif($this->tipoCalificacion == 3){
            $sentenciasAlNotas = new AlumnoNotasInterdisciplinar();       
        }
        else{
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }
        
        
        

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();

        $modelMallaArea = $sentencias->get_areas_alumno($alumno, "'NORMAL','OPTATIVAS'");

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

                

                    $notaArea = $sentenciasAlNotas->get_nota_area($area['area_id'], $alumno, $paralelo, $this->usuario);       
                    if ($area['promedia'] == true) {
                    
                    $notaq1         = $notaArea['q1'];
                    $notaq2         = $notaArea['q2'];
                    $finalNormal    = $notaArea['final_ano_normal'];
                    $mejoraQ1       = $notaArea['mejora_q1'];
                    $mejoraQ2       = $notaArea['mejora_q2'];
                    $finalConMejora       = $notaArea['final_con_mejora'];
                    $finalTotal     = $notaArea['final_total'];

                    $html .= $notaq1 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notaq1 . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notaq1 . '</strong></td>';

                    $html .= $notaq2 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notaq2 . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notaq2 . '</strong></td>';

                    $html .= $finalNormal < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $finalNormal . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $finalNormal . '</strong></td>';
                    
                    $html .= $mejoraQ1 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $mejoraQ1 . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $mejoraQ1 . '</strong></td>';
                    
                    $html .= $mejoraQ2 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $mejoraQ2 . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $mejoraQ2 . '</strong></td>';
                    
                    $html .= $finalConMejora < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $finalConMejora . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $finalConMejora . '</strong></td>';

                    //$html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>-</strong></td>';
                    //$html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>-</strong></td>';
//                    $html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>-</strong></td>';
                    $html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>-</strong></td>';
                    $html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>-</strong></td>';
                    $html .= '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>-</strong></td>';
                    $html .= $finalTotal < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $finalTotal . '</strong></td>' :
                            '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $finalTotal . '</strong></td>';
                }
            }

            $modelMaterias = $sentencias->get_materias_alumno($area['id'], $alumno);

            foreach ($modelMaterias as $clase) {
                if ($clase['se_imprime'] == true) {
                    $html .= '<tr>';

                    if ($clase['promedia'] == true) {
                        $html .= '<td class="tamano8 conBorde">   ' . $clase['materia'] . '</td>';
                    } else {
                        $html .= '<td class="tamano8 conBorde">   *' . $clase['materia'] . '</td>';
                    }

                    $notasMateria = $sentenciasAlNotas->get_nota_materia($clase['grupo_id']);                    
                    
                    $mq1 = $notasMateria['q1'];
                    $mq2 = $notasMateria['q2'];
                    $mFInalAnoNormal = $notasMateria['final_ano_normal'];

                    $mMejora1 = $notasMateria['mejora_q1'];
                    $mMejora2 = $notasMateria['mejora_q2'];
                    $mTotalMejora = $notasMateria['final_con_mejora'];

                    $mSupletorio = $notasMateria['supletorio'];
                    $mRemedial = $notasMateria['remedial'];
                    $mGracia = $notasMateria['gracia'];
                    $mFinal = $notasMateria['final_total'];

                    $html .= $mq1 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mq1 . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mq1 . '</td>';
                    $html .= $mq2 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mq2 . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mq2 . '</td>';
                    $html .= $mFInalAnoNormal < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mFInalAnoNormal . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mFInalAnoNormal . '</td>';

                    $html .= $mMejora1 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mMejora1 . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mMejora1 . '</td>';
                    $html .= $mMejora2 < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mMejora2 . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mMejora2 . '</td>';
                    $html .= $mTotalMejora < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mTotalMejora . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mTotalMejora . '</td>';

                    $html .= $mSupletorio < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mSupletorio . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mSupletorio . '</td>';
                    $html .= $mRemedial < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mRemedial . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mRemedial . '</td>';
                    $html .= $mGracia < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mGracia . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mGracia . '</td>';
                    $html .= $mFinal < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mFinal . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $mFinal . '</td>';


                    $html .= '</tr>';
                }
            }
        }
        
        $html .= '<tr>';   
        $html .= '<td class="tamano8 conBorde"><strong>PROMEDIOS:</strong></td>';
        
        $promedios = $sentenciasAlNotas->get_promedio_alumno($alumno, $paralelo, $this->usuario);
        isset($promedios['q1']) ? $q1 = $promedios['q1'] : $q1 = 0;
        isset($promedios['q2']) ? $q2 = $promedios['q2'] : $q2 = 0;
        isset($promedios['final_ano_normal']) ? $final_ano_normal = $promedios['final_ano_normal'] : $final_ano_normal = 0;
        isset($promedios['final_total']) ? $final_total = $promedios['final_total'] : $final_total = 0;
        
        $html .= '<td class="tamano8 conBorde centrarTexto"><strong>'.$q1.'</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"><strong>'.$q2.'</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"><strong>'.$final_ano_normal.'</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto"><strong>'.$final_total.'</strong></td>';
                      
        $html .= '</tr>';
        
        
        /*** INICIO DE PROYECTOS ***/
        $html .= '<tr>';
        $proyectos = new MecProcesaMaterias();
        $proyQ1 = $proyectos->get_proyectos($alumno, $paralelo, 'q1');
        $html .= '<td class="tamano8 conBorde"><strong>PROYECTOS ESCOLARES:</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto" colspan="10">'.$proyQ1['q2']['abreviatura'].'</td>';
        $html .= '</tr>';
        /*** FIN DE PROYECTOS ***/
        
        
        
        /*** INICIA COMPORTAMIENTO ***/
        $html .= '<tr>';
        $html .= '<td class="tamano8 conBorde"><strong>COMPORTAMIENTO:</strong></td>';
//        $notas = $sentenciasNxx->toma_comportamiento();
        $notas = new ComportamientoProyectos($alumno, $paralelo);
        $notaC= $notas->arrayNotasComp;
        
        
        $html .= '<td class="tamano8 conBorde centrarTexto" colspan="10">'.$notaC[0]['q2'].'</td>';

        $html .= '</tr>';
        

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
//        $modelMaterias = $sentencia->get_materias_alumno($area, $alumno);


        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';

//        foreach ($modelMaterias as $clase) {
//            if ($clase['se_imprime'] == true) {
//                $html .= '<tr>';
//
//                if ($clase['promedia'] == true) {
//                    $html .= '<td class="tamano8 conBorde">   ' . $clase['materia'] . '</td>';
//                } else {
//                    $html .= '<td class="tamano8 conBorde">   *' . $clase['materia'] . '</td>';
//                }
//
//                $notas = $sentenciaNotas->calcula_nota($alumno, $clase['materia_id'], $periodoCodigo);
//
//                $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>';
//                $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';
//                $html .= $notas['final_ano_normal'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>';
//                $html .= $notas['mejora_q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['mejora_q1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['mejora_q1'] . '</td>';
//                $html .= $notas['mejora_q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['mejora_q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['mejora_q2'] . '</td>';
//                $html .= $notas['final_con_mejora'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_con_mejora'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_con_mejora'] . '</td>';
//                $html .= $notas['supletorio'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['supletorio'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['supletorio'] . '</td>';
//                $html .= $notas['remedial'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['remedial'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['remedial'] . '</td>';
//                $html .= $notas['gracia'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['gracia'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['gracia'] . '</td>';
//                $html .= $notas['final_total'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_total'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_total'] . '</td>';
//
//                $html .= '</tr>';
//            }
//        }

        return $html;
    }


    public function proyectos($alumno, $usuario, $modelBloque, $malla) {
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

            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
            $html .= '<td class=" tamano8 conBorde colorPlomo"></td>';
            $html .= '<td class=" tamano8 conBorde colorPlomo"></td>';
            $html .= '<td class=" tamano8 conBorde colorPlomo"></td>';
            $html .= '<td class=" tamano8 conBorde colorPlomo"></td>';
            $html .= '<td class=" tamano8 conBorde colorPlomo"></td>';
            $html .= '<td class=" tamano8 conBorde colorPlomo"></td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';


            $html .= '</tr>';

            return $html;
        } else {
            return '';
        }
    }

//    public function comportamiento($alumno, $usuario, $modelBloque, $malla) {
//        $sentencias = new \backend\models\SentenciasRepLibreta2();
//
//
//        $html = '';
//        
//
//        return $html;
//    }

    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $quimestre, $paralelo) {

        $sentenciasFaltas = new SentenciasFaltas();

        $html = '';
        $html .= '<br>';

        /**
         * FALTAS Y ATRASOS
         */
//        $datosFaltas = $sentenciasFaltas->devuelve_faltas_a_libreta($alumno, $quimestre, $paralelo);
//
//        $presentes = $datosFaltas[3] - ($datosFaltas[1] + $datosFaltas[2]);
//
//        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
//        $html .= '<tr>';
//        $html .= '<td class="centrarTexto conBorde" rowspan="2">ASISTENCIA</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="">Atraso</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Justificada</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Injustificada</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="">Presente</td>';
//        $html .= '</tr>';
//        $html .= '<tr>';
//        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[0] . '</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[1] . '</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[2] . '</td>';
//        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $presentes . '</td>';
//        $html .= '</tr>';
//        $html .= '</table>';


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
