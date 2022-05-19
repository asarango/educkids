<?php

namespace padre\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        
        $modelBase = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'base'])
                ->one();
        
        $db = $modelBase->nombre;                
        $usuario = Yii::$app->user->identity->usuario;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelInstituto = \backend\models\OpInstitute::find()->where(['id' => $institutoId])->one();
        
        $modelAlumnos = new \backend\models\SentenciasPadre();
        $hijos = $modelAlumnos->get_mis_hijos($usuario, $periodoId);
        
        
        $modelFotos = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'fotos1'])
                ->one();
        
        $modelFotosPath2 = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'fotos2'])
                ->one();


        return $this->render('index',[
            'usuario' => $usuario,
            'modelInstituto' => $modelInstituto,
            'hijos' => $hijos,
            'db' => $db,
            'modelFotos' => $modelFotos,
            'modelFotosPath2' => $modelFotosPath2
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
//    public function actionLogin() {
//        
//        $this->layout = 'main_normal';
//
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            //return $this->goBack();
//            return $this->redirect(['index']);
//        } else {
//            $model->password = '';
//
//            return $this->render('login', [
//                        'model' => $model,
//            ]);
//        }
//    }
    
    
    
    public function actionLogin() {
        
        if(isset($_GET['token'])){
            $token = $_GET['token'];
        }elseif(isset($_GET['?token'])){
            $token = $_GET['?token'];
        }else{
//            $this->salio();
        }
        
               
        
        $this->layout = 'login';
        //$this->layout = 'main_normal';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        
        $usuario = $model->valida_usuario_token($token);
        
//        echo $usuario;
//        die();
        
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            return $this->redirect(['index']);
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
                        'usuario' => $usuario,
            ]);
        }
    }
    
    
    

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        
        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'cerrar'])
                ->one();
                
        Yii::$app->user->logout();

        //return $this->goHome();
        return $this->redirect($modelParametros->nombre);
    }
    
    

}
