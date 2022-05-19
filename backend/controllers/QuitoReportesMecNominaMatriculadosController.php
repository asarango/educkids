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
class QuitoReportesMecNominaMatriculadosController extends Controller {
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


        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 25,
            'margin_right' => 20,
            'margin_top' => 45,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);

        $html1 = $this->nomina($paralelo);
//        
        $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
//        $mpdf->addPage();



        $mpdf->Output('Nomina-Matriculados' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

        $sentencias = new \backend\models\SentenciasMec();                
        $model = $sentencias->get_paralelo($paralelo);
        $periodo = \Yii::$app->user->identity->periodo_id;        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);


        $html = '';
        $html .= '<table width="100%" border="0">';
        $html .= '<tr>';
        $html .= '<td align="right" width="30%" class=""><img src="imagenes/instituto/mec/educacion_nuevo.png" width="150"></td>';
        $html .= '<td align="" width="40%" class=""></td>';
        $html .= '<td align="left" width="30%" class=""><img src="imagenes/instituto/logo/logo2.png" width="60"></td>';
        $html .= '</tr>';
        $html .= '<tr>';    
        $html .= '<td colspan="3" class="" align="center">';
        $html .= 'SUBSECRETARIA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO<br>';
        $html .= $model->institute->name.'<br>';
        $html .= 'NÓMINA DE MATRICULADOS<br>';
        $html .= 'Año Lectivo: '.$modelPeriodo->nombre;
        $html .= '<td>';    
        
//        $html .= '<td align="center">'; 
//        $html .= $model->institute->name.'<br>';
//         $html .= 'NÓMINA DE MATRICULADOS<br>';
//         $html .= 'AÑO LECTIVO: '.$modelPeriodo->nombre.'<br>';
//         $html .= $modelParalelo->course->name;
//        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table><br>';

        return $html;
    }

    
    private function nomina($paralelo) {
        
        $sentencias = new \backend\models\SentenciasMecNormales();
        $sentenciasMec = new \backend\models\SentenciasMec();
        $sentencias2 = new \backend\models\Notas();
        $modelAl = $sentenciasMec->get_alumnos($paralelo);
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where(['template_id' => $modelParalelo->course->x_template_id])->one();
        
               

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
        $html .= '</style>';

        
       
        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td><strong>'.$modelParalelo->course->xTemplate->name.' '.$modelParalelo->name.'</strong></td>';        
        $html .= '<td><strong></strong></td>';        
        $html .= '<td align="right"><strong>JORNADA MATUTINA<br>RÉGIMEN SIERRA</strong></td>';        
        
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<table width="100%" class="tamano10" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido" align="center"><strong>ORD</strong></td>';        
        $html .= '<td class="bordesolido" align="center"><strong>APELLIDOS Y NOMBRES<strong></td>';        
        $html .= '<td class="bordesolido" align="center"><strong>FOLIO<strong></td>';        
        $html .= '<td class="bordesolido" align="center"><strong>MATRÍCULA<strong></td>';        
        $html .= '<td class="bordesolido" align="center"><strong>OBSERVACIONES<strong></td>';        
        $html .= '</tr>';
        
        $html .= $this->detalle($paralelo);
        
        $html .= '</table>';
        
        $html .= '<br><br><br>'; 
        
        
        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>_____________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>_____________________________________</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center"><strong>'.$modelFirmas->principal_nombre.'</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>'.$modelFirmas->secretaria_nombre.'</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center"><strong>'.$modelFirmas->principal_cargo.'</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>'.$modelFirmas->secretaria_cargo.'</strong></td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        
        return $html;
    }
    
    private function detalle($paralelo){
        $sentencias = new \backend\models\SentenciasMec();      
        $modelAlumnos = $sentencias->get_matrciculados($paralelo);  
        $html = '';
        $i = 0;
        foreach ($modelAlumnos as $alumno){
            $i++;
            $html .= '<tr>';
            $html .= '<td class="bordesolido" align="center">'.$i.'</td>';
            $html .= '<td class="bordesolido" align="">'.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'].'</td>';
            $html .= '<td class="bordesolido" align="center">'.$alumno['folio'].'</td>';
            $html .= '<td class="bordesolido" align="center">'.$alumno['folio'].'</td>';
            
            if($alumno['inscription_state'] == 'M'){
                $estado = '';
            }else{
                $estado = 'RETIRADO';
            }
            
            $html .= '<td class="bordesolido" align="center">'.$estado.'</td>';
            
            $html .= '</tr>';
        }
        
        return $html;
    }
    
}
