<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2Homologacion;
use backend\models\ScholarisMecV2HomologacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * ScholarisMecV2HomologacionController implements the CRUD actions for ScholarisMecV2Homologacion model.
 */
class ScholarisMecV2HomologacionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisMecV2Homologacion models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $curso = $_GET['curso'];
        $materia = $_GET['materia'];
        
        $modelMateria = \backend\models\ScholarisMecV2Distribucion::find()
                ->where(['materia_id' => $materia, 'curso_id' => $curso])
                ->one();
        
        $modelDisti = \backend\models\ScholarisMecV2Distribucion::find()->where(['materia_id' => $materia, 'curso_id' => $curso])->one();
        
        $searchModel = new ScholarisMecV2HomologacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelMateria->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelMateria' => $modelMateria,
            'modelDisti' => $modelDisti
        ]);
    }

    /**
     * Displays a single ScholarisMecV2Homologacion model.
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
     * Creates a new ScholarisMecV2Homologacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        if(isset($_GET['distribucionId'])){
            $distribucionId = $_GET['distribucionId'];
        }else{
            $distribucionId = $_POST['distribucionId'];
            $tipo = $_POST['tipo'];
            $recurso = $_POST['recurso'];
            
            if($tipo == 'AREA'){
                $modelRecurso = \backend\models\ScholarisArea::findOne($recurso);
            } else {
                $modelRecurso = \backend\models\ScholarisMateria::findOne($recurso);
            }
            
            $model = new ScholarisMecV2Homologacion();
            $model->distribucion_id = $distribucionId;
            $model->tipo = $tipo;
            $model->codigo_tipo = $recurso;
            $model->nombre_tipo = $modelRecurso->name;
            $model->save();
            
            $modelDisti = \backend\models\ScholarisMecV2Distribucion::findOne($distribucionId);
            
            return $this->redirect([
                'index1',
                'curso' => $modelDisti->curso_id,
                'materia' => $modelDisti->materia_id
            ]);
            
        }
        
                       
        $modelDisti = \backend\models\ScholarisMecV2Distribucion::findOne($distribucionId);
        
        $model = new ScholarisMecV2Homologacion();

        return $this->render('create', [
            'model' => $model,
            'modelDisti' => $modelDisti
        ]);
    }

    /**
     * Updates an existing ScholarisMecV2Homologacion model.
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

    
    
    
    public function actionRecurso(){
        $tipo = $_POST['tipo'];
        $materia = $_POST['materia'];
        $curso = $_POST['curso'];
        
        $modelCursos = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $curso])->one();
        
        
        if($tipo == 'AREA'){
            $modelRecurso = $this->get_area($modelCursos->malla_id);
        }else{
            $modelRecurso = $this->get_materia($modelCursos->malla_id);
        }

        
        $data = ArrayHelper::map($modelRecurso, 'id', 'name');
        
        echo Select2::widget([
            'name' => 'recurso',
            'value' => 0,
            'data' => $data,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione recurso',
                //'onchange' => 'mostrarBloque(this,"' . Url::to(['bloque']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        
    }
    
    
    private function get_area($mallaId){
        $con = Yii::$app->db;
        $query = "select a.id, a.name
from	scholaris_malla_area m
		inner join scholaris_area a on a.id = m.area_id
where	m.malla_id = $mallaId;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function get_materia($mallaId){
        $con = Yii::$app->db;
        $query = "select mat.id, mat.name
from 	scholaris_malla_area a
		inner join scholaris_malla_materia m on m.malla_area_id = a.id
		inner join scholaris_materia mat on mat.id = m.materia_id
where	a.malla_id = $mallaId;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}
