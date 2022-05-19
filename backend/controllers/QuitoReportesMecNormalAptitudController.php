<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Mpdf\Mpdf;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class QuitoReportesMecNormalAptitudController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//              'class' => AccessControl::className(),
//                'rules' => [
//                  [
//                      'allow' => true,
//                      'roles' => ['@'],
//                  ]  
//                ],
//            ],
//            
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    
//     public function beforeAction($action) {
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
     * Lists all PlanPlanificacion models.
     * @return mixed
     */
    public function actionIndex() {

        $sentencia = new \backend\models\SentenciasAlumnos();
        $paralelo = $_GET['paralelo'];
        $conAreas = $_GET['conareas'];



        $modelAlumnos = $sentencia->get_alumnos_paralelo($paralelo);

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 60,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 1,
        ]);

        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);

        //$html1 = '';
        foreach ($modelAlumnos as $alumno) {
            $html1 = $this->detalle_alumno($alumno, $paralelo, $conAreas);
            $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }



        $mpdf->Output('Reporte-Promocion' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

        $sentencias = new \backend\models\SentenciasMecNormales();
        $model = $sentencias->get_paralelo($paralelo);

        if (isset(Yii::$app->user->identity->periodo_id)) {
            $periodo = Yii::$app->user->identity->periodo_id;
            $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        } else {
            $this->redirect(['site/index']);
        }

        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%" border="0">';
        $html .= '<tr>';
        $html .= '<td align="right" width="33%"></td>';
        $html .= '<td align="center" width="34%"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="300"></td>';
        $html .= '<td align="right" width="33%" <img src="imagenes/instituto/logo/logo.png" width="300">></td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" border="0" class="tamano12">';
        $html .= '<tr>';
        $html .= '<td align="center" colspa><strong>SUBSECRETARÍA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center" colspa><strong>'.$model->institute->name.'</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center" colspa><strong>CERTIFICADO DE APTITUD</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>AÑO LECTIVO: </strong>'.$modelPeriodo->codigo.'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="center" colspa><strong>JORNADA MATUTINA</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';        
        return $html;
    }

    private function detalle_alumno($alumno, $paralelo, $conareas) {

        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano16{font-size:16px;}';
        $html .= '.tamano10{font-size:12px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '</style>';
         $html .= '<br>';

        $html .= '<div align="justify" class="tamano10">';
        $html .= 'De conformidad con lo prescrito en el Art. 197 del Reglamento General a la Ley Orgánica de '
                . 'Educación Intercultural y demás normativas vigentes, certifica que el / la estudiante '
                . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'].' del ' . $modelParalelo->course->xTemplate->name . ', PARALELO "' . $modelParalelo->name . '"';
        $html .= ' , obtuvo las siguientes calificaciones durante el presente año lectivo.';
        $html .= '</div><br>';

        $html .= $this->calificaciones_alumno($alumno, $paralelo, $conareas);


        return $html;
    }

    private function calificaciones_alumno($alumno, $paralelo, $conareas) {


        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $curso = $modelParalelo->course->id;

        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';

        if ($conareas == 'si') {
            $html .= '<td align="center" rowspan="2" class="bordesolido" width="25%">ÁREA</td>';
        }

        $html .= '<td align="center" rowspan="2" class="bordesolido" width="25%">ASIGNATURAS</td>';
        $html .= '<td colspan="2" align="center" class="bordesolido">CALIFICACIONES</td>';

        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center" class="bordesolido" width="15%">NÚMERO</td>';
        $html .= '<td align="center" class="bordesolido" width="35%">LETRAS</td>';
        $html .= '</tr>';

        $html .= $this->detalle_materias($curso, $alumno, $paralelo, $conareas);



//        $html .= '</table>';

        return $html;
    }

    private function consecuencia($curso, $promedio, $totalBajos) {
        
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = \backend\models\OpInstitute::findOne($institutoId);
        

        $notaMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $modelCurso = \backend\models\OpCourse::findOne($curso);
        $modelRindeSup = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $curso])->one();

        $html = '';
       
        $texto2 = 'Para certificar suscriben en unidad de acto el /la  Rector/a con el/la Secretaria General del Plantel.';

        $textoPierde = '<br>Por lo tanto no es promovido/a al siguiente nivel';
        
        
         $html .= '<br>';
         $html .= '<div class="tamano10">'.$texto2.'</div>';
        

        
        
