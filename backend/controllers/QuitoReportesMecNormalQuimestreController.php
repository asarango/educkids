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
class QuitoReportesMecNormalQuimestreController extends Controller {
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
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();
//
//
        $mpdf->SetHtmlHeader($cabecera);
////        $mpdf->showImageErrors = true;
//
//
//
        $html1 = $this->reporte_quimestre($paralelo, 'QUIMESTRE I','q1');
        $html2 = $this->reporte_quimestre($paralelo, 'QUIMESTRE II','q2');
//
        $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
        $mpdf->addPage();
        $mpdf->WriteHTML($html2, $this->renderPartial('mpdf'));


        $mpdf->Output('Reporte-Quimestral' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

        $sentencias = new \backend\models\SentenciasMec();                
        $model = $sentencias->get_paralelo($paralelo);


        $html = '';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td align="right" width="20%"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="200"></td>';
        $html .= '<td align="center" class="tamano10">SUBSECRETARÍA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO <br>'.$model->institute->name.'</td>';
        $html .= '<td align="left" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align=""></td>'; 
        $html .= '<td align="center">'; 
        
        $html .= '</td>';
        $html .= '<td align=""></td>';
        $html .= '</tr>';
        $html .= '</table><br>';

        return $html;
    }

    
    private function reporte_quimestre($paralelo, $quimestre, $campo) {
        
        $sentencias = new \backend\models\SentenciasMecNormales();
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $modelParalelo = $sentencias->get_paralelo($paralelo);
        $curso = $modelParalelo->course_id;
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where(['template_id' => $modelParalelo->course->x_template_id])->one();
        
        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id'=>$curso])->one();
        $modelQuimestre = \backend\models\ScholarisQuimestre::find()->where(['codigo' => $quimestre])->one();
        
        $malla = $modelMalla->malla_id;
        

        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= 'td {
                    border-collapse: collapse;
                    border: 1px black solid;
                  }
                  tr:nth-of-type(5) td:nth-of-type(1) {
                    visibility: hidden;
                  }
                  .rotate {
                    /* FF3.5+ */
                    -moz-transform: rotate(-90.0deg);
                    /* Opera 10.5 */
                    -o-transform: rotate(-90.0deg);
                    /* Saf3.1+, Chrome */
                    -webkit-transform: rotate(-90.0deg);
                    /* IE6,IE7 */
                    filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
                    /* IE8 */
                    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
                    /* Standard */
                    transform: rotate(-90.0deg);
                  }';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '</style>';

        $html .= '<div align="center" class="tamano10">CUADRO DE CALIFICACIONES DEL ' . $modelQuimestre->nombre . '<br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA</div>';

        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td>' . $modelParalelo->course->xTemplate->name . ' '.$modelParalelo->name;        
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<table style="font-size:8px;" cellspacing="" cellpadding="" width="100%">';
        $html .= '<tr>';
        $html .= '<td text-rotate="90" align="center" class="bordesolido" rowspan="2"><div class="rotate">ORD</div></td>';
        $html .= '<td align="center" class="bordesolido" rowspan="2">NOMBRES Y APELLIDOS</td>';
        
        

        $materiasN = $sentencias->get_materias($malla, 'normal');
        
        
        foreach ($materiasN as $mat) {
            $html .= '<td height="10" text-rotate="90" align="center" class="bordesolido tamano6">' .$mat['nombre'] . '</td>';
         }

        $html .= '<td text-rotate="90" align="center" class="bordesolido" height="30" rowspan="2"><strong>PROMEDIO</strong></td>';

        $materiasP = $sentencias->get_materias($malla, 'proyectos');
        foreach ($materiasP as $mat) {
            $html .= '<td text-rotate="90" align="center" class="bordesolido" rowspan="2">' . $mat['nombre'] . '</td>';
        }

        $materiasC = $sentencias->get_materias($malla, 'comportamiento');
        foreach ($materiasC as $mat) {
            $html .= '<td text-rotate="90" align="center" class="bordesolido" rowspan="2">' . $mat['nombre'] . '</td>';
        }


