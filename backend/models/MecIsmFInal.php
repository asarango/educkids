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
class MecIsmFInal extends \yii\db\ActiveRecord {

    private $paralelo;
    private $modelParalelo;
    private $periodoId;
    private $periodoCodigo;
    private $tieneProyectos = 0;
    private $rindeSupletorio = 0;
    private $modelAlumnos;
    private $usuario;
    private $modelBloquesQ1;
    private $seccion;
    private $comportamientoAutomatico = 0;
    private $tipoCalificacionProyectos = 'PROYECTOSNORMAL';
    private $tipoCalificacion;
    private $mallaMecId;
    private $arrayMaterias;
    private $escala;
    private $totalBajos = 0;
    private $notaMinima = 7;

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
        $modelTipoCalificacion = ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->valor;
        //////// fin de tipo de calificacion /////////////

        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $this->modelParalelo->course->section0->code;


        $modelMalla = ScholarisMecV2MallaCurso::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->mallaMecId = $modelMalla->malla_id;


        $this->tieneProyectos = $this->tiene_proyectos(); //llama a funcion para buscar si tiene proyectos
        $this->rinde_supletorios(); //llama a funcion para buscar si tiene proyectos

        $this->usuario = Yii::$app->user->identity->usuario;  //usuario que esta con login

        $this->modelAlumnos = $sentencias->get_alumnos_paralelo_todos($paralelo); // toma estudiantes del paralelo

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

    private function rinde_supletorios() {
        $cursoId = $this->modelParalelo->course_id;
        $rindeModelo = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $cursoId])->one();
        $this->rindeSupletorio = $rindeModelo->rinde_supletorio;
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
            'format' => 'A4-L',
            'margin_left' => 40,
            'margin_right' => 10,
            'margin_top' => 35,
            'margin_bottom' => 0,
            'margin_header' => 20,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera();
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->estilos();
        $html .= $this->genera_cuerpo('CUADRO FINAL CON SUPLETORIO', 'final_total', 'supletorio');
        $mpdf->WriteHTML($html);
        //$mpdf->addPage();            
        //$mpdf->SetFooter($pie);

        $mpdf->Output('MEC-Quimestrales' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        
        
        $modelTituloUnoDistrito = ScholarisParametrosOpciones::find()->where(['codigo' => 'tit1mec'])->one();
        isset($modelTituloUnoDistrito->valor) ? $titulo1 = $modelTituloUnoDistrito->valor : $titulo1 = 'Configurar en parámetros: parametro id = 10, codigo = tit1mec, Nombre=título uno de certificados mec, valor=nombre del distrito';
        
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="10%"></td>';

        $html .= '<td class="centrarTexto tamano10">';
        $html .= '<strong>'.$titulo1.'<br>';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '</td>';

        $html .= '<td align="right" width="10%"><img src="imagenes/instituto/mec/sellopromo1.png" width="150"></td>';
        $html .= '</tr>';
        $html .= '</table>';
//        $html .= '<hr>';

        return $html;
    }

    private function estilos() {
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
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

        return $html;
    }

    private function genera_cuerpo($titulo, $quimestre, $extra) {

        $html = '';
        $html .= '<table class="tamano8 centrarTexto" width="100%">';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<strong>' . $titulo . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO ' . $this->periodoCodigo . '</strong><br>';
        $html .= '<strong>JORNADA MATUTINA</strong>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<p class="tamano8">' . $this->modelParalelo->course->xTemplate->name . ' "' . $this->modelParalelo->name . '"</p>';

        $html .= $this->procesa_asignaturas($quimestre, $extra);

        return $html;
    }

    private function firmas() {
        $institutoId = Yii::$app->user->identity->instituto_defecto;
//        $modelInstituto = OpInstitute::findOne($institutoId);
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
                    'codigo_reporte' => 'MEC',
                    'template_id' => $this->modelParalelo->course->xTemplate->id,
                    'instituto_id' => $institutoId
                ])->one();

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
        $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelFirmas->principal_nombre . '</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelFirmas->secretaria_nombre . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>'.$modelFirmas->principal_cargo.'</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>'.$modelFirmas->secretaria_cargo.'</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

