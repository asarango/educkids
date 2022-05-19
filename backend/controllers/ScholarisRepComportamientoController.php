<?php

namespace backend\controllers;

use Yii;
use backend\models\OpStudent;
use backend\models\OpCourseParalelo;
use backend\models\ScholarisBloqueActividad;
use backend\models\OpInstitute;
use backend\models\ScholarisResumenParciales;
use backend\models\SentenciasRepNotasCurso;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepPromediosController implements the CRUD actions for ScholarisRepPromedios model.
 */
class ScholarisRepComportamientoController extends Controller {

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

        $paralelo = $_GET['paralelo'];
        $bloque = $_GET['bloque'];
        $usuario = Yii::$app->user->identity->usuario;

        $modelEstudiantes = OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(["op_student_inscription.parallel_id" => $paralelo, "op_student_inscription.inscription_state" => 'M'])
                ->orderBy("op_student.last_name", "op_student.first_name")
                ->all();

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $modelBloque = ScholarisBloqueActividad::find()
                ->where(["id" => $bloque])
                ->one();

        return $this->render('index', [
                    'modelEstudiantes' => $modelEstudiantes,
                    'modelParalelo' => $modelParalelo,
                    'modelBloque' => $modelBloque
        ]);
    }

    /*
     * Genera el pdf
     */

    public function actionPdf() {


        $paralelo = $_GET['paralelo'];
        $bloque = $_GET['bloque'];

//        echo $bloque;
//        die();

        $cabecera = $this->genera_cabecera($paralelo, $bloque);

        $pie = $this->genera_pie();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 20,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->genera_html($paralelo, $bloque);

        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));

        $mpdf->SetFooter($pie);

