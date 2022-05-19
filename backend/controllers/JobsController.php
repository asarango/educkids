<?php

namespace backend\controllers;

use backend\models\jobs\EstaditicasCriteriosPai;
use backend\models\jobs\TotalActividades;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class JobsController extends Controller {

    // Job que elimna la saturacion de registros en las tablas temporales
    public function actionLiberaTablaNotasLibreta() {
        $con    = Yii::$app->db;
        $query1  = "truncate table scholaris_proceso_areas_calificacion_normal";
        $query2  = "truncate table scholaris_proceso_promedios_calificacion_normal";       
        $con->createCommand($query1)->execute();
        $con->createCommand($query2)->execute();
       
    }

    /**Job que regenera el total de actividades para que se presenten en
    la dosificaciòn de las actividades
    Ejemplo de ejecucion:
    http://192.168.47.128/educandi/backend/web/index.php?r=jobs%2Ftotal-actividades
    **/
    public function actionTotalActividades(){
        $procesar = new TotalActividades();
        $procesar->llena_actividades_a_dw();
    }


    /**
     * JOB QUE GENERA LOS CONTEOS DE LOS CRITERIOS PAI
     * FORMATIVAS Y SUMATIVAS
     * Ejemplo de ejecuciòn
     * http://192.168.47.128/educandi/backend/web/index.php?r=jobs%2Festadisticas-criterios-pai
     */
    public function actionEstadisticasCriteriosPai(){
        $procesar = new EstaditicasCriteriosPai();
        $procesar->llena_tabla_dw();
    }

    
}
    