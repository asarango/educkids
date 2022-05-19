<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class ReportesMecNormalPromocionesController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//              'class' => AccessControl::className(),
//                'rules' => [
//                  [
//                      'allow' => true,
//                      'roles' => ['@'],
//                  ]  
//                ],
//            ],
//            
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//     public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//            
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
//                echo $this->render('/site/error',[
//                   'message' => "Acceso denegado. No puede ingresar a este sitio !!!", 
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Lists all PlanPlanificacion models.
     * @return mixed
     */
    public function actionIndex() {
        $sentencias = new \backend\models\SentenciasMecNormales;
        $paralelo = $_GET['paralelo'];
        $reporte = $_GET['reporte'];

        $modelAlumnos = $sentencias->get_alumnos($paralelo);
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelMallaCurso = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $modelAreasN = $sentencias->get_areas($modelMallaCurso->malla_id, 'NORMAL');
        $modelAreasP = $sentencias->get_areas($modelMallaCurso->malla_id, 'PROYECTOS');
        $modelAreasC = $sentencias->get_areas($modelMallaCurso->malla_id, 'COMPORTAMIENTO');

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

        $cabecera = $this->genera_cabecera_pdf($paralelo, $reporte);
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
//        $mpdf->showImageErrors = true;


        foreach ($modelAlumnos as $al) {
            $html = $this->reporte_quimestre($paralelo, $al, $modelAreasN, $modelAreasP, $modelAreasC);
            $mpdf->addPage();
            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
        }




        $mpdf->Output('Reporte-Quimestral' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo, $reporte) {

        $sentencias = new \backend\models\SentenciasMecNormales();
        $model = $sentencias->get_paralelo($paralelo);


        if ($reporte == 'aptitud') {
            $rep = 'APTITUD';
        } else {
            $rep = 'PROMOCIÓN';
        }

        $html = '';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="30%"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="150"></td>';
        $html .= '<td align="center">';
        $html .= '<strong>'.$model->institute->name . '</strong><br>CERTIFICADO DE ' . $rep;
        $html .= '</td>';
        $html .= '<td align="right" width="30%"><img src="imagenes/instituto/logo/logo2.png" width="100"></td>';
        $html .= '</tr>';
        $html .= '</table>,<br><br>';
        

        return $html;
    }

    private function reporte_quimestre($paralelo, $alumno, $modelAreasN, $modelAreasP, $modelAreasC) {

        $sentencias = new \backend\models\SentenciasMecNormales();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelParalelo = $sentencias->get_paralelo($paralelo);


        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '</style>';

        $html .= '<div align="center">AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA</div>';
        $html .= '<br><br>';

        $texto = '<p>';
        $texto .= 'De conformidad con lo prescrito en el Art. 197 del Reglamento General de la Ley Orgánica de la Educacion '
                . 'Intercultural y demás normativas vigentes, certifica que la estudiante ';
        $texto .= $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . ' ';
        $texto .= ' del ' . $modelParalelo->course->xTemplate->name . ' ' . $modelParalelo->name . ', ';
        $texto .= 'obtuvo las siguientes calificaciones durante el presente año lectivo.';
        $texto .= '</p>';

        $html .= $texto;

        $html .= $this->cuadro_malla($alumno['id'], $modelAreasN, $modelAreasP, $modelAreasC, $paralelo, $modelParalelo->course_id);
        
        $html .= '<p>Para certificar suscriben en unidad de acto la Rectora con la Secretaria General del Plantel.</p>';
        $html .= '<br><br>';
        
        
        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center">'.$modelParalelo->course->xInstitute->rector.'</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">'.$modelParalelo->course->xInstitute->secretario.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center">RECTOR / A</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">SECRETARIO / A</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        

        return $html;
    }

    private function cuadro_malla($alumnoId, $modelAreasN, $modelAreasP, $modelAreasC, $paralelo, $curso) {

        $html = '';
        $html .= '<table class="bordesolido tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido" align="center" rowspan="2"><strong>ÁREAS</strong></td>';
        $html .= '<td class="bordesolido" align="center" rowspan="2"><strong>ASIGNATURAS</strong></td>';
        $html .= '<td class="bordesolido" align="center" colspan="2"><strong>CALIFICACIONES</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="bordesolido" align="center">NUM</td>';
        $html .= '<td class="bordesolido" align="center">LETRAS</td>';
        $html .= '</tr>';

        $html .= $this->cuadro_detalleN($alumnoId, $modelAreasN, $paralelo, $curso);
        $html .= $this->cuadro_detalleP($alumnoId, $modelAreasP, $paralelo, $curso);
        $html .= $this->cuadro_detalleC($alumnoId, $modelAreasC, $paralelo, $curso);

        $html .= '</table>';

        return $html;
    }

    private function cuadro_detalleN($alumnoId, $modelAreasN, $paralelo, $curso) {
        $sentencias = new \backend\models\SentenciasMecNormales();
        $sentencias2 = new \backend\models\Notas();
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        $html = '';

        $suma = 0;
        $acum = 0;
        foreach ($modelAreasN as $area) {
            $modelMaterias = $sentencias->get_materias_area($paralelo, $area['id']);
            $html .= '<tr>';
            $html .= '<td class="bordesolido" rowspan="' . count($modelMaterias) . '">' . $area['area'] . '</td>';


            if (count($modelMaterias) == 1) {
                foreach ($modelMaterias as $mat) {
                    $html .= '<td class="bordesolido">' . $mat['materia'] . '</td>';
                    $modelNotas = $sentencias->get_notas_clase($alumnoId, $mat['id']);
                    if ($modelNotas['final_total']) {
                        $nota = $modelNotas['final_total'];
                        $html .= '<td class="bordesolido">' . $nota . '</td>';
                    } else {
                        $nota = $modelNotas['final_ano_normal'];
                        $html .= '<td class="bordesolido" bgcolor="#FF0000">' . $nota . '</td>';
                    }

                    $conv = $this->convertir_letras($modelNotas['final_total']);

                    $html .= '<td class="bordesolido">' . $conv . '</td>';

                    $suma = $suma + $nota;
                    $acum++;
                }
            } else {
                $html .= '<td>' . $modelMaterias[0]['materia'] . '</td>';
                $modelNotas = $sentencias->get_notas_clase($alumnoId, $modelMaterias[0]['id']);

                if ($modelNotas['final_total']) {
                    $nota = $modelNotas['final_total'];
                    $html .= '<td class="bordesolido">' . $nota . '</td>';
                } else {
                    $nota = $modelNotas['final_ano_normal'];
                    $html .= '<td class="bordesolido" bgcolor="#FF0000">' . $nota . '</td>';
                }

                $conv = $this->convertir_letras($nota);
                $html .= '<td class="bordesolido">' . $conv . '</td>';

                $suma = $suma + $nota;
                $acum++;

                for ($i = 1; $i < count($modelMaterias); $i++) {
                    $html .= '<tr>';
                    $html .= '<td class="bordesolido">' . $modelMaterias[$i]['materia'] . '</td>';
                    $modelNotas = $sentencias->get_notas_clase($alumnoId, $modelMaterias[$i]['id']);
                    if ($modelNotas['final_total']) {
                        $nota = $modelNotas['final_total'];
                        $html .= '<td class="bordesolido">' . $nota . '</td>';
                    } else {
                        $nota = $modelNotas['final_ano_normal'];
                        $html .= '<td class="bordesolido" bgcolor="#FF0000">' . $nota . '</td>';
                    }


                    $conv = $this->convertir_letras($nota);
                    $html .= '<td class="bordesolido">' . $conv . '</td>';


                    $suma = $suma + $nota;
                    $acum++;
                    $html .= '</tr>';
                }
            }

            $html .= '</tr>';
        }

        $promedio = $suma / $acum;
        $promedio = $sentencias2->truncarNota($promedio, 2);
        $con = $this->convertir_letras($promedio);
        
        $html .= '<tr>';
        $html .= '<td colspan="2" class="bordesolido">PROMEDIO GENERAL:</td>';
        $html .= '<td class="bordesolido">' . $promedio . '</td>';
        $html .= '<td class="bordesolido">' . $con . '</td>';
        $html .= '</tr>';

        return $html;
    }
    
    
    private function cuadro_detalleP($alumnoId, $modelAreasP, $paralelo, $curso) {
        if($modelAreasP){
            
        
        $sentencias = new \backend\models\SentenciasMecNormales();
        $sentencias2 = new \backend\models\Notas();
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        $html = '';

        $suma = 0;
        $acum = 0;
        foreach ($modelAreasP as $area) {
            $modelMaterias = $sentencias->get_materias_area($paralelo, $area['id']);
            $html .= '<tr>';
            $html .= '<td class="bordesolido" rowspan="' . count($modelMaterias) . '">' . $area['id'] . $area['area'] . count($modelMaterias) . '</td>';


            if (count($modelMaterias) == 1) {
                foreach ($modelMaterias as $mat) {
                    $html .= '<td class="bordesolido">' . $mat['materia'] . '</td>';
                    $modelNotas = $sentencias->get_notas_clase($alumnoId, $mat['id']);
                    if ($modelNotas['final_total']) {
                        $nota = $modelNotas['final_total'];
                        $html .= '<td class="bordesolido">' . $nota . '</td>';
                    } else {
                        $nota = $modelNotas['final_ano_normal'];
                        $html .= '<td class="bordesolido" bgcolor="#FF0000">' . $nota . '</td>';
                    }

                    $conv = $this->convertir_letras($modelNotas['final_total']);

                    $html .= '<td class="bordesolido">' . $conv . '</td>';

                    $suma = $suma + $nota;
                    $acum++;
                }
            } else {
                $html .= '<td>' . $modelMaterias[0]['materia'] . '</td>';
                $modelNotas = $sentencias->get_notas_clase($alumnoId, $modelMaterias[0]['id']);

                if ($modelNotas['final_total']) {
                    $nota = $modelNotas['final_total'];
                    $html .= '<td class="bordesolido">' . $nota . '</td>';
                } else {
                    $nota = $modelNotas['final_ano_normal'];
                    $html .= '<td class="bordesolido" bgcolor="#FF0000">' . $nota . '</td>';
                }

                $conv = $this->convertir_letras($nota);
                $html .= '<td class="bordesolido">' . $conv . '</td>';

                $suma = $suma + $nota;
                $acum++;

                for ($i = 1; $i < count($modelMaterias); $i++) {
                    $html .= '<tr>';
                    $html .= '<td>' . $modelMaterias[$i]['materia'] . '</td>';
                    $modelNotas = $sentencias->get_notas_clase($alumnoId, $modelMaterias[$i]['id']);
                    if ($modelNotas['final_total']) {
                        $nota = $modelNotas['final_total'];
                        $html .= '<td class="bordesolido">' . $nota . '</td>';
                    } else {
                        $nota = $modelNotas['final_ano_normal'];
                        $html .= '<td class="bordesolido" bgcolor="#FF0000">' . $nota . '</td>';
                    }


                    $conv = $this->convertir_letras($nota);
                    $html .= '<td class="bordesolido">' . $conv . '</td>';


                    $suma = $suma + $nota;
                    $acum++;
                    $html .= '</tr>';
                }
            }

            $html .= '</tr>';
        }

        $promedio = $suma / $acum;
        $promedio = $sentencias2->truncarNota($promedio, 2);
        $con = $this->convertir_letras($promedio);
        
        $html .= '<tr>';
        $html .= '<td colspan="2" class="bordesolido">PROMEDIO GENERAL:</td>';
        $html .= '<td class="bordesolido">' . $promedio . '</td>';
        $html .= '<td class="bordesolido">' . $con . '</td>';
        $html .= '</tr>';

        return $html;
        }
    }
    
    
    private function cuadro_detalleC($alumnoId, $modelAreasC, $paralelo, $curso) {
        $sentencias = new \backend\models\SentenciasMecNormales();
        $sentencias2 = new \backend\models\Notas();
        $sentencias3 = new \backend\models\SentenciasRepLibreta2();
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        
        $html = '';

       
        foreach ($modelAreasC as $area) {
            $modelMaterias = $sentencias->get_materias_area($paralelo, $area['id']);
            $html .= '<tr>';
//            $html .= '<td class="bordesolido" rowspan="' . count($modelMaterias) . '">' . $area['id'] . $area['area'] . count($modelMaterias) . '</td>';

           
                foreach ($modelMaterias as $mat) {
                    $html .= '<td class="bordesolido" colspan="2">' . $mat['materia'] . '</td>';
//                    $modelNotas = $sentencias->get_notas_clase($alumnoId, $mat['id']);
                    $modelNota = $sentencias3->get_notas_finales_comportamiento($alumnoId);
                    $modelNotaD = $sentencias3->get_notas_finales_comportamiento_descripcion($alumnoId);
                    $html .= '<td class="bordesolido">' . $modelNota[5] . '</td>';
                    $html .= '<td class="bordesolido">' . $modelNotaD[5] . '</td>';
                }
        }

        return $html;
    }
    
    

    private function convertir_letras($nota) {

        $sentencias = new \backend\models\SentenciasMecNormales();

        $cadenaNumero = explode(".", $nota);
        $entero = $cadenaNumero[0];
        $decimal = $cadenaNumero[1];

        $entero = $sentencias->numToLetras($entero);
        $decimal = $sentencias->decimalToLetras($decimal);


        $res = $entero . ' COMA ' . $decimal;
        return $res;
    }

}
