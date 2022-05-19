<?php
namespace backend\controllers;

use backend\models\PlanificacionOpciones;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter; 

use backend\models\PudPai;

class HelperPudPaiController extends Controller{

    /**
     * Undocumented function
     * Ajax para seleccionar objetivos disponibles
     *
     * @return void
     */
    public function actionAjaxCrearPregunta(){

        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');

        $planUnidadId = $_POST['planificacion_bloque_unidad_id'];
        $pregunta = $_POST['pregunta'];
        $tipo = $_POST['tipo'];
        $seccion = $_POST['seccion'];
        
        $model = new PudPai();
        $model->planificacion_bloque_unidad_id = $planUnidadId;
        $model->seccion_numero = $seccion;
        $model->tipo = $tipo;
        $model->contenido = $pregunta;
        $model->created = $usuarioLog;
        $model->created_at = $fechaHoy;
        $model->updated = $usuarioLog;
        $model->updated_at = $fechaHoy;
        $model->save();
    }


    public function actionAjaxMuestraPreguntas(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];

        $preguntas = PudPai::find()->where([                        
            'in', 'tipo', ['facticas', 'conceptuales', 'debatibles']
        ])
        ->andWhere([
            'planificacion_bloque_unidad_id' => $planUnidadId
        ])
        ->all();

        $html = '<div class="table table-responsive">';
        
        $html .= '<table class="table table-condensed table-bordered">';
        
