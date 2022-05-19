<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisComportamientoInicial;
use backend\models\ScholarisComportamientoInicialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisComportamientoInicialController implements the CRUD actions for ScholarisComportamientoInicial model.
 */
class ScholarisComportamientoInicialController extends Controller
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
     * Lists all ScholarisComportamientoInicial models.
     * @return mixed
     */
    
    public function actionIndex() {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $usuario = \Yii::$app->user->identity->usuario;
        
        
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $modelCursos = $this->toma_clases_profesor($usuario, $modelPeriodo->codigo);
        

        return $this->render('listado', [
                    'modelPeriodo' => $modelPeriodo,
                    'institutoId' => $institutoId,
                    'modelCursos' => $modelCursos
        ]);
        
        
    }
    
    private function toma_clases_profesor($usuario, $periodoCodigo){
        $con = \Yii::$app->db;
        $query = "select 	cur.name as curso
		,p.id
		,p.name as paralelo
                ,f.id as faculty_id
                from	res_users u
                                inner join op_faculty f on f.partner_id = u.partner_id
                                inner join scholaris_clase c on c.idprofesor = f.id
                                inner join scholaris_malla_materia mm on mm.id = c.malla_materia
                                inner join op_course_paralelo p on p.id = c.paralelo_id
                                inner join op_course cur on cur.id = p.course_id
                where 	u.login = '$usuario'
                                and mm.tipo = 'COMPORTAMIENTO'
                                and c.periodo_scholaris = '$periodoCodigo' 
                order by cur.orden, p.name;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function actionIndex1()
    {
        
        $paralelo = $_GET['paralelo'];
        
        $this->ingresa_registro_notas($paralelo);
        
        
        $modelAlumnos = $this->toma_listado_calificado($paralelo);

        return $this->render('index', [
            'modelAlumnos' => $modelAlumnos
        ]);
    }
    
    
    private function toma_listado_calificado($paralelo){
        $con = \Yii::$app->db;
        $query = "select 	c.id
                                ,s.last_name
                                ,s.first_name
                                ,s.middle_name
                                ,c.q1
                                ,c.q2
                                ,i.id as inscription_id
                                ,s.id as alumno_id
                from	scholaris_comportamiento_inicial c
                                inner join op_student_inscription i on i.id = c.inscription_id
                                inner join op_student s on s.id = i.student_id
                where	i.parallel_id = $paralelo
                order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function ingresa_registro_notas($paralelo){
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
         
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        
                  
        $usuario = \Yii::$app->user->identity->usuario;
        //$modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
        $modelFaculty = $this->toma_clases_profesor($usuario, $modelPeriodo->codigo);
        $facultyId = $modelFaculty[0]['faculty_id'];
        $fecha = date("Y-m-d H:i:s");
        
        
        $con = \Yii::$app->db;
        $query = "insert into scholaris_comportamiento_inicial(inscription_id, faculty_id, creado_por, creado_fecha, actualizado_por, actualizado_fecha,q1,q2)
                    select 	id, $facultyId, '$usuario', '$fecha','$usuario', '$fecha','A','A'
                    from	op_student_inscription
                    where	parallel_id = $paralelo
                                    and inscription_state = 'M'
                                    and id not in (select inscription_id from scholaris_comportamiento_inicial);";
        
//        echo $query;
//        die();
        
        $con->createCommand($query)->execute();
    }

    /**
     * Displays a single ScholarisComportamientoInicial model.
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
     * Creates a new ScholarisComportamientoInicial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisComportamientoInicial();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisComportamientoInicial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'paralelo' => $model->inscription->parallel->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisComportamientoInicial model.
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
     * Finds the ScholarisComportamientoInicial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisComportamientoInicial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisComportamientoInicial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
