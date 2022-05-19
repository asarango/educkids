<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisNotasPai;
use backend\models\ScholarisNotasPaiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisNotasPaiController implements the CRUD actions for ScholarisNotasPai model.
 */
class ScholarisNotasPaiController extends Controller {

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
     * Lists all ScholarisNotasPai models.
     * @return mixed
     */
    public function actionIndex1() {

        $claseId = $_GET['id'];

        $modelClase = \backend\models\ScholarisClase::findOne($claseId);


        if (!isset($_GET['quimestre'])) {
            $quimestre = 'QUIMESTRE I';
        } else {
            $quimestre = $_GET['quimestre'];
        }

        $modelQuimestre = \backend\models\ScholarisQuimestre::find()->where(['codigo' => $quimestre])->one();
        $this->ingresa_notas_pai_quimestre($claseId, $quimestre);
        $this->actualiza_notas_pai_parciales($claseId, $quimestre);

        $modelCalificaciones = ScholarisNotasPai::find()->where([
                    'clase_id' => $claseId,
                    'quimestre' => $quimestre
                ])
                ->orderBy('alumno')
                ->all();

        return $this->render('index', [
                    'modelQuimestre' => $modelQuimestre,
                    'modelClase' => $modelClase,
                    'modelCalificaciones' => $modelCalificaciones
        ]);
    }

    public function actionActualizanota() {

        $alumno = $_POST['alumno'];
        $quimestre = $_POST['quimestre'];
        $criterio = $_POST['criterio'];
        $nota = $_POST['nota'];
        $clase = $_POST['clase'];

        if ($quimestre == 1) {
            $quimestre = 'QUIMESTRE I';
        } else {
            $quimestre = 'QUIMESTRE II';
        }

        switch ($criterio) {
            case 'A':
                $campo = 'nota_a';
                break;

            case 'B':
                $campo = 'nota_b';
                break;

            case 'C':
                $campo = 'nota_c';
                break;

            case 'D':
                $campo = 'nota_d';
                break;
        }


        $model = ScholarisNotasPai::find()->where([
                    'clase_id' => $clase,
                    'alumno_id' => $alumno,
                    'quimestre' => $quimestre
                ])->one();

        $model->$campo = $nota;
        $model->save();

        $model = ScholarisNotasPai::find()->where([
                    'clase_id' => $clase,
                    'alumno_id' => $alumno,
                    'quimestre' => $quimestre
                ])->one();

        $model->suma_total = $model->nota_a + $model->nota_b + $model->nota_c + $model->nota_d;
        $notaH = $this->homologa_suma_pai($model->suma_total);
        $model->final_homologado = $notaH;
        $model->save();
    }

    private function homologa_suma_pai($nota) {
        $con = \Yii::$app->db;
        $query = "select 	calificacion_final
				from 	scholaris_notas_pai_homologacion_total 
				where 	$nota between equivale_minimo and equivale_maximo";
        $res = $con->createCommand($query)->queryOne();
        return $res['calificacion_final'];
    }

    private function ingresa_notas_pai_quimestre($claseId, $quimestre) {

        $periodo = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;
        $modelUser = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
        $hoy = date("Y-m-d");

        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);

        $con = Yii::$app->db;

        $query = "insert into scholaris_notas_pai(clase_id, alumno_id, alumno, quimestre, scholaris_periodo_codigo, creado, usuario_crea, actualizado, usuario_modifica)
            select 	g.clase_id
		,g.estudiante_id 
		,concat(s.last_name,' ',s.first_name,' ',s.middle_name) 
		,'$quimestre'
		,'$modelPeriodo->codigo'
		,'$hoy'
		,'$modelUser->id'
		,'$hoy'
		,'$modelUser->id'
from 	scholaris_grupo_alumno_clase g
		inner join op_student s on s.id = g.estudiante_id 
where	g.clase_id = $claseId
		and g.estudiante_id not in(
			select alumno_id from scholaris_notas_pai where alumno_id = g.estudiante_id and clase_id = g.clase_id and quimestre = '$quimestre'
		);";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }

