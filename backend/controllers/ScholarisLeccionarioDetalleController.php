<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisLeccionarioDetalle;
use backend\models\ScholarisLeccionarioDetalleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * ScholarisLeccionarioDetalleController implements the CRUD actions for ScholarisLeccionarioDetalle model.
 */
class ScholarisLeccionarioDetalleController extends Controller
{
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
     * Lists all ScholarisLeccionarioDetalle models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $paralelo = $_GET['paralelo_id'];
        $fecha = $_GET['fecha'];
        
        $searchModel = new ScholarisLeccionarioDetalleSearch();
        $searchModel->registrar_clases($paralelo, $fecha);
        $modelNoveades = $searchModel->toma_novedades($paralelo, $fecha);
        
        
        
        $modelDetalle = ScholarisLeccionarioDetalle::find()
                ->where(['paralelo_id' => $paralelo,'fecha' => $fecha])
                ->orderBy("desde")
                ->all();
        $modelLeccionario = \backend\models\ScholarisLeccionario::find()
                ->where(['paralelo_id' => $paralelo, 'fecha' => $fecha])
                ->one();
        $modelLeccionario->total_clases = count($modelDetalle);
        $modelLeccionario->save();

        return $this->render('index', [
            'modelDetalle' => $modelDetalle,
            'modelLeccionario' => $modelLeccionario,
            'modelNoveades' => $modelNoveades
        ]);
    }
    
    
    /**
     * 
     */
    public function actionEditar(){
        if(isset($_GET['asistencia'])){
            $asistenciaId = $_GET['asistencia']; 
        }
       
       $detalleId = $_GET['detalle'];
       
       $modelDetalle = ScholarisLeccionarioDetalle::find()
               ->where(['id' => $detalleId])
               ->one();
       
       return $this->render('editar',[
           'modelDetalle' => $modelDetalle
       ]);
    }

    /**
     * Displays a single ScholarisLeccionarioDetalle model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisLeccionarioDetalle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisLeccionarioDetalle();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisLeccionarioDetalle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisLeccionarioDetalle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisLeccionarioDetalle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisLeccionarioDetalle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisLeccionarioDetalle::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    public function actionJustificaprofesor(){
        $codigo = $_POST['codigo'];
        $motivo = $_POST['motivo'];
        $detalle = $_POST['detalle'];
        $tiempo = $_POST['tiempo'];
        
        print_r($_POST);
        
        $fecha = date("Y-m-d");
        $usuario = Yii::$app->user->identity->usuario;
        $modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();        
        
        $modelDetalle = ScholarisLeccionarioDetalle::find()
                ->where(['id' => $detalle])
                ->one();
        
        echo $modelDetalle->asistencia_id;
        
        if($modelDetalle->asistencia_id){
            $asistencia = $modelDetalle->asistencia_id;
        }else{
            $asistencia = 0;
        }
        
        $modelJustifica = new \backend\models\ScholarisAsistenciaJustificacionProfesor();
        
        $modelJustifica->asistencia_id = $asistencia;
        $modelJustifica->fecha = $fecha;
        $modelJustifica->usuario_crea = $modelUsuario->id;
        $modelJustifica->codigo_persona = $modelDetalle->clase->idprofesor;
        $modelJustifica->tipo_persona = 1;
        $modelJustifica->motivo_justificacion = $motivo;
        $modelJustifica->opcion_justificacion_id = $codigo;
        $modelJustifica->estado = 0;
        $modelJustifica->fecha_registro = $modelDetalle->fecha;
        $modelJustifica->hora_registro = $modelDetalle->hora_id;
        $modelJustifica->tiempo_justificado = $tiempo;
        $modelJustifica->save();
        
        
        $modelDetalle->motivio_justificacion_falta = $motivo;
        if($codigo == 0){
            $modelDetalle->justifica_falta = true;
        }else{
            $modelDetalle->justifica_falta = null;
            $modelDetalle->justifica_atraso = true;
        }
        
        $modelDetalle->save();
        
        return $this->redirect(['editar',
                'detalle' => $modelDetalle->id
            ]);
    }
}
