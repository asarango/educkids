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
        $sentenciasNotas = new \backend\models\NotasEnLibreta();


        $curso = $_GET['curso'];
        $paralelo = $_GET['paralelo'];
        $alumno = $_GET['alumno'];

        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();


        $malla = $modelMalla->malla_id;

        if ($alumno) {
            $clases = $sentencias->clases_alumno($alumno);
        } else {
            $clases = $sentencias->clases_paralelo($paralelo);
        }

//        foreach ($clases as $clase) {
//            $sentenciasNotas->actualizaParcialesLibreta($clase['clase_id']);
//            $sentenciasNotas->calcula_promedios_clase($clase['clase_id']);
//        }

        $sentencias->procesarAreas($curso, $paralelo);

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


        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();

        $minima = $modelParametros->valor;

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

            $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }



//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf() {
        $instituto = Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = \backend\models\OpInstitute::findOne($instituto);
        $html = '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td width="30%"><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';

        $html .= '<td><center>';
        $html .= '<p>' . $modelInstituto->name . '</p>';
        $html .= '<p style="font-size:10px">CUADRO DE NOTAS ANUAL</p>';
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
        $html .= '<td class="tamano10">ESTUDIANTE: ' . $data->last_name . ' ' . $data->first_name . ' ' . $data->middle_name . '</td>';
        $html .= '<td class="tamano10 derechaTexto">CURSO: ' . $modelParalelo->course->name . ' "' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->genera_cuerpo_pdf_detalle_materias($data->id, $malla, $paralelo,
                $modelEscalasProyectos, $modelEscalasComportamiento,
                $minima, $modelEscalasApro);

        return $html;
    }

    public function genera_cuerpo_pdf_detalle_materias($alumno, $malla, $paralelo, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro) {
        $html = '';

        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2">ASIGNATURAS</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="8">QUIMESTRE I</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="8">QUIMESTRE II</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2">FIN</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto">P1</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">P2</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">P3</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">PR</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">80</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">EX</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">20</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">Q1</td>';

        $html .= '<td class="tamano10 conBorde centrarTexto">P4</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">P5</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">P6</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">PR</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">80</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">EX</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">20</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto">Q2</td>';

        $html .= '</tr>';

        $html .= $this->asignaturas($alumno, $malla, $minima);
        $html .= '</table>';

        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $paralelo);
        $html .= $this->firmas();


//        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento);

        return $html;
    }

    private function asignaturas($alumno, $malla, $minima) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();



        $modelMallaArea = $sentencias->get_areas_alumno($alumno, 'NORMAL');