//            $html .= '<div class="centrarTexto"><img src="imagenes/instituto/logo/sellolibreta.png" width="100px"></div>';


        return $html;
    }

    private function procesa_asignaturas($quimestre, $extra) {

        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="2" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td text-rotate="90" class="bordesolido centrarTexto" rowspan="2"><strong>ORD</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" width="200px" rowspan="2"><strong>NOMBRES Y APELLIDOS</strong></td>';

        foreach ($this->arrayMaterias as $mat) {
            $html .= '<td align="center" class="bordesolido tamano6" colspan="3" width="50px">' . $mat['nombre'] . '</td>';
        }

        if ($this->tieneProyectos != 0) {
            $html .= '<td height="10" align="center" class="bordesolido tamano6" rowspan="2" width="50px"><strong>PROYECTOS ESCOLARES</strong></td>';
        }
        $html .= '<td height="10" align="center" class="bordesolido tamano6" rowspan="2" width="50px"><strong>COMPORTA - MIENTO</strong></td>';
        $html .= '<td height="10" align="center" class="bordesolido tamano6" rowspan="2" width="50px"><strong>PROMEDIO</strong></td>';
        $html .= '<td height="10" align="center" class="bordesolido tamano6" rowspan="2" width="50px">OBSERVACIÓN</td>';

        $html .= '</tr>';

        $html .= '<tr>';
        foreach ($this->arrayMaterias as $mat) {
            $html .= '<td align="center" class="bordesolido tamano6" colspan="">PQ</td>';
            $html .= '<td align="center" class="bordesolido tamano6" colspan="">ES</td>';
            $html .= '<td align="center" class="bordesolido tamano6" colspan=""> PF </td>';
        }
        $html .= '</tr>';

        $i = 0;
        foreach ($this->modelAlumnos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>' . $i . '</strong></td>';
            $html .= '<td height="10" class="bordesolido tamano8"><strong>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</strong></td>';

            /// ********  PROCESAMIENTO DE NOTAS ***********/
            if($alumno['inscription_state'] == 'M'){
                $html .= $this->procesa_notas($alumno, $quimestre, $extra);
            }else{
                for($ii=0; $ii < count($this->arrayMaterias); $ii++){
                    $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>-</strong></td>';
                    $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>-</strong></td>';
                    $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>-</strong></td>';
                }
                if($this->tieneProyectos > 0){
                    $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>-</strong></td>';
                }
                
                $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>-</strong></td>';
                $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>-</strong></td>';
                $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>RETIRADO</strong></td>';
                
            }
            

            $html .= '</tr>';
        }
        $html .= '</table>';

        $html .= $this->firmas();

        return $html;
    }

    private function get_materias_normales() {
        $sentenciasMec = new MecProcesaMaterias();
        $materia = $sentenciasMec->get_materias_mec_normales($this->mallaMecId);
        $this->arrayMaterias = $materia;
    }

    private function procesa_notas($arrayAlumno, $quimestre, $extra) {
        
         $alumnoId = $arrayAlumno['id'];
        $sentenciasMec = new MecProcesaMaterias();
        $sentenciasNotas = new Notas();

        $html = '';

        $suma = 0;
        $cont = 0;
        $this->totalBajos = 0;
        
//        echo '<pre>';
//        print_r($this->arrayMaterias);
//        die();

        foreach ($this->arrayMaterias as $materia) {
            $notas = $sentenciasMec->get_nota( $materia['id'],
                                               $alumnoId, $this->tipoCalificacion, 
                                               $this->paralelo, $this->usuario,
                                               $this->periodoCodigo);

//            if($materia['id'] == 242){
//                echo '<pre>';
//                print_r($notas);
//                print_r($notas['final_con_mejora']);
//                die();
//            }
            
            if (isset($notas[$quimestre])) {
                $notas[$quimestre] = $notas[$quimestre];
            } else {
                $notas[$quimestre] = 0;
            }

            if (isset($notas['final_con_mejora'])) {
                $notas['final_con_mejora'] = $notas['final_con_mejora'];
            } else {
                $notas['final_con_mejora'] = 0;
            }

            if (isset($notas[$extra])) {
                $notas[$extra] = $notas[$extra];
            } else {
                $notas[$extra] = 0;
            }

            $notaFAnio = number_format($sentenciasNotas->truncarNota($notas['final_con_mejora'] / $this->escala, 2), 2);
            $notaExtra = number_format($sentenciasNotas->truncarNota($notas[$extra] / $this->escala, 2), 2);
            $notaFInal = number_format($sentenciasNotas->truncarNota($notas[$quimestre] / $this->escala, 2), 2);

            if ($notaExtra == 0) {
                $notaExtra = '';
            } elseif($notaExtra >= $this->notaMinima) {
                $notaFInal = number_format($this->notaMinima, 2);
            }else{
                $notaFInal = number_format($notaFAnio, 2);
            }


            if ($notaFInal < $this->notaMinima) {
                $this->totalBajos++;
            }

            $suma = $suma + $notaFInal;
            $cont++;
            $html .= '<td height="10" align="center" class="bordesolido tamano8">&nbsp;' . $notaFAnio . '&nbsp;</td>';
            $html .= '<td height="10" align="center" class="bordesolido tamano8">&nbsp;' . $notaExtra . '&nbsp;</td>';
            $html .= '<td height="10" align="center" class="bordesolido tamano8">&nbsp;' . $notaFInal . '&nbsp;</td>';
        }


        /*         * ************   INICIA PROYECTOS ************ */

        if ($this->tieneProyectos > 0) {
            
            $proyectos = new MecProcesaMaterias();
            $proys = $proyectos->get_proyectos($alumnoId, $this->paralelo, 'q1');
                      

//            if ($quimestre == 'QUIMESTRE I') {
//                $quimestre = 'q1';
//            } else {
//                $quimestre = 'q2';
//            }
//            $proyectosComp = new ComportamientoProyectos($alumnoId, $this->paralelo);
//            //$notasP = $proyectosComp->arrayNotasProy;
//
//            $notaP = $sentenciasMec->get_proyectos_mec($alumnoId, $this->mallaMecId, $quimestre, $this->paralelo);
            $html .= '<td height="10" align="center" class="bordesolido tamano6">' . $proys['q2']['abreviatura'] . '</td>';
        }
        ///////////// FIN DE PROYECTOS ////////////////////

        /*         * ************   INICIA COMPORTAMIENTO ************ */
        $notaC = $sentenciasMec->get_comportamiento($alumnoId, $this->paralelo, 'q2');
        $html .= '<td height="10" align="center" class="bordesolido tamano8">' . $notaC . '</td>';
        ///////////// FIN DE COMPORTAMIENTO ////////////////////


        /*         * ******* aprovechamiento ********* */
        $promedio = number_format($sentenciasNotas->truncarNota($suma / $cont, 2), 2);


        if ($this->rindeSupletorio == 1) {
            if ($this->totalBajos > 0) {
                $promedio = '-';
            }

            if ($promedio == '-') {
                $observacion = 'REMEDIAL';
            } else {
                $observacion = '';
            }
        }else{
            $observacion = '';
        }




        $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>' . $promedio . '</strong></td>';
        
        
        if($arrayAlumno['inscription_state'] == 'R'){
            $observacionEstado = 'RETIRADO';
        }else{
            $observacionEstado = $observacion;
        }
        
        $html .= '<td height="10" align="center" class="bordesolido tamano8"><strong>' . $observacionEstado . '</strong></td>';
        //////////////////////////////////////

        return $html;
    }

}
