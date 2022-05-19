<?php

namespace backend\controllers;

use Yii;
use backend\models\OpCourseParalelo;
use backend\models\ScholarisBloqueActividad;
use backend\models\OpInstitute;
use backend\models\ScholarisClase;
use backend\models\SentenciasRepInsumos;
use backend\models\OpFaculty;
use backend\models\ScholarisResumenParciales;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepPromediosController implements the CRUD actions for ScholarisRepPromedios model.
 */
class ScholarisRepInsumosController extends Controller {

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
     * Lists all ScholarisRepPromedios models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $paralelo = $_GET['paralelo'];
        $bloque = $_GET['bloque'];
        $usuario = Yii::$app->user->identity->usuario;

        $sentencia = new SentenciasRepInsumos();
        $clases = $sentencia->muestra_clases($paralelo, $modelPeriodo->codigo);


        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        return $this->render('index', [
                    'clases' => $clases,
                    'paralelo' => $paralelo,
                    'usuario' => $usuario,
                    'bloque' => $bloque,
                    'modelParalelo' => $modelParalelo
        ]);
    }

    /*
     * Genera el pdf
     */

    public function actionPdf() {

        $sentencias = new SentenciasRepInsumos();
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $clase = $_GET['clase'];
        $paralelo = $_GET['paralelo'];
        $bloque = $_GET['bloque'];


        if ($clase) {
            $clases = $sentencias->muestra_clases_una($paralelo, $clase);
        } else {
            $clases = $sentencias->muestra_clases($paralelo, $modelPeriodo->codigo);
        }


        $cabecera = $this->genera_cabecera($paralelo, $bloque);

        $pie = $this->genera_pie();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 20,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;

        foreach ($clases as $data) {

            $html = $this->genera_html($data['clase_id'], $paralelo, $bloque);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->SetFooter($pie);

            $mpdf->addPage();
        }



        $mpdf->Output('Cuadro_Cursos_Materia.pdf', 'D');
        exit;
    }

    private function genera_cabecera($paralelo, $bloque) {

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $modelBloque = ScholarisBloqueActividad::find()
                ->where(['id' => $bloque])
                ->one();

        $modelInstituto = OpInstitute::find()->one();


        $cab = '<table width="100%">';
        $cab .= '<tr>';
        $cab .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $cab .= '<td><center>';
        $cab .= '<p>' . $modelInstituto->name . '</p>';
        $cab .= '<p style="font-size:10px">CUADRO DE NOTAS POR CURSOS Y MATERIAS </p>';

        $cab .= '</center>';
        $cab .= '</td>';

        $cab .= '</tr>';
        $cab .= '</table>';


        return $cab;
    }

    private function genera_pie() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_html($clase, $paralelo, $bloque) {

        $modelClase = ScholarisClase::find()
                ->where(['id' => $clase])
                ->one();

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $modelBloque = ScholarisBloqueActividad::find()
                ->where(['id' => $bloque])
                ->one();

        $modelProfesor = OpFaculty::find()->where(['id' => $modelClase->idprofesor])->one();


        $html = '';
        $html .= '<style>';
        $html .= '.centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano14{
                    font-size: 14px;
                  }
                  
                  .tamano12{
                    font-size: 12px;
                  }
                  
                  .tamano10{
                    font-size: 10px;
                  }
                  
                 .tamano7{
                    font-size: 7px;
                  }
                  
                  .conBorde {
                    border: 0.3px solid black;
                  }
                 ';
        $html .= '</style>';
        $html .= '<p class="tamano10 centrarTexto">( ' . $modelClase->materia->name . ' ) AÑO LECTIVO: ' . $modelClase->periodo_scholaris . '</p>';

        $html .= '<table width="100%" class="conBorde">';
        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>CURSO:</strong></td>';
        $html .= '<td class="tamano10">' . $modelParalelo->course->name . ' ' . $modelParalelo->name . '</td>';
        $html .= '<td class="tamano10"><strong>QUIMESTRE:</strong></td>';
        $html .= '<td class="tamano10">' . $modelBloque->quimestre . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>PROFESOR (A):</strong></td>';
        $html .= '<td class="tamano10">' . $modelProfesor->last_name . ' ' . $modelProfesor->x_first_name . '</td>';
        $html .= '<td class="tamano10"><strong>BLOQUE:</strong></td>';
        $html .= '<td class="tamano10">' . $modelBloque->name . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $profesorName = $modelProfesor->last_name . ' ' . $modelProfesor->x_first_name;

        $html .= $this->genera_cuadro_notas($clase, $paralelo, $bloque);

        $html .= $this->genera_firmas($clase, $profesorName);

        return $html;
    }

    /**
     * PARA CUADRO DE NOTAS
     */
    private function genera_cuadro_notas($clase, $paralelo, $bloque) {

        $sentencias = new SentenciasRepInsumos();
        //$insumos = $sentencias->extrae_insumos($clase, $bloque);
        $insumos = $sentencias->extrae_insumos_v2();


        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%" cellpadding="1" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde tamano10" width="5%">Ord</td>';
        $html .= '<td class="centrarTexto conBorde tamano10">Nombres</td>';
        foreach ($insumos as $data) {
            $html .= '<td class="centrarTexto conBorde tamano7" width="9%">' . $data['nombre_grupo'] . '</td>';
        }
        $html .= '<td class="centrarTexto conBorde tamano10" width="9%">Total</td>';
        $html .= '<td class="centrarTexto conBorde tamano10" width="9%">Promedio</td>';
        $html .= '</tr>';

        $html .= $this->genera_detalle_cuadro($clase, $paralelo, $bloque, $insumos);


        $html .= '</table>';


        return $html;
    }

    /**
     * GENERA EL DETALLE DEL CUADRO
     * @param type $clase
     * @param type $paralelo
     * @param type $bloque
     * @param type $insumos
     * @return string $html con el detalle de la tabla
     */
    private function genera_detalle_cuadro($clase, $paralelo, $bloque, $insumos) {

        $sentencias2 = new \backend\models\Notas();
        $sentenciasIns = new SentenciasRepInsumos();

        $modelAlumnos = \backend\models\OpStudent::find()
                ->innerJoin('op_student_inscription', 'op_student_inscription.student_id = op_student.id')
                ->where(['parallel_id' => $paralelo, 'inscription_state' => 'M'])
                ->orderBy('op_student.last_name', 'op_student.first_name', 'op_student.middle_name')
                ->all();

        $html = '';

        $i = 0;
        foreach ($modelAlumnos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="centrarTexto conBorde tamano10">' . $i . '</td>';
            $html .= '<td class="conBorde tamano10">' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

            $total = 0;

            foreach ($insumos as $insumo) {


                $nota = $sentenciasIns->get_promedio_insumo($clase, $alumno->id, $insumo['grupo_numero'], $bloque);




                $total = $total + $nota;
                $total = number_format($total, 2);

                $html .= '<td class="centrarTexto conBorde tamano10">' . $nota . '</td>';
            }
            $html .= '<td class="centrarTexto conBorde tamano10">' . $total . '</td>';


            ////saca nota parcial
            $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
                    ->where(['estudiante_id' => $alumno->id, 'clase_id' => $clase])
                    ->one();
            
//            print_r($modelGrupo);
//            die();
            
            if ($modelGrupo) {
                $modelNotaResumen = $sentencias2->get_nota_parcial($bloque, $modelGrupo->id);
            } else {
//                               if($nota == 'error'){
                $mensaje = 'Hay ninos no asignados a las clases';
                echo '<div class="alert alert-danger">'.$mensaje.'</div>';
                die();
                
            }




            if ($modelNotaResumen) {
                $html .= '<td class="centrarTexto conBorde tamano10">' . $modelNotaResumen . '</td>';
            } else {
                $html .= '<td class="centrarTexto conBorde tamano10">-</td>';
            }



            $html .= '</tr>';
        }

        return $html;
    }

    /**
     * CALCULA EL PROMEDIO DEL INSUMO
     * @param type $alumno
     * @param type $clase
     * @param type $bloque
     * @param type $insumo
     * @return type
     */
    private function calcula_promedio_insumo($alumno, $clase, $bloque, $insumo) {

        $sentencias = new SentenciasRepInsumos();
        $notas = $sentencias->calcula_promedio_insumo($insumo, $alumno, $bloque, $clase);

        return $notas['nota'];
    }

    private function genera_firmas($clase, $profesor) {
        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto">________________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td class="centrarTexto">________________________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto tamano10">PROFESOR (A)</td>';
        $html .= '<td></td>';
        $html .= '<td class="centrarTexto tamano10">SECRETARIA</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto tamano10">' . $profesor . '</td>';
        $html .= '<td></td>';
        $html .= '<td class="centrarTexto tamano10"></td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

}
