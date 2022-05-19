<?php
namespace backend\models\pudpep;

use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PudPep;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

class Pdf extends \yii\db\ActiveRecord{

    private $planUnidad;
    private $pudPep;

    public function __construct($planUnidadId){
        $this->planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $this->pudPep = PudPep::find()->where(['planificacion_bloque_unidad_id' => $planUnidadId])
                        ->orderBy("tipo", "codigo")
                        ->all();
        $this->generate_pdf();
    }

    private function generate_pdf(){
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

         $cabecera = $this->cabecera();
        //$pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        //foreach ($modelAlmunos as $data) {
        $html = $this->cuerpo();
//
        $mpdf->WriteHTML($html);
        // $mpdf->addPage();
        //}
//        $mpdf->addPage();
        //$mpdf->SetFooter($pie);

        $mpdf->Output('Planificacion-de-unidad' . "curso" . '.pdf', 'D');
        exit;
    }

    

    private function cabecera(){
        $html = ''; 
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="border" align="center" width=""></td>';
        $html .= '<td class="border" align="right" width="20%">Código: ISMR20-18</td>';
        $html .= '</tr>'; 
        $html .= '</table>'; 
        return $html;
    }

    private function cuerpo(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);

        $html = $this->estilos();

        $html .= '<table width="100%" cellspacing="0" cellpadding="10">'; 
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center"><b>ISM</b> <br> International Scholastic Model</td>';
        $html .= '</tr>';
        $html .= '<tr>'; 
        $html .= '<td class="border" align="center"><b>PLANIFICACIÓN DE UNIDAD</b> <br> AÑO ESCOLAR '.$periodo->codigo.'</td>';
        $html .= '</tr>';      
        $html .= '<tr>'; 
        $html .= '<td class="border" align=""><b>1.- DATOS INFORMATIVOS</b></td>';
        $html .= '</tr>';                
        $html .= '</table>';         
        $html .= $this->uno(); 
        $html .= $this->dos(); 
        $html .= $this->dos_detalle(); 

