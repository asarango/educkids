<?php

namespace backend\models\pudpai;

use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\models\PlanificacionVerticalPaiDescriptores;
use backend\models\PlanificacionVerticalPaiOpciones;
use backend\models\PudPai;
use backend\models\pudpep\DatosInformativos;
use backend\models\ScholarisPeriodo;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

class Pdf extends \yii\db\ActiveRecord {

    private $planUnidad;
    private $pudPai;

    public function __construct($planUnidadId) {
        $this->planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $this->pudPai = PudPai::find()->where([
                    'planificacion_bloque_unidad_id' => $planUnidadId
                ])
                ->orderBy('seccion_numero')
                ->all();

        $this->generate_pdf();
    }

    private function generate_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 35,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $cabecera = $this->cabecera();
        //$pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpo();
        $mpdf->WriteHTML($html);
        // $mpdf->addPage();
//        $mpdf->addPage();
        //$mpdf->SetFooter($pie);

        $mpdf->Output('Planificacion-de-unidad' . '.pdf', 'D');
        exit;
    }

    private function cabecera() {
        $html = '';
        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="60px"></td>';
        $html .= '<td class="border" align="center" width=""></td>';
        $html .= '<td class="border" align="right" width="20%">
                    Código: ISMR20-22 <br>
                    Versión: 5.0<br>
                    Fecha: 28/09/021<br>
                    Página: {PAGENO} / {nb}<br>
                  </td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }

    private function cuerpo() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::findOne($periodoId);

        $html = $this->estilos();

        $html .= '<table width="100%" cellspacing="0" cellpadding="10">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>ISM</b> <br> International Scholastic Model</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>PLAN DE UNIDAD PAI</b> <br> AÑO ESCOLAR ' . $periodo->codigo . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border" align=""><b>1.- DATOS INFORMATIVOS</b></td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= $this->uno();
        $html .= $this->dos();
        $html .= $this->tres();
        $html .= $this->cuatro();
        $html .= $this->cinco();
        $html .= $this->seis();
        $html .= $this->siete();
        $html .= $this->ocho();
        $html .= $this->nueve();
        $html .= $this->diez();

        return $html;
    }

    private function uno() {

        $datos = new Datos($this->planUnidad->id);
        //$tiempo = $datos->calcula_horas($this->planUnidad->planCabecera->scholaris_materia_id,
        $tiempo = $datos->calcula_horas($this->planUnidad->planCabecera->ismAreaMateria->materia_id,
                $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id);

        $docentes = $datos->get_docentes();

        $html = '';
        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" align="" width="10%"><b>GRUPO DE ASIGNATURAS Y DISCIPLINA: </b></td>';
        $html .= '<td class="border" align="center">';
        $html .= $this->planUnidad->planCabecera->ismAreaMateria->materia->nombre;
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>UNIDAD Nº: </b>';
        $html .= '<td class="border" align="center">' . $this->planUnidad->curriculoBloque->last_name . '</td>';
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>AÑO DEL PAI: </b>';
        $html .= '<td class="border" align="center">' . $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . '</td>';
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>FECHA DE INICIO: </b>';
        $html .= '<td class="border" align="center">' . $tiempo['fecha_inicio'] . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center" width="10%"><b>PROFESOR: </b>';
        $html .= '<td class="border" align="center">';
        foreach ($docentes as $docente) {
            $html .= $docente['docente'] . ' | ';
        }
        $html .= '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>TÍTULO DE LA UNIDAD Nº: </b>';
        $html .= '<td class="border" align="center">' . $this->planUnidad->unit_title . '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>DURACIÓN DE LA UNIDAD (EN HORAS): </b>';
        $html .= '<td class="border" align="center">' . $tiempo['horas'] . '</td>';
        $html .= '<td class="border" align="center" width="10%"><b>FECHA FINALIZACIÓN </b>';
        $html .= '<td class="border" align="center">' . $tiempo['fecha_final'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function dos() {

        $conceptos = PlanificacionVerticalPaiOpciones::find()->where([
                    'plan_unidad_id' => $this->planUnidad->id
                ])
                ->orderBy('tipo', 'contenido')
                ->all();

        $html = '';
        $html .= '<table class="" width="100%" cellspacing="0" cellpadding="5">';
        $html .= '<tr>';
        $html .= '<td class="border" align=""><b>2.- INDAGACIÓN: ESTABLECIMIENTO DEL PROPÓSITO DE LA UNIDAD</b></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" align="center" width="33%"><b>CONCEPTOS CLAVE</b></td>';
        $html .= '<td class="border" align="center" width="33%"><b>CONCEPTO(S) RELACIONADO(S)</b></td>';
        $html .= '<td class="border" align="center" width="34%"><b>CONCEPTO GLOBAL Y EXPLORACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center">';
        $html .= '<ul>';
        foreach ($conceptos as $clave) {
            if ($clave->tipo == 'concepto_clave') {
                $html .= '<li>';
                $html .= $clave->contenido;
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</td>';

        $html .= '<td class="border" align="center">';
        $html .= '<ul>';
        foreach ($conceptos as $clave) {
            if ($clave->tipo == 'concepto_relacionado') {
                $html .= '<li>';
                $html .= $clave->contenido;
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</td>';

        $html .= '<td class="border" align="center">';
        $html .= '<ul>';
        foreach ($conceptos as $clave) {
            if ($clave->tipo == 'contexto_global') {
                $html .= '<li>';
                $html .= $clave->contenido;
                $html .= '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</td>';

        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" style="color: #65b2e8"><b style="color: #000">ENUNCIADO DE LA INDAGACIÓN: </b>(expresa claramente una comprensión conceptual importante que tiene un profundo significado y un valor a largo plazo para los alumnos. Incluye claramente un concepto clave, conceptos relacionados y una exploración del contexto global específica, que da una perspectiva creativa y compleja del mundo real; describe una comprensión transferible y a la vez importante para la asignatura; establece un propósito claro para la indagación).</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="border">' . $this->planUnidad->enunciado_indagacion . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $preguntas = PudPai::find()->where([
                    'in', 'tipo', ['facticas', 'conceptuales', 'debatibles']
                ])
                ->andWhere([
                    'planificacion_bloque_unidad_id' => $this->planUnidad->id
                ])
                ->all();

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="2"><b style="color: #000">PREGUNTAS DE INDAGACIÓN: </b>(inspiradas en el enunciado de indagación. Su fin es explorar el enunciado en mayor detalle. Ofrecen andamiajes).</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" width="20%"><b style="color: #000">Fácticas: </b>(se basan en conocimientos y datos, ayudan a comprender terminología del enunciado, facilitan la comprensión, se pueden buscar)</td>';
        $html .= '<td class="border">';
        $html .= $this->dos_recorre_preguntas($preguntas, 'facticas');
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" width="20%"><b style="color: #000">Conceptuales: </b>(conectar los datos, comparar y contrastar, explorar contradicciones, comprensión  más  profunda,  transferir  a  otras situaciones, contextos e ideas, analizar y aplicar)</td>';
        $html .= '<td class="border">';
        $html .= $this->dos_recorre_preguntas($preguntas, 'conceptuales');
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" width="20%"><b style="color: #000">Debatibles: </b>(promover la discusión, debatir una posición, explorar cuestiones importantes desde múltiples perspectivas, 
        deliberadamente polémicas, presentar tensión, evaluar)
        </td>';
        $html .= '<td class="border">';
        $html .= $this->dos_recorre_preguntas($preguntas, 'debatibles');
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function dos_recorre_preguntas($preguntas, $tipo) {
        $html = '';

        foreach ($preguntas as $pregunta) {
            if ($pregunta->tipo == $tipo) {
                $html .= $pregunta->contenido . ' | ';
            }
        }

        return $html;
    }

    public function tres() {

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="3"><b>3. EVALUACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="1"><b style="color: #000">OBJETVOS ESPECÍFICOS Y ASPECTOS: </b><br>(copiar la redacción tal como aparece en la guía  de  la  asignatura,  para  cada  año  del PAI)</td>';
        $html .= '<td class="border colorAyudas" colspan="2"><b style="color: #000">OBJETVOS ESPECÍFICOS: </b>(se explica claramente qué harán los alumnos para demostrar lo que saben, lo que comprenden y lo que  pueden hacer; permite demostrar comprensión de los conceptos, la relación conceptual y el contexto que se describen en el enunciado de la indagación; permite demostrar objetivos y aspectos escogidos para  la  unidad;  utiliza  términos  de  instrucción  correctos para  ese  año  del  PAI,  permite  a  los  alumnos  demostrar  los  descriptores  de  todos  los  niveles  de  logro;  es estimulante pero accesible; permite a los alumnos comunicar lo que saben, lo que comprenden y lo que pueden hacer de maneras múltiples y abiertas; permite aplicar lo que han aprendido a una variedad de situaciones auténticas o situaciones que simulan el mundo real).</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border">' . $this->consulta_sumativas() . '</td>';

        $html .= '<td class="border" valign="top">';
        $html .= '<p class="colorAyudas">Resumen de las tareas de evaluación sumativa y criterios de evaluación correspondientes:</p><br>';
        $html .= $this->evaluacion_sumativas();
        $html .= '</td>';

        $html .= '<td class="border" valign="top">';
        $html .= '<p class="colorAyudas">Relación entre las tareas de evaluación sumativa y el enunciado de la indagación::</p><br>';
        $html .= $this->evaluacion_sumativas2();
        $html .= '</td>';

        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function consulta_sumativas() {
        $objetivos = PlanificacionVerticalPaiDescriptores::find()
                ->innerJoin('scholaris_criterio_descriptor d', 'd.id = planificacion_vertical_pai_descriptores.descriptor_id')
                ->innerJoin('scholaris_criterio c', 'c.id = d.criterio_id')
                ->where([
                    'plan_unidad_id' => $this->planUnidad->id
                ])
                ->orderBy('c.criterio')
                ->all();

        $html = '';
        $html .= '<ul>';
        foreach ($objetivos as $obj) {
            $html .= '<li>' . '<b>CRITERIO ' . $obj->descriptor->criterio->criterio . ': </b> ' . $obj->descriptor->descricpcion . '</li>';
            $html .= '<br>';
        }
        $html .= '</ul>';
        return $html;
    }

    private function evaluacion_sumativas() {
        $planUnidadId = $this->planUnidad->id;

        $con = Yii::$app->db;
        $query = "select 	p.id
                            ,c.criterio 
                            ,p.titulo 
                            ,p.contenido 
                    from 	pud_pai p
                            inner join scholaris_criterio c on c.id = p.criterio_id 
                    where 	p.tipo = 'eval_sumativa'
                            and p.planificacion_bloque_unidad_id = $planUnidadId 
                    order by c.criterio;";
        $sumativas = $con->createCommand($query)->queryAll();

        $html = '';
        $html .= '<ul>';
        foreach ($sumativas as $sumativa) {
            if ($sumativa['contenido'] == 'sin contenido') {
                $color = 'red';
                $titulo = 'SIN TITULO';
            } else {
                $color = '';
                $titulo = $sumativa['titulo'];
            }

            $html .= '<div class="" style="color: ' . $color . '">';
            //$html .= $this->modal_sumativa($sumativa['id'], $sumativa['contenido'], $titulo);
            $html .= '<b>Criterio ' . $sumativa['criterio'] . '</b>: ' . $titulo . '<br>';
            $html .= $sumativa['contenido'] . '<br>';
            $html .= '</div>';
        }
        $html .= '</ul>';
        return $html;
    }

    private function evaluacion_sumativas2() {
        $planUnidadId = $this->planUnidad->id;

        $model = PudPai::find()->where([
                    'planificacion_bloque_unidad_id' => $planUnidadId,
                    'tipo' => 'relacion-suma-eval'
                ])->one();

        $html = '';
        $html .= '<p>';
        $html .= $model->contenido;
        $html .= '</p>';

        return $html;
    }

    private function cuatro() {

        $aspectosClass = new Aspecto($this->planUnidad->id);
        $indicadoresClass = new Indicadores($this->planUnidad->id);

        $habilidades = $this->cuatro_get_hablidades();
        $aspectos = $aspectosClass->get_hablidades();
        $indicadores = $indicadoresClass->get_hablidades();

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="6"><b>4. ENFOQUES DE APRENDIZAJE</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="16%" align="center" class="border"><b>CATEGORÍA DE HABILIDADES</b></td>';
        $html .= '<td width="16%" align="center" class="border"><b>COMUNICACIÓN</b></td>';
        $html .= '<td width="16%" align="center" class="border"><b>SOCIALES</b></td>';
        $html .= '<td width="16%" align="center" class="border"><b>AUTOGESTIÓN</b></td>';
        $html .= '<td width="16%" align="center" class="border"><b>INVESTIGACIÓN</b></td>';
        $html .= '<td width="17%" align="center" class="border"><b>PENSAMIENTO</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>Grupo de habilidades</b></td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE COMUNICACIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE SOCIALES') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE AUTOGESTIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE INVESTIGACIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($habilidades, 'HABILIDADES DE PENSAMIENTO') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>Aspecto del Objetivo</b></td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE COMUNICACIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE SOCIALES') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE AUTOGESTIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE INVESTIGACIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($aspectos, 'HABILIDADES DE PENSAMIENTO') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>Indicadores de la habilidad</b></td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE COMUNICACIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE SOCIALES') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE AUTOGESTIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE INVESTIGACIÓN') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_habilidades($indicadores, 'HABILIDADES DE PENSAMIENTO') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>Cómo se enseñará explícitamente la habilidad (Actividades)</b></td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_comunicacion') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_sociales') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_autogestion') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_investigacion') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'ensenara_pensamiento') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>Perfil BI</b></td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'comunicacion') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'social') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'autogestion') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'investigacion') . '</td>';
        $html .= '<td class="border">' . $this->cuatro_busca_tipos_planificacion($this->pudPai, 'pensamiento') . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function cuatro_busca_tipos_planificacion($model, $tipo) {
        $html = '<ul>';
        foreach ($model as $data) {
            if ($data->tipo == $tipo) {
                $html .= $data->contenido . '<br>';
            }
        }
        $html .= '<ul>';

        return $html;
    }

    private function cuatro_busca_habilidades($habilidades, $tipo) {
        $html = '<ul>';
        foreach ($habilidades as $habilidad) {
            if ($habilidad['es_titulo1'] == $tipo) {
                $html .= '<li>' . $habilidad['contenido'] . '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }

    private function cuatro_get_hablidades() {
        $planUnidadId = $this->planUnidad->id;
        $con = Yii::$app->db;
        $query = "select 	h.es_titulo2  as contenido
                      ,h.es_titulo1 
                  from 	planificacion_vertical_pai_opciones op 
                      inner join contenido_pai_habilidades h on h.es_exploracion = op.contenido 
                  where 	op.plan_unidad_id = $planUnidadId
                  group  by h.es_titulo2,h.es_titulo1 
                  order by h.es_titulo2;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function cinco() {

        $contenidos = PlanificacionBloquesUnidadSubtitulo::find()
                ->where(['plan_unidad_id' => $this->planUnidad->id])
                ->orderBy('orden')
                ->all();

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border" colspan="4"><b>5. ACCIÓN: ENSEÑANZA Y APRENDIZAJE A TRAVÉS DE LA INDAGACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="colorAyudas border"><b style="color: #000">CONTENIDOS: </b>copiar OA de MINEDUC. Incluir las habilidades, los conocimientos disciplinarios y los conceptos clave y relacionados elegidos para la unidad.</td>';
        $html .= '<td class="colorAyudas border"><b style="color: #000">EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA: </b>variedad que abarque el espectro de preferencias de los alumnos. Basadas en los conocimientos previos y en la indagación. (Todas las actividades a realizar en clase o para la casa)</td>';
        $html .= '<td class="colorAyudas border"><b style="color: #000">EVALUACIÓN FORMATIVA: </b>genera evidencia de avance y ofrece oportunidades variadas de practicar, de hacer comentarios detallados y adaptar la enseñanza planificada. Incluye autoevaluación y coevaluación. Se deben ofrecer comentarios sobre el avance en el desarrollo de habilidades.</td>';
        $html .= '<td class="colorAyudas border"><b style="color: #000">DIFERENCIACIÓN: </b>de contenido, de proceso (cómo se enseñará y se aprenderá) y de producto (lo que se evaluará). Definir las actividades correspondientes a los 3 diferentes estilos de aprendizaje más reconocidos: VISUAL, KINESTÉSICO, AUDITIVO.</td>';
        $html .= '</tr>';

        foreach ($contenidos as $contenido) {
            $html .= '<tr>';
            $html .= '<td class="border">' . $contenido->subtitulo . '</td>';
            $html .= '<td class="border">' . $contenido->experiencias . '</td>';
            $html .= '<td class="border">' . $contenido->evaluacion_formativa . '</td>';
            $html .= '<td class="border">' . $contenido->diferenciacion . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

    public function seis() {

        $categorias = $this->get_categoria($this->planUnidad->id);
        $acciones = \backend\models\PudPaiServicioAccion::find()->where([
                    'planificacion_bloque_unidad_id' => $this->planUnidad->id
                ])->all();

        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="6"><b style="color: #000">6.	SERVICIO COMO ACCIÓN: </b>(Los tipos de acción son Servicio Directo, Servicio Indirecto, Promoción de una causa, Investigación, etc.)</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center" rowspan="2"><b>TIPOS DE ACCION</b></td>';
        $html .= '<td class="border" align="center" rowspan="2"><b>ACTIVIDAD DE ACCIÓN</b></td>';
        $html .= '<td class="border" align="center" colspan="4"><b>SITUACIONES DE APRENDIZAJE</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>PRESENCIAL</b></td>';
        $html .= '<td class="border" align="center"><b>EN LÍNEA</b></td>';
        $html .= '<td class="border" align="center"><b>COMBINADO</b></td>';
        $html .= '<td class="border" align="center"><b>REMOTO</b></td>';
        $html .= '</tr>';

        foreach ($categorias as $cat) {
            $categ = $cat['categoria'];
            $html .= '<tr>';
            $html .= '<td class="border" align="center">' . $cat['categoria'] . '</td>';
            $html .= '<td class="border" align="center">';
            foreach ($acciones as $acc) {
                if ($acc->opcion->categoria == $cat['categoria']) {
                    $html .= '<lu>';
                    $html .= '<li>' . $acc->opcion->opcion . '</li>';
                    $html .= '</lu>';
                }
            }

            $html .= '</td>';

            $presencial = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'presencial');
            $html .= '<td align="center" class="border">';
            if (!$presencial) {
                $html .= '<i style="color: #ab0a3d"></i>';
            } else {
                $html .= '<i style="color: green">X</i>';
            }
            $html .= '</td>';

            $enLinea = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'en_linea');
            $html .= '<td align="center" class="border">';
            if (!$enLinea) {
                $html .= '<i style="color: #ab0a3d"></i>';
            } else {
                $html .= '<i style="color: green">X</i>';
            }
            $html .= '</td>';

            $combinado = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'combinado');
            $html .= '<td align="center" class="border">';
            if (!$combinado) {
                $html .= '<i style="color: #ab0a3d"></i>';
            } else {
                $html .= '<i style="color: green">X</i>';
            }
            $html .= '</td>';

            $remoto = $this->get_situacion_aprendizaje($this->planUnidad->id, $categ, 'remoto');
            $html .= '<td align="center" class="border">';
            if (!$remoto) {
                $html .= '<i style="color: #ab0a3d"></i>';
            } else {
                $html .= '<i style="color: green">X</i>';
            }
            $html .= '</td>';

            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

    private function get_categoria($planUnidadId) {
        $con = \Yii::$app->db;
        $query = "select 	op.categoria 
                    from 	pud_pai_servicio_accion p
                                    inner join planificacion_opciones op on op.id = p.opcion_id
                    where 	p.planificacion_bloque_unidad_id = $planUnidadId
                    group by op.categoria;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_situacion_aprendizaje($planUnidadId, $categoria, $opcion){
        $model = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => $categoria,
            'contenido' => $opcion
        ])->one();
        
        if($model){
            return $model->id;
        }else{
            return false;
        }
    }
    
    

    public function siete() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="2"><b style="color: #000">7. ATENCIÓN A LAS NECESIDADES EDUCATIVAS ESPECIALES: </b>(Detalle  las estrategias de trabajo a realizar para cada caso, las especificadas por el Tutor Psicólogo  y las propias de su asignatura o enseñanza)</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="border" align="center"><b>GRADO 1</b></td>';
        $html .= '<td class="border"></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>GRADO 2</b></td>';
        $html .= '<td class="border"></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" align="center"><b>GRADO 3</b></td>';
        $html .= '<td class="border"></td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    public function ocho() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="2"><b style="color: #000">8. RECURSOS: </b>En esta sección especificar claramente cada recurso que se utilizará. Podría mejorarse incluyendo recursos que pudieran utilizarse para llevar a cabo la diferenciación, así como también agregando, por ejemplo, oradores y entornos que pudieran generar mayor profundidad en el trabajo reflexivo sobre el enunciado de la unidad.</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="20%" class="border"><b>BIBLIOGRÁFICO: </b></td>';
        $html .= '<td class="border">' . $this->busca_pud_pai('bibliografico') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>TECNOLÓGICO: </b></td>';
        $html .= '<td class="border">' . $this->busca_pud_pai('tecnologico') . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border"><b>OTROS: </b></td>';
        $html .= '<td class="border">' . $this->busca_pud_pai('otros') . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    public function nueve() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="2"><b style="color: #000">9. REFLEXIÓN: </b>(Consideración de la planificación, el proceso y el impacto de la indagación. En el proceso de reflexión, garantizar dar respuesta a varias de la preguntas planteadas en cada momento.)</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="border"><b>ANTES DE ENSEÑAR LA UNIDAD</b></td>';
        $html .= '<td width="33%" class="border"><b>MIENTRAS SE ENSEÑA LA UNIDAD</b></td>';
        $html .= '<td width="34%" class="border"><b>DESPUÉS DE ENSEÑAR LA UNIDAD</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="border" valign="top">' . $this->busca_pud_pai('antes') . '</td>';
        $html .= '<td class="border" valign="top">' . $this->busca_pud_pai('mientras') . '</td>';
        $html .= '<td class="border" valign="top">' . $this->busca_pud_pai('despues') . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function busca_pud_pai($tipo) {
        $html = '';
        foreach ($this->pudPai as $pud) {
            if ($pud->tipo == $tipo && ($tipo == 'antes' || $tipo == 'mientras' || $tipo == 'despues')) {
                $html .= '<b><u>' . $pud->contenido . '</u></b><br>';
                $html .= $pud->respuesta . '<br>';
            } elseif ($pud->tipo == $tipo) {
                $html .= $pud->contenido;
            }
        }

        return $html;
    }

    public function diez() {
        $html = '';

        $html .= '<table class="tamano10" width="100%" cellspacing="0" cellpadding="2">';
        $html .= '<tr>';
        $html .= '<td class="border colorAyudas" colspan="3"><b style="color: #000">10. FIRMAS DE RESPONSABILIDAD</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="border"><b>FIRMAS DE DOCENTE</b></td>';
        $html .= '<td width="33%" class="border"><b>FIRMAS DE JEFE DE ÁREA</b></td>';
        $html .= '<td width="34%" class="border"><b>FIRMAS DE COORDINACIÓN</b></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td height="80px" class="border"></td>';
        $html .= '<td class="border"></td>';
        $html .= '<td class="border"></td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

    private function estilos() {
        $html = '';
        $html .= '<style>';
        $html .= '.border {
                    border: 0.1px solid black;
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
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .colorPlomo{
                    background-color:#c9cfcb;
                }
                
                .colorFinal{
                    background-color:#8ccaa0;
                }

                .colorAyudas{
                    color: #65b2e8;
                }

                    ';
        $html .= '</style>';
        return $html;
    }

}

?>