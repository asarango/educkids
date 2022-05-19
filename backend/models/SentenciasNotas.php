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
class SentenciasNotas extends \yii\db\ActiveRecord {

    public function eliminaNotasLibreta($paralelo, $usuario) {
        $con = \Yii::$app->db;
        $query = "delete from scholaris_rep_libreta where usuario = '$usuario' and paralelo_id = $paralelo";
        $con->createCommand($query)->execute();
    }

    public function insertaNotasLibreta($paralelo, $usuario, $alumno, $periodo) {

        $con = \Yii::$app->db;

        if ($alumno) {
            $query = "insert into scholaris_rep_libreta(codigo, usuario, clase_id, promedia, peso, tipo_uso_bloque, tipo, asignatura_id, asignatura, paralelo_id, alumno_id, area_id, tipo_calificacion)
select 	concat(c.id, i.student_id) as codigo
		,'$usuario' as usuario
		,c.id as clase_id
		,c.promedia
		,c.peso
		,cast(c.tipo_usu_bloque as  int)
		,'materia' as tipo
		,m.id as asignatura_id
		,m.name as asignatura
		,i.parallel_id
		,i.student_id as alumno_id
		,m.area_id
                ,m.tipo
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join op_student_inscription i on i.student_id = g.estudiante_id
where	i.parallel_id = $paralelo
		and c.periodo_scholaris = '$periodo'
		and g.estudiante_id = $alumno
order by i.student_id
		,c.promedia desc		
		,m.area_id asc;";
        } else {
            $query = "insert into scholaris_rep_libreta(codigo, usuario, clase_id, promedia, peso, tipo_uso_bloque, tipo, asignatura_id, asignatura, paralelo_id, alumno_id, area_id, tipo_calificacion)
select 	concat(c.id, i.student_id) as codigo
		,'$usuario' as usuario
		,c.id as clase_id
		,c.promedia
		,c.peso
		,cast(c.tipo_usu_bloque as  int)
		,'materia' as tipo
		,m.id as asignatura_id
		,m.name as asignatura
		,i.parallel_id
		,i.student_id as alumno_id
		,m.area_id
                ,m.tipo
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join op_student_inscription i on i.student_id = g.estudiante_id
where	i.parallel_id = $paralelo
		and c.periodo_scholaris = '$periodo'
order by i.student_id
		,c.promedia desc		
		,m.area_id asc;";
        }

        $con->createCommand($query)->execute();
    }

    public function tomaUso($paralelo, $usuario) {
        $con = \Yii::$app->db;
        $query = "select 	tipo_uso_bloque 
                    from 	scholaris_rep_libreta l
                                    inner join scholaris_clase c on c.id = l.clase_id
                    where	l.paralelo_id = $paralelo
                                    and l.usuario = '$usuario'
                    limit 1;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }

    public function nota_parcial($clase, $alumno, $orden, $periodo, $uso) {
        $con = \Yii::$app->db;
        $query = "select 	calificacion
from 	scholaris_resumen_parciales p
		inner join scholaris_bloque_actividad b on b.id = p.bloque_id
where	p.clase_id = $clase
		and p.alumno_id = $alumno
		and b.orden = $orden
		and b.scholaris_periodo_codigo = '$periodo' and b.tipo_uso = '$uso';";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }

    public function calcula_promedios($paralelo, $usuario) {
        $con = \Yii::$app->db;
        $query = "update 	scholaris_rep_libreta 
                    set
                                    pr1 = trunc((p1+p2+p3)/3,2)
                                    ,pr180 = trunc((trunc((p1+p2+p3)/3,2))*80/100,2)
                                    ,ex120 = trunc(ex1*20/100,2)
                                    ,pr2 = trunc((p4+p5+p6)/3,2)
                                    ,pr280 = trunc((trunc((p4+p5+p6)/3,2))*80/100,2)
                                    ,ex220 = trunc(ex2*20/100,2)
                    where	paralelo_id = $paralelo
                                    and usuario = '$usuario';";
        $con->createCommand($query)->execute();
    }

    public function calcula_promedios_finales($paralelo, $usuario) {
        $con = \Yii::$app->db;
        $query = "update 	scholaris_rep_libreta
set
		 q1 = pr180+ex120
		,q2 = pr180+ex220
		,nota_final = trunc((pr180+ex120+pr180+ex220)/2,2)		
where	paralelo_id = $paralelo
		and usuario = '$usuario';";
        $con->createCommand($query)->execute();
    }

    public function calcula_promedio_final_bloque($alumno, $bloque) {
        $con = \Yii::$app->db;
        $query = "select 	trunc(avg(p.calificacion),2) as nota
                    from 	scholaris_resumen_parciales p
                                    inner join scholaris_clase c on c.id = p.clase_id  
                    where 	p.alumno_id = $alumno 
                                    and p.bloque_id = $bloque
                                    and c.promedia = 1;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }

    public function truncarNota($numero, $digito) {
        $raiz = 100;
        $multiplicado = $numero * $raiz;
        $extrae = explode('.', $multiplicado);
        $entero = $extrae[0];
        $resultado = $entero / $raiz;

        return $resultado;
    }

    public function toma_nota_clase($campo, $alumno, $clase) {
        $con = \Yii::$app->db;
        $query = "select $campo as nota
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.estudiante_id = $alumno
                                    and g.clase_id = $clase;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }

    public function toma_casos($campo, $alumno, $periodo) {
        $con = \Yii::$app->db;

        $modelMinimo = ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();

        $query = "select count($campo) as total
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$periodo'
                                    and $campo < $modelMinimo->valor "
                . "and mm.tipo <> 'COMPORTAMIENTO';";
        
//        echo $query;
//        die();
        
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }

    public function toma_promedio_clase($campo, $clase) {
        $con = \Yii::$app->db;
        $query = "select trunc(avg($campo),2) as nota
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.clase_id = $clase;";
        $resp = $con->createCommand($query)->queryOne();
        return $resp;
    }

    public function toma_total_alumnos_clase($clase, $paralelo) {        
        $periodId = \Yii::$app->user->identity->periodo_id;
        $con = \Yii::$app->db;                
        $query = "select count(*) as total 
                    from 	scholaris_grupo_alumno_clase g
                                   inner join op_student_inscription i on i.student_id = g.estudiante_id 
                                   inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                    where 	g.clase_id = $clase
                                   and sop.scholaris_id = $periodId and i.inscription_state = 'M';";
        
//        echo $query;
//        die();
        $resp = $con->createCommand($query)->queryOne();
        return $resp;
    }

    public function toma_casos_bajos_clase($clase, $campo, $bajos) {

        $con = \Yii::$app->db;
        $query = "select count($campo) as total
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.clase_id = $clase
                                    and l.p3 < $bajos";
        
//        echo $query;
//        die();
        
        $resp = $con->createCommand($query)->queryOne();
        return $resp;
    }

    public function toma_casos_bajos_porcentaje_clase($clase, $campo, $bajos) {

        $con = \Yii::$app->db;
        $query = "select (count($campo) * 100)/(select count(id) as total from scholaris_grupo_alumno_clase where clase_id = $clase) ::double precision as total
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.clase_id = $clase
                                    and $campo < $bajos;";
        
//        echo $query;
//        die();
        
        $resp = $con->createCommand($query)->queryOne();
        return $resp;
    }
    
    public function toma_casos_altos_porcentaje_clase($clase, $campo, $altos) {

        $con = \Yii::$app->db;
        $query = "select (count($campo) * 100)/(select count(id) as total from scholaris_grupo_alumno_clase where clase_id = $clase)::double precision as total
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.clase_id = $clase
                                    and $campo > $altos;";
//        echo $query;
//        die();
        $resp = $con->createCommand($query)->queryOne();
        return $resp;
    }

    public function toma_casos_altos_clase($clase, $campo, $altos) {

        $con = \Yii::$app->db;
        $query = "select count($campo) as total
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.clase_id = $clase
                                    and $campo >= $altos;";
        $resp = $con->createCommand($query)->queryOne();
        return $resp;
    }
    
    
    public function toma_total_calificados_con_nulos($actividad){
        $con = \Yii::$app->db;
        $query = "select count(id) as total from scholaris_calificaciones 
                    where idactividad = $actividad
                                    and calificacion is null;";        
        $res = $con->createCommand($query)->queryOne();
        
        return $res['total'];
    }
    
    
    public function get_calificaciones($actividad){
        $model = ScholarisCalificaciones::find()
                ->where(['idactividad' => $actividad])
                ->andWhere(['not', ['calificacion' => null]])
                ->all();
             
        
        return count($model);
    }
    
    
    public function toma_total_calificados($actividad, $totalAlu) {
        
        $modelActividad = \backend\models\ScholarisActividad::findOne($actividad);
        
        if($modelActividad->tipo_calificacion == 'P'){
            $totalAct = $this->total_calificaciones_actividad($actividad);
            $totalCalificaciones = $totalAct * $totalAlu;
        
            return $totalAct;
        }else{
            
                        
            $totalAct = 1;
            $totalCalificaciones = $totalAct * $totalAlu;
        
            return $totalCalificaciones;
        }
        
        
    }
        
    
    
    public function toma_total_calificaciones($actividad, $totalAlu){
        
        $modelActividad = ScholarisActividad::findOne($actividad);
        if($modelActividad->tipo_calificacion == 'P'){
            $total = $this->total_actividades_pai($actividad) * $totalAlu;            
        }else{
            $periodo = \Yii::$app->user->identity->periodo_id;
            $sentencias = new SentenciasClase();
            $alumnos = $sentencias->get_alumnos_clase($modelActividad->paralelo_id, $periodo);
            $total = count($alumnos);   
        }      
        
        
        return $total;
//        die();
    }
    
    private function total_actividades_pai($actividad){
        $con = \Yii::$app->db;
        $query = "select count(total) as total
                    from (
                    select 	count(d.criterio_id) as total 
                    from 	scholaris_actividad a 
                                    inner join scholaris_actividad_descriptor d on d.actividad_id = a.id 
                    where 	a.id = $actividad
                    group by d.criterio_id
                    ) as total";
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res['total'];
    }




    private function total_calificaciones_actividad($actividad){
        
        $con = \Yii::$app->db;
        $query = "select count(criterio_id) as total
                    from (
                    select 	criterio_id 
                    from 	scholaris_calificaciones 
                    where 	idactividad = $actividad 
                    group by criterio_id) as total;";
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res['total'];
    }
    

    public function cambia_grupo_actividad($actividad, $grupo, $tipoActividad) {
        $con = \Yii::$app->db;
        $query = "update scholaris_calificaciones "
                . "set idtipoactividad = $tipoActividad, "
                . "grupo_numero = $grupo "
                . "where "
                . "idactividad = $actividad";
        $con->createCommand($query)->execute();
        
    }

}