        return $html;
    }

    private function uno(){

        $datos = new DatosInformativos($this->planUnidad->id);
        $docentes = $datos->consulta_docentes();

        $html = '';
         $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">'; 
         $html .= '<tr>'; 
         $html .= '<td class="border" align="" width="25%">';
         $html .= '<b>DOCENTES: </b>';
         foreach($docentes as $docente){
            $html .= $docente['docente'].' - ';
         }
         $html .= '</td>';
         $html .= '<td class="border" align="center" width="25%"><b>ASIGNATURA: </b>'.$this->planUnidad->planCabecera->scholarisMateria->name.'</td>';
         $html .= '<td class="border" align="center" width="25%"><b>GRADO: </b> '.$this->planUnidad->planCabecera->opCourseTemplate->name.'</td>';
         $html .= '<td class="border" align="" width="25%"><b>PARALELOS: </b>';
         foreach($docentes as $paralelo){
            $html .= $paralelo['paralelo'].' - ';
         }
         $html .= '</td>';
         $html .= '</tr>'; 

         $html .= '<tr>'; 
         $html .= '<td class="border" rowspan="2"><b>UNIDAD Nº: </b> '.$this->planUnidad->curriculoBloque->code.'</td>'; 
         $html .= '<td class="border">';
         $html .= '<b>TÍTULO DE LA UNIDAD:</b> '.$this->planUnidad->unit_title;
         $html .= '</td>';

        //para consultar fechas
        
        $html .= '<td class="border"><b>FECHA DE COMIENZO DE LA UNIDAD:</b></td>';
        $html .= '<td class="border"><b>FECHA QUE TERMINA LA UNIDAD:</b></td>';
         //fin de cnsulta de fechas
         $html .= '</tr>'; 
         $html .= '<tr>'; 
         $html .= '<td class="border"><b>EJES DE LA UNIDAD: </b>';
         $html .= '<ul>';
         $html .= '<li>Eje 1</li>';
         $html .= '<li>Eje 2</li>';
         $html .= '<li>Eje 3</li>';
         $html .= '<li>Eje 4</li>';
         $html .= '<li>Eje 5</li>';
         $html .= '</ul>';
         $html .= '</td>'; 

         $fechas = $datos->consulta_fechas_bloque();
         $html .= '<td class="border" align="center">'.$fechas['desde'].'</td>'; 
         $html .= '<td class="border" align="center">'.$fechas['hasta'].'</td>'; 
         $html .= '</tr>';
         $html .= '</table>'; 

         $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
         $html .= '<tr>';
         $html .= '<td class="border"><b>OBJETIVOS DE LA UNIDAD:</b><br>';
         $html .= '<ul>';
         
         foreach($this->pudPep as $pud){
            if($pud->tipo == 'objetivos_generales'){
                $html .= '<li>'.$pud->codigo.' '.$pud->contenido.'</li>';
            }            
         }

         $html .= '</ul>';


         $html .= '</td>';
         $html .= '</tr>';
         $html .= '</table>';

        return $html;
    }

    private function dos(){
        $criterios = PlanificacionDesagregacionCriteriosEvaluacion::find()->where([
            'bloque_unidad_id' => $this->planUnidad->id
        ])
        ->orderBy('id')
        ->all();

        $html = '';
         $html .= '<table class="" width="100%" cellspacing="0" cellpadding="5">'; 
         $html .= '<tr>'; 
         $html .= '<td class="border" align=""><b>2.- PLAN DE UNIDAD</b></td>';
         $html .= '</tr>';
         $html .= '</table>';

         $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">'; 
         $html .= '<tr>'; 
         $html .= '<td class="border" align=""><b>CRITERIOS DE EVALUACIÓN</b>';
         foreach( $criterios as $criterio ){
            $html .= '<p><b>'.$criterio->criterioEvaluacion->code.'</b>'.$criterio->criterioEvaluacion->description.'</p><br>';
         }
         $html .= '</td>';
         $html .= '</tr>';         
         $html .= '</table>';

         return $html;
    }

    private function dos_detalle(){
        $html = '';
         $html .= '<table class="my-text-small" width="100%" cellspacing="0" cellpadding="5">'; 
         $html .= '<tr>'; 
         $html .= '<td class="border" align="center" rowspan="2">';
         $html .= '¿Qué van a aprender?<br>';
         $html .= '<b>DESTREZAS CON CRITERIOS DE DESEMPEÑO</b>';
         $html .= '</td>';
         
         $html .= '<td class="border" align="center" rowspan="2">';
         $html .= '¿Cómo vas a aprender?<br>';
         $html .= '<b>ACTIVIDADES DE APRENDIZAJE</b>';
         $html .= '</td>';

         $html .= '<td class="border" rowspan="2" align="center">';         
         $html .= '<b>RECURSOS</b>';
         $html .= '</td>';

         $html .= '<td class="border" colspan="2" align="center">';         
         $html .= '¿Qué y cómo evaluar?<br>';
         $html .= '<b>EVALUACIÓN</b>';
         $html .= '</td>'; 

         $html .= '</tr>';
         
         $html .= '<tr>';
         $html .= '<td class="border" align="center"><b>Indicadores de la Evaluación</b></td>';         
         $html .= '<td class="border" align="center"><b>Técnicas e instrumentos de Evaluación</b></td>';
         $html .= '</tr>';
         
         $html .= '<tr>';
         foreach($this->pudPep as $pud){
            if($pud->tipo == 'indicador'){
                $html .= '<tr>';
                $html .= '<td class="border" align="center">'.$this->recorre_destrezas($pud->id).'</td>';
                $html .= '<td class="border" align="center">'.$this->recorre_ada($pud->id).'</td>';
                $html .= '<td class="border" align="">'.$this->recorre_recursos($pud->id).'</td>';               
                $html .= '<td class="border" align="">'.$pud->codigo.' '.$pud->contenido.'</td>';
                $html .= '<td class="border" align="">'.$this->recorre_tecnicas_instrumentos($pud->id).'</td>';
                $html .= '</tr>';
            }
         }
         $html .= '</tr>';         
         $html .= '</table>';         

         return $html;
    }


    /**
     * PARA TOMAR LAS TECNICAS E INTRUMENTOS
     */
    private function recorre_tecnicas_instrumentos($indicadorId){
        $html = '';
        $categorias = $this->consulta_tecnicas_categorias($indicadorId);

        foreach($categorias as $cat){
            $html .= '<b>'.$cat['catego'].'</b>';

            
            $tecnicas = $this->consulta_tecnicas($cat['categoria'], $indicadorId);
            $html .= '<ul>';
                foreach($tecnicas as $tecnica){
                    $html .= '<li>'.$tecnica['opcion'].'</li>';
                }
            $html .= '</ul>';   
            
        }
        return $html;

    } 

    private function consulta_tecnicas_categorias($indicadorId){
        $unidadId = $this->planUnidad->id;
        $con = Yii::$app->db;
        $query = "select 	op.categoria
                            ,case
                                when op.categoria =  'eval-formativa' then 'EVALUACIÓN FORMATIVA'
                                when op.categoria =  'eval-sumativa' then 'EVALUACIÓN SUMATIVA'
                            end as catego
                    from 	pud_pep pep
                            inner join planificacion_opciones op on op.id = cast(pep.codigo as int)
                    where 	pep.planificacion_bloque_unidad_id = $unidadId
                            and pep.pertenece_indicador_id = $indicadorId
                            and pep.tipo = 'TECNICA-INSTRUMENTO'
                    group by op.categoria;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_tecnicas($categoria, $perteneceIndicadorId){
        $unidadId = $this->planUnidad->id;

        $con = Yii::$app->db;
        $query = "select 	op.opcion 
        from 	pud_pep pep
                inner join planificacion_opciones op on op.id = cast(pep.codigo as int)
        where 	pep.planificacion_bloque_unidad_id = $unidadId
                and pep.tipo = 'TECNICA-INSTRUMENTO'
                and pep.pertenece_indicador_id = $perteneceIndicadorId
                and op.categoria = '$categoria'
        order by op.opcion";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }



     /*************************fin de tecnicas e instrumentos *************************** */


    /**
     * PARA TOMAR LAS ACTIVIDADES DE APRENDIZAJE
     */
    private function recorre_ada($indicadorId){
        $html = '';
        $categorias = $this->consulta_ada_categorias($indicadorId);

        foreach($categorias as $cat){
            $html .= '<b>'.$cat['catego'].'</b>';

            $adas = $this->consulta_ada($cat['categoria'], $indicadorId);
            $html .= '<ul>';
                foreach($adas as $ada){
                    $html .= '<li>'.$ada['opcion'].'</li>';
                }
            $html .= '</ul>';
            
        }

        return $html;

    } 

    private function consulta_ada_categorias($indicadorId){
        $unidadId = $this->planUnidad->id;
        $con = Yii::$app->db;
        $query = "select 	op.categoria
                            ,case
                                when op.categoria =  'aprend-induccion' then 'Aprendizaje por inducción'
                                when op.categoria =  'aprend-proyecto' then 'Aprendizaje basado en proyectos'
                                when op.categoria =  'aprend-colaboativo' then 'Aprendizaje Colaborativo'
                            end as catego
                    from 	pud_pep pep
                            inner join planificacion_opciones op on op.id = cast(pep.codigo as int)
                    where 	pep.planificacion_bloque_unidad_id = $unidadId
                            and pep.pertenece_indicador_id = $indicadorId
                            and pep.tipo = 'ACTV-APRENDIZAJE'
                    group by op.categoria;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_ada($categoria, $perteneceIndicadorId){
        $unidadId = $this->planUnidad->id;

        $con = Yii::$app->db;
        $query = "select 	op.opcion 
        from 	pud_pep pep
                inner join planificacion_opciones op on op.id = cast(pep.codigo as int)
        where 	pep.planificacion_bloque_unidad_id = $unidadId
                and pep.tipo = 'ACTV-APRENDIZAJE'
                and pep.pertenece_indicador_id = $perteneceIndicadorId
                and op.categoria = '$categoria'
        order by op.opcion";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    /****************FIN DE ACTIVIDADES DE APRENDIZAJE******************************* */

    private function recorre_recursos($indicadorId){
        $html = '';
        $categorias = $this->consulta_categoria_recursos($indicadorId);
        
        foreach($categorias as $cat){
            $html .= '<b>'.$cat['categoria'].'</b>';

            $recursos = $this->consulta_recursos($cat['categoria'], $indicadorId);
            $html .= '<ul>';
                foreach($recursos as $recurso){
                    $html .= '<li>'.$recurso['opcion'].'</li>';
                }
            $html .= '</ul>';
        }

        return $html;

    }

    private function consulta_recursos($categoria, $perteneceIndicadorId){
        $unidadId = $this->planUnidad->id;

        $con = Yii::$app->db;
        $query = "select 	op.opcion 
        from 	pud_pep pep
                inner join planificacion_opciones op on op.id = cast(pep.codigo as int)
        where 	pep.planificacion_bloque_unidad_id = $unidadId
                and pep.tipo = 'RECURSO'
                and pep.pertenece_indicador_id = $perteneceIndicadorId
                and op.categoria = '$categoria'
        order by op.opcion";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function consulta_categoria_recursos($indicadorPepId){
        $con = Yii::$app->db;
        $query = "select 	op.categoria 
        from 	pud_pep pep
                inner join planificacion_opciones op on op.id = cast(pep.codigo as int)
        where 	pep.pertenece_indicador_id = $indicadorPepId
                and pep.tipo = 'RECURSO'
        group by op.categoria;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function recorre_destrezas($indicadorId){
        $html = '';
        $html .= '<ul>';
        $destrezas = PudPep::find()->where([
            'tipo' => 'destreza',
            'pertenece_indicador_id' => $indicadorId
        ])->all();            

            foreach($destrezas as $destreza){
                $html .= '<li>'.$destreza->contenido.'</li>';        
            }


        $html .= '</ul>';

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