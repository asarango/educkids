<?php

namespace backend\controllers;

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
class ScholarisRepLibreta2Controller extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function actionIndex() {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
       
        
        $curso = $_GET['curso'];
        $paralelo = $_GET['paralelo'];
        $alumno = $_GET['alumno'];

        $malla = $sentencias->procesarAreas($curso, $paralelo);                

        return $this->redirect(['pdf', "paralelo" => $paralelo, "alumno" => $alumno, 'malla' => $malla]);
    }

    public function actionPdf($paralelo, $alumno, $malla) {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();        
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        
        $modelEscalasProyectos = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'PROYECTOS'
                ])
                ->orderBy('rango_minimo')
                ->all();
        
        $modelEscalasComportamiento = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'COMPORTAMIENTO',
                    'section_codigo' => $modelParalelo->course->section0->code
                ])
                ->orderBy('rango_minimo')
                ->all();
        
        
        

        if ($alumno) {
            $modelAlmunos = OpStudent::find()
                    ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                    ->where([
                        'op_student_inscription.student_id' => $alumno,
                        'op_student_inscription.parallel_id' => $paralelo
                    ])
                    ->all();
        } else {
            $modelAlmunos = OpStudent::find()
                    ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                    ->where([
                        'op_student_inscription.parallel_id' => $paralelo
                    ])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                    ->all();
        }


        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf();
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;
        
        
        
        

        foreach ($modelAlmunos as $data) {

            $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }



//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Litrerta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf() {

        $html = '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td width="30%"><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';

        $html .= '<td><center>';
//        $html .= '<p>' . $modelInstituto->name . '</p>';
        $html .= '<p style="font-size:10px">Cuadro de notas anual</p>';
        $html .= '</center>';

        $html .= '<td width="30%" style="text-align: right;">';
