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
class TotalActividades{

    public function llena_actividades_a_dw(){
        $con    = Yii::$app->db;
        $queryDelete  = "delete from dw_total_actividades_paralelo";
        $con->createCommand($queryDelete)->execute();

        $queryLlena = "insert into dw_total_actividades_paralelo 
                            select 	c.paralelo_id 
                                    , a.inicio 
                                    ,count(a.id)
                            from 	scholaris_actividad a
                                    inner join scholaris_clase c on c.id = a.paralelo_id
                                    inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                            where 	a.calificado = 'SI'
                                    and p.id = 8
                            group by c.paralelo_id, a.inicio 
                            order by c.paralelo_id ;";
        $con->createCommand($queryLlena)->execute();
    }


}