<?php

namespace backend\models\pca;

use backend\models\CurriculoMecNiveles;
use backend\models\PcaDetalle;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionDesagregacionCabecera;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class Pca extends ActiveRecord {

    private $cabeceraId;
    private $cabecera;
    private $pcaDetalle;
    public $html;

    public function __construct($cabeceraId) {
        $this->cabeceraId = $cabeceraId;
        $this->html = '';

        $this->cabecera = PlanificacionDesagregacionCabecera::findOne($this->cabeceraId);
        $this->pcaDetalle = PcaDetalle::find()
                ->where(['desagregacion_cabecera_id' => $this->cabeceraId])
                ->all();
        $this->pca();
    }

    private function pca() {
        $this->html .= '<div class="card shadow my-text-small scroll-400">';
        $this->html .= '<h5 class="card-header" style="background-color: #ab0a3d; color: white">INFORMACIÓN DEL PCA</h5>';
        $this->html .= '<div class="card-body">';

        $this->datos_informativos();
        $this->tiempo();
        $this->objetivos_generales();
        $this->ejes_transversale();
        $this->unidades_microcurriculares();
        $this->observaciones();
        $this->bibliografia();

        $this->html .= '</div>';
        $this->html .= '</div>';
    }

    private function datos_informativos() {
        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">1. DATOS INFORMATIVOS</u></strong>';
        $this->html .= '</div>';

        $this->html .= '<div class="row">';
        $this->html .= '<div class="col-lg-3 col-md-3" style="width:80px;color:black"><strong>AREA:</strong></div>';
        $this->html .= '<div class="col-lg-3 col-md-3" style="color:black; text-align:start; width="120px;">' 
                . $this->cabecera->ismAreaMateria->mallaArea->area->nombre 
                . '</div>';
        $this->html .= '<div class="col-lg-3 col-md-3" style="width:100px;color:black"><strong>ASIGNATURA:</strong></div>';
        $this->html .= '<div class="col-lg-3 col-md-3" style="color:black;text-align:start">' 
                . $this->cabecera->ismAreaMateria->materia->nombre . '</div>';
        $this->html .= '</div>';

        $this->html .= '<br>';

        $this->html .= '<div class="row">';
        $this->html .= '<div class="col-lg-3 col-md-3" style="width:80px;color:black"><strong>CURSO:</strong></div>';
        $this->html .= '<div class="col-lg-3 col-md-3" style="color:black;text-align:start;">' 
                . $this->cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . '</div>';
        $this->html .= '<div class="col-lg-3 col-md-3" style="width:80px;color:black"><strong>NIVEL:</strong></div>';
        $nivelCurriculo = CurriculoMecNiveles::findOne($this->cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->curriculo_nivel_id);
        $this->html .= '<div class="col-lg-3 col-md-3" style="color:black;text-align:start;">' . $nivelCurriculo->name . '</div>';
        $this->html .= '</div> <hr>';
    }

    private function tiempo() {
        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">2. TIEMPO</u></strong>';
        $this->html .= '</div>';
        $this->html .= '<div class="row">';
        $this->html .= '<div class="col-lg-2 col-md-2" style="color:black"><b>Carga horaria semanal:</b></div>';
        $this->html .= '<div class="col-lg-2 col-md-2" style="color:black; font-size:13px">' . $this->cabecera->carga_horaria_semanal . '</div>';
        $this->html .= '<div class="col-lg-2 col-md-2" style="color:black"><b>Semanas trabajo:</b></div>';
        $this->html .= '<div class="col-lg-2 col-md-2" style="color:black; font-size:13px">' . $this->cabecera->semanas_trabajo . '</div>';
        $this->html .= '<div class="col-lg-2 col-md-2" style="color:black"><b>E. Aprendizaje e imprevistos:</b></div>';
        $this->html .= '<div class="col-lg-2 col-md-2" style="color:black; font-size:13px">' . $this->cabecera->evaluacion_aprend_imprevistos . '</div>';
        $this->html .= '</div>';

        $this->html .= '<div class="row">';
        $this->html .= '<div class="col" style="color:black"><b>Total semanas:</b></div>';
        $this->html .= '<div class="col" style="color:black; font-size:13px">' . $this->cabecera->total_semanas_clase . '</div>';
        $this->html .= '<div class="col" style="color:black"><b>Total periodos:</b></div>';
        $this->html .= '<div class="col" style="color:black; font-size:13px">' . $this->cabecera->total_periodos . '</div>';
        $this->html .= '<div class="col"></div>';
        $this->html .= '<div class="col"></div>';
        $this->html .= '</div>';

        $this->html .= '<hr>';
    }

    private function objetivos_generales() {

        $pca = PcaDetalle::find()->where([
                    'desagregacion_cabecera_id' => $this->cabeceraId,
                    'tipo' => 'objetivos_generales'
                ])->all();

        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">3. OBJETIVOS GENERALES</u></strong>';
        $this->html .= '</div>';
        $this->html .= '<br>';

        foreach ($pca as $objetivos) {
            if($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION' ){
                $this->html .= '<li style="color:black"><strong>' . $objetivos->codigo . '</strong>' . $objetivos->contenido . '</li>';
            }else{
                $this->html .= '<p style="color:black"><a href="#" onclick="ajaxDeletePca(' . $objetivos->id . ')"><i class="fas fa-trash" style="color:#ab0a3d" ></i></a><strong>' . $objetivos->codigo . '</strong>' . $objetivos->contenido . '</p>';
            }
            
        }

        $this->html .= '<hr>';
    }

    private function ejes_transversale() {
        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">4. EJES TRANSVERSALES</u></strong>';
        $this->html .= '<div class="col-lg-12 col-md-12" style="margin-left:10px;color:black" >';
        $this->html .= '<u>';
        $this->html .= '<li>Justicia</li>';
        $this->html .= '<li>Solidaridad</li>';
        $this->html .= '<li>Innovador</li>';
        $this->html .= '</u>';
        $this->html .= '</div>';
        $this->html .= '</div>';

        $this->html .= '<hr>';
    }

    private function unidades_microcurriculares() {

        $titulos = \backend\models\PlanificacionBloquesUnidad::find()
                ->where(['plan_cabecera_id' => $this->cabecera->id])
                ->orderBy('curriculo_bloque_id')
                ->all();

        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">5. UNIDADES MICROCURRICULARES</u></strong>';
        $this->html .= '<div class="col-lg-12 col-md-12">';
        $this->html .= '<table class="table table-hover table-striped table-bordered">';
        $this->html .= '<thead>';
        $this->html .= '<tr style="text-align:center">';
        $this->html .= '<td><b>UNIDAD</b></td>';
        $this->html .= '<td><b>CONTENIDO</b></td>';
        $this->html .= '</tr>';
        $this->html .= '</thead>';
        $this->html .= '<body>';

        foreach ($titulos as $unidad) {
            $subtitulos = PlanificacionBloquesUnidadSubtitulo::find()->where(['plan_unidad_id' => $unidad->id])
                    ->orderBy('orden')
                    ->all();

            $this->html .= '<tr>';
            $this->html .= '<td style="vertical-align:middle;text-align:center">' . $unidad->unit_title . '</td>';
            $this->html .= '<td style="padding-left:20px">';
            foreach ($subtitulos as $subtitulo) {
                $subtitulos2 = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                            'subtitulo_id' => $subtitulo->id
                        ])
                        ->orderBy('orden')
                        ->all();

                $this->html .= '<b>' . $subtitulo->subtitulo . '</ul></b>';
                foreach ($subtitulos2 as $sub2) {
                    $this->html .= '<ul>';
                    $this->html .= '<li>' . $sub2->orden . ') ' . $sub2->contenido . '</li>';
                    $this->html .= '</ul>';
                }
            }
            $this->html .= '</td>';
            $this->html .= '</tr>';
        }
        $this->html .= '</body>';
        $this->html .= '</table>';
        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '<hr>';
    }

    private function observaciones() {
        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">6. OBSERVACIONES</u></strong>';
        $this->html .= '<div class="col-lg-12 col-md-12" style="color:black">';

        foreach ($this->pcaDetalle as $observacion) {
            if ($observacion->tipo == 'observaciones') {
                if ($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION') {
                    $this->html .= '<li>' . $observacion->contenido . '</li>';
                } else {
                    $this->html .= '<p><i onclick="ajaxDeletePca(' . $observacion->id . ')" type="button" class="fas fa-trash" style="color:#ab0a3d" ></i> ' . $observacion->contenido . '</p>';
                }
            }
        }

        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '<hr>';
    }

    private function bibliografia() {
        $this->html .= '<div class="row">';
        $this->html .= '<strong><u class="text-color-s">7. BIBLIOGRAFÍA</u></strong>';
        $this->html .= '<div class="col-lg-12 col-md-12" style="color:black">';

        foreach ($this->pcaDetalle as $bibliografia) {
            if ($bibliografia->tipo == 'bibliografia') {
                if ($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION') {
                    $this->html .= '<li>'.$bibliografia->contenido.'</li>';                    
                } else {
                    $this->html .= '<p><i onclick="ajaxDeletePca(' . $bibliografia->id . ')" type="button" class="fas fa-trash" style="color:#ab0a3d" ></i>' . $bibliografia->contenido . '</p>';
                }
            }
        }

        $this->html .= '</div>';
        $this->html .= '</div>';
        $this->html .= '<hr>';
    }

}
