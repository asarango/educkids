<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMateria;
use backend\models\ScholarisMateriaConceptosRelacionadosPai;
use backend\models\ScholarisMateriaSearch;
use backend\models\ScholarisPeriodo;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMateriaConceptosRelacionadosPaiController implements the CRUD actions for ScholarisMateria model.
 */
class ScholarisMateriaConceptosRelacionadosPaiController extends Controller
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
    
    public function actionIndex1(){
        $materiaId = $_GET['materia_id'];
        $materia = ScholarisMateria::findOne($materiaId);

        $model = new ScholarisMateriaConceptosRelacionadosPai();

        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['index1', 'materia_id' => $materiaId]);
        }

        $conceptos = ScholarisMateriaConceptosRelacionadosPai::find()->where([
            'materia_id' => $materiaId
        ])
        ->all();


        return $this->render('index1',[
            'materia' => $materia,
            'conceptos' => $conceptos,
            'model' => $model
        ]);        
        
    }   

    public function actionEliminar(){
        $id = $_GET['id'];
        $model = ScholarisMateriaConceptosRelacionadosPai::findOne($id);
        $materiaId = $model->materia_id;
        $model->delete();

        return $this->redirect(['index1',
            'materia_id' => $materiaId
        ]);
    }

    public function actionUpdate(){
        
        $id = $_POST['id'];
        $español    = $_POST['contenido_es'];
        $ingles     = $_POST['contenido_en'];
        $frances    = $_POST['contenido_fr'];
            
        $model = ScholarisMateriaConceptosRelacionadosPai::findOne($id);
        $model->contenido_es = $español;
        $model->contenido_en = $ingles;
        $model->contenido_fr = $frances;
        $model->save();

        return $this->redirect(['index1', 'materia_id' => $model->materia_id]);

    }
    
}
