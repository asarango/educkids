<?php

namespace backend\controllers;

use backend\models\MessageHeader;
use backend\models\services\WebServicesUrls;
use backend\models\MessagePara;
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
class MensajesController extends Controller {

  /**
   * {@inheritdoc}
   */
  public function behaviors() {
      return [
          'access' => [
              'class' => AccessControl::className(),
              'rules' => [
                  [
                      'allow' => true,
                      'roles' => ['@'],
                  ]
              ],
          ],
          'verbs' => [
              'class' => VerbFilter::className(),
              'actions' => [
                  'delete' => ['POST'],
              ],
          ],
      ];
  }

  public function beforeAction($action) {
      if (!parent::beforeAction($action)) {
          return false;
      }

      if (Yii::$app->user->identity) {

          //OBTENGO LA OPERACION ACTUAL
          list($controlador, $action) = explode("/", Yii::$app->controller->route);
          $operacion_actual = $controlador . "-" . $action;
          //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
          if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
              echo $this->render('/site/error',[
                 'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                  'name' => 'Acceso denegado!!',
              ]);
          }
      } else {
          header("Location:" . \yii\helpers\Url::to(['site/login']));
          exit();
      }
      return true;
  }

  /**
   * Lists all PlanArea models.
   * @return mixed
   */
  public function actionIndex(){
        // inicio info obtenida desde el web service
        $userLog = \Yii::$app->user->identity->usuario;

        //consultando web service de academico
        $service = new WebServicesUrls('academico');
        $dataJson = $service->consumir_servicio($service->url.'/all/'.$userLog);
        $messages = json_decode($dataJson);
        // fin de proceso web service academico
      
        $mensajes =  $messages->data;
        //fin obtenida desde el web service


        return $this->render('index',[
            'mensajes' => $mensajes
        ]);
  }


  public function actionDetalle(){
      $id = $_GET['id'];

      $mensaje = MessagePara::findOne($id);

      if(!$mensaje->fecha_lectura){
        $mensaje->fecha_lectura = date('Y-m-d H:i:s');
        $mensaje->estado = 'leído';
        $mensaje->save();
      }


      return $this->render('detalle', [
          'mensaje' => $mensaje
      ]);

  }

  public function actionCambiarEstado(){
      $id       = $_GET['id'];
      $estado   = $_GET['estado'];

      $model = MessagePara::findOne($id);
      $model->estado = $estado;
      $model->save();

      return $this->redirect(['index']);

  }

  public function actionCreate(){
      $usuarioLog = Yii::$app->user->identity->usuario;
      $fechaHoy = date('Y-m-d H:i:s');
      $appOrigen = 'Educandi';
      $tablaOrigen = 'message_header';
      $periodoId = Yii::$app->user->identity->periodo_id;
      $institutoId = Yii::$app->user->identity->instituto_defecto;
      $cursos = $this->consulta_cursos($periodoId, $institutoId);

      $arrayPersonas = array();
      $arrayCursos = array();
      $arrayParalelos = array();

      $model = new MessageHeader();

      if( $model->load(Yii::$app->request->post())){
            $model->remite_usuario  = $usuarioLog;
            $model->created_at      = $fechaHoy;
            $model->updated_at      = $fechaHoy;
            $model->texto           = $_POST['texto'];
            $model->aplicacion_origen = $appOrigen;
            $model->tabla_origen    = $tablaOrigen;

            if($model->save()){
                
                if(isset($_POST['personas-seleccionadas'])){
                    $this->enviar_a_personas($_POST['personas-seleccionadas'], $model->id);
                }

                // if(isset($_POST(['grupo-seleccionadas'])) && isset($_POST['aquien-seleccionadas']) ){
                //     foreach(isset($_POST['aquien-seleccionadas']){

                //     }
                // }


            }

            
            //$model->save();
            //return $this->redirect(['index']);
      }

      return $this->render('create', [
          'model' => $model,
          'cursos' => $cursos,
          'arrayPersonas' => $arrayPersonas,
          'arrayCursos' => $arrayCursos,
          'arrayParalelos' => $arrayParalelos
      ]);

  }

  private function consulta_cursos($periodoId, $intituteId){
    $con = Yii::$app->db;
    $query = "select 	c.id 
                        ,c.name as course
                from 	op_course c
                        inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = c.period_id 
                        inner join op_course_template t on t.id = c.x_template_id 
                where 	sop.scholaris_id = $periodoId
                        and c.x_institute = $intituteId
                order by t.next_course_id desc;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

private function enviar_a_personas($arrayPersonas, $messageId){
    $fechaHoy = date('Y-m-d H:i:s');
    foreach($arrayPersonas as $persona){
        $model = new MessagePara();
        $model->message_id = $messageId;
        $model->para_usuario = $persona;
        $model->estado = 'recibido';
        $model->fecha_recepcion = $fechaHoy;
        $model->save();
    }
  }
  
  
  private function enviar_grupos($grupo, $arrayParalelos){
    if($grupo == 'DOCENTES'){

    }
  }

}
