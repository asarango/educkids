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
class ScholarisRepSabanaCualitativasController extends Controller {

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
        
               
        if($alumno){
            $clases = $sentencias->clases_alumno($alumno);
        }else{
            $clases = $sentencias->clases_paralelo($paralelo);
        }
        
        
//        foreach ($clases as $clase){
//            $sentenciasNotas->actualizaParcialesLibreta($clase['clase_id']);
//            $sentenciasNotas->calcula_promedios_clase($clase['clase_id']);
//        }
        

        $malla = $sentencias->procesarAreas($curso, $paralelo);    

        
        return $this->redirect(['pdf', "paralelo" => $paralelo, "alumno" => $alumno, 'malla' => $malla]);
    }

    
    
    
    
    
    public function actionPdf($paralelo, $alumno, $malla) {

        $sentencias = new \backend\models\SentenciasRepLibreta2;

        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();


        $modelAlmunos = OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where([
                    'op_student_inscription.parallel_id' => $paralelo
                ])
                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                ->all();

        $modelAreas = \backend\models\ScholarisMallaArea::find()
                ->where([
                            'malla_id' => $malla,
                            'tipo' => 'PROYECTOS'
                        ])
                ->orderBy("orden")
                ->all();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();

        $modelBloque = ScholarisBloqueActividad::find()
                ->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_uso' => $modelMalla->tipo_uso
                ])
                ->andFilterWhere(['IN', 'tipo_bloque', ['PARCIAL', 'EXAMEN']])
                ->orderBy("orden")
                ->all();


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


        $cabecera = $this->genera_cabecera_pdf($modelParalelo, $modelPeriodo);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;


        foreach ($modelAreas as $data) {
            
            $html = $this->cuerpo_pdf($data, $modelAlmunos, $modelBloque);
            
            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->SetFooter($pie);
            if($data->se_imprime==true){
                $mpdf->addPage();
            }

            
            $modelMateria = $sentencias->get_clases_por_area($paralelo, $data->id);
            
//            $modelMateria = \backend\models\ScholarisClase::find()
//                    ->innerJoin("scholaris_malla_materia", "scholaris_malla_materia.id = scholaris_clase.malla_materia")
//                    ->where(['scholaris_malla_materia.malla_area_id' => $data->id])
//                    ->orderBy("scholaris_malla_materia.orden")
//                    ->all();
            
                        
            foreach ($modelMateria as $dataM) {
                $html1 = $this->materia_cabecera($dataM, $modelAlmunos, $modelBloque);
                $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
//                $mpdf->SetFooter($pie);
                $mpdf->addPage();
            }
            
        }


