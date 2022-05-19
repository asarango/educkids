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
class QuitoScholarisCerMatricula3Controller extends Controller {

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
            'margin_top' => 45,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
            'default_font' => 'arial'
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
        
        $modelTituloUnoDistrito = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'tit1mec'])->one();
                
        isset($modelTituloUnoDistrito->valor) ? $titulo1 = $modelTituloUnoDistrito->valor : $titulo1 = 'Configurar en parámetros: parametro id = 10, codigo = tit1mec, Nombre=título uno de certificados mec, valor=nombre del distrito';
        
        $cab = '<table width="100%" border="">';
        $cab .= '<tr>';
        $cab .= '<td align="left"><img src="imagenes/instituto/mec/sellopromo1.png" width="200px"></td>';
        $cab .= '<td></td>';
        $cab .= '<td align="right" style="color: #00486f"><strong>'.$titulo1.'</strong></td>';
        $cab .= '</tr>';
        $cab .= '</table>';
                
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
        
        if($alumno->gender == 'f'){
            $gen = 'la';
        }else{
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
        $fechaHoy = date("Y-m-d");
        
        isset($datoMatricula['create_date']) ? $fechaMatricula = $datoMatricula['create_date'] : $fechaMatricula = $fechaHoy;
        
        $fechaMatricula = $this->obtenerFechaEnLetra($fechaMatricula);
        
        $hoy = $this->obtenerFechaEnLetra($fechaHoy);

        
        $modelInscripcion = OpStudentInscription::find()->where([
            'student_id' => $alumno->id,
            'parallel_id' => $paralelo
        ])->one();
        
        $modelMatricula = OpStudentEnrollment::find()->where(['inscription_id' => $modelInscripcion->id])->one();

        $html = '';
        $html .= '<style>';
        $html .= '.centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .Titulo1{
                    font-size: 22px;
                  }
                  
                  .tamano14{
                    font-size: 14px;
                  }
                  
                  .tamano12{
                    font-size: 12px;
                  }
                  
                  .tamano10{
                    font-size: 12px;
                  }
                  
                 ';
        $html .= '</style>';
        
        $html .= '<p class="centrarTexto Titulo1"><strong>'.$modelParalelo->course->xInstitute->name.'</strong></p>';
        
        $html .= '<p class="centrarTexto">';
        $html .= $modelParalelo->course->xInstitute->direccion.' Telf: '.$modelParalelo->course->xInstitute->celular.'<br>';              
        $html .= 'Email: '.$modelParalelo->course->xInstitute->email.'<br>';        
        $html .= 'CÓDIGO AMIE: '.$modelParalelo->course->xInstitute->codigo_amie;
        $html .= '</p>';

        $html .= '<p class="centrarTexto Titulo1"><strong>CERTIFICADO DE MATRÍCULA</strong></p>';
        
        
        $html .= '<p class="centrarTexto">';
        $html .= 'Previo cumplimiento de los requisitos legales establecidos en el Reglamento General de la Ley Orgánica de Educación Intercultural vigente, certifico que el/la estudiante:';
        $html .= '</p>';
        
        $html .= '<p class="centrarTexto Titulo1"><strong>'.$alumno->last_name.'</strong></p>';
        
        $html .= '<p class="centrarTexto">Ha sido matriculado en el <strong>'.$modelParalelo->course->xTemplate->name.', paralelo '.$modelParalelo->name.'</strong></p>';
        
        $html .= '<p class="centrarTexto">en el Año Lectivo '.$modelPeriodo->codigo.'</p>';
        $html .= '<p class="centrarTexto">Es todo cuanto puedo certificar en honor a la verdad, pudiendo el interesado hacer uso del presente documento '
                . 'en lo que estimare conveniente.</p>';
               
        
        $html .= '<p class="derechaTexto">'.$modelParalelo->course->xInstitute->store->x_direccion.', '. $fechaMatricula . '.</p>';

        
        $html .= '<p class="centrarTexto">Atentamente,</p>';
        
        $html .= '<br><br><br><br>';

        $template = $modelParalelo->course->xTemplate->id;
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
            'template_id' => $template,
            'instituto_id' => $instituto
        ])->one();
        
        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td width="20%"><strong></strong></td>';
        $html .= '<td align="center"><strong>_____________________________________</strong></td>';
        $html .= '<td width="20%"><strong></strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="20%" align="center"></td>';
        $html .= '<td align="center"><strong>'.$modelParalelo->institute->rector.'</strong></td>';
        $html .= '<td width="20%"></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="20%" align="center"></td>';
        $html .= '<td align="center"><strong>'.$modelFirmas->principal_cargo.'</strong></td>';
        $html .= '<td width="20%"></td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        
        $html .= '<br><br>';
        
        $html .= '<table width="100%" border="">';
        $html .= '<tr>';
        $html .= '<td align="left"></td>';
        $html .= '<td></td>';
        $html .= '<td align="right" style="color: #00486f"><img src="imagenes/instituto/mec/sellopromo2.png" width="200px"></td>';
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
        return $num . ' de ' . $mes . ' de ' . $anno;
    }

}