//        $mpdf->addPage();



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
        $cab .= '<p style="font-size:10px">CUADRO DE COMPORTAMIENTO </p>';

        $cab .= '</center>';
        $cab .= '</td>';

        $cab .= '<td class="derechaTexto tamano8">';
        $cab .= '<p style="font-size:8px; text-align: right; ">';
        $cab .= $modelParalelo->course->name . ' ' . $modelParalelo->name . '<br>';
        $cab .= $modelBloque->quimestre . ' ' . $modelBloque->name . '<br>';
        $cab .= 'AÑO LECTIVO: ' . $modelBloque->scholaris_periodo_codigo;
        $cab .= '</p>';
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

    private function genera_html($paralelo, $bloque) {

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
                  
                  .tamano8{
                    font-size: 8px;
                  }
                  
                  .conBorde {
                    border: 0.3px solid black;
                  }
                 ';
        $html .= '</style>';

        $html .= $this->cuadro_notas_cabecera($paralelo, $bloque);


        $html .= $this->genera_firmas();

        return $html;
    }

    /**
     * PARA REALIZAR LA CABECERA DE LA TABLA
     * @param type $paralelo
     * @param type $bloque
     * @return string
     */
    private function cuadro_notas_cabecera($paralelo, $bloque) {

        $sentencias = new SentenciasRepNotasCurso();

        $clases = $sentencias->muestra_clases_comportamiento($paralelo);

        $modelEstudiantes = OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(["op_student_inscription.parallel_id" => $paralelo, "op_student_inscription.inscription_state" => 'M'])
                ->orderBy("op_student.last_name", "op_student.first_name")
                ->all();


        $html = '';


        $i = 0;

        $html .= '<table width="100%" cellpadding="1" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano8 conBorde"><strong>Ord.</strong></td>';
        $html .= '<td class="tamano8 conBorde"><strong>Nombres</strong></td>';

        foreach ($clases as $clase) {

            if ($clase['promedia'] == 1) {
                $html .= '<td class="tamano8 conBorde centrarTexto"><strong>' . $clase['materia'] . '</strong></td>';
            } else {
                $html .= '<td class="tamano8 conBorde centrarTexto"><strong>* ' . $clase['materia'] . '</strong></td>';
            }
        }
//        $html .= '<td class="tamano8 conBorde"><strong>PRO</strong></td>';
        $html .= '</tr>';


        $html .= $this->cuadro_notas_detalle($modelEstudiantes, $clases, $bloque, $paralelo);


        $html .= '</table>';

        return $html;
    }

    private function cuadro_notas_detalle($modelAlumnos, $modelClases, $bloque, $paralelo) {

        $sentenciaNotas = new \backend\models\SentenciasNotas();
        $sentencia2 = new \backend\models\Notas();
        
        $modelParalelo = OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        
        $seccion = $modelParalelo->course->section0->code;       

        $html = '';

        $i = 0;
        foreach ($modelAlumnos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="tamano8 conBorde centrarTexto">' . $i . '</td>';
            $html .= '<td class="tamano8 conBorde">' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

            foreach ($modelClases as $clase) {

                $claseId = $clase['id'];

                $modelNota = ScholarisResumenParciales::find()
                        ->where(['clase_id' => $claseId, 'bloque_id' => $bloque, 'alumno_id' => $alumno->id])
                        ->one();
                
                if(isset($modelNota)){
                    
//                    $notaHomologada = $modelNota->calificacion;
                    $notaHomologada = $sentencia2->homologa_comportamiento($modelNota->calificacion, $seccion);
                    
                    $html .= '<td class="tamano8 conBorde centrarTexto">' . $notaHomologada . '</td>';
                }else{
                    $html .= '<td class="tamano8 conBorde centrarTexto">0</td>';
                }
            }


//            $notaProB = $sentenciaNotas->calcula_promedio_final_bloque($alumno->id, $bloque);

//            $html .= '<td class="tamano8 conBorde centrarTexto"><strong>' . $notaProB['nota'] . '</strong></td>';                        

            $html .= '</tr>';
        }
        
//        $html .= $this->promedios($bloque, $paralelo, $modelClases);

        return $html;
    }

    private function promedios($bloque, $paralelo, $arregloClases) {
        $sentencias = new SentenciasRepNotasCurso();
        $sentenciasNotas = new \backend\models\SentenciasNotas();
        
        $html = '';
        $html .= '<tr>';
        $html .= '<td class="tamano8 conBorde centrarTexto" colspan="2"><strong>PROMEDIOS:</strong></td>';
        
        $suma = 0;
        $cont = 0;
        
        foreach ($arregloClases as $clase){            
            $notaParalelo = $sentencias->calcula_notas_clases_parcial_paralelo($paralelo, $clase['id'], $bloque);
            $promedia = $notaParalelo['promedia'];
            
            if($promedia == 1){
                $suma = $suma + $notaParalelo['nota'];
                $cont++;
            }                        
            
            $html .= '<td class="tamano8 conBorde centrarTexto"><strong>'.$notaParalelo['nota'].'</strong></td>';
        }
        
        $final = $suma / $cont;
        $final = $sentenciasNotas->truncarNota($final,2);
        
        $html .= '<td class="tamano8 conBorde centrarTexto"><strong>'.$final.'</strong></td>';
        
        $html .= '</tr>';
        
        return $html;
        
    }

    private function genera_firmas() {
        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto">________________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td class="centrarTexto">________________________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto">DOCENTE</td>';
        $html .= '<td></td>';
        $html .= '<td class="centrarTexto">SECRETARIA</td>';
        $html .= '</tr>';

//        $html .= '<tr>';
//        $html .= '<td class="centrarTexto">djkhfjdsfk</td>';
//        $html .= '<td></td>';
//        $html .= '<td class="centrarTexto"></td>';
//        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }
    
    public function actionRecalcula(){
        
        print_r($_GET);
        
        $sentencias = new \backend\models\Notas();
        
        //$modelClases = \backend\models\ScholarisClase::find()->where(['idcurso' => $_GET['curso']])->all();
        $modelClases = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $_GET['paralelo']])->all();
        foreach ($modelClases as $clase){
            //echo $clase->id.'<br>';
            $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['clase_id' => $clase->id])->all();
            //echo '-----'. count($modelGrupo).'<br>';
            foreach ($modelGrupo as $grupo){
                //echo '-----'.$grupo->id.'<br>';
                $sentencias->actualiza_parcial($_GET['bloque'], $grupo->estudiante_id, $clase->id);
                
            }
        }
        
        return $this->redirect(['reportes-parcial/index']);
        
    }

}
