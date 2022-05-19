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
class SentenciasAsignaturas extends \yii\db\ActiveRecord {
    
    
    public function get_bloque_con_fecha($uso, $fecha, $codigo){
        $con = Yii::$app->db;
        $query = "";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res['id'];
    }
    
  
}
