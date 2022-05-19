<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
//use backend\models\SentenciasSql;
use frontend\models\SentenciasSql;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ReportePudController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//
//    public function beforeAction($action) {
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
//            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
//                echo $this->render('/site/error', [
//                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex1() {


        $pudId = $_GET['pudId'];
        $modelPud = \backend\models\ScholarisPlanPud::findOne($pudId);



        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);



        $cabecera = $this->cabecera($modelPud);
        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->html($modelPud);

        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));


        $mpdf->Output('Reporte_PUD' . "curso" . '.pdf', 'D');
        exit;
    }

    protected function cabecera($modelPud) {
        $html = '';


        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="30px"><br>'
                . '<img src="imagenes/instituto/logo/logoaux.png" width="30px"></td>';
        $html .= '<td align="center">' . $modelPud->clase->course->xInstitute->name . '<br>'
                . 'PLANIFICACIÓN  DE UNIDAD DIDÁCTICA (PUD)</td>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logored.png" width="100px"></td>';
        $html .= '<tr>';
        $html .= '</table>';

        return $html;
    }

    protected function html($modelPud) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';

        $html .= $this->uno_datos($modelPud);
        $html .= $this->dos_planificacion($modelPud);
        $html .= $this->tres_adaptaciones($modelPud);
        $html .= $this->tres_uno_actividades($modelPud);
        $html .= $this->cuatro_bibliografia($modelPud);
        $html .= $this->quinto_observaciones($modelPud);
        $html .= $this->firmas($modelPud);

        return $html;
    }

    private function uno_datos($modelPud) {
        $html = '';
        $html .= '<p class="tamano10" align="center">1. DATOS INFORMATIVOS</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>DOCENTE:</strong></td>';
        $html .= '<td class="conBorde ">' . $modelPud->clase->profesor->last_name . ' ' . $modelPud->clase->profesor->x_first_name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>GRADO/CURSO:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->clase->course->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>PARALELO:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->clase->paralelo->name . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>AREA:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->clase->materia->area->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>  ASIGNATURA:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->clase->materia->name . '</td>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>UNIDAD N:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->bloque->name . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>TITULO DE LA UNIDAD:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->titulo . '</td>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="2"><strong>FECHA INICIO:</strong></td>';
        $html .= '<td class="conBorde" rowspan="2">' . $modelPud->bloque->bloque_inicia . '</td>';
        $html .= '<td class="conBorde colorEtiqueta" rowspan="2"><strong>FECHA DE FINALIZACIÓN:</strong></td>';
        $html .= '<td class="conBorde" rowspan="2">' . $modelPud->bloque->bloque_finaliza . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>NÚMERO DE PERÍODOS:</strong></td>';
        $html .= '<td class="conBorde"></td>';

        $html .= '</tr>';

        $html .= '</table>';


        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta"><strong>OBJETIVO DE LA UNIDAD:</strong></td>';
        $html .= '<td class="conBorde">' . $modelPud->objetivo_unidad . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function dos_planificacion($modelPud) {
        $html = '';
        $html .= '<p class="tamano10" align="center">2. PLANIFICACIÓN</p>';

        $modelDestrezas = \backend\models\ScholarisPlanPudDetalle::find()
                ->where([
                    'tipo' => 'destreza',
                    'pud_id' => $modelPud->id
                ])
                ->all();

        $i = 0;
        foreach ($modelDestrezas as $destreza) {
            $i++;
            $html .= '<table width="100%" cellspacing="0" class="tamano8">';
            $html .= '<tr>';
            $html .= '<td class="conBorde colorEtiqueta" width="30%"><strong>DESTREZA CON CRITERIO DE DESEMPEÑO (' . $i . '): </strong></td>';
            $html .= '<td class="conBorde">' . $destreza->codigo . ' ' . $destreza->contenido . '</td>';
            $html .= '<td class="conBorde colorEtiqueta"><strong>EJE TRANSVERSAL: </strong></td>';

            $modelEjes = \backend\models\ScholarisPlanPudDetalle ::find()
                    ->where([
                        'pertenece_a_codigo' => $destreza->codigo,
                        'tipo' => 'eje'
                    ])
                    ->all();

            $html .= '<td class="conBorde" colspan="2">';
            foreach ($modelEjes as $eje) {
                $html .= '<ul>';
                $html .= '<li>' . $eje->contenido . '</li>';
                $html .= '</ul>';
            }
            $html .= '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="conBorde colorEtiqueta" colspan="4" align="center"><strong>ACTIVIDADES  DE APRENDIZAJE</strong></td>';
            $html .= '<td class="conBorde colorEtiqueta" rowspan="2"><strong>RECURSOS</strong></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="conBorde colorEtiqueta" align="center" width="20%">EXPLORO Y CONOZCO (ANTICIPACIÓN) Indagación</td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center" width="20%">COMPRENDO (CONSTRUCCIÓN) Acción</td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center" width="20%">APLICO LO APRENDIDO (CONSOLIDACIÓN) Reflexión</td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center" width="20%">ME COMPROMETO</td>';
            $html .= '</tr>';

            $html .= '<tr>';

            $html .= '<td class="conBorde">';
            $modelExploro = $this->momentos($destreza->id, 'exploro');

            foreach ($modelExploro as $data) {
                $html .= '<ul>';
                $html .= '<li>' . $data['momento_detalle'] . '</li>';
                $html .= '</ul>';
            }
            $html .= '</td>';

            $html .= '<td class="conBorde">';
            $modelComprendo = $this->momentos($destreza->id, 'comprendo');
            foreach ($modelComprendo as $data) {
                $html .= '<ul>';
                $html .= '<li>' . $data['momento_detalle'] . '</li>';
                $html .= '</ul>';
            }
            $html .= '</td>';

            $html .= '<td class="conBorde">';
            $modelAplico = $this->momentos($destreza->id, 'aplico');
            foreach ($modelAplico as $data) {
                $html .= '<ul>';
                $html .= '<li>' . $data['momento_detalle'] . '</li>';
                $html .= '</ul>';
            }
            $html .= '</td>';

            $html .= '<td class="conBorde">';
            $modelComprometo = $this->momentos($destreza->id, 'comprometo');
            foreach ($modelComprometo as $data) {
                $html .= '<ul>';
                $html .= '<li>' . $data['momento_detalle'] . '</li>';
                $html .= '</ul>';
            }
            $html .= '</td>';


            $html .= '<td class="conBorde">';
            $modelRecurso = $this->detalles_de_detalle($destreza->codigo, 'recurso');
            foreach ($modelRecurso as $data) {
                $html .= '<ul>';
                $html .= '<li>' . $data->contenido . '</li>';
                $html .= '</ul>';
            }
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '<br>';


            $html .= $this->dos_uno_evaluaciones($destreza->id);
        }


        return $html;
    }

    private function tres_adaptaciones($modelPud) {

        $html = '';
        $html .= '<br>';
        $html .= '<p class="tamano10" align="center">3. ADAPTACIONES CURRICULARES</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';

        $html .= '<td class="conBorde colorEtiqueta" width="50%" align="center"><strong>ESPECIFICACIÓN DE LA NECESIDAD EDUCATIVA ATENDIDA</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" width="50%" align="center"><strong>ESPECIFICACIÓN DE LA ADAPTACIÓN APLICADA</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde" width="">' . $modelPud->ac_necesidad_atendida . '</td>';
        $html .= '<td class="conBorde" width="">' . $modelPud->ac_adaptacion_aplicada . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }

    private function tres_uno_actividades($modelPud) {

        $model = \app\models\ScholarisActividad::find()
                ->innerJoin("scholaris_plan_pud_detalle d", "d.id = scholaris_actividad.destreza_id")
                ->where(["d.pud_id" => $modelPud->id])
                ->all();

        $html = '';
        $html .= '<br>';
        $html .= '<p class="tamano10" align="center">EVALUACIÓN FORMATIVA</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" width="25%" align="center">INSUMOS</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="50%" align="center">DESCRIPCIÓN DE LAS ACTIVIDADES EVALUATIVAS</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="25%" align="center">FECHA DE ENTREGA</td>';
        $html .= '</tr>';

        foreach ($model as $activ) {
            if ($activ->insumo->tipo == 'F') {
                $html .= '<tr>';
                $html .= '<td class="conBorde">' . $activ->insumo->nombre_nacional . '</td>';
                $html .= '<td class="conBorde">' . $activ->title . '</td>';
                $html .= '<td class="conBorde" align="center">' . $activ->inicio . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        
        $html .= '<p class="tamano10" align="center">EVALUACIÓN SUMATIVA</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" width="25%" align="center">INSUMOS</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="50%" align="center">DESCRIPCIÓN DE LAS ACTIVIDADES EVALUATIVAS</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="25%" align="center">FECHA DE APLICACIÓN</td>';
        $html .= '</tr>';

        foreach ($model as $activ) {
            if ($activ->insumo->tipo == 'S') {
                $html .= '<tr>';
                $html .= '<td class="conBorde">' . $activ->insumo->nombre_nacional . '</td>';
                $html .= '<td class="conBorde">' . $activ->title . '</td>';
                $html .= '<td class="conBorde" align="center">' . $activ->inicio . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';


        return $html;
    }
    
    
    private function cuatro_bibliografia($modelPud){
        $html = '';
        $html .= '<p class="tamano10" align="center">4.BIBLIOGRAFÍA/ WEBGRAFÍA (Utilizar normas APA VI edición)</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde">'.$modelPud->bibliografia.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    private function quinto_observaciones($modelPud){
        $html = '';
        $html .= '<p class="tamano10" align="center">5. OBSERVACIONES</p>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde">'.$modelPud->observaciones.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    
    private function firmas($modelPud){
        
        $fecha = date("Y-m-d");
        
        $html = '';
        $html.= '<br>';
        
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" width="33%" align="center">ELABORADO</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="34%" align="center">REVISADO</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="33%" align="center">APROBADO</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" align="center">'.$modelPud->clase->profesor->last_name.' '.$modelPud->clase->profesor->x_first_name.'</td>';
        $html .= '<td class="conBorde" align="center">'.$modelPud->quienRevisa->last_name.' '.$modelPud->quienRevisa->x_first_name.'</td>';
        $html .= '<td class="conBorde" align="center">'.$modelPud->quienAprueba->last_name.' '.$modelPud->quienAprueba->x_first_name.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '<td class="conBorde" align="center" height="40"></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '<td class="conBorde" align="center" height="">'.$fecha.'</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        return $html;
    }

    private function dos_uno_evaluaciones($destrezaId) {

        $model = \backend\models\ScholarisPlanPudDetalle::findOne($destrezaId);
        $modelDetalle = \backend\models\ScholarisPlanPudDetalle::find()
                ->where([
                    'pertenece_a_codigo' => $model->codigo,
                    'pud_id' => $model->pud_id
                ])
                ->orderBy('tipo')
                ->all();

        $html = '';


        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde" colspan="3" align="center"><strong>EVALUACION</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" width="33%" align="center">CRITERIO DE EVALUACIÓN</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="34%" align="center">INDICADORES PARA LA EVALUACIÓN DEL CRITERIO</td>';
        $html .= '<td class="conBorde colorEtiqueta" width="33%" align="center">TIPOS, TÉCNICAS E INSTRUMENTOS</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde">';
        $html .= $this->get_detalle($modelDetalle, 'evaluacion');
        $html .= '</td>';

        $html .= '<td class="conBorde">';
        $html .= $this->get_detalle($modelDetalle, 'indicador');
        $html .= '</td>';

        $html .= '<td class="conBorde">';
        $html .= $this->get_tecnicas($modelDetalle);
        $html .= '</td>';

        $html .= '</tr>';

        $html .= '</table>';
        $html .= '<br>';

        return $html;
    }

    private function get_detalle($modelDetalle, $tipo) {
        $html = '';
        foreach ($modelDetalle as $evaluacion) {
            if ($evaluacion->tipo == $tipo) {
                $html .= '<ul>';
                $html .= '<li>' . $evaluacion->contenido . '</li>';
                $html .= '</ul>';
            }
        }
        return $html;
    }

    private function get_tecnicas($modelDetalle) {
        $html = '';
        foreach ($modelDetalle as $evaluacion) {
            if ($evaluacion->tipo == 'tecnicas' || $evaluacion->tipo == 'tipos' || $evaluacion->tipo == 'instrumentos') {
                $html .= '<ul>';
                $html .= '<li>' . $evaluacion->contenido . ' <strong>(' . $evaluacion->tipo . ')</strong></li>';
                $html .= '</ul>';
            }
        }
        return $html;
    }

    private function momentos($destrezaId, $tipo) {
        $con = Yii::$app->db;
        $query = "select 	a.momento_detalle
                    from	scholaris_actividad a
                                    inner join scholaris_momentos_academicos m on m.id = a.momento_id
                    where	a.destreza_id = $destrezaId
                                    and m.codigo = '$tipo';";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function detalles_de_detalle($destrezaCodigo, $tipo) {
        $modelDetalle = \backend\models\ScholarisPlanPudDetalle::find()
                ->where([
                    'pertenece_a_codigo' => $destrezaCodigo,
                    'tipo' => $tipo
                ])
                ->all();

        return $modelDetalle;
    }

}