//         $html .= '<div class="tamano10">Dado y firmado en: '.$modelInstituto->store->company->partner->city.', '.$modelInstituto->store->company->partner->state->name.' '.$this->fecha_hoy().'</div>';
        
        $html .= '<br><br><br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center"><strong>'.$modelCurso->xInstitute->rector.'</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>'.$modelCurso->xInstitute->secretario.'</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td align="center"><strong>RECTORA</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>SECRETARIA</strong></td>';
        $html .= '</tr>';
        
        $html .= '</table>';


        return $html;
    }
    
    private function fecha_hoy(){
        $anio = date("Y");
        $mes = date("m");
        $dia = date("d");
        
        
        switch ($mes){
            case 1:
                $m = 'Enero';
                break;
            
            case 2:
                $m = 'Febrero';
                break;
            
            case 3:
                $m = 'Marzo';
                break;
            
            case 4:
                $m = 'Abril';
                break;
            
            case 5:
                $m = 'Mayo';
                break;
            
            case 6:
                $m = 'Junio';
                break;
            
            case 7:
                $m = 'Julio';
                break;
            
            case 8:
                $m = 'Agosto';
                break;
            
            case 9:
                $m = 'Septiembre';
                break;
            
            case 10:
                $m = 'Octubre';
                break;
            
            case 11:
                $m = 'Noviembre';
                break;
            
            case 12:
                $m = 'Diciembre';
                break;
        }
        
        $respuesta = ', el '.$dia.' de '.$m.' '.$anio;
        
        return $respuesta;
    }

    private function detalle_materiasProyectos($curso, $alumno, $paralelo, $conareas) {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        
        $sentenciasNxx = new \backend\models\SentenciasNotasDefinitivasAlumno($alumno['id'], $periodoId, $paralelo);
        $sentencias = new \backend\models\SentenciasMec();
        $sentenciasNotas = new \backend\models\Notas();

        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $curso])->one();

        $mallaId = $modelMalla->malla_id;

        $modelAreasNormal = \backend\models\ScholarisMecV2MallaArea::find()
                        ->where(['malla_id' => $mallaId, 'tipo' => 'PROYECTOS'])
                        ->orderBy('orden')->all();

        $html = '';

        foreach ($modelAreasNormal as $area) {

            $modelMaterias = \backend\models\ScholarisMecV2MallaMateria::find()->where(['area_id' => $area->id])->orderBy('orden')->all();

            $html .= '<tr>';
            if ($conareas == 'si') {
                $html .= '<td rowspan="' . count($modelMaterias) . '" class="bordesolido">' . $area->asignatura->nombre . '</td>';
            }

            foreach ($modelMaterias as $materia) {
                $html .= '<td class="bordesolido">' . $materia->asignatura->nombre . '</td>';

                $nota = $sentencias->toma_notas_materias($materia->id, $alumno['id'], $paralelo, 'final_total');
                
                if($nota){
                    $nota = $nota;
                }else{
                    $nota = 0;
                }
                
                $notaHomo = $sentenciasNotas->homologa_cualitativas($nota);
                $notaDesc = $sentenciasNotas->get_descripcion_proyectos($nota);

                $html .= '<td align="center" class="bordesolido">' . $notaHomo . '</td>';

//                $letras = $this->convertir_letras($nota);

                $html .= '<td align="" class="bordesolido">' . $notaDesc . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
            }
        }


        return $html;
    }

    private function detalle_materiasComportam($curso, $alumno, $paralelo, $conareas) {
        $sentencias = new \backend\models\SentenciasMec();
        $sentenciaNotas = new \backend\models\Notas();

        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $curso])->one();

        $mallaId = $modelMalla->malla_id;

        $modelAreasNormal = \backend\models\ScholarisMecV2MallaArea::find()
                        ->where(['malla_id' => $mallaId, 'tipo' => 'COMPORTAMIENTO'])
                        ->orderBy('orden')->all();

        $html = '';

        foreach ($modelAreasNormal as $area) {

            $modelMaterias = \backend\models\ScholarisMecV2MallaMateria::find()->where(['area_id' => $area->id])->orderBy('orden')->all();

            $html .= '<tr>';
            if ($conareas == 'si') {
                $html .= '<td rowspan="' . count($modelMaterias) . '" class="bordesolido">' . $area->asignatura->nombre . '</td>';
            }

            foreach ($modelMaterias as $materia) {
                $html .= '<td class="bordesolido">' . $materia->asignatura->nombre . '</td>';

                $nota = $sentencias->toma_notas_materias($materia->id, $alumno['id'], $paralelo, 'p6');
                $compNota = $sentenciaNotas->homologa_comportamiento_mec($nota, $modelParalelo->course->section0->code);

                $html .= '<td align="center" class="bordesolido">' . $compNota['abreviatura'] . '</td>';

                $html .= '<td align="" class="bordesolido">' . $compNota['descripcion'] . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
            }
        }


        return $html;
    }

    private function detalle_materias($curso, $alumno, $paralelo, $conareas) {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $sentencias = new \backend\models\SentenciasMec();
        $sentenciaNotas = new \backend\models\Notas();
        $modelEscala = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'scala'])->one();
        $escala = $modelEscala->valor;
        
        $sentenciasNxx = new \backend\models\SentenciasNotasDefinitivasAlumno($alumno['id'], $periodoId, $paralelo);
        
        $digito = 2;


        $notaMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $curso])->one();

        $mallaId = $modelMalla->malla_id;
        
        $modelAreasNormal = \backend\models\ScholarisMecV2MallaArea::find()
                        ->where(['malla_id' => $mallaId, 'tipo' => 'NORMAL'])
                        ->orderBy('orden')->all();

        $html = '';


        $suma = 0;
        $cont = 0;
        $menosdeMinimo = 0;
        foreach ($modelAreasNormal as $area) {

            $modelMaterias = \backend\models\ScholarisMecV2MallaMateria::find()->where(['area_id' => $area->id])->orderBy('orden')->all();

            $html .= '<tr>';
            if ($conareas == 'si') {
                $html .= '<td class="bordesolido" rowspan="'. count($modelMaterias).'">' . $area->asignatura->nombre . '</td>';
            }

            foreach ($modelMaterias as $materia) {
//                $html .= '<td class="bordesolido">' . $area->asignatura->nombre . '</td>';
                $html .= '<td class="bordesolido">' . $materia->asignatura->nombre . '</td>';

                $nota = $sentencias->toma_notas_materias($materia->id, $alumno['id'], $paralelo, 'final_total');
                $nota = $nota/$escala;
                $nota = number_format($nota,2);

                $suma = $suma + $nota;
                $cont++;

                if ($nota < $notaMinima->valor) {
                    $menosdeMinimo++;
                }

                $html .= '<td align="center" class="bordesolido">' . $nota . '</td>';
                
//                $letras = $sentenciaNotas->equivalencia_aprovechamiento($nota);
                $letras = $this->convertir_letras($nota);

                $html .= '<td align="left" class="bordesolido">' . $letras . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
            }
        }
        
        $promedio = $suma / $cont;
        $promedio = $sentenciaNotas->truncarNota($promedio, $digito);
        $promedio = number_format($promedio, $digito);

        $enLetras = $this->convertir_letras($promedio);
