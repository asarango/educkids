<?php

namespace backend\controllers;

use backend\models\IsmAreaMateria;
use backend\models\kids\micro\Datos;
use backend\models\kids\micro\PlanExperiencia;
use backend\models\KidsMicroDestreza;
use backend\models\KidsMicroObjetivos;
use backend\models\KidsUnidadMicro;
use backend\models\CurCurriculoObjetivoIntegrador;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use Exception;

/**
 * KidsPcaController implements the CRUD actions for KidsPca model.
 */
class KidsExperienciaController extends Controller {

    public function actionIndex1() {
        $html = '';
        $experienciaId = $_GET['id']; //microId = experienciaId
        $micro = KidsUnidadMicro::findOne($experienciaId);

        $objDatos = new Datos($experienciaId);
        $datos = $objDatos->response;

        $objetivosSeleccionados = KidsMicroObjetivos::find()->where([
            'micro_id' => $experienciaId
        ])->all();
        // echo '<pre>';
        // print_r($objetivosSeleccionados);
        // die();

        $objetivosDisponibles =  $this->consulta_objetivos_disponibles($experienciaId);

        return $this->render('index1', [
                    'micro' => $micro,
                    'datos' => $datos,
                    'objetivosDisponibles' => $objetivosDisponibles,
                    'objetivosSeleccionados' => $objetivosSeleccionados,
                    'html' => $html
        ]);
    }

    public function actionPlanMicro() {

        $html = '';

        if ($_GET['bandera'] == 'criterios') {
            $microId = $_GET['micro_id'];
            $html = $this->html_plan($microId);
        } elseif ($_GET['bandera'] == 'seleccionadas') {
            $microId = $_GET['micro_id'];
            $html = $this->html_destrezas($microId);
        }

        return $html;
    }

    public function actionMicro() {

        $html = '';

        if ($_POST['bandera'] == 'plan') {
            $experienciaId = $_POST['id'];
            $html = $this->plan($experienciaId);
        } elseif ($_POST['bandera'] == 'crear') {
            $usuarioLog = Yii::$app->user->identity->usuario;
            $hoy = date('Y-m-d H:i:s');

            $destrezaId = $_POST['destreza_id'];
            $microId = $_POST['micro_id'];
            $this->insert_micro_destreza($microId, $destrezaId, $usuarioLog, $hoy);
            return true;
        } elseif ($_POST['bandera'] == 'Actualizar') {

//            echo '<pre>';
//            print_r($_POST);
//            die();
            $update = $this->update_micro_destreza($_POST);
            if ($update == true) {
                return $this->redirect([
                            'index1', 'id' => $_POST['micro_id']
                ]);
            } else {
                echo 'no se logró actualizar destreza';
            }
        } elseif ($_POST['bandera'] == 'Eliminar') {
            
            $model = KidsMicroDestreza::findOne($_POST['id'])->delete();
            return $this->redirect([
                        'index1', 'id' => $_POST['micro_id']
            ]);
        } elseif($_POST['bandera'] == 'objetivo') {
            
            $usuarioLog = Yii::$app->user->identity->usuario;
            $hoy = date('Y-m-d H:i:s');

            $model = new KidsMicroObjetivos();
            $model->micro_id = $_POST['micro_id'] ;
            $model->objetivo_id = $_POST['objetivo_id'] ;
            $model->created_at = $hoy ;
            $model->created = $usuarioLog;
            $model->save();
            // print_r($model);
            // die();

        }

        return $html;
    }

