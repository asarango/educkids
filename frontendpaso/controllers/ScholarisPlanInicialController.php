<?php

namespace frontend\controllers;

use Yii;
use backend\models\ScholarisPlanPud;
use backend\models\ScholarisPlanPudSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisPlanPudController implements the CRUD actions for ScholarisPlanPud model.
 */
class ScholarisPlanInicialController extends Controller {

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
     * Lists all ScholarisPlanPud models.
     * @return mixed
     */
    public function actionIndex1() {
        $sentencis = new \backend\models\SentenciasPlanInicial();
        $clase = $_GET['id'];

        if (isset($_GET['quimestre'])) {
            $quimestre = $_GET['quimestre'];
        } else {
            $quimestre = 'QUIMESTRE I';
        }

        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        $modelEjes = $sentencis->get_ejes($modelClase->codigo_curso_curriculo);

        $modelPlan = \backend\models\ScholarisPlanInicial::find()
                ->where([
                    'clase_id' => $clase,
                    'quimestre_codigo' => $quimestre
                ])
                ->orderBy('orden')
                ->all();
        
        return $this->render('index', [
                    'modelClase' => $modelClase,
                    'quimestre' => $quimestre,
                    'modelEjes' => $modelEjes,
                    'modelPlan' => $modelPlan
        ]);
    }

    public function actionPlanificar() {

        $ambitoId = $_GET['id'];
        $quimestre = $_GET['quimestre'];
        $clase = $_GET['clase'];

        $modelAmbito = \backend\models\CurCurriculoAmbito::findOne($ambitoId);
        $modelDestreza = \backend\models\CurCurriculoDestreza::find()
                ->where(['ambito_id' => $ambitoId])
                ->orderBy('id')
                ->all();
        $modelClase = \backend\models\ScholarisClase::findOne($clase);


        return $this->render('planificar', [
                    'modelAmbito' => $modelAmbito,
                    'modelDestreza' => $modelDestreza,
                    'modelClase' => $modelClase,
                    'quimestre' => $quimestre
        ]);
    }

    public function actionAsignar() {

        $destrezaId = $_GET['id'];
        $quimestre = $_GET['quimestre'];
        $clase = $_GET['clase'];
        $modelDestreza = \backend\models\CurCurriculoDestreza::findOne($destrezaId);

        $modelPlan = \backend\models\ScholarisPlanInicial::find()
                ->where([
                    'clase_id' => $clase,
                    'quimestre_codigo' => $quimestre,
                    'codigo_destreza' => $modelDestreza->codigo
                ])
                ->one();

        
        if (count($modelPlan) > 0) {

            return $this->redirect(['desagregar',
                        'planId' => $modelPlan->id,
                        'ambitoId' => $modelDestreza->ambito_id,
//            return $this->redirect(['planificar',
//                        'id' => $modelDestreza->ambito_id,
//                        'quimestre' => $quimestre,
//                        'clase' => $clase
            ]);
        } else {
            $model = new \backend\models\ScholarisPlanInicial();
            $model->clase_id = $clase;
            $model->quimestre_codigo = $quimestre;
            $model->codigo_destreza = $modelDestreza->codigo;
            $model->destreza_original = $modelDestreza->nombre;
            $model->destreza_desagregada = $modelDestreza->nombre;
            $model->estado = 'CONSTRUYENDO';
            $model->save();
            $primary = $model->getPrimaryKey();

            return $this->redirect(['desagregar',
                        'planId' => $primary,
                        'ambitoId' => $modelDestreza->ambito_id,
                        
            ]);
        }
    }

    public function actionDesagregar() {
        $id = $_GET['planId'];
        $ambitoId = $_GET['ambitoId'];
        $model = \backend\models\ScholarisPlanInicial::findOne($id);
        
        
        if ($model->load(Yii::$app->request->post())) {          
            $model->save();
            return $this->redirect(['planificar',
                    'id' => $ambitoId,
                    'quimestre' => $model->quimestre_codigo,
                    'clase' => $model->clase_id
                ]);
        }
        
        return $this->render('desagregar',[
            'model' => $model,
            'ambitoId' => $ambitoId
        ]);
        
    }
    
    public function actionOrden(){
        $id = $_POST['id'];
        $orden = $_POST['orden'];
        
        $model = \backend\models\ScholarisPlanInicial::findOne($id);
        $model->orden = $orden;
        $model->save();
    }
    
    public function actionEliminar(){
        //print_r($_GET);
        $idCur = $_GET['id'];
        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        $ambito = $_GET['ambitoId'];
        
        $modelCur = \backend\models\CurCurriculoDestreza::findOne($idCur);
        
        $model = \backend\models\ScholarisPlanInicial::find()
                ->where([
                    'clase_id' => $clase,
                    'quimestre_codigo' => $quimestre,
                    'codigo_destreza' => $modelCur->codigo
                ])
                ->one();
        $model->delete();
        return $this->redirect(['planificar',
                        'id' => $ambito,
                        'quimestre' => $quimestre,
                        'clase' => $clase
              ]);
    }
    
