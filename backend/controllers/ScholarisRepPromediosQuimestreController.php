<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisRepPromedios;
use backend\models\ScholarisRepPromediosSearch;
use backend\models\OpCourseParalelo;
use backend\models\ScholarisBloqueActividad;
use backend\models\OpInstitute;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepPromediosController implements the CRUD actions for ScholarisRepPromedios model.
 */
class ScholarisRepPromediosQuimestreController extends Controller {

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
     * Lists all ScholarisRepPromedios models.
     * @return mixed
     */
    public function actionIndex() {

        $paralelo = $_GET['paralelo'];
        //$bloque = $_GET['bloque'];
        $usuario = Yii::$app->user->identity->usuario;

        $searchModel = new ScholarisRepPromediosSearch();
        $dataProvider = $searchModel->searchTodos(Yii::$app->request->queryParams, $paralelo, $usuario);        

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'paralelo' => $paralelo,
                    'usuario' => $usuario
        ]);
    }

    /*
     * Genera el pdf
     */

    public function actionPdf() {
        //print_r($_GET);

        $usuario = $_GET['usuario'];
        $paralelo = $_GET['paralelo'];
        $bloque = $_GET['bloque'];

        $cabecera = $this->genera_cabecera($paralelo, $bloque);
        $html = $this->genera_html($usuario, $paralelo, $bloque);
        $pie = $this->genera_pie();

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

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
        $mpdf->SetFooter($pie);

        $mpdf->Output('MyPDF.pdf', 'D');
        exit;
    }

    private function genera_cabecera($paralelo, $bloque) {

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();
        
        $modelBloque = ScholarisBloqueActividad::find()
                ->where(['id' => $bloque])
                ->one();
        
        $modelInstituto = OpInstitute::find()->one();
       

        $cab = '<table width="100%">';
        $cab .= '<tr>';
        $cab .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $cab .= '<td><center>';
        $cab .= '<p>'.$modelInstituto->name.'</p>';
        $cab .= '<p style="font-size:10px">Registro de promedio de notas de mayor a menor</p>';
        
        $cab .= '</center>';
        $cab .= '</td>';
        $cab .= '<td align="right"><p style="font-size:10px">' . $modelParalelo->course->name . ' ' . $modelParalelo->name . '</p>';
        $cab .= '<p style="font-size:10px">' . $modelBloque->name . '</p>';
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

    private function genera_html($usuario, $paralelo, $bloque) {
        $model = ScholarisRepPromedios::find()
                ->where([
                    'paralelo_id' => $paralelo,
                    'usuario' => $usuario
                ])
                ->orderBy(['nota_promedio' => SORT_DESC])
                ->all();

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
                  }';
        $html .= '</style>';

        $html .= '<table width="100%" cellspacing="0" style="font-size:10px">';
        $html .= '<tr>';
        $html .= '<td class="conBorde">#</td>';
        $html .= '<td class="conBorde">Estudiante</td>';
        $html .= '<td class="conBorde">Promedio</td>';
        $html .= '<tr>';

        $i = 0;
        foreach ($model as $data) {
            $i++;
            $html .= "<tr>";
            $html .= '<td class="conBorde">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $data->alumno->last_name . ' ' . $data->alumno->first_name . ' ' . $data->alumno->middle_name . "</td>";
            $html .= '<td class="conBorde"><center>' . $data->nota_promedio . "</center></td>";
            $html .= "</tr>";
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

    /*
     * Para excel
     */



    public function actionExcel($paralelo, $usuario, $bloque) {
        header('Content-type: application/excel');
        $filename = 'filename.xls';
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

        $data .= $this->genera_excel_cabecera($paralelo, $bloque);
        $data .= $this->genera_excel_cuerpo($paralelo, $usuario, $bloque);
        

        $data .= "</body>";
        $data .= "</html>";
        echo $data;
        /* fin de excel */
    }
    
    private function genera_excel_cabecera($paralelo, $bloque){
        
        $modelInstituto = OpInstitute::find()->one();
        
        $modelBloque = ScholarisBloqueActividad::find()
                ->where(['id' => $bloque])
                ->one();
        
        $model = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();
        
        
        $data = '<table border="1">';
        $data .= "<tr>";
        $data .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $data .= '<td>'.$modelInstituto->name.'</td>';
        $data .= '<td>'.$model->course->name.' '.$model->name.'</td>';
        $data .= "</tr>";
        $data .= "<tr>";
        $data .= "<td></td>";
        $data .= "<td>Registro de promedio de notas de mayor a menor</td>";
        $data .= "<td>".$modelBloque->name."</td>";
        $data .= "</tr>";
        $data .= "</table>";
        
        
        return $data;
    }
    
    private function genera_excel_cuerpo($paralelo, $usuario, $bloque){
        $model = ScholarisRepPromedios::find()
                ->select([
                            "concat(op_student.last_name,' ',op_student.first_name,' ', op_student.middle_name) as last_name",
                            "scholaris_rep_promedios.nota_promedio"
                        ])
                ->innerJoin("op_student", "op_student.id = scholaris_rep_promedios.alumno_id")
                ->where([
                    'paralelo_id' => $paralelo,
                    'usuario' => $usuario
                ])                
                ->orderBy(['nota_promedio' => SORT_DESC])
                ->asArray()
                ->all();
        
        
        $data = "<table>";
        $data .= "<tr>";
        $data .= '<td>#</td>';
        $data .= '<td>Estudiante</td>';
        $data .= '<td>Promedio</td>';
        $data .= "</tr>";
        
        $num = 0;
        for ($i=0; $i < count($model); $i++){
            $num++;
            $data.= '<tr>';
            $data.= '<td>'.$num.'</td>';
            $data.= '<td>'.$model[$i]['last_name'].'</td>';
            $data.= '<td>'.$model[$i]['nota_promedio'].'</td>';
            $data.= '</tr>';
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
    
    
    

    /**
     * Displays a single ScholarisRepPromedios model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisRepPromedios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisRepPromedios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->codigo]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisRepPromedios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->codigo]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisRepPromedios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisRepPromedios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ScholarisRepPromedios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisRepPromedios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
