<?php

namespace backend\controllers;

use Yii;
use backend\models\Usuario;
use backend\models\UsuarioSearch;
use backend\models\Rol;
use backend\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
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

    /**
     * Lists all Usuario models.
     * @return mixed
     */
    public function actionIndex()
    {

        $users = $this->get_users();

        return $this->render('index', [
            'users' => $users
        ]);
    }


    private function get_users()
    {
        $con    = Yii::$app->db;
        $query  = "select 	u.avatar 
                            ,u.usuario
                            ,p.name as nombre
                            ,u.email 
                            ,u.activo 
                            ,u.firma		
                            ,r.rol 
                    from 	usuario u
                            inner join rol r on r.id = u.rol_id
                            inner join res_users ru on ru.login = u.usuario 
                            inner join res_partner p on p.id = ru.partner_id 
                    order by r.rol, p.name ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Displays a single Usuario model.
     * @param string $id
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
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuario();

        if ($model->load(Yii::$app->request->post())) {
            $model->clave = md5($model->clave);
            $model->save();
            return $this->redirect(['view', 'id' => $model->usuario]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->oldAttributes['clave'] != $model->clave) {
                $model->clave = md5($model->clave);
            }

            $model->save();

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }


    public function actionUploadFirma()
    {

        $ruta = '/var/www/html/educandi/backend/web/ISM/firmas/';
         
        if(isset($_FILES['Usuario'])){
            $errors= array();
            $file_name = $_FILES['Usuario']['name']['firma'];
            $file_size = $_FILES['Usuario']['size']['firma'];
            $file_tmp = $_FILES['Usuario']['tmp_name']['firma'];
            $file_type = $_FILES['Usuario']['type']['firma'];
            //$file_ext=strtolower(end(explode('.',$_FILES['Usuario']['name']['avatar'])));
            $file_ext = explode('.', $_FILES['Usuario']['name']['firma']);

            $extension = end($file_ext);
            
            $expensions= array("jpeg","jpg","png", "JPG");
           
            if(in_array($extension,$expensions)=== false){
               $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
           
            if($file_size > 2097152) {
               $errors[]='File size must be excately 2 MB';
            }
           
            if(empty($errors)==true) {
               
               $userPost = $_POST['Usuario']['usuario'].'.'.$extension;
               $model = Usuario::find()->where(['usuario' => $_POST['Usuario']['usuario']])->one();
               $newName = $ruta.$userPost;
               
                // echo $userPost.'<br>';
                // echo $newName.'<br>';
                // die();

               move_uploaded_file($file_tmp, $newName); 
               $model->firma = $userPost;
               $model->save();

               return $this->redirect(['update', 'id' => $_POST['Usuario']['usuario']]);
               
            }else{
               print_r($errors);
            }
         }
        


    }

    /**
     * Deletes an existing Usuario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionProfesores()
    {

        if (isset($_POST['perfil'])) {

            $perfil = $_POST['perfil'];

            $institutoId = Yii::$app->user->identity->instituto_defecto;
            $periodoId = Yii::$app->user->identity->periodo_id;
            $clave = md5('12345');


            $con = Yii::$app->db;
            $query = "insert into usuario(usuario, clave, email, rol_id, activo, instituto_defecto, periodo_id)
                        select 	u.login
                                        ,'$clave'
                                        ,p.email
                                        ,$perfil
                                        ,true
                                        ,$institutoId
                                        ,$periodoId
                        from	op_faculty f
                                        inner join res_users u on u.partner_id = f.partner_id
                                        inner join res_partner p on p.id = f.partner_id
                        where	u.active = true
                                        and u.login not in (select usuario from usuario) group by u.login, p.email;";
            $con->createCommand($query)->execute();

            return $this->redirect(['index']);
        } else {

            $modelRol = Rol::find()->all();

            return $this->render('profesores', [
                'modelRol' => $modelRol
            ]);
        }
    }


    public function actionPadres()
    {



        if (isset($_POST['perfil'])) {

            $perfil = $_POST['perfil'];

            $institutoId = Yii::$app->user->identity->instituto_defecto;
            $periodoId = Yii::$app->user->identity->periodo_id;
            $clave = md5('12345');


            $con = Yii::$app->db;
            $query = "insert into usuario(usuario, clave, email, rol_id, activo, instituto_defecto, periodo_id)
                       select 	u.login, '827ccb0eea8a706c4c34a16891f84e7b'
                                        ,pa.email
                                        ,$perfil
                                        ,true
                                        ,(
                                                select 	std.x_institute 
                                                        from 	op_parent opp 
                                                                        inner join op_parent_op_student_rel rel on rel.op_parent_id = opp.id 
                                                                        inner join op_student std on std.id = rel.op_student_id
                                                                        inner join op_parent pare on pare.id = rel.op_parent_id 
                                                                        inner join res_users use on use.partner_id = pare.name
                                                        where	use.login = u.login 
                                                        limit 1
                                        )
                                        ,$periodoId
                        from 	op_student_inscription i
                                        inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
                                        inner join op_parent_op_student_rel r on r.op_student_id = i.student_id
                                        inner join op_parent p on p.id = r.op_parent_id 
                                        inner join res_partner pa on pa.id = p.name
                                        inner join res_users u on u.partner_id = pa.id
                                        inner join op_course_paralelo par on par.id = i.parallel_id 
                                        inner join op_course c on c.id = par.course_id 
                        where 	sop.scholaris_id = $periodoId
                                        and i.inscription_state = 'M'
                                        and u.login not in (select usuario from usuario where usuario = u.login)
                        group by u.login, pa.email
                        order by u.login;";
            $con->createCommand($query)->execute();

            return $this->redirect(['index']);
        } else {

            $modelRol = Rol::find()->all();

            return $this->render('padres', [
                'modelRol' => $modelRol
            ]);
        }
    }
}
