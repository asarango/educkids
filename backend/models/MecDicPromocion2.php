<?php

namespace backend\models;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\OpStudent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class MecDicPromocion2 extends \yii\db\ActiveRecord {

    private $paralelo;
    private $modelParalelo;
    private $periodoId;
    private $periodoCodigo;
    private $tieneProyectos = 0;
    private $modelAlumnos;
    private $usuario;
    private $modelBloquesQ1;
    private $seccion;
    private $comportamientoAutomatico = 0;
    private $tipoCalificacionProyectos = 'PROYECTOSNORMAL';
    private $tipoCalificacion;
    private $mallaMecId;
    private $arrayMaterias;
    private $arrayAreas;
    private $escala;
    private $promedio;
    private $totalBajos;

    public function __construct($paralelo) {
        $this->paralelo = $paralelo;

        /*         * ** Periodo actual ** */
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        ///// FIN DE PERIODO

        /**
         * para tomar tipo de calificacion
         */
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        //////// fin de tipo de calificacion /////////////

        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $this->modelParalelo->course->section0->code;


        $modelMalla = ScholarisMecV2MallaCurso::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->mallaMecId = $modelMalla->malla_id;


        $this->tieneProyectos = $this->tiene_proyectos(); //llama a funcion para buscar si tiene proyectos

        $this->usuario = Yii::$app->user->identity->usuario;  //usuario que esta con login

        $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo); // toma estudiantes del paralelo

        /*         * * para el uso del bloque ** */
        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;
        //// fin del uso del bloque

        /*         * *** verifica si tiene comportamiento automatico *** */
        $modelComportamientoParam = ScholarisParametrosOpciones::find()->where(['codigo' => 'comportamiento'])->one();
        $this->comportamientoAutomatico = $modelComportamientoParam->valor;
        //// FIN DE VERIFICACION DE COMPORTAMIENTO AUTOMATICO ///////


        /*         * ********** para ver tipo de proyectos ******** */
        $modelTipoProyectos = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->tipoCalificacionProyectos = $modelTipoProyectos->tipo_proyectos;
        /////////////////////////////////////////////////////////////////////////////

        $this->modelBloquesQ1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();

        $this->get_materias_normales();  ///para poblar variable con arreglo de las materias
        ///para poblar variable de escala
        $parametros = ScholarisParametrosOpciones::find()->where(['codigo' => 'scala'])->one();
        $this->escala = $parametros->valor;
        ////// fin de escala /////////////////////////////

