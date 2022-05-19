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
class SentenciasSeguimientoActividades extends \yii\db\ActiveRecord {

    public function get_cursos($instituto, $periodo) {

        $con = Yii::$app->db;
        $query = "select 	c.id
                            ,c.name
                            ,s.code
                    from	scholaris_periodo p
                            inner join scholaris_op_period_periodo_scholaris sop on sop.scholaris_id = p.id
                            inner join op_section s on s.period_id = sop.op_id
                            inner join op_course c on c.section = s.id
                            inner join op_period per on per.id = s.period_id
                    where	p.id = $periodo
                            and per.institute = $instituto
                            and s.code not in ('PRES')
                    order by c.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_paralelos($curso, $orden) {
        $con = Yii::$app->db;
        $query = "select 	p.id
                                ,p.name as paralelo
                                ,(select 	count(act.id) as si
                                        from 	scholaris_actividad act
                                                        inner join scholaris_clase cla on cla.id = act.paralelo_id
                                                        inner join scholaris_bloque_actividad blo on blo.id = act.bloque_actividad_id
                                        where	blo.orden = $orden
                                                        and cla.paralelo_id = p.id
                                                        and act.calificado = 'SI')
                                ,(select 	count(act.id) as no
                                        from 	scholaris_actividad act
                                                        inner join scholaris_clase cla on cla.id = act.paralelo_id
                                                        inner join scholaris_bloque_actividad blo on blo.id = act.bloque_actividad_id
                                        where	blo.orden = $orden
                                                        and cla.paralelo_id = p.id
                                                        and act.calificado = 'NO')
                from	scholaris_clase c
                                inner join op_course_paralelo p on p.id = c.paralelo_id
                where	c.idcurso = $curso
                group by p.id, p.name 
                order by p.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_bloques_agrupados($instituto, $periodo) {

        $con = Yii::$app->db;
        $query = "select 	abreviatura
                                    ,orden
                    from 	scholaris_bloque_actividad 
                    where 	instituto_id = $instituto
                                    and scholaris_periodo_codigo = '$periodo'
                    group by abreviatura, orden order by orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
