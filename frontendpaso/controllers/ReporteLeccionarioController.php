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
class ReporteLeccionarioController extends Controller {
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


        $tomaId = $_GET['id'];
        $modelToma = \backend\models\ScholarisTomaAsistecia::findOne($tomaId);



        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 16,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $cabecera = $this->cabecera($modelToma);
        $mpdf->SetHeader($cabecera);
//        $mpdf->showImageErrors = true;

        $html = $this->html($modelToma);
        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));


        $mpdf->Output('Leccionario' . $modelToma->paralelo->course->name . ' ' . $modelToma->paralelo->name . '.pdf', 'D');
        exit;
    }

    protected function cabecera($modelToma) {
        $html = '';


        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="30px"></td>';
        $html .= '<td align="center">' . $modelToma->paralelo->institute->name . '<br>'
                . 'LECCIONARIO</td>';
        $html .= '<td align="right" width="20%">';
        $html .= '<h6>' . $modelToma->paralelo->course->name . ' ' . $modelToma->paralelo->name . '</h6>';
        $html .= '<h6>' . $modelToma->fecha . '</h6>';
        $html .= '</td>';
        $html .= '<tr>';
        $html .= '</table>';

        return $html;
    }

    protected function html($modelToma) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';

        $html .= $this->docentes($modelToma);
        $html .= $this->estudiantes($modelToma);
        $html .= $this->temas_deberes($modelToma);


        return $html;
    }

    private function temas_deberes($modelToma) {
        $sentencias = new \backend\models\SentenciasLeccionario();
        $diaNumero = date("w", strtotime($modelToma->fecha));

        $modelClases = $sentencias->get_clases_fecha($modelToma->paralelo_id, $diaNumero);
        //$modelEstudiantes = $sentencias->get_estudiantes($modelToma->fecha, $modelToma->paralelo_id);

        $html = '';
        $html .= '<hr>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>HORA</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>ASIGNATURA</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>TEMAS TRATADOS EN CLASE</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>ACTIVIDADES (DEBERES) PARA ESTE DIA</strong></td>';
        $html .= '</tr>';

        foreach ($modelClases as $clase) {
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $clase['sigla'] . '</td>';
            $html .= '<td class="conBorde">' . $clase['materia'] . '</td>';
            $temas = $this->temas($clase['clase_id'], $modelToma->fecha);
            if(count($temas)>0){
                $html .= '<td class="conBorde">';
                foreach ($temas as $tema){
                    $html .= $tema['tema'].';';
                }
                $html .= '</td>';
            }else{
                $html .= '<td class="conBorde"></td>';
            }
            
            $actividades = $this->actividades($clase['clase_id'], $modelToma->fecha);
            if(count($temas)>0){
                $html .= '<td class="conBorde">';
                foreach ($actividades as $acti){
                    $html .= $acti->title.';';
                }
                $html .= '</td>';
            }else{
                $html .= '<td class="conBorde"></td>';
            }

            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    private function temas($claseId, $fecha) {
        $sentencias = new \backend\models\SentenciasLeccionario();
        $model = $sentencias->get_temas($claseId, $fecha);
        return $model;
    }
    
    private function actividades($claseId, $fecha) {
        
        $model = \backend\models\ScholarisActividad::find()
                ->where(['paralelo_id' => $claseId, 'inicio' => $fecha])
                ->all();
        
        return $model;
    }

    private function estudiantes($modelToma) {
        $sentencias = new \backend\models\SentenciasLeccionario();
        $diaNumero = date("w", strtotime($modelToma->fecha));

        //$modelClases = $sentencias->get_clases_fecha($modelToma->paralelo_id, $diaNumero);

        $modelEstudiantes = $sentencias->get_estudiantes($modelToma->fecha, $modelToma->paralelo_id);

        $html = '';
        $html .= '<hr>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>HORA</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>ESTUDIANTE</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>COMPORTAMIENTO</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>DETALLE</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>OBERVACION</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>JUSTIFICACION</strong></td>';
        $html .= '</tr>';

        foreach ($modelEstudiantes as $data) {
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $data['sigla'] . '</td>';
            $html .= '<td class="conBorde">' . $data['est_apellido'] . ' ' . $data['est_nombre1'] . ' ' . $data['est_nombre2'] . '</td>';
            $html .= '<td class="conBorde">' . $data['comportamiento'] . '</td>';
            $html .= '<td class="conBorde">' . $data['nombre'] . '</td>';
            $html .= '<td class="conBorde">' . $data['observacion'] . '</td>';
            $html .= '<td class="conBorde">' . $this->justificacion_alumno($data['novedad_id']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    private function justificacion_alumno($novedadId) {
        //$sentencias = new \backend\models\SentenciasLeccionario();
        $modelJustif = \backend\models\ScholarisAsistenciaJustificacionAlumno::find()
                ->where(['novedad_id' => $novedadId])
                ->one();

        if (count($modelJustif) > 0) {
            return 'Justificado';
        } else {
            return 'Sin Justificar';
        }
    }

    private function docentes($modelToma) {
        $sentencias = new \backend\models\SentenciasLeccionario();
        $diaNumero = date("w", strtotime($modelToma->fecha));

        $modelClases = $sentencias->get_clases_fecha($modelToma->paralelo_id, $diaNumero);
        //$modelEstudiantes = $sentencias->get_estudiantes($modelToma->fecha, $modelToma->paralelo_id);

        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>HORA</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>ASIGNATURA</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>DOCENTE</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>INGRESO</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>ESTADO</strong></td>';
        $html .= '<td align="center" class="conBorde colorEtiqueta"><strong>JUSTIFICACION</strong></td>';
        $html .= '</tr>';

        foreach ($modelClases as $clase) {
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $clase['sigla'] . '</td>';
            $html .= '<td class="conBorde">' . $clase['materia'] . '</td>';
            $html .= '<td class="conBorde">' . $clase['last_name'] . ' ' . $clase['x_first_name'] . '</td>';
            $html .= $this->asistencia_profesor($clase['clase_id'], $modelToma->fecha, $clase['desde'], $clase['hasta'], $clase['hora_id']);
            $html .= '</tr>';
        }

        $html .= '</table>';



        return $html;
    }

    private function asistencia_profesor($clase, $fecha, $desde, $hasta, $horaId) {
        $html = '';
        $desde = strtotime($desde, time());
        $hasta = strtotime($hasta, time());
        $modelAsistencia = \backend\models\ScholarisAsistenciaProfesor::find()
                ->where([
                    'fecha' => $fecha,
                    'clase_id' => $clase,
                    'hora_id' => $horaId
                ])
                ->orderBy("hora_ingresa")
                ->one();
        if ($modelAsistencia) {
            $html .= '<td class="conBorde" align="center">';
            $html .= $modelAsistencia->hora_ingresa;
            $ingresa = strtotime($modelAsistencia->hora_ingresa, time());
            $html .= '</td>';

            if ($ingresa >= $desde && $ingresa <= $hasta) {
                $estado = 'OK';
                //$html .= '<td>OK</td>';
            } else {
                $estado = 'FUERA DE TIEMPO';
                //$html .= '<td></td>';
            }
        } else {
            $estado = 'FALTA';
            $html .= '<td class="conBorde"></td>';
        }

        $html .= '<td class="conBorde" align="center">' . $estado . '</td>';

        if ($estado == 'OK') {
            $html .= '<td bgcolor="#02420C" class="conBorde"></td>';
        } else {
            $modelJusti = $this->consulta_justificacion($clase, $fecha, $horaId);
            if (count($modelJusti) > 0) {
                $html .= '<td class="conBorde" align="center">';
                $html .= 'Justificado';
                $html .= '</td>';
            } else {
                $html .= '<td class="conBorde" align="center">Sin Justificar</td>';
            }
//            $html .= '<td class="conBorde">';
//            $html .= 'Sin Justificar';
//            $html .= '</td>';
        }
        return $html;
    }

    private function consulta_justificacion($clase, $fechaRegistro, $horaRegistro) {

        $modelClase = \backend\models\ScholarisClase::findOne($clase);

        $model = \backend\models\ScholarisAsistenciaJustificacionProfesor::find()
                ->where([
                    'codigo_persona' => $modelClase->idprofesor,
                    'fecha_registro' => $fechaRegistro,
                    'hora_registro' => $horaRegistro
                ])
                ->all();
        return $model;
    }

}