        $this->genera_reporte_pdf();
    }

    private function tiene_proyectos() {
        $cursoId = $this->modelParalelo->course_id;

        $con = Yii::$app->db;
        $query = "select 	count(ma.id) as total 
from 	scholaris_mec_v2_malla_curso c
		inner join scholaris_mec_v2_malla_area ma on ma.malla_id = c.malla_id
where	c.curso_id = $cursoId
		and ma.tipo = 'PROYECTOS';";

        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }

    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 20,
            'margin_right' => 10,
            'margin_top' => 55,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
            'default_font' => 'dejavusans'
        ]);


        $cabecera = $this->genera_cabecera();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;


        foreach ($this->modelAlumnos as $alumno) {
            $html = $this->genera_cuerpo('CERTIFICADO DE PROMOCION', 'final_total', $alumno);
            $mpdf->WriteHTML($html);
            $mpdf->addPage();
        }

        $mpdf->Output('MEC-Quimestrales' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';

        $html .= '<table style="font-size:16px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/mec/sellopromo1.png" width="200px"></td>';
        $html .= '<td class="centrarTexto"></td>';
        $html .= '<td align="right" style="color: #001c61">MINISTERIO DE EDUCACIÓN</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<br>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano12" align="left" width="30%"><strong>CÓDIGO AMIE: </strong>' . $modelParalelo->institute->codigo_amie . '</td>';
        $html .= '<td class="tamano12" align="center"><strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo . '</td>';
        $html .= '<td class="tamano12" align="right" width="30%"><strong>RÉGIMEN: </strong>' . $modelParalelo->institute->regimen . '</td>';
        $html .= '</tr>';
        $html .= '</table>';


        $html .= '<br>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" style="font-size:16px"><strong>CERTIFICADO DE PROMOCIÓN</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="tamano12">El Rector (a) / Director (a) de la institución Educativa:</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" style="font-size:16px"><strong>' . $modelParalelo->institute->name . '</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<br>';

        return $html;
    }

    private function genera_cuerpo($titulo, $quimestre, $alumno) {

        $html = '';

        $html .= '<style>';
        '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= 'td {
                    border-collapse: collapse;
                    border: 1px black solid;
                  }
                  tr:nth-of-type(5) td:nth-of-type(1) {
                    visibility: hidden;
                  }
                  .rotate {
                    /* FF3.5+ */
                    -moz-transform: rotate(-90.0deg);
                    /* Opera 10.5 */
                    -o-transform: rotate(-90.0deg);
                    /* Saf3.1+, Chrome */
                    -webkit-transform: rotate(-90.0deg);
                    /* IE6,IE7 */
                    filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
                    /* IE8 */
                    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
                    /* Standard */
                    transform: rotate(-90.0deg);
                  }';
        $html .= '.bordesolido{border: 0.2px solid #000;}';
        $html .= '.tamano16{font-size:16px;}';
        $html .= '.tamano12{font-size:12px;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';


        $html .= $this->procesa_asignaturas($quimestre, $alumno);

        return $html;
    }

    private function firmas() {
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = OpInstitute::findOne($institutoId);

        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';






        $html .= '<table width="100%" height="300" cellpadding="0" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>_________________________________________</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>_________________________________________</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelInstituto->rector . '</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelInstituto->secretario . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>RECTOR(A)</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>SECRETARIO(A)</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<div class="centrarTexto"><img src="imagenes/instituto/logo/sellolibreta.png" width="100px"></div>';


        return $html;
    }

    private function procesa_asignaturas($quimestre, $alumno) {


        $html = '';

        $html .= '<br><br><br>';

        $html .= '<div align="justify" class="tamano12">';
        $html .= 'De conformidad con lo prescrito en el Art. 197 del Reglamento General a la Ley Orgánica de '
                . 'Educación Intercultural y demás normativas vigentes, certifica que el / la estudiante';

        $html .= '<br>';

        $html .= '<p class="centrarTexto tamano16"><strong>'
                . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'];
        $html .= '</strong><p>';

        $html .= '<p><strong>del '
                . $this->modelParalelo->course->xTemplate->name . ', PARALELO <strong>"' . $this->modelParalelo->name . '",</strong>';
        $html .= '</strong></p>CÓDIGO DE ESTUDIANTE N° ' . $alumno['numero_identificacion'] . ', obtuvo las siguientes calificaciones durante el presente año lectivo.';
        $html .= '</div><br>';

        $html .= '<table width="100%" cellspacing="0" cellpadding="3" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td rowspan="2" class="bordesolido centrarTexto"><strong>ÁREA</strong></td>';
        $html .= '<td rowspan="2" class="bordesolido centrarTexto"><strong>ASIGNATURAS</strong></td>';
        $html .= '<td colspan="2" class="bordesolido centrarTexto"><strong>PROMEDIO ANUAL</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto"><strong>CALIFICACIÓN CUANTITATIVA</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>CALIFICACIÓN CUALITATIVA</strong></td>';
        $html .= '</tr>';

        $html .= $this->procesa_materias($alumno);


        $html .= '</table>';

        $html .= $this->consecuencia($this->modelParalelo->course_id, $this->promedio);

        return $html;
    }

    private function procesa_materias($alumno) {

        if ($this->tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new AlumnoNotasNormales();
        } elseif ($this->tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
        } elseif ($this->tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }

        $procesosMec = new MecProcesaMaterias();
        $sentenciasNotas = new Notas();

        $html = '';

        $suma = 0;
        $cont = 0;
        $this->totalBajos = 0;

        foreach ($this->arrayAreas as $area) {
            $html .= '<tr>';

            $html .= '<td class="bordesolido" rowspan="' . $area['total_materias'] . '"><strong>' . $area['nombre'] . '</strong></td>';
            $materias = $procesosMec->get_materias_x_area_mec_normales($area['id']);

            foreach ($materias as $materia) {
                $html .= '<td class="bordesolido">' . $materia['nombre'] . '</strong></td>';
//                    
//                    $notas = $procesosMec->get_nota($materia['id'], $alumno['id'], $this->tipoCalificacion, 
//                                                    $this->paralelo, $this->usuario, $this->periodoCodigo);

                $source = $procesosMec->devuelve_source($alumno['id'], $materia['id'], $this->periodoCodigo);

                if ($source['tipo_homolagacion'] == 'AREA') {
                    $notas = $sentenciasNotasAlumnos->get_nota_area($source['source_id'], $alumno['id'], $this->paralelo, $this->usuario);
                } else {
                    $notas = $sentenciasNotasAlumnos->get_nota_materia($source['grupo_id']);
                }


                if (isset($notas['final_total'])) {
                    $notasF = $notas['final_total'];
                } else {
                    $notasF = 0;
                }
//                    
//                    $nota = number_format($sentenciasNotas->truncarNota($notasF/$this->escala,2),2);
//                    $letras = $this->convertir_letras($notasF);
//
                $suma = $suma + $notasF;
                $cont++;
                if ($notasF < 7) {
                    $this->totalBajos++;
                }
//                    
                $html .= '<td class="bordesolido centrarTexto">' . number_format($notasF, 2) . '</td>';

                $letras = $sentenciasNotas->equivalencia_aprovechamiento($notasF);

                $html .= '<td class="bordesolido">' . $letras . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
            }
        }

        //para promedios

        $this->promedio = number_format($sentenciasNotas->truncarNota(($suma / $cont), 2), 2);

        //$letras = $this->convertir_letras($this->promedio);        
        $letras = $sentenciasNotas->equivalencia_aprovechamiento($this->promedio);



        $html .= '<td class="bordesolido" colspan="2"><strong>PROMEDIO GENERAL:</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>' . $this->promedio . '</strong></td>';
        $html .= '<td class="bordesolido"><strong>' . $letras . '</strong></td>';
        $html .= '</tr>';



        /*         * ***
         * para proyectos y comportamientos
         */

        $proyectosComp = new ComportamientoProyectos($alumno['id'], $this->paralelo);

        if ($this->tieneProyectos <> 0) {

            $proyectos = new MecProcesaMaterias();
            $proy = $proyectos->get_proyectos($alumno['id'], $this->paralelo, 'q1');


//            $notaP = $procesosMec->get_proyectos_mec($alumno['id'], $this->mallaMecId, 'q2', $this->paralelo);
//            
            $notaPTransformada = $this->transforma_proyectos($proy['q2']['abreviatura']);

            $html .= '<tr>';
            $html .= '<td class="bordesolido" colspan=""><strong>PROYECTOS ESCOLARES</strong></td>';
            $html .= '<td class="bordesolido" colspan=""><strong>PROYECTOS ESCOLARES</strong></td>';
            $html .= '<td class="bordesolido centrarTexto" colspan="">' . $proy['q2']['abreviatura'] . '</td>';
            $html .= '<td class="bordesolido" colspan="">' . $notaPTransformada . '</td>';
            $html .= '</tr>';
        }

        $notasC = $proyectosComp->arrayNotasComp;
//        print_r($notasC);
//        die();
        //$compNota = $sentenciasNotas->homologa_comportamiento_mec_x_abrev($notasC[0]['q2'], $this->modelParalelo->course->section0->code);
        $compNota = $this->transforma_comportamiento($notasC[0]['q2']);

        $html .= '<tr>';
        $html .= '<td class="bordesolido" colspan="2"><strong>COMPORTAMIENTO</strong></td>';
        $html .= '<td class="bordesolido centrarTexto">' . $notasC[0]['q2'] . '</td>';
        $html .= '<td class="bordesolido">' . $compNota . '</td>';
        $html .= '</tr>';


        return $html;
    }

    private function transforma_proyectos($nota) {
        
        $nota == 'EX' ? $nota = 'EXCELENTE' : $nota = $nota;
        $nota == 'B' ? $nota = 'BUENA' : $nota = $nota;
        $nota == 'MB' ? $nota = 'MUY BUENA' : $nota = $nota;
        $nota == 'R' ? $nota = 'REGULAR' : $nota = $nota;


        $modelHomologacion = ScholarisTablaEscalasHomologacion::find()->where([
                    'scholaris_periodo' => $this->periodoCodigo,
                    'corresponde_a' => 'PROYECTOS',
                    'nombre' => $nota
                ])->one();

        isset($modelHomologacion->nombre) ? $descripcion = $modelHomologacion->nombre : $descripcion = '-';
        return $descripcion;
    }
    
    private function transforma_comportamiento($nota) {
        

        $modelHomologacion = ScholarisTablaEscalasHomologacion::find()->where([
                    'scholaris_periodo' => $this->periodoCodigo,
                    'corresponde_a' => 'COMPORTAMIENTO',
                    'abreviatura' => $nota,
                    'section_codigo' => $this->modelParalelo->course->section0->code
                ])->one();        

        isset($modelHomologacion->nombre) ? $descripcion = $modelHomologacion->nombre : $descripcion = '-';
        return $descripcion;
    }
    
    
    

    private function get_materias_normales() {
        $sentenciasMec = new MecProcesaMaterias();
        $materia = $sentenciasMec->get_materias_mec_normales($this->mallaMecId);

        $this->arrayMaterias = $materia; //guarda las materias mec

        $areas = $sentenciasMec->get_areas_mec_normales($this->mallaMecId);

        $this->arrayAreas = $areas; // guarda las areas mec       
    }

    private function consecuencia($curso, $promedio) {

        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = \backend\models\OpInstitute::findOne($institutoId);

        $escala = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'scala'])->one();
        $modelNotaMinima = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $notaMinima = $modelNotaMinima->valor / $escala->valor;


        $modelCurso = \backend\models\OpCourse::findOne($curso);
        $modelRindeSup = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $curso])->one();

        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
                    'codigo_reporte' => 'MEC',
                    'template_id' => $modelCurso->x_template_id,
                    'instituto_id' => $institutoId
                ])->one();

        $html = '';
        $texto1 = '<br>Por lo tanto es promovido/a al ';
