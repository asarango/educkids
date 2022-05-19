<?php
namespace backend\models\diplomaphpv;

use backend\controllers\PlanificacionVerticalDiplomaController;
use backend\models\CurriculoMecBloque;
use backend\models\OpCourseTemplate;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PudPep;
use backend\models\ScholarisMateria;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

class Pdf extends \yii\db\ActiveRecord{

    private $planCabecera;
    public function __construct($cabeceraId){        
        $this->planCabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);                      
        $this->generate_pdf();
    }
    /*****  METODOS DE CONSULTA A LA BASE ****/
       
    private function select_scholaris_materia(){
        //extrae el id de la materia para el reporte
        $modelScholaris = ScholarisMateria::find($this->planCabecera->scholaris_materia_id)->one();
        return $modelScholaris;
    }
    private function select_plan_des_cab(){
        //extraer los cursos den PLAN DES CAB , enviando el id de la materia       
        $modelPdc = PlanificacionDesagregacionCabecera::find()->where([
            'scholaris_materia_id'=>$this->planCabecera->scholaris_materia_id
            ])->asArray()->all();
               
        return $modelPdc;
    }
    private function select_op_course(){        
        $arrayIdCourse = $this->select_plan_des_cab(); 
        //arreglo para capturar los registro de  op_course_template_id  
        $arryIds=array();
        foreach($arrayIdCourse as $planDC)
        {            
            array_push($arryIds,$planDC['op_course_template_id']);
        }          
        $modelOpCourse = OpCourseTemplate::find()->where(['in','id',$arryIds])->all(); 
        return $modelOpCourse;
    } 
    /*****FIN  METODOS DE CONSULTA A LA BASE ****/

    private function generate_pdf(){
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
        $cabecera = $this->cabecera();
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;  
        
        $html = $this->cuerpo();                 
        $mpdf->WriteHTML($html); 

        $piePagina=$this->piePagina();
        $mpdf->SetFooter($piePagina);      

        //$mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        $mpdf->Output();
        exit;
    }
    /****CABCERA */
    private function cabecera(){
        $html = ''; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="border" align="center" width="" >
                <font size="6">
                        <b>
                        <p>ISM</p>
                        <p>International Scholastic Model</p> 
                        </b>
                </font>                       
                </td>';
        $html .= '<td class="border" align="right" width="20%"></td>';
        $html .= '</tr>'; 
        $html .= '</table>';         
        return $html;
    }
    private function piePagina(){
        $html = ''; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center" width=""><font size="3">ELABORADO POR</font></td>';
        $html .= '<td class="border" align="center" width=""><font size="3">APROBADO POR</font></td>';
        $html .= '</tr>'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="left" width=""><font size="3">DOCENTE:</font></td>';
        $html .= '<td class="border" align="left" width=""><font size="3">NOMBRE:</font></td>';
        $html .= '</tr>'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="left" width=""><font size="3">Firma</font></td>';
        $html .= '<td class="border" align="left" width=""><font size="3">Firma</font></td>';
        $html .= '</tr>'; 
        $html .= '</table>';         
        return $html;
    }

    /***BLOQUE-MATERIA TITULOS */
    private function bloque_materia_iteracion()
    {
        $modelOpCourse = $this->select_op_course();       
        $modelScholarisM = $this->select_scholaris_materia();       
        foreach($modelOpCourse as $model)
        {
            $html='';
            $html .= '</pr>'; 
            $html .= '<table width="100%" cellspacing="0" cellpadding="3">'; 
            $html .= '<tr>'; 
            $html .= '<td class="border" align=""><font size="3"><b>DIPLOMA UNO: </b>'.$model->name.'</font></td>';
            $html .= '</tr>';
            $html .= '<tr>'; 
            $html .= '<td class="border" align=""><font size="3"><b>Asignatura: </b>'.$modelScholarisM->name.'</font></td>';
            $html .= '</tr>';
            $html .= '</table>'; 
        }
        return $html;
    }
    /*** FIN BLOQUE-MATERIA ITERACION  */

    /*** UNIDADES ITERACION  */
    private function unidades_iteracion()
    {
        $objPlanVerticalDiploma = new PlanificacionVerticalDiploma();
        $html = ''; 
        $planUnidadBloque = PlanificacionBloquesUnidad::find()->where([
            'plan_cabecera_id'=>$this->planCabecera->id
        ])
        ->orderBy(['curriculo_bloque_id'=> SORT_ASC])
        ->all();             
        
        //cabecera tabla
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">'; 
        $html .= '<tr >';  
            $html .= '<td class="border" align=""><font size="2"><b>TITULO DE LA UNIDAD</b></font></td>';
            $html .= '<td class="border" align=""><font size="2"><b>OBJETIVOS DE LA ASIGNATURA</b></font></td>';           
            $html .= '<td class="border" align=""><font size="2"><b>CONCEPTO CLAVE</b></font></td>';           
            $html .= '<td class="border" align=""><font size="2"><b>CONTENIDOS</b></font></td>';           
            $html .= '<td class="border" align=""><font size="2"><b>RELACION CON TDC</b></font></td>';           
            $html .= '<td class="border" align=""><font size="2"><b>HABILIDADES DE ENFOQUES DEL APRENDIZAJE</b></font></td>';           
            $html .= '<td class="border" align=""><font size="2"><b>OBJETIVOS DE LA EVALUACIÓN</b></font></td>';           
            $html .= '<td class="border" align=""><font size="2"><b>INSTRUMENTOS EVALUACIÓN</b></font></td>'; 
        $html .= '</tr>';        
       

        foreach($planUnidadBloque as $plaUni)
        {                
            $planVerticalDipl = PlanificacionVerticalDiploma::find()->where([
                'planificacion_bloque_unidad_id'=>$plaUni->id
            ])->asArray()->all(); 

            $bloques = CurriculoMecBloque::findOne($plaUni->curriculo_bloque_id);
            
            $html .= '<tr>';  
            $html .= '<td class="border" align=""><font size="2"><b>'.$bloques->last_name.'</b><br>'.$plaUni->unit_title.'</font></td>';//TITULO DE LA UNIDAD
            
            if (count($planVerticalDipl)>0) 
            {
                /***BUSQUEDA ITEM: RELACION TDC */          
                $arrayConsultaTdc=$objPlanVerticalDiploma->consultar_tdc_ckeck_reporte($planVerticalDipl[0]['id']);              
                 // recorremos un bucle para capturar los item re relacion tdc
                $relacion_tdc ='';
                if (count($arrayConsultaTdc)>0) 
                {
                    for ($i = 0; $i < count($arrayConsultaTdc); $i++) 
                    {
                        $relacion_tdc  = $relacion_tdc.'* '.$arrayConsultaTdc[$i]['opcion'].'<br><br>';
                    }
                }
                /***FIN BUSQUEDA ITEM: RELACION TDC */  
                
                /***BUSQUEDA ITEM: HABILIDADES  */          
                $arrayConsultaHab=$objPlanVerticalDiploma->consultar_habilidad_check_reporte($planVerticalDipl[0]['id']);              
                // recorremos un bucle para capturar los item re relacion tdc                
                $habilidades ='';
                if (count($arrayConsultaHab)>0) 
                {
                    for ($j = 0; $j < count($arrayConsultaHab); $j++) 
                    {
                        $habilidades  = $habilidades.'* '.$arrayConsultaHab[$j]['es_exploracion'].'<br><br>';                        
                    }
                }                
                /***FIN BUSQUEDA ITEM: HABILIDADES*/ 
                //echo '<pre>';
                //print_r($habilidades);                
                $html .= '<td class="border" align=""><font size="3">'.$planVerticalDipl[0]['objetivo_asignatura'].'</font></td>';   //OBJ UNIDAD
                $html .= '<td class="border" align=""><font size="3">'.$planVerticalDipl[0]['concepto_clave'].'</font></td>';        //CONCEP. CLAVE 
                $html .= '<td class="border" align=""><font size="3">'.$planVerticalDipl[0]['objetivo_evaluacion'].'</font></td>';   //CONTENIDO 
                $html .= '<td class="border" align=""><font size="3">'.$relacion_tdc.'</font></td>';           //RELACION CON TDC
                $html .= '<td class="border" align=""><font size="2">'.$habilidades.'</font></td>';  //HABILIDADES DE ENFOQUE DEL APRENDIZAJE 
                $html .= '<td class="border" align=""><font size="3">'.$planVerticalDipl[0]['objetivo_evaluacion'].'</font></td>';   //OBJ EVALUACION 
                $html .= '<td class="border" align=""><font size="3">'.$planVerticalDipl[0]['intrumentos'].'</font></td>';  //INSTRUMENTO EVALUACION 
            } 
            else
            {
                //INGRESA AQUI CUANDO NO TIENE DATOS A MOSTRAR 
                $html .= '<td class="border" align=""></td>';   //OBJ UNIDAD        
                $html .= '<td class="border" align=""></td>';   //CONCEP. CLAVE   
                $html .= '<td class="border" align=""></td>';   //CONTENIDO        
                $html .= '<td class="border" align=""></td>';   //RELACION CON TDC
                $html .= '<td class="border" align=""></td>';   //HABILIDADES DE ENFOQUE DEL APRENDIZAJE          
                $html .= '<td class="border" align=""></td>';   //OBJ EVALUACION        
                $html .= '<td class="border" align=""></td>';   //INSTRUMENTO EVALUACION
            }            
            $html .= '</tr>'; 
        } 
        $html .= '</table>'; 
        return $html;       
    }
    
    /***FIN UNIDADES ITERACION TITULOS */

    private function cuerpo(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);
        $modelScholarisM = $this->select_scholaris_materia();  
        $cursos = \backend\models\OpCourseTemplate::find()->all();        

        $html = $this->estilos();
        $texto = "La finalidad de la planificación vertical es establecer una secuencia en el aprendizaje 
                que garantice la continuidad y la progresión a lo largo de cada año del programa, e incluso 
                para los futuros estudios de los alumnos. Exploran las conexiones y relaciones entre las 
                asignaturas entre las asignaturas y refuerzan los conocimientos, la comprensión y las 
                habilidades comunes a las distintas disciplinas. (Bachillerato Internacional, 2015, pág. 62)";

        $html .= '<br/>'; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="5">'; 
        $html .= '<tr>'; 
        $html .= '  <td class="border" align="center"><font size="2">PROGRAMA DEL DIPLOMA</font></td>';
        $html .= '</tr>';
        $html .= '<tr>'; 
        $html .= '  <td class="border" align="center"><font size="2">PLANIFICACIÓN VERTICAL</font></td>';
        $html .= '</tr>';      
        $html .= '<tr>'; 
        $html .= '  <td class="border" align="center"><font size="2">AÑO ESCOLAR: '.$periodo->codigo.'</font></td>';
        $html .= '</tr>';  
        $html .= '<tr>'; 
        $html .= '  <td class="border" align=""><font size="3"><b>GRUPO DE ASIGNATURA DE: '.$modelScholarisM->name.'</br></font></td>';
        $html .= '</tr>'; 
        $html .= '<tr>'; 
        $html .= '  <td class="border" align=""><font size="2">'.$texto.'</font></td>';
        $html .= '</tr>';                
        $html .= '</table>';         
        $html .= $this->bloque_materia_iteracion();        
        $html .= $this->unidades_iteracion(); 
        return $html;
    }



    private function estilos(){
        $html = '';
        $html .= '<style>';
        $html .= '.border {
                    border: 0.1px solid black;
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
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .colorPlomo{
                    background-color:#c9cfcb;
                }
                
                .colorFinal{
                    background-color:#8ccaa0;
                }

                    ';
        $html .= '</style>';
        return $html;
    }

}


?>