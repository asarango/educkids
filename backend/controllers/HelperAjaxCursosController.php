<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter; 

class HelperAjaxCursosController extends Controller{


    /**
     * Undocumented function
     * Ajax para seleccionar objetivos disponibles
     *
     * @return void
     */

    public function actionPersona(){
        $nombres = $_POST['nombres'];
        
        $personas = $this->consulta_persona($nombres);

        $html = '';
        foreach($personas as $persona){
            $html.= '<div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" onclick="elije_persona(\''.$persona['login'].'\')">
            <label class="form-check-label" for="flexCheckDefault">';

            $html .= $persona['name'];
              
            $html .= '</label>
          </div>';
        }

        echo $html;
    }

    private function consulta_persona($nombres){
        $con = Yii::$app->db;
        $query = "select 	p.name
                            ,u.login 
                    from res_users u
                            inner join res_partner p on p.id = u.partner_id 
                    where name ilike '%$nombres%' limit 10;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionParalelos(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        
        $curso = $_POST['paralelo'];

        $cursos = $this->consulta_paralelos($periodoId, $institutoId, $curso);


        $html='';
        foreach($cursos as $curso){
            $html.= '<div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefaultG" onclick="elije_grupo(\''.$curso['id'].'\')">
            <label class="form-check-label" for="flexCheckDefaultG">';
            $html.= $curso['course'];
            $html.= '</label>';
            $html.= '</div>';
        }

        echo $html;

    }

    private function consulta_paralelos($periodoId, $intituteId, $cursoNombre){
        $con = Yii::$app->db;
        $query = "select 	p.id , concat(c.name, ' - ', p.name) as course
                    from 	op_course c
                            inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = c.period_id 
                            inner join op_course_template t on t.id = c.x_template_id 
                            inner join op_course_paralelo p on p.course_id = c.id 
                    where 	sop.scholaris_id = $periodoId
                            and c.x_institute = $intituteId 
                            and c.name ilike '%$cursoNombre%'
                    order by t.next_course_id desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


}
?>