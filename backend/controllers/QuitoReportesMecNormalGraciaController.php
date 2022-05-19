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
class QuitoReportesMecNormalGraciaController extends Controller {
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
    private $escala;
    public function actionIndex() {

        $paralelo = $_GET['paralelo'];
        $modelEscala = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'scala'
        ])->one();
        
        $this->escala = $modelEscala->valor;
        $this->hacemos_pdf($paralelo);
    }

    private function hacemos_pdf($paralelo) {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 10,
            'margin_header' => 0,
            'margin_footer' => 1,
        ]);

        /* para la cabecera */
        $cabecera = $this->genera_cabecera_pdf($paralelo);
        $mpdf->SetHtmlHeader($cabecera);


        /* para tomar los datos del detalle */
        $sentencias = new \backend\models\SentenciasMecNormales();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelParalelo = $sentencias->get_paralelo($paralelo);

        $modelParalelo = $sentencias->get_paralelo($paralelo);
        $curso = $modelParalelo->course_id;
        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $curso])->one();

        $malla = $modelMalla->malla_id;
        $materiasN = $sentencias->get_materias($malla, 'normal');


        $html1 = $this->reporte_quimestre1($paralelo);
        $html2 = $this->reporte_quimestre2($paralelo);
        $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
        $mpdf->addPage();
        $mpdf->WriteHTML($html2, $this->renderPartial('mpdf'));

        /*         * *************************************************** */

        /* salida del pdf */
        $mpdf->Output('Gracia' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

        $sentencias = new \backend\models\SentenciasMecNormales();
        $model = $sentencias->get_paralelo($paralelo);
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $html = '';
        $html .= '<table width="100%" class="tamano10 arial">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="180"></td>';
        $html .= '<td align="center">';
        $html .= 'SUBSECRETARÍA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO <br>';
        $html .= $model->institute->name . '<br>';
        $html .= 'CUADRO FINAL CON GRACIA <br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA <br>';
        $html .= '</td>';
        $html .= '<td align="right"><img src="imagenes/instituto/logo/logo2.png" width="70"></td>';
        $html .= '</table>';


        return $html;
    }

    private function reporte_quimestre1($paralelo) {

        $sentencias = new \backend\models\SentenciasMecNormales();
//
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelParalelo = $sentencias->get_paralelo($paralelo);

        $modelParalelo = $sentencias->get_paralelo($paralelo);
        $curso = $modelParalelo->course_id;
        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $curso])->one();

        $malla = $modelMalla->malla_id;


        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:6px;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

//        $html .= '<div align="center" class="tamano10">CUADRO FINAL CON SUPLETORIOS<br>';
//        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA</div>';

        $html .= '<table width="100%" class="tamano8 arial">';
        $html .= '<tr>';
        $html .= '<td>' . $modelParalelo->course->xTemplate->name . ' ' . $modelParalelo->name;
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<table width="100%" cellpadding="2" cellspacing="0" class="tamano8 arial">';
        $html .= '<tr>';
        $html .= '<td width="1%" text-rotate="90" align="center" class="bordesolido arial" rowspan="2">ORD</td>';
        $html .= '<td width="36%" align="center" class="bordesolido arial" rowspan="2" width="200">NOMBRES Y APELLIDOS</td>';

        $materiasN = $sentencias->get_materias($malla, 'normal');
        $i = 0;


        for ($j = 0; $j < 9; $j++) {
            //foreach ($materiasN as $mat) {
            $html .= '<td width="7%" align="center" class="bordesolido tamano8 arial" colspan="3">' . $materiasN[$j]['nombre'] . '</td>';
        }


        $html .= '</tr>';



        /*
         * SEGUNDA LINEA DE LA TABLA
         */

        $html .= '<tr>';
        //$materiasN = $sentencias->get_materias($malla, 'normal');