//        $enLetras = $sentenciaNotas->equivalencia_aprovechamiento($promedio);

        $html .= '<tr>';
        if ($conareas == 'si') {
            $html .= '<td colspan="2" class="bordesolido"><strong>PROMEDIO GENERAL</strong></td>';
        } else {
            $html .= '<td class="bordesolido"><strong>PROMEDIO GENERAL</strong></td>';
        }

        $html .= '<td colspan="" class="bordesolido" align="center"><strong>' . $promedio . '</strong></td>';
        $html .= '<td colspan="" class="bordesolido" align="center"><strong>' . $enLetras . '</strong></td>';

        $html .= '</tr>';

        $html .= $this->detalle_materiasProyectos($curso, $alumno, $paralelo, $conareas);
        $html .= $this->detalle_materiasComportam($curso, $alumno, $paralelo, $conareas);
        $html .= '</table>';

        $html .= $this->consecuencia($curso, $promedio, $menosdeMinimo);

        return $html;
    }

    private function convertir_letras($nota) {
        
        if($nota){
            $sentencias = new \backend\models\SentenciasMecNormales();

        $cadenaNumero = explode(".", $nota);
        $entero = $cadenaNumero[0];
        $decimal = $cadenaNumero[1];

        $entero = $sentencias->numToLetras($entero);
        $decimal = $sentencias->decimalToLetras($decimal);


        $res = $entero . ' COMA ' . $decimal;
        return $res;
        }else{
            return 'No se cerró el proceso en esta materia';
        }

        
    }

}
