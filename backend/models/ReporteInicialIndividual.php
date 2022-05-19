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
class ReporteInicialIndividual extends \yii\db\ActiveRecord {

    public function genera_reporte($alumno, $quimestre, $clase) {
        //echo $pudId;
        $instituto = Yii::$app->user->identity->instituto_defecto;
        $usuario = Yii::$app->user->identity->usuario;
        

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 10,
        ]);

        $cabecera = $this->cabecera($instituto, $alumno, $clase);
        //$pie = $this->pie();

        $mpdf->SetHeader($cabecera);
//        $mpdf->showImageErrors = true;
//
        $html = $this->html($alumno, $quimestre, $clase);

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooter('<hr>'
                . '<table width="100%" cellspacing="0">'
                . '<tr>'
                . '<td>PAGINA: {PAGENO}</td>'
                . '<td></td>'
                . '<td></td>'
                . '</tr>'
                . '</table>');

        $mpdf->Output('Reporte_PUD' . "curso" . '.pdf', 'D');
        exit;
    }

    protected function cabecera($instituto, $alumno, $clase) {

        $modelInst = OpInstitute::findOne($instituto);
        $modelClase = ScholarisClase::findOne($clase);
        $modelAlumno = OpStudent::findOne($alumno);

        $html = '';


        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%" style="font-size:8px;">'
                . '<img src="imagenes/instituto/logo/logo2.png" width="30px">'
                // . '<br>Proceso Académico'
                . '</td>';
        $html .= '<td align="center">' . $modelInst->name . '<br>'
                . 'REPORTE DE NOTAS POR CLASE<br>'
                . $modelAlumno->last_name.' '.$modelAlumno->first_name.' '.$modelAlumno->middle_name
                . '</td>';
        $html .= '<td align="right" width="20%" style="font-size:8px;">';
        $html .= $modelClase->materia->name.'<br>';
        $html .= $modelClase->profesor->last_name.' '.$modelClase->profesor->x_first_name.'<br>';
        $html .= $modelClase->curso->name.' - '.$modelClase->paralelo->name.'<br>';
        $html .='</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    protected function pie() {
        $html = '';
        $html .= '<hr>';

        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td>' . PAGENO . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    protected function html($alumno, $quimestre, $clase) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';

        $destrezas = $this->get_destrezas($alumno, $quimestre, $clase);
        
        foreach ($destrezas as $des){
            $html .= '<p align="center" class="tamano10">';
            $html .= '<strong>'.$des['codigo_destreza'].$des['destreza_desagregada'].'</strong>';
            $html .= '</p>';
            
            
            $html .= $this->detalle_destreza($des['codigo_destreza'], $alumno, $quimestre, $clase);
            $html .= '<hr>';
        }
        
        $html .= '<br><br>';
        $html .= $this->tres_firmas();
        return $html;
    }
    
    
    private function detalle_destreza($codigo, $alumno, $quimestre, $clase){
        $html = '<table class="tamano10" align="center" width="70%">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong><u>FECHA</u></strong></td>';
        $html .= '<td align="center"><strong><u>CALIFICACIÓN</u></strong></td>';
        $html .= '<td align="center"><strong><u>OBSERVACIÓN</u></strong></td>';
        $html .= '</tr>';
        
        $modelDetDetalle = $this->get_destreza_por_codigo($alumno, $quimestre, $clase, $codigo);
      
        foreach ($modelDetDetalle as $det){
            $html .= '<tr>';
            $html .= '<td align="center">'.$det['creado_fecha'].'</td>';
            $html .= '<td align="center">'.$det['calificacion'].'</td>';
            $html .= '<td align="center">'.$det['observacion'].'</td>';
            $html .= '</tr>';
            
        }
        
        $html .= '</table>';
        
        return $html;
    }
    
    private function get_destreza_por_codigo($alumno, $quimestre, $clase, $codigo){
        $con = \Yii::$app->db;
        $query = "select 	c.creado_fecha
                                ,c.calificacion
                                ,c.observacion		
                from 	scholaris_calificaciones_inicial c
                                inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                                inner join scholaris_plan_inicial i on i.id = c.plan_id
                where	g.estudiante_id = $alumno
                                and c.quimestre_id = $quimestre
                                and g.clase_id = $clase
                                and i.codigo_destreza ilike '$codigo'
                order by c.creado_fecha;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_destrezas($alumno, $quimestre, $clase){
        $con = \Yii::$app->db;
        $query = "select 	i.codigo_destreza
                                    ,i.destreza_desagregada		
                    from 	scholaris_calificaciones_inicial c
                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                                    inner join scholaris_plan_inicial i on i.id = c.plan_id
                    where	g.estudiante_id = $alumno
                                    and c.quimestre_id = $quimestre
                                    and g.clase_id = $clase
                    group by i.codigo_destreza
                                    ,i.destreza_desagregada
                    order by i.codigo_destreza
                                    ,i.destreza_desagregada;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    

    private function tres_firmas() {

        $html = '';
        $html .= '<br>';
        $html .= '<table width="60%" cellspacing="0" class="tamano8" align="center">';
        $html .= '<tr>';
        $html .= '<td width="50%" class="conBorde colorEtiqueta" colspan="" align="center"><strong>'
                . 'FIRMA DE DOCENTE</strong>'
                . '</td>';
        $html .= '</tr>';
        //$html .= '<td width="50%" class="conBorde colorEtiqueta" colspan="" align="center"><strong>'
        //        . 'FIRMA COORDINACION / COORDINATOR\'S SIGNATURE /SIGNATURE DU COORDINATEUR / TRICE</strong>'
         //       . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde" height="50"></td>';
        //$html .= '<td class="conBorde" height="50"></td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }

}