    private function actualiza_notas_pai_parciales($clase, $quimestre) {
        $notasClase = $this->get_notas_clase($clase, $quimestre);
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        foreach ($notasClase as $actualiza) {
            $alumnoId = $actualiza['idalumno'];
            $cri = $actualiza['criterio'];
            $bloqueOrden = $actualiza['orden'];
//            $notaH = $no->homologaNota($actualiza['nota']);

            if ($actualiza['nota']) {
                $no = $actualiza['nota'];
            } else {
                $no = 0;
            }

            $notaH = $this->homologaNota($no);
//            $nota = $notaH[0]['nota_pai'];
            isset($notaH['nota_pai']) ? $nota = $notaH['nota_pai'] : $nota = 0;
            
            if($nota){
                $nota = $nota;
            } else {
                $nota = 0;
            }


            if ($cri == 'A' && $bloqueOrden == 1) {
                $sumativa = 'sumativa1_a';
            }
            if ($cri == 'A' && $bloqueOrden == 2) {
                $sumativa = 'sumativa2_a';
            }
            if ($cri == 'A' && $bloqueOrden == 3) {
                $sumativa = 'sumativa3_a';
            }

            if ($cri == 'B' && $bloqueOrden == 1) {
                $sumativa = 'sumativa1_b';
            }
            if ($cri == 'B' && $bloqueOrden == 2) {
                $sumativa = 'sumativa2_b';
            }
            if ($cri == 'B' && $bloqueOrden == 3) {
                $sumativa = 'sumativa3_b';
            }

            if ($cri == 'C' && $bloqueOrden == 1) {
                $sumativa = 'sumativa1_c';
            }
            if ($cri == 'C' && $bloqueOrden == 2) {
                $sumativa = 'sumativa2_c';
            }
            if ($cri == 'C' && $bloqueOrden == 3) {
                $sumativa = 'sumativa3_c';
            }


            if ($cri == 'D' && $bloqueOrden == 1) {
                $sumativa = 'sumativa1_d';
            }
            if ($cri == 'D' && $bloqueOrden == 2) {
                $sumativa = 'sumativa2_d';
            }
            if ($cri == 'D' && $bloqueOrden == 3) {
                 $sumativa = 'sumativa3_d';
            }


            if ($cri == 'A' && $bloqueOrden == 5) {
                $sumativa = 'sumativa1_a';
            }
            if ($cri == 'A' && $bloqueOrden == 6) {
                $sumativa = 'sumativa2_a';
            }
            if ($cri == 'A' && $bloqueOrden == 7) {
                $sumativa = 'sumativa3_a';
            }

            if ($cri == 'B' && $bloqueOrden == 5) {
                $sumativa = 'sumativa1_b';
            }
            if ($cri == 'B' && $bloqueOrden == 6) {
                $sumativa = 'sumativa2_b';
            }
            if ($cri == 'B' && $bloqueOrden == 7) {
                $sumativa = 'sumativa3_b';
            }

            if ($cri == 'C' && $bloqueOrden == 5) {
                $sumativa = 'sumativa1_c';
            }
            if ($cri == 'C' && $bloqueOrden == 6) {
                $sumativa = 'sumativa2_c';
            }
            if ($cri == 'C' && $bloqueOrden == 7) {
                $sumativa = 'sumativa3_c';
            }


            if ($cri == 'D' && $bloqueOrden == 5) {
                $sumativa = 'sumativa1_d';
            }
            if ($cri == 'D' && $bloqueOrden == 6) {
                $sumativa = 'sumativa2_d';
            }
            if ($cri == 'D' && $bloqueOrden == 7) {
                $sumativa = 'sumativa3_d';
            }



            $this->actualizaNotas($clase, $quimestre, $modelPeriodo->codigo, $sumativa, $nota, $alumnoId);
        }
    }

    private function get_notas_clase($clase, $quimestre) {
        $con = Yii::$app->db;
        $query = "select n.calificacion as nota, n.idalumno
				,pai.alumno
				,cri.criterio
				,bl.orden as orden
			from 	scholaris_calificaciones n
				inner join scholaris_tipo_actividad t on t.id = n.idtipoactividad
				inner join scholaris_actividad act on act.id = n.idactividad
				inner join scholaris_bloque_actividad bl on bl.id = act.bloque_actividad_id
				inner join scholaris_periodo per on per.codigo = bl.scholaris_periodo_codigo
				left join scholaris_criterio cri on cri.id = n.criterio_id
				left join scholaris_notas_pai pai on pai.alumno_id = n.idalumno
								and pai.clase_id = act.paralelo_id
			where 	t.nombre_pai = 'SUMATIVA'
				and bl.quimestre = '$quimestre'
				and per.estado = 't'
				and act.paralelo_id = $clase
ORDER BY pai.alumno, cri.criterio, bl.id  asc";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function homologaNota($nota) {
        $con = Yii::$app->db;
        $query = "select 	nota_pai
                                        from 	scholaris_notas_pai_homologacion
                                        WHERE	$nota between equivale_minimo and equivale_maximo";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function actualizaNotas($clase, $quimestre, $periodo, $campo, $valor, $alumnoId) {
        $con = Yii::$app->db;
        $query = "update 	scholaris_notas_pai 
set	$campo = $valor
where alumno_id = $alumnoId and clase_id = $clase 
and quimestre = '$quimestre' "
                . "and scholaris_periodo_codigo = '$periodo' ";
        $con->createCommand($query)->execute();
    }

}
