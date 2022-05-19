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
            'margin_top' => 50,
            'margin_bottom' => 10,
            'margin_header' => 0,
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
        $html .= '<table width="100%" border="">';
        $html .= '<tr>';
        $html .= '<td align="right" width="60%"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="200"></td>';
        $html .= '<td align="right" width="40%"><img src="imagenes/instituto/logo/logo2.png" width="70"></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center" colspan="2"><strong>';
        $html .= 'SUBSECRETARÍA DE EDUCACIÓN DEL DISTRITO METROPOLITANO DE QUITO <br>';
        $html .= $model->institute->name . '<br>';
        $html .= 'CERTIFICADO DE APTITUD<br>';
        $html .= 'AÑO LECTIVO ' . $modelPeriodo->nombre . '<br>';
        $html .= 'JORNADA MATUTINA';
        $html .= '</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';


        return $html;
    }

    private function detalle_alumno($alumno, $paralelo, $conareas) {

        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '</style>';


        $html .= '<p align="justify">';
        $html .= 'De conformidad con lo prescrito en el Art. 197 del Reglamento General a la Ley Orgánica de '
                . 'Educación Intercultural y demás normativas vigentes, certifica que el / la estudiante '
                . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name']
                . ' del ' . $modelParalelo->course->xTemplate->name . ', PARALELO "' . $modelParalelo->name . '" , obtuvo las siguientes calificaciones durante el
presente año lectivo:';
        $html .= '</p>';

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
            $html .= '<td align="center" rowspan="2" class="bordesolido">ÁREA</td>';
        }

        $html .= '<td align="center" rowspan="2" class="bordesolido">ASIGNATURAS</td>';
        $html .= '<td colspan="2" align="center" class="bordesolido">CALIFICACIONES</td>';

        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center" class="bordesolido">NÚMERO</td>';
        $html .= '<td align="center" class="bordesolido">LETRAS</td>';
        $html .= '</tr>';

        $html .= $this->detalle_materias($curso, $alumno, $paralelo, $conareas);



//        $html .= '</table>';

        return $html;
    }

    private function consecuencia($curso, $promedio, $totalBajos) {

        $notaMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $modelCurso = \backend\models\OpCourse::findOne($curso);
        $modelRindeSup = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $curso])->one();

        $html = '';
        //$texto1 = '<br>Por lo tanto es promovido/a al ';
        $texto2 = '<br>Para certificar suscriben en unidad de acto la Rectora con la Secretaria General del Plantel.';

        $textoPierde = '<br>Por lo tanto no es promovido/a al siguiente nivel';

        if ($modelRindeSup->rinde_supletorio == 1) {
            if ($totalBajos > 0) {
                $html .= $textoPierde . $texto2;
            } else {
                if ($modelCurso->xTemplate->next_course_id != null) {
                    $modelCursoNuevo = \backend\models\OpCourseTemplate::findOne($modelCurso->xTemplate->next_course_id);
                    //$html .= $texto1.$modelCursoNuevo->name.$texto2;
                    $html .= $texto2;
                } else {
                    $html .= $texto2;
                }
            }
        } else {
            if ($promedio < $notaMinima->valor) {
                $html .= $textoPierde . $texto2;
            } else {
                $modelCursoNuevo = \backend\models\OpCourseTemplate::findOne($modelCurso->xTemplate->next_course_id);
//                    $html .= $texto1.$modelCursoNuevo->name.$texto2;
                $html .= $texto2;
            }
        }


        $html .= '<br><br><br>';
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

    private function detalle_materiasProyectos($curso, $alumno, $paralelo, $conareas) {
        $sentencias = new \backend\models\SentenciasMec();

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

                $html .= '<td align="center" class="bordesolido">' . $nota . '</td>';

                $letras = $this->convertir_letras($nota);

                $html .= '<td align="" class="bordesolido">' . $letras . '</td>';
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

        $sentencias = new \backend\models\SentenciasMec();
        $sentenciaNotas = new \backend\models\Notas();
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
                $html .= '<td rowspan="' . count($modelMaterias) . '" class="bordesolido">' . $area->asignatura->nombre . '</td>';
            }

            foreach ($modelMaterias as $materia) {
                $html .= '<td class="bordesolido">' . $materia->asignatura->nombre . '</td>';

                $nota = $sentencias->toma_notas_materias($materia->id, $alumno['id'], $paralelo, 'final_total');

                $suma = $suma + $nota;
                $cont++;

                if ($nota < $notaMinima->valor) {
                    $menosdeMinimo++;
                }

                $html .= '<td align="center" class="bordesolido">' . $nota . '</td>';

                $letras = $this->convertir_letras($nota);

                $html .= '<td align="" class="bordesolido">' . $letras . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
            }
        }

        $promedio = $suma / $cont;
        $promedio = $sentenciaNotas->truncarNota($promedio, $digito);
        $promedio = number_format($promedio, $digito);

        $enLetras = $this->convertir_letras($promedio);

        $html .= '<tr>';
        if ($conareas == 'si') {
            $html .= '<td colspan="2" class="bordesolido"><strong>PROMEDIO GENERAL</strong></td>';
        } else {
            $html .= '<td class="bordesolido"><strong>PROMEDIO GENERAL</strong></td>';
        }

        $html .= '<td colspan="" class="bordesolido" align="center"><strong>' . $promedio . '</strong></td>';
        $html .= '<td colspan="" class="bordesolido" align=""><strong>' . $enLetras . '</strong></td>';

        $html .= '</tr>';

        $html .= $this->detalle_materiasProyectos($curso, $alumno, $paralelo, $conareas);
        $html .= $this->detalle_materiasComportam($curso, $alumno, $paralelo, $conareas);
        $html .= '</table>';

        $html .= $this->consecuencia($curso, $promedio, $menosdeMinimo);

        return $html;
    }

    private function convertir_letras($nota) {

        $sentencias = new \backend\models\SentenciasMecNormales();


        if (isset($cadenaNumero)) {
            $cadenaNumero = explode(".", $nota);
            $entero = $cadenaNumero[0];
            $decimal = $cadenaNumero[1];

            $entero = $sentencias->numToLetras($entero);
            $decimal = $sentencias->decimalToLetras($decimal);


            $res = $entero . ' COMA ' . $decimal;
            return $res;
        } else {
            return 0;
        }
    }

}