//        $texto2 = 'Para certificar suscriben en unidad de acto el /la  Rector/a con el/la Secretaria General del Plantel.';
        $texto2 = 'Para certificar suscriben en unidad de acto el /la ' . ucfirst(strtolower($modelFirmas->principal_cargo)) . ' con el/la Secretaria General del Plantel.';

        $textoPierde = '<br>Por lo tanto no es promovido/a al siguiente nivel';

        if ($modelRindeSup->rinde_supletorio == 1) {
            if ($this->totalBajos > 0) {
                $html .= '<div class="tamano12">' . $textoPierde . '</div>' . '<div class="tamano12">' . $texto2 . '</div>';
            } else {
                if ($modelCurso->xTemplate->next_course_id != null) {
                    $modelCursoNuevo = \backend\models\OpCourseTemplate::findOne($modelCurso->xTemplate->next_course_id);



                    if ($modelCursoNuevo) {
                        $html .= '<div class="tamano12">' . $texto1 . $modelCursoNuevo->name . '.</div>' . '<div class="tamano12">' . $texto2 . '</div>';
                    } else {
                        $html .= '<div class="tamano12"></div>';
                    }
                } else {
                    $html .= '<div class="tamano12">' . $texto2 . '</div>';
                }
            }
        } else {
            if ($promedio < $notaMinima) {
//                echo $notaMinima;
//                die();
                $html .= '<div class="tamano12">' . $textoPierde . '</div>' . '<div class="tamano12">' . $texto2 . '</div>';
            } else {
                $modelCursoNuevo = \backend\models\OpCourseTemplate::findOne($modelCurso->xTemplate->next_course_id);


                if ($modelCursoNuevo) {
                    $html .= '<div class="tamano12">' . $texto1 . $modelCursoNuevo->name . '.</div>' . '<div class="tamano12">' . $texto2 . '</div>';
                } else {
                    $html .= '<div class="tamano12"></div>';
                }
            }
        }

        $modelFechaCierre = \backend\models\ScholarisFechasCierreAnio::find()->where([
                    'scholaris_periodo_id' => $this->periodoId
                ])->one();

        if ($modelFechaCierre) {
            $fechaHoy = 'el ' . $modelFechaCierre->fecha;
        } else {
            $fechaHoy = $this->fecha_hoy();
        }

        $html .= '<div class="tamano12">Dado y firmado en: ' . $modelInstituto->store->company->partner->city . ', '
                . $modelInstituto->store->company->partner->state->name . ' ' . $this->fecha() . '</div>';

        $html .= '<br><br><br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelFirmas->principal_nombre . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelFirmas->secretaria_nombre . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelFirmas->principal_cargo . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelFirmas->secretaria_cargo . '</strong></td>';
        $html .= '</tr>';

        $html .= '</table>';


        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';

        $html .= '<table width="100%" cellpadding="0" cellspacing="0" class="tamano8" border="">';
        $html .= '<tr>';
        $html .= '<td width="33%">'
                . 'Dirección: Av. Amazonas N34-451 y Av. Atahualpa<br>'
                . 'Código postal: 170507 / Quito - Ecuador<br>'
                . 'Teléfono: 593-2-396-1300 / www.educacion.gob.ec'
                . '</td>';
        $html .= '<td width="33%"></td>';
        $html .= '<td width="34%" align="right"><img src="imagenes/instituto/mec/sellopromo2.png" width="200px"></td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    
    private function fecha(){
        $fecha = ScholarisParametrosOpciones::find()->where(['codigo' => 'fechapromo'])->one();
        
        if(isset($fecha->valor)){
            $fe = $fecha->valor;
        }else{
            $fe = 'CONGIGURAR LA FECHA EN LOS PARAMETROS CON EL Código fechapromo';
        }
        return $fe;
        
        
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

    private function convertir_letras($nota) {

        if ($nota) {
            $sentencias = new \backend\models\SentenciasMecNormales();

            $cadenaNumero = explode(".", $nota);
            $entero = $cadenaNumero[0];

            isset($cadenaNumero[1]) ? $decimal = $cadenaNumero[1] : $decimal = 0;

            //$decimal = $cadenaNumero[1];

            $entero = $sentencias->numToLetras($entero);
            $decimal = $sentencias->decimalToLetras($decimal);


            $res = $entero . ' COMA ' . $decimal;
            return $res;
        } else {
            return 'No se cerró el proceso en esta materia';
        }
    }

}
