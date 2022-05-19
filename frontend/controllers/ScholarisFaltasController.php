<?php

namespace frontend\controllers;

use Yii;
use backend\models\ScholarisFaltasYAtrasosParcial;
use backend\models\ScholarisFaltasYAtrasosParcialSearch;
use backend\models\ScholarisOpPeriodPeriodoScholaris;
use backend\models\OpCourse;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisFaltasYAtrasosParcialController implements the CRUD actions for ScholarisFaltasYAtrasosParcial model.
 */
class ScholarisFaltasController extends Controller {

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

    /**
     * Lists all ScholarisFaltasYAtrasosParcial models.
     * @return mixed
     */
    public function actionIndex() {
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $periodo = ScholarisOpPeriodPeriodoScholaris::find()
                ->innerJoin("scholaris_periodo", "scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
                ->innerJoin("op_period", "op_period.id = scholaris_op_period_periodo_scholaris.op_id")
                ->where(["scholaris_periodo.id" => $periodoId, "op_period.institute" => $institutoId])
                ->one();

        $modelCursos = OpCourse::find()
                ->innerJoin("op_section", "op_section.id = op_course.section")
                ->where(['op_section.period_id' => $periodo, "x_institute" => $institutoId])
                ->all();


        return $this->render('index', [
                    'modelCursos' => $modelCursos
        ]);
    }

    /**
     * Displays a single ScholarisFaltasYAtrasosParcial model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisFaltasYAtrasosParcial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisFaltasYAtrasosParcial();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisFaltasYAtrasosParcial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisFaltasYAtrasosParcial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisFaltasYAtrasosParcial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisFaltasYAtrasosParcial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisFaltasYAtrasosParcial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetalle() {

        $bloque = $_POST['id'];
        $paralelo = $_POST['paralelo'];

        $html = '<div class="table table-responsive">';
        $html .= '<table class="table table-hover table-bordered">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th rowspan="2">#</th>';
        $html .= '<th rowspan="2">ESTUDIANTE</th>';
        $html .= '<th colspan="3" bgcolor="#EBFCB9">ATRASOS</th>';
        $html .= '<th colspan="3" bgcolor="#FCF5B9">FALTAS JUSTIFICADAS</th>';
        $html .= '<th colspan="3" bgcolor="#F9DEDB">FALTAS INJUSTIFICADAS</th>';
        $html .= '<th colspan="1" bgcolor="">ATRASOS</th>';
        $html .= '<th colspan="1" bgcolor="">FALTAS JUSTIFICADAS</th>';
        $html .= '<th colspan="1" bgcolor="">FALTAS INJUSTIFICADAS</th>';
        $html .= '<th colspan="1" bgcolor="">OBSERVACIONES</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td bgcolor="#EBFCB9">Noved</td>';
        $html .= '<td bgcolor="#EBFCB9">Justi</td>';
        $html .= '<td bgcolor="#EBFCB9">Total</td>';
        
        $html .= '<td bgcolor="#FCF5B9">Noved</td>';
        $html .= '<td bgcolor="#FCF5B9">Justi</td>';
        $html .= '<td bgcolor="#FCF5B9">Total</td>';
        
        $html .= '<td bgcolor="#F9DEDB">Noved</td>';
        $html .= '<td bgcolor="#F9DEDB">Justi</td>';
        $html .= '<td bgcolor="#F9DEDB">Total</td>';
        
        $html .= '</tr>';        
        $html .= '</thead>';
        $html .= '<tbody>';

        $html .= $this->detalleAtrasos($bloque, $paralelo);

        $html .= '</tbody>';
        $html .= '</div>';

        return $html;
    }

    private function detalleAtrasos($bloque, $paralelo) {
        $modelAlumnos = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where([
                    'op_student_inscription.parallel_id' => $paralelo,
                    'op_student_inscription.inscription_state' => 'M'
                ])
                ->orderBy("op_student.last_name", "op_student.first_name")
                ->all();
        $html = "";
        $i=0;
        foreach ($modelAlumnos as $alumno) {
            $i++;
            $html .= "<tr>";
            $html .= '<td>'.$i.'</td>';
            $html .= '<td>'.$alumno->last_name.' '.$alumno->first_name.' '.$alumno->middle_name.'</td>';
            
            $novedad = $this->detalleNovedades($bloque, $alumno->id);
            $html .= '<td bgcolor="#EBFCB9">'.$novedad[0].'</td>';
            $html .= '<td bgcolor="#EBFCB9">'.$novedad[1].'</td>';
            $html .= '<td bgcolor="#EBFCB9">'.$novedad[2].'</td>';
            
            $html .= '<td bgcolor="#FCF5B9">'.$novedad[3].'</td>';
            $html .= '<td bgcolor="#FCF5B9">'.$novedad[4].'</td>';
            $html .= '<td bgcolor="#FCF5B9">'.$novedad[5].'</td>';
            
            $html .= '<td bgcolor="#F9DEDB">'.$novedad[6].'</td>';
            $html .= '<td bgcolor="#F9DEDB">'.$novedad[7].'</td>';
            $html .= '<td bgcolor="#F9DEDB">'.$novedad[8].'</td>';
            
            $html .= '<td bgcolor="">';
            $html .= '<input type="text" name="atr" id="at'.$alumno->id.'" onchange="cambiaNovedad(this,'.$bloque.','.$alumno->id.',1)" value="'.$novedad[9].'" class="form-control">';
            $html .= '</td>';
            
            $html .= '<td bgcolor="">';
            $html .= '<input type="text" name="atr" id="fj'.$alumno->id.'" onchange="cambiaNovedad(this,'.$bloque.','.$alumno->id.',2)" value="'.$novedad[10].'" class="form-control">';
            $html .= '</td>';
            
            $html .= '<td bgcolor="">';
            $html .= '<input type="text" name="atr" id="fi'.$alumno->id.'" onchange="cambiaNovedad(this,'.$bloque.','.$alumno->id.',3)" value="'.$novedad[11].'" class="form-control">';
            $html .= '</td>';
            
            $html .= '<td bgcolor="">';
            $html .= '<input type="text" name="atr" id="ob'.$alumno->id.'" onchange="cambiaNovedad(this,'.$bloque.','.$alumno->id.',4)" value="'.$novedad[12].'" class="form-control">';
            $html .= '</td>';
//            $html .= '<td bgcolor="">'.$novedad[9].'</td>';
//            $html .= '<td bgcolor="">'.$novedad[10].'</td>';
//            $html .= '<td bgcolor="">'.$novedad[11].'</td>';
//            $html .= '<td bgcolor="">'.$novedad[12].'</td>';
            
            
            $html .= "</tr>";
        }

        return $html;
    }
    
    private function detalleNovedades($bloque, $alumno){        
        $sentencias = new \backend\models\SentenciasFaltas();        
        $novedades = $sentencias->get_novedad($alumno, $bloque);
        
        return $novedades;        
    }
    
    
    public function actionAsigna(){
        
        $alumno = $_POST['alumno'];
        $bloque = $_POST['bloque'];
        $valor = $_POST['valor'];
        $tipo = $_POST['tipo'];
        
        
        $sentencias = new \backend\models\SentenciasFaltas();
        
        $sentencias->modifica_novedad_real($alumno, $bloque, $valor, $tipo);
        
    }
    
    

}
