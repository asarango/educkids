<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisCalificaComportamiento;
use backend\models\ScholarisCalificaComportamientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScholarisCalificaComportamientoController implements the CRUD actions for ScholarisCalificaComportamiento model.
 */
class ScholarisCalificaComportamientoController extends Controller
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
     * Lists all ScholarisCalificaComportamiento models.
     * @return mixed
     */
    public function actionIndex1()
    {
                
        if (Yii::$app->user->isGuest) {
            $this->redirect(['/site/login']);
        }
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        
        $claseId = $_GET['id'];
        
        $modelClase = \backend\models\ScholarisClase::findOne($claseId);

        $sentenciasCl = new \backend\models\SentenciasClase();
        $modelAlumnos = $sentenciasCl->get_alumnos_clase($claseId, $periodoId);
        
        
        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
            'tipo_bloque' => 'PARCIAL',
            'scholaris_periodo_codigo' => $modelPeriodo->codigo
        ])->orderBy('orden')
                ->all();
        
        $this->ingresa_notas($modelAlumnos, $modelBloques);
        
        $arregloAl = $this->genera_reporte($modelAlumnos, $modelBloques);
        
        return $this->render('index', [            
            'modelClase' => $modelClase,
            'arregloAl' => $arregloAl,
            'modelBloques' => $modelBloques,
            'modelAlumnos' => $modelAlumnos
        ]);
    }
    
    private function genera_reporte($modelAlumnos, $modelBloques){
        
        $arregloNotas = array();
        
        foreach ($modelAlumnos as $alumno){
            
            
            
            $notas = array();
            
            foreach ($modelBloques as $bloque){
                
                $modelC = ScholarisCalificaComportamiento::find()->where([
                    'inscription_id' => $alumno['inscription_id'],
                    'bloque_id' => $bloque->id
                ])->one();
                
                array_push($notas,array(
                    'bloque_id' => $modelC->bloque_id,
                    'calificacion' => $modelC->calificacion,
                    'calificacionId' => $modelC->id
                ));
                
            }
            
            
            array_push($arregloNotas,array(
                'inscription_id' => $alumno['inscription_id'],
                'last_name' => $alumno['last_name'],
                'first_name' => $alumno['first_name'],
                'middle_name' => $alumno['middle_name'],
                'notas' => $notas
            ));
            
            
        }
        
        return $arregloNotas;
        
    }
    
    
    private function ingresa_notas($modelAlumnos, $modelBloques){
        
        $modelParametroComportamiento = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'califmmaxima'
        ])->one();
        
        $valorComportamiento = $modelParametroComportamiento->valor;
        
        $usuario = \Yii::$app->user->identity->usuario;
        $fecha = date("Y-m-d H:i:s");
        foreach ($modelAlumnos as $alumno){
            foreach ($modelBloques as $bloque){
                
                $modelC = ScholarisCalificaComportamiento::find()->where([
                    'inscription_id' => $alumno['inscription_id'],
                    'bloque_id' => $bloque->id
                ])->one();
                
                if(!$modelC){
                    $model = new ScholarisCalificaComportamiento();
                    $model->inscription_id = $alumno['inscription_id'];
                    $model->bloque_id = $bloque->id;
                    $model->calificacion = $valorComportamiento;
                    $model->creado_por = $usuario;
                    $model->creado_fecha = $fecha;
                    $model->actualizado_por = $fecha;
                    $model->actualizado_fecha = $fecha;
                    $model->save();
                }
                
            }
        }
    }
    
    public function actionCambiarNota(){
        $id = $_POST['id'];
        $nota = $_POST['nota'];
        
        $model = ScholarisCalificaComportamiento::findOne($id);
        $model->calificacion = $nota;
        $model->save();
        
    }
    
    

    /**
     * Displays a single ScholarisCalificaComportamiento model.
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
     * Creates a new ScholarisCalificaComportamiento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisCalificaComportamiento();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisCalificaComportamiento model.
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
     * Deletes an existing ScholarisCalificaComportamiento model.
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
     * Finds the ScholarisCalificaComportamiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisCalificaComportamiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisCalificaComportamiento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
