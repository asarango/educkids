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
class SentenciasPlanSemanal extends \yii\db\ActiveRecord {

    public function get_plan($usuario, $periodoCodigo, $condicion){
                
        $con = Yii::$app->db;
        $query = "select 	o.id
		,s.nombre_semana
		,s.fecha_inicio
		,s.fecha_finaliza
		,b.name as bloque
		,c.nombre
		,o.observacion
                ,s.id as semana_id
from 	scholaris_bloque_semanas_observacion o
		inner join scholaris_bloque_semanas s on s.id = o.semana_id
		inner join scholaris_bloque_actividad b on b.id = s.bloque_id
		inner join scholaris_bloque_comparte c on c.valor = cast(b.tipo_uso as integer)
where	o.usuario = $usuario
		and b.scholaris_periodo_codigo = '$periodoCodigo' 
                $condicion
order by c.nombre, s.semana_numero;";

        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}
