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
class QuitoReportesMecNormalPromocionRedeController extends Controller {
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
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        $modelAlumnos = $sentencia->get_alumnos_paralelo($paralelo);
        $modelMalla = \backend\models\ScholarisMecV2MallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 40,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 1,
        ]);

        $cabecera = $this->genera_cabecera_pdf($paralelo);
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);

        //$html1 = '';
        foreach ($modelAlumnos as $alumno) {
            $html1 = $this->detalle_alumno($alumno, $paralelo, $conAreas, $modelMalla->curso_id);
            $mpdf->WriteHTML($html1, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }



        $mpdf->Output('Reporte-Promocion' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($paralelo) {

       

        $html = '';
        $html .= '<br>';
        $html .= '<table width="100%" border="0">';
        $html .= '<tr>';
        $html .= '<td align="right" width="33%"></td>';
        $html .= '<td align="center" width="33%"><img src="imagenes/instituto/logo/logo2.png" width="80"></td>';
        $html .= '<td align="right" width="34%"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="300"></td>';
        $html .= '</tr>';
        $html .= '</table>';

        

        return $html;
    }

    private function detalle_alumno($alumno, $paralelo, $conareas, $mallaId) {

        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
         $sentencias = new \backend\models\SentenciasMecNormales();
        $model = $sentencias->get_paralelo($paralelo);

        if (isset(Yii::$app->user->identity->periodo_id)) {
            $periodo = Yii::$app->user->identity->periodo_id;
            $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        } else {
            $this->redirect(['site/index']);
        }

        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano16{font-size:16px;}';
        $html .= '.tamano10{font-size:12px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '</style>';
        $html .= '<br>';
        
        $html .= '<table width="100%" border="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="left"><strong>CÓDIGO AMIE: </strong>' . $model->institute->codigo_amie . '</td>';
        $html .= '<td align="center"><strong>AÑO LECTIVO: </strong>' . $modelPeriodo->codigo . '</td>';
        $html .= '<td align="right"><strong>RÉGIMEN: </strong>' . $model->institute->regimen . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br>';

        $html .= '<div align="center" class="tamano16"><strong>CERTIFICADO DE PROMOCIÓN</strong></div>';
        $html .= '<div align="left" class="tamano10">El Rector (a) / Director (a) de la Institución Educativa: </div>';
        $html .= '<br>';
        $html .= '<div align="center" class="tamano16"><strong>' . $model->institute->name . '</strong></div>';

        $html .= '<div align="justify" class="tamano10">';
        $html .= 'De conformidad con lo prescrito en el Art. 197 del Reglamento General a la Ley Orgánica de '
                . 'Educación Intercultural y demás normativas vigentes, certifica que el / la estudiante <strong>' 
                . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</strong> del <strong>' 
                . $modelParalelo->course->xTemplate->name . ', PARALELO "' . $modelParalelo->name . '"</strong>, CÓDIGO ESTUDIANTE N°' . $alumno['numero_identificacion'] 
                . ' , obtuvo las siguientes calificaciones durante el presente año lectivo.';
        $html .= '</div>';
        $html .= '<br>';

        $html .= $this->calificaciones_alumno($alumno, $paralelo, $conareas, $mallaId);

        return $html;
    }

    private function calificaciones_alumno($alumno, $paralelo, $conareas, $mallaId) {


        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $curso = $modelParalelo->course->id;

        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';

        if ($conareas == 'si') {
            $html .= '<td align="center" rowspan="2" class="bordesolido" width="25%">ÁREA</td>';
        }

        $html .= '<td align="center" rowspan="2" class="bordesolido" width="25%">ASIGNATURAS</td>';
        $html .= '<td colspan="2" align="center" class="bordesolido">PROMEDIO ANUAL</td>';

        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center" class="bordesolido" width="15%">CALIFICACIÓN CUANTITATIVA</td>';
        $html .= '<td align="center" class="bordesolido" width="35%">CALIFICACIÓN CUALITATIVA</td>';
        $html .= '</tr>';

        $html .= $this->detalle_materias($curso, $alumno, $paralelo, $conareas, $mallaId);



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
        $texto1 = '<br>Por lo tanto es promovido/a al ';
        $texto2 = 'Para certificar suscriben el /la director/a - Rector/a del Plantel.';

        $textoPierde = '<br>Por lo tanto no es promovido/a al siguiente nivel';

        if ($modelRindeSup->rinde_supletorio == 1) {
            if ($totalBajos > 0) {
                $html .= '<div class="tamano10">' . $textoPierde . '</div>' . '<div class="tamano10">' . $texto2 . '</div>';
            } else {
                if ($modelCurso->xTemplate->next_course_id != null) {
                    $modelCursoNuevo = \backend\models\OpCourseTemplate::findOne($modelCurso->xTemplate->next_course_id);
                    $html .= '<div class="tamano10">' . $texto1 . $modelCursoNuevo->name . '</div>' . '<div class="tamano10">' . $texto2 . '</div>';
                } else {
                    $html .= '<div class="tamano10">' . $texto2 . '</div>';
                }
            }
        } else {
            if ($promedio < $notaMinima->valor) {
                $html .= '<div class="tamano10">' . $textoPierde . '</div>' . '<div class="tamano10">' . $texto2 . '</div>';
            } else {
                $modelCursoNuevo = \backend\models\OpCourseTemplate::findOne($modelCurso->xTemplate->next_course_id);
                $html .= '<div class="tamano10">' . $texto1 . $modelCursoNuevo->name . '</div>' . '<div class="tamano10">' . $texto2 . '</div>';
            }
        }

        $html .= '<div class="tamano10">Dado y firmado en: ' . $modelInstituto->store->company->partner->city . ', ' . $modelInstituto->store->company->partner->state->name . ' ' . $this->fecha_hoy() . '</div>';

        $html .= '<br><br><br><br><br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelCurso->xInstitute->rector . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelCurso->xInstitute->secretario . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>RECTORA</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>SECRETARIA</strong></td>';
        $html .= '</tr>';

        $html .= '</table>';


        return $html;
    }

    private function fecha_hoy() {
        $anio = date("Y");
        $mes = date("m");
        $dia = date("d");


        switch ($mes) {
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

        $respuesta = ', el ' . $dia . ' de ' . $m . ' ' . $anio;

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

                $nota = $sentencias->toma_notas_materias($materia->id, $alumno['id'], $paralelo, 'p6');

                if ($nota) {
                    $nota = $nota;
                } else {
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

    private function detalle_materias($curso, $alumno, $paralelo, $conareas, $mallaId) {

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
        $modelMaterias = \backend\models\ScholarisMecV2MallaMateria::find()
                        ->innerJoin('scholaris_mec_v2_malla_area ma', 'ma.id = scholaris_mec_v2_malla_materia.area_id')
                        ->where(['ma.malla_id' => $mallaId, 'ma.tipo' =>'NORMAL'])
                        ->orderBy('ma.orden', 'scholaris_mec_v2_malla_materia')->all();


        $html .= '<tr>';

        foreach ($modelMaterias as $materia) {
            
            
            if($materia->promedia == true){
                $promedia = '';
            }else{
                $promedia = '*';
            }
            
            
            $html .= '<td class="bordesolido">' .$promedia. $materia->area->asignatura->nombre . '</td>';
            $html .= '<td class="bordesolido">' . $materia->asignatura->nombre . '</td>';

            $nota = $sentencias->toma_notas_materias($materia->id, $alumno['id'], $paralelo, 'final_total');
             $nota = $nota/$escala;
             $nota = number_format($nota,2);

             if($materia->promedia == true){
                $suma = $suma + $nota;
                $cont++;
             }        
            

            if ($nota < $notaMinima->valor) {
                $menosdeMinimo++;
            }

            $html .= '<td align="center" class="bordesolido">' . $nota . '</td>';

            $letras = $sentenciaNotas->equivalencia_aprovechamiento($nota);

            $html .= '<td align="center" class="bordesolido">' . $letras . '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
        }

//        $promedio = $sentenciasNxx->notaFinalAprovechamiento;
        $promedio = $suma / $cont;
        $promedio = $sentenciaNotas->truncarNota($promedio, 2);
        $enLetras = $sentenciaNotas->equivalencia_aprovechamiento($promedio);
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
//
        $html .= $this->consecuencia($curso, $promedio, $menosdeMinimo);

        return $html;
    }

    private function convertir_letras($nota) {

        $sentencias = new \backend\models\SentenciasMecNormales();

        $cadenaNumero = explode(".", $nota);
        $entero = $cadenaNumero[0];
        $decimal = $cadenaNumero[1];

        $entero = $sentencias->numToLetras($entero);
        $decimal = $sentencias->decimalToLetras($decimal);


        $res = $entero . ' COMA ' . $decimal;
        return $res;
    }

}
