<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisRepLibreta;
use backend\models\ScholarisRepLibretaSearch;
use backend\models\SentenciasNotas;
use backend\models\ScholarisPeriodo;
use backend\models\OpCourseParalelo;
use backend\models\OpInstitute;
use backend\models\OpStudentInscription;
use backend\models\ScholarisCursoImprimeLibreta;
use backend\models\ScholarisBloqueActividad;
use backend\models\SentenciasRepLibreta;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class ScholarisRepLibretaController extends Controller {

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

        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $sentencias = new SentenciasNotas();
        $sentencias->eliminaNotasLibreta($paralelo, $usuario);
        $sentencias->insertaNotasLibreta($paralelo, $usuario, $alumno, $modelPeriodo->codigo);


        $this->calcula_notas_promedios($paralelo, $usuario);

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $searchModel = new ScholarisRepLibretaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $usuario, $paralelo);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelParalelo' => $modelParalelo,
                    'alumno' => $alumno,
        ]);
    }

    public function calcula_notas_promedios($paralelo, $usuario) {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();

        $sentencias = new SentenciasNotas();
        $uso = $sentencias->tomaUso($paralelo, $usuario);


        $model = ScholarisRepLibreta::find()
                ->where(['usuario' => $usuario, 'paralelo_id' => $paralelo])
                ->orderBy('alumno_id')
                ->all();

        foreach ($model as $data) {

            $nota1 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 1, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $nota2 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 2, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $nota3 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 3, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $exam1 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 4, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $nota4 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 5, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $nota5 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 6, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $nota6 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 7, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);
            $exam2 = $sentencias->nota_parcial($data->clase_id, $data->alumno_id, 8, $modelPeriodo->codigo, $uso['tipo_uso_bloque']);

            $data->p1 = $nota1['calificacion'];
            $data->p2 = $nota2['calificacion'];
            $data->p3 = $nota3['calificacion'];
            $data->ex1 = $exam1['calificacion'];
            $data->p4 = $nota4['calificacion'];
            $data->p5 = $nota5['calificacion'];
            $data->p6 = $nota6['calificacion'];
            $data->ex2 = $exam2['calificacion'];
            $data->save();
        }


        $sentencias->calcula_promedios($paralelo, $usuario);
        $sentencias->calcula_promedios_finales($paralelo, $usuario);
    }

    public function actionPdf() {

        $paralelo = $_GET['paralelo'];


        if ($_GET['alumno']) {
            $alumno = OpStudentInscription::find()->where(['parallel_id' => $paralelo, 'student_id' => $_GET['alumno']])->all();
        } else {
            $alumno = OpStudentInscription::find()->where(['parallel_id' => $paralelo])->all();
        }

        
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo, $alumno);
        $pie = $this->genera_pie_pdf();


        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;


        foreach ($alumno as $data) {
            
            $html = $this->genera_cuerpo_pdf($data, $paralelo);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }

        

//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Litrerta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo, $alumno) {
        $usuario = \Yii::$app->user->identity->usuario;
        $periodo = \Yii::$app->user->identity->periodo_id;
        $instituto = \Yii::$app->user->identity->instituto_defecto;

        $modelInstituto = OpInstitute::find()->where(['id' => $instituto])->one();
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodo])->one();

        $modelParalelo = OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $cab = '<table width="100%">';
        $cab .= '<tr>';
        $cab .= '<td width="30%"><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';

        $cab .= '<td><center>';
        $cab .= '<p>' . $modelInstituto->name . '</p>';
        $cab .= '<p style="font-size:10px">Cuadro de notas anual</p>';
        $cab .= '</center>';

        $cab .= '<td width="30%" style="text-align: right;">';
        $cab .= '<p style="font-size:8px">' . $modelParalelo->course->name . ' - ' . $modelParalelo->name . '</p>';
        $cab .= '<p style="font-size:7px">Año lectivo: ' . $modelPeriodo->nombre . '</p>';

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
        
