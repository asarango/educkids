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
class ReportesMecNormalNominaController extends Controller {
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
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 1,
        ]);
        
        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
       
////        $mpdf->showImageErrors = true;
        
        $html1 = $this->reporte_quimestre($paralelo);
        $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));


        $mpdf->Output('Nomina-Matriculados' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

        $sentencias = new \backend\models\SentenciasMecNormales();                
        $model = $sentencias->get_paralelo($paralelo);


        $html = '';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="100"></td>';
        $html .= '<td align="center" style="font-size:10px">'; 
        $html .= 'SUBSECRETARÍA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO <br>';
        $html .= $model->institute->name;
        $html .= '</td>';
        $html .= '<td align="right"><img src="imagenes/instituto/logo/logo2.png" width="70"></td>';
        $html .= '</tr>';
        $html .= '</table><br><br>';
        

        return $html;
    }

    
    private function reporte_quimestre($paralelo) {
        
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

        $html .= '<div align="center" class="tamano10">NOMINA DE MATRICULADOS<br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA</div>';

        $html .= '<table width="100%" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td>' . $modelParalelo->course->xTemplate->name . ' '.$modelParalelo->name;        
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center" class="bordesolido"><strong>N°</strong></td>';
        $html .= '<td align="center" class="bordesolido"><strong>NOMBRES Y APELLIDOS</strong></td>';
        $html .= '<td align="center" class="bordesolido"><strong>FOLIO</strong></td>';
        $html .= '<td align="center" class="bordesolido"><strong>MATRÍCULA</strong></td>';
        $html .= '<td align="center" class="bordesolido"><strong>OBSERVACIONES</strong></td>';
        $html .= '</tr>';
        
        
        $html .= $this->detalle($paralelo);
        
        
        $html .= '</table>';
        
        
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
    
    
    private function detalle($paralelo){
        $sentencias = new \backend\models\SentenciasMecNormales();
        $model = $sentencias->nomina_alumnos($paralelo);
        
        
        $html = '';
        
        $i=0;
        foreach ($model as $nom){
            $i++;
            $html.= '<tr>';
            $html.= '<td class="bordesolido" align="center">'.$i.'</td>';
            $html.= '<td class="bordesolido">'.$nom['last_name'].' '.$nom['first_name'].$nom['middle_name'].'</td>';
            $html.= '<td class="bordesolido" align="center">'.$nom['folio'].'</td>';
            $html.= '<td class="bordesolido" align="center">'.$nom['matricula'].'</td>';
            
            if($nom['estado'] == 'M'){
                $estado = '';
            }else{
                $estado = 'RETIRADO';
            }
            
            $html.= '<td class="bordesolido" align="center">'.$estado.'</td>';
            $html.= '</tr>';
        }
        
        return $html;
    }
    
    
    

}
