<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2Distribucion;
use backend\models\ScholarisMecV2DistribucionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * ScholarisMecV2DistribucionController implements the CRUD actions for ScholarisMecV2Distribucion model.
 */
class ScholarisMecV2DistribucionController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
     * Lists all ScholarisMecV2Distribucion models.
     * @return mixed
     */
    public function actionIndex1() {

        $malla = $_GET['malla'];
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($malla);


        return $this->render('index', [
                    'modelMalla' => $modelMalla
        ]);
    }

    public function actionDetalle() {
        $curso = $_POST['curso'];
        $malla = $_POST['malla'];


        $this->inserta_materias($malla, $curso);
        $html = $this->get_html($curso, $malla);

        return $html;
    }

    private function inserta_materias($malla, $curso) {
        $con = \Yii::$app->db;
        $query = "insert into scholaris_mec_v2_distribucion(materia_id, curso_id) "
                . "select m.id, $curso
                    from 	scholaris_mec_v2_materia m
                                    inner join scholaris_malla_area a on a.id = m.malla_area_id
                    where	a.malla_id = $malla
                                    and m.id not in (select materia_id from scholaris_mec_v2_distribucion where materia_id = m.id and curso_id = $curso);";
        $con->createCommand($query)->execute();
    }

    private function get_html($curso, $malla) {
        $html = '';

//        $html.= '<p>'.Html::a('Editar', ['editar','malla' => $malla, 'curso' =>$curso], ['class' => 'btn btn-primary']).'</p>';

        $modelCurso = \backend\models\OpCourse::findOne($curso);
        $modelMaterias = \backend\models\ScholarisMecV2Materia::find()
                ->innerJoin("scholaris_mec_v2_area a", "a.id = scholaris_mec_v2_materia.malla_area_id")
                ->where(["a.malla_id" => $malla])
                ->all();

        $html .= '<center><u>' . $modelCurso->name . '</u></center>';

        $html .= '<div class="row">';
        $html .= '<div class="col-md-4"><center>ASIGNATURA MEC</center></div>';
        $html .= '<div class="col-md-8"><center>ASIGNATURA INSTITUCIONALES</center></div>';
        $html .= '</div>';
        $html .= '<hr>';

        $html .= '<div class="row">';
        $html .= '<div class="col-md-1"><center>COD MEC</center></div>';
        $html .= '<div class="col-md-3"><center>ASIGNATURA MEC</center></div>';
        $html .= '<div class="col-md-2"><center>TIPO</center></div>';
        $html .= '<div class="col-md-4"><center>ASIGNATURA</center></div>';
        $html .= '<div class="col-md-2"><center>CÓDIGO</center></div>';
        $html .= '</div>';

        $html .= '<hr>';

        foreach ($modelMaterias as $mat) {
            $html .= '<div class="row">';
            $html .= '<div class="col-md-1">' . $mat->id . '</div>';
            $html .= '<div class="col-md-3">' . $mat->nombre;
            $html .= '<p>' . Html::a('Ingresar asignatura', ['scholaris-mec-v2-homologacion/index1', 'materia' => $mat->id, 'curso' => $curso], ['class' => 'btn btn-link']) . '</p>';
            $html .= '</div>';

            $html .= $this->get_materias_institucion($mat->id, $curso);

            $html .= '</div>';
            $html .= '<hr>';
        }

        $html .= $this->get_opciones_reportes($malla, $curso);

        return $html;
    }

    private function get_materias_institucion($materia, $curso) {
        $html = '';

        $modelDistrib = \backend\models\ScholarisMecV2Distribucion::find()
                ->where(['materia_id' => $materia, 'curso_id' => $curso])
                ->one();
        $modelHomologaciones = \backend\models\ScholarisMecV2Homologacion::find()
                ->where(['distribucion_id' => $modelDistrib->id])
                ->all();

        foreach ($modelHomologaciones as $homol) {
            $html .= '<p>';
            $html .= '<div class="col-md-2"><center>' . $homol->tipo . '</center></div>';
            $html .= '<div class="col-md-4"><center>' . $homol->nombre_tipo . '</center></div>';
            $html .= '<div class="col-md-2"><center>' . $homol->codigo_tipo . '</center></div>';
            $html .= '</p>';
        }
        return $html;
    }

    private function get_opciones_reportes($malla, $curso) {
        
        $modelParalelos = \backend\models\OpCourseParalelo::find()->where(['course_id' => $curso])->all();
        $data = ArrayHelper::map($modelParalelos,'id','name');
        
        $html = '<center>REPORTES MEC</center>';
        $html .= '<div class="row">';
        
//        $html .= '<div class="btn-group btn-group-lg">';
        $html .= Html::beginForm(['reportes', 'post']);
        
        
        $html .= '<div class="col-md-4">';
        $html .= Select2::widget([
                    'name' => 'paralelo',
                    'value' => 0,
                    'data' => $data,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione paralelo',
//                        'onchange' => 'detalle(this,"' . Url::to(['detalle']) . '",' . $modelMalla->id . ');',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);        
        $html.= '</div>';
        
        $html .= '<div class="col-md-4">';
        $html .= Select2::widget([
                    'name' => 'reporte',
                    'value' => 0,
                    'data' => [
                                'reporte-mec-quimestral/index1' => 'REPORTE QUIMESTRAL'
                              ],
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione reporte',
//                        'onchange' => 'detalle(this,"' . Url::to(['detalle']) . '",' . $modelMalla->id . ');',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
        $html.= '</div>';
        
        $html.= '<input type="hidden" name="malla" value="'.$malla.'">';
        
        $html .= '<div class="col-md-4">';
        $html .= Html::submitButton('Generar Reporte',['class' => 'btn btn-primary']);
        $html.= '</div>';
        
//        $html .= Html::a('CUADRO QUIMESTRAL', ['reporte-mec-quimestral/index1','malla' => $malla, 'curso' => $curso], ['class' => 'btn btn-primary']);
//        $html .= Html::a('SUPLETORIOS', ['reporte-mec-supletorios/index1','malla' => $malla, 'curso' => $curso,'opcion' => 'supletorio'], ['class' => 'btn btn-warning']); 
//        $html .= Html::a('REMEDIAL', ['reporte-mec-supletorios/index1','malla' => $malla, 'curso' => $curso,'opcion' => 'remedial'], ['class' => 'btn btn-warning']); 
//        $html .= Html::a('GRACIA', ['reporte-mec-supletorios/index1','malla' => $malla, 'curso' => $curso,'opcion' => 'gracia'], ['class' => 'btn btn-warning']); 
//        $html .= Html::a('PROMOCIONES', ['reporte-mec-promocion/index1','malla' => $malla, 'curso' => $curso], ['class' => 'btn btn-primary']); 
        
        $html .= Html::endForm();
        
        $html .= '</div>';

//        $html.= '<div class="col-md-3">'.Html::a('CUADRO QUIMESTRAL', ['reporte-mec-quimestral/index1','materia' => $malla, 'curso' => $curso], ['class' => 'btn btn-primary']).'</div>'; 
//        $html.= '<div class="col-md-2">'.Html::a('SUPLETORIOS', ['reporte-mec-supletorios/index1','materia' => $malla, 'curso' => $curso,'opcion' => 'supletorio'], ['class' => 'btn btn-warning']).'</div>'; 
//        $html.= '<div class="col-md-2">'.Html::a('REMEDIAL', ['reporte-mec-supletorios/index1','materia' => $malla, 'curso' => $curso,'opcion' => 'remedial'], ['class' => 'btn btn-warning']).'</div>'; 
//        $html.= '<div class="col-md-2">'.Html::a('GRACIA', ['reporte-mec-supletorios/index1','materia' => $malla, 'curso' => $curso,'opcion' => 'gracia'], ['class' => 'btn btn-warning']).'</div>'; 
//        $html.= '<div class="col-md-3">'.Html::a('GRACIA', ['reporte-mec-promocion/index1','materia' => $malla, 'curso' => $curso], ['class' => 'btn btn-primary']).'</div>'; 
//        $html .= '</div>';

        return $html;
    }

    public function actionEditar($malla, $curso) {

        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($malla);
        $modelCurso = \backend\models\OpCourse::findOne($curso);
        $modelMaterias = \backend\models\ScholarisMecV2Materia::find()
                ->innerJoin("scholaris_mec_v2_area a", "a.id = scholaris_mec_v2_materia.malla_area_id")
                ->where(["a.malla_id" => $malla])
                ->all();

        return $this->render('editar', [
                    'modelCurso' => $modelCurso,
                    'modelMalla' => $modelMalla,
                    'modelMaterias' => $modelMaterias
        ]);
    }
    
    
    public function actionReportes(){
        $paralelo = $_POST['paralelo'];
        $malla = $_POST['malla'];
        $reporte = $_POST['reporte'];
        
        return $this->redirect([$reporte,
            'paralelo' => $paralelo,
            'malla' => $malla
        ]);
        
    }

}
