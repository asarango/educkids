<?php
namespace backend\models\kids\micro;

use backend\models\KidsUnidadMicro;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class PlanExperiencia extends ActiveRecord{
    private $microId;
    private $micro;
    private $opCourseTemplateId;
    public $response;


    public function __construct($microId){
        $this->microId = $microId;
        $this->micro = KidsUnidadMicro::findOne($this->microId);

        $this->opCourseTemplateId = $this->micro->pca->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;
        
        $this->response();
    }

    private function response(){
        $this->response = array(
            'disponibles' => $this->get_disponibles(),     
            'seleccionadas' => $this->get_seleccionadas()       
        );
    }
    
    private function get_disponibles(){
        $con = Yii::$app->db;
        $query = "select 	d.id
                            ,ce.nombre as criterio_evaluacion
                            ,e.nombre as eje
                            ,a.nombre as ambito
                            ,d.codigo 
                            ,d.nombre as destreza
                            ,d.imprescindible 
                            ,d.criterio_evaluacion_id 
                    from 	cur_curriculo_destreza d
                            inner join cur_curriculo_ambito a on a.id = d.ambito_id 
                            inner join cur_curriculo_eje e on e.id = a.eje_id
                            inner join cur_curriculo_kids_criterio_evaluacion ce on ce.id = d.criterio_evaluacion_id 
                    where 	e.op_course_template_id = $this->opCourseTemplateId
                            and d.id not in (
                                select destreza_id from kids_micro_destreza where micro_id = $this->microId
                            );";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_seleccionadas(){
        $con = Yii::$app->db;
        $query = "select 	kd.id 
                            ,e.nombre as eje
                            ,a.nombre as ambito
                            ,d.nombre as detreza
                    from 	kids_micro_destreza kd
                            inner join cur_curriculo_destreza d on d.id = kd.destreza_id 
                            inner join cur_curriculo_ambito a on a.id = d.ambito_id 
                            inner join cur_curriculo_eje e on e.id = a.eje_id
                    where 	kd.micro_id = $this->microId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


}