        $html .= '<tr>'; //comienza fàcticas
            $html .= '<td width="40%"><b>Fácticas: </b>(Se basan en conocimientos y datos, ayudan a comprender terminología del enunciado, 
                            facilitan la comprensión, se pueden buscar)
                    </td>';
            $html .= '<td>';
            
            $html .= $this->modalDetallePreguntas('facticas', $preguntas);

        
        $html .= '</td>';
        $html .= '</tr>'; //termina fàcticas
        
        $html .= '<tr>'; //comienza conceptuales
            $html .= '<td width="40%"><b>Conceptuales: </b> (conectar los datos, comparar y  contrastar, explorar contradicciones
                                                            , comprensión más profunda, transferir a otras situaciones, contextos e ideas
                                                            , analizar y aplicar)
                    </td>';
            $html .= '<td>';

            $html .= $this->modalDetallePreguntas('conceptuales', $preguntas);
        
        $html .= '</td>';
        $html .= '</tr>';//fin de conceptuales

        $html .= '<tr>'; //comienza debatibles
            $html .= '<td width="40%"><b>Debatibles: </b>  (promover la discusión, debatir una posición, 
            explorar cuestiones importantes desde múltiples perspectivas, deliberadamente polémicas, presentar tensión, evaluar)
                    </td>';
            $html .= '<td>';
            $html .= $this->modalDetallePreguntas('debatibles', $preguntas);
        $html .= '</td>';
        $html .= '</tr>'; //fin de debatibles

        $html .= '</table>';
        $html .= '</div>';
        return $html;
    }

    private function modalDetallePreguntas($tipo, $preguntas){
        $html = '';
        foreach($preguntas as $pregunta){
            if($pregunta->tipo == $tipo){
                
                if($pregunta->tipo == 'facticas'){
                    $color = '#0a1f8f';
                }elseif($pregunta->tipo == 'conceptuales'){
                    $color = '#ff9e18';
                }else{
                    $color = '#ab0a3d';
                }


                $html .= '<a href="#"  data-bs-toggle="modal" data-bs-target="#editModal" 
                onclick="showEdit('.$pregunta->id.', \''.$pregunta->contenido.'\')"> 
                <span class="badge rounded-pill" 
                style="background-color: '.$color.'"><i class="fas fa-question-circle" aria-hidden="true"></i>';
                $html .= $pregunta->contenido;
                $html .= '</span>';
                $html .= '</a><br>';
            }
            
        }

        $html.= '<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">PREGUNTAS FÁCTICAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">
                    
                    <hr>';

                    $html .= '<input type="hidden" name="pre_id" id="input-edit-id">';
                    $html .= '<input type="text" name="facticas" class="form-control" id="input-edit">';                    
                      
                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="delete_pud()">Eliminar</button>
                      <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update()">Actualizar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';

        return $html;
    }

    public function actionAjaxUpdate(){
        $id = $_POST['id'];
        $contenido = $_POST['contenido'];

        $model = PudPai::findOne($id);
        $model->contenido = $contenido;
        $model->save();
    }

    public function actionAjaxDelete(){
        $id = $_POST['id'];

        $model = PudPai::findOne($id);
        $model->delete();
    }


    /**
     * para acciones de seccion 3 de avaluacion
     *
     * @return void
     */

    public function actionMuestraSumativas(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];
        $sumativas = $this->consulta_sumativas($planUnidadId);        

        $html = '';
        $html .= '<small style="color: #65b2e8">Resumen de las tareas de evaluación sumativa y criterios de evaluación correspondientes:<hr></small>';
        foreach($sumativas as $sumativa){
            if($sumativa['contenido'] == 'sin contenido'){
                $color = 'red';
                $titulo = 'SIN TITULO';
            }else{
                $color = '';
                $titulo = $sumativa['titulo'];
            }

            $html .= '<div class="" style="color: '.$color.'">';
            $html .= $this->modal_sumativa($sumativa['id'], $sumativa['contenido'], $titulo);
            $html .= '<b>Criterio '.$sumativa['criterio'].'</b>: '.$titulo.'<br>';
            $html .= $sumativa['contenido'].'<hr>';
            $html .= '</div>';
        }

        return $html;
    }

    private function modal_sumativa($id, $contenido, $titulo){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modal'.$id.'"> 
        <i class="fas fa-edit"></i>';
        $html .= '</a>';

        $html.= '<div class="modal fade" id="modal'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CRITERIO '.$titulo.'</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>
        <div class="modal-body">

        <hr>';

        $html .= '<input type="text" name="facticas" class="form-control" id="input-titulo-sumativa'.$id.'" 
                placeholder="Ingrese el tema" value="'.$titulo.'"><br>'; 
                
        $html .= '<textarea name="sumativas" id="editor-sumativa'.$id.'" class="form-control">'.$contenido.'</textarea>
         <script>
         CKEDITOR.replace("editor-sumativa'.$id.'", {
         customConfig: "/ckeditor_settings/config.js"
         })
         </script>';

        $html .= '</div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_sumativa1('.$id.')">Actualizar</button>

        </div>
        </div>
        </div>
        </div>';
        return $html;
    }

    public function actionUpdateSumativas1(){
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $fechaHoy = date('Y-m-d H:i:s');
        $usuarioLog = Yii::$app->user->identity->usuario;
                
        $model = PudPai::findOne($id);
        $model->titulo = $titulo;
        $model->contenido = $contenido;
        $model->updated = $usuarioLog;
        $model->updated_at = $fechaHoy;
        $model->save();

    }


    private function consulta_sumativas($planUnidadId){
        $con = Yii::$app->db;
        $query = "select 	p.id
                            ,c.criterio 
                            ,p.titulo 
                            ,p.contenido 
                    from 	pud_pai p
                            inner join scholaris_criterio c on c.id = p.criterio_id 
                    where 	p.tipo = 'eval_sumativa'
                            and p.planificacion_bloque_unidad_id = $planUnidadId 
                    order by c.criterio;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function actionMuestraSumativas2(){
        
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];

        $model = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => 'relacion-suma-eval'
        ])->one();

        $html = $this->modal_sumativa2($model->id, $model->contenido);
        $html .= '<small style="color: #65b2e8">Relación entre las tareas de evaluación sumativa y el enunciado de la indagación:<hr></small>';
        $html .= '<p>';
        $html .= $model->contenido;
        $html .= '</p>';

        return $html;
    }
    

    private function modal_sumativa2($id, $contenido){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modalS2'.$id.'"> 
        <i class="fas fa-edit"></i>';
        $html .= '</a>';

        $html.= '<div class="modal fade" id="modalS2'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">RELACIÓN</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>
        <div class="modal-body">

        <hr>';
                
        $html .= '<textarea id="editor-sumativa2'.$id.'" name="sumativas" " class="form-control">'.$contenido.'</textarea>
         <script>
         CKEDITOR.replace("editor-sumativa2'.$id.'", {
         customConfig: "/ckeditor_settings/config.js"
         })
         </script>';

        $html .= '</div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_sumativa2('.$id.')">Actualizar</button>

        </div>
        </div>
        </div>
        </div>';
        return $html;
    }


    /**
     * PARA ENSENARA
     *
     * @return void
     */
    public function actionMuestraEnsenara(){
        $planUnidadId = $_GET['planUnidadId'];
        $ensenara = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'seccion_numero' => 4
        ])->all();

        $html = '<tr>';
        $html .= $this->busca_ensenara($ensenara, 'ensenara_comunicacion');
        $html .= $this->busca_ensenara($ensenara, 'ensenara_sociales');
        $html .= $this->busca_ensenara($ensenara, 'ensenara_autogestion');
        $html .= $this->busca_ensenara($ensenara, 'ensenara_investigacion');
        $html .= $this->busca_ensenara($ensenara, 'ensenara_pensamiento');
        $html .= '</tr>';

        return $html;
    }

    private function busca_ensenara($ensenara, $tipo){

        $html = '<td>';
        foreach($ensenara as $ens){
            
            if($ens->tipo == $tipo){
                $html .= $ens->contenido;
            }
        }
        $html .= '</td>';

        return $html;
    }

    public function actionUpdateEnsenara(){
        $planUnidadId = $_POST['planUnidadId'];
        $comunicacion = $_POST['comunicacion'];
        $sociales = $_POST['sociales'];
        $autogestion = $_POST['autogestion'];
        $investigacion = $_POST['investigacion'];
        $pensamiento = $_POST['pensamiento'];
        
        $this->actualiza_ensenara($planUnidadId, 'ensenara_comunicacion', $comunicacion);
        $this->actualiza_ensenara($planUnidadId, 'ensenara_sociales', $sociales);
        $this->actualiza_ensenara($planUnidadId, 'ensenara_autogestion', $autogestion);
        $this->actualiza_ensenara($planUnidadId, 'ensenara_investigacion', $investigacion);
        $this->actualiza_ensenara($planUnidadId, 'ensenara_pensamiento', $pensamiento);
        
    }

    private function actualiza_ensenara($planUnidadId, $tipo, $contenido){
        $model = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => $tipo
        ])->one();

        $model->contenido = $contenido;
        $model->save();
    }


    ///////////////FIN DE ENSENARA

    /***
     * PARA RECURSOS
     */
    public function actionMuestraRecursos(){
        $planUnidadId = $_GET['plan_unidad_id'];
        $pudPai = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'seccion_numero' => 8
        ])->all();

        $html = '';

        $html .= '<tr valign="top">';            
        $html .= '<td style="background-color: #eee" width="25%"><b>BIBLIOGRÁFICO: </b></td>';   
        
        $html .= '<td width="75%">';
        foreach($pudPai as $recurso){
            if($recurso->tipo == 'bibliografico'){
                $html .= $recurso->contenido. ' ';
            }
        }
        $html .= '</td>';
        $html .= '</tr>';                        

        $html .= '<tr>';                        
        $html .= '<td style="background-color: #eee"><b>TECNOLÓGICO: </b></td>';
        $html .= '<td id="td-tecnologico">';
        foreach($pudPai as $recurso){
            if($recurso->tipo == 'tecnologico'){
                $html .= $recurso->contenido. ' ';
            }
        }
        $html .= '</td>';
        $html .= '</tr>';
        
        $html .= '<tr valign="top">';            
        $html .= '<td style="background-color: #eee"><b>OTROS: </b></td>';
        $html .= '<td id="td-otros">';
        foreach($pudPai as $recurso){
            if($recurso->tipo == 'otros'){
                $html .= $recurso->contenido. ' ';
            }
        }
        $html .= '</td>';
        $html .= '</tr>'; 

        return $html;
    }

    public function actionUpdateRecurso(){
        
        $planUnidadId = $_POST['plan_unidad_id'];
        $bibliografico = $_POST['bibliografico'];
        $tecnologico = $_POST['tecnologico'];
        $otros = $_POST['otros'];

        $biblio = PudPai::find()->where(['planificacion_bloque_unidad_id' => $planUnidadId, 'tipo' => 'bibliografico'])->one();
        $tecnol = PudPai::find()->where(['planificacion_bloque_unidad_id' => $planUnidadId, 'tipo' => 'tecnologico'])->one();
        $otro = PudPai::find()->where(['planificacion_bloque_unidad_id' => $planUnidadId, 'tipo' => 'otros'])->one();

        $biblio->contenido = $bibliografico;
        $biblio->save();

        $tecnol->contenido = $tecnologico;
        $tecnol->save();

        $otro->contenido = $otros;
        $otro->save();

    }   
    ///////////fin de recursos


    /***
     * PARA REFLEXION
     */
    public function actionShowReflexionDisponibles(){
        $planUnidadId = $_GET['plan_unidad_id'];
        $disponibles = $this->consulta_reflexion_disponible($planUnidadId);
        
        $html = '<tr>';
            $html .= '<td>'.$this->muestra_pregunta($disponibles, 'antes').'</td>';
            $html .= '<td>'.$this->muestra_pregunta($disponibles, 'mientras').'</td>';
            $html .= '<td>'.$this->muestra_pregunta($disponibles, 'despues').'</td>';
        $html .= '</tr>';

        return $html;
    }

    private function muestra_pregunta($disponibles, $categoria){

        if($categoria == 'antes'){
            $color = '#0a1f8f';
        }elseif($categoria == 'mientras'){
            $color = '#9e28b5';
        }elseif($categoria == 'despues'){
            $color = '#ab0a3d';
        }

        $html = '';
        $html .= '<ul>';
        foreach($disponibles as $dispo){
            if($dispo['categoria'] == $categoria){
                $html .= '<li>';
                $html .= '<a class="zoom" href="#" 
                            onclick="inster_reflexion('.$dispo['id'].',\''.$categoria.'\')" 
                            style="color: '.$color.'">'.$dispo['opcion'].'</a>';
                $html .= '</li>';
            }        
        }
        $html .= '</ul>';

        return $html;
    }

    private function consulta_reflexion_disponible($planUnidadId){
        $con = Yii::$app->db;
        $query = "select 	id, tipo, categoria, opcion, seccion, estado 
                    from 	planificacion_opciones op 
                    where 	op.seccion = 'PAI'
                            and op.tipo = 'REFLEXION'
                            and op.estado = true
                            and op.opcion not in (
                                select 	contenido 
                                from 	pud_pai
                                where 	planificacion_bloque_unidad_id = $planUnidadId
                                        and contenido = op.opcion 		
                            )
                    order by op.categoria; ";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionInsertReflexion(){
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');

        $id = $_POST['id'];
        $planUnidadId = $_POST['plan_unidad_id'];
        $tipo = $_POST['tipo'];

        $opcion = PlanificacionOpciones::findOne($id);        

        $model = new PudPai();
        $model->planificacion_bloque_unidad_id = $planUnidadId;
        $model->seccion_numero = 9;
        $model->tipo = $tipo;
        $model->contenido = $opcion->opcion;
        $model->created_at = $fechaHoy;
        $model->created = $userLog;
        $model->updated_at = $fechaHoy;
        $model->updated = $userLog;
        $model->save();

    }


    public function actionShowReflexionSeleccionados(){
        $planUnidadId = $_GET['plan_unidad_id'];
        $reflexiones = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'seccion_numero' => 9,
        ])
        ->orderBy('tipo')
        ->all();

        $html = '<tr>';
            $html .= '<td>'.$this->devuelve_preguntas($reflexiones, 'antes').'</td>';
            $html .= '<td>'.$this->devuelve_preguntas($reflexiones, 'mientras').'</td>';
            $html .= '<td>'.$this->devuelve_preguntas($reflexiones, 'despues').'</td>';
        $html .= '</tr>';

        return $html;

    }

    private function devuelve_preguntas($reflexiones, $tipo){
        if($tipo == 'antes'){
            $color = '#0a1f8f';
        }elseif($tipo == 'mientras'){
            $color = '#9e28b5';
        }elseif($tipo == 'despues'){
            $color = '#ab0a3d';
        }


        $html = '';
        $html .= '<ul>';
        foreach($reflexiones as $refle){
            if($refle->tipo == $tipo){
                $html .= '<li style="color: '.$color.'">';
                $html .=  $this->modal_respuesta($refle->id, $refle->contenido, $refle->respuesta);
                $html .= '<b><u> '.$refle->contenido.'</u></b><br>'.$refle->respuesta;
                $html .= '</li>';

                $html .= '<li style="color: '.$color.'"><hr></li>';
            }                        
        }
        
        $html .= '</ul>';

        return $html;
    }

    private function modal_respuesta($id, $pregunta, $respuesta){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModalR'.$id.'" onclick="show_reflexion_disponibles()"> 
                        <i class="fas fa-reply"> </i>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="reflexionModalR'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">RESPUESTA DE PREGUNTA:</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

                    $html .= '<div class="modal-body">'; //Inicio de modal-body

                        $html .= '<b><u>'.$pregunta.'</u></b><br><br>';
                
                        $html .= '<div class="form-group">';
                        $html .= '<textarea class="form-control" id="textarea-respuesta-'.$id.'" onchange="update_reflexion('.$id.')">'.$respuesta.'</textarea>';
                        $html .= '</div>';

                      
                    $html .= '</div>';// fin de modal-body

                    $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                      
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="eliminar_reflexion('.$id.')">Eliminar</button>                      
                      <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Grabar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }

    public function actionUpdateReflexion(){
        $id = $_GET['id'];
        $respuesta = $_GET['respuesta'];

        $model = PudPai::findOne($id);
        $model->respuesta = $respuesta;
        $model->save();
    }

    public function actionDeleteReflexion(){
        $id = $_POST['id'];
        $model = PudPai::findOne($id);
        $model->delete();
    }

    ///////////fin de reflexion


    ///////////incicio de perfiles
    public function actionShowPerfilesDisponibles(){
        $planUnidadId = $_GET['plan_unidad_id'];           
        $comunicacion = $this->consulta_perfil_disponible($planUnidadId, 'comunicacion');
        $social = $this->consulta_perfil_disponible($planUnidadId, 'social');
        $autogestion = $this->consulta_perfil_disponible($planUnidadId, 'autogestion');
        $investigacion = $this->consulta_perfil_disponible($planUnidadId, 'autogestion');
        $pensamiento = $this->consulta_perfil_disponible($planUnidadId, 'pensamiento');

        $html = '<tr>';
            $html .= '<td>'.$this->muestra_perfiles_disponibles($comunicacion, 'comunicacion').'</td>';
            $html .= '<td>'.$this->muestra_perfiles_disponibles($social, 'social').'</td>';
            $html .= '<td>'.$this->muestra_perfiles_disponibles($autogestion, 'autogestion').'</td>';
            $html .= '<td>'.$this->muestra_perfiles_disponibles($investigacion, 'investigacion').'</td>';
            $html .= '<td>'.$this->muestra_perfiles_disponibles($pensamiento, 'pensamiento').'</td>';
        $html .= '</tr>';

        return $html;
    }

    private function muestra_perfiles_disponibles($arrayPerfil, $categoria){

        if($categoria == 'comunicacion'){
            $color = '#ff9e18';
        }elseif($categoria == 'social'){
            $color = '#ab0a3d';
        }elseif($categoria == 'autogestion'){
            $color = '#9e28b5';
        }elseif($categoria == 'investigacion'){
            $color = '#0a1f8f';
        }elseif($categoria == 'pensamiento'){
            $color = '#65b2e8';
        }

        $html = '';
        $html .= '<ul>';
        foreach( $arrayPerfil as $perfil ){            
            $html .= '<li>';
            $html .= '<a class="zoom" href="#" 
                        onclick="insert_perfil(\''.$perfil['categoria'].'\', \''.$categoria.'\')" 
                            style="color: '.$color.'">'.$perfil['categoria'].'</a>';
            $html .= '</li>';
        
        }
        $html .= '</ul>';

        return $html;
    }

    private function consulta_perfil_disponible($planUnidadId, $tipo){
        $con = Yii::$app->db;
        $query = "select op.categoria 
                    from planificacion_opciones op 
                    where op.tipo = 'PERFIL' 
                    and op.categoria not in (select contenido from pud_pai 
                    where seccion_numero = 4
                    and planificacion_bloque_unidad_id = $planUnidadId
                    and contenido = op.categoria 
                    and tipo = '$tipo') order by op.id asc";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionInsertPerfil(){
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');
        
        $planUnidadId = $_POST['plan_unidad_id'];
        $perfil = $_POST['perfil'];
        $tipo = $_POST['categoria'];

        $model = new PudPai();
        $model->planificacion_bloque_unidad_id = $planUnidadId;
        $model->seccion_numero = 4;
        $model->tipo = $tipo;
        $model->contenido = $perfil;
        $model->created_at = $fechaHoy;
        $model->created = $userLog;
        $model->updated_at = $fechaHoy;
        $model->updated = $userLog;
        $model->save();

    }

    public function actionShowPerfilesSeleccionados(){
        $planUnidadId = $_GET['plan_unidad_id'];
        $reflexiones = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'seccion_numero' => 4,
        ])
        ->orderBy('tipo')
        ->all();

        $html = '<tr>';
            $html .= '<td>'.$this->devuelve_perfiles_seleccionados($reflexiones, 'comunicacion').'</td>';
            $html .= '<td>'.$this->devuelve_perfiles_seleccionados($reflexiones, 'social').'</td>';
            $html .= '<td>'.$this->devuelve_perfiles_seleccionados($reflexiones, 'autogestion').'</td>';
            $html .= '<td>'.$this->devuelve_perfiles_seleccionados($reflexiones, 'investigacion').'</td>';
            $html .= '<td>'.$this->devuelve_perfiles_seleccionados($reflexiones, 'pensamiento').'</td>';
            
        $html .= '</tr>';

        return $html;

    }

    private function devuelve_perfiles_seleccionados($reflexiones, $tipo){

        if($tipo == 'comunicacion'){
            $color = '#ff9e18';
        }elseif($tipo == 'social'){
            $color = '#ab0a3d';
        }elseif($tipo == 'autogestion'){
            $color = '#9e28b5';
        }elseif($tipo == 'investigacion'){
            $color = '#0a1f8f';
        }elseif($tipo == 'pensamiento'){
            $color = '#65b2e8';
        }

        $html = '';
        $html .= '<ul>';
        foreach($reflexiones as $refle){
            if($refle->tipo == $tipo){
                $html .= '<li style="color: '.$color.'">';
                $html .=  $this->modal_perfiles_seleccionado($refle->id, $refle->contenido, $refle->respuesta);
                $html .= '<b><u> '.$refle->contenido.'</u></b><br>'.$refle->respuesta;
                $html .= '</li>';

                $html .= '<li style="color: '.$color.'"><hr></li>';
            }                        
        }
        
        $html .= '</ul>';

        return $html;
    }

    private function modal_perfiles_seleccionado($id, $pregunta, $respuesta){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModalR'.$id.'" onclick="show_reflexion_disponibles()"> 
                        <i class="fas fa-reply"> </i>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="reflexionModalR'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">RESPUESTA DE PREGUNTA:</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

                    $html .= '<div class="modal-body text-center">'; //Inicio de modal-body

                        $html .= '<b><h3>'.$pregunta.'</h3></b><br><br>';
                
                        $html .= '<div class="form-group">';
                        
                        $html .= '</div>';

                      
                    $html .= '</div>';// fin de modal-body

                    $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                      
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="eliminar_perfil('.$id.')">Eliminar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    ///////////fin de perfiles


    ///inicia servicios de accion
    public function actionShowServicioAccionSeleccionadas(){
        
        $planUnidadId = $_GET['plan_unidad_id'];
        $categorias = $this->get_categoria($planUnidadId);        
        $acciones = \backend\models\PudPaiServicioAccion::find()->where(['planificacion_bloque_unidad_id' => $planUnidadId])->all();
        
        $html = '';
        
        foreach ($categorias as $cat){
            $categ = $cat['categoria'];
            $html .= '<tr>';
            $html .= '<td>'.$cat['categoria'].'</td>';
            $html .= '<td>';
            foreach ($acciones as $acc){
                if($acc->opcion->categoria == $cat['categoria']){
                    $html.= '<lu>';
                    $html.= '<li>'.$acc->opcion->opcion.'</li>';
                    $html.= '</lu>';
                }
            }
                        
            $html .= '</td>';
            
            $presencial = $this->get_situacion_aprendizaje($planUnidadId, $categ, 'presencial');                                    
            $html .= '<td align="center">';
                if(!$presencial){
                    $html .= '<a href="#" onclick="inserta_situacion('.$planUnidadId.', \''.$categ.'\', \'presencial\')"><i class="fas fa-ban" style="color: #ab0a3d"></i></a>';
                }else{
                    $html .= '<a href="#" onclick="elimina_situacion('.$presencial.')"><i class="fas fa-check-circle" style="color: green"></i></a>';
                }
            $html .= '</td>';
            
            $enLinea = $this->get_situacion_aprendizaje($planUnidadId, $categ, 'en_linea');                                    
            $html .= '<td align="center">';
                if(!$enLinea){
                    $html .= '<a href="#" onclick="inserta_situacion('.$planUnidadId.', \''.$categ.'\', \'en_linea\')"><i class="fas fa-ban" style="color: #ab0a3d"></i></a>';
                }else{
                    $html .= '<a href="#" onclick="elimina_situacion('.$enLinea.')"><i class="fas fa-check-circle" style="color: green"></i></a>';
                }
            $html .= '</td>';
            
            $combinado = $this->get_situacion_aprendizaje($planUnidadId, $categ, 'combinado');                                    
            $html .= '<td align="center">';
                if(!$combinado){
                    $html .= '<a href="#" onclick="inserta_situacion('.$planUnidadId.', \''.$categ.'\', \'combinado\')"><i class="fas fa-ban" style="color: #ab0a3d"></i></a>';
                }else{
                    $html .= '<a href="#" onclick="elimina_situacion('.$combinado.')"><i class="fas fa-check-circle" style="color: green"></i></a>';
                }
            $html .= '</td>';
            
            $remoto = $this->get_situacion_aprendizaje($planUnidadId, $categ, 'remoto');                                    
            $html .= '<td align="center">';
                if(!$remoto){
                    $html .= '<a href="#" onclick="inserta_situacion('.$planUnidadId.', \''.$categ.'\', \'remoto\')"><i class="fas fa-ban" style="color: #ab0a3d"></i></a>';
                }else{
                    $html .= '<a href="#" onclick="elimina_situacion('.$remoto.')"><i class="fas fa-check-circle" style="color: green"></i></a>';
                }
            $html .= '</td>';
            
            
            $html .= '</tr>';
        }
        
        return $html;
        
    }
    
    private function get_situacion_aprendizaje($planUnidadId, $categoria, $opcion){
        $model = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => $categoria,
            'contenido' => $opcion
        ])->one();
        
        if($model){
            return $model->id;
        }else{
            return false;
        }
    }
    
    
    private function get_categoria($planUnidadId){
        $con = \Yii::$app->db;
        $query = "select 	op.categoria 
                    from 	pud_pai_servicio_accion p
                                    inner join planificacion_opciones op on op.id = p.opcion_id
                    where 	p.planificacion_bloque_unidad_id = $planUnidadId
                    group by op.categoria;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actionShowServicioAccionDisponibles(){
        $planUnidadId = $_GET['plan_unidad_id'];
        $html = '';

        $disponibles = $this->get_actividad($planUnidadId);
        
        $html .= '<ul>';
        foreach ($disponibles as $dispo){
            $html .= '<li>';
                $html .= '<a class="zoom" href="#" onclick="insert_accion('.$dispo['id'].')">'.$dispo['opcion'].'</a>';
            $html .= '</li>';
            $html .= '<li><hr>';
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    private function get_actividad($planUnidadId){
        $con = Yii::$app->db;
        $query = "select 	id, tipo, categoria, opcion, seccion, estado 
        from 	planificacion_opciones op 
        where 	op.tipo = 'SERVICIO_ACCION'
                and op.id not in (
                    select 	opcion_id 
                        from 	pud_pai_servicio_accion
                        where 	planificacion_bloque_unidad_id = $planUnidadId
                                and opcion_id = op.id 
                )
        order by op.categoria, op.id;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function actionInsertaAccion(){
        $opcionId       = $_POST['opcion_id'];
        $planUnidadId   = $_POST['plan_unidad_id'];
        
        $fechaHoy = date('Y-m-d H:i:s');
        $userLog = \Yii::$app->user->identity->usuario;
        
        $model = new \backend\models\PudPaiServicioAccion();
        $model->planificacion_bloque_unidad_id = $planUnidadId;
        $model->opcion_id = $opcionId;
        $model->created = $userLog;
        $model->created_at = $fechaHoy;
        $model->save();
    } 
    
    
    public function actionInsertaSituacion(){
        $planUnidadId = $_POST['plan_unidad_id'];
        $opcion = $_POST['opcion'];
        $categoria = $_POST['categoria'];
        $userLog = \Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');
        
        $model = new PudPai();
        $model->planificacion_bloque_unidad_id = $planUnidadId;
        $model->seccion_numero = 6;
        $model->tipo = $categoria;
        $model->contenido = $opcion;
        $model->created = $userLog;
        $model->created_at = $fechaHoy;
        $model->updated = $userLog;
        $model->updated_at = $fechaHoy;
        $model->save();
        
    }
    
    public function actionEliminaSituacion(){
        $id = $_POST['id'];
        $model = PudPai::findOne($id);
        $model->delete();
    }
    ///fin servicios de accion
}
?>