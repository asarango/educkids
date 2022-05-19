<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use backend\models\OpStudentInscription;
use backend\models\OpStudent;
use backend\models\OpCourseParalelo;
use backend\models\OpStudentEnrollment;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class ScholarisCerMatriculaController extends Controller {

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
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function actionIndex() {

        $usuario = \Yii::$app->user->identity->usuario;
        $paralelo = $_GET['paralelo'];
        $alumno = $_GET['alumno'];

        if ($alumno) {
            $modelAlumno = OpStudent::find()
                    ->select(['op_student.id',
                        "concat(op_student.last_name, ' ',op_student.first_name, ' ',op_student.middle_name) as last_name",
                        "op_student_inscription.id as ins_id"
                    ])
                    ->innerJoin("op_student_inscription", "op_student.id = op_student_inscription.student_id")
                    ->where(['op_student.id' => $alumno, 'op_student_inscription.inscription_state' => 'M'])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name, ")
                    ->all();
        } else {
            $modelAlumno = OpStudent::find()
                    ->select(['op_student.id',
                        "concat(op_student.last_name, ' ',op_student.first_name, ' ',op_student.middle_name) as last_name",
                        "op_student_inscription.id as ins_id",
                        "op_student.gender as gender"
                    ])
                    ->innerJoin("op_student_inscription", "op_student.id = op_student_inscription.student_id")
                    ->where(['op_student_inscription.parallel_id' => $paralelo, 'op_student_inscription.inscription_state' => 'M'])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name, ")
                    ->all();
        }



        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 30,
            'margin_right' => 30,
            'margin_top' => 18,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf();
        $pie = $this->genera_pie_pdf();


        $mpdf->SetHtmlHeader($cabecera);
//        $mpdf->SetHeader($cabecera);

        $mpdf->showImageErrors = true;


        foreach ($modelAlumno as $data) {

            $html = $this->genera_cuerpo_pdf($data, $paralelo);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }

        $mpdf->SetFooter($pie);

        $mpdf->Output('Certificado_Matricula' . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf() {
        $cab = '<div align="left"><img src="imagenes/instituto/logo/logo_bi.jpg"></div>';
        return $cab;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($alumno, $paralelo) {

        $sentencias = new \backend\models\SentenciasMatriculas();

        $imprimeNombreInstituto = 1;
        $modelImprimeNombre = \backend\models\ScholarisParametrosOpciones::find()->where([
                    'codigo' => 'imprimenombre'
                ])->one();

        if (isset($modelImprimeNombre)) {
            $imprimeNombreInstituto = $modelImprimeNombre->valor;
        }

        if ($alumno->gender == 'f') {
            $gen = 'la';
        } else {
            $gen = 'el';
        }

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $instituto = \Yii::$app->user->identity->instituto_defecto;

        $ciudad = $sentencias->get_ciudad($instituto);

        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $datoMatricula = $this->toma_fecha_matricula($alumno, $paralelo);
        $fechaMatricula = $this->obtenerFechaEnLetra($datoMatricula['create_date']);
        $fechaHoy = date("Y-m-d");
        $hoy = $this->obtenerFechaEnLetra($fechaHoy);
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
            'template_id' => $modelParalelo->course->x_template_id
        ])->one();

        $html = '';
        $html .= '<style>';
        $html .= '.centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano14{
                    font-size: 14px;
                  }
                  
                  .tamano12{
                    font-size: 12px;
                  }
                  
                 ';
        $html .= '</style>';

        $html .= '<br><br><br>';
        $html .= '<p class="centrarTexto tamano12"><strong>SECRETARÍA GENERAL</strong></p>';
        $html .= '<p class="centrarTexto tamano12"></p>';
        $html .= '<p class="centrarTexto tamano12"></p>';
        $html .= '<p class="centrarTexto tamano12">CERTIFICADO DE MATRÍCULA</p>';
        $html .= '<p class="centrarTexto tamano12">' . $modelPeriodo->codigo . '</p>';
        if ($imprimeNombreInstituto == 1) {
            $html .= '<p class="centrarTexto">' . $modelParalelo->course->xInstitute->name . '</p>';
        }

        //$html .= '<p class="centrarTexto">ROSA DE JESÚS CORDERO</p>';
        $html .= '<br><br><br>';
        $html .= '<p class="centrarTexto tamano14"><strong>CERTIFICA:</strong></p>';
        $html .= '<br><br><br><br>';
        $html .= '<p>Que ' . $gen . ' estudiante <strong>' . $alumno->last_name . '</strong>, previo a los requisitos legales, se matriculó '
                . 'en el <strong>' . $modelParalelo->course->xTemplate->name . ' ' . $modelParalelo->name . '</strong>, el ' . $fechaMatricula . '.</p>';


        $html .= '<p>Así consta en el folio <strong>' . $datoMatricula['name'] . '</strong> del libro respectivo.</p>';

        $html .= '<br><br><br><br>';

        $html .= '<p class="derechaTexto"><strong>' . $ciudad . ', ' . $fechaMatricula . '</strong>.</p>';

        $html .= '<br><br><br><br>';

        $html .= '<p class="centrarTexto">____________________________<br>';
        if($modelFirmas->secretaria_cargo == 'Secretaria'){
         $html .= '<strong>'.$modelFirmas->secretaria_nombre.'</strong><br>';   
        }
        $html .= '<strong>Secretaria</strong></p>';


        return $html;
    }

    private function toma_fecha_matricula($alumno, $paralelo) {
        $modelAlumno = OpStudentInscription::find()
                ->where(['parallel_id' => $paralelo, 'student_id' => $alumno->id])
                ->one();

        $modelMatricula = OpStudentEnrollment::find()
                ->where(['inscription_id' => $modelAlumno->id])
                ->one();

        return $modelMatricula;
    }

    private function obtenerFechaEnLetra($fecha) {
        //$dia= conocerDiaSemanaFecha($fecha);
        $num = date("j", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($fecha)) * 1) - 1];
        //return $dia.', '.$num.' de '.$mes.' del '.$anno;
        return $num . ' de ' . $mes . ' de ' . $anno;
    }

}
