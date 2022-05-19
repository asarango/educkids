<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use DateTime;
use backend\models\SentenciasMatriculas;
use backend\models\ResPartner;
use backend\models\OpStudentInscription;
use backend\models\OpStudent;
use backend\models\OpCourseParalelo;
use backend\models\OpStudentEnrollment;
use backend\models\ScholarisPeriodo;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class ScholarisCerFichaMatriculaSinFirmasController extends Controller {

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
                        "op_student_inscription.id as ins_id",
                        'op_student.x_representante',
                        'op_student.emergency_contact'
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
                        'op_student.x_representante',
                        'op_student.emergency_contact'
                    ])
                    ->innerJoin("op_student_inscription", "op_student.id = op_student_inscription.student_id")
                    ->where(['op_student_inscription.parallel_id' => $paralelo, 'op_student_inscription.inscription_state' => 'M'])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name, ")
                    ->all();
        }



        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 25,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);



        $cabecera = $this->genera_logo();
        $pie = $this->genera_pie_pdf();


        //$mpdf->SetHeader($cabecera);
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;


        foreach ($modelAlumno as $data) {

            $html = $this->genera_cuerpo_pdf($data, $paralelo);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->SetFooter($pie);
            $mpdf->addPage();
        }



        $mpdf->Output('Ficha_Matricula' . '.pdf', 'D');
        exit;
    }

    private function genera_logo() {
        $cab = '<img src="imagenes/instituto/logo/logo_bi.jpg">';

        return $cab;
    }

    private function genera_cabecera_pdf($paralelo) {
        
        $imprimeNombreInstituto = 1;
        $modelImprimeNombre = \backend\models\ScholarisParametrosOpciones::find()->where([
                    'codigo' => 'imprimenombre'
                ])->one();

        if (isset($modelImprimeNombre)) {
            $imprimeNombreInstituto = $modelImprimeNombre->valor;
        }

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();

        $cab = '';
        if ($imprimeNombreInstituto == 1) {
            $cab .= '<p class="centrarTexto">' . $modelParalelo->course->xInstitute->name . '<br><br>';
            $cab .= $modelParalelo->course->xInstitute->direccion . ' Teléfono: ' . $modelParalelo->course->xInstitute->telefono . '</p>';
        }else{
            $cab .= '<br>';
            $cab .= '<br>';
            $cab .= '<br>';
        }
        
        
        
        
        $cab .= '<p class="centrarTexto">FICHA DE IDENTIFICACIÓN Y MATRÍCULA<br>';
        $cab .= 'AÑO LECTIVO: ' . $modelPeriodo->nombre . '</p>';

        return $cab;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = ' ';
        // $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($alumno, $paralelo) {



        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $datoMatricula = $this->toma_fecha_matricula($alumno, $paralelo);
        $fechaMatricula = $this->obtenerFechaEnLetra($datoMatricula['create_date']);
        $fechaHoy = date("Y-m-d");
        $hoy = $this->obtenerFechaEnLetra($fechaHoy);


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
                  
                  .tamano10{
                    font-size: 10px;
                  }
                  
                  .conBorde {
                    border: 0.3px solid black;
                  }
                 ';
        $html .= '</style>';

        $html .= $this->genera_cabecera_pdf($paralelo);

        $html .= '<br>';
        $html .= '<p class="centrarTexto"><strong>' . $alumno->last_name . '<br>';

        $html .= 'Matrícula N° ' . $datoMatricula['name'] . '</strong></p>';


        $html .= $this->literal_a($alumno, $paralelo);

        $html .= $this->literal_b($alumno, $paralelo);

        $html .= $this->literal_c($alumno, $paralelo);


        return $html;
    }

    private function literal_a($alumno, $paralelo) {

        $sentencias = new SentenciasMatriculas();

        $datos = $sentencias->tomaLiteralA($paralelo, $alumno->id);

        $html = '';
        $html .= '<p class=""><strong>A. INFORMACIÓN PERSONAL DE LA ESTUDIANTE: </strong></p>';
        $html .= '<table width="70%">';

        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>C.I.:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['numero_identificacion'] . '</td>';
        $html .= '<td class="tamano10"><strong>Nacionalidad</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['nacionalidad'] . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>Código:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['codigo'] . '</td>';
        $html .= '<td class="tamano10"><strong>Fecha Nacimiento:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['birth_date'] . '</td>';
        $html .= '</tr>';

        $edad = $this->obtenerEdad($datos[0]['birth_date']);

        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>Curso:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['curso'] . ' ' . $datos[0]['paralelo'] . '</td>';
        $html .= '<td class="tamano10"><strong>Edad:</strong></td>';
        $html .= '<td class="tamano10">' . $edad . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>Fecha Matrícula:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['create_date'] . '</td>';
        $html .= '<td class="tamano10"><strong>Domicilio:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['x_main_street'] . ' ' . $datos[0]['x_home_number'] . ' ' . $datos[0]['x_second_street'] . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="tamano10"><strong>Correo:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['email'] . '</td>';
        $html .= '<td class="tamano10"><strong>Teléfono:</strong></td>';
        $html .= '<td class="tamano10">' . $datos[0]['phone'] . '</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '<hr>';

        return $html;
    }

    private function literal_b($alumno, $paralelo) {

        $sentencias = new SentenciasMatriculas();
        $datosPadre = $sentencias->tomaLiteralB($alumno->id, 'padre');
        $datosMadre = $sentencias->tomaLiteralB($alumno->id, 'madre');


        if (!$datosPadre) {
            $datosPadre[0]['nombre'] = '';
            $datosPadre[0]['numero_identificacion'] = '';
            $datosPadre[0]['nacionalidad'] = '';
            $datosPadre[0]['street'] = '';
            $datosPadre[0]['phone'] = '';
            $datosPadre[0]['mobile'] = '';
            $datosPadre[0]['profesion'] = '';
            $datosPadre[0]['acupacion'] = '';
            $datosPadre[0]['email'] = '';
        }

        if (!$datosMadre) {
            $datosMadre[0]['nombre'] = '';
            $datosMadre[0]['numero_identificacion'] = '';
            $datosMadre[0]['nacionalidad'] = '';
            $datosMadre[0]['street'] = '';
            $datosMadre[0]['phone'] = '';
            $datosMadre[0]['mobile'] = '';
            $datosMadre[0]['profesion'] = '';
            $datosMadre[0]['acupacion'] = '';
            $datosMadre[0]['email'] = '';
        }


        $html = '';
        $html .= '<p class=""><strong>B. INFORMACIÓN PADRES DE FAMILIA: </strong></p>';
        $html .= '<table cellpadding="0" cellspacing="0" width="100%">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Información</strong></td>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Padre</strong></td>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Madre</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Apellidos y nombres:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['nombre'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['nombre'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>CI:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['numero_identificacion'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['numero_identificacion'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Nacionalidad:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['nacionalidad'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['nacionalidad'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Dirección:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['street'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['street'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Teléfono:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['phone'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['phone'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Celular:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['mobile'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['mobile'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Profesión:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['profesion'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['profesion'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Ocupación:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['acupacion'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['acupacion'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto tamano10"><strong>Correo:</strong></td>';
        $html .= ($datosPadre[0]['x_state'] = 'padre') ? '<td class="conBorde centrarTexto tamano10">' . $datosPadre[0]['email'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= ($datosMadre[0]['x_state'] = 'madre') ? '<td class="conBorde centrarTexto tamano10">' . $datosMadre[0]['email'] . '</td>' : '<td class="conBorde centrarTexto tamano10">-</td>';
        $html .= '</tr>';

        $html .= '</table>';
        $html .= '<hr>';

        return $html;
    }

    private function literal_c($alumno, $paralelo) {
        
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        $modelInst = \backend\models\OpInstitute::findOne($instituto);
        
        $modelParalelo = OpCourseParalelo::findOne($paralelo);

        $representante = ResPartner::find()
                ->where(['id' => $alumno->emergency_contact])
                ->one();

        $html = '';
        $html .= '<p class=""><strong>C. INFORMACIÓN DEL REPRESENTANTE: </strong></p>';
        $html .= '<table>';

        if (isset($representante)) {
            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Representante:</strong></td>';


            $html .= '<td class="tamano10">' . $representante->name . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>CI:</strong></td>';
            $html .= '<td class="tamano10">' . $representante->numero_identificacion . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Dirección:</strong></td>';
            $html .= '<td class="tamano10">' . $representante->street . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Teléfono:</strong></td>';
            $html .= '<td class="tamano10">' . $representante->phone . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Celular:</strong></td>';
            $html .= '<td class="tamano10">' . $representante->mobile . '</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Correo:</strong></td>';
            $html .= '<td class="tamano10">' . $representante->email . '</td>';
            $html .= '</tr>';
        } else {
            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Representante:</strong></td>';


            $html .= '<td class="tamano10"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>CI:</strong></td>';
            $html .= '<td class="tamano10"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Dirección:</strong></td>';
            $html .= '<td class="tamano10"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Teléfono:</strong></td>';
            $html .= '<td class="tamano10"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Celular:</strong></td>';
            $html .= '<td class="tamano10"></td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td class="tamano10"><strong>Correo:</strong></td>';
            $html .= '<td class="tamano10"></td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        
        
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        
        
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
            'template_id' => $modelParalelo->course->x_template_id
        ])->one();


        $html .= '<table width="100%" border="0">';
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>_______________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td align="center" class="tamano10"><strong>'.$modelFirmas->secretaria_nombre.'</strong></td>';
        $html .= '<td></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td align="center" class="tamano10"><strong>'.$modelFirmas->secretaria_cargo.'</strong></td>';
        $html .= '<td></td>';
        $html .= '</tr>';

        $html .= '</table>';
        
        
        
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
        return $num . ' de ' . $mes . ' del ' . $anno;
    }

    private function obtenerEdad($fechaNacimiento) {
        $cumpleanos = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $annos = $hoy->diff($cumpleanos);
        return $annos->y;
    }

}
