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
class SentenciasPlanInicial extends \yii\db\ActiveRecord {

    
    
    public function get_ejes($codigoCurso){
        $con = Yii::$app->db1;
        $query = "select e.id
		,e.codigo
		,e.nombre
		,e.color
                from 	cur_curriculo_eje e
                        inner join gen_curso c on c.id = e.curso_id
                where	c.codigo = '$codigoCurso';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}
