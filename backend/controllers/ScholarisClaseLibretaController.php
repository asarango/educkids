<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisClaseLibreta;
use backend\models\ScholarisClaseLibretaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;
use miloschuman\highcharts\Highcharts;
use fruppel\googlecharts\GoogleCharts;

/**
 * ScholarisClaseLibretaController implements the CRUD actions for ScholarisClaseLibreta model.
 */
class ScholarisClaseLibretaController extends Controller {
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

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $sentencias->asignarLibretas();
        
//        $this->calcular_notas();


        $searchModel = new ScholarisClaseLibretaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function calcular_notas(){
        $periodo = \Yii::$app->user->identity->periodo_id;
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        $sentencias = new \backend\models\SentenciasRecalcularUltima();
                
        $grupos = $this->toma_grupos($instituto, $modelPeriodo->codigo);
        
        foreach ($grupos as $g){
            $sentencias->sentar_notas_parciales($g['alumno_id'], $g['clase_id'], $g['grupo_id']);
        }
        
    }
    
    private function toma_grupos($instituto, $periodoCodigo){
        $con = \Yii::$app->db;
        $query = "select 	g.id as grupo_id
		,g.estudiante_id as alumno_id
		,c.id as clase_id
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join op_course cur on cur.id = c.idcurso
                    where	c.periodo_scholaris = '$periodoCodigo'
                                    and cur.x_institute = $instituto;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Displays a single ScholarisClaseLibreta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisClaseLibreta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisClaseLibreta();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisClaseLibreta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisClaseLibreta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisClaseLibreta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisClaseLibreta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisClaseLibreta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionActualizar() {
        $sentencias = new \backend\models\SentenciasSoporte();

        $sentencias->arreglaNotasTodas();
    }
    

    public function actionParalelo($curso, $paralelo) {


        $sentencia = new \backend\models\SentenciasClaseLibreta();
        $sentenciasR = new \backend\models\SentenciasRecalcularUltima();
        $sentenciasR->por_paralelo($paralelo);

        $modelMallaCurso = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $curso])->one();
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student_inscription.parallel_id' => $paralelo])
                ->orderBy("op_student.last_name, op_student.first_name")
                ->all();

        $modelMaterias = $sentencia->get_materias($modelMallaCurso->malla_id);

