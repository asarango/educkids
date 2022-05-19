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
class QuitoReportesMecNormalFinalUnaController extends Controller {
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

        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();



        $mpdf->SetHtmlHeader($cabecera);

////        $mpdf->showImageErrors = true;

        $html1 = $this->reporte_quimestre($paralelo);
        $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));


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
        $html .= $model->institute->codigo_distrito.'<br>';
        $html .= $model->institute->name . '<br>';
        $html .= 'CUADRO FINAL CON SUPLETORIOS <br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA <br>';
        $html .= '</td>';
        $html .= '<td align="right"><img src="imagenes/instituto/logo/logo2.png" width="70"></td>';
        $html .= '</table>';


        return $html;
    }

    private function reporte_quimestre($paralelo) {

        $sentencias = new \backend\models\SentenciasMecNormales();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelParalelo = $sentencias->get_paralelo($paralelo);

        $modelParalelo = $sentencias->get_paralelo($paralelo);
        $curso = $modelParalelo->course_id;
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where(['template_id' => $modelParalelo->course->x_template_id])->one();
        
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
        $html .= '<td text-rotate="90" align="center" class="bordesolido arial" rowspan="2">ORD</td>';
        $html .= '<td align="center" class="bordesolido arial" rowspan="2" width="200">NOMBRES Y APELLIDOS</td>';

        $materiasN = $sentencias->get_materias($malla, 'normal');
        $i=0;
        foreach ($materiasN as $mat) {
            $html .= '<td align="center" class="bordesolido tamano8 arial" colspan="3">' . $mat['nombre'] . '</td>';
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
        foreach ($materiasN as $mat) {
            $html .= '<td align="center" class="bordesolido tamano8 arial">PQ</td>';
            $html .= '<td align="center" class="bordesolido tamano8 arial">ES</td>';
            $html .= '<td align="center" class="bordesolido tamano8 arial">PF</td>';
        }


        $html .= '</tr>';
//        
        $html .= $this->get_datos_alumnos($paralelo, $materiasN, $materiasP, $materiasC);

        $html .= '</table>';
        $html .= '<br><br>';


        $html .= '<table width="70%" class="tamano10 arial">';
        $html .= '<tr>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center">' . $modelFirmas->principal_nombre . '</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">' . $modelFirmas->secretaria_nombre . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center">'.$modelFirmas->principal_cargo.'</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">'.$modelFirmas->secretaria_cargo.'</td>';
        $html .= '</tr>';

        $html .= '</table>';



        return $html;
    }

    private function get_datos_alumnos($paralelo, $materiasN, $materiasP, $materiasC) {

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
//            echo '<pre>';
//            print_r($materiasN);
//            die();
            foreach ($materiasN as $normal) {
                
                
                $notaMat = $sentencias2->truncarNota($sentenciasMec->toma_notas_materias($normal['id'], $al['id'], $paralelo, 'final_con_mejora')/$this->escala,2);
                $notaSup = $sentencias2->truncarNota($sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'supletorio', $paralelo)/$this->escala,2);
                $notaFin = $sentencias2->truncarNota($sentenciasMec->toma_notas_materias($normal['id'], $al['id'], $paralelo, 'final_total')/$this->escala,2);

                if ($al['inscription_state'] == 'R') {
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                } else {
                    $html .= '<td class="bordesolido arial" align="center">' . $notaMat . '</td>';
                    $html .= '<td class="bordesolido arial" align="center">' . $notaSup . '</td>';
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

                if($normal['promedia']){
                    $suma = $suma + $notaFin;
                    $count++;
                }
                
            }

            $promedio = $suma / $count;
            $promedio = $sentencias2->truncarNota($promedio, $digito);
            $promedio = number_format($promedio, $digito);

            if ($countRemedial > 0) {
                $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
            } else {
                if ($al['inscription_state'] == 'R') {
                    $html .= '<td class="bordesolido arial" align="center"><strong>-</strong></td>';
                }else{
                    $html .= '<td class="bordesolido arial" align="center"><strong>' . $promedio . '</strong></td>';
                }
            }



//            
            foreach ($materiasP as $normal) {

//                $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'final_total', $paralelo);
                $notaFin = $sentenciasMec->toma_notas_materias($normal['id'], $al['id'], $paralelo, 'p6');
                
                  if($notaFin){
                    $notaFin = $notaFin;
                } else {
                    $notaFin = 0;
                }
                

                $nota = $sentencias2->homologa_cualitativas($notaFin);

                if ($al['inscription_state'] == 'R') {
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                }else{
                    $html .= '<td class="bordesolido arial" align="center">' . $nota . '</td>';
                }
            }
//            
            foreach ($materiasC as $normal) {
//                
                $notaFin = $sentenciasMec->get_nota_quimestre_v2($al['id'], $normal['id'], 'p6', $paralelo);
//                
                $nota = $sentencias2->homologa_comportamiento($notaFin, $modelParealelo->course->section0->code);

                if ($al['inscription_state'] == 'R') {
                    $html .= '<td class="bordesolido arial" align="center">-</td>';
                }else{
                    $html .= '<td class="bordesolido arial" align="center">' . $nota . '</td>';
                }
            }

            $estado = $this->devuelve_estado($promedio, $countRemedial);


            if ($al['inscription_state'] == 'M') {
                $html .= '<td class="bordesolido arial" align="center">' . $estado . '</td>';
            } else {
                $html .= '<td class="bordesolido arial" align="center">RETIRADO</td>';
            }





            $html .= '</tr>';
        }

        return $html;
    }

    private function devuelve_estado($nota, $countRemedial) {
        

        if ($countRemedial > 0) {
            $estado = 'REMEDIAL';
        } else {

            $modelMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'nominpasar'])->one();

            if ($nota < $modelMinima->valor) {
                $estado = 'PIERDE EL AÑO';
            } else {
                $estado = 'APROBADO';
            }
        }

        return $estado;
    }

}
