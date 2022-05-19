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
class SentenciasRepInsumos extends \yii\db\ActiveRecord {

    public function muestra_clases($paralelo, $periodo) {
        $con = \Yii::$app->db;
        $query = "select 	c.id as clase_id
		,m.id as materia_id
		,m.name as materia
                ,f.last_name
		,f.x_first_name
from	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria
                inner join op_faculty f on f.id = c.idprofesor
where	i.parallel_id = $paralelo
		and i.inscription_state = 'M'
                and c.periodo_scholaris = '$periodo'
group by c.id
		,m.id
		,m.name
                ,f.last_name
		,f.x_first_name
order by m.name";
        $resp = $con->createCommand($query)->queryAll();
        
//        echo $query;
//        die();

        return $resp;
    }

    public function muestra_clases_una($paralelo, $clase) {
        $con = \Yii::$app->db;
        $query = "select 	c.id as clase_id
		,m.id as materia_id
		,m.name as materia
                ,f.last_name
		,f.x_first_name
from	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria
                inner join op_faculty f on f.id = c.idprofesor
where	i.parallel_id = $paralelo
		and i.inscription_state = 'M'
                and c.id = $clase
group by c.id
		,m.id
		,m.name
                ,f.last_name
		,f.x_first_name
order by m.name";
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }

    
    /**
     * TOMA LOS INSUMOS AGRUPADOS DE LA CLASE
     * @param type $clase
     * @param type $bloque
     * @return type array de insumos
     * 
     */
    public function extrae_insumos($clase, $bloque) {
        $con = \Yii::$app->db;
        
        $query = "select id, nombre_nacional, orden from scholaris_tipo_actividad order by orden;";
        
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    public function extrae_insumos_v2() {
        $con = \Yii::$app->db;
        
        $query = "select 	g.nombre_grupo
                                    ,g.grupo_numero
                    from 	scholaris_tipo_actividad t
                                    inner join scholaris_grupo_orden_calificacion g on g.codigo_tipo_actividad = t.id
                    where	t.activo = true
                    order by g.grupo_numero;";
        
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    
    public function calcula_promedio_insumo($insumo, $alumno, $bloque, $clase) {
        $con = \Yii::$app->db;
        $query = "select 	trunc(avg(calificacion),2) as nota
from	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
where	c.idtipoactividad = $insumo
		and c.idalumno = $alumno
		and a.bloque_actividad_id = $bloque
		and a.paralelo_id = $clase;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }
    
    
    public function get_grupo_insumos(){
        $con = \Yii::$app->db;
        $query = "select 	g.grupo_numero
                                ,a.nombre_nacional
                from 	scholaris_tipo_actividad a 
                                inner join scholaris_grupo_orden_calificacion g on g.codigo_tipo_actividad = a.id
                where	a.activo = true
                group by g.grupo_numero,a.nombre_nacional 
                order by g.grupo_numero;";
//        echo $query;
//        die();
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    
    public function get_promedio_insumo($clase, $alumno,$grupo,$bloque){
        
        $notaMinima = ScholarisParametrosOpciones::find()->where(['codigo'=>'notaminima'])->one();
        
        $notaNormal = $this->toma_promedio_insumo_normal($clase, $alumno, $grupo, $bloque);
        
        if($notaNormal['nota'] >= $notaMinima->valor){
            $nota = $notaNormal['nota'];
            
        }else{
            $modelGrupo = ScholarisGrupoAlumnoClase::find()
                    ->where(['estudiante_id' => $alumno, 'clase_id' => $clase])
                    ->one();
            
            if(!$modelGrupo){
                return 'error';
            }
            
            $modelRefuerzo = ScholarisRefuerzo::find()
                    ->where([
                             'grupo_id' => $modelGrupo->id, 
                             'bloque_id' => $bloque,
                             'orden_calificacion' => $grupo
                            ])
                    ->one();
            
            
            if($modelRefuerzo){
                if($modelRefuerzo->nota_final > 0){
                $nota = $modelRefuerzo->nota_final;
                }else{
                    $nota = $notaNormal['nota'];
                }
                
            }else{
                $nota = $notaNormal['nota'];
            }
                        
        }
        return $nota;
        
    }


    
    public function toma_promedio_insumo_normal($clase, $alumno,$grupo,$bloque){
        $con = \Yii::$app->db;
        $query = "select 	trunc(avg(c.calificacion),2) as nota
                    from 	scholaris_calificaciones c 
                                    inner join scholaris_actividad a on a.id = c.idactividad
                    where	a.paralelo_id = $clase
                                    and idalumno = $alumno
                                    and c.grupo_numero = $grupo
                                    and a.bloque_actividad_id = $bloque;";
        $resp = $con->createCommand($query)->queryOne();
//        echo $query;
//        die();
        return $resp;
    }

}
