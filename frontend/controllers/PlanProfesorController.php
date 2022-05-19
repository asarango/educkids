<?php

namespace frontend\controllers;

use backend\models\OpFaculty;
use backend\models\ResUsers;
use backend\models\ScholarisClase;
use backend\models\ScholarisPeriodo;
use backend\models\ScholarisBloqueActividad;


use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class PlanProfesorController extends Controller
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
                    ],
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex1()
    {

        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()
            ->where(['id' => $periodoId])
            ->one();
        $modelUsuario = ResUsers::find()->where(['login' => $usuario])->one();
        $modelProfesor = OpFaculty::find()->where(['partner_id' => $modelUsuario->partner_id])->one();

        $modelClases = ScholarisClase::find()
            ->where([
                'idprofesor' => $modelProfesor->id,
                'periodo_scholaris' => $modelPeriodo->codigo,
            ])
            ->all();

        $modelUso = ScholarisClase::find()
            ->where([
                'idprofesor' => $modelProfesor->id,
                'periodo_scholaris' => $modelPeriodo->codigo,
            ])
            ->one();

        $modelBloque = ScholarisBloqueActividad::find()
                      ->where([
                                'tipo_uso' => $modelUso->tipo_usu_bloque,
                                'scholaris_periodo_codigo' => $modelPeriodo->codigo
                            ])
                      ->orderBy('orden')
                      ->all();

        // \print_r($modelBloque);
        // die();
        

        return $this->render('index', [
            'modelProfesor' => $modelProfesor,
            'modelClases' => $modelClases,
            'modelBloque' => $modelBloque
            
        ]);
    }

}
