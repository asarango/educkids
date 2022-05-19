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
 * ESTA CLASE TRATA CON PROCESAMIENTO DE NOTAS SOLO PARA CALITICACIONES NORMALES
 * Ejemplo para colegios ISM; ROSA DE JESUS; SANTO DOMINGO QUITO
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model
 * 
 * Esta clase debe ser invocada una sola vez.
 */
class ProcesaNotasNormales extends \yii\db\ActiveRecord {

    private $usuario;
    private $modelParalelo;
    private $paraleloId;
    private $modelAlumnos;
    private $arrayAreas;
    private $periodoId;
    private $periodoCodigo;
    private $notaMinima;
    private $tipoCalificacion;

    public function __construct($paralelo, $alumno) {
        
        //asigna usuario
        isset(\Yii::$app->user->identity->usuario) ? $this->usuario = \Yii::$app->user->identity->usuario : $this->usuario = 'admin';
        
        $this->busca_nota_minima();                                     //nusca la nota minima
        $this->toma_periodos();                                         // invoca metodo para poblar periodos
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);    //sentencia para guardar el modelo del paralelo   
        $this->paraleloId = $paralelo;                                  //asigna el paralelo a atributo
        $this->toma_array_alumnos($alumno);                             //Guardando modelo de alumnos//
        
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        
        $this->procesa_tablas();                                       //invoca metodo de procesamiento de datos de tablas
    }
    
    
    /******
     * METODO PARA ASIGNAR LA NOTA MINIMA
     */
    private function busca_nota_minima(){
        $parametros = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $this->notaMinima = $parametros->valor;
    }
    
    
    /**
     * METODO PARA POBLAR PERIODOS
     */
    private function toma_periodos(){
        
        if(isset(\Yii::$app->user->identity->periodo_id)){
            $this->periodoId = \Yii::$app->user->identity->periodo_id;
        }else{
            $modelPeriodo = ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            
            $this->periodoId = $modelPeriodo->id;
        }
        
        
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
    }
    
    
    /**
     * METODO PARA TOMAR LOS ALUMNOS
     * @param type $alumno
     */    
    private function toma_array_alumnos($alumno){
        $sentencias = new SentenciasAlumnos();
        if($alumno == ''){
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($this->modelParalelo->id); //seleccciona a todos los alumnos de paralelo
        }else{
            
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($this->modelParalelo->id, $alumno); //toma a un solo alumno del paralelo
        }
    }
    
    
    /***
     * PROCESAMIENTO DE TABLAS NORMALES
     * AREAS
     * PROMEDIOS
     */
    private function procesa_tablas(){
        
        $con = \Yii::$app->db;
        
        ////elimina tabala de procesamiento
        //esta informacion se regenera en cada llamado
        $queryEliminaAreas = "delete from scholaris_proceso_areas_calificacion_normal where paralelo_id = $this->paraleloId and usuario = '$this->usuario'";
        $queryEliminaProme = "delete from scholaris_proceso_promedios_calificacion_normal where paralelo_id = $this->paraleloId and usuario = '$this->usuario'";
        
        $con->createCommand($queryEliminaAreas)->execute();
        $con->createCommand($queryEliminaProme)->execute();
        ///// fin de elimina datos de tablas de lleno /////

        $this->modificaPrClaseLibreta();//modifica correctamente clase libreta 80%; 20% y totales de quimestres
        $this->calculaClaseLibreta();   //calcula y modifica correctamente 
        
        if($this->tipoCalificacion == 0){
            $this->modificaMejorasQ1Q2(); //modifica mejoras he iguala las que no estan con nota
            $this->modificaMejoras();
            $this->modificaMejorasConDosQuimestres();
        }
        
        $this->modificaNotaFinal();
                        
        $this->llena_areas();           //invoca metodo que llena areas con promedios         
                        
        $this->modificaPrAreas();       //modifica pr1 y pr2 para realizar correctamente los truncates
        
        $this->calculaAreasFinalAnoNormal();          //calcula 80%; 20% y totales de quimestres
        
        $this->calculaNotaFinalConMejora();          //calcula notas finales con la mejora de sus quimestres
               
        $this->procesa_promedios();     //invoca metodo de calculo de promedios
        
    }
    
    
    /********* 
     * M[etodo que realiza el calculo y modificacion de los valores de clase libreta de PR1 y PR2
     */
    private function modificaPrClaseLibreta(){
        $con = \Yii::$app->db;
        $query = "update 	scholaris_clase_libreta l
                    set		pr1 = (select case 
                                                                when p3 is not null or p3 <> 0 then trunc((p1+p2+p3)/3,2)
                                                    when p3 is null or p3 = 0 then trunc((p1+p2)/2,2)
                                                  end  
                                   from  scholaris_clase_libreta
                                   where grupo_id = g.id)
                                    ,pr2 = (select case 
                                                                            when p6 is not null or p6 <> 0 then trunc((p4+p5+p6)/3,2)
                                                                when p6 is null or p6 = 0 then trunc((p4+p5)/2,2)
                                                              end  
                                               from  scholaris_clase_libreta
                                               where grupo_id = g.id)
                    from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
                    where	g.id = l.grupo_id
                                    and i.student_id = g.estudiante_id
                                    and c.id = g.clase_id
                                    and i.parallel_id = $this->paraleloId
                                    and c.periodo_scholaris = '$this->periodoCodigo';";
        $con->createCommand($query)->execute();
    }
    
    
    /********
     * metodo que calcula correctamente los valores de 80 y 20 %
     */
    private function calculaClaseLibreta(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_clase_libreta l
set		pr180 = trunc(pr1 * 0.80,2)
		,q1 = trunc(pr1 * 0.80,2) + ex120 
		,pr280 = trunc(pr2 * 0.80,2)
		,q2 = trunc(pr2 * 0.80,2) + ex220
		,final_ano_normal = trunc((trunc(pr1 * 0.80,2) + ex120 + trunc(pr2 * 0.80,2) + ex220)/2,2)
from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
where	g.id = l.grupo_id
		and i.student_id = g.estudiante_id
		and c.id = g.clase_id
		and i.parallel_id = $this->paraleloId
		and c.periodo_scholaris = '$this->periodoCodigo';";
        $con->createCommand($query)->execute();
    }
    
    /*****
     * METODO QUE MODIFICA MEJORAS
     */
    
    private function modificaMejorasQ1Q2(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_clase_libreta l
set		mejora_q1 = (select case
								when mejora_q1 > q1 then mejora_q1								
								else q1 
						    end
						    from scholaris_clase_libreta 
						    where grupo_id = g.id
                                                    )
                ,mejora_q2 = (select case
								when mejora_q2 > q2 then mejora_q2								
								else q2 
						    end
						    from scholaris_clase_libreta 
						    where grupo_id = g.id
                                                    )                                    
from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
where	g.id = l.grupo_id
		and i.student_id = g.estudiante_id
		and c.id = g.clase_id
		and i.parallel_id = $this->paraleloId
		and c.periodo_scholaris = '$this->periodoCodigo';";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }
    
    private function modificaMejoras(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_clase_libreta l
set		final_con_mejora = (select case
								when mejora_q1 > q1 then trunc((mejora_q1 + q2)/2,2)
								when mejora_q2 > q2	then trunc((mejora_q2 + q1)/2,2)
								else final_ano_normal 
						    end
						    from scholaris_clase_libreta 
						    where grupo_id = g.id 
						   )                                    
from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
where	g.id = l.grupo_id
		and i.student_id = g.estudiante_id
		and c.id = g.clase_id
		and i.parallel_id = $this->paraleloId
		and c.periodo_scholaris = '$this->periodoCodigo';";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }
     
    
    /*****
     * METODO QUE MODIFICA MEJORAS CON DOS QUIMESTRES
     */
    private function modificaMejorasConDosQuimestres(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_clase_libreta l
set		final_con_mejora = (select case
                                        when mejora_q1 > q1 and mejora_q2 > q2 then trunc((mejora_q2 + mejora_q1)/2,2)
                                    end
                                    from scholaris_clase_libreta 
                                    where grupo_id = g.id 
                                   )
from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
where	g.id = l.grupo_id
		and i.student_id = g.estudiante_id
		and c.id = g.clase_id
		and i.parallel_id = $this->paraleloId
		and c.periodo_scholaris = '$this->periodoCodigo' and l.mejora_q1 > l.q1 
		and l.mejora_q2 > l.q2;";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }
     
    
    /**********
     * METODO QUE MODIFICA VALOR FINAL
     */
    private function modificaNotaFinal(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_clase_libreta l
set		final_total = (select case
                                 when supletorio >= $this->notaMinima or remedial >= $this->notaMinima or gracia >= $this->notaMinima then $this->notaMinima
				else final_con_mejora 
				end
				from scholaris_clase_libreta 
				where grupo_id = g.id 
				)
from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
where	g.id = l.grupo_id
		and i.student_id = g.estudiante_id
		and c.id = g.clase_id
		and i.parallel_id = $this->paraleloId
		and c.periodo_scholaris = '$this->periodoCodigo';	";
        $con->createCommand($query)->execute();
    }
     
    
    
    /**
     * METODO QUE LLENA AREAS CON CALCULO DE PROMEDIOS
     * TOMA DESDE LA TABLA CLASE LIBRETAS
     */
    private function llena_areas(){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_proceso_areas_calificacion_normal
select 	'$this->usuario', i.student_id, i.parallel_id
		,ma.area_id, a.name as area, ma.total_porcentaje, ma.promedia, ma.se_imprime 
		,sum(trunc(p1 * mm.total_porcentaje / ma.total_porcentaje,2)) as p1
		,sum(trunc(p2 * mm.total_porcentaje / ma.total_porcentaje,2)) as p2
		,sum(trunc(p3 * mm.total_porcentaje / ma.total_porcentaje,2)) as p3
		,sum(trunc(pr1 * mm.total_porcentaje / ma.total_porcentaje,2)) as pr1
		,sum(trunc(pr180 * mm.total_porcentaje / ma.total_porcentaje,2)) as pr180
		,sum(trunc(ex1 * mm.total_porcentaje / ma.total_porcentaje,2)) as ex1
		,sum(trunc(ex120 * mm.total_porcentaje / ma.total_porcentaje,2)) as ex120
		,sum(trunc(q1 * mm.total_porcentaje / ma.total_porcentaje,2)) as q1
		,sum(trunc(p4 * mm.total_porcentaje / ma.total_porcentaje,2)) as p4
		,sum(trunc(p5 * mm.total_porcentaje / ma.total_porcentaje,2)) as p5
		,sum(trunc(p6 * mm.total_porcentaje / ma.total_porcentaje,2)) as p6
		,sum(trunc(pr2 * mm.total_porcentaje / ma.total_porcentaje,2)) as pr2
		,sum(trunc(pr280 * mm.total_porcentaje / ma.total_porcentaje,2)) as pr280
		,sum(trunc(ex2 * mm.total_porcentaje / ma.total_porcentaje,2)) as ex2
		,sum(trunc(ex220 * mm.total_porcentaje / ma.total_porcentaje,2)) as ex220
		,sum(trunc(q2 * mm.total_porcentaje / ma.total_porcentaje,2)) as q2
                ,null
                ,sum(trunc(mejora_q1 * mm.total_porcentaje / ma.total_porcentaje,2)) as mejora_q1
                ,sum(trunc(mejora_q2 * mm.total_porcentaje / ma.total_porcentaje,2)) as mejora_q2
		,sum(trunc(final_con_mejora * mm.total_porcentaje / ma.total_porcentaje,2)) as final_con_mejora
                ,null
                ,null
                ,null
                ,sum(trunc(final_total * mm.total_porcentaje / ma.total_porcentaje,2)) as final_total
from 	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id  = i.period_id 
		inner join scholaris_periodo p on p.id = sop.scholaris_id 
		inner join scholaris_clase_libreta l on l.grupo_id = g.id 
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
		inner join scholaris_area a on a.id = ma.area_id 
where 	i.parallel_id = $this->paraleloId
		and p.id = $this->periodoId
		and mm.tipo in ('NORMAL', 'OPTATIVAS')
                and c.periodo_scholaris = '$this->periodoCodigo'
		--and i.student_id = 6456
group by i.student_id, i.parallel_id
		,ma.area_id, a.name, ma.total_porcentaje, ma.promedia, ma.se_imprime;";
        
//        echo $query;
//        die();
        
        $con->createCommand($query)->execute();
    }
    
    private function modificaPrAreas(){
        $con = \Yii::$app->db;
        $query = "update scholaris_proceso_areas_calificacion_normal n set pr1 = ( 
                    select  case 
                                    when p3 is not null or p3 <> 0 then trunc((p1+p2+p3)/3,2)
                                    when p3 is null or p3 = 0 then trunc((p1+p2)/2,2)
                            end
                            from 	scholaris_proceso_areas_calificacion_normal
                            where	usuario = n.usuario 
                                            and paralelo_id = n.paralelo_id 
                                            and area_id = n.area_id 
                                            and alumno_id = n.alumno_id 
                    ),
                    pr2 = ( 
                    select  case 
                                    when p6 is not null or p6 <> 0 then trunc((p4+p5+p6)/3,2)
                                    when p6 is null or p6 = 0 then trunc((p4+p5)/2,2)
                            end
                            from 	scholaris_proceso_areas_calificacion_normal
                            where	usuario = n.usuario 
                                            and paralelo_id = n.paralelo_id 
                                            and area_id = n.area_id 
                                            and alumno_id = n.alumno_id 
                    )
                    where	usuario = '$this->usuario'
                    and paralelo_id = $this->paraleloId;	";
        $con->createCommand($query)->execute();
    }
    
    
    /**
     * Calcula notas finales
     * despues de esto de debe correr metodo calula valor final con mejora, para sentar la nota con mejor
     */
    private function calculaAreasFinalAnoNormal(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_proceso_areas_calificacion_normal	
set		--pr180 = trunc(pr1 * 0.80,2)
		--,q1 = trunc(pr1 * 0.80,2) + ex120 
		--,pr280 = trunc(pr2 * 0.80,2)
		--,q2 = trunc(pr2 * 0.80,2) + ex220
		final_ano_normal = trunc((trunc(pr1 * 0.80,2) + ex120 + trunc(pr2 * 0.80,2) + ex220)/2,2)
		--,final_total = trunc((trunc(pr1 * 0.80,2) + ex120 + trunc(pr2 * 0.80,2) + ex220)/2,2)
where	usuario = '$this->usuario'
		and paralelo_id = $this->paraleloId;";
        $con->createCommand($query)->execute();
    }
    
    
     /*****
     * METODO QUE MODIFICA MEJORAS
     */
    
    private function modificaMejorasAreas(){
        $con = \Yii::$app->db;
        $query = "update  scholaris_clase_libreta l
set		mejora_q1 = (select case
								when mejora_q1 > q1 then mejora_q1								
								else q1 
						    end
						    from scholaris_clase_libreta 
						    where grupo_id = g.id
                                                    )
                ,mejora_q2 = (select case
								when mejora_q2 > q2 then mejora_q2								
								else q2 
						    end
						    from scholaris_clase_libreta 
						    where grupo_id = g.id
                                                    )                                    
from	scholaris_grupo_alumno_clase g, op_student_inscription i, scholaris_clase c 
where	g.id = l.grupo_id
		and i.student_id = g.estudiante_id
		and c.id = g.clase_id
		and i.parallel_id = $this->paraleloId
		and c.periodo_scholaris = '$this->periodoCodigo';";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }
    
    
    
    /**
     * Calcula notas finales con la mejora de quimstres
     * despues de esto de debe correr metodo calula valor final con mejora, para sentar la nota con mejor
     */
    private function calculaNotaFinalConMejora(){
//        $con = \Yii::$app->db;
//        $query = "update 	scholaris_proceso_areas_calificacion_normal 
//set		final_total = final_con_mejora 	
//where	paralelo_id = $this->paraleloId
//		and usuario  = '$this->usuario'
//		and mejora_q1 > 0 or mejora_q2 > 0;";
//        $con->createCommand($query)->execute();
    }
    
    
    
    /**
     * METODO QUE REALIZA EL CALCULO DE LOS PROMEDIOS CON CALIFICCIONES NORMALES
     */
    private function procesa_promedios(){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_proceso_promedios_calificacion_normal
                    select '$this->usuario', alumno_id, paralelo_id
                                    ,trunc( avg(p1),2 ) as p1 
                                    ,trunc( avg(p2),2 ) as p2
                                    ,trunc( avg(p3),2 ) as p3
                                    ,trunc( avg(pr1),2 ) as pr1
                                    ,trunc( avg(pr180),2 ) as pr180
                                    ,trunc( avg(ex1),2 ) as ex1 
                                    ,trunc( avg(ex120),2 ) as ex120 
                                    ,trunc( avg(q1),2 ) as q1 
                                    ,trunc( avg(p4),2 ) as p4 
                                    ,trunc( avg(p5),2 ) as p5
                                    ,trunc( avg(p6),2 ) as p6
                                    ,trunc( avg(pr2),2 ) as pr2
                                    ,trunc( avg(pr280),2 ) as pr280
                                    ,trunc( avg(ex2),2 ) as ex2
                                    ,trunc( avg(ex220),2 ) as ex220 
                                    ,trunc( avg(q2),2 ) as q2 
                                    ,trunc( avg(final_ano_normal),2 ) as final_ano_normal 
                                    ,trunc( avg(mejora_q1),2 ) as mejora_q1 
                                    ,trunc( avg(mejora_q2),2 ) as mejora_q2 
                                    ,null
                                    ,null
                                    ,null
                                    ,trunc( avg(final_con_mejora),2 ) as final_con_mejora 
                                    ,trunc( avg(final_total),2 ) as final_total
                    from (
                                    select 	'$this->usuario', a.alumno_id, a.paralelo_id
                                                    ,a.p1, a.p2, a.p3, a.pr1, a.pr180, a.ex1, a.ex120, a.q1
                                                    ,a.p4, a.p5, a.p6, a.pr2, a.pr280, a.ex2, a.ex220, a.q2
                                                    ,final_ano_normal as final_ano_normal 
                                                    ,mejora_q1, mejora_q2 as mejora_q2, final_con_mejora, supletorio as supletorio
                                                    ,remedial, gracia,final_total
                                    from 	scholaris_proceso_areas_calificacion_normal a
                                    where 	a.paralelo_id = $this->paraleloId
                                                    and a.promedia = true
                                                    --and a.alumno_id = 6456
                                    union all
                                    select 	'$this->usuario', i.student_id, i.parallel_id 
                                                    ,l.p1 as p1
                                                    ,l.p2 as p2
                                                    ,l.p3 as p3
                                                    ,l.pr1 as pr1
                                                    ,l.pr180 as pr180
                                                    ,l.ex1 as ex1
                                                    ,l.ex120 as ex120
                                                    ,l.q1 as q1
                                                    ,l.p4 as p4
                                                    ,l.p5 as p5
                                                    ,l.p6 as p6
                                                    ,l.pr2 as pr2
                                                    ,l.pr280 as pr280
                                                    ,l.ex2 as ex2
                                                    ,l.ex220 as ex220
                                                    ,l.q2 as q2
                                                    ,l.final_ano_normal as final_ano_normal 
                                                    ,l.mejora_q1 as mejora_q1
                                                    ,l.mejora_q2 as mejora_q2
                                                    ,l.final_con_mejora as final_con_mejora
                                                    ,l.supletorio as supletorio
                                                    ,l.remedial as remedial
                                                    ,l.gracia as gracia
                                                    ,l.final_total as final_total
                                    from 	op_student_inscription i
                                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id 
                                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id  = i.period_id 
                                                    inner join scholaris_periodo p on p.id = sop.scholaris_id 
                                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id 
                                                    inner join scholaris_clase c on c.id = g.clase_id 
                                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                                                    inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                                                    inner join scholaris_area a on a.id = ma.area_id 
                                    where 	i.parallel_id = $this->paraleloId
                                                    and p.id = $this->periodoId
                                                    and c.periodo_scholaris = '$this->periodoCodigo'
                                                    and mm.tipo in ('NORMAL', 'OPTATIVAS')
                                                    and mm.promedia = true
                                                    --and i.student_id = 6456
                    ) as promedio
                    group by alumno_id, paralelo_id";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }
    
    
    

}
