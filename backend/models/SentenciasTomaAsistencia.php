<?php

namespace backend\models;

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
class SentenciasTomaAsistencia extends \yii\db\ActiveRecord {

    public function ingresa_alumnos($paralelo, $tomaId) {
        $usuario = Yii::$app->user->identity->usuario;
        $fecha = date("Y-m-d H:i:s");

        $con = Yii::$app->db;
        $query = "insert into scholaris_toma_asistecia_detalle 
                    (toma_id, alumno_id, asiste, creado_por, creado_fecha, actualizado_por, actualizado_fecha)
                    select $tomaId, student_id, true
                                    ,'$usuario','$fecha','$usuario','$fecha'
                    from	op_student_inscription
                    where	parallel_id = $paralelo
                                and inscription_state = 'M'
                                and student_id not in (select alumno_id from scholaris_toma_asistecia_detalle where toma_id = $tomaId);";
//        echo $query;
//        die();
        
        $con->createCommand($query)->execute();
    }
    
    
    public function get_detalle_asistencias($tomaId, $paralelo){        
       
        $con = Yii::$app->db;
        $query = "select d.id,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,i.inscription_state
                                    ,d.asiste
                                    ,d.atraso
                                    ,d.atraso_justificado
                                    ,d.atraso_observacion_justificacion
                                    ,d.falta
                                    ,d.falta_justificada
                                    ,d.falta_observacion_justificacion
                    from 	scholaris_toma_asistecia_detalle d
                                    inner join op_student_inscription i on i.student_id = d.alumno_id
                                    inner join op_student s on s.id = i.student_id
                    where 	d.toma_id = $tomaId
                                    and i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                    order by s.last_name, s.first_name, s.middle_name;";    
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
        
    }

}
