<?php

namespace backend\controllers;

use Yii;
use backend\models\OpPsychologicalAttention;
use backend\models\OpPsychologicalAttentionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OpPsychologicalAttentionController implements the CRUD actions for OpPsychologicalAttention model.
 */
class OpPsychologicalAttentionController extends Controller
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
     * Lists all OpPsychologicalAttention models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $usuario = Yii::$app->user->identity->usuario;
        
        $modelUsuario = \backend\models\Usuario::findOne($usuario);
        if($modelUsuario->rol->rol == 'Dece' 
                || $modelUsuario->rol->rol == 'dece'
                || $modelUsuario->rol->rol == 'DECE'
          ){
            $consultarTodos = 1;
        }else{
            $consultarTodos = 0;
        }
        
        $searchModel = new OpPsychologicalAttentionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $usuario, $consultarTodos);
        
        $students = $this->get_students_for_user($usuario);
        $usersAttention = $this->get_users_on_attention();    
        $employees = $this->get_employes();

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'students'      => $students,
            'usersAttention' => $usersAttention,
            'employees'      => $employees
        ]);
    }
    
    private function get_employes(){
        $con = \Yii::$app->db;
        $query = "select 	a.employee_id 
                                        ,rr.name as empleado
                        from	op_psychological_attention a
                                        inner join hr_employee e on e.id = a.employee_id
                                        inner join resource_resource rr on rr.id = e.resource_id 
                        group by a.employee_id, rr.name
                        order by rr.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function get_users_on_attention(){
        $con = \Yii::$app->db;
        $query = "select 	u.id 
                                ,p.name as usuario
                from 	op_psychological_attention a
                                inner join res_users u on u.id = a.create_uid
                                inner join res_partner p on p.id = u.partner_id
                group by u.id, p.name;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    private function get_students_for_user($usuario){
        $con = Yii::$app->db;
        $query = "select 	s.id 
		,concat(s.last_name, ' ', s.first_name,' ',s.middle_name) as student_name
                ,date
                from 	op_psychological_attention a
                                inner join op_student s on s.id = a.student_id
                                inner join hr_employee emp on emp.id = a.employee_id 
                                inner join resource_resource rr on rr.id = emp.resource_id 
                                inner join res_users u on u.id = rr.user_id
                where 	u.login = '$usuario'
                order by s.last_name, s.first_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    

    /**
     * Displays a single OpPsychologicalAttention model.
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
     * Creates a new OpPsychologicalAttention model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OpPsychologicalAttention();

        if ($model->load(Yii::$app->request->post())) {
            
            $dataCourseParallel = $this->recupera_curso_paralelo($model->student_id);
            $model->course_id   = $dataCourseParallel['course_id'];
            $model->parallel_id = $dataCourseParallel['parallel_id'];
            
            $model->save();
            $id = $model->primaryKey();
            
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    
    private function recupera_curso_paralelo($alumnoId){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $con = Yii::$app->db;
        $query = "select 	i.course_id 
                                    ,i.parallel_id 
                    from	op_student_inscription i 
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                    where 	sop.scholaris_id  = $periodoId
                                    and i.student_id = $alumnoId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    

    /**
     * Updates an existing OpPsychologicalAttention model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new \backend\models\OpPsychologicalAttentionAsistentesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        if($model->state == 'open'){
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Deletes an existing OpPsychologicalAttention model.
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
     * Finds the OpPsychologicalAttention model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpPsychologicalAttention the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpPsychologicalAttention::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionPrintOne(){
        $attentionId = $_GET['id'];
        
        new \backend\models\InfOpAttentionPsy($attentionId);
        
    }
    
    public function actionValidateAttention(){
        $attentionId = $_GET['id'];        
        $model = OpPsychologicalAttention::findOne($attentionId);
        
        $model->state = 'open';
        $model->save();
        
        return $this->redirect(['view', 'id' => $attentionId]);
        
    }
}