//        $modelMallaArea = \backend\models\ScholarisMallaArea::find()
//                ->where(['malla_id' => $malla, 'tipo' => 'NORMAL'])
//                ->orderBy("orden")
//                ->all();

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
                }else{
                    $html .= '<td class="tamano8 conBorde"><strong>* ' . $area['area'] . '</strong></td>';
                }

                $notas = $sentencias->get_nota_por_area($alumno, $usuario, $area['id']);

                if ($area['promedia'] == true) {
//
                    $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                    $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                    $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                    $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr180'] . '</strong></td>';
                    $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr180'] . '</strong></td>';
                    $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>';


                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex2'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';

                    $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>';

                    $html .= "</tr>";
                }
            }

            $html .= $this->materias($area['id'], $alumno, $usuario, $minima);
        }

        $html .= $this->aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima);
        $html .= $this->proyectos($alumno, $usuario, $modelBloque, $malla);
        $html .= $this->comportamiento($alumno, $usuario, $modelBloque, $malla);

        return $html;
    }

    private function materias($area, $alumno, $usuario, $minima) {

        $sentencia = new \backend\models\SentenciasRepLibreta2();
        $modelMaterias = $sentencia->get_materias_alumno($area, $alumno);


        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';

        foreach ($modelMaterias as $clase) {
            if ($clase['se_imprime'] == true) {
                $html .= '<tr>';
                
                if($clase['promedia'] == true){
                    $html .= '<td class="tamano8 conBorde">   ' . $clase['materia'] . '</td>';
                }else{
                    $html .= '<td class="tamano8 conBorde">   *' . $clase['materia'] . '</td>';
                }
                

                $notas = $sentencia->get_notas_por_materia($clase['clase_id'], $alumno);
//
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

                $html .= $notas['final_ano_normal'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['final_ano_normal'] . '</td>';

                $html .= '</tr>';
            }
        }

        return $html;
    }

    public function aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>APROVECHAMIENTO:</strong></td>';

        $notas = $sentencias->get_notas_finales($alumno, $usuario, $malla);
//
//        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p1'] . '</strong></td>';
        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p2'] . '</strong></td>';
        $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p3'] . '</strong></td>';
        $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr1'] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr180'] . '</strong></td>';
        $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex1'] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex120'] . '</strong></td>';
        $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q1'] . '</strong></td>';


        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p4'] . '</strong></td>';
        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p5'] . '</strong></td>';
        $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p6'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['p6'] . '</strong></td>';
        $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr2'] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['pr280'] . '</strong></td>';
        $html .= $notas['ex2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex2'] . '</strong></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['ex220'] . '</strong></td>';
        $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q2'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['q2'] . '</strong></td>';

        $html .= $notas['final_ano_normal'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['final_ano_normal'] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas['final_ano_normal'] . '</strong></td>';


        $html .= '</tr>';

        return $html;
    }

    public function proyectos($alumno, $usuario, $modelBloque, $malla) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();


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
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $pr1['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $pr180['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $ex1['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $ex120['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $q1['abreviatura'] . '</td>';

        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $pr2['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $pr280['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $ex2['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $ex220['abreviatura'] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $q2['abreviatura'] . '</td>';

        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $final_ano_normal['abreviatura'] . '</td>';

        $html .= '</tr>';

        return $html;
    }

    public function comportamiento($alumno, $usuario, $modelBloque, $malla) {
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
//        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"></td>';
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
//        
        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';

        $html .= '</tr>';

        return $html;
    }

    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $paralelo) {
        
        $sentenciasFaltas = new \backend\models\SentenciasFaltas();
        $datosFaltas = $sentenciasFaltas->devuelve_faltas_a_libreta($alumno, 'q2', $paralelo);

        $presentes = $datosFaltas[3] - ($datosFaltas[1] + $datosFaltas[2]);
        
        
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
        $html .= '<td class="centrarTexto conBorde" colspan="2">FALTAS Y ATRASOS</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">ATRASOS:</td>';
        $html .= '<td class="conBorde" align="center">'.$datosFaltas[0].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">F. JUSTIFICADAS:</td>';
        $html .= '<td class="conBorde" align="center">'.$datosFaltas[1].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">F. INJUSTIFICADAS:</td>';
        $html .= '<td class="conBorde" align="center">'.$datosFaltas[2].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">DIAS ASISTIDOS:</td>';
        $html .= '<td class="conBorde" align="center">'.$presentes.'</td>';
        $html .= '</tr>';
//        $html .= '<tr>';
//        $html .= '<td class="conBorde">DIAS LABORADOS:</td>';
//        $html .= '<td class="conBorde">'.$datosFaltas[0].'</td>';
//        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';

        /*         * *
         * ESCALAS APROVECHAMIENTO
         */
        $html .= '<td width="33%" valign="top">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto" colspan="3">ESCALAS DE APROVECHAMIENTO</td>';
        $html .= '</tr>';


        foreach ($modelEscalasApro as $proy) {
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $proy['abreviatura'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proy['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proy['rango_maximo'] . '</td>';
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
        $html .= '<td class="conBorde centrarTexto">ESCALAS DE COMPORTAMIENTO</td>';
        $html .= '</tr>';

        foreach ($modelEscalasComportamiento as $comp) {
            $html .= '<tr>';
            $html .= '<td class="conBorde">(' .$comp['abreviatura'].') - '. $comp['descripcion'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['rango_maximo'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</td>';


        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function firmas() {
        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';

        $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
        $html .= '<td width="34%" class=""></td>';
        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="centrarTexto">SECRETARÍA</td>';
        $html .= '<td width="34%" class=""></td>';
        $html .= '<td width="33%" class="centrarTexto">TUTORÍA</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

}
