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
class SentenciasCursos extends \yii\db\ActiveRecord {
    
    
    public function get_cursos($periodo, $instituto){
        $con = Yii::$app->db;
        $query = "select c.id
                                    ,c.name
                    from	scholaris_periodo p
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.scholaris_id = p.id
                                    inner join op_section s on s.period_id = sop.op_id
                                    inner join op_course c on c.section = s.id
                                    inner join op_period per on per.id = s.period_id
                    where	p.id = $periodo
                                    and per.institute = $instituto
                                and c.id not in (select curso_id from scholaris_mec_v2_distribucion where curso_id = c.id limit 1)
                    order by c.name;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    
    public function get_cursos1($periodo, $instituto){
        $con = Yii::$app->db;
        $query = "select c.id
                                    ,c.name
                    from	scholaris_periodo p
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.scholaris_id = p.id
                                    inner join op_section s on s.period_id = sop.op_id
                                    inner join op_course c on c.section = s.id
                                    inner join op_period per on per.id = s.period_id
                    where	p.id = $periodo
                                    and per.institute = $instituto                                
                    order by c.name;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    public function get_cursos_distribucion_mec($malla){
        $con = Yii::$app->db;
        $query = "select c.id
		,c.name
from 	scholaris_mec_v2_distribucion d
		inner join scholaris_mec_v2_materia m on m.id = d.materia_id
		inner join scholaris_mec_v2_area a on a.id = m.malla_area_id
		inner join op_course c on c.id = d.curso_id
where	a.malla_id = $malla
group by c.id, c.name;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
  
}
