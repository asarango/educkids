<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisParametrosOpciones;
use backend\models\ScholarisParametrosOpcionesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;

/**
 * ScholarisParametrosOpcionesController implements the CRUD actions for ScholarisParametrosOpciones model.
 */
class ScholarisPciController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//              'class' => AccessControl::className(),
//                'rules' => [
//                  [
//                      'allow' => true,
//                      'roles' => ['@'],
//                  ]  
//                ],
//            ],
//            
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//     public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//            
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
//                echo $this->render('/site/error',[
//                   'message' => "Acceso denegado. No puede ingresar a este sitio !!!", 
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Lists all ScholarisParametrosOpciones models.
     * @return mixed
     */
    public function actionIndex() {

        $peridoId = Yii::$app->user->identity->periodo_id;
        $modelSubniveles = \backend\models\GenSubnivel::find()->all();
        $modelOp = new \backend\models\ScholarisPlanPci();
        $modelSub = '';
        $modelPci = '';
        $modelOptativas = '';
        
        //print_r($_POST);
        if ($_POST) {
            
            
            $subnivel = $_POST['subnivel'];
            $modelSub = \backend\models\GenSubnivel::find()->where(['id' => $subnivel])->one();
            $this->ingresa_materias_a_pci($subnivel);

            $modelPci = \backend\models\ScholarisPlanPci::find()->where(['subnivel_id' => $subnivel,'periodo_id' => $peridoId])->all();
            $modelOptativas = $modelOp->consulta_materias_curriculo_optativas($subnivel);
        } 
//        else {
//            $modelSub = '';
//            $modelPci = '';
//            $modelOptativas = '';
//            
//            //return $this->redirect(['index']);
//        }

        return $this->render('index', [
                    'modelSubniveles' => $modelSubniveles,
                    'modelSub' => $modelSub,
                    'modelPci' => $modelPci,
                    'modelOptativas' => $modelOptativas
        ]);
    }
    
    public function actionOptativa(){
        $matMallaCurr = $_POST['materia'];
        $subnivel = $_POST['subnivel'];
        $periodoId = \Yii::$app->user->identity->periodo_id;
        
        $modelMatMalla = \backend\models\GenMallaMateria::findOne($matMallaCurr);
        
        $model = new \backend\models\ScholarisPlanPci();
        $model->subnivel_id = $subnivel;
        $model->materia_curriculo_id = $matMallaCurr;
        $model->materia_curriculo_nombre = $modelMatMalla->materia->nombre;
        $model->materia_curriculo_color = $modelMatMalla->materia->color;
        $model->tipo_materia = $modelMatMalla->tipo_materia;
        $model->subnivel_codigo = $modelMatMalla->mallaArea->subnivel->codigo;
        $model->materia_curriculo_codigo = $modelMatMalla->materia->codigo;
        $model->periodo_id = $periodoId;
        
        $model->save();
        
        return $this->redirect(['index']);
    }

    private function ingresa_materias_a_pci($subnivel) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        
        $model = new \backend\models\ScholarisPlanPci();
        $materias = $model->consulta_materias_curriculo($subnivel);

        foreach ($materias as $materia) {
            $modelPci = \backend\models\ScholarisPlanPci::find()->where([
                'subnivel_id' => $subnivel, 
                'materia_curriculo_id' => $materia['id'],
                'periodo_id' => $periodoId
                ])->one();

            if ($modelPci) {
                
            } else {
                $model2 = new \backend\models\ScholarisPlanPci();
                $model2->subnivel_id = $subnivel;
                $model2->materia_curriculo_id = $materia['id'];
                $model2->materia_curriculo_nombre = $materia['nombre'];
                $model2->materia_curriculo_color = $materia['color'];
                $model2->tipo_materia = $materia['tipo_materia'];
                $model2->subnivel_codigo = $materia['codigo'];
                $model2->materia_curriculo_codigo = $materia['materia_codigo'];
                $model2->periodo_id = $periodoId;

                $model2->save();
            }
        }
    }

    public function actionDetalle() {
        $modelPci = \backend\models\ScholarisPlanPci::find()->where(['id' => $_GET['pci']])->one();
        $modelSubNivel = \backend\models\GenSubnivel::find()->where(['id' => $modelPci->subnivel_id])->one();
        $modelCursos = \backend\models\GenCurso::find()->where(['subnivel_id' => $modelPci->subnivel_id])->orderBy('orden')->all();
        $modelEvaluaciones = \backend\models\CurCurriculo::find()
                ->select(['id', "concat(codigo,' ',detalle) as codigo"])
                ->where([
                    'tipo_referencia' => 'evaluacion',
                    'materia_id' => $modelPci->materia_curriculo_id
                ])
                ->orderBy('codigo')
                ->all();

        $modelPciEvalu = \backend\models\ScholarisPlanPciEvaluacion::find()
                        ->where(['pci_id' => $_GET['pci']])
                        ->orderBy('codigo_criterio_evaluacion')->all();

        return $this->render('detalle', [
                    'modelPci' => $modelPci,
                    'modelSubNivel' => $modelSubNivel,
                    'modelCursos' => $modelCursos,
                    'modelEvaluaciones' => $modelEvaluaciones,
                    'modelPciEvalu' => $modelPciEvalu
        ]);
    }

    public function actionEliminareval() {
        $id = $_GET['evaluacion'];
        $model = \backend\models\ScholarisPlanPciEvaluacion::findOne($id);
        $pci = $model->pci_id;

        $model->delete();

        return $this->redirect(['detalle', 'pci' => $pci]);
    }

    public function actionCreate() {

        $eval = $_POST['evaluaciones'];
        $pci = $_POST['pci'];

        $modelCurriculo = \backend\models\CurCurriculo::find()
                ->where(['id' => $eval])
                ->one();


        $model = new \backend\models\ScholarisPlanPciEvaluacion();
        $model->pci_id = $pci;
        $model->codigo_criterio_evaluacion = $modelCurriculo->codigo;
        $model->descripcion_criterio_evaluacion = $modelCurriculo->detalle;


        $model->save();
        return $this->redirect(['detalle', 'pci' => $pci]);
    }

    public function actionCreatedestreza() {

        $destreza = $_POST['destreza'];
        $curso = $_POST['curso'];
        $evaluacion = $_POST['evaluacion'];
        $desagrega = $_POST['desagrega'];

        if ($desagrega == 'false') {
            $desagrega = false;
        } else {
            $desagrega = true;
        }


        $modelCurso = \backend\models\GenCurso::find()->where(['id' => $curso])->one();
        $modelDestreza = \backend\models\CurCurriculo::find()->where(['id' => $destreza])->one();
        $modelEvaluacion = \backend\models\ScholarisPlanPciEvaluacion::find()->where(['id' => $evaluacion])->one();

        $model = new \backend\models\ScholarisPlanPciEvaluacionDestrezas();
        $model->evaluacion_id = $evaluacion;
        $model->curso_subnivel_id = $curso;
        $model->curso_subnivel_nombre = $modelCurso->nombre;
        $model->destreza_id = $destreza;
        $model->destreza_codigo = $modelDestreza->codigo;
        $model->destreza_detalle = $modelDestreza->detalle;
        $model->desagregado = $desagrega;
        $model->curso_subnivel_codigo = $modelCurso->codigo;
        $model->save();

        return $this->redirect(['detalle', 'pci' => $modelEvaluacion->pci_id]);
    }

    public function actionEliminar() {
        $destreza = $_GET['destreza'];

        $model = \backend\models\ScholarisPlanPciEvaluacionDestrezas::findOne($destreza);
        $pci = $model->evaluacion->pci_id;
        $model->delete();
        return $this->redirect(['detalle', 'pci' => $pci]);
    }

    public function actionDesagregar() {
        $destreza = $_GET['destreza'];
        //echo $destreza;        
        $model = \backend\models\ScholarisPlanPciEvaluacionDestrezas::findOne($destreza);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['detalle', 'pci' => $model->evaluacion->pci_id]);
        }

        return $this->render('desagregar', [
                    'model' => $model
        ]);
    }

    public function actionPdf() {
        $subnivel = $_GET['subnivel'];

        $modelSubNivel = \backend\models\GenSubnivel::findOne($subnivel);

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);


        $cabecera = $this->genera_cabecera_pdf($modelSubNivel);
        $pie = $this->genera_pie_pdf();
