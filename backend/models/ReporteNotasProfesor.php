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
class ReporteNotasProfesor extends \yii\db\ActiveRecord {

    public function cuadro_refuerzos($clase, $bloque){
       $data = $this->get_datos_refuerzos_clase($clase, $bloque);
              
       $html = '';
       $html .= '<p align="center"><strong>CUADRO DE REFUERZOS PARCIALES</strong></p>';
       
       $html .= '<table width="100%" style="font-size: 7px" cellspacing="0" class="table-responsive table-bordered table-condensed table-striped">';        
       $html .= '<tr>';
       $html .= '<td align="center" bgcolor="#ECE6F8"><strong>ESTUDIANTE</strong></td>';
       $html .= '<td align="center" bgcolor="#ECE6F8"><strong>TIPO INSUMO</strong></td>';
       $html .= '<td align="center" bgcolor="#ECE6F8"><strong>PROMEDIO NORMAL</strong></td>';
       $html .= '<td align="center" bgcolor="#ECE6F8"><strong>REFUERZO</strong></td>';
       $html .= '<td align="center" bgcolor="#ECE6F8"><strong>NOTA FINAL</strong></td>';
       $html .= '<td align="center" bgcolor="#ECE6F8"><strong>OBSERVACION</strong></td>';
       $html .= '</tr>';
       
       foreach ($data as $da){
           $html .= '<tr>';
           $html .= '<td>'.$da['last_name'].' '.$da['first_name'].' '.$da['middle_name'].'</td>';
           
           $tipoInsumo = ScholarisGrupoOrdenCalificacion::find()->where(['grupo_numero' => $da['orden_calificacion']])->one();
           
           $html .= '<td align="center">'.$tipoInsumo->nombre_grupo.'</td>';
           $html .= '<td align="center">'.$da['promedio_normal'].'</td>';
           $html .= '<td align="center">'.$da['nota_refuerzo'].'</td>';
           $html .= '<td align="center">'.$da['nota_final'].'</td>';
           $html .= '<td align="center">'.$da['observacion'].'</td>';
           $html .= '</tr>';
       }
       
       $html .= '</table>';
       
       
       return $html;
       
    }
    
    
    private function get_datos_refuerzos_clase($clase, $bloque){
        $con = \Yii::$app->db;
        $query = "select 	s.last_name
                                ,s.first_name
                                ,s.middle_name
                                ,r.promedio_normal
                                ,r.nota_refuerzo
                                ,r.nota_final
                                ,r.orden_calificacion
                                ,r.observacion
                from 	scholaris_refuerzo r
                                inner join scholaris_grupo_alumno_clase g on g.id = r.grupo_id
                                inner join scholaris_clase c on c.id = g.clase_id
                                inner join op_student s on s.id = g.estudiante_id
                                --inner join scholaris_grupo_orden_calificacion o on o.grupo_numero = r.orden_calificacion
                where	c.id = $clase
                                and r.bloque_id = $bloque
                                and r.nota_refuerzo > 0
                order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}
