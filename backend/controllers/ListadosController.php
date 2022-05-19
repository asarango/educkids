<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;

/**
 * ScholarisClaseLibretaController implements the CRUD actions for ScholarisClaseLibreta model.
 */
class ListadosController extends Controller {
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
     * Lists all ScholarisClaseLibreta models.
     * @return mixed
     */
    public function actionIndex() {

//        print_r($_POST);
//        die();

        $paralelo = $_GET['paralelo'];
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelStudent = new \backend\models\OpStudent();
        $modelAlumnos = $modelStudent->toma_alumnos_paralelo($paralelo);

//        $modelAlumnos = \backend\models\OpStudent::find()
//                ->select([
//                    "op_student.id",
//                    "op_student.last_name",
//                    "op_student.first_name",
//                    "op_student.middle_name",
//                    "op_student_inscription.inscription_state as insc_estado",
//                ])
//                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
//                ->where(["op_student_inscription.parallel_id" => $paralelo])
//                ->orderBy('op_student.last_name', "op_student.first_name", "op_student.middle_name")               
//                ->all();


        return $this->render('index', [
//            'periodo' => $periodoId,
                    'modelParalelo' => $modelParalelo,
                    'modelAlumnos' => $modelAlumnos
        ]);
    }

    public function actionReporte() {
        $reporte = $_POST['repo'];
        $paralelo = $_POST['paralelo'];
        $orientacion = $_POST['orientacion'];
        $campos = '';
        $auxiliares = '';



        foreach ($_POST as $r => $valor) {

            if ($valor == 'on') {
                if ($r == 'firma' || $r == 'obs' || $r == 'obs1') {

                    $auxiliares .= $r . ',';
                } else {
                    $campos .= $r . ',';
                }
            }
        }

//        print_r($campos);
//        print_r($auxiliares);
//        die();

        if ($reporte == 'pdf') {
            return $this->redirect(['pdf', 'paralelo' => $paralelo, 'condicion' => $campos, 'orientacion' => $orientacion, 'auxiliares' => $auxiliares]);
        } else {
            return $this->redirect(['excel', 'paralelo' => $paralelo, 'condicion' => $campos, 'orientacion' => $orientacion, 'auxiliares' => $auxiliares]);
        }
    }

    public function actionPdf() {
        //print_r($_GET);

        $paralelo = $_GET['paralelo'];
        $campos = $_GET['condicion'];
        $orientacion = $_GET['orientacion'];
        $auxiliares = $_GET['auxiliares'];


        $cabecera = $this->genera_cabecera($paralelo);
        $html = $this->genera_html($paralelo, $campos, $auxiliares);
        $pie = $this->genera_pie();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => $orientacion,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
        $mpdf->SetFooter($pie);

        $mpdf->Output('Listados_Paralelo.pdf', 'D');
        exit;
    }

    private function genera_cabecera($paralelo) {
        
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $modelInstituto = \backend\models\OpInstitute::findOne($institutoId);


        $cab = '<table width="100%">';
        $cab .= '<tr>';
        $cab .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $cab .= '<td><center>';
        $cab .= '<p>' . $modelInstituto->name . '</p>';
        $cab .= '<p style="font-size:10px">NÓMINA DE ESTUDIANTES</p>';

        $cab .= '</center>';
        $cab .= '</td>';
        $cab .= '<td align="right"><p style="font-size:10px">' . $modelParalelo->course->name . ' ' . $modelParalelo->name . '</p>';
//        $cab .= '<p style="font-size:10px">' . $modelBloque->name . '</p>';
        $cab .= '</td>';
        $cab .= '</tr>';
        $cab .= '</table>';

//        $cab = '<div style="text-align: right; font-weight: bold;">
//    My document
//</div>';

        return $cab;
    }

    private function genera_pie() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_html($paralelo, $campos, $auxiliares) {

        $explo = explode(',', $campos);
        $auxil = explode(',', $auxiliares);


        $modelStudent = new \backend\models\OpStudent();
        $modelAlumnos = $modelStudent->toma_alumnos_paralelo($paralelo);

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
                  }';
        $html .= '</style>';

        $html .= '<table width="80%" cellspacing="0" style="font-size:12px" align="center">';
        $html .= '<tr>';
        $html .= '<td class="conBorde">#</td>';
        $html .= '<td class="conBorde">Estudiante</td>';
//        $html .= '<td class="conBorde"><center>Estado</center></td>';
        for ($i = 0; $i < count($explo) - 1; $i++) {

            $html .= '<td class="conBorde">' . strtoupper($explo[$i]) . '</td>';
        }

        if ($auxil) {
            for ($j = 0; $j < count($auxil) - 1; $j++) {
                $html .= '<td class="conBorde" align="center">' . strtoupper($auxil[$j]) . '</td>';
            }
        }

        $html .= '<tr>';

        $x = 0;
        foreach ($modelAlumnos as $alumno) {
            $x++;
            $html .= "<tr>";
            $html .= '<td class="conBorde">' . $x . '</td>';
            $html .= '<td class="conBorde">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . "</td>";

            for ($i = 0; $i < count($explo) - 1; $i++) {
//                $e1 = strtolower($e1);
                $html .= '<td class="conBorde">' . $alumno[$explo[$i]] . "</td>";
            }

            if ($auxil) {
                for ($j = 0; $j < count($auxil) - 1; $j++) {
                    $html .= '<td class="conBorde" width="200px"></td>';
                }
            }
            $html .= "</tr>";
        }
        $html .= '</table>';

        return $html;
    }

    public function actionExcel() {

        $paralelo = $_GET['paralelo'];
        $campos = $_GET['condicion'];
        $orientacion = $_GET['orientacion'];
        $auxiliares = $_GET['auxiliares'];

        header('Content-type: application/excel');
        $filename = 'Listado_paralelo.xls';
        header('Content-Disposition: attachment; filename=' . $filename);

        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                        <head>
                            <meta charset="utf-8">
                            <!--[if gte mso 9]>
                            <xml>
                                <x:ExcelWorkbook>
                                    <x:ExcelWorksheets>
                                        <x:ExcelWorksheet>
                                            <x:Name>Sheet 1</x:Name>
                                            <x:WorksheetOptions>
                                                <x:Print>
                                                    <x:ValidPrinterInfo/>
                                                </x:Print>
                                            </x:WorksheetOptions>
                                        </x:ExcelWorksheet>
                                    </x:ExcelWorksheets>
                                </x:ExcelWorkbook>
                            </xml>
                            <![endif]-->
                        </head>
                        <body>';

        $data .= $this->genera_cabecera($paralelo);
        $data .= $this->genera_html($paralelo, $campos, $auxiliares);


        $data .= "</body>";
        $data .= "</html>";
        echo $data;
        /* fin de excel */
    }

}