//        $cuerpo = $this->genera_cuerpo_pdf($modelSubNivel, $mpdf);

        $mpdf->SetHeader($cabecera);

        //$mpdf->WriteHTML($cuerpo, $this->renderPartial('mpdf'));
        $modelPci = \backend\models\ScholarisPlanPci::find()
                ->where(['subnivel_id' => $modelSubNivel->id])
                ->all();

        $modelCursos = \backend\models\GenCurso::find()
                ->where(['subnivel_id' => $subnivel])
                ->orderBy('orden')
                ->all();

        foreach ($modelPci as $data) {

            $html = '<div align="center" style="top:0; float: right;">' . $data->materia_curriculo_nombre . '</div>';
            $html .= $this->genera_cuerpo_pdf($data->id, $modelCursos);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }


        $mpdf->WriteHTML($html);

        $mpdf->SetFooter($pie);

        $mpdf->Output('PCI ' . $modelSubNivel->nombre . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($modelSubNivel) {
        $html = '';
//        $html .= '<div style="width: 14cm; height: 21cm; position: relative">
        $html .= '<div align="left" style="width: 200px; top:0; float: left;"><img src="imagenes/instituto/mec/selloMec.png" width="100px"></div>';
        $html .= '<div style="width: 200px; top:0; float: right;"><img src="imagenes/instituto/mec/ase.png" width="60px"></div>';
        $html .= '<div align="center" style="top:0; float: right;">';
        $html .= 'CZ6-ASRE-EQUIPO DE ASESORÍA EDUCATIVA - 2017<br>MATRIZ DE DISTRIBUCION DE DESTREZAS (PCI.)<br>' . $modelSubNivel->nombre;
        $html .= '</div>';

        return $html;
    }

    private function genera_pie_pdf() {
        $html = '';
        $html .= '<div style="width: 200px; top:0; float: right;"><p>{PAGENO}</p></div>';
        return $html;
    }

    private function genera_cuerpo_pdf($pciId, $modelCursos) {

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td rowspan="2" align="center">CRITERIOS DE EVALUACIÓN</td>';
        $html .= '<td colspan="3" align="center">DESTREZAS CON CRITERIOS DE DESEMPEÑO</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center">' . $modelCursos[0]['nombre'] . '</td>';
        $html .= '<td align="center">' . $modelCursos[1]['nombre'] . '</td>';
        $html .= '<td align="center">' . $modelCursos[2]['nombre'] . '</td>';
        $html .= '</tr>';

        $modelEvaluacion = \backend\models\ScholarisPlanPciEvaluacion::find()
                ->where(['pci_id' => $pciId])
                ->all();

        foreach ($modelEvaluacion as $eva) {
            $html .= '<tr>';
            $html .= '<td align="left">' . $eva->codigo_criterio_evaluacion . ' ' . $eva->descripcion_criterio_evaluacion . '</td>';
            
            foreach ($modelCursos as $curso){
                
                $modelDes = \backend\models\ScholarisPlanPciEvaluacionDestrezas::find()
                        ->where(['evaluacion_id' => $eva->id, 'curso_subnivel_id' => $curso->id])
                        ->all();
                $html .= '<td align="left">';
                foreach ($modelDes as $des){
                    $html .= $des->destreza_codigo.' '.$des->destreza_detalle;
                    $html .= '<hr>';
                }
                
                $html .= '</td>';
                
                
            }
                                    
            
            
            
            
            $html .= '<tr>';
        }




        $html .= '</table>';


        return $html;
    }

}
