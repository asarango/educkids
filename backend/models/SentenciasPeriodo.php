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
class SentenciasPeriodo extends \yii\db\ActiveRecord {
    
    
    public function get_periodo_odoo($periodoScholaris, $instituto){
        $con = Yii::$app->db;
        $query = "select p.id
from	scholaris_op_period_periodo_scholaris sop
		inner join op_period p on p.id = sop.op_id
where	sop.scholaris_id = $periodoScholaris
		and p.institute = $instituto;";
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }
            
}
