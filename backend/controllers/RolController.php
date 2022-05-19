<?php

namespace backend\controllers;

use Yii;
use backend\models\Rol;
use backend\models\RolSearch;
use backend\models\Operacion;
use backend\models\RolOperacion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * RolController implements the CRUD actions for Rol model.
 */
class RolController extends Controller
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
     * Lists all Rol models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new RolSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $perfiles = Rol::find()->orderBy('rol')->all();

        return $this->render('index', [
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
            'perfiles' => $perfiles
        ]);
    }

    /**
     * Displays a single Rol model.
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
     * Creates a new Rol model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rol();

        if ($model->load(Yii::$app->request->post())) {
            //guarda
            $operaciones = $_POST['Rol']['rolOperaciones'];
            $model->save();
            $rol_id = $model->id;
            
            foreach ($operaciones as $operacion_id){
                $modelRO = new RolOperacion();
                $modelRO->rol_id = $rol_id;
                $modelRO->operacion_id = $operacion_id;
                $modelRO->save();
            }                        
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            //inicia formularios
            $listaOperaciones = Operacion::find()->all();
            $listaOperaciones = ArrayHelper::map($listaOperaciones, 'id', 'operacion');
            return $this->render('create', [
                        'model' => $model,
                        'listaOperaciones' => $listaOperaciones,
            ]);
        }
    }

    /**
     * Updates an existing Rol model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $this->borrarPermisos($model->id);
            $model->save();
            
            if(!empty($_POST['Rol']['rolOperaciones'])){
                $operaciones = $_POST['Rol']['rolOperaciones'];
                $rol_id = $model->id;
                foreach ($operaciones as $operacion_id) {
                    $modelRO = new RolOperacion();
                    $modelRO->rol_id = $rol_id;
                    $modelRO->operacion_id = $operacion_id;
                    $modelRO->save();
                }
            }            
            // return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        }else {
            $listaOperaciones = Operacion::find()->orderBy("nombre")->all();
            $listaOperaciones = ArrayHelper::map($listaOperaciones, 'id', 'nombre');
            $model->rolOperaciones = ArrayHelper::map($model->operacionPermitidas, 'id', 'id');
            return $this->render('update', [
                        'model' => $model,
                        'listaOperaciones' => $listaOperaciones,
            ]);
        }
    }
    
    public function borrarPermisos($rol_id) {
        $listaRO = RolOperacion::find()->where("rol_id = $rol_id")->all();
        foreach ($listaRO as $RO) {
            $RO->delete();
        }
    }

    /**
     * Deletes an existing Rol model.
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
     * Finds the Rol model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rol the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rol::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    public function actionPermisos($id){
        $modelMenu = \backend\models\Menu::find()->orderBy("orden")->all();
        $modelRol = Rol::find()->where(['id' => $id])->one();
        
        return $this->render('permisos',[
            "modelMenu" => $modelMenu,
            'modelRol' => $modelRol
        ]);
        
        
    }
    
    public function actionAsignacion($id, $accion, $rolId){

        
        
        if($accion == 'a'){
            $model = new RolOperacion();
            $model->rol_id = $rolId;
            $model->operacion_id = $id;
            $model->save();
        }else{
            $model = RolOperacion::find()
                    ->where(['rol_id' => $rolId, 'operacion_id' => $id])
                    ->one();
            $model->delete();
            
        }
                
        return $this->redirect(['permisos',
            "id" => $rolId            
            ]);
        
    }
    
}