//        print_r($alumno);
//        print_r($paralelo);
//        die();
        
        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano6{
                    font-size: 6px;
                  }
                    ';
        $html .= '</style>';

        $html .= '<p style="font-size:7px"><strong>ESTUDIANTE: </strong> ' . $alumno->student->last_name . ' ' . $alumno->student->first_name . ' ' . $alumno->student->middle_name . '  </p>';




        $html .= '<table cellspacing="0" width="60%">';
        $html .= '<tr>';
        $html .= '<td class="conBorde" style="font-size:8px>">ASIGNATURAS</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">P1</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">P2</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">P3</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">PR1</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">E1</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">80%</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">20%</td>';
        $html .= '<td class="conBorde" style="font-size:8px>" bgcolor="#EBF5FB">Q1</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">P4</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">P5</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">P6</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">PR2</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">E2</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">80%</td>';
        $html .= '<td class="conBorde" style="font-size:8px>">20%</td>';
        $html .= '<td class="conBorde" style="font-size:8px>" bgcolor="#EBF5FB">Q2</td>';
        $html .= '<td class="conBorde" style="font-size:8px>" bgcolor="#E7FAB9">FINAL</td>';
        $html .= '</tr>';


        $html .= $this->materias($alumno, $paralelo);

        $html .= '</table>';


        return $html;
    }

    private function materias($alumno, $paralelo) {

        $usuario = Yii::$app->user->identity->usuario;


        $modelParalelo = OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelPromedia = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $modelParalelo->course_id])->one();


        if ($modelPromedia->imprime == 'NO') {
            $modelMateriasPromedia = ScholarisRepLibreta::find()
                    ->where(['usuario' => $usuario,
                        'paralelo_id' => $paralelo,
                        'alumno_id' => $alumno->student_id,
                        'tipo_calificacion' => 'Cuantitativo'
                    ])
                    ->orderBy(['promedia' => SORT_DESC,
                        'asignatura_id' => SORT_ASC
                    ])
                    ->all();


            $modelMateriasNoPromedia = ScholarisRepLibreta::find()
                    ->where(['usuario' => $usuario,
                        'paralelo_id' => $paralelo,
                        'alumno_id' => $alumno->student_id,
                        'tipo_calificacion' => 'Cualitativo'
                    ])
                    ->andWhere(["<>","asignatura",'Comportamiento'])
                    ->orderBy(['promedia' => SORT_DESC,
                        'asignatura_id' => SORT_ASC
                    ])
                    ->all();
            
            $modelMateriasComportamiento = ScholarisRepLibreta::find()
                    ->where(['usuario' => $usuario,
                        'paralelo_id' => $paralelo,
                        'alumno_id' => $alumno->student_id,
                        'tipo_calificacion' => 'Cualitativo',
                        'asignatura' => 'Comportamiento'
                    ])
                    ->orderBy(['promedia' => SORT_DESC,
                        'asignatura_id' => SORT_ASC
                    ])
                    ->all();
        } else {
            
        }


        $html = '';

        $html .= $this->detalle_materias($modelMateriasPromedia, 1);
        $html .= $this->promedios_libreta($alumno);
        $html .= $this->detalle_materias($modelMateriasNoPromedia, 0);
        $html .= $this->detalle_comportamiento($alumno, $modelMateriasComportamiento);




        return $html;
    }

    /**
     * DEVUELVE EL DETALLE DE LAS MATERIAS
     * @param type $materias
     * @param type $cuantitativa : Si es 1 son cuantitativas, si es 0 son cualitativas
     * @return string
     */
    private function detalle_materias($materias, $cuantitativa) {
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        
        $modelPeriodo = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();
        
        $sentencias = new SentenciasRepLibreta();
        
        $html = '';
        if ($cuantitativa == 1) {
            foreach ($materias as $data) {
                $html .= '<tr>';
                $html .= '<td class="conBorde" style="font-size:8px>">' . $data->asignatura . '</td>';

                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->p1 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->p2 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->p3 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->pr1 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->ex1 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->pr180 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->ex120 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>" bgcolor="#EBF5FB">' . $data->q1 . '</td>';

                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->p4 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->p5 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->p6 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->pr2 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->ex2 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->pr280 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $data->ex220 . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>" bgcolor="#EBF5FB">' . $data->q2 . '</td>';

                $html .= '<td class="conBorde" style="font-size:6px>" bgcolor="#E7FAB9">' . $data->nota_final . '</td>';

                $html .= '</tr>';
            }
        }else{
            foreach ($materias as $data) {
                $html .= '<tr>';
                $html .= '<td class="conBorde" style="font-size:8px>">' . $data->asignatura . '</td>';
                
                $p1 = $sentencias->homologa_proyectos($data->p1, $modelPeriodo->codigo);
                $p2 = $sentencias->homologa_proyectos($data->p2, $modelPeriodo->codigo);
                $p3 = $sentencias->homologa_proyectos($data->p3, $modelPeriodo->codigo);
                $pr1 = $sentencias->homologa_proyectos($data->pr1, $modelPeriodo->codigo);
                $ex1 = $sentencias->homologa_proyectos($data->ex1, $modelPeriodo->codigo);
                $pr180 = $sentencias->homologa_proyectos($data->pr180, $modelPeriodo->codigo);
                $ex120 = $sentencias->homologa_proyectos($data->ex120, $modelPeriodo->codigo);
                $q1 = $sentencias->homologa_proyectos($data->q1, $modelPeriodo->codigo);
                
                $p4 = $sentencias->homologa_proyectos($data->p4, $modelPeriodo->codigo);
                $p5 = $sentencias->homologa_proyectos($data->p5, $modelPeriodo->codigo);
                $p6 = $sentencias->homologa_proyectos($data->p6, $modelPeriodo->codigo);
                $pr2 = $sentencias->homologa_proyectos($data->pr2, $modelPeriodo->codigo);
                $ex2 = $sentencias->homologa_proyectos($data->ex2, $modelPeriodo->codigo);
                $pr280 = $sentencias->homologa_proyectos($data->pr280, $modelPeriodo->codigo);
                $ex220 = $sentencias->homologa_proyectos($data->ex220, $modelPeriodo->codigo);
                $q2 = $sentencias->homologa_proyectos($data->q2, $modelPeriodo->codigo);
                
                $nf = $sentencias->homologa_proyectos($data->nota_final, $modelPeriodo->codigo);
                
                $html .= '<td class="conBorde" style="font-size:6px>">' . $p1['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $p2['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $p3['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $pr1['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $ex1['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $pr180['abreviatura'] . '</td>';                
                $html .= '<td class="conBorde" style="font-size:6px>">' . $ex120['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>" bgcolor="#EBF5FB">' . $q1['abreviatura'] . '</td>';

                $html .= '<td class="conBorde" style="font-size:6px>">' . $p4['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $p5['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $p6['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $pr2['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $ex2['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $pr280['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>">' . $ex220['abreviatura'] . '</td>';
                $html .= '<td class="conBorde" style="font-size:6px>" bgcolor="#EBF5FB">' . $q2['abreviatura'] . '</td>';

                $html .= '<td class="conBorde" style="font-size:6px>" bgcolor="#E7FAB9">' . $nf['abreviatura'] . '</td>';

                $html .= '</tr>';
            }
        }


        return $html;
    }

    private function promedios_libreta($alumno) {

        $usuario = \Yii::$app->user->identity->usuario;
        $sentencias = new SentenciasRepLibreta();

        $datos = $sentencias->calcula_promedios($alumno->student_id, $usuario);

        $html = '';
        $html .= '<tr>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6"><strong>PROMEDIOS:</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['p1'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['p2'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['p3'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['pr1'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['ex1'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['pr180'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['ex120'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['q1'] . '</strong></td>';

        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['p4'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['p5'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['p6'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['pr2'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['ex2'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['pr280'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['ex220'] . '</strong></td>';
        $html .= '<td bgcolor="#E5F2FA" class="conBorde tamano6 centrarTexto"><strong>' . $datos['q2'] . '</strong></td>';

        $html .= '<td bgcolor="#E7FAB9" class="conBorde tamano6 centrarTexto"><strong>' . $datos['nota_final'] . '</strong></td>';

        $html .= '</tr>';

        return $html;
    }
    
    private function detalle_comportamiento($alumno, $modelMateriasComportamiento){
                
        $html = '';
        
        $html .= '<tr>';
        $html .= '<td bgcolor="#CBCBC9" class="conBorde tamano6"><strong>Comportamiento:</strong></td>';
        //$html .= '<td bgcolor="#CBCBC9" class="conBorde tamano6 centrarTexto"><strong>'.$modelMateriasComportamiento->p1.'</strong></td>';
        $html .= '</tr>';
        
        return $html;
    }

}
