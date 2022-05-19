<?php

namespace backend\controllers;

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
                        'actions' => ['logout', 'index', 'get-sub-menu', 'under'],
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


        $userLog = \Yii::$app->user->identity->usuario;
        $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
        $resUser = \backend\models\ResUsers::find()->where(['login' => $user->usuario])->one();

        $isTeacher = $this->validate_profile($user);

        if ($isTeacher > 0) {
//            $menu = \backend\models\Menu::find()->where(['codigo' => 'profesor'])->one();
//            $menuId = $menu->id;
//
//            return $this->redirect(['get-sub-menu',
//                    'menu_id' => $menuId
//            ]);
            
            return $this->redirect(['scholaris-asistencia-profesor/index']);

        }


        return $this->render('index', [
                    'user' => $user,
                    'resUser' => $resUser
        ]);
    }

    private function validate_profile($modelUser) {

        $profileId = $modelUser->rol_id;

        $con = \Yii::$app->db;
        $query = "select    count(id) as total
                    from    rol
                    where   id = $profileId
                            and rol ilike '%profesor%';";

        $res = $con->createCommand($query)->queryOne();

        return $res['total'];
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {

        $this->layout = 'loginism';
        //$this->layout= 'login2';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionGetSubMenu() {

        if (isset(\Yii::$app->user->identity->usuario)) {
            $userLog = \Yii::$app->user->identity->usuario;
            $user = \backend\models\Usuario::find()->where(['usuario' => $userLog])->one();
            $rolId = $user->rol_id;
        } else {
            $this->redirect(['site/login']);
        }

        $menuId = $_GET['menu_id'];
        $menu = \backend\models\Menu::findOne($menuId);
        $submenu = $this->get_submenu($rolId, $menuId);

        return $this->render('submenu', [
                    'submenu' => $submenu,
                    'menu' => $menu
        ]);
    }

    private function get_submenu($rolId, $menuId) {
        $con = \Yii::$app->db;
        $query = "select 	o.id
                                ,o.operacion
                                ,o.nombre
                                ,o.ruta_icono
                from 	operacion o
                                inner join rol_operacion ro on ro.operacion_id = o.id
                where 	o.menu_id = $menuId
                                and ro.rol_id = $rolId
                                and o.operacion ilike '%-index'
                order by o.nombre;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionUnder() {
        return $this->render('under');
    }

}
