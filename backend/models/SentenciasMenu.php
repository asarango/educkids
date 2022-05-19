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
class SentenciasMenu extends \yii\db\ActiveRecord {
    
    public function get_no_asignados($rol, $menuId){
        $con = Yii::$app->db;
        $query = "select id, menu_id, operacion, nombre 
                    from	operacion o
                    where  o.menu_id = $menuId
                                    and o.id not in (select operacion_id from rol_operacion where rol_id = $rol) "
                . "order by nombre; ";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    public function get_asignados($rol, $menuId){
        $con = Yii::$app->db;
        $query = "select r.operacion_id
                                ,r.rol_id
                                ,o.nombre
                from 	rol_operacion r
                                inner join operacion o on o.id = r.operacion_id
                where	r.rol_id = $rol
                                and o.menu_id = $menuId;";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    

}
