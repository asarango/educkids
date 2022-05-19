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
class QuitoReportesMecNormalFinalController extends Controller {
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

        $paralelo = $_GET['paralelo'];

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
        $html2 = $this->reporte_quimestre2($paralelo, 9, count($materiasN), 2, $materiasN, $modelParalelo, $malla);
        $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
        $mpdf->addPage();
        $mpdf->WriteHTML($html2, $this->renderPartial('mpdf'));

        /*         * *************************************************** */

        /* salida del pdf */
        $mpdf->Output('Reporte-Quimestral' . "curso" . '.pdf', 'D');
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
        $html .= 'CUADRO FINAL CON SUPLETORIOS <br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA <br>';
        $html .= '</td>';
        $html .= '<td align="right" width="180"><img src="imagenes/instituto/logo/logo2.png" width="70"></td>';
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


        $html .= '<table width="1024" cellpadding="2" cellspacing="0" class="tamano8 arial">';
        $html .= '<tr>';
        $html .= '<td width="1%" text-rotate="90" align="center" class="bordesolido arial" rowspan="2">ORD</td>';
        $html .= '<td width="" align="center" class="bordesolido arial" rowspan="2" width="200">NOMBRES Y APELLIDOS</td>';

        $materiasN = $sentencias->get_materias($malla, 'normal');
        $i = 0;


        for ($j = 0; $j < 9; $j++) {
            //foreach ($materiasN as $mat) {
            
            if(isset($materiasN[$j]['nombre'])){
                $html .= '<td width="5" align="center" class="bordesolido tamano8 arial" colspan="3">' . $materiasN[$j]['nombre'] . '</td>';
            }else{
                $html .= '<td width="5" align="center" class="bordesolido tamano8 arial" colspan="3">NO EXISTE EN LA MALLA DE INSTITUCION</td>';
            }
            
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
            $html .= '<td align="center" class="bordesolido tamano8 arial">ES</td>';
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
            $html .= '<td align="center" class="bordesolido tamano8 arial">ES</td>';
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
            $i++;
            $html .= '<tr>';
            $html .= '<td class="bordesolido arial">' . $i . '</td>';
            $html .= '<td class="bordesolido arial">' . $al['last_name'] . ' ' . $al['first_name'] . ' ' . $al['middle_name'] . '</td>';

            $suma = 0;
            $count = 0;
            $countRemedial = 0;
//            foreach ($materiasN as $normal) {
            for ($j = 0; $j < 9; $j++) {
//                
                $notaMat = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_con_mejora', $paralelo);
                $notaSup = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'supletorio', $paralelo);
                $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_total', $paralelo);

                if ($al['inscription_state'] == 'R') {
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                } else {
                    $html .= '<td class="bordesolido arial" align="center">' . $notaMat . '</td>';
                    if($notaSup == 0){
                        $html .= '<td class="bordesolido arial" align="center">-</td>';
                    }else{
                        $html .= '<td class="bordesolido arial" align="center">' . $notaSup . '</td>';
                    }
                }


                if ($notaSup < $notaMinima->valor) {
                    if ($al['inscription_state'] == 'R') {
                        $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                    } else {
                        $html .= '<td class="bordesolido arial" align="center"><strong>' . $notaMat . '</strong></td>';
                    }

                    if ($notaSup != null) {
                        $countRemedial++;
                    }
                } else {
                    if ($al['inscription_state'] == 'R') {
                        $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                    } else {
                        $html .= '<td class="bordesolido arial" align="center"><strong>' . $notaFin . '</strong></td>';
                    }
                }

                $suma = $suma + $notaFin;
                $count++;
            }

            $html .= '</tr>';
        }

        return $html;
    }

    private function get_datos_alumnos2($paralelo, $materiasN, $materiasP, $materiasC) {

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
            $i++;
            $html .= '<tr>';
            $html .= '<td class="bordesolido arial">' . $i . '</td>';
            $html .= '<td class="bordesolido arial">' . $al['last_name'] . ' ' . $al['first_name'] . ' ' . $al['middle_name'] . '</td>';



            $suma = 0;
            $count = 0;
            $countRemedial = 0;
            //foreach ($materiasN as $normal) {
            for ($j = 0; $j < count($materiasN); $j++) {
//                
                $notaMat = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_con_mejora', $paralelo);
                $notaSup = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'supletorio', $paralelo);
                $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $materiasN[$j]['id'], 'final_total', $paralelo);

                if ($j >= 9) {
                    if ($al['inscription_state'] == 'R') {
                        $html .= '<td class="bordesolido arial" align="center">-</td>';
                        $html .= '<td class="bordesolido arial" align="center">-</td>';
                    } else {
                        $html .= '<td class="bordesolido arial" align="center">' . $notaMat . '</td>';
                        $html .= '<td class="bordesolido arial" align="center">' . $notaSup . '</td>';
                    }
                }


                if ($notaSup < $notaMinima->valor) {
                    if ($al['inscription_state'] == 'R') {
                        if ($j >= 9) {
                            $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                        }
                    } else {
                        if ($j >= 9) {
                            $html .= '<td class="bordesolido arial" align="center"><strong>' . $notaMat . '</strong></td>';
                        }
                    }

                    if ($notaSup != null) {
                        $countRemedial++;
                    }
                } else {
                    if ($al['inscription_state'] == 'R') {
                        if ($j >= 9) {
                            $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                        }
                    } else {
                        if ($j >= 9) {
                            $html .= '<td class="bordesolido arial" align="center"><strong>' . $notaFin . '</strong></td>';
                        }
                    }
                }

                $suma = $suma + $notaFin;
                $count++;
            }

            $promedio = $suma / $count;
            $promedio = $sentencias2->truncarNota($promedio, $digito);
            $promedio = number_format($promedio, $digito);

            if ($countRemedial > 0) {
                if ($j >= 9) {
                    $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                }
            } else {
                if ($al['inscription_state'] == 'R') {
                    if ($j >= 9) {
                        $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                    }
                } else {
                    if ($j >= 9) {
                        $html .= '<td class="bordesolido arial" align="center"><strong>' . $promedio . '</strong></td>';
                    }
                }
            }



//            
            foreach ($materiasP as $normal) {

                $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'final_total', $paralelo);
                
                if($notaFin){
                    $notaFin = $notaFin;
                } else {
                    $notaFin = 0;
                }

                $nota = $sentencias2->homologa_cualitativas($notaFin);

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
//            
            foreach ($materiasC as $normal) {
//                
                $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'p6', $paralelo);
//                
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

            $estado = $this->devuelve_estado($promedio, $countRemedial);


            if ($al['inscription_state'] == 'M') {
                if ($j >= 9) {
                    $html .= '<td class="bordesolido arial" align="center">' . $estado . '</td>';
                }
            } else {
                if ($j >= 9) {
                    $html .= '<td class="bordesolido arial" align="center">RETIRADO</td>';
                }
            }





            $html .= '</tr>';
        }

        return $html;
    }

    private function devuelve_estado($nota, $countRemedial) {

        if ($countRemedial > 0) {
            $estado = 'REMEDIAL';
        } else {

            $modelMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

            if ($nota < $modelMinima->valor) {
                $estado = 'PIERDE EL AÑO';
            } else {
                $estado = '';
            }
        }

        return $estado;
    }

}
