<?php
namespace backend\models\kids;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use DateTime;

class ScriptsKids extends ActiveRecord{

    public function get_class_teacher(){
        $userLog = Yii::$app->user->identity->usuario;
        $periodId = Yii::$app->user->identity->periodo_id;
        $idTeacher = $this->get_op_faculty_id($userLog);
        
        $con = Yii::$app->db;
        $query = "select iam.id, im.nombre,  cur.name as curso, sec.code 
                    from scholaris_clase sc inner join ism_area_materia iam on iam.id = sc.ism_area_materia_id 
                            inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                            inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                            inner join ism_materia im on im.id = iam.materia_id 
                            inner join op_course_paralelo par on par.id = sc.paralelo_id 
                            inner join op_course cur on cur.id = par.course_id 
                            inner join op_section sec on sec.id = cur.section 
                    where sc.idprofesor = $idTeacher 
                            and ipm.scholaris_periodo_id = $periodId
                            and sec.code = 'PRES'
                    group by iam.id,  cur.name, sec.code, im.nombre
                    order by cur.name, im.nombre;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_op_faculty_id($userLog){
        $user = \backend\models\ResUsers::find()->where(['login' => $userLog])->one();
        $docente = \backend\models\OpFaculty::find()->where(['partner_id' => $user->partner_id])->one();

        return $docente->id;
    }

}