<?php

namespace backend\models;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\OpStudent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class ProcesaNotas extends \yii\db\ActiveRecord {
    
    
       

    /**
     * TOMA LAS MALLAS DE LAS AREAS POR PARALELO Y PERIODO
     * @param type $paralelo
     * @param type $periodoCodigo
     * @return type
     */
    public function get_malla_areas_paralelo($paralelo, $periodoCodigo){
        $con = \Yii::$app->db;
        $query = "select 	ma.id
                                ,a.name
                                ,ma.se_imprime
                                ,ma.promedia
                from	op_student_inscription i
                                left join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                left join scholaris_clase c on c.id = g.clase_id
                                left join scholaris_malla_materia mm on mm.id = c.malla_materia
                                left join scholaris_malla_area ma on ma.id = mm.malla_area_id
                                left join scholaris_area a on a.id = ma.area_id
                where	i.parallel_id = $paralelo		
                                and c.periodo_scholaris = '$periodoCodigo'
                                and ma.tipo not in ('COMPORTAMIENTO','PROYECTOS')
                group by ma.id
                                ,a.name
                order by ma.orden;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    /***
     * METODO PARA TOMAR LAS MATERIAS POR EL AREA
     */
    public function get_malla_materias_paralelo($paralelo, $periodoCodigo, $mallaAreaId){
        $con = \Yii::$app->db;
        $query = "select 	m.name		
                                    ,mm.total_porcentaje
                                    ,mm.promedia
                                    --,f.last_name
                                    --,f.x_first_name
                                    ,m.id as materia_id
                                    --,c.id
                    from	op_student_inscription i
                                    left join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    left join scholaris_clase c on c.id = g.clase_id
                                    left join scholaris_malla_materia mm on mm.id = c.malla_materia
                                    left join scholaris_materia m on m.id = mm.materia_id
                                    left join op_faculty f on f.id = c.idprofesor
                    where	i.parallel_id = $paralelo		
                                    and c.periodo_scholaris = '$periodoCodigo'
                                    and mm.tipo not in ('COMPORTAMIENTO','PROYECTOS')
                                    and mm.malla_area_id = $mallaAreaId
                    group by m.name, mm.orden
                                    ,mm.total_porcentaje
                                    ,mm.promedia
                                    --,f.last_name
                                    --,f.x_first_name
                                    ,m.id
                                    --,c.id                                    
                    order by mm.orden;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    /**
     * METODO QUE BUSCA el tipo de calificacion y redirecciona a las tablas que contienen 
     * las notas procesadas
     * 
     * para normales: scholaris_proceso_areas_calificacion_normal
     * 
     * para calificaciones covid: scholaris_proceso_areas
     * 
     * @param type $alumnoId
     * @param type $tipoCalificacion
     * @param type $paraleloId
     * @param type $usuario
     * @param type $mallaId
     */
    public function get_areas($alumnoId, $tipoCalificacion, $paraleloId, $usuario, $mallaId){
        if($tipoCalificacion == 0){
            $areas = $this->get_areas_procesadas_normal($alumnoId, $usuario, $paraleloId, $mallaId);
        }else{
            $areas = $this->get_areas_procesadas_covid($alumnoId, $usuario, $paraleloId, $mallaId);
        }
        
        
        return $areas;
    }
    
    
    
    
    /**
     * METODO QUE DEVUELVE LAS MATERIAS DEL ESTUDIANTE POR AREA
     * @param type $alumnoId
     * @param type $areaId
     * @param type $periodoCodigo
     * @return type
     */
    public function get_materias_x_area($alumnoId, $areaId, $periodoCodigo) {
        $con = \Yii::$app->db;
        $query = "select 	c.id as clase_id 
		,c.idmateria as materia_id
		,m.name as materia
		,mm.promedia 
		,mm.se_imprime 
		,mm.total_porcentaje 
                ,g.id as grupo_id
from 	scholaris_clase c
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
		inner join scholaris_materia m on m.id = c.idmateria 
where 	ma.area_id = $areaId
		and g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$periodoCodigo';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    /** DEVUELEVE AREAS DE TIPO CALIFICACION NORMAL (TODA LAS AREAS EN GENERAL POR ALUMNO)
     * 
     * @param type $alumnoId
     * @param type $usuario
     * @param type $paraleloId
     * @param type $mallaId
     * @return type
     */
    private function get_areas_procesadas_normal($alumnoId, $usuario, $paraleloId, $mallaId){
        $con = \Yii::$app->db;
        $query = "select 	a.id 
                                ,a.name as area 
                                ,pa.promedia 
                                ,pa.se_imprime 
                from 	scholaris_proceso_areas_calificacion_normal pa
                                inner join scholaris_area a on a.id = pa.area_id
                                inner join scholaris_malla_area ma on ma.area_id = a.id 
                where	alumno_id = $alumnoId
                                and usuario = '$usuario'
                                and paralelo_id = $paraleloId
                                and ma.malla_id = $mallaId
                group by a.id,a.name, ma.orden, pa.promedia 
                                ,pa.se_imprime
                order by ma.orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    /** DEVUELVE AREAS DE TIPO DE CALIFICACION COVID
     * 
     * @param type $alumnoId
     * @param type $usuario
     * @param type $paraleloId
     * @param type $mallaId
     * @return type
     */
    private function get_areas_procesadas_covid($alumnoId, $usuario, $paraleloId, $mallaId) {
        $con = \Yii::$app->db;
        $query = "select 	a.id 
                                ,a.name as area 
                                ,pa.promedia 
                                ,pa.imprime 
                from 	scholaris_proceso_areas pa
                                inner join scholaris_area a on a.id = pa.area_id
                                inner join scholaris_malla_area ma on ma.area_id = a.id 
                where	alumno_id = $alumnoId
                                and usuario = '$usuario'
                                and paralelo_id = $paraleloId
                                and ma.malla_id = $mallaId
                group by a.id,a.name, ma.orden, pa.promedia 
                                ,pa.imprime
                order by ma.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    ///////////////////////   FIN DE AREAS DE TIPO CALIFICACION ///////////////

    
    
    /**
     * METODO QUE BUSCA LA NOTA DEL AREA
     * @param type $alumnoId
     * @param type $areaId
     * @param type $tipoCalificacion
     */
    
    public function busca_nota_area($alumnoId, $areaId, $tipoCalificacion, $paraleloId, $usuario){       
        
        if($tipoCalificacion == 0){
            $notas = $this->get_nota_area_normal($alumnoId, $areaId, $paraleloId, $usuario);
        }else{
            $notas = $this->get_nota_covid($alumnoId, $areaId, $paraleloId, $usuario);
        }
        
        return $notas;
        
    }
    
    
    private function get_nota_area_normal($alumnoId, $areaId, $paraleloId, $usuario){                
        
        
        $con = \Yii::$app->db;
        $query = "select 	usuario, alumno_id, paralelo_id, area_id, area, total_porcentaje, promedia, se_imprime
		, p1, p2, p3, pr1, pr180, ex1, ex120, q1, p4, p5, p6, pr2, pr280, ex2, ex220, q2
		, final_ano_normal, mejora_q1, mejora_q2, final_con_mejora
		, supletorio, remedial, gracia, final_total 
from 	scholaris_proceso_areas_calificacion_normal
where	usuario = '$usuario'
		and area_id = $areaId
		and paralelo_id = $paraleloId
		and alumno_id = $alumnoId;";        
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['p1']) ? $p1 = $res['p1'] : $p1 = 0;
        isset($res['p2']) ? $p2 = $res['p2'] : $p2 = 0;
        isset($res['p3']) ? $p3 = $res['p3'] : $p3 = 0;
        isset($res['pr1']) ? $pr1 = $res['pr1'] : $pr1 = 0;
        isset($res['pr180']) ? $pr180 = $res['pr180'] : $pr180 = 0;
        isset($res['ex1']) ? $ex1 = $res['ex1'] : $ex1 = 0;
        isset($res['ex120']) ? $ex120 = $res['ex120'] : $ex120 = 0;
        isset($res['q1']) ? $q1 = $res['q1'] : $q1 = 0;
        
        isset($res['p4']) ? $p4 = $res['p4'] : $p4 = 0;
        isset($res['p5']) ? $p5 = $res['p5'] : $p5 = 0;
        isset($res['p6']) ? $p6 = $res['p6'] : $p6 = 0;
        isset($res['pr2']) ? $pr2 = $res['pr2'] : $pr2 = 0;
        isset($res['pr280']) ? $pr280 = $res['pr280'] : $pr280 = 0;
        isset($res['ex2']) ? $ex2 = $res['ex2'] : $ex2 = 0;
        isset($res['ex220']) ? $ex220 = $res['ex220'] : $ex220 = 0;
        isset($res['q2']) ? $q2 = $res['q2'] : $q2 = 0;
        
        isset($res['mejora_q1']) ? $mejora_q1 = $res['mejora_q1'] : $mejora_q1 = 0;
        isset($res['mejora_q2']) ? $mejora_q2 = $res['mejora_q2'] : $mejora_q2 = 0;
        isset($res['final_ano_normal']) ? $final_ano_normal = $res['final_ano_normal'] : $final_ano_normal = 0;
        isset($res['final_con_mejora']) ? $final_con_mejora = $res['final_con_mejora'] : $final_con_mejora = 0;
        isset($res['final_total']) ? $final_total = $res['final_total'] : $final_total = 0;
        
        
        return array(
           // 'alumno_id' => $res['alumno_id'],
            'p1' => $p1,
            'p2' => $p2,
            'p3' => $p3,
            'pr1' => $pr1,
            'pr180' => $pr180,
            'ex1' => $ex1,
            'ex120' => $ex120,
            'q1' => $q1,
            'p4' => $p4,
            'p5' => $p5,
            'p6' => $p6,
            'pr2' => $pr2,
            'pr280' => $pr280,
            'ex2' => $ex2,
            'ex220' => $ex220,
            'q2' => $q2,
            'mejora_q1' => $mejora_q1,
            'mejora_q2' => $mejora_q2,
            'final_ano_normal' => $final_ano_normal,
            'final_con_mejora' => $final_con_mejora,
            'final_total' => $final_total
        );
    }
    
    private function get_nota_covid($alumnoId, $areaId, $paraleloId, $usuario) {
        $con = \Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_areas
                    where 	alumno_id = $alumnoId
                                    and paralelo_id = $paraleloId
                                    and usuario = '$usuario'
                                    and area_id = $areaId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    ////   TERMINA LAS NOTAS DEL AREA //////
    
    
    /**
     * METODO QUE ENCUENTRA LAS NOTAS DE LA MATERIA
     * @param type $alumnoId
     * @param type $materiaId
     * @param type $tipoCalificacion
     */
    
    public function get_nota_materia($alumnoId, $materiaId, $tipoCalificacion, $grupoId){
        if($tipoCalificacion == 0){
            $notas = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupoId])->one();
            return $notas;
            
        }else{
            
            //return $nota = $this->nota_materia_covid($alumnoId, $materiaId);
        }
    }
        
    
    
    private function nota_materia_covid($alumnoId, $materiaId) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, clase_id, materia_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_materias
                    where	usuario = '$this->usuario'
                                    and materia_id = $materiaId
                                    and alumno_id = $alumnoId
                            and paralelo_id = $this->paralelo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    /////// FIN DE NOTAS DE MATERIA /////
    
    
    /** **************   INCIA CONSULTA DE NOTAS FINALES ******************************
     * METODO QUE ENTREGA NOTAS FINALES DE ALMUNO
     * @param type $alumnoId
     * @param type $tipoCalificacion
     * @param type $paralelo
     * @param type $usuario
     * @return type
     */
    public function consulta_notas_finales($alumnoId, $tipoCalificacion, $paralelo, $usuario){
        
        if($tipoCalificacion == 0){
            $notas = $this->notas_finales_normales($alumnoId, $paralelo, $usuario);
        }else{
            $notas = $this->notas_finales_covid($alumnoId, $usuario, $paralelo);
        }
        
        return $notas;
    }
    
    private function notas_finales_normales($alumnoId, $paralelo, $usuario){
        $con = Yii::$app->db;
        $query = "select 	usuario, alumno_id, paralelo_id, p1, p2, p3, pr1, pr180, ex1, ex120, q1
                                , p4, p5, p6, pr2, pr280, ex2, ex220, q2, final_ano_normal, mejora_q1, mejora_q2
                                , final_con_mejora, supletorio, remedial, gracia, final_total 
                    from 	scholaris_proceso_promedios_calificacion_normal 
                    where	usuario = '$usuario'
                                    and alumno_id = $alumnoId
                                    and paralelo_id = $paralelo;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    private function notas_finales_covid($alumnoId, $usuario, $paralelo) {

        $con = Yii::$app->db;
        $query = "select 	bloque, nota 	 
                    from 	scholaris_proceso_promedios
                    where	usuario = '$usuario'
                                    and paralelo_id = $paralelo
                                    and alumno_id = $alumnoId
                                    --and bloque in ('q1','q2')
                    order by bloque;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    ///////////////////////// FIN DE NOTAS FINALES DE ALUMNO //////////////////////////
    
    
    public function consulta_comportamientos_y_proyectos($alumnoId, $paralelo, $usuario) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, comportamiento_notaq1, comportamiento_notaq2, proyectos_notaq1, proyectos_notaq2 
from 	scholaris_proceso_comportamiento_y_proyectos
where	paralelo_id = $paralelo
		and alumno_id = $alumnoId
                and usuario = '$usuario';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
}
