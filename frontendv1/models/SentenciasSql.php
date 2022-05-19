<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "rol".
 *
 * @property int $id
 * @property string $rol
 *
 * @property RolOperacion[] $rolOperacions
 * @property Operacion[] $operacions
 * @property Usuario[] $usuarios
 */
class SentenciasSql extends \yii\db\ActiveRecord {
    /*     * ***INICIO ALUMNOS ** */

    public function getAlumnosClase($clase, $periodo) {
        $con = Yii::$app->db;
        $query = "select 	s.id
                                        ,s.last_name
                                        ,s.first_name
                                        ,s.middle_name
                        from 	scholaris_grupo_alumno_clase g
                                        inner join op_student_inscription i on i.student_id = g.estudiante_id
                                        inner join op_student s on s.id = g.estudiante_id
                                        inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
                                        inner join scholaris_periodo sp on sp.id = sop.scholaris_id
                        where 	g.clase_id = $clase
                                        and i.inscription_state = 'M'
                                        and sp.codigo = '$periodo'
                        order by s.last_name
                                        ,s.first_name
                                        ,s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getAlumnosPorParalelo($paralelo) {
        $con = Yii::$app->db;
        $query = "select 	s.id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                    from	op_student_inscription i
                                    inner join op_student s on s.id = i.student_id
                    where	i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                    order by s.last_name
                                    ,s.first_name
                                    ,s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /*     * ***FIN ALUMNOS ** */


    /*     * ******* NOTAS PROFESORE ******************** */

    public function getInsumos($clase, $bloque) {
        $con = Yii::$app->db;
        $query = "select count(idtipoactividad) as total
		,idtipoactividad
		,nombre_nacional
		,grupo_numero
from(
select 	cal.idtipoactividad	
		,a.id
		,t.nombre_nacional
		,cal.grupo_numero
from	scholaris_calificaciones cal
		inner join scholaris_actividad a on a.id = cal.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where	a.paralelo_id = $clase
		and a.bloque_actividad_id = $bloque
group by a.id
		,cal.idtipoactividad
		,t.nombre_nacional
		,cal.grupo_numero
)as total
group by idtipoactividad
		,nombre_nacional
		,grupo_numero;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getActividadesPorInsumo($orden, $clase, $bloque) {
        $con = Yii::$app->db;
        $query = "select a.id
                         ,a.title
                  from 	scholaris_calificaciones cal
                        inner join scholaris_actividad a on a.id = cal.idactividad
                  where	cal.grupo_numero = $orden
                        and a.paralelo_id = $clase
                        and a.bloque_actividad_id = $bloque
                  group by a.id
                        ,a.title
                  order by a.id;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getNotaOrden($alumno, $bloque, $orden, $clase) {
        $con = Yii::$app->db;
        $query = "select 	trunc(avg(cal.calificacion),2) as nota
                    from 	scholaris_calificaciones cal
                                    inner join scholaris_actividad a on a.id = cal.idactividad
                    where	cal.idalumno = $alumno
                                    and a.bloque_actividad_id = $bloque
                                    and cal.grupo_numero = $orden
                                    and a.paralelo_id = $clase;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getNotaParcialClase($alumno, $bloque, $tipoLibreta, $clase) {
        $con = Yii::$app->db;
        $query = "select 	l.nota
                    from 	scholaris_alumno_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.alumno_id
                                    inner join scholaris_estructura_libreta_detalle det on det.id = l.estructura_detalle_id
                                    inner join scholaris_bloque_actividad b on b.orden = det.orden_bloque
                    where	g.estudiante_id = $alumno
                                    and b.id = $bloque
                                    and det.cabecera_id = $tipoLibreta
                                    and g.clase_id = $clase;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /*     * *********FIN DE NOTAS PROFESOR *************** */

    /** INICIO DE TOMA CLASES Y MATERIAS * */
    public function getClasesPromedia($alumnoId, $periodo) {
        $con = Yii::$app->db;
        $query = "select 	c.id
		,m.id as materia_id
		,m.name as materia
		,c.promedia
                ,g.id as grupo_id
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria		
where	g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$periodo'
                and c.promedia = 1
order by c.promedia desc,c.id asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getClasesNoPromedia($alumnoId, $periodo) {
        $con = Yii::$app->db;
        $query = "select 	c.id
		,m.id as materia_id
		,m.name as materia
		,c.promedia
                ,g.id as grupo_id
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria		
where	g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$periodo'
                and c.promedia = 0
                and m.name not ilike '%COMPORTAMIENTO%'
order by c.promedia desc,c.id asc;";

//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getClasesComportamiento($alumnoId, $periodo) {
        $con = Yii::$app->db;
        $query = "select 	c.id
		,m.id as materia_id
		,m.name as materia
		,c.promedia
                ,g.id as grupo_id
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria		
where	g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$periodo'
                and c.promedia = 0
                and m.name ilike '%COMPORTAMIENTO%'
order by c.promedia desc,c.id asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function getNotasClase($grupoId) {
        $con = Yii::$app->db;
        $query = "select 	d.id
		,l.nota
from 	scholaris_alumno_clase_libreta l
		inner join scholaris_estructura_libreta_detalle d on d.id = l.estructura_detalle_id
where 	l.alumno_id = $grupoId 
order by d.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /** FIN DE TOMA CLASES Y MATERIAS * */

    /** INICIO LIBRETAS DATOS EN TABLA LIBRETA FINAL * */
    public function ingresaAlumnosLibretaFinal($alumnoId, $periodo, $cabeceraLibreta) {
        $con = Yii::$app->db;
        $query = "insert into scholaris_alumno_libreta_final(alumno_id, scholaris_periodo, estructura_detalle_id)
                    select 	$alumnoId
                                    ,'$periodo'
                                    ,d.id
                    from 	scholaris_estructura_libreta_detalle d
                    where	d.cabecera_id = 1
                                    and id not in (select estructura_detalle_id from scholaris_alumno_libreta_final where alumno_id = $alumnoId and scholaris_periodo = '$periodo' and estructura_detalle_id = d.id)
                    order by orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actualizaNotasFinalesNoImprimeAreas($alumnoId, $periodo, $cabeceraLibreta) {
        $con = Yii::$app->db;
        $query = "update scholaris_alumno_libreta_final fin
                    set nota =
                    (
                                            select 	
                                                            trunc(avg(l.nota),2) as nota
                                            from 	scholaris_grupo_alumno_clase g
                                                            inner join scholaris_clase c on c.id = g.clase_id
                                                            inner join scholaris_alumno_clase_libreta l on l.alumno_id = g.id
                                                            inner join scholaris_estructura_libreta_detalle d on d.id = l.estructura_detalle_id
                                                            inner join scholaris_alumno_libreta_final lf on lf.alumno_id = g.estudiante_id
                                                                                                    and lf.scholaris_periodo = c.periodo_scholaris
                                            where	g.estudiante_id = fin.alumno_id
                                                            and c.periodo_scholaris = fin.scholaris_periodo
                                                            and d.cabecera_id = $cabeceraLibreta
                                                            and c.promedia = 1
                                                            and d.id = fin.estructura_detalle_id
                                            group by d.id
                            )
                    where 	fin.alumno_id = $alumnoId
                                    and fin.scholaris_periodo = '$periodo';";
        $res = $con->createCommand($query)->execute();
    }

    public function getNotasFinalNoImprime($alumnoId, $periodo) {
        $con = Yii::$app->db;
        $query = "select 	d.id
		,d.sigla
		,lf.nota
from 	scholaris_alumno_libreta_final lf
		inner join scholaris_estructura_libreta_detalle d on d.id = lf.estructura_detalle_id
where 	lf.alumno_id = $alumnoId
		and lf.scholaris_periodo = '$periodo' order by d.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /** FGIN LIBRETAS DATOS EN TABLA LIBRETA FINAL * */

    /** INICIO DE HORARIOS DE CLASE * */
    public function getHorarioClase($horario, $paralelo) {
        $con = Yii::$app->db;
        $query = "select 	det.id
                ,dia.id as dia_id
		,dia.nombre
		,ho.sigla
		,(
			select 	mx.name
                        from	scholaris_horariov2_horario horx
                                        inner join scholaris_clase cx on cx.id = horx.clase_id
                                        inner join scholaris_materia mx on mx.id = cx.idmateria
                        where	horx.detalle_id = det.id
                                        and cx.paralelo_id = $paralelo
		)as materia
from 	scholaris_horariov2_detalle det
		inner join scholaris_horariov2_dia dia on dia.id = det.dia_id
		inner join scholaris_horariov2_hora ho on ho.id = det.hora_id		
where	det.cabecera_id = $horario
order by dia.numero
		,ho.numero;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /** FIN DE HORARIOS DE CLASE * */
    /* INICIO CALIFICACIONES */

    public function insertarEspaciosCalificacionPai($actividad, $tipoId, $criterioId, $claseId) {
        $con = Yii::$app->db;
        $query = "insert into scholaris_calificaciones(idalumno, idactividad, idtipoactividad, criterio_id, estado_proceso, grupo_numero, estado)
                select 	g.estudiante_id as idalumno
                                ,$actividad 
                                ,$tipoId 
                                ,$criterioId
                                ,0 
                                ,o.grupo_numero
                                ,1 
                from 	scholaris_grupo_alumno_clase g
                                inner join scholaris_criterio c on c.id = $criterioId
                                inner join scholaris_grupo_orden_calificacion o on o.codigo_nombre_pai = c.criterio
                where 	g.clase_id = $claseId
                            and g.estudiante_id not in (select idalumno from scholaris_calificaciones where idalumno = g.estudiante_id and idactividad = $actividad and criterio_id = $criterioId);";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->execute();
        //return $res;
    }
    
    public function insertarEspaciosCalificacionPaiSumativa($actividad, $tipoId, $criterioId, $claseId, $grupo) {
        $con = Yii::$app->db;
        $query = "insert into scholaris_calificaciones(idalumno, idactividad, idtipoactividad, criterio_id, estado_proceso, grupo_numero, estado)
                select 	g.estudiante_id as idalumno
                                ,$actividad 
                                ,$tipoId 
                                ,$criterioId
                                ,0 
                                ,$grupo
                                ,1 
                from 	scholaris_grupo_alumno_clase g
                                inner join scholaris_criterio c on c.id = $criterioId
                                inner join scholaris_grupo_orden_calificacion o on o.codigo_nombre_pai = c.criterio
                where 	g.clase_id = $claseId
                            and g.estudiante_id not in (select idalumno from scholaris_calificaciones where idalumno = g.estudiante_id and idactividad = $actividad and criterio_id = $criterioId);";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->execute();
        //return $res;
    }

    public function insertarEspaciosCalificacionNac($actividad, $tipoId, $claseId) {
        $con = Yii::$app->db;
        $query = "insert into scholaris_calificaciones(idalumno, idactividad, idtipoactividad, estado_proceso, grupo_numero, estado)
                    select 	g.estudiante_id as idalumno
                                    ,$actividad
                                    ,$tipoId
                                    ,0
                                    ,o.grupo_numero
                                    ,1
                    from 	scholaris_grupo_alumno_clase g				
                                    inner join scholaris_tipo_actividad t on t.id = $tipoId
                                    inner join scholaris_grupo_orden_calificacion o on o.codigo_nombre_pai = t.nombre_pai
                    where 	g.clase_id = $claseId
                                    and g.estudiante_id not in (select idalumno from scholaris_calificaciones where idalumno = g.estudiante_id and idactividad = $actividad);";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function notaParcial($alumno, $bloque, $clase) {
        $con = Yii::$app->db;
        $query = " select trunc(avg(nota),2) as nota
				from (
				select 	c.grupo_numero
						,trunc(avg(c.calificacion),2) as nota		
				from 	scholaris_calificaciones c
						inner join scholaris_actividad a on a.id = c.idactividad
						inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
				where	c.idalumno = $alumno
						and b.id = $bloque
						and a.paralelo_id = $clase
				group by c.grupo_numero		
				) as nota";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function actualizaLibreta($alumno, $bloque, $clase){
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::find()->where(['id' => $bloque])->one();
        
        if($modelBloque->orden == 1){
            $campo = 'p1';
        }
        
        if($modelBloque->orden == 2){
            $campo = 'p2';
        }
        
        if($modelBloque->orden == 3){
            $campo = 'p3';
        }
        
        if($modelBloque->orden == 4){
            $campo = 'ex1';
        }
        
        if($modelBloque->orden == 5){
            $campo = 'p4';
        }
        
        if($modelBloque->orden == 6){
            $campo = 'p5';
        }
        
        if($modelBloque->orden == 7){
            $campo = 'p6';
        }
        
        if($modelBloque->orden == 8){
            $campo = 'ex2';
        }
        
                
        $nota = $this->notaParcial($alumno, $bloque, $clase);
        $notaP = $nota['nota'];
        
        if(!$notaP){
            $notaP = 0;
        }
        
        
        $con = Yii::$app->db;
        $query = "update scholaris_clase_libreta
                    set		$campo = $notaP
                    from 	scholaris_grupo_alumno_clase
                    where	scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id
                                    and scholaris_grupo_alumno_clase.estudiante_id = $alumno
                                    and scholaris_grupo_alumno_clase.clase_id = $clase;";
        
        $con->createCommand($query)->execute();
                
    }






    /* fin CALIFICACIONES */


    /*     * **inicio FECHAS PARA ACTIVIDADES *** */

    public function fechasDisponibles($desde, $hasta, $clase, $bloque) {
        $con = Yii::$app->db;
        $query = "SELECT date_trunc('day', dd):: date as fecha
                        ,extract(dow from date_trunc('day', dd):: date) as numero_dia
                        ,(select nombre from scholaris_horariov2_dia where numero = extract(dow from date_trunc('day', dd):: date)) as dia
                        ,(select 	nombre_semana 
                            from 	scholaris_bloque_semanas 
                            where 	bloque_id = $bloque
                                            and date_trunc('day', dd):: date between fecha_inicio and fecha_finaliza) as semana
                        FROM generate_series
                                ( '$desde'::timestamp 
                                , '$hasta'::timestamp
                                , '1 day'::interval) dd
                        where	extract(dow from date_trunc('day', dd):: date)  in (select 	dia.numero 
                        from 	scholaris_horariov2_horario h
                                        inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
                                        inner join scholaris_horariov2_dia dia on dia.id = d.dia_id
                        where 	clase_id = $clase
                        group by dia.numero)
                        --order by extract(dow from date_trunc('day', dd):: date);";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function hora_asignada_automaticamente($clase, $fecha) {
        $con = Yii::$app->db;
        $query = "select 	ho.id as hora_id
from	scholaris_horariov2_horario h
		inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
		inner join scholaris_horariov2_hora ho on ho.id = d.hora_id
		inner join scholaris_horariov2_dia dia on dia.id = d.dia_id
where	h.clase_id = $clase
		and dia.numero = date_part('dow',cast('$fecha' as date));";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res['hora_id'];
    }
    
    

    public function horasDia($clase, $diaNumero) {
        $con = Yii::$app->db;
        $query = "select 	ho.id
                                ,ho.sigla
                from 	scholaris_horariov2_horario h
                                inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
                                inner join scholaris_horariov2_hora ho on ho.id = d.hora_id
                where 	h.clase_id = $clase
                                and d.dia_id = $diaNumero
                order by ho.numero;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function criteriosNoAsignados($curso, $area, $actividad) {
        $con = Yii::$app->db;
        $query = "select 	c.id as criterio_id
                                    ,d.id as detalle_id
                                    ,c.criterio
                                    ,d.descricpcion
                    from 	scholaris_criterio_detalle  d
                                    inner join scholaris_criterio c on c.id = d.idcriterio
                    where 	d.curso_id = $curso
                                    and c.area_id = $area
                                    and d.id not in (select detalle_id from scholaris_actividad_descriptor where actividad_id = $actividad and criterio_id = c.id and detalle_id = d.id)
                    order by c.criterio, d.orden;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function criteriosAsignados($actividad) {
        $con = Yii::$app->db;
        $query = "select d.id
		,c.criterio
                ,det.id as detalle_id
		,det.descricpcion
from 	scholaris_actividad_descriptor d
		inner join scholaris_criterio_detalle det on det.id = d.detalle_id
		inner join scholaris_criterio c on c.id = d.criterio_id
where	d.actividad_id = $actividad
		order by c.criterio, det.orden;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function eliminaCalificaciones($actividadId, $alumnoId){
        $con = Yii::$app->db;
        $query = "delete from scholaris_calificaciones where idactividad = $actividadId and idalumno = $alumnoId;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->execute();

    }

    /*     * **fin FECHAS PARA ACTIVIDADES *** */

    /*     * **Inicio NOTAS PROFESOR *** */

    public function titutloCriterios($bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select count(grupo_numero)+1 as total, grupo_numero
from
(
select 	a.id, c.grupo_numero
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
		inner join scholaris_grupo_orden_calificacion o on o.codigo_tipo_actividad = a.tipo_actividad_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai <> 'SUMATIVA'
group by a.id, c.grupo_numero, a.title
order by c.grupo_numero
) as total
group by grupo_numero
order by grupo_numero;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function tituloSumativas($bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select count(grupo_numero) as total, grupo_numero
from
(
select 	a.id, c.grupo_numero
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
		inner join scholaris_grupo_orden_calificacion o on o.codigo_tipo_actividad = a.tipo_actividad_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai = 'SUMATIVA'
group by a.id, c.grupo_numero, a.title
order by c.grupo_numero
) as total
group by grupo_numero
order by grupo_numero;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function tituloSumativas1($bloqueId, $claseId, $grupo) {
        $con = Yii::$app->db;
        $query = "select 	cri.id as criterio_id
		,cri.criterio
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_criterio cri on cri.id = c.criterio_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and c.grupo_numero = $grupo
group by cri.id 
		,cri.criterio;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function actividadesNoSumativas($bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	c.grupo_numero, a.title
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
						and t.id = c.idtipoactividad
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai <> 'SUMATIVA'
group by c.grupo_numero, a.title
order by c.grupo_numero, a.title;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function grupoInsumos($bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	c.grupo_numero as grupo
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai <> 'SUMATIVA'
group by c.grupo_numero
order by c.grupo_numero;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actividadesPorGrupo($bloqueId, $claseId, $grupo) {
        $con = Yii::$app->db;
        $query = "select 	a.id
		,a.title
from	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai <> 'SUMATIVA'
		and c.grupo_numero = $grupo
group by a.id
		,a.title
order by a.id;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function grupoInsumosSumativas($bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	c.grupo_numero as grupo
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai = 'SUMATIVA'
group by c.grupo_numero
order by c.grupo_numero;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actividadesPorGrupoSumativas($bloqueId, $claseId, $grupo) {
        $con = Yii::$app->db;
        $query = "select 	a.id
		,a.title
                ,c.criterio_id
from	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
		and t.nombre_pai = 'SUMATIVA'
		and c.grupo_numero = $grupo
group by a.id
		,a.title,c.criterio_id
order by a.id;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function notasActividades($bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	s.id as alumno_id
		,a.id as actividad_id
		,c.grupo_numero
		,c.calificacion
from 	scholaris_calificaciones c 
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join op_student s on s.id = c.idalumno
where	a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and a.calificado = 'SI'
order by s.last_name, s.first_name, s.middle_name, c.grupo_numero, a.id;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function promedioGrupo($grupo, $alumno, $bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	trunc(avg(c.calificacion),2) as nota
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where 	c.grupo_numero = $grupo
		and c.idalumno = $alumno
		and a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and t.nombre_pai <> 'SUMATIVA' and a.calificado = 'SI';";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function promedioSumativas($alumno, $bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select 	trunc(avg(c.calificacion),2) as nota
from 	scholaris_calificaciones c
		inner join scholaris_actividad a on a.id = c.idactividad
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
where 	c.idalumno = $alumno
		and a.paralelo_id = $claseId
		and a.bloque_actividad_id = $bloqueId
		and t.nombre_pai = 'SUMATIVA';";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    public function promedioParcial($alumno, $bloqueId, $claseId) {
        $con = Yii::$app->db;
        $query = "select calificacion from scholaris_resumen_parciales where alumno_id = $alumno and clase_id = $claseId and bloque_id = $bloqueId;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    public function cuadroEstadistico($periodo, $claseId, $campo) {
        
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        
        
        $con = Yii::$app->db;
        $query = "select t.id ,t.abreviatura ,t.descripcion 
		,t.rango_minimo ,t.rango_maximo 
		,(
			select 	count($campo)
				from 	scholaris_grupo_alumno_clase g
						inner join op_student_inscription i on i.student_id = g.estudiante_id
						inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
						inner join scholaris_periodo sp on sp.id = sop.scholaris_id
						inner join scholaris_clase_libreta l on l.grupo_id = g.id
				where 	g.clase_id = $claseId
						and sp.id = $periodo
						and i.inscription_state = 'M'
						and $campo between t.rango_minimo and t.rango_maximo
		 ) as total
from scholaris_tabla_escalas_homologacion t 
where	t.scholaris_periodo = '$modelPeriodo->codigo' 
and t.corresponde_a = 'APROVECHAMIENTO' 
order by rango_maximo desc;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function promedioParcialClase($claseId, $campo) {
        $periodo = Yii::$app->user->identity->periodo_id;
        
        $con = Yii::$app->db;
        $query = "select trunc(avg($campo),2) as promedio
from 	scholaris_grupo_alumno_clase g
		inner join op_student_inscription i on i.student_id = g.estudiante_id
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
		inner join scholaris_periodo sp on sp.id = sop.scholaris_id
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
where 	g.clase_id = $claseId
		and sp.id = $periodo
		and i.inscription_state = 'M';";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    

    /*     * **fin NOTAS PROFESOR *** */
    
    
    /*     * **INICIO ASISTENCIA PROFESOR *** */
    
    public function asistenciaProfesor($usuario, $periodo) {
        $con = Yii::$app->db;
        $query = "select mat.name as materia, hor.detalle_id
                ,cur.name as curso
		,par.name as paralelo
		,hor.clase_id
		,dia.id as dia_id
		,dia.nombre as dia
		,hora.id as hora_id
		,hora.nombre as hora
		,hora.desde as desde
		,hora.hasta as hasta
		,asi.id as asistencia_id
		,asi.hora_ingresa		
from 	scholaris_horariov2_horario hor
		inner join scholaris_horariov2_detalle det on det.id = hor.detalle_id
		inner join scholaris_horariov2_hora hora on hora.id = det.hora_id
		inner join scholaris_horariov2_dia dia on dia.id = det.dia_id
                inner join scholaris_clase cla on cla.id = hor.clase_id
		inner join scholaris_materia mat on mat.id = cla.idmateria
                inner join op_course cur on cur.id = cla.idcurso
		inner join op_course_paralelo par on par.id = cla.paralelo_id
		left join scholaris_asistencia_profesor asi on asi.clase_id = hor.clase_id
								and asi.fecha = current_date
                                                                and asi.hora_id = hora.id
where	hor.clase_id in (
				select 	c.id
				from 	scholaris_clase c
						inner join op_faculty f on f.id = c.idprofesor
						inner join res_users u on u.partner_id = f.partner_id
				where	u.login = '$usuario'
						and c.periodo_scholaris = '$periodo'
				)
		and dia.numero = date_part('dow',current_date)
order by hora.numero asc;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function toma_hora_siguiente($usuario, $periodo, $clase){
        $con = Yii::$app->db;
        $query = "select mat.name as materia, hor.detalle_id ,cur.name as curso ,par.name as paralelo, hora.numero
,hor.clase_id ,dia.id as dia_id ,dia.nombre as dia ,hora.id as hora_id 
,hora.nombre as hora ,hora.desde as desde ,hora.hasta as hasta ,asi.id as asistencia_id 
,asi.hora_ingresa	
from scholaris_horariov2_horario hor 
inner join scholaris_horariov2_detalle det on det.id = hor.detalle_id 
inner join scholaris_horariov2_hora hora on hora.id = det.hora_id 
inner join scholaris_horariov2_dia dia on dia.id = det.dia_id 
inner join scholaris_clase cla on cla.id = hor.clase_id 
inner join scholaris_materia mat on mat.id = cla.idmateria 
inner join op_course cur on cur.id = cla.idcurso 
inner join op_course_paralelo par on par.id = cla.paralelo_id 
left join scholaris_asistencia_profesor asi on asi.clase_id = hor.clase_id and asi.fecha = current_date and asi.hora_id = hora.id 
where	hor.clase_id in ( select c.id from scholaris_clase c inner join op_faculty f on f.id = c.idprofesor 
							inner join res_users u on u.partner_id = f.partner_id 
							where	u.login = '$usuario' and c.periodo_scholaris = '$periodo' ) 
		and dia.numero = date_part('dow',current_date) 
		and cla.id = $clase
		order by hora.numero desc
	limit 1;";
//        print_r($query);
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }






    /*     * **FIN ASISTENCIA PROFESOR *** */
    
    public function fecha_para_duplicar($modelActividad, $clase){
        
        $modelBloqueFin = \backend\models\ScholarisBloqueActividad::find()
                ->where(['is not','bloque_finaliza',null])
                ->orderBy(['bloque_finaliza' => SORT_DESC])
                ->limit(1)
                ->one();
        
        $con = Yii::$app->db;
        $query = "SELECT date_trunc('day', dd):: date as fecha
                                    ,extract(dow from date_trunc('day', dd):: date) as numero_dia
                                    ,(select nombre from scholaris_horariov2_dia where numero = extract(dow from date_trunc('day', dd):: date)) as dia
                    FROM generate_series
                            ( '$modelActividad->inicio'::timestamp 
                                            , '$modelBloqueFin->bloque_finaliza 00:00:00'::timestamp
                                            , '1 day'::interval) dd
                                            where	extract(dow from date_trunc('day', dd):: date)  in (select 	dia.numero 
                                            from 	scholaris_horariov2_horario h
                                                            inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
                                                            inner join scholaris_horariov2_dia dia on dia.id = d.dia_id
                    where 	clase_id = $clase
                    group by dia.numero)
                    order by extract(dow from date_trunc('day', dd):: date) limit 1;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    public function get_actividad_duplicada($original,$clase){       
        
        $modelActividades = \app\models\ScholarisActividad::find()
                ->where([
                            'paralelo_id' => $clase,
                            'actividad_original' => $original
                        ])
                ->one();
        return $modelActividades;
    }
    
    public function hora_para_duplicar($clase,$inicio){
        
        $con = Yii::$app->db;
        $query = "select det.hora_id
                    from 	scholaris_horariov2_horario h
                                    inner join scholaris_horariov2_detalle det on det.id = h.detalle_id
                                    inner join scholaris_horariov2_dia dia on dia.id = det.dia_id
                    where	h.clase_id = $clase
                                    and dia.numero = date_part('dow','$inicio'::date)
                                    limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    public function duplicar_criterios($actividadNueva, $actividadVieja){        
        $con = Yii::$app->db;
        $query = "insert into scholaris_actividad_descriptor(actividad_id, criterio_id, detalle_id)
                    select  $actividadNueva, criterio_id, detalle_id from scholaris_actividad_descriptor where actividad_id = $actividadVieja;";
        $con->createCommand($query)->execute();        
    }
    
    public function duplicar_archivos($actividadNueva, $actividadVieja){        
        $con = Yii::$app->db;
        $query = "insert into scholaris_archivosprofesor(idactividad, archivo, fechasubido, nombre_archivo)
                  select $actividadNueva, archivo, fechasubido, nombre_archivo from scholaris_archivosprofesor where idactividad = $actividadVieja;";
        $con->createCommand($query)->execute();        
    }
    
    
    /**
     * para realizar actividades por parcial
     */
    
    public function get_insumos($clase, $bloque){
        $con = Yii::$app->db;
        $query = "select o.grupo_numero, o.nombre_grupo
                                ,(
                                        select count(total) as total
                                        from(
                                        select count(act.id) as total
                                        from 	scholaris_calificaciones cal
                                                        inner join scholaris_actividad act on act.id = cal.idactividad
                                        where	cal.grupo_numero = o.grupo_numero
                                                        and act.paralelo_id = $clase
                                                        and act.bloque_actividad_id = $bloque
                                                        and act.calificado = 'SI'
                                        group by act.id
                                        ) as total
                                ) as total
                from 	scholaris_grupo_orden_calificacion o
                                inner join scholaris_tipo_actividad t on t.id = o.codigo_tipo_actividad
                group by o.grupo_numero, o.nombre_grupo
                order by o.grupo_numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
       
    
    
         
}