//        $mpdf->addPage();


        $mpdf->Output('Litrerta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($modelParalelo, $modelPeriodo) {

        $html = '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td width="30%"><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';

        $html .= '<td><center>';
        $html .= '<p>' . $modelParalelo->course->xInstitute->name . '</p>';
        $html .= '<p style="font-size:10px">Sábana de calificaciones</p>';
        $html .= '</center>';

        $html .= '<td width="30%" style="text-align: right;">';
        $html .= '<p style="font-size:8px">' . $modelParalelo->course->name . ' - ' . $modelParalelo->name . '</p>';
        $html .= '<p style="font-size:7px">Año lectivo: ' . $modelPeriodo->nombre . '</p>';

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

    private function cuerpo_pdf($modelAreas, $modelAlumno, $modelBloque) {

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
                
                .fondoCeleste{
                    background: #C2EBF8;
                }
                
                .fondoPlomo{
                    background: #E4E4E4;
                }
                
                .padding2{
                    padding: 5px;
                }

                    ';
        $html .= '</style>';


        //if ($modelAreas->se_imprime == true) {
        if ($modelAreas->se_imprime == true) {
            $html .= '<table class="conBorde tamano10 fondoCeleste" width="100%">';
            $html .= '<tr>';
            $html .= '<td class="centrarTexto"><strong>MALLA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>ÁREA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>PROMEDIA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>PESO %</strong></td>';
            $html .= '<td class="centrarTexto"><strong>CUANTITATIVA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>TIPOP</strong></td>';
            $html .= '<td class="centrarTexto"><strong>ORDEN</strong></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="centrarTexto">' . $modelAreas->malla->nombre_malla . '</td>';
            $html .= '<td class="centrarTexto">' . $modelAreas->area->name . '</td>';
            //$html .= '<td class="centrarTexto">'.$modelAreas->promedia.'</td>';
            $html .= ($modelAreas->promedia == 1) ? '<td class="centrarTexto">SI</td>' : '<td class="centrarTexto">NO</td>';
            $html .= '<td class="centrarTexto">' . $modelAreas->total_porcentaje . '</td>';
            //$html .= '<td class="centrarTexto">'.$modelAreas->es_cuantitativa.'</td>';
            $html .= ($modelAreas->es_cuantitativa == 1) ? '<td class="centrarTexto">SI</td>' : '<td class="centrarTexto">NO</td>';
            $html .= '<td class="centrarTexto">' . $modelAreas->tipo . '</td>';
            $html .= '<td class="centrarTexto">' . $modelAreas->orden . '</td>';
            $html .= '</tr>';

            $html .= '</table>';
            $html .= '<br>';
            $html .= '<br>';

            $html .= $this->detalle_area($modelAreas->id, $modelAlumno, $modelBloque);
        }

//        $html .= $this->materias($modelAreas, $modelAlumno, $modelBloque);

        return $html;
    }

    private function detalle_area($areaId, $modelAlumno, $modelBloque) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $usuario = Yii::$app->user->identity->usuario;


        $html = '';
        $html .= '<table class="tamano10" width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto"><strong>ORD:</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>ESTUDIANTES:</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>QUIMESTRE I</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>QUIMESTRE II</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>FINAL</strong></td>';
        $html .= '</tr>';

        $i = 0;
        foreach ($modelAlumno as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

            $nota = $sentencias->get_nota_por_area($alumno->id, $usuario, $areaId, $modelBloque);

            $html .= '<td class="conBorde centrarTexto">' . $nota['q1'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $nota['q2'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $nota['final_ano_normal'] . '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }



    private function materia_cabecera($modelMateria, $modelALumnos, $modelBloques) {

        $html = '';

        if ($modelMateria['se_imprime'] == true) {

            $html .= '<table class="conBorde tamano10 fondoPlomo" width="100%">';
            $html .= '<tr>';
            $html .= '<td class="centrarTexto"><strong>ÁREA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>ASIGNATURA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>PROMEDIA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>PESO %</strong></td>';
            $html .= '<td class="centrarTexto"><strong>CUANTITATIVA</strong></td>';
            $html .= '<td class="centrarTexto"><strong>TIPOP</strong></td>';
            $html .= '<td class="centrarTexto"><strong>ORDEN</strong></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="centrarTexto">'.$modelMateria['area'].'</td>';
            $html .= '<td class="centrarTexto">'.$modelMateria['clase_id'].$modelMateria['materia'].'</td>';
////            $html .= '<td class="centrarTexto">'.$modelMateria->promedia.'</td>';
            $html .= ($modelMateria['promedia'] == true) ? '<td class="centrarTexto">SI</td>' : '<td class="centrarTexto">NO</td>';
            $html .= '<td class="centrarTexto">'.$modelMateria['total_porcentaje'].'</td>';
////            $html .= '<td class="centrarTexto">'.$modelMateria->es_cuantitativa.'</td>';
            $html .= ($modelMateria['es_cuantitativa'] == true) ? '<td class="centrarTexto">SI</td>' : '<td class="centrarTexto">NO</td>';
//            $html .= '<td class="centrarTexto">'.$modelMateria->mallaMateria->tipo.'</td>';
//            $html .= '<td class="centrarTexto">'.$modelMateria->mallaMateria->orden.'</td>';
            $html .= '</tr>';

            $html .= '</table>';
            $html .= '<br>';
            $html .= '<br>';

            $html .= $this->detalle_materia($modelMateria['clase_id'], $modelALumnos, $modelBloques);                        
        }

        return $html;
    }
    
    private function detalle_materia($clase, $modelAlumnos, $modelBloques){
        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentencias2 = new \backend\models\Notas();

        $usuario = Yii::$app->user->identity->usuario;


        $html = '';
        $html .= '<table class="tamano8" width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>ORD:</strong></td>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>ESTUDIANTES:</strong></td>';
        $html .= '<td class="conBorde centrarTexto" colspan="4"><strong>QUIMESTRE I</strong></td>';
        $html .= '<td class="conBorde centrarTexto" colspan="4"><strong>QUIMESTRE II</strong></td>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>FINAL</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto"><strong>P1</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P2</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P3</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>PR</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>80</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>EX</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>Q1</strong></td>';
        
        $html .= '<td class="conBorde centrarTexto"><strong>P4</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P5</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P6</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>PR</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>80</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>EX</strong></td>';
//        $html .= '<td class="conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>Q2</strong></td>';
        $html .= '</tr>';

        $i = 0;
        
        
        
        foreach ($modelAlumnos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

            
            $nota = $sentencias->get_notas_por_materia($clase, $alumno->id);
            
            if($nota['p1']){
                $p1 = $sentencias2->homologa_cualitativas($nota['p1']);
            }else{
                $p1 = $nota['p1'];
            }
            
            if($nota['p2']){
                $p2 = $sentencias2->homologa_cualitativas($nota['p2']);
            }else{
                $p2 = $nota['p2'];
            }
            
            if($nota['p3']){
                $p3 = $sentencias2->homologa_cualitativas($nota['p3']);
            }else{
                $p3 = $nota['p3'];
            }
            
            
            //QUIMESTRE 2
            if($nota['p4']){
                $p4 = $sentencias2->homologa_cualitativas($nota['p4']);
            }else{
                $p4 = $nota['p4'];
            }
            
            if($nota['p5']){
                $p5 = $sentencias2->homologa_cualitativas($nota['p5']);
            }else{
                $p5 = $nota['p5'];
            }
            
            if($nota['p6']){
                $p6 = $sentencias2->homologa_cualitativas($nota['p6']);
            }else{
                $p6 = $nota['p6'];
            }
            
            $html .= '<td class="conBorde centrarTexto padding2">' . $p1 . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p2 . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p3 . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p3 . '</td>';
            
            $html .= '<td class="conBorde centrarTexto padding2">' . $p4 . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p5. '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p6 . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p6 . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2">' . $p6 . '</td>';
            
            

            
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['q1'] . '</td>';
////            
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['p4'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['p5'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['p6'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['pr2'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['pr280'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['ex2'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['ex220'] . '</td>';
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['q2'] . '</td>';
//            
//            $html .= '<td class="conBorde centrarTexto padding2">' . $nota['final_ano_normal'] . '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

}
