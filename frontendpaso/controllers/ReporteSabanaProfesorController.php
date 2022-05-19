<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ReporteSabanaProfesorController extends Controller {
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
    public function actionIndex1($id) {
   
               
        $model = \backend\models\ScholarisClase::find()->where(['id' => $id])->one();
        
        $modelRindeSupletorio = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $model->idcurso])->one();
       
        $modelLibreta = \backend\models\ScholarisClaseLibreta::find()
                ->innerJoin("scholaris_grupo_alumno_clase", "scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id")
                ->innerJoin("op_student","op_student.id = scholaris_grupo_alumno_clase.estudiante_id")
                ->where(["scholaris_grupo_alumno_clase.clase_id" => $id])
                ->orderBy("op_student.last_name","op_student.first_name")
                ->all();
        
        $modelMinimo = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();
        $minima = $modelMinimo->valor;
        
        return $this->render('index',[
            'model' => $model,
            'modelLibreta' => $modelLibreta,
            'minima' => $minima,
            'modelRindeSupletorio' => $modelRindeSupletorio
        ]);
        
    }
    
    public function actionCalcular($clase){
        //echo $clase;
        
        $sentencias = new \backend\models\NotasEnLibreta();
        $sentencias->calcula_promedios_clase($clase);
        
        return $this->redirect(['index1','id' => $clase]);
    }

    public function actionPdf($clase) {
        
//        echo $clase;
//        die();
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelClase = \backend\models\ScholarisClase::find()->where(['id' => $clase])->one();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $cabecera = $this->genera_cabecera_pdf($modelClase);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
        $mpdf->SetFooter($pie);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpoPdf($modelClase);

        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));

        $mpdf->Output('Sábana Profesor' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($modelClase) {

        $html = '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td width="30%"><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';

        $html .= '<td><center>';
        $html .= '<p>' . $modelClase->curso->xInstitute->name . '</p>';
        $html .= '<p style="font-size:10px">Sábana de calificaciones</p>';
        $html .= '</center>';

        $html .= '<td width="30%" style="text-align: right;">';
        $html .= '<p style="font-size:8px">' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name . '</p>';
        $html .= '<p style="font-size:7px">Año lectivo: ' . $modelClase->periodo_scholaris . '</p>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function cuerpoPdf($modelClase) {
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
                  
                  .tamano8{
                    font-size: 8px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .fondoCeleste{
                    background: #C2EBF8;
                }
                
                .fondoPlomo{
                    background: #E4E4E4;
                }
                
                .padding2{
                    padding: 5px;
                }

                    ';
        $html .= '</style>';

        $html .= '<table class="conBorde tamano10 fondoCeleste" width="100%">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto"><strong>ASIGNATURA</strong></td>';
        $html .= '<td class="centrarTexto"><strong>PROFESOR</strong></td>';
        $html .= '<td class="centrarTexto"><strong>PROMEDIA</strong></td>';
        $html .= '<td class="centrarTexto"><strong>PESO %</strong></td>';
        $html .= '<td class="centrarTexto"><strong>CUANTITATIVA</strong></td>';
        $html .= '<td class="centrarTexto"><strong>TIPOP</strong></td>';
        $html .= '<td class="centrarTexto"><strong>ORDEN</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->materia->name . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->materia->promedia . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->total_porcentaje . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->es_cuantitativa . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->tipo . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->orden . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $html .= $this->detallePdf($modelClase->id);

        return $html;
    }

    private function detallePdf($clase) {

        $html = '';

        $html .= '<br><br>';

        $html .= '<table class="tamano8" width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>ORD:</strong></td>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>ESTUDIANTES:</strong></td>';
        $html .= '<td class="conBorde centrarTexto" colspan="8"><strong>QUIMESTRE I</strong></td>';
        $html .= '<td class="conBorde centrarTexto" colspan="8"><strong>QUIMESTRE II</strong></td>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>FINAL</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto"><strong>P1</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P2</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P3</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>PR</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>80</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>EX</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>Q1</strong></td>';

        $html .= '<td class="conBorde centrarTexto"><strong>P4</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P5</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>P6</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>PR</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>80</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>EX</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>Q2</strong></td>';
        $html .= '</tr>';


        $modelNotas = \backend\models\ScholarisClaseLibreta::find()
                ->innerJoin("scholaris_grupo_alumno_clase","scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id")
                ->innerJoin("op_student","op_student.id = scholaris_grupo_alumno_clase.estudiante_id")
                ->where(["scholaris_grupo_alumno_clase.clase_id" => $clase])
                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                ->all();
        

        $i = 0;
        
        foreach ($modelNotas as $alumno){
             $i++;
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $alumno->grupo->alumno->last_name . ' ' . $alumno->grupo->alumno->first_name . ' ' . $alumno->grupo->alumno->middle_name . '</td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->p1 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->p2 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->p3 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $alumno->pr1 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $alumno->pr180 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->ex1 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $alumno->ex120 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $alumno->q1 . '</strong></td>';
            
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->p4 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->p5 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->p6 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $alumno->pr2 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $alumno->pr280 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor=""><strong>' . $alumno->ex2 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $alumno->ex220 . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $alumno->q2 . '</strong></td>';
            
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $alumno->final_ano_normal . '</strong></td>';
            $html .= '</tr>';
        }
        
        $html .= $this->promediosPdf($clase);
        $html .= $this->cuadroPdf($clase);
        
        $html .= '</table>';


        return $html;
    }

    private function promediosPdf($clase) {

        $sentencias = new \backend\models\NotasEnLibreta();

        $html = '';

        $nota = $sentencias->promedios_clase($clase);
      

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F" colspan="2"><strong>PROMEDIOS:</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['p1'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['p2'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['p3'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['pr1'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['pr180'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['ex1'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['ex120'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['q1'] . '</strong></td>';
        
        
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['p4'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['p5'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['p6'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['pr2'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['pr280'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['ex2'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['ex220'] . '</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['q2'] . '</strong></td>';
        
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $nota['final'] . '</strong></td>';
        
        
        $html .= '<tr>';

        return $html;
    }

    private function cuadroPdf($clase) {
        
        $sentencia = new \backend\models\NotasEnLibreta();
        
        $html = '';
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        
        $modelCuadro = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where(['corresponde_a' => 'APROVECHAMIENTO', 'scholaris_periodo' => $modelPeriodo->codigo])
                ->orderBy(["rango_minimo" => SORT_DESC])
                ->all();

        foreach ($modelCuadro as $cuadro){
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC" colspan="2"><strong>' 
                    . $cuadro->abreviatura
                    ."(".$cuadro->rango_minimo .' - '.$cuadro->rango_maximo.')'
                    . '</strong></td>';
            
            
            $p1 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'p1');
            $p2 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'p2');
            $p3 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'p3');
            $pr1 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'pr1');
            $pr180 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'pr180');
            $ex1 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'ex1');
            $ex120 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'ex120');
            $q1 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'q1');
            
            $p4 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'p4');
            $p5 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'p5');
            $p6 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'p6');
            $pr2 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'pr2');
            $pr280 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'pr280');
            $ex2 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'ex2');
            $ex220 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'ex220');
            $q2 = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'q2');
            
            $final = $sentencia->totales_cuadro($clase, $cuadro->rango_minimo, $cuadro->rango_maximo, 'final_ano_normal');
            
            
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $p1['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $p2['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $p3['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $pr1['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $pr180['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $ex1['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $ex120['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $q1['total'] . '</strong></td>';
            
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $p4['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $p5['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $p6['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $pr2['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $pr280['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $ex2['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $ex220['total'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $q2['total'] . '</strong></td>';
            
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $final['total'] . '</strong></td>';
            
            $html .= '</tr>';
        }

        return $html;
    }
    
    
    

}