        return $this->render('promedios', [
                    'modelAlumno' => $modelAlumno,
                    'modelMallaCurso' => $modelMallaCurso,
                    'modelParalelo' => $modelParalelo,
                    'modelMaterias' => $modelMaterias,
        ]);
    }

    public function actionExcel($curso, $paralelo) {

        header('Content-type: application/excel');
        $filename = 'promedios_finales.xls';
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

        $data .= $this->genera_excel_cabecera($paralelo);
        $data .= $this->genera_excel_cuerpo($curso, $paralelo);


        $data .= "</body>";
        $data .= "</html>";
        echo $data;
        /* fin de excel */
    }

    private function genera_excel_cabecera($paralelo) {

        $modelInstituto = \backend\models\OpInstitute::find()->one();
//                       
        $model = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();


        $data = '<table border="1">';
        $data .= "<tr>";
        $data .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $data .= '<td>' . $modelInstituto->name . '</td>';
        $data .= '<td>' . $model->course->name . ' ' . $model->name . '</td>';
        $data .= "</tr>";
        $data .= "<tr>";
        $data .= "<td></td>";
        $data .= "<td>Registro de promedio de notas de mayor a menor</td>";
        $data .= "<td>PROMEDIOS FINALES QUIMESTRE I</td>";
        $data .= "</tr>";
        $data .= "</table>";


        return $data;
    }

    private function genera_excel_cuerpo($curso, $paralelo) {
        $sentencia = new \backend\models\SentenciasClaseLibreta();

        $modelMallaCurso = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $curso])->one();
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where(['op_student_inscription.parallel_id' => $paralelo])
                ->orderBy("op_student.last_name, op_student.first_name")
                ->all();

        $modelMaterias = $sentencia->get_materias($modelMallaCurso->malla_id);


        $data = "<table>";
        $data .= "<tr>";
        $data .= '<td>#</td>';
        $data .= '<td>Estudiante</td>';
        foreach ($modelMaterias as $materia) {
            if ($materia['promedia'] == true) {
                $data .= '<td colspan="1">' . $materia['abreviarura'] . '</td>';
            } else {
                $data .= '<td colspan="1"> * ' . $materia['abreviarura'] . '</td>';
            }
        }
        $data .= '<td colspan="1">PROMEDIO</td>';
        $data .= "</tr>";


        $i = 0;
        foreach ($modelAlumno as $alumno) {
            $i++;
            $data .= '<tr>';
            $data .= '<td>' . $i . '</td>';
            $data .= '<td>' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

            foreach ($modelMaterias as $mat) {
                $notas = $sentencia->get_notas_finales_normales($mat['id'], $alumno->id);

                $data .= '<td>' . $notas['q1'] . '</td>';
//                $data .= '<td>' . $notas['q2'] . '</td>';
//                $data .= '<td bgcolor="#CCCCCC">' . $notas['final_ano_normal'] . '</td>';
            }

            $promedios = $sentencia->get_promedios_normales($alumno->id);

            $data .= '<td>' . $promedios['q1'] . '</td>';
//            echo '<td>' . $promedios['q2'] . '</td>';
//            echo '<td bgcolor="#CCCCCC">' . $promedios['final_ano_normal'] . '</td>';


            $data .= '</tr>';
        }


        $data .= "</table>";

        $data .= "<table>";
        $data .= "<tr>";
        $data .= "<td>_____________________</td>";
        $data .= "<td></td>";
        $data .= "<td>_____________________</td>";
        $data .= "</tr>";
        $data .= "<tr>";
        $data .= "<td>TUTOR(A)</td>";
        $data .= "<td></td>";
        $data .= "<td>SECRETARIA</td>";
        $data .= "</tr>";
        $data .= "</table>";
        return $data;
    }

    public function actionExcelmayoramenor($curso, $paralelo) {

        header('Content-type: application/excel');
        $filename = 'promedios_finales.xls';
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

        $data .= $this->genera_excel_cabecera($paralelo);
        $data .= $this->genera_excel_cuerpoMayor($curso, $paralelo);


        $data .= "</body>";
        $data .= "</html>";
        echo $data;
        /* fin de excel */
    }

    private function genera_excel_cuerpoMayor($curso, $paralelo) {
        $sentencia = new \backend\models\SentenciasClaseLibreta();

        $modelDetalle = $sentencia->get_finales_mayor_a_menor($paralelo);

//        print_r($modelDetalle);
//        die();


        $data = "<table>";
        $data .= "<tr>";
        $data .= '<td>#</td>';
        $data .= '<td>Estudiante</td>';
        $data .= '<td colspan="1">PROMEDIO</td>';
        $data .= "</tr>";


        $i = 0;
        foreach ($modelDetalle as $alumno) {
            $i++;
            $data .= '<tr>';
            $data .= '<td>' . $i . '</td>';
            $data .= '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';

            $data .= '<td>' . $alumno['q1'] . '</td>';

            $data .= '</tr>';
        }


        $data .= "</table>";

        $data .= "<table>";
        $data .= "<tr>";
        $data .= "<td>_____________________</td>";
        $data .= "<td></td>";
        $data .= "<td>_____________________</td>";
        $data .= "</tr>";
        $data .= "<tr>";
        $data .= "<td>TUTOR(A)</td>";
        $data .= "<td></td>";
        $data .= "<td>SECRETARIA</td>";
        $data .= "</tr>";
        $data .= "</table>";
        return $data;
    }

    /*     * *
     * PARA PDF
     */

    public function actionPdf() {
        $curso = $_GET['curso'];
        $paralelo = $_GET['paralelo'];
        $quimestre = $_GET['qui'];

        if ($quimestre == 'Q1') {
            $campo = 'q1';
            $format = 'A4-P';
            $quimesteNom = 'QUIMESTRE I';
        } elseif ($quimestre == 'Q2') {
            $campo = 'q2';
            $format = 'A4-P';
            $quimesteNom = 'QUIMESTRE II';
        } else {
            $campo = 'final_ano_normal';
            $format = 'A4-L';
            $quimesteNom = 'NOTAS FINALES';
        }

        $cabecera = $this->genera_cabecera($paralelo, $quimesteNom);
        $html = $this->genera_html($curso, $paralelo, $campo,$quimestre);
        $pie = $this->genera_pie();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => $format,
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
//        $mpdf->WriteHTML('ola k ase', $this->renderPartial('mpdf'));
        $mpdf->SetFooter($pie);

        $mpdf->Output('Reportes_finales.pdf', 'D');
        exit;
    }

    private function genera_cabecera($paralelo, $quimestreNom) {

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $modelInstituto = \backend\models\OpInstitute::find()->one();


        $cab = '<table width="100%">';
        $cab .= '<tr>';
        $cab .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $cab .= '<td><center>';
        $cab .= '<p>' . $modelInstituto->name . '</p>';
        $cab .= '<p style="font-size:10px">PROMEDIOS QUIMESTRALES</p>';

        $cab .= '</center>';
        $cab .= '</td>';
        $cab .= '<td align="right"><p style="font-size:10px">' . $modelParalelo->course->name . ' ' . $modelParalelo->name . '</p>';
        $cab .= '<p style="font-size:10px">'.$quimestreNom.'</p>';
        $cab .= '</td>';
        $cab .= '</tr>';
        $cab .= '</table>';

        return $cab;
    }

    private function genera_pie() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_html($curso, $paralelo, $campo, $quimestre) {

        $parametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();
        $minimo = $parametros->valor;

        $sentencia = new \backend\models\SentenciasClaseLibreta();
        $sentencia2 = new \backend\models\Notas();

        $modelMallaCurso = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $curso])->one();
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelAlumno = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where([
                            'op_student_inscription.parallel_id' => $paralelo,
                            'op_student_inscription.inscription_state' => 'M'
                        ])
                ->orderBy("op_student.last_name, op_student.first_name")
                ->all();

        $modelMaterias = $sentencia->get_materias($modelMallaCurso->malla_id);

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                ';
        $html .= '</style>';

        $html .= '<p class="centrarTexto">DETALLE DE NOTAS</p>';

        $html .= '<table width="100%" cellspacing="0" style="font-size:10px">';
        $html .= "<tr>";
        $html .= '<td class="conBorde" align="center">#</td>';
        $html .= '<td class="conBorde" align="center">Estudiante</td>';

        foreach ($modelMaterias as $materia) {
            if ($materia['promedia'] == true) {
                $html .= '<td colspan="1" class="conBorde" align="center">' . $materia['abreviarura'] . '</td>';                
            } else {
                $html .= '<td colspan="1" class="conBorde" align="center"> * ' . $materia['abreviarura'] . '</td>';
            }
        }
        $html .= '<td colspan="1" class="conBorde" align="center">PROMEDIO</td>';
        $html .= '<td colspan="1" class="conBorde" align="center">DISCI</td>';
        
        if($quimestre == 'FI'){
            $html .= '<td colspan="1" class="conBorde" align="center">OBSERVACIÓN</td>';
        }
        
        $html .= "</tr>";


        $i = 0;
        foreach ($modelAlumno as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

            foreach ($modelMaterias as $mat) {
                $notas = $sentencia->get_notas_finales_normales($mat['id'], $alumno->id);
                $html .= $notas[$campo] < $minimo ? '<td class="conBorde centrarTexto" bgcolor="#FF0000">' . $notas[$campo] . '</td>' : '<td class="conBorde centrarTexto">' . $notas[$campo] . '</td>';
            }

            $promedios = $sentencia->get_promedios_normales($alumno->id);
            $html .= $promedios[$campo] < $minimo ? '<td class="conBorde centrarTexto" bgcolor="#FF0000">' . $promedios[$campo] . '</td>' : '<td class="conBorde centrarTexto"><strong>' . $promedios[$campo] . '</strong></td>';

            
            $notaComportamiento = $sentencia->get_comportamiento_finales($alumno->id, $paralelo);
                    
            
            $modelClase = \backend\models\ScholarisClase::findOne($notaComportamiento['id']);
            
            $notaHomo = $sentencia2->homologa_comportamiento($notaComportamiento['p6'], $modelClase->curso->section0->code);
            
            $html .= '<td class="conBorde centrarTexto">'.$notaHomo.'</td>';
            
            $obs = $this->get_observacion_final_normal($alumno->id, $modelMaterias, $paralelo);
            
            $html .= '<td class="conBorde centrarTexto">'.$obs.'</td>';
            
            $html .= '</tr>';
        }
        $html .= '</table>';



        $html .= '<br><br><br>';
        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td align="center">______________________________</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">______________________________</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center">TUTOR(A)</td>';
        $html .= '<td></td>';
        $html .= '<td align="center">SECRETARIA</td>';
        $html .= '</tr>';
        $html .= '</table>';





        return $html;
    }
    
    
    private function get_observacion_final_normal($alumno, $modelMaterias, $paralelo){
        
        $sentencias = new \backend\models\SentenciasClaseLibreta();
        
        $parametrosMinima = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();
        $minimo = $parametrosMinima->valor;
        
        $parametrosRemedial = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaRemed'])
                ->one();
        $remedial = $parametrosRemedial->valor;
        
        $totales = $sentencias->get_total_supletorios($alumno, $minimo, $remedial);        
        
        
        if($totales['supletorio'] > 0 && $totales['remedial'] == 0){
            $observacion = 'SUPLETORIO';
        }elseif($totales['supletorio'] > 0 && $totales['remedial'] > 0){
            $observacion = 'SUPLETORIO Y REMEDIAL';
        }elseif($totales['supletorio'] == 0 && $totales['remedial'] > 0){
            $observacion = 'REMEDIAL';
        }else{
            $observacion = '-';
        }
        
        
//        return $totales['supletorio'].' '.$totales['remedial'] ;
        return $observacion;
    }

    //FIN DE PDF     
}
