<?php
namespace backend\controllers;

use backend\models\NeeOpciones;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class NeeOpcionesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
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

    public function actionIndex(){
        $opciones = NeeOpciones::find()->orderBy('categoria', 'orden')->orderBy('categoria', 'orden')->all();

        return $this->render('index', [
            'opciones' => $opciones
        ]);
    }

    public function actionCreate(){
        $model = new NeeOpciones();
        if($model->load(Yii::$app->request->post())){
            $max = NeeOpciones::find()->where(['categoria' => $model->categoria])->max('orden');
            $model->orden = $max+1;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }


    public function actionUpdate($id){
        $model = NeeOpciones::findOne($id);

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id){
        $model = NeeOpciones::findOne($id);
        $model->delete();
        return $this->redirect(['index']);
    }
    
    
}


?>