    private function html_destrezas($microId) {
        $con = Yii::$app->db;
        $query = "select 	kd.id 
                            ,e.nombre as eje
                            ,a.nombre as ambito
                            ,d.codigo as codigo
                            ,d.nombre as destreza
                            ,kd.actividades_aprendizaje
                            ,kd.recursos 
		                    ,kd.indicadores_evaluacion 
                    from 	kids_micro_destreza kd
                            inner join cur_curriculo_destreza d on d.id = kd.destreza_id
                            inner join cur_curriculo_ambito a on a.id = d.ambito_id
                            inner join cur_curriculo_eje e on e.id = a.eje_id 
                    where 	kd.micro_id = $microId;";
        $res = $con->createCommand($query)->queryAll();
        $html = '';

        foreach ($res as $r) {
            $html .= '<tr>';
            $html .= '<td>' . $r['eje'] . '</td>';
            $html .= '<td>' . $r['ambito'] . '</td>';
            $html .= '<td><b>' . $r['codigo'] . ' </b>' . $r['destreza'] . '</td>';
            $html .= '<td>' . $r['actividades_aprendizaje'] . '</td>';
            $html .= '<td>' . $r['recursos'] . '</td>';
            $html .= '<td>' . $r['indicadores_evaluacion'] . '</td>';
            $html .= '<td>';
            $html .= $this->modal_update($r['id'], $r['codigo']);
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    private function modal_update($id, $codigo) {

        $hoy = date('Y-m-d H:i:s');
        $model = KidsMicroDestreza::findOne($id);
        $usuarioUpdate = Yii::$app->user->identity->usuario;
//        echo '<pre>';
//        print_r($model);
//        die();

        $html = '';
        $html .= ' <a type="button" title="Editar" data-bs-toggle="modal" 
                        data-bs-target="#updateModal' . $id . '">
                        <i class="fas fa-edit" style="color:#0a1f8f"></i>
                    </a>';

        $html .= '<div class="modal fade" id="updateModal' . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editando Destreza '.$codigo.'</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">';

        $html .= Html::beginForm(['micro'], 'post');

        $html .= Html::input('hidden', 'id', $id, ['class' => 'my-text-medium']);

        $html .= Html::input('hidden', 'micro_id', $model->micro_id, ['class' => 'my-text-medium']);

//        $html .= Html::input('hidden', 'bandera', 'actualizar', ['class' => 'my-text-medium']);

        $html .= Html::label('Actividades de aprendizaje', '', ['class' => 'my-text-medium']);
        $html .= '<textarea class="form-control" rows="3" name="actividades_aprendizaje" >' . $model->actividades_aprendizaje . '</textarea>';

        $html .= Html::label('Recursos', '', ['class' => 'form-label']);
        $html .= '<textarea class="form-control" rows="3" name="recursos" >' . $model->recursos . '</textarea>';

        $html .= Html::label('Indicadores de evaluación', '', ['class' => 'my-text-medium']);
        $html .= '<textarea class="form-control" rows="3" name="indicadores_evaluacion" >' . $model->indicadores_evaluacion . '</textarea>';

        $html .= Html::input('hidden', 'updated_at', $hoy, ['class' => 'form-control']);
        $html .= Html::input('hidden', 'updated', $usuarioUpdate, ['class' => 'form-control']);

        $html .= '<div style="margin-top:10px; text-align:end">';
        $html .= '<input name="bandera" class="btn btn-danger" type="submit" value="Eliminar">';
        $html .= '<input name="bandera" class="btn btn-secondary" type="submit" value="Actualizar">';
        $html .= '</div>';
//        $html .= '<div style="margin-top:10px; text-align:end">';
//        $html .= '<input name="bandera" class="btn btn-secondary" type="submit" value="Actualizar">';
////        $html .= Html::submitButton('Actualizar', ['class' => 'btn btn-secondary']);
//        $html .= '</div>';

        $html .= Html::endForm();

        $html .= '</div>
            </div>
        </div>
    </div>';

        return $html;
    }

    private function html_plan($microId) {
        $con = Yii::$app->db;
        $query = "select 	c.codigo,c.nombre  
                    from 	kids_micro_destreza kd
                            inner join cur_curriculo_destreza d on d.id = kd.destreza_id
                            inner join cur_curriculo_kids_criterio_evaluacion c on c.id = d.criterio_evaluacion_id 
                    where 	kd.micro_id = $microId
                    group by c.codigo,c.nombre;";
        $res = $con->createCommand($query)->queryAll();
        $html = '';

        // $html .= '<ul>';
        foreach ($res as $r) {
            $html .= '<li><b>' . $r['codigo'] . '</b> - ' . $r['nombre'] . '</li>';
        }
        // $html .= '</ul>';

        return $html;
    }

    private function update_micro_destreza($post) {
        $id = $post['id'];
        $aP = $post['actividades_aprendizaje'];
        $r = $post['recursos'];
        $iE = $post['indicadores_evaluacion'];
        $hoy = $post['updated_at'];
        $userUpdate = $post['updated'];

        $con = Yii::$app->db;
        $query = "update kids_micro_destreza
                    set actividades_aprendizaje  = '$aP'
                            ,recursos = '$r'
                            ,indicadores_evaluacion = '$iE'
                            ,updated_at = '$hoy'
                            ,updated = '$userUpdate'
                    where id = $id;";

        return $con->createCommand($query)->execute() ? true : false;
    }

    private function insert_micro_destreza($microId, $destrezaId, $usuarioLog, $hoy) {
        $con = Yii::$app->db;
        $query = "insert into kids_micro_destreza (micro_id, destreza_id, actividades_aprendizaje, recursos, indicadores_evaluacion, created_at, created, updated_at, updated) 
        values($microId, $destrezaId, 'vacio', 'vacio', 'vacio', '$hoy', '$usuarioLog', '$hoy', '$usuarioLog');";

        $con->createCommand($query)->execute();
    }

    private function plan($experienciaId) {
        $html = '';
        $objPlan = new PlanExperiencia($experienciaId);
        $plan = $objPlan->response;

        foreach ($plan['disponibles'] as $dis) {
            $destrezaId = $dis['id'];
            $html .= '<tr>';
            $html .= '<td>' . $dis['codigo'] . '</td>';
            $html .= '<td>' . $dis['destreza'] . '</td>';
            $html .= '<td><a href="#" 
                        onclick="inserta_destreza(\'crear\', ' . $destrezaId . ')">Ingresar</a></td>';
        }

        return $html;
    }

    // Consulta objetivos integradores disponibles
    private function consulta_objetivos_disponibles($microId){
        $con = Yii::$app->db;
        $query = "select
                        *
                    from
                        cur_curriculo_objetivo_integrador ob
                    where
                        id not in(
                                    select
                                        id
                                    from
                                        kids_micro_objetivos kmo
                                    where
                                        micro_id = $microId	
                    )order by orden;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    //Elimina objetivos integradores por AJAX
    public function eliminaObjetivo(){
        $model = KidsMicroObjetivos::findOne($_POST['id'])->delete();
    }



}
