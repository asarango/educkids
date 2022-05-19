<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
//use backend\models\SentenciasSql;
use frontend\models\SentenciasSql;
use kartik\select2\Select2;
use yii\helpers\Url;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ReporteFaltasEstudianteController extends Controller {

    private $uso;
    private $periodoCodigo;

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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;

        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $periodo = $modelPeriodo->codigo;

        $sentenciasAlumnos = new \backend\models\SentenciasAlumnos();
        $modelAlumnos = $sentenciasAlumnos->get_estudiantes_x_instituto_periodo($periodoId, $institutoId);





        return $this->render('index', [
                    'modelAlumnos' => $modelAlumnos
        ]);
    }

    public function actionParciales() {
        $alumnoId = $_POST['id'];

        $modelBloques = $this->recupera_parciales($alumnoId);

        $data = \yii\helpers\ArrayHelper::map($modelBloques, 'id', 'name');

        echo '<label class="control-label">Parciales:</label>';
        echo Select2::widget([
            'name' => 'paralelo',
            'id' => 'paraleloId',
            'value' => 0,
            'data' => $data,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione parcial',
                'onchange' => 'mostrarDetalle(this,"' . Url::to(['detalle']) . '",' . $alumnoId . ');',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);

        echo '<a href="#" onclick="mostrarTodos(' . $alumnoId . ')">Todos</a>';
    }

    private function recupera_parciales($alumnoId) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $sentenciasAlumno = new \backend\models\SentenciasAlumnos();
        $modelAlumno = $sentenciasAlumno->get_estudiantes_x_instituto_periodo_x_id($periodoId, $institutoId, $alumnoId);
        $modelClase = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $modelAlumno['parallel_id']])->one();
        $uso = $modelClase->tipo_usu_bloque;
        $periodoCodigo = $modelClase->periodo_scholaris;

        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $periodoCodigo
                ])
                ->orderBy('orden')
                ->all();

        return $modelBloques;
    }

    public function actionDetalle() {
        $parcialId = $_POST['id'];
        $alumnoId = $_POST['alumno'];

        $html = $this->html_cabecera($alumnoId, $parcialId);
        $html .= $this->html_tabla($alumnoId, $parcialId);

        echo $html;
    }

    private function html_cabecera($alumnoId, $parcialId) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $sentenciasAlumno = new \backend\models\SentenciasAlumnos();
        $modelAlumno = $sentenciasAlumno->get_estudiantes_x_instituto_periodo_x_id($periodoId, $institutoId, $alumnoId);
        
        $html = '';

        if ($parcialId == 'todos') {
            
            $modelClase = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $modelAlumno['parallel_id']])->one();
            $this->uso = $modelClase->tipo_usu_bloque;
            $this->periodoCodigo = $modelClase->periodo_scholaris;
            
            $html .= '<h3><strong>' . $modelAlumno['student'] . '</strong>(Todos los parciales)</h3>';
            
            
        } else {
            $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($parcialId);
            $this->uso = $modelBloque->tipo_uso;
            $this->periodoCodigo = $modelBloque->scholaris_periodo_codigo;
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-8 col-md-8">';
            $html .= '<h3><strong>' . $modelAlumno['student'] . '</strong>(' . $modelBloque->name . ')</h3>';
            $html .= '</div>';
            
            $html .= '<div class="col-lg-4 col-md-4">';

            $html .= \yii\helpers\Html::a('<h3><span class="glyphicon glyphicon-cloud-download"></span></h3>',['download','alumno' => $alumnoId, 'parcial' => $parcialId]);
//            $html .= '<h3><a href="#" onclick="downloadparcial(' . $alumnoId . ','.$parcialId.')"><span class="glyphicon glyphicon-cloud-download"></span></a></h3>';
            $html .= '</div>';
            
            
            $html .= '</div>';
        }

        return $html;
    }

    private function html_tabla($alumnoId, $parcialId) {
        $html = '';

        $html .= '<div class="table table-responsive">';
        $html .= '<table class="table table-hover table-bordered">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>FECHA</strong></td>';
        $html .= '<td align="center"><strong>ATRASO</strong></td>';
        $html .= '<td align="center"><strong>ATRASO J</strong></td>';
        $html .= '<td align="center"><strong>FALTA</strong></td>';
        $html .= '<td align="center"><strong>FALTA J</strong></td>';
        $html .= '</tr>';


        if ($parcialId == 'todos') {
            
            $data = $this->consulta_datos_todos($alumnoId);
        } else {
            $data = $this->consulta_datos_parcial($alumnoId, $parcialId);
        }


        foreach ($data as $dat) {
            $html .= '<tr>';
            $html .= '<td align="center">' . $dat['fecha'] . '</td>';
            $html .= '<td align="center">' . $dat['atraso'] . '</td>';
            $html .= '<td align="center">' . $dat['atraso_justificado'] . '</td>';
            $html .= '<td align="center">' . $dat['falta'] . '</td>';
            $html .= '<td align="center">' . $dat['falta_justificada'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    private function consulta_datos_parcial($alumnoId, $parcialId) {

        $con = Yii::$app->db;
        $query = "select 	a.fecha, d.atraso, d.atraso_justificado, d.falta, d.falta_justificada 
                    from	scholaris_toma_asistecia a
                                    inner join scholaris_toma_asistecia_detalle d on d.toma_id = a.id 
                    where	bloque_id = $parcialId
                                    and d.alumno_id = $alumnoId
                                    and (atraso = true
                                    or atraso_justificado = true
                                    or falta = true
                                    or falta_justificada = true)
                    order by a.fecha asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_datos_todos($alumnoId) {
        
        $con = Yii::$app->db;
        $query = "select 	a.fecha, d.atraso, d.atraso_justificado, d.falta, d.falta_justificada 
from	scholaris_toma_asistecia a
		inner join scholaris_toma_asistecia_detalle d on d.toma_id = a.id
		inner join scholaris_bloque_actividad b on b.id = a.bloque_id 
where	d.alumno_id = $alumnoId
		and (atraso = true
		or atraso_justificado = true
		or falta = true
		or falta_justificada = true)
		and b.scholaris_periodo_codigo = '$this->periodoCodigo'
		and b.tipo_uso = '$this->uso'
order by a.fecha asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    
    
    public function actionDownload(){
        $alumno = $_GET['alumno'];
        $parcial = $_GET['parcial'];
        
        $modelReporteFaltas = new \backend\models\InfFaltasAlumno($alumno, $parcial);
        

    }
    
}
