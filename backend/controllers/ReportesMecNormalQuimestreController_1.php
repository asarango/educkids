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
class ReportesMecNormalQuimestreController extends Controller {
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
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();
//
//
        $mpdf->SetHeader($cabecera);
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


//        foreach ($alumno as $data) {
//            
//            $html = $this->genera_cuerpo_pdf($data, $paralelo);
//
//            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
//            $mpdf->addPage();
//        }
//        $mpdf->addPage();
//        $mpdf->SetFooter($pie);

        $mpdf->Output('Reporte-Quimestral' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

        $sentencias = new \backend\models\SentenciasMec();                
        $model = $sentencias->get_paralelo($paralelo);


        $html = '';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="100"></td>';
        $html .= '<td align="center">'; 
        $html .= 'SUBSECRETARÍA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO <br>';
        $html .= $model->institute->name;
        $html .= '</td>';
        $html .= '<td align="right"><img src="imagenes/instituto/logo/logo2.png" width="100"></td>';
        $html .= '</tr>';
        $html .= '</table><br>';

        return $html;
    }

    
    private function reporte_quimestre($paralelo, $quimestre, $campo) {
        
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

        $html .= '<div align="center" class="tamano10">CUADRO DE CALIFICACIONES DEL ' . $quimestre . '<br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>JORNADA MATUTINA</div>';

        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td>' . $modelParalelo->course->xTemplate->name . ' '.$modelParalelo->name;        
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<table width="100%" style="font-size:10px;" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td text-rotate="90" align="center" class="bordesolido">ORD</td>';
        $html .= '<td align="center" class="bordesolido">NOMBRES Y APELLIDOS</td>';

        $materiasN = $sentencias->get_materias($paralelo, 'NORMAL');
        foreach ($materiasN as $mat) {
            $html .= '<td text-rotate="90" align="center" class="bordesolido tamano8">' .$mat['materia'] . '</td>';
        }

        $html .= '<td text-rotate="90" align="center" class="bordesolido">PROMEDIO</td>';


        $materiasP = $sentencias->get_materias($paralelo, 'PROYECTOS');
        foreach ($materiasP as $mat) {
            $html .= '<td text-rotate="90" align="center" class="bordesolido">' . $mat['materia'] . '</td>';
        }

        $materiasC = $sentencias->get_materias($paralelo, 'COMPORTAMIENTO');
        foreach ($materiasC as $mat) {
            $html .= '<td text-rotate="90" align="center" class="bordesolido">' . $mat['materia'] . '</td>';
        }


        $html .= '<td text-rotate="90" align="center" class="bordesolido">OBSERVACIÓN</td>';
        $html .= '</tr>';
//        
        $html .= $this->get_datos_alumnos($paralelo, $materiasN, $materiasP, $materiasC, $campo);
          
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
    
    
    private function get_datos_alumnos($paralelo,$materiasN,$materiasP,$materiasC, $campo){
        
        $sentencias = new \backend\models\SentenciasMec();
        $sentencias2 = new \backend\models\Notas();
        $modelAl = $sentencias->get_alumnos($paralelo);
        $modelParealelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        if($campo == 'q1'){
            $campoComportamiento = 'p3';
        }else{
            $campoComportamiento = 'p6';
        }
        
               
        $html = '';
        
        $i = 0;
        foreach ($modelAl as $al){
            $i++;
            $html.= '<tr>';
            $html.= '<td class="bordesolido">'.$i.'</td>';
            $html.= '<td class="bordesolido">'.$al['last_name'].' '.$al['first_name'].' '.$al['middle_name'].'</td>';
            
            foreach ($materiasN as $normal){
                
                $notaMat = $sentencias->get_nota_materia($al['id'], $normal['clase_id'], $campo);
                $html.= '<td class="bordesolido" align="center">'.$notaMat['nota'].'</td>';
            }
            
            $nota = $sentencias->get_promedio_quimestre($al['id'], $campo, $periodoId);
            
            $html.= '<td class="bordesolido" align="center"><strong>'.$nota.'</strong></td>';
            
            foreach ($materiasP as $normal){
                
                $notaMat = $sentencias->get_nota_materia($al['id'], $normal['clase_id'], $campo);
                $html.= '<td class="bordesolido" align="center">'.$notaMat['nota'].'</td>';
            }
            
            foreach ($materiasC as $normal){                                
                
                $notaMat = $sentencias->get_nota_materia($al['id'], $normal['clase_id'], $campo);
                
                $notaHomo = $sentencias2->homologa_comportamiento($notaMat[$campoComportamiento], $modelParealelo->course->section0->code);
                
                $html.= '<td class="bordesolido" align="center">'.$notaHomo.'</td>';
            }
            
            $html.= '<td class="bordesolido" align="center"></td>';
            
            $html.= '</tr>';
        }
        
        return $html;
    }
    
    
    
    
    
    

}