//        foreach ($materiasN as $mat) {
        for ($j = 0; $j < 9; $j++) {
            $html .= '<td align="center" class="bordesolido tamano8 arial">PQ</td>';
            $html .= '<td align="center" class="bordesolido tamano8 arial">ER</td>';
            $html .= '<td align="center" class="bordesolido tamano8 arial">PF</td>';
        }


        $html .= '</tr>';
//        
        $html .= $this->get_datos_alumnos1($paralelo, $materiasN);

        $html .= '</table>';
        $html .= '<br><br>';


        $html .= '<table width="70%" class="tamano10 arial">';
        $html .= '<tr>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center">' . $modelParalelo->course->xInstitute->rector . '</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">' . $modelParalelo->course->xInstitute->secretario . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center">RECTORA</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">SECRETARIA</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function reporte_quimestre2($paralelo) {

        $sentencias = new \backend\models\SentenciasMecNormales();
//
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelParalelo = $sentencias->get_paralelo($paralelo);

        $modelParalelo = $sentencias->get_paralelo($paralelo);
        $curso = $modelParalelo->course_id;
        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $curso])->one();

        $malla = $modelMalla->malla_id;


        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:6px;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

//        $html .= '<div align="center" class="tamano10">CUADRO FINAL CON SUPLETORIOS<br>';
//        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA</div>';

        $html .= '<table width="100%" class="tamano8 arial">';
        $html .= '<tr>';
        $html .= '<td>' . $modelParalelo->course->xTemplate->name . ' ' . $modelParalelo->name;
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<table width="" cellpadding="2" cellspacing="0" class="tamano8 arial">';
        $html .= '<tr>';
        $html .= '<td width="" text-rotate="90" align="center" class="bordesolido arial" rowspan="2">ORD</td>';
        $html .= '<td width="" align="center" class="bordesolido arial" rowspan="2" width="200">NOMBRES Y APELLIDOS</td>';

        $materiasN = $sentencias->get_materias($malla, 'normal');
        $i = 0;



        //foreach ($materiasN as $mat) {
        for ($j = 0; $j < count($materiasN); $j++) {
            if ($j >= 9) {
                $html .= '<td align="center" class="bordesolido tamano8 arial" colspan="3">' . $materiasN[$j]['nombre'] . '</td>';
            }
        }

        $html .= '<td text-rotate="90" align="center" class="bordesolido arial" rowspan="2">PROMEDIO</td>';
        $materiasP = $sentencias->get_materias($malla, 'proyectos');
        foreach ($materiasP as $mat) {
            $html .= '<td align="center" class="bordesolido arial" rowspan="2">' . $mat['nombre'] . '</td>';
        }

        $materiasC = $sentencias->get_materias($malla, 'comportamiento');
        foreach ($materiasC as $mat) {
            $html .= '<td text-rotate="90" align="center" class="bordesolido arial" rowspan="2">' . $mat['nombre'] . '</td>';
        }


        $html .= '<td align="center" class="bordesolido arial" rowspan="2">OBSERVACIÓN</td>';
        $html .= '</tr>';



        /*
         * SEGUNDA LINEA DE LA TABLA
         */

        $html .= '<tr>';
        //$materiasN = $sentencias->get_materias($malla, 'normal');
        //foreach ($materiasN as $mat) {
        for ($j = 9; $j < count($materiasN); $j++) {
            $html .= '<td align="center" class="bordesolido tamano8 arial">PQ</td>';
            $html .= '<td align="center" class="bordesolido tamano8 arial">EG</td>';
            $html .= '<td align="center" class="bordesolido tamano8 arial">PF</td>';
        }

        $html .= '</tr>';
        $html .= $this->get_datos_alumnos2($paralelo, $materiasN, $materiasP, $materiasC);

        $html .= '</table>';
        $html .= '<br><br>';

        $html .= '<table width="70%" class="tamano8 arial">';
        $html .= '<tr>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center">' . $modelParalelo->course->xInstitute->rector . '</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">' . $modelParalelo->course->xInstitute->secretario . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center">RECTORA</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">SECRETARIA</td>';
        $html .= '</tr>';

        $html .= '</table>';



        return $html;
    }

    private function get_datos_alumnos1($paralelo, $materiasN) {
        $sentencias = new \backend\models\SentenciasMecNormales();
        $sentencias2 = new \backend\models\Notas();
        $sentenciasMec = new \backend\models\SentenciasMec();
        $modelAl = $sentencias->get_alumnos($paralelo);
        $modelParealelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $periodoId = Yii::$app->user->identity->periodo_id;
        $notaMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

        $digito = 2;

        $modelRemedial = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaRemed'])
                ->one();

        $html = '';

        $i = 0;

        foreach ($modelAl as $al) {
            $total = $this->toma_total_remediales($materiasN, $al['id'], $paralelo, $notaMinima->valor);

            $i++;
            if ($total > 0) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido arial">' . $i . '</td>';
                $html .= '<td class="bordesolido arial">' . $al['last_name'] . ' ' . $al['first_name'] . ' ' . $al['middle_name'] . '</td>';

                $suma = 0;
                $cont = 0;
                $contRemedial = 0;

                for ($j = 0; $j < 9; $j++) {
                    $notaMat = $sentencias2->truncarNota($sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_con_mejora', $paralelo)/$this->escala,2);
                    $notaSup = $sentencias2->truncarNota($sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'gracia', $paralelo)/$this->escala,2);
                    $notaFin = $sentencias2->truncarNota($sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_total', $paralelo)/$this->escala,2);

                    $html .= '<td class="bordesolido arial" align="center">' . $notaMat . '</td>';
                    if ($notaSup) {
                        $html .= '<td class="bordesolido arial" align="center">' . $notaSup . '</td>';
                    } else {
                        $html .= '<td class="bordesolido arial" align="center">-</td>';
                    }

                    if ($notaSup >= $notaMinima->valor) {
                        $html .= '<td class="bordesolido arial" align="center"><strong>' . number_format($notaMinima->valor, 2) . '</strong></td>';
                    } else {
                        $html .= '<td class="bordesolido arial" align="center"><strong>' . number_format($notaFin, 2) . '</strong></td>';
                    }
                }
            }
        }

        return $html;
    }

    private function toma_total_remediales($materiasN, $alumno, $paralelo, $notaMinima) {
        $sentenciasMec = new \backend\models\SentenciasMec();
        $total = 0;

        foreach ($materiasN as $mat) {
            $nota = $sentenciasMec->get_nota_quimestre_v2($alumno, $mat['id'], 'remedial', $paralelo);
            if ($nota < $notaMinima && $nota != NULL) {
                $total++;
            }
        }

        return $total;
    }

    private function toma_promedio($materiasN, $alumno, $paralelo, $notaMinima) {
        $sentenciasMec = new \backend\models\SentenciasMec();
        $sentencias2 = new \backend\models\Notas();
        $digito = 2;

        $suma = 0;
        $cont = 0;
        foreach ($materiasN as $mat) {
            $notaM = $sentenciasMec->get_nota_quimestre_v2($alumno, $mat['id'], 'final_con_mejora', $paralelo);
            $notaE = $sentenciasMec->get_nota_quimestre_v2($alumno, $mat['id'], 'gracia', $paralelo);
            $notaF = $sentenciasMec->get_nota_quimestre_v2($alumno, $mat['id'], 'final_total', $paralelo);

            if ($notaF >= $notaMinima) {
                $suma = $suma + $notaF;
                $cont++;
            } elseif ($notaE >= $notaMinima) {
                $suma = $suma + $notaMinima;
                $cont++;
            }
        }

        $promedio = $suma / $cont;
        $promedio = $sentencias2->truncarNota($promedio, $digito);
        $promedio = number_format($promedio, $digito);

        return $promedio;
    }

    private function get_datos_alumnos2($paralelo, $materiasN, $materiasP, $materiasC) {
        $sentencias = new \backend\models\SentenciasMecNormales();
        $sentencias2 = new \backend\models\Notas();
        $sentenciasMec = new \backend\models\SentenciasMec();
        $modelAl = $sentencias->get_alumnos($paralelo);
        $modelParealelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $periodoId = Yii::$app->user->identity->periodo_id;
        $notaMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'nominpasar'])->one();

        $digito = 2;

        $modelRemedial = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaRemed'])
                ->one();

        $html = '';

        $i = 0;

        foreach ($modelAl as $al) {
            $total = $this->toma_total_remediales($materiasN, $al['id'], $paralelo, $notaMinima->valor);

            $i++;
            if ($total > 0) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido arial">' . $i . '</td>';
                $html .= '<td class="bordesolido arial">' . $al['last_name'] . ' ' . $al['first_name'] . ' ' . $al['middle_name']  . '</td>';

                $suma = 0;
                $cont = 0;
                $contRemedial = 0;

                for ($j = 0; $j < count($materiasN); $j++) {
                    $notaMat = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_con_mejora', $paralelo);
                    $notaSup = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'gracia', $paralelo);
                    $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_total', $paralelo);

                    if ($notaSup < $notaMinima->valor && $notaSup != null) {
//                    if ($notaSup < $notaMinima->valor) {
                        $contRemedial++;
                    }

                    if ($j >= 9) {
                        $html .= '<td class="bordesolido arial" align="center">' . $notaMat . '</td>';
                        if ($notaSup) {
                            $html .= '<td class="bordesolido arial" align="center">' . $notaSup . '</td>';
                        } else {
                            $html .= '<td class="bordesolido arial" align="center">-</td>';
                        }

                        if ($notaSup >= $notaMinima->valor) {
                            $html .= '<td class="bordesolido arial" align="center">' . $notaMinima->valor . '</td>';
                        } else {
                            $html .= '<td class="bordesolido arial" align="center">' . $notaFin . '</td>';
                        }
                    }
                }

                $promedio = $this->toma_promedio($materiasN, $al['id'], $paralelo, $notaMinima->valor);
                
                
                if ($contRemedial > 0) {
                    $estado = 'PIERDE EL AÑO';
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                } 
//                elseif ($contRemedial == 1) {
//                    $estado = 'GRACIA';
//                    $html .= '<td class="bordesolido arial" align="center">-</td>';
//                } 
                else {
                    $estado = '';
                    $html .= '<td class="bordesolido arial" align="center">' . $promedio . '</td>';
                }
                
                
                



                foreach ($materiasP as $normal) {

                    $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'final_total', $paralelo);

                    $nota = $sentencias2->homologa_cualitativas($notaFin);

                    $html .= '<td class="bordesolido arial" align="center">' . $nota . '</td>';
                }


//                foreach ($materiasC as $normal) {
//                    $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'final_total', $paralelo);
//                    $nota = $sentencias2->homologa_comportamiento($notaFin, $modelParealelo->course->section0->code);
//                    $html .= '<td class="bordesolido arial" align="center">' . $nota . '</td>';
//                }
                
                foreach ($materiasC as $normal) {
                    $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'p6', $paralelo);
                    $nota = $sentencias2->homologa_comportamiento($notaFin, $modelParealelo->course->section0->code);

                    if ($al['inscription_state'] == 'R') {
                        if ($j >= 9) {
                            $html .= '<td class="bordesolido arial" align="center">-</td>';
                        }
                    } else {
                        if ($j >= 9) {
                            $html .= '<td class="bordesolido arial" align="center">' . $nota . '</td>';
                        }
                    }
                }

                


                $html .= '<td class="bordesolido arial" align="center">' . $estado . '</td>';
            }
        }

        return $html;
    }

    

    

}
