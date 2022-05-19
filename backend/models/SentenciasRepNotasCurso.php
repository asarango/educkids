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
class SentenciasRepNotasCurso extends \yii\db\ActiveRecord {

    public function muestra_clases($paralelo, $periodo) {
        $con = \Yii::$app->db;
        $query = "select 	c.id
                                    ,m.name 
                                    ,m.abreviarura as materia
                                    ,c.promedia
                                    , mat.tipo 
                    from	op_student_inscription i
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_materia m on m.id = c.idmateria
                                    inner join scholaris_malla_materia mat on mat.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = mat.malla_area_id
                    where	i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                                    and c.periodo_scholaris = '$periodo'
                    group by c.id
                                    ,m.name
                                    ,m.abreviarura
                                    ,c.promedia
                                    ,a.orden, mat.orden
                                    , mat.tipo 
                    order by a.orden, mat.orden, c.promedia desc, m.name;";
        
//        echo $query;
//        die();
//        
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    
    public function muestra_clases_cualitativas($paralelo) {
        $con = \Yii::$app->db;
        $query = "select c.id ,m.name ,m.abreviarura as materia ,c.promedia 
                    from	op_student_inscription i 
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id 
                                    inner join scholaris_clase c on c.id = g.clase_id 
                                    inner join scholaris_materia m on m.id = c.idmateria
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia
                    where	i.parallel_id = $paralelo and i.inscription_state = 'M' 
                                    and mm.es_cuantitativa = false
                                    and mm.tipo <> 'COMPORTAMIENTO'
                    group by c.id ,m.name ,m.abreviarura ,c.promedia 
                    order by m.name;";
        
//        echo $query;
//        die();
//        
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    public function muestra_clases_comportamiento($paralelo) {
        $con = \Yii::$app->db;
        $query = "select c.id ,m.name ,m.abreviarura as materia ,c.promedia 
                    from	op_student_inscription i 
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id 
                                    inner join scholaris_clase c on c.id = g.clase_id 
                                    inner join scholaris_materia m on m.id = c.idmateria
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia
                    where	i.parallel_id = $paralelo and i.inscription_state = 'M' 
                                    and mm.es_cuantitativa = false
                                    and mm.tipo = 'COMPORTAMIENTO'
                    group by c.id ,m.name ,m.abreviarura ,c.promedia 
                    order by m.name;";
        
//        echo $query;
//        die();
//        
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    
    
    
    public function calcula_notas_clases_parcial_paralelo($paralelo, $clase, $bloque) {
        $con = \Yii::$app->db;
        $query = "select 	trunc(avg(p.calificacion),2) as nota
		,c.promedia
from	scholaris_resumen_parciales p
		inner join op_student_inscription i on i.student_id = p.alumno_id
		inner join scholaris_clase c on c.id = p.clase_id
where	i.parallel_id = $paralelo
		and p.clase_id = $clase
		and p.bloque_id = $bloque
		and i.inscription_state = 'M'
group by c.promedia;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }
    
    
}
