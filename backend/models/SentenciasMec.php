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
class SentenciasMec extends \yii\db\ActiveRecord {

    public function get_paralelo($paralelo) {
        $model = \backend\models\OpCourseParalelo::findOne($paralelo);
        return $model;
    }

    public function get_materias_malla($malla, $tipo) {
        $con = Yii::$app->db;
        $query = "select m.id, m.nombre, m.tipo
                    from	scholaris_mec_v2_materia m
                                    inner join scholaris_mec_v2_area a on a.id = m.malla_area_id
                    where	a.malla_id = $malla and m.tipo = '$tipo';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_alumnos($paralelo) {
        $con = Yii::$app->db;
        $query = "select s.id
		,s.last_name
		,s.first_name
		,s.middle_name
		,i.inscription_state
from 	op_student_inscription i 
		inner join op_student s on s.id = i.student_id
where	i.parallel_id = $paralelo
order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_nota_materia($alumno, $clase, $campo) {
        $con = Yii::$app->db;
        $query = "select $campo as nota,l.supletorio, l.remedial, l.gracia,l.final_total,p6,p3
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.estudiante_id = $alumno
                                    and g.clase_id = $clase";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    public function get_promedio_quimestre($alumno, $campo, $periodo) {
        $con = Yii::$app->db;
        $query = "select trunc(avg($campo),2) as nota
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mat on mat.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = mat.malla_area_id
		inner join scholaris_malla mal on mal.id = a.malla_id
where	g.estudiante_id = $alumno
		and mal.periodo_id = $periodo
		and mat.tipo = 'NORMAL';";
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

    public function get_nota_materia_finales($alumno, $clase, $campo) {
        $con = Yii::$app->db;
        $query = "select $campo as nota,final_total,p6,final_ano_normal
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.estudiante_id = $alumno
                                    and g.clase_id = $clase";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    public function get_promedio_final_total($alumno, $periodo) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(final_total),2) as nota
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mat on mat.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = mat.malla_area_id
		inner join scholaris_malla mal on mal.id = a.malla_id
where	g.estudiante_id = $alumno
		and mal.periodo_id = $periodo
		and mat.tipo = 'NORMAL';";
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

    /** para cuadros definitivos * */
    public function get_nota_quimestre_v2($alumno, $materiaMec, $campo, $paralelo) {


        $modelMateriaMec = ScholarisMecV2MallaDisribucion::find()
                ->select(['materia_id', 'tipo_homologacion'])
                ->where(['materia_id' => $materiaMec])
                ->groupBy(['materia_id', 'tipo_homologacion'])
                ->all();

        if (count($modelMateriaMec) > 1) {
            return 'error';
        } else {
            //$tipo = $modelMateriaMec[0]->tipo_homologacion;
             
            $nota = $this->recibe_nota_quimestre_v2($alumno, $materiaMec, $campo, $paralelo);
            return $nota;
        }
    }

    public function recibe_nota_quimestre_v2($alumno, $materiaMec, $campo, $paralelo) {

               
        $modelMateriaMec = ScholarisMecV2MallaDisribucion::find()
                ->select(['materia_id', 'tipo_homologacion'])
                ->where(['materia_id' => $materiaMec])
                ->groupBy(['materia_id', 'tipo_homologacion'])
                ->one();

        if ($modelMateriaMec) {
            $valor = $modelMateriaMec->tipo_homologacion;

            if ($valor == 'MATERIA') {
                
                
//                $nota = $this->toma_notas_materias($materiaMec, $alumno, $paralelo, $campo);
                $nota = $this->toma_notas_materias($materiaMec, $alumno, $paralelo, $campo);
                
            } else {
                $nota = $this->toma_notas_area($materiaMec, $alumno, $paralelo, $campo);
            }
        } else {
            $nota = 'sin conf';
        }

        return $nota;
    }

    public function toma_notas_materias($materiaMec, $alumno, $paralelo, $campo) {

        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        //$arregloSource = array();
        $materias = 0;
        $modelDist = ScholarisMecV2MallaDisribucion::find()->where(['materia_id' => $materiaMec])->all();
        
        if ($modelDist[0]->tipo_homologacion == 'AREA') {
            $areaId = $modelDist[0]->codigo_materia_source;

            $con = Yii::$app->db;
            $query = "select 	sum(trunc(l.p1 * mm.total_porcentaje/100,2)) as p1
		,sum(trunc(l.p2 * mm.total_porcentaje/100,2)) as p2
		,sum(trunc(l.p1 * mm.total_porcentaje/100,2)) as p3
		,sum(trunc(l.pr1 * mm.total_porcentaje/100,2)) as pr1
		,sum(trunc(l.pr180 * mm.total_porcentaje/100,2)) as pr180
		,sum(trunc(l.ex1 * mm.total_porcentaje/100,2)) as ex1
		,sum(trunc(l.ex120 * mm.total_porcentaje/100,2)) as ex120
		,sum(trunc(l.q1 * mm.total_porcentaje/100,2)) as q1
		,sum(trunc(l.p4 * mm.total_porcentaje/100,2)) as p4
		,sum(trunc(l.p5 * mm.total_porcentaje/100,2)) as p5
		,sum(trunc(l.p6 * mm.total_porcentaje/100,2)) as p6
		,sum(trunc(l.pr2 * mm.total_porcentaje/100,2)) as pr2
		,sum(trunc(l.pr280 * mm.total_porcentaje/100,2)) as pr280
		,sum(trunc(l.ex2 * mm.total_porcentaje/100,2)) as ex2
		,sum(trunc(l.ex220 * mm.total_porcentaje/100,2)) as ex220
		,sum(trunc(l.q2 * mm.total_porcentaje/100,2)) as q2
		,sum(trunc(l.final_ano_normal * mm.total_porcentaje/100,2)) as final_ano_normal
                                    ,sum(trunc(l.final_con_mejora * mm.total_porcentaje/100,2)) as final_con_mejora
		,trunc(sum(l.final_total * mm.total_porcentaje/100),2) as final_total
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id 
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mm on mm.id  = c.malla_materia
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                                    inner join scholaris_area a on a.id = ma.area_id
--where 	c.paralelo_id = $paralelo
where 	c.periodo_scholaris = '$modelPeriodo->codigo'
		and g.estudiante_id  = $alumno
		and ma.promedia = true
		and a.id = $areaId;";
//            echo $query;
//            die();
            $res = $con->createCommand($query)->queryOne();
            return $res[$campo];
        } else {
            foreach ($modelDist as $dis) {
                 $materias .= ',' . $dis->codigo_materia_source;
            }


            $con = \Yii::$app->db;
            $query = "select 	$campo 
                    from 	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                    where 	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPeriodo->codigo'
                                    and c.idmateria in ($materias)
                    order by $campo desc
                    limit 1;";

            $res = $con->createCommand($query)->queryOne();
            
            if(isset($res[$campo])){
                $nota = $res[$campo];
            }else{
                $nota = 0;
            }

            return $nota;
        }
    }

    private function toma_notas_area($materiaMec, $alumno, $paralelo, $campo) {


        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        //$arregloSource = array();
        $materias = 0;
        $modelDist = ScholarisMecV2MallaDisribucion::find()->where(['materia_id' => $materiaMec])->all();
        foreach ($modelDist as $dis) {
            //array_push($arregloSource,$dis->codigo_materia_source);
            $materias .= ',' . $dis->codigo_materia_source;
        }

        $con = \Yii::$app->db;
        $query = "select 	trunc(sum($campo*mm.total_porcentaje/ma.total_porcentaje),2) as $campo
                    from	scholaris_clase c
                                    inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
                                    inner join scholaris_materia m on m.id = c.idmateria
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia
                                    inner join scholaris_malla_area ma on ma.id = mm.malla_area_id
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$modelPeriodo->codigo'
                                    and m.area_id in ($materias)
                    order by trunc(sum($campo*mm.total_porcentaje/ma.total_porcentaje),2) desc
                    limit 1;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();

        return $res[$campo];
    }

    public function get_matrciculados($paralelo) {
        $con = Yii::$app->db;
        $query = "select 	s.last_name
                                ,s.first_name
                                ,s.middle_name
                                ,substr(e.name,4,10) as folio
                                ,i.inscription_state
                from	op_student_inscription i
                                inner join op_student s on s.id = i.student_id
                                inner join op_student_enrollment e on e.inscription_id = i.id
                where	i.parallel_id = $paralelo
                order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    /** FIN para cuadros definitivos * */
}
