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
class SentenciasMallas extends \yii\db\ActiveRecord {
    
    
    public function get_materias($periodo){
        $con = Yii::$app->db;
        $query = "select 	m.id
		,concat(mat.name,' - (',s.nombre_malla,')') as materia
from 	scholaris_malla s
		inner join scholaris_malla_area a on a.malla_id = s.id
		inner join scholaris_malla_materia m on m.malla_area_id = a.id
		inner join scholaris_materia mat on mat.id = m.materia_id
where	s.periodo_id = $periodo
order by mat.name, s.nombre_malla;";               
//        echo $query;
//        die();      
        $res = $con->createCommand($query)->queryAll();      
        return $res;
    }
    
    
    public function get_materias_curriculo(){
        $con = Yii::$app->db1;
        $query = "select m.id,asi.codigo
		,asi.nombre as materia
from 	gen_malla_materia m
		inner join gen_malla_area a on a.id = m.malla_area_id
		inner join gen_subnivel s on s.id = a.subnivel_id
		inner join gen_asignaturas asi on asi.id = m.materia_id order by s.nombre;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    
}