        $html .= '<td align="center" class="bordesolido" rowspan="2"><strong>OBSERVACIÓN</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        foreach ($materiasN as $mat) {
            $html .= '<td height="10" text-rotate="" align="center" class="bordesolido tamano6">PROM</td>';
         }
        $html .= '</tr>';
        
    
        $html .= $this->get_datos_alumnos($paralelo, $materiasN, $materiasP, $materiasC, $campo, $quimestre);
          
        $html .= '</table>';
        $html .= '<br><br>';
        
        
        $html .= '<table width="" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">_____________________________________</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center">'.$modelFirmas->principal_nombre.'</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">'.$modelFirmas->secretaria_nombre.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center">'.$modelFirmas->principal_cargo.'</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">'.$modelFirmas->secretaria_cargo.'</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        
        return $html;
    }
    
    
    private function get_datos_alumnos($paralelo,$materiasN,$materiasP,$materiasC, $campo, $quimestre){
        
        $sentencias = new \backend\models\SentenciasMec();
        $sentenciasLib = new \backend\models\Notas();
        $sentencias2 = new \backend\models\Notas();
        
        $modelAl = $sentencias->get_alumnos($paralelo);
        $modelParealelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $digito = 2;
        
        if($campo == 'q1'){
            $campoComportamiento = 'p3';
        }else{
            $campoComportamiento = 'p6';
        }
        
               
        $html = '';
        
        $i = 0;
        
        foreach ($modelAl as $al){
            
            $sentenciasNxx = new \backend\models\SentenciasNotasDefinitivasAlumno($al['id'], $periodoId, $paralelo);
            
            $estado = $al['inscription_state'];
            $i++;
            $html.= '<tr>';
            $html.= '<td class="bordesolido" align="center">'.$i.'</td>';
            $html.= '<td class="bordesolido">'.$al['last_name'].' '.$al['first_name'].' '.$al['middle_name'].'</td>';
            
            $suma = 0;
            $count = 0;
            foreach ($materiasN as $normal){            
                $notaMat = $sentencias2->truncarNota(($sentencias->get_nota_quimestre_v2($al['id'], $normal['id'], $campo, $paralelo)/$this->escala),2);
                if($estado == 'M'){
                    $html.= '<td class="bordesolido" align="center">'.$notaMat.'</td>';
                }else{
                    $html.= '<td class="bordesolido" align="center">-</td>';
                }
                
                
                $suma = $suma + $notaMat;
                $count++;
            }
            
            $promedio = $suma / $count;
            $promedio = $sentencias2->truncarNota($promedio, $digito);
            $promedio = number_format($promedio,$digito);
           
            if($estado == 'M'){
                $html.= '<td class="bordesolido" align="center"><strong>'.$promedio.'</strong></td>';
            }else{
                $html.= '<td class="bordesolido" align="center"><strong>-</strong></td>';
            }
            
            /*
             * fin de notas normales
             */
            
            
           $suma = 0;
            $count = 0; 
            foreach ($materiasP as $normal){
                
                $notaMat = $sentencias->get_nota_quimestre_v2($al['id'], $normal['id'], $campo, $paralelo);
                if($estado == 'M'){
                    
                    $notaHomo = $sentenciasLib->homologa_cualitativas($notaMat);
                    $html.= '<td class="bordesolido" align="center">'.$notaHomo.'</td>';                                        
                 }else{
                     $html.= '<td class="bordesolido" align="center">-</td>';
                 }
                
                $suma = $suma + $notaMat;
                $count++;
            }
            
            /*
             * fin de notas PROYECTOS
             */
            
          
            $suma = 0;
            $count = 0; 
            foreach ($materiasC as $normal){                                
                
                $notaMat = $sentencias->get_nota_quimestre_v2($al['id'], $normal['id'], $campoComportamiento, $paralelo);
//                print_r($notaMat);
//                die();
//                $html.= '<td class="bordesolido" align="center">'.$notaMat.'</td>';
                
                $notaHomo = $sentencias2->homologa_comportamiento($notaMat, $modelParealelo->course->section0->code);
                
                if($estado == 'M'){
                    $html.= '<td class="bordesolido" align="center">'.$notaHomo.'</td>';
                }else{
                    $html.= '<td class="bordesolido" align="center">-</td>';
                }
            }
            
            
            /*
             * fin de notas COMPORTAMIENTO
             */
//            
            if($estado == 'M'){
                $html.= '<td class="bordesolido" align="center"></td>';
            }else{
                $html.= '<td class="bordesolido" align="center">RETIRADO</td>';
            }
            
            
            $html.= '</tr>';
        }
        
        return $html;
    }
    
    
    
    
    
    

}
