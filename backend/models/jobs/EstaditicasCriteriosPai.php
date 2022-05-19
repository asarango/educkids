<?php

namespace backend\models\jobs;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class EstaditicasCriteriosPai{

    public function llena_tabla_dw(){
        $con    = Yii::$app->dbdwh;
        $queryDelete  = "delete from dw_estadisticas_criterios_pai";
        $con->createCommand($queryDelete)->execute();

        $periodoId = Yii::$app->user->identity->periodo_id;        
        
        $queryLlena = "insert into dw_estadisticas_criterios_pai
        select * from dblink(
            'hostaddr=127.0.0.1 port=5432 dbname=quito user=master password=Nersisbest.-2122',
            'select 	cur.name as curso
                                ,par.name as paralelo
                                ,concat(f.x_first_name,'' '', f.last_name) as docente
                                ,mat.name as materia
                                ,a.paralelo_id as clase_id
                                ,b.quimestre
                                ,b.id as bloque_id
                                ,b.name as bloque
                                ,t.nombre_nacional 
                                ,cri.criterio 
                                ,count(cri.criterio) as total 
                                ,p.codigo
                from 	scholaris_actividad a
                                inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id 
                                inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id 
                                inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                                inner join scholaris_actividad_descriptor ad on ad.actividad_id = a.id
                                inner join scholaris_criterio cri on cri.id = ad.criterio_id 
                                inner join scholaris_clase cla on cla.id = a.paralelo_id 
                                inner join op_course cur on cur.id = cla.idcurso 
                                inner join op_course_paralelo par on par.id = cla.paralelo_id 
                                inner join op_faculty f on f.id = cla.idprofesor 
                                inner join scholaris_materia mat on mat.id = cla.idmateria 
                where 	p.id = $periodoId
                                and a.tipo_calificacion = ''P''
                group by cur.name
                                ,par.name
                                ,concat(f.x_first_name,'' '', f.last_name) 
                                ,a.paralelo_id 
                                ,b.quimestre
                                ,b.id
                                ,b.name
                                ,t.nombre_nacional 
                                ,cri.criterio
                                ,mat.name
                                ,p.codigo
                order by a.paralelo_id, b.orden, t.nombre_nacional, cri.criterio;'
        ) as (name varchar, paralelo varchar, docente varchar, materia varchar
              ,clase_id int, quimestre varchar, bloque_id int, bloque varchar, nombre_nacional varchar, criterio varchar 
              ,total int, periodo_id varchar);";
        $con->createCommand($queryLlena)->execute();
    }


}