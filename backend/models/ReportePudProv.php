<?php

namespace backend\models;
use Mpdf\Mpdf;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class ReportePudProv extends \yii\db\ActiveRecord {

    public function genera_reporte($pudId){
        //echo $pudId;
        $modelPud = \backend\models\ScholarisPlanPud::findOne($pudId);
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 35,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 10,
        ]);

        $cabecera = $this->cabecera($modelPud);
        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->html($modelPud);
        //$html = 'ola k ase';

        //$mpdf->WriteHTML($html, $this->renderPartial('/frontend/view/reporte-pud/mpdf'));
        $mpdf->WriteHTML($html);


        $mpdf->Output('Reporte_PUD' . "curso" . '.pdf', 'D');
        exit;
    }

    protected function cabecera($modelPud) {
        $html = '';

        $html.= '<table width="100%" cellspacing="0">';
        $html.= '<tr>';
        $html.= '<td align="center">';
        $html.= '<img src="imagenes/instituto/logo/sello_ministerio.jpg" width="100px" align="center">';
        $html.= '</td>';
        $html.= '</tr>';
        $html.= '</table>';

        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="30px"></td>';
        $html .= '<td align="center">' . $modelPud->clase->course->xInstitute->name . '<br>'
                . 'PLANIFICACIÓN  DE UNIDAD DIDÁCTICA (PUD)</td>';
        $html .= '<td align="center" width="20%"></td>';
        $html .= '<tr>';
        $html .= '</table>';

        return $html;
    }

    protected function html($modelPud) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';

        $html .= $this->uno_datos($modelPud);
        
        return $html;
    }

    private function uno_datos($modelPud) {

        $modelEval = ScholarisPlanPudDetalle::find()
                        ->where(['pud_id' => $modelPud->id, 'tipo' => 'evaluacion'])
                        ->orderBy('id')
                        ->all();

        $html = '';
        
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr><td class="conBorde colorEtiqueta" colspan="9" align="center"><strong>1. DATOS INFORMATIVOS</strong></td></tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="2"><strong>NOMBRE DEL DOCENTE:</strong></td>';
        $html .= '<td class="conBorde" colspan="3">' . $modelPud->clase->profesor->last_name . ' ' . $modelPud->clase->profesor->x_first_name . '</td>';        
        $html .= '<td class="conBorde colorEtiqueta" colspan="2"><strong>FECHA:</strong></td>';
        $html .= '<td class="conBorde" colspan="2">' . date("Y-m-d") . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>AREA:</strong></td>';
        $html .= '<td class="conBorde" colspan="2">' . $modelPud->clase->materia->area->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>GRADO/CURSO:</strong></td>';
        $html .= '<td class="conBorde" colspan="3">' . $modelPud->clase->course->name.' '.$modelPud->clase->paralelo->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>Año Lectivo:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->clase->periodo_scholaris . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>  ASIGNATURA:</strong></td>';
        $html .= '<td class="conBorde" colspan="2">' . $modelPud->clase->materia->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>UNIDAD N°:</strong></td>';
        $html .= '<td class="conBorde" colspan="2">' . $modelPud->bloque->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>TIEMPO:</strong></td>';
        $html .= '<td class="conBorde" colspan="2">' . $modelPud->total_semanas.' - '.$modelPud->total_periodos . '</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="2"><strong>UNIDAD DIDÁCTICA:</strong></td>';
        $html .= '<td class="conBorde" colspan="7">' . $modelPud->titulo . '</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="2"><strong>OBJETIVO DE LA UNIDAD:</strong></td>';
        $html .= '<td class="conBorde" colspan="7">' . $modelPud->objetivo_unidad . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="2"><strong>CRITERIOS DE EVALUACIÓN:</strong></td>';
        $html .= '<td class="conBorde" colspan="7">';
        foreach($modelEval as $eva){
            $html .= $eva->contenido;
            $html .= '<br>';
        }
        $html .= '</td>';
        $html .= '</tr>';

        $html .= $this->dos_planificacion($modelPud);
        $html .= $this->tres_adaptaciones($modelPud);
        $html .= $this->cuarto_observaciones($modelPud);
        $html .= $this->firmas($modelPud);

        $html .= '</table>';

        return $html;
    }

    private function dos_planificacion($modelPud) {
        $html = '';
        $html .= '<tr><td class="conBorde colorEtiqueta" colspan="9" align="center"><strong>2. PLANIFICACIÓN</strong></td></tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align="center" rowspan="2">¿Qué van a aprender?<br><strong>DESTREZA CON CRITERIOS DE DESEMPEÑO</strong></td>';        
        $html .= '<td class="conBorde colorEtiqueta" align="center" rowspan="2">
        ¿Como van a aprender?<br><strong>ACTIVIDADES DE APRENDIZAJE</strong>
        <br>(Estrategias Metodológicas)</td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center" rowspan="2"><strong>RECURSOS</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center" colspan="4">¿Qué y cómo evaluar?<strong>PLAN DE EVALUACIÓN</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center" colspan="2"><strong>CALIFICACIÓN</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>Indicadores de Evaluación de la unidad</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>ACTIVIDAD DE LAS EVALUACIÓN Y/O PRODUCTO</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>Técnicas e Instrumentos de evaluación</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>EVALUADOR</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>FORMATIVA</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>SUMATIVA</strong></td>';
        $html .= '</tr>';
        
        
        $modelDestreza = ScholarisPlanPudDetalle::find()
        ->where(['pud_id' => $modelPud->id, 'tipo' => 'destreza'])
        ->orderBy('id')->all();

        foreach($modelDestreza as $destreza){
            $modelActividades = $this->get_actividades($destreza->id);

            /*echo '<pre>';
            print_r($modelActividades[0]['id']);
            die();
*/
            if($modelActividades){
            $totalActividades = count($modelActividades);
            $html .= '<tr>';
            $html .= '<td rowspan="'.$totalActividades.'" class="conBorde">'.$destreza->codigo.' '.$destreza->contenido.'</td>';
            /*para los momentos didacticos toma el arreglo 0 */
            $html .= '<td class="conBorde">'.$modelActividades[0]['nombre'].'</td>';

            /*para recursos del arreglo 0*/
            $html .= '<td class="conBorde">';
                $modelRec0 = ScholarisActividadRecursos::find()
                ->where(['actividad_id' => $modelActividades[0]['id'],'tipo_codigo' => 'RECURSO'])
                ->all();
                foreach($modelRec0 as $rec){
                    $html .= $rec->nombre.'|';
                }
            $html .= '</td>';

            /*Indicadores de evaluacion*/
            $html .= '<td rowspan="'.$totalActividades.'" class="conBorde">'.$destreza->codigo.' '.$destreza->contenido.'</td>';
            
            /*para los titulos de las actividades */
            $html .= '<td class="conBorde">'.$modelActividades[0]['title'].'</td>';

            /*para tecnicas e instrumentos del arreglo 0*/
            $html .= '<td class="conBorde">';
                $modelRec0 = ScholarisActividadRecursos::find()
                ->where(['actividad_id' => $modelActividades[0]['id']])
                ->andWhere(['in', 'tipo_codigo',['TECNICA','INSTRUMENTO']])
                ->orderBy('tipo_codigo')
                ->all();
                foreach($modelRec0 as $rec){
                    $html .= $rec->nombre.'|';
                }
            $html .= '</td>';

            /*Evaluador*/
            $html .= '<td rowspan="'.$totalActividades.'" class="conBorde"></td>';

            /*FORMATIVA o SUMATIVA */
            if($modelActividades[0]['formativa_sumativa'] == 'F'){
                $html .= '<td class="conBorde" align="center">X</td>';
                $html .= '<td class="conBorde" align="center"></td>';
                
            }else{
                $html .= '<td class="conBorde" align="center"></td>';
                $html .= '<td class="conBorde" align="center">X</td>';
            }
            

            $html .= '</tr>';

            /*para los momentos didacticos toma desde el arreglo 1 */
            /*hecho para que cuadre en los rows */
            for($i=1; $i < count($modelActividades); $i++){
                $html .= '<tr>';
                $html .= '<td class="conBorde">'.$modelActividades[$i]['nombre'].'</td>';
                
                /*para los recursos*/
                $html .= '<td class="conBorde">';
                $modelRec0 = ScholarisActividadRecursos::find()
                ->where(['actividad_id' => $modelActividades[$i]['id'],'tipo_codigo' => 'RECURSO'])
                ->all();
                foreach($modelRec0 as $rec){
                    $html .= $rec->nombre.'|';
                }
                $html .= '</td>';

                /*para titulos del arreglo desde 1*/
                $html .= '<td class="conBorde">'.$modelActividades[$i]['title'].'</td>';

                /*para las tecnicas e instrumentos*/
                $html .= '<td class="conBorde">';
                $modelRec0 = ScholarisActividadRecursos::find()
                ->where(['actividad_id' => $modelActividades[$i]['id']])
                ->andWhere(['in', 'tipo_codigo',['TECNICA','INSTRUMENTO']])
                ->orderBy('tipo_codigo')
                ->all();
                foreach($modelRec0 as $rec){
                    $html .= $rec->nombre.'|';
                }
                $html .= '</td>';

                /*formativas o sumativas*/
                if($modelActividades[$i]['formativa_sumativa'] == 'F'){
                    $html .= '<td class="conBorde" align="center">X</td>';
                    $html .= '<td class="conBorde" align="center"></td>';
                    
                }else{
                    $html .= '<td class="conBorde" align="center"></td>';
                    $html .= '<td class="conBorde" align="center">X</td>';
                }
                

                $html .= '</tr>';
            }

            
            
            }else{
                $html .= '<tr>';
                $html .= '<td class="conBorde">'.$destreza->codigo.' '.$destreza->contenido.'</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '<td class="conBorde" bgcolor="#FF0000">NO PLANIFICADO</td>';
                $html .= '</tr>';
            }
        }
           
        return $html;
    }


    private function get_actividades($destrezaId){
        $con = Yii::$app->db;
        $query = "select 	m.id
		,m.nombre
		,a.title
		,a.calificado
        ,a.formativa_sumativa
        ,a.id as actividad_id
from 	scholaris_actividad a
		inner join scholaris_momentos_academicos m on m.id = a.momento_id
where	a.destreza_id = $destrezaId
		order by m.id, a.id;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    private function tres_adaptaciones($modelPud) {

        $html = '';
        $html .= '<tr><td class="conBorde colorEtiqueta" colspan="9" align="center"><strong>3. *Adaptaciones curriculares: En este apartado se deben desarrollar las adaptaciones curriculares para todos los estudiantes con N.E.E asociadas o no a la discapacidad.
        </strong></td></tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="3" colspan="2" align="center"><strong>Especificación de la necesidad educativa</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="7" align="center"><strong>Especificación de la adaptación a ser aplicada</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="2" align="center"><strong>DESTREZA CON CRITERIO DE DESEMPEÑO</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="2" align="center"><strong>ACTIVIDADES DE APRENDIZAJE</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="2" align="center"><strong></strong>(Estrategias Metodológicas)De acuerdo al momento)</td>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="2" align="center"><strong>RECURSOS</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="3" align="center"><strong>EVALUACIÓN</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>Indicadores de evaluación de la Unidad</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="2" align="center"><strong>Técnicas e instrumentos de Evaluación</strong></td>';
        $html .= '</tr>';

        $modelNee = $this->get_necesidades_especiales($modelPud);
        
        if($modelNee){
            $totalRow = count($modelNee);
            $html .= '<tr>';
            $html .= '<td class="conBorde" rowspan="'.$totalRow.'" colspan="2" align="center">'.$modelNee[0]['ac_necesidad_atendida'].'</td>';
            
            $html .= '<td class="conBorde" align="">'.$modelNee[0]['contenido'].'</td>';
            $html .= '<td class="conBorde" align="">'.$modelNee[0]['title'].'</td>';
            $html .= '<td class="conBorde" rowspan="'.$totalRow.'" colspan="" align="">'.$modelNee[0]['ac_adaptacion_aplicada'].'</td>';
            $html .= '<td class="conBorde">';
            $modelRec0 = ScholarisActividadRecursos::find()
            ->where(['actividad_id' => $modelNee[0]['actividad_id'],'tipo_codigo' => 'RECURSO'])
            ->all();
            if($modelRec0){
                foreach($modelRec0 as $rec){
                    $html .= $rec->nombre.'|';
                }
            }
            
            $html .= '</td>';

            $html .= '<td class="conBorde">';
            $modelInd = ScholarisPlanPudDetalle::find()
            ->where([
                'pud_id' => $modelPud->id,
                'pertenece_a_codigo' => $modelNee[0]['codigo'],
                'tipo' => 'indicador'
            ])
            ->all();
            if($modelInd){
                foreach($modelInd as $rec){
                    $html .= $rec->contenido.'|';
                }
            }
            
            $html .= '</td>';

            /*para las tecnicas e instrumentos*/
            $html .= '<td class="conBorde" colspan="2">';
            $modelRec0 = ScholarisActividadRecursos::find()
            ->where(['actividad_id' => $modelNee[0]['actividad_id']])
            ->andWhere(['in', 'tipo_codigo',['TECNICA','INSTRUMENTO']])
            ->orderBy('tipo_codigo')
            ->all();
            foreach($modelRec0 as $rec){
                $html .= $rec->nombre.'|';
            }
            $html .= '</td>';

            $html .= '</tr>';

            for($i=1; $i<count($modelNee); $i++){
                $html .= '<tr>';
                //$html .= '<td class="conBorde" align="center">'.$modelNee[$i]['ac_necesidad_atendida'].'</td>';
                $html .= '<td class="conBorde" align="">'.$modelNee[$i]['contenido'].'</td>';
                $html .= '<td class="conBorde" align="">'.$modelNee[$i]['title'].'</td>';
                $html .= '<td class="conBorde">';
                $modelRec0 = ScholarisActividadRecursos::find()
                ->where(['actividad_id' => $modelNee[$i]['actividad_id'],'tipo_codigo' => 'RECURSO'])
                ->all();
                if($modelRec0){
                    foreach($modelRec0 as $rec){
                        $html .= $rec->nombre.'|';
                    }
                }
            
                $html .= '</td>';

                $html .= '<td class="conBorde">';
                $modelInd = ScholarisPlanPudDetalle::find()
                ->where([
                    'pud_id' => $modelPud->id,
                    'pertenece_a_codigo' => $modelNee[$i]['codigo'],
                    'tipo' => 'indicador'
                ])
                ->all();
                if($modelInd){
                    foreach($modelInd as $rec){
                        $html .= $rec->contenido.'|';
                    }
                }
                
                $html .= '</td>';

                /*para las tecnicas e instrumentos*/
            $html .= '<td class="conBorde" colspan="2">';
            $modelRec0 = ScholarisActividadRecursos::find()
            ->where(['actividad_id' => $modelNee[$i]['actividad_id']])
            ->andWhere(['in', 'tipo_codigo',['TECNICA','INSTRUMENTO']])
            ->orderBy('tipo_codigo')
            ->all();
            foreach($modelRec0 as $rec){
                $html .= $rec->nombre.'|';
            }
            $html .= '</td>';

                $html .= '</tr>';
            }
            
        }
        return $html;
    }

    private function get_necesidades_especiales($modelPud){
        $con = Yii::$app->db;
        $query = "select 	p.id
		,p.ac_necesidad_atendida
		,d.codigo
		,d.contenido
		,p.ac_adaptacion_aplicada
		,a.con_nee
		,a.grado_nee
		,a.observacion_nee
        ,a.title
        ,a.id as actividad_id
from 	scholaris_actividad a
		inner join scholaris_plan_pud_detalle d on d.id = a.destreza_id
		inner join scholaris_plan_pud p on p.id = d.pud_id
where	a.con_nee = true
		and d.pud_id = $modelPud->id order by a.id;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function cuarto_observaciones($modelPud){
        $html = '';
        $html .= '<tr><td class="conBorde colorEtiqueta" colspan="9" align="center"><strong>5. OBSERVACIONES</strong></td></tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" colspan="9">'.$modelPud->observaciones.'</td>';
        $html .= '</tr>';
                
        return $html;
    }
    
    
    private function firmas($modelPud){
        
        $fecha = date("Y-m-d");
        
        $html = '';
        
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" width="33%" align="center"><strong>ELABORADO</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" width="34%" align="center"><strong>REVISADO</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" width="33%" align="center"><strong>APROBADO</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" align="center">'.$modelPud->clase->profesor->last_name.' '.$modelPud->clase->profesor->x_first_name.'</td>';
        $html .= '<td class="conBorde" align="center">'.$modelPud->quienRevisa->last_name.' '.$modelPud->quienRevisa->x_first_name.'</td>';
        $html .= '<td class="conBorde" align="center">'.$modelPud->quienAprueba->last_name.' '.$modelPud->quienAprueba->x_first_name.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '</tr>';
        
        /*$html .= '<tr>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '</tr>';
        */
        $html .= '</table>';
        
        return $html;
    }

    

}