    public function actionCopiar(){
        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $modelCursos = $this->get_cursos_planificados($modelClase->idcurso, $modelClase->idmateria, $quimestre);
        
        return $this->render('copiar',[
           'modelClase' => $modelClase,
           'modelCursos' => $modelCursos,
           'quimestre' => $quimestre
        ]);
        
    }
    
    private function get_cursos_planificados($curso, $materia, $quimestre){
        $con = Yii::$app->db;
        $query = "select 	cu.id as curso_id
		,cu.name as curso
		,p.name as paralelo
		,c.id as clase_id
from 	scholaris_plan_inicial i
		inner join scholaris_clase c on c.id = i.clase_id
		inner join op_course cu on cu.id = c.idcurso
		inner join op_course_paralelo p on p.id = c.paralelo_id
where	c.idcurso = $curso
		and c.idmateria = $materia
		and i.quimestre_codigo = '$quimestre'
group by cu.id,cu.name,p.name,c.id
order by cu.id,cu.name,p.name,c.id;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function actionReporte(){
        $clase = $_GET['clase'];
        $quimestre = $_GET['quimestre'];
        
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);
        
        $cabecera = $this->cabecera($modelClase, $quimestre);
        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->html($modelClase, $quimestre);
//
        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));


        $mpdf->Output('Planificacion_EGI' . "curso" . '.pdf', 'D');
        exit;
    }
    
    protected function cabecera($modelClase, $quimestre) {
        $html = '';


        $html .= '<table width="100%" cellspacing="0" style="font-size: 10;">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="30px"></td>';
        $html .= '<td align="center"><strong>' . $modelClase->paralelo->course->xInstitute->name . '<br>'
                . 'PLANIFICACIÓN  QUIMESTRAL - '.$quimestre.'<strong></td>';
        $html .= '<td align="center" width="20%"><strong>';
        $html .= $modelClase->paralelo->course->name.' '.$modelClase->paralelo->name;
        $html .= '<strong></td>';
        $html .= '<tr>';
        $html .= '</table>';

        return $html;
    }
    
    protected function html($modelClase, $quimestre) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';
        $html .= $this->uno_datos($modelClase, $quimestre);
        $html .= $this->firmas($modelClase, $quimestre);

        return $html;
    }
    
    private function uno_datos($modelClase,$quimestre) {
        $html = '';
        $html .= '<p class="tamano10" align="center">1. DATOS INFORMATIVOS</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>DOCENTE:</strong></td>';
        $html .= '<td class="conBorde ">' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>  ASIGNATURA:</strong></td>';
        $html .= '<td class="conBorde">' . $modelClase->materia->name . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $modelPlan = \backend\models\ScholarisPlanInicial::find()
                    ->where(['clase_id' => $modelClase->id, 'quimestre_codigo' => $quimestre])
                    ->all();

        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>CÓDIGO</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>DESTREZA ORIGINAL</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>DESTREZA DESAGREGADA (MODIFICADA)</strong></td>';
        $html .= '</tr>';
        
        foreach ($modelPlan as $plan){
            $html .= '<tr>';
            $html .= '<td class="conBorde">'.$plan->codigo_destreza.'</td>';
            $html .= '<td class="conBorde">'.$plan->destreza_original.'</td>';
            $html .= '<td class="conBorde">'.$plan->destreza_desagregada.'</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';

        return $html;
    }
    
    private function firmas($modelClase, $quimestre){
        
        $fecha = date("Y-m-d");
        
        $html = '';
        $html.= '<br>';
        
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td class="conBorde colorEtiqueta" width="34%" align="center">ELABORADO</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td class="conBorde" align="center">'.$modelClase->profesor->x_first_name.' '.$modelClase->profesor->last_name.'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }
    
    public function actionCopiarejecuta($clase, $quimestre){
        //print_r($_GET);
        $clase = $_GET['clase'];
        $clasePlanificada = $_GET['clase_planificada'];
        $quimestre = $_GET['quimestre'];
        
        $this->ingresa_copia_plan($clasePlanificada, $clase, $quimestre);    
        
        
        return $this->redirect(['index1', 'id' => $clase]);
        
    }
    
    private function ingresa_copia_plan($claseOriginal, $clase, $quimestre){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_plan_inicial(clase_id, quimestre_codigo, codigo_destreza, destreza_original, destreza_desagregada, estado, orden)
                    select 	$clase, p.quimestre_codigo, p.codigo_destreza
                                    ,p.destreza_original
                                    ,p.destreza_desagregada
                                    ,p.estado
                                    ,p.orden
                    from 	scholaris_plan_inicial p
                    where 	p.clase_id = $claseOriginal
                                    and p.quimestre_codigo = '$quimestre'
                                    and p.codigo_destreza not in (
                                                    select 	codigo_destreza
                                                    from 	scholaris_plan_inicial
                                                    where	clase_id = $clase
                                                                    and quimestre_codigo = p.quimestre_codigo		
                                    )
                    order by orden;";
        $con->createCommand($query)->execute();
    }

}
