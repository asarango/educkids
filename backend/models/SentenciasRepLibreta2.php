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
class SentenciasRepLibreta2 extends \yii\db\ActiveRecord {

    public function get_clases_por_area($paralelo, $area) {
        $con = Yii::$app->db;
        $query = "select 	c.id as clase_id
		,mat.name as materia
		,m.promedia
		,m.total_porcentaje
                ,ar.name as area
                ,m.se_imprime
                ,m.es_cuantitativa
                ,m.tipo
                ,m.orden
from	op_student_inscription i
		left join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		left join scholaris_clase c on c.id = g.clase_id
		left join scholaris_malla_materia m on m.id = c.malla_materia
		left join scholaris_materia mat on mat.id = m.materia_id
                left join scholaris_malla_area a on a.id = m.malla_area_id
		left join scholaris_area ar on ar.id = a.area_id
where 	i.parallel_id = $paralelo
		and i.inscription_state = 'M'
		and m.malla_area_id = $area
		group by c.id,mat.name, m.promedia 
                ,m.total_porcentaje, ar.name, m.se_imprime,m.es_cuantitativa,m.tipo
                ,m.orden";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function clases_alumno($alumno) {
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPerido = ScholarisPeriodo::find()->where(['id' => $periodo])->one();

        $con = \Yii::$app->db;
        $query = "select  c.id as clase_id
                    from 	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPerido->codigo';";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function clases_paralelo($paralelo) {
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPerido = ScholarisPeriodo::find()->where(['id' => $periodo])->one();

        $con = \Yii::$app->db;
        $query = "select g.clase_id
                    from 	op_student_inscription i
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                    where	i.parallel_id = $paralelo
                                    and c.periodo_scholaris = '$modelPerido->codigo'
                    group by g.clase_id;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function asignarLibretas() {
        $con = Yii::$app->db;

//        $query = "insert into scholaris_clase_libreta (grupo_id, p1, p2, p3, ex1, p4, p5,p6,ex2)
//                    select gru.id
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 1
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 2
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 3
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 4
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 5
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 6
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 7
//                    )
//                    ,(
//                            select p.calificacion as nota
//                            from 	scholaris_grupo_alumno_clase g
//                                            inner join scholaris_clase c on c.id = g.clase_id
//                                            inner join scholaris_resumen_parciales p on p.clase_id = g.clase_id
//                                                            and p.alumno_id = g.estudiante_id
//                                            inner join scholaris_bloque_actividad b on b.id = p.bloque_id
//                            where	g.id = gru.id
//                                            and b.orden = 8
//                    )
//                    from	scholaris_grupo_alumno_clase gru
//                    where	gru.id not in (select grupo_id from scholaris_clase_libreta)
//                    order by gru.id asc;";
//                    
//                    

        $query = "insert into scholaris_clase_libreta (grupo_id)
                    select gru.id                  
                    from	scholaris_grupo_alumno_clase gru
                    where	gru.id not in (select grupo_id from scholaris_clase_libreta)
                    order by gru.id asc;";


//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }

    public function procesarAreas($curso, $paralelo) {

        $usuario = \Yii::$app->user->identity->usuario;

        $modelMallaCurso = ScholarisMallaCurso::find()
                ->where(['curso_id' => $curso])
                ->one();


        $this->procesaAreas($modelMallaCurso->malla_id, $usuario, $paralelo);



        return $modelMallaCurso->malla_id;
    }

    private function procesaAreas($malla, $usuario, $paralelo) {

        $this->eliminarAreas($usuario, $paralelo);
        $this->llenaAreas($usuario, $malla, $paralelo);
    }

    private function eliminarAreas($usuario, $paralelo) {
        $con = \Yii::$app->db;
        $query = "DELETE FROM scholaris_notas_areas where usuario = '$usuario' and paralelo_id = $paralelo";
        $con->createCommand($query)->execute();
    }

    private function llenaAreas($usuario, $malla, $paralelo) {
        $con = \Yii::$app->db;
        $query = "insert into scholaris_notas_areas (usuario, malla_area_id, alumno_id, paralelo_id, p1, p2, p3, pr1, pr180, ex1, ex120, q1, p4, p5, p6, pr2, pr280, ex2, ex220, q2, final_ano_normal, final_total)
select '$usuario'
		,a.id
		,g.estudiante_id
		,$paralelo

                ,sum(trunc(l.p1 * m.total_porcentaje /100,2)) as p1 
		,sum(trunc(l.p2 * m.total_porcentaje /100,2)) as p2 
		,sum(trunc(l.p3 * m.total_porcentaje /100,2)) as p3 
		,sum(trunc(l.pr1 * m.total_porcentaje /100,2)) as pr1 		
		,trunc(sum(trunc(l.pr1 * m.total_porcentaje /100,2))*80/100,2) as pr180
		,sum(trunc(l.ex1 * m.total_porcentaje /100,2)) as ex1 
		,trunc(sum(trunc(l.ex1 * m.total_porcentaje /100,2))*20/100,2) as ex120
		,trunc(sum(trunc(l.pr1 * m.total_porcentaje /100,2))*80/100,2) +  trunc(sum(trunc(l.ex1 * m.total_porcentaje /100,2))*20/100,2) as q1
		-- 
		,sum(trunc(l.p4 * m.total_porcentaje /100,2)) as p4 
		,sum(trunc(l.p5 * m.total_porcentaje /100,2)) as p5 
		,sum(trunc(l.p6 * m.total_porcentaje /100,2)) as p6 
		,sum(trunc(l.pr2 * m.total_porcentaje /100,2)) as pr2 
		,trunc(sum(trunc(l.pr2 * m.total_porcentaje /100,2))*80/100,2) as pr280
		,sum(trunc(l.ex2 * m.total_porcentaje /100,2)) as ex2
		,trunc(sum(trunc(l.ex2 * m.total_porcentaje /100,2))*20/100,2) as ex220
		,trunc(sum(trunc(l.pr2 * m.total_porcentaje /100,2))*80/100,2) +  trunc(sum(trunc(l.ex2 * m.total_porcentaje /100,2))*20/100,2) as q2 
		,trunc((trunc(sum(trunc(l.pr1 * m.total_porcentaje /100,2))*80/100,2) +  trunc(sum(trunc(l.ex1 * m.total_porcentaje /100,2))*20/100,2) +
		trunc(sum(trunc(l.pr2 * m.total_porcentaje /100,2))*80/100,2) +  trunc(sum(trunc(l.ex2 * m.total_porcentaje /100,2))*20/100,2))/2,2) as final_ano_normal
                                    ,sum(trunc(l.final_total * m.total_porcentaje /100,2)) as final_total
from	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia m on m.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = m.malla_area_id
		inner join op_student_inscription i on i.student_id = g.estudiante_id
where	a.malla_id = $malla
		and i.parallel_id = $paralelo		
group by a.id,g.estudiante_id
order by g.estudiante_id, a.id asc;";


        $con->createCommand($query)->execute();
    }

    public function get_nota_por_area($alumno, $usuario, $area) {

        $con = \Yii::$app->db;
        $query = "select usuario, malla_area_id, alumno_id, paralelo_id, p1, p2, "
                . "p3, pr1, pr180, ex1, ex120, q1, p4, p5, p6, pr2, pr280, "
                . "ex2, ex220, q2, final_ano_normal "
                . "from scholaris_notas_areas "
                . "where alumno_id = $alumno "
                . "and malla_area_id = $area and usuario = '$usuario';";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;

        //return $resultado;
    }

    public function get_notas_por_materia($clase, $alumno) {
        $con = Yii::$app->db;
        $query = "select grupo_id, p1, p2, p3, pr1, pr180, ex1, ex120, q1
                    , p4, p5, p6, pr2, pr280, ex2, ex220, q2, final_ano_normal
                    , mejora_q1, mejora_q2, final_con_mejora, supletorio, remedial, gracia, final_total
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.clase_id = $clase
                                    and g.estudiante_id = $alumno;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function get_notas_clases($alumno) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $con = \Yii::$app->db;
        $query = "select 	mat.name as materia
                                    ,mat.id as materia_id
                                    ,c.id as clase_id
                                    ,g.id as grupo_id
                                    ,l.p1
                                    ,l.p2
                                    ,l.p3
                                    ,l.pr1
                                    ,l.pr180
                                    ,l.ex1
                                    ,l.ex120
                                    ,l.q1
                                    ,l.p4
                                    ,l.p5
                                    ,l.p6
                                    ,l.pr2
                                    ,l.pr280
                                    ,l.ex2
                                    ,l.ex220
                                    ,l.q2
                                    ,l.final_ano_normal 
                                    ,l.mejora_q1
                                    ,l.mejora_q2
                                    ,l.final_con_mejora
                                    ,l.supletorio
                                    ,l.remedial
                                    ,l.gracia
                                    ,l.final_total
                                    ,m.tipo
                    from	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                                    inner join scholaris_materia mat on mat.id = m.materia_id
                                    inner join scholaris_malla_area a on a.id = m.malla_area_id
                                    inner join scholaris_area ar on ar.id = a.area_id
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPeriodo->codigo'
                    order by a.orden, m.orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }
    
    
    public function get_notas_clases_normales_optativas($alumno) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $con = \Yii::$app->db;
        $query = "select 	mat.name as materia
                                    ,mat.id as materia_id
                                    ,c.id as clase_id
                                    ,g.id as grupo_id
                                    ,l.p1
                                    ,l.p2
                                    ,l.p3
                                    ,l.pr1
                                    ,l.pr180
                                    ,l.ex1
                                    ,l.ex120
                                    ,l.q1
                                    ,l.p4
                                    ,l.p5
                                    ,l.p6
                                    ,l.pr2
                                    ,l.pr280
                                    ,l.ex2
                                    ,l.ex220
                                    ,l.q2
                                    ,l.final_ano_normal 
                                    ,l.mejora_q1
                                    ,l.mejora_q2
                                    ,l.final_con_mejora
                                    ,l.supletorio
                                    ,l.remedial
                                    ,l.gracia
                                    ,l.final_total
                                    ,m.tipo
                    from	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                                    inner join scholaris_materia mat on mat.id = m.materia_id
                                    inner join scholaris_malla_area a on a.id = m.malla_area_id
                                    inner join scholaris_area ar on ar.id = a.area_id
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPeriodo->codigo'
                                    and m.tipo in ('NORMAL','OPTATIVAS')
                    order by a.orden, m.orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    /**
     * PARA TOMAR NOTAS FINALES DE ALUMNO
     */
    public function get_notas_finales($alumno, $usuario, $malla) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(final_ano_normal),2) as final_ano_normal
                                    ,trunc(avg(final_total),2) as final_total
from (
select p1 as p1
		,p2 as p2
		,p3 as p3
		,pr1 as pr1
		,pr180 as pr180
		,ex1 as ex1
		,ex120 as ex120
		,q1 as q1
		,p4 as p4
		,p5 as p5
		,p6 as p6
		,pr2 as pr2
		,pr280 as pr280
		,ex2 as ex2
		,ex220 as ex220
		,q2 as q2
		,final_ano_normal as final_ano_normal
                                    ,final_total as final_total
from 	scholaris_notas_areas n
		inner join scholaris_malla_area a on a.id = n.malla_area_id		
where	n.alumno_id = $alumno
		and a.malla_id = $malla
		and a.promedia = true
		and n.usuario = '$usuario'
union all
select p1 as p1
		,p2 as p2
		,p3 as p3
		,pr1 as pr1
		,pr180 as pr180
		,ex1 as ex1
		,ex120 as ex120
		,q1 as q1
		,p4 as p4
		,p5 as p5
		,p6 as p6
		,pr2 as pr2
		,pr280 as pr280
		,ex2 as ex2
		,ex220 as ex220
		,q2 as q2
		,final_ano_normal as final_ano_normal
                                    ,final_total as final_total
from	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia m on m.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = m.malla_area_id
where	g.estudiante_id = $alumno
		and a.malla_id = $malla
		and m.promedia = true
) as nota;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

//    public function aprovechamiento_parcial($alumno, $bloque, $usuario, $malla) {
//        $con = Yii::$app->db;
//        $query = "select trunc(avg(nota),2) as nota
//                    from (
//                    select n.nota		
//                    from	scholaris_notas_areas n
//                                    inner join scholaris_malla_area a on a.id = n.malla_area_id		
//                    where	n.alumno_id = $alumno
//                                    and a.malla_id = $malla
//                                    and n.bloque_id = $bloque
//                                    and a.promedia = true
//                                    and n.usuario = '$usuario'
//                    union			
//                    select 	p.calificacion as nota		
//                    from	scholaris_resumen_parciales p
//                                    inner join scholaris_clase c on c.id = p.clase_id
//                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
//                                    inner join  scholaris_malla_area a on a.id = m.malla_area_id
//                    where	p.alumno_id = $alumno
//                                    and p.bloque_id = $bloque
//                                    and m.promedia = true
//                                    and a.malla_id = $malla
//                    ) as nota";
//        $res = $con->createCommand($query)->queryOne();
//
//        return $res;
//    }
//

    public function get_nota_proyectos($alumno, $periodoCod) {
        $con = Yii::$app->db;
        $query = "select 	avg(p1) as p1
		,avg(p2) as p2
		,avg(p3) as p3
		,avg(pr1) as pr1
		,avg(pr180) as pr180
		,avg(ex1) as ex1
		,avg(ex120) as ex120
		,avg(q1) as q1
		,avg(p4) as p4
		,avg(p5) as p5
		,avg(p6) as p6
		,avg(pr2) as pr2
		,avg(pr280) as pr280
		,avg(ex2) as ex2
		,avg(ex220) as ex220
		,avg(q2) as q2
		,avg(final_ano_normal) as final_ano_normal
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodoCod'
		and mm.tipo = 'PROYECTOS';";
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function get_nota_dhi($alumno, $periodoCod) {
        $con = Yii::$app->db;
        $query = "select 	avg(p1) as p1
		,avg(p2) as p2
		,avg(p3) as p3
		,avg(pr1) as pr1
		,avg(pr180) as pr180
		,avg(ex1) as ex1
		,avg(ex120) as ex120
		,avg(q1) as q1
		,avg(p4) as p4
		,avg(p5) as p5
		,avg(p6) as p6
		,avg(pr2) as pr2
		,avg(pr280) as pr280
		,avg(ex2) as ex2
		,avg(ex220) as ex220
		,avg(q2) as q2
		,avg(final_ano_normal) as final_ano_normal
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodoCod'
		and mm.tipo = 'DHI';";
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function get_notas_cualitativas($alumno, $usuario, $malla, $tipo) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(final_ano_normal),2) as final_ano_normal
from 	scholaris_notas_areas n
		inner join scholaris_malla_area a on a.id = n.malla_area_id		
where	n.alumno_id = $alumno
		and a.malla_id = $malla
		and n.usuario = '$usuario'
		and a.tipo = '$tipo';";
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function get_notas_finales_comportamiento($alumno) {

        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $arreglo = array();

        $con = Yii::$app->db;
        $query = "select l.p1,l.p2,l.p3
                                    ,l.p4,l.p5,l.p6
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPeriodo->codigo'
                                    and m.tipo = 'COMPORTAMIENTO';";

        $res = $con->createCommand($query)->queryOne();

        isset($res['p1']) ? $p1 = $res['p1'] : $p1 = 0;
        isset($res['p2']) ? $p2 = $res['p2'] : $p2 = 0;
        isset($res['p3']) ? $p3 = $res['p3'] : $p3 = 0;
        
        isset($res['p4']) ? $p4 = $res['p4'] : $p4 = 0;
        isset($res['p5']) ? $p5 = $res['p5'] : $p5 = 0;
        isset($res['p6']) ? $p6 = $res['p6'] : $p6 = 0;
        
        $nota1 = $this->homologaComportamiento($p1);
        $nota2 = $this->homologaComportamiento($p2);
        $nota3 = $this->homologaComportamiento($p3);

        $nota4 = $this->homologaComportamiento($p4);
        $nota5 = $this->homologaComportamiento($p5);
        $nota6 = $this->homologaComportamiento($p6);

        array_push($arreglo, $nota1['abreviatura']);
        array_push($arreglo, $nota2['abreviatura']);
        array_push($arreglo, $nota3['abreviatura']);
        array_push($arreglo, $nota4['abreviatura']);
        array_push($arreglo, $nota5['abreviatura']);
        array_push($arreglo, $nota6['abreviatura']);


        return $arreglo;
    }

    public function get_notas_finales_comportamiento_descripcion($alumno) {

        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $arreglo = array();

        $con = Yii::$app->db;
        $query = "select l.p1,l.p2,l.p3
                                    ,l.p4,l.p5,l.p6
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPeriodo->codigo'
                                    and m.tipo = 'COMPORTAMIENTO';";

        $res = $con->createCommand($query)->queryOne();

        $nota1 = $this->homologaComportamiento($res['p1']);
        $nota2 = $this->homologaComportamiento($res['p2']);
        $nota3 = $this->homologaComportamiento($res['p3']);

        $nota4 = $this->homologaComportamiento($res['p4']);
        $nota5 = $this->homologaComportamiento($res['p5']);
        $nota6 = $this->homologaComportamiento($res['p6']);


        array_push($arreglo, $nota1['descripcion']);
        array_push($arreglo, $nota2['descripcion']);
        array_push($arreglo, $nota3['descripcion']);
        array_push($arreglo, $nota4['descripcion']);
        array_push($arreglo, $nota5['descripcion']);
        array_push($arreglo, $nota6['descripcion']);


        return $arreglo;
    }

//
//    public function proyectos_parcial($alumno, $bloque, $usuario, $malla) {
//        $con = Yii::$app->db;
//        $query = "select trunc(avg(nota),2) as nota
//                    from (
//                    select n.nota		
//                    from	scholaris_notas_areas n
//                                    inner join scholaris_malla_area a on a.id = n.malla_area_id		
//                    where	n.alumno_id = $alumno
//                                    and a.malla_id = $malla
//                                    and n.bloque_id = $bloque
//                                    and a.tipo = 'PROYECTOS'    
//                                    and n.usuario = '$usuario'
//                    union			
//                    select 	p.calificacion as nota		
//                    from	scholaris_resumen_parciales p
//                                    inner join scholaris_clase c on c.id = p.clase_id
//                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
//                                    inner join  scholaris_malla_area a on a.id = m.malla_area_id
//                    where	p.alumno_id = $alumno
//                                    and p.bloque_id = $bloque
//                                    and m.tipo = 'PROYECTOS'
//                                    and a.malla_id = $malla
//                    ) as nota";
//        $res = $con->createCommand($query)->queryOne();
//
//        return $res;
//    }

    public function homologaProyectos($nota) {
        
        if(isset(Yii::$app->user->identity->periodo_id)){
            $periodoId = Yii::$app->user->identity->periodo_id;
        }else{
            $modelPer = ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            $periodoId = $modelPer->id;
        }
                         
        $modelPerido = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        if ($nota) {
            $nota = $nota;
        } else {
            $nota = 0;
        }

        $con = \Yii::$app->db;
        $query = "select 	abreviatura
                    from 	scholaris_tabla_escalas_homologacion
                    where 	$nota between rango_minimo and rango_maximo		
                                    and corresponde_a = 'PROYECTOS'
                                    and scholaris_periodo = '$modelPerido->codigo';";
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;

    }

//    public function get_notas_finales_comportamiento($alumno, $modelBloque, $usuario, $malla) {
//        $sentencias2 = new SentenciasNotas();
//        $digito = 2;
//        $resultado = array();
//
//        $suma = 0;
//        $cont = 0;
//
//        $sumaQuimestre = 0;
//
//        foreach ($modelBloque as $bloque) {
//
//            $nota = $this->comportamiento_parcial($alumno, $bloque->id, $usuario, $malla);
//
//            if ($bloque->tipo_bloque == 'PARCIAL') {
//                $suma = $suma + $nota['nota'];
//                $cont++;
//                $notaH = $this->homologaComportamiento($nota['nota']);
//
//                array_push($resultado, $notaH['abreviatura']);
////                array_push($resultado, $notaP);
//            } else {
//                
//                
//
//                array_push($resultado, '-');
//                array_push($resultado, '-');
//                array_push($resultado, '-');
//                array_push($resultado, '-');
//                array_push($resultado, $notaH['abreviatura']);
//            }
//        }
//        
//        array_push($resultado, $notaH['abreviatura']);
//
//        return $resultado;
//    }
//
//    public function comportamiento_parcial($alumno, $bloque, $usuario, $malla) {
//        $con = Yii::$app->db;
//        $query = "select trunc(avg(nota),2) as nota
//                    from (
//                    select n.nota		
//                    from	scholaris_notas_areas n
//                                    inner join scholaris_malla_area a on a.id = n.malla_area_id		
//                    where	n.alumno_id = $alumno
//                                    and a.malla_id = $malla
//                                    and n.bloque_id = $bloque
//                                    and a.tipo = 'COMPORTAMIENTO'    
//                                    and n.usuario = '$usuario'
//                    union			
//                    select 	p.calificacion as nota		
//                    from	scholaris_resumen_parciales p
//                                    inner join scholaris_clase c on c.id = p.clase_id
//                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
//                                    inner join  scholaris_malla_area a on a.id = m.malla_area_id
//                    where	p.alumno_id = $alumno
//                                    and p.bloque_id = $bloque
//                                    and m.tipo = 'COMPORTAMIENTO'
//                                    and a.malla_id = $malla
//                    ) as nota";
//        $res = $con->createCommand($query)->queryOne();
//
//        return $res;
//    }

    public function homologaComportamiento($nota=0) {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPerido = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        if (isset($nota)=='' || isset($nota) == null) {
            $nota = 0;
        } else {
            $nota = $nota;
        }

        $con = \Yii::$app->db;
        $query = "select 	abreviatura,descripcion
                    from 	scholaris_tabla_escalas_homologacion
                    where 	$nota between rango_minimo and rango_maximo		
                                    and corresponde_a = 'COMPORTAMIENTO'
                                    and scholaris_periodo = '$modelPerido->codigo';";
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }

    public function promediosClase($clase, $modelBloque) {

        $sentencias2 = new SentenciasNotas();
        $digito = 2;

        $arreglo = array();

        $suma = 0;
        $cont = 0;

        $sumaQuimestre = 0;

        foreach ($modelBloque as $bloque) {
            $nota = $this->get_promedio_clase_bloque($clase, $bloque->id);

            if ($bloque->tipo_bloque == 'PARCIAL') {
                $suma = $suma + $nota['nota'];
                $cont++;
                array_push($arreglo, $nota['nota']);
            } else {
                if ($cont == 0) {
                    $pr = 0;
                } else {
                    $pr = $suma / $cont;
                }

                $pr = $sentencias2->truncarNota($pr, $digito);
                $pr = number_format($pr, $digito);

                $pr80 = $pr * 80 / 100;
                $pr80 = $sentencias2->truncarNota($pr80, $digito);
                $pr80 = number_format($pr80, $digito);

                $ex20 = $nota['nota'] * 20 / 100;
                $ex20 = $sentencias2->truncarNota($ex20, $digito);
                $ex20 = number_format($ex20);

                $pq = $pr80 + $ex20;
                $pq = number_format($pq, $digito);

                $sumaQuimestre = $sumaQuimestre + $pq;

                $cont = 0;
                $suma = 0;

                array_push($arreglo, $pr);
                array_push($arreglo, $pr80);
                array_push($arreglo, $nota['nota']);
                array_push($arreglo, $ex20);
                array_push($arreglo, $pq);
            }

            array_push($arreglo, $nota['nota']);
        }

        return $arreglo;
    }

    private function get_promedio_clase_bloque($clase, $bloque) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(calificacion),2) as nota
                    from 	scholaris_resumen_parciales
                    where	clase_id = $clase
                                    and bloque_id = $bloque";
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function cuadroAprovechamiento($periodo) {
        $con = Yii::$app->db;

        $query = "select id, abreviatura, descripcion, rango_minimo, rango_maximo
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'APROVECHAMIENTO'
                                    and scholaris_periodo = '2018-2019'
                    order by rango_minimo desc;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function cuadroTotalesAprovechamiento($clase, $orden, $minimo, $maximo) {


        $con = Yii::$app->db;

        $query = "select count(p.id) as total
                    from	scholaris_resumen_parciales p
                                    inner join scholaris_bloque_actividad b on b.id = p.bloque_id
                    where	p.clase_id = $clase
                                    and b.orden = $orden
                                    and p.calificacion between $minimo and $maximo;";

        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    public function tipo_de_actividades_clase($alumno, $clase, $orden) {
        $con = Yii::$app->db;
        $query = "select t.id, t.nombre_nacional, t.orden, c.grupo_numero
                    from	scholaris_calificaciones c
                                    inner join scholaris_actividad a on a.id = c.idactividad
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
                                    inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
                    where 	a.paralelo_id = $clase
                                    and b.orden = $orden
                                    and a.calificado = 'SI'
                                    and c.idalumno = $alumno
                    group by t.id, t.nombre_nacional, t.orden, c.grupo_numero
                    order by t.orden;";

//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function insumos_reporte_padre($alumno, $clase, $orden) {
        $con = Yii::$app->db;
        $query = "select 	g.nombre_grupo as nombre_nacional
		,c.grupo_numero		
from   	scholaris_calificaciones c 
		inner join scholaris_actividad a on a.id = c.idactividad 
		inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id 
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id 
		inner join scholaris_grupo_orden_calificacion g on g.grupo_numero = c.grupo_numero
where 	a.paralelo_id = $clase 
		and b.orden = $orden 
		and a.calificado = 'SI' 
		and c.idalumno = $alumno 
group by g.nombre_grupo,c.grupo_numero 
order by c.grupo_numero;";

//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function actividades_clase($alumno, $clase, $grupo, $orden) {
        $con = Yii::$app->db;
        //        $query = "select 'dkjkldsjfkljsdl' as title, c.calificacion
        $query = "select a.title, c.calificacion, c.grupo_numero
                    from	scholaris_calificaciones c
                                    inner join scholaris_actividad a on a.id = c.idactividad 
                                    inner join  scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
                    where	c.grupo_numero = $grupo
                                    and c.idalumno = $alumno
                                    and a.paralelo_id = $clase
                                    and a.calificado = 'SI' and b.orden = $orden and c.calificacion > 0;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function get_areas_alumno($alumno, $tipo) {

        $periodo = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	a.id
                                ,ar.name as area
                                ,a.se_imprime
                                ,a.promedia
                                ,a.tipo
                                ,a.total_porcentaje
                                ,a.es_cuantitativa
                                ,a.area_id as area_id
                from	scholaris_grupo_alumno_clase g
                                inner join scholaris_clase c on c.id = g.clase_id
                                inner join scholaris_periodo p on p.codigo = c.periodo_scholaris
                                inner join scholaris_malla_materia m on m.id = c.malla_materia
                                inner join scholaris_malla_area a on a.id = m.malla_area_id
                                inner join scholaris_area ar on ar.id = a.area_id
                where	g.estudiante_id = $alumno
                                and p.id = $periodo
                                and a.tipo in ($tipo)
                group by a.id
                                ,ar.name
                                ,a.se_imprime
                                ,a.promedia
                                ,a.tipo
                                ,a.total_porcentaje
                                ,a.es_cuantitativa
                                ,a.area_id
                order by a.orden;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function get_materias_alumno($area, $alumno) {
        $periodo = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	m.id
                                ,c.id as clase_id
                                ,mat.name as materia
                                ,m.es_cuantitativa
                                ,m.promedia
                                ,m.se_imprime
                                ,m.tipo
                                ,c.promedia as clase_promedia
                                ,mat.id as materia_id
                                ,g.id as grupo_id
                from	scholaris_grupo_alumno_clase g
                                inner join scholaris_clase c on c.id = g.clase_id
                                inner join scholaris_malla_materia m on m.id = c.malla_materia
                                inner join scholaris_materia mat on mat.id = m.materia_id
                                inner join scholaris_periodo p on p.codigo = c.periodo_scholaris
                where	g.estudiante_id = $alumno
                                and m.malla_area_id = $area
                                and p.id = $periodo
                order by m.orden;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function toma_dias_asistidos_parcial($paralelo, $alumno, $ordenBloque) {
        $arreglo = array();

        $at = $this->faltas_atrasos($paralelo, $alumno, 'atraso', $ordenBloque);
        array_push($arreglo, $at['total']);

        $fj = $this->faltas_atrasos($paralelo, $alumno, 'falta_justificada', $ordenBloque);
        array_push($arreglo, $fj['total']);

        $fi = $this->faltas_atrasos($paralelo, $alumno, 'falta', $ordenBloque);
        array_push($arreglo, $fi['total']);
        array_push($arreglo, $fi['dias_laborados']);

//        print_r($arreglo);
//        die();

        return $arreglo;
    }

    private function faltas_atrasos($paralelo, $alumno, $campo, $orden) {
        $con = Yii::$app->db;
        $query = "select 	count(d.id) as total
                                ,b.dias_laborados
                    from 	scholaris_toma_asistecia_detalle d
                                    inner join scholaris_toma_asistecia a on a.id = d.toma_id
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_id
                    where	a.paralelo_id = $paralelo
                                    and b.orden = $orden
                                    and d.alumno_id = $alumno
                                    and d.$campo = true group by b.dias_laborados;";
        $res = $con->createCommand($query)->queryOne();
//    echo $query;
//    die();
        return $res;
    }
    
    
    public function toma_promedio_area_paralelo($areaId, $paralelo, $usuario){
        $con = Yii::$app->db;
        $query = "select 	trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1	
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(final_ano_normal),2) as final_ano_normal		
from 	scholaris_notas_areas 
where 	malla_area_id =  $areaId
		and usuario = '$usuario'
		and paralelo_id = $paralelo;";
        $res = $con->createCommand($query)->queryOne();
//    echo $query;
//    die();
        return $res;
    }
    
    
    
    

}
