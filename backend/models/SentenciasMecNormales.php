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
class SentenciasMecNormales extends \yii\db\ActiveRecord {
    
    
    public function nomina_alumnos($paralelo){
        $con = Yii::$app->db;
        $query = "select 	s.last_name
                            ,s.first_name
                            ,s.middle_name
                            ,substring(e.name,4) as folio
                            ,substring(e.name,4) as matricula
                            ,i.inscription_state as estado
                    from	op_student_inscription i
                            inner join op_student s on s.id = i .student_id
                            inner join op_student_enrollment e on e.inscription_id = i.id
                    where	i.parallel_id = $paralelo
                    order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    

    public function get_paralelo($paralelo) {
        $model = \backend\models\OpCourseParalelo::findOne($paralelo);
        return $model;
    }

//    public function get_materias($paralelo,$tipo) {
    public function get_materias($malla,$tipo) {
        $con = Yii::$app->db;
//        $query = "select c.id as clase_id
//                                    ,m.name as materia
//                                    ,mat.tipo
//                    from 	scholaris_clase c
//                                    inner join scholaris_malla_materia mat on mat.id = c.malla_materia
//                                    inner join scholaris_materia m on m.id = c.idmateria
//                                    inner join scholaris_malla_area a on a.id = mat.malla_area_id
//                    where	c.paralelo_id = $paralelo
//                                    and mat.tipo = '$tipo'
//                    order by a.orden,mat.orden;";
        
        $query = "select 	mm.id
                                    ,a.nombre
                                    ,mm.promedia
                    from 	scholaris_mec_v2_malla_materia mm
                                    inner join scholaris_mec_v2_malla_area ma on ma.id = mm.area_id
                                    inner join scholaris_mec_v2_asignatura a on a.id = mm.asignatura_id
                    where	ma.malla_id = $malla
                                    and mm.tipo = '$tipo'
                    order by ma.orden, mm.orden;";
        
//        echo $query;
//        die();
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

    public function get_nota_materia($paralelo, $curso, $alumno, $materia, $tipo) {
        $con = Yii::$app->db;
        $query = "select l.final_total, l.q1, l.q2, m.id, m.name
                    from	scholaris_mec_v2_homologacion h
                                    inner join scholaris_materia m on m.id = h.codigo_tipo
                                    inner join scholaris_clase c on c.idmateria = m.id
                                    inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
                                    inner join op_student_inscription i on i.student_id = g.estudiante_id
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                    inner join scholaris_mec_v2_distribucion d on d.id = h.distribucion_id
                    where	d.materia_id = $materia
                                    and i.parallel_id = $paralelo
                                    and c.idcurso = $curso
                                    and g.estudiante_id = $alumno
                                    and h.tipo = '$tipo' 
                    limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
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
    
    public function get_promedio_final_total($alumno,$periodo){
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
    
    public function get_observacion_supletorio($alumno, $periodo, $notaRemedial){
        $con = Yii::$app->db;
        $query = "select 
(select count(l.supletorio) as supletorio		
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mat on mat.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = mat.malla_area_id
		inner join scholaris_malla mal on mal.id = a.malla_id
where	g.estudiante_id = $alumno
		and mal.periodo_id = $periodo
		and mat.tipo = 'NORMAL')
,(select count(l.final_ano_normal) as remedial		
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mat on mat.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = mat.malla_area_id
		inner join scholaris_malla mal on mal.id = a.malla_id
where	g.estudiante_id = $alumno
		and mal.periodo_id = $periodo
		and mat.tipo = 'NORMAL'
		and final_ano_normal <= $notaRemedial);";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    public function get_nota_materia_comportamiento($alumno, $clase, $campo) {
        $con = Yii::$app->db;
        $query = "select $campo as nota,l.supletorio, l.remedial, l.gracia,l.final_total,p6,p3
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                    where	g.estudiante_id = $alumno
                                    and g.clase_id = $clase";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function get_areas($malla, $tipo){
        $con = Yii::$app->db;
        $query = "select a.id
		,ar.name as area
		,ar.id as area_id
                from 	scholaris_malla_area a
                                inner join scholaris_area ar on ar.id = a.area_id
                where	a.malla_id = $malla
                                and a.tipo = '$tipo'
                order by a.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function get_materias_area($paralelo, $area){
        $con = Yii::$app->db;
        $query = "select c.id
		,mat.name as materia
                                ,mat.id as materia_id
                from	scholaris_malla_materia m
                                inner join scholaris_clase c on c.malla_materia = m.id
                                inner join scholaris_materia mat on mat.id = c.idmateria
                                                and mat.id = m.materia_id
                where	m.malla_area_id = $area
                                and c.paralelo_id = $paralelo
                order by m.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function get_notas_clase($alumno, $clase){
        $con = Yii::$app->db;
        $query = "select l.final_ano_normal, l.final_total 
                    from 	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                    where	g.estudiante_id = $alumno
                                    and g.clase_id = $clase;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function numToLetras($valor) {
        switch ($valor){
            case 1:
                $letras = 'UNO';
                break;
            case 2:
                $letras = 'DOS';
                break;
            case 3:
                $letras = 'TRES';
                break;
            case 4:
                $letras = 'CUATRO';
                break;
            case 5:
                $letras = 'CINCO';
                break;
            case 6:
                $letras = 'SEIS';
                break;
            case 7:
                $letras = 'SIETE';
                break;
            case 8:
                $letras = 'OCHO';
                break;
            case 9:
                $letras = 'NUEVE';
                break;
            case 10:
                $letras = 'DIEZ';
                break;
            default:
                $letras = $valor;
                break;
        }
        return $letras;
    }
    
    public function decimalToLetras($u){
        if ($u==0)  {$ru = "CERO CERO ";} 
        elseif ($u==1)  {$ru = "CERO UNO ";} 
        elseif ($u==2)  {$ru = "CERO DOS ";} 
        elseif ($u==3)  {$ru = "CERO TRES ";} 
        elseif ($u==4)  {$ru = "CERO CUATRO ";} 
        elseif ($u==5)  {$ru = "CERO CINCO ";} 
        elseif ($u==6)  {$ru = "CERO SEIS ";} 
        elseif ($u==7)  {$ru = "CERO SIETE ";} 
        elseif ($u==8)  {$ru = "CERO OCHO ";} 
        elseif ($u==9)  {$ru = "CERO NUEVE";} 
        elseif ($u==10) {$ru = "DIEZ ";} 

        elseif ($u==11) {$ru = "ONCE ";} 
        elseif ($u==12) {$ru = "DOCE ";} 
        elseif ($u==13) {$ru = "TRECE ";} 
        elseif ($u==14) {$ru = "CATORCE ";} 
        elseif ($u==15) {$ru = "QUINCE ";} 
        elseif ($u==16) {$ru = "DIECISEIS ";} 
        elseif ($u==17) {$ru = "DIECISIETE ";} 
        elseif ($u==18) {$ru = "DIECIOCHO ";} 
        elseif ($u==19) {$ru = "DIECINUEVE ";} 
        elseif ($u==20) {$ru = "VEINTE ";} 

        elseif ($u==21) {$ru = "VEINTE Y UNO ";} 
        elseif ($u==22) {$ru = "VEINTE Y DOS";} 
        elseif ($u==23) {$ru = "VEINTE Y TRES ";} 
        elseif ($u==24) {$ru = "VEINTE Y CUATRO ";} 
        elseif ($u==25) {$ru = "VEINTE Y CINCO ";} 
        elseif ($u==26) {$ru = "VEINTE Y SEIS ";} 
        elseif ($u==27) {$ru = "VEINTE Y SIETE ";} 
        elseif ($u==28) {$ru = "VEINTE Y OCHO ";} 
        elseif ($u==29) {$ru = "VEINTE Y NUEVE ";} 
        elseif ($u==30) {$ru = "TREINTA ";} 

        elseif ($u==31) {$ru = "TREINTA Y UNO";} 
        elseif ($u==32) {$ru = "TREINTA Y DOS ";} 
        elseif ($u==33) {$ru = "TREINTA Y TRE";} 
        elseif ($u==34) {$ru = "TREINTA Y CUATRO";} 
        elseif ($u==35) {$ru = "TREINTA Y CINCO";} 
        elseif ($u==36) {$ru = "TREINTA Y SEIS";} 
        elseif ($u==37) {$ru = "TREINTA Y SIETE";} 
        elseif ($u==38) {$ru = "TREINTA Y OCHO";} 
        elseif ($u==39) {$ru = "TREINTA Y NUEVE";} 
        elseif ($u==40) {$ru = "CUARENTA";} 

        elseif ($u==41) {$ru = "CUARENTA Y UNO";} 
        elseif ($u==42) {$ru = "CUARENTA Y DOS";} 
        elseif ($u==43) {$ru = "CUARENTA Y TRES";} 
        elseif ($u==44) {$ru = "CUARENTA Y CUATRO";} 
        elseif ($u==45) {$ru = "CUARENTA Y CINCO";} 
        elseif ($u==46) {$ru = "CUARENTA Y SEIS";} 
        elseif ($u==47) {$ru = "CUARENTA Y SIETE";} 
        elseif ($u==48) {$ru = "CUARENTA Y OCHO";} 
        elseif ($u==49) {$ru = "CUARENTA Y NUEVE";} 
        elseif ($u==50) {$ru = "CINCUENTA";} 

        elseif ($u==51) {$ru = "CINCUENTA Y UNO";} 
        elseif ($u==52) {$ru = "CINCUENTA Y DOS";} 
        elseif ($u==53) {$ru = "CINCUENTA Y TRES";} 
        elseif ($u==54) {$ru = "CINCUENTA Y CUATRO";} 
        elseif ($u==55) {$ru = "CINCUENTA Y CINCO";} 
        elseif ($u==56) {$ru = "CINCUENTA Y SEIS";} 
        elseif ($u==57) {$ru = "CINCUENTA Y SIETE";} 
        elseif ($u==58) {$ru = "CINCUENTA Y OCHO";} 
        elseif ($u==59) {$ru = "CINCUENTA Y NUEVE";} 
        elseif ($u==60) {$ru = "SESENTA";} 

        elseif ($u==61) {$ru = "SESENTA Y UNO";} 
        elseif ($u==62) {$ru = "SESENTA Y DOS";} 
        elseif ($u==63) {$ru = "SESENTA Y TRES";} 
        elseif ($u==64) {$ru = "SESENTA Y CUATRO";} 
        elseif ($u==65) {$ru = "SESENTA Y CINCO";} 
        elseif ($u==66) {$ru = "SESENTA Y SEIS";} 
        elseif ($u==67) {$ru = "SESENTA Y SIETE";} 
        elseif ($u==68) {$ru = "SESENTA Y OCHO";} 
        elseif ($u==69) {$ru = "SESENTA Y NUEVE";} 
        elseif ($u==70) {$ru = "SETENTA";} 

        elseif ($u==71) {$ru = "SETENTA Y UNO";} 
        elseif ($u==72) {$ru = "SETENTA Y DOS";} 
        elseif ($u==73) {$ru = "SETENTA Y TRES";} 
        elseif ($u==74) {$ru = "SETENTA Y CUATRO";} 
        elseif ($u==75) {$ru = "SETENTA Y CINCO";} 
        elseif ($u==76) {$ru = "SETENTA Y SEIS";} 
        elseif ($u==77) {$ru = "SETENTA Y SIETE";} 
        elseif ($u==78) {$ru = "SETENTA Y OCHO";} 
        elseif ($u==79) {$ru = "SETENTA Y NUEVE";} 
        elseif ($u==80) {$ru = "OCHENTA";} 

        elseif ($u==81) {$ru = "OCHENTA Y UNO";} 
        elseif ($u==82) {$ru = "OCHENTA Y DOS";} 
        elseif ($u==83) {$ru = "OCHENTA Y TRES";} 
        elseif ($u==84) {$ru = "OCHENTA Y CUATRO";} 
        elseif ($u==85) {$ru = "OCHENTA Y CINCO";} 
        elseif ($u==86) {$ru = "OCHENTA Y SEIS";} 
        elseif ($u==87) {$ru = "OCHENTA Y SIETE";} 
        elseif ($u==88) {$ru = "OCHENTA Y OCHO";} 
        elseif ($u==89) {$ru = "OCHENTA Y NUEVE";} 
        elseif ($u==90) {$ru = "NOVENTA";} 

        elseif ($u==91) {$ru = "NOVENTA Y UNO";} 
        elseif ($u==92) {$ru = "NOVENTA Y DOS";} 
        elseif ($u==93) {$ru = "NOVENTA Y TRES";} 
        elseif ($u==94) {$ru = "NOVENTA Y CUATRO";} 
        elseif ($u==95) {$ru = "NOVENTA Y CINCO";} 
        elseif ($u==96) {$ru = "NOVENTA Y SEIS";} 
        elseif ($u==97) {$ru = "NOVENTA Y SIETE";} 
        elseif ($u==98) {$ru = "NOVENTA Y OCHO";} 
        else            {$ru = "NOVENTA Y NUEVE";} 
        return $ru; //Retornar el resultado 
    }
    

}