//        $html .= '<p style="font-size:8px">' . $modelParalelo->course->name . ' - ' . $modelParalelo->name . '</p>';
//        $html .= '<p style="font-size:7px">Año lectivo: ' . $modelPeriodo->nombre . '</p>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento) {

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
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
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }

                    ';
        $html .= '</style>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>ESTUDIANTE: </strong>' . $data->last_name . ' ' . $data->first_name . ' ' . $data->middle_name . '</td>';
        $html .= '<td class="tamano10 derechaTexto"><strong>CURSO: </strong>' . $modelParalelo->course->name . ' "' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->genera_cuerpo_pdf_detalle_materias($data->id, $malla, $paralelo, $modelEscalasProyectos, $modelEscalasComportamiento);

        return $html;
    }

    public function genera_cuerpo_pdf_detalle_materias($alumno, $malla, $paralelo, $modelEscalasProyectos, $modelEscalasComportamiento) {
        $html = '';

        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2"><strong>ASIGNATURAS</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="8"><strong>QUIMESTRE I</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="8"><strong>QUIMESTRE II</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2"><strong>FIN</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>P1</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>P2</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>P3</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>PR</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>80</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>EX</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>Q1</strong></td>';

        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>P4</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>P5</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>P6</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>PR</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>80</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>EX</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="tamano10 conBorde centrarTexto"><strong>Q2</strong></td>';

        $html .= '</tr>';

        $html .= $this->asignaturas($alumno, $malla);
        $html .= '</table>';
        
        
        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento);

        return $html;
    }

    private function asignaturas($alumno, $malla) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();

        $modelMallaArea = \backend\models\ScholarisMallaArea::find()
                ->where(['malla_id' => $malla, 'tipo' => 'NORMAL'])
                ->orderBy("orden")
                ->all();

        $modelBloque = ScholarisBloqueActividad::find()
                ->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_uso' => $modelMalla->tipo_uso
                ])
                ->andFilterWhere(['IN', 'tipo_bloque', ['PARCIAL', 'EXAMEN']])
                ->orderBy("orden")
                ->all();

        $html = '';

        foreach ($modelMallaArea as $area) {

            if ($area->se_imprime == true) {
                $html .= "<tr>";
                $html .= '<td class="tamano8 conBorde"><strong>' . $area->area->name . '</strong></td>';

                $notas = $sentencias->get_nota_por_area($alumno, $usuario, $area->id, $modelBloque);

                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[0] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[1] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[2] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[3] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[4] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[5] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[6] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[7] . '</strong></td>';

                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[8] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[9] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[10] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[11] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[12] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[13] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[14] . '</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[15] . '</strong></td>';

                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[16] . '</strong></td>';

                $html .= "</tr>";
            }

            $html .= $this->materias($area->id, $alumno, $usuario, $modelBloque);
        }

        $html .= $this->aprovechamiento($alumno, $usuario, $modelBloque, $malla);
        $html .= $this->proyectos($alumno, $usuario, $modelBloque, $malla);
        $html .= $this->comportamiento($alumno, $usuario, $modelBloque, $malla);
        
        return $html;
    }

    private function materias($area, $alumno, $usuario, $modelBloque) {
        $sentencia = new \backend\models\SentenciasRepLibreta2();

        $modelMaterias = \backend\models\ScholarisMallaMateria::find()
                ->where(['malla_area_id' => $area])
                ->all();

        $modelMaterias = \backend\models\ScholarisClase::find()
                ->innerJoin("scholaris_malla_materia", "scholaris_malla_materia.id = scholaris_clase.malla_materia")
                ->innerJoin("scholaris_materia", "scholaris_materia.id = scholaris_malla_materia.materia_id")
                ->where(['scholaris_malla_materia.malla_area_id' => $area])
                ->all();

        $html = '';

        foreach ($modelMaterias as $clase) {
            $html .= '<tr>';
            $html .= '<td class="tamano8 conBorde">' . $clase->mallaMateria->materia->name . '</td>';

            $notas = $sentencia->get_notas_por_materia($clase->id, $alumno, $usuario, $modelBloque);
//            
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[6] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[7] . '</td>';

            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[8] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[9] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[10] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[11] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[12] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[13] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[14] . '</td>';
            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[15] . '</td>';

            $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[16] . '</td>';

            $html .= '</tr>';
        }

        return $html;
    }

    public function aprovechamiento($alumno, $usuario, $modelBloque, $malla) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>APROVECHAMIENTO:</strong></td>';

        $notas = $sentencias->get_notas_finales($alumno, $modelBloque, $usuario, $malla);

        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[0] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[1] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[2] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[3] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[4] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[5] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[6] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[7] . '</strong></td>';
        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[8] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[9] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[10] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[11] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[12] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[13] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[14] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[15] . '</strong></td>';
        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[16] . '</strong></td>';

        $html .= '</tr>';

        return $html;
    }
    
    public function proyectos($alumno, $usuario, $modelBloque, $malla){
        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>PROYECTOS ESCOLARES:</strong></td>';

        $notas = $sentencias->get_notas_finales_proyectos($alumno, $modelBloque, $usuario, $malla);

        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[0] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[1] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[2] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[3] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[4] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[5] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[6] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[7] . '</strong></td>';
        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[8] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[9] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[10] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[11] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[12] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[13] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[14] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[15] . '</strong></td>';
        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[16] . '</strong></td>';

        $html .= '</tr>';

        return $html;
    }
    
    public function comportamiento($alumno, $usuario, $modelBloque, $malla){
        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>COMPORTAMIENTO:</strong></td>';

        $notas = $sentencias->get_notas_finales_comportamiento($alumno, $modelBloque, $usuario, $malla);

        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[0] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[1] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[2] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[3] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[4] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[5] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[6] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[7] . '</strong></td>';
        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[8] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[9] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[10] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[11] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[12] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[13] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[14] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[15] . '</strong></td>';
        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[16] . '</strong></td>';

        $html .= '</tr>';

        return $html;
    }
    
    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento){
        
               
        
        $html = '';
        $html .= '<br>';
        
        $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
        $html .= '<tr>'; 
        
        /**
         * FALTAS Y ATRASOS
         */
        
        $html .= '<td width="33%" class="" valign="top">';
        $html .= '<table height="300" width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';       
        $html .= '<td class="centrarTexto conBorde" colspan="2"><strong>FALTAS Y ATRASOS</strong></td>';        
        $html .= '</tr>';        
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>ATRASOS:</strong></td>';
        $html .= '<td class="conBorde"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>F. JUSTIFICADAS:</strong></td>';
        $html .= '<td class="conBorde"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>F. INJUSTIFICADAS:</strong></td>';
        $html .= '<td class="conBorde"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>DIAS ASISTIDOS:</strong></td>';
        $html .= '<td class="conBorde"></td>';
        $html .= '</tr>';        
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>DIAS LABORADOS:</strong></td>';
        $html .= '<td class="conBorde"></td>';
        $html .= '</tr>';                      
        $html .= '</table>';
        $html .= '</td>';
        
        /***
         * ESCALAS APROVECHAMIENTO
         */
        $html .= '<td width="33%" valign="top">';        
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';       
        $html .= '<td class="conBorde centrarTexto" colspan="3"><strong>ESCALAS DE APROVECHAMIENTO</strong></td>';
        $html .= '</tr>';
        
        
        foreach ($modelEscalasProyectos as $proy){
            $html .= '<tr>';
            $html .= '<td class="conBorde">'.$proy['descripcion'].'</td>';
            $html .= '<td class="conBorde centrarTexto">'.$proy['rango_minimo'].'</td>';
            $html .= '<td class="conBorde centrarTexto">'.$proy['rango_maximo'].'</td>';
            $html .= '</tr>';
        }
        
        
        
        
        $html .= '</table>';        
        $html .= '</td>';
        
        /**
         * ESCALAS COMPORTAMIENTO
         */
        $html .= '<td width="34%" valign="top">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';       
        $html .= '<td class="conBorde centrarTexto"><strong>ESCALAS DE COMPORTAMIENTO</strong></td>';
        $html .= '</tr>';
        
        foreach ($modelEscalasComportamiento as $comp){
            $html .= '<tr>';
            $html .= '<td class="conBorde">'.$comp['descripcion'].'</td>';
            $html .= '<td class="conBorde centrarTexto">'.$comp['rango_minimo'].'</td>';
            $html .= '<td class="conBorde centrarTexto">'.$comp['rango_maximo'].'</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</td>';
               
        
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }

}
