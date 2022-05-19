<?php

namespace backend\models;

use Mpdf\Mpdf;
use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class ReportePlanSemanal extends \yii\db\ActiveRecord {
    
    private $nombreDocente;
    private $uso;
    private $periodoCodigo;

    public function genera_reporte($id, $facultyId) {
        //echo $pudId;
        $instituto = Yii::$app->user->identity->instituto_defecto;
        $usuario = Yii::$app->user->identity->usuario;
        $modelObs = \backend\models\ScholarisBloqueSemanasObservacion::findOne($id);

        $this->uso = $modelObs->semana->bloque->tipo_uso;
        $this->periodoCodigo = $modelObs->semana->bloque->scholaris_periodo_codigo;
        
        

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 10,
        ]);

        $cabecera = $this->cabecera($modelObs, $instituto, $usuario);
        //$pie = $this->pie();

        $mpdf->SetHeader($cabecera);
//        $mpdf->showImageErrors = true;
//
        $html = $this->html($id, $modelObs, $facultyId);

        $mpdf->WriteHTML($html);

        $mpdf->SetHTMLFooter('<hr>'
                . '<table width="100%" cellspacing="0">'
                . '<tr>'
                . '<td>PAGINA: {PAGENO}</td>'
                . '<td></td>'
                . '<td></td>'
                . '</tr>'
                . '</table>');

        $mpdf->Output('Reporte_PUD' . "curso" . '.pdf', 'D');
        exit;
    }

    protected function cabecera($modelObs, $instituto, $usuario) {

        $modelInst = OpInstitute::findOne($instituto);
        $modelUser = ResUsers::find()->where(['login' => $usuario])->one();

        $this->nombreDocente = $modelUser->partner->name;
        
        $html = '';

//        $html.= '<table width="100%" cellspacing="0">';
//        $html.= '<tr>';
//        $html.= '<td align="center">';
//        $html.= '<img src="imagenes/instituto/logo/sello_ministerio.jpg" width="100px" align="center">';
//        $html.= '</td>';
//        $html.= '</tr>';
//        $html.= '</table>';

        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%" style="font-size:8px;">'
                . '<img src="imagenes/instituto/logo/sellolibreta.png" width="50px">'
                // . '<br>Proceso Académico'
                . '</td>';
        $html .= '<td align="center">' . $modelInst->name . '<br>'
                . 'International Scholastic Model<br>'
                . 'Plan Semanal / Weekly Plan / Plan Hebdomadaire (IN-BE-BM-PAI-DP)'
                . '</td>';
        $html .= '<td align="right" width="20%" style="font-size:8px;">'
                . 'Código: ISMR20-29 D<br>
Version: 3.0<br>
Fecha: 8/12/2020<br>'
                . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center" width="20%" style="font-size:8px;"><strong>Proceso Académico</strong></td>';
        $html .= '<td align="center" style="font-size:10px;">'
                . $modelUser->partner->name . ' / '
                . '<strong>Semana: </strong>' . $modelObs->semana->nombre_semana . ' / '
                . '<strong>Desde: </strong>' . $modelObs->semana->fecha_inicio . ' / '
                . '<strong>Hasta: </strong>' . $modelObs->semana->fecha_finaliza . ' / '
                //.$modelObs->semana->fecha_inicio.
                . '</td>';
        $html .= '<td align="right" width="20%">';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    protected function pie() {
        $html = '';
        $html .= '<hr>';

        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td>' . PAGENO . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    protected function html($id, $modelObs, $facultyId) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';

        $html .= $this->destrezas($modelObs, $facultyId);
        $html .= $this->uno_datos($id);
        $html .= $this->dos_observaciones($modelObs);
        $html .= $this->tres_firmas($modelObs);

        return $html;
    }

    private function destrezas($modelObs, $facultyId) {

        $modelDes = ScholarisPlanSemanalDestrezas::find()
                ->where([
                    'faculty_id' => $facultyId,
                    'semana_id' => $modelObs->semana_id,
                    'comparte_valor' => $modelObs->comparte_bloque
                ])
                ->all();

        $html = '';
        if ($modelDes) {

            $html .= '<p class="tamano10" align="center"><strong>PLANIFICACIÓN DE DESTREZAS</strong></p>';
            $html .= '<table width="100%" cellspacing="0" class="tamano8">';
            $html .= '<tr>';
            
            $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>CURSO</strong></td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>CONCEPTOS</strong></td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>CONTEXTOS</strong></td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>PREGUNTAS DE INDAGACIÓN</strong></td>';
            $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>ENFOQUES DE HABILIDADES</strong></td>';
            $html .= '</tr>';
            foreach ($modelDes as $des) {
                $html .= '<tr>';
                $html .= '<td class="conBorde" align=""><strong>' . $des->curso->name . '</strong></td>';
                $html .= '<td class="conBorde" align=""><strong>' . $des->concepto . '</strong></td>';
                $html .= '<td class="conBorde" align=""><strong>' . $des->contexto . '</strong></td>';
                $html .= '<td class="conBorde" align=""><strong>' . $des->pregunta_indagacion . '</strong></td>';
                $html .= '<td class="conBorde" align=""><strong>' . $des->enfoque . '</strong></td>';
                $html .= '</tr>';
            }

            $html .= '</table>';
        }


        return $html;
    }

    private function uno_datos($id) {
        
        $modelObs = ScholarisBloqueSemanasObservacion::findOne($id);
         
        
        $html = '';

        $html .= '<p class="tamano10" align="center"><strong>ACTIVIDADES / ACTIVITIES / ACTIVITÉS</strong></p>';

        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="80" class="conBorde colorEtiqueta" colspan="" align="center"><strong>Dia / Day/ Jour</strong></td>';
        $html .= '<td width="10" class="conBorde colorEtiqueta" colspan="" align="center"><strong>Hora /Hour /Heure</strong></td>';
        $html .= '<td width="100" class="conBorde colorEtiqueta" colspan="" align="center"><strong>Nivel /Level /Niveau</strong></td>';
        $html .= '<td width="120" class="conBorde colorEtiqueta" colspan="" align="center"><strong>Asignatura / Subject /Classe </strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center"><strong>Enseñanzas / Knowledge / Enseignements</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center"><strong>Actividades / Activities / Activités </strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center"><strong>Tareas / Evaluación / Homework / Assessment / Devoirs / Évaluation</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center"><strong>Criterios / Criteria / Critères</strong></td>';
        $html .= '</tr>';

        $detalle = $this->get_datos_plan($id, $modelObs->usuario);

        foreach ($detalle as $det) {
            $html .= '<tr>';
            //$html .= '<td width="80" class="conBorde colorEtiqueta" colspan="" align="center"><strong>Dia / Day/ Jour</strong></td>';
            $html .= '<td class="conBorde" colspan="" align="center">' .$det['dia'] . '</td>';
            $html .= '<td class="conBorde" colspan="" align="center">' . $det['sigla'] . '</td>';
            $html .= '<td class="conBorde" colspan="" align="center">' . $det['curso'] . ' ' . $det['paralelo'] . '</td>';
            $html .= '<td class="conBorde" colspan="" align="center">' . $det['materia'] . '</td>';
            $html .= '<td class="conBorde" colspan="" align="center">' . $det['title'] . '</td>';
            $html .= '<td class="conBorde" colspan="" align="center">' . $det['descripcion'] . '</td>';
            $html .= '<td class="conBorde" colspan="" align="center">' . $det['tareas'] . '</td>';
            
            $modelCriterios = $this->get_criterios($det['actividad_id']);
            
            $html .= '<td class="conBorde" colspan="" align="center">';
            foreach ($modelCriterios as $cri){
                $html .= '<p>'.$cri['criterio'].'</p>';
            }
            $html .= '</td>';
            
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }
    
    private function get_criterios($actividadId){
        $con = Yii::$app->db;
        $query = "select 	c.criterio
                    from 	scholaris_actividad_descriptor d
                                    inner join scholaris_criterio c on c.id = d.criterio_id
                    where	d.actividad_id = $actividadId
                    group by c.criterio
                    order by c.criterio;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_datos_plan($obsId, $usuario) {
        $con = Yii::$app->db;
        $query = "select 	case 
			when extract(dow from a.inicio) = 1 then 'Lunes'
			when extract(dow from a.inicio) = 2 then 'Martes'
			when extract(dow from a.inicio) = 3 then 'Miercoles'
			when extract(dow from a.inicio) = 4 then 'Jueves'
			when extract(dow from a.inicio) = 5 then 'Viernes'
		end as dia
		,h.sigla
		,cur.name as curso
		,p.name as paralelo
		,m.name as materia
		,a.title
		,a.descripcion
		,a.tareas
		,o.usuario
		,c.id
                ,a.id as actividad_id
from 	scholaris_bloque_semanas_observacion o
		inner join scholaris_bloque_semanas s on s.id = o.semana_id
		inner join scholaris_bloque_actividad b on b.id = s.bloque_id
		inner join scholaris_actividad a on a.bloque_actividad_id = b.id
				   and a.semana_id = s.id
		inner join scholaris_clase c on c.id = a.paralelo_id
		inner join scholaris_horariov2_hora h on h.id = a.hora_id
		inner join op_course cur on cur.id = c.idcurso
		inner join op_course_paralelo p on p.id = c.paralelo_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join op_faculty f on f.id = c.idprofesor
		inner join res_users u on u.partner_id = f.partner_id
where 	o.id = $obsId
		and u.id = $usuario
order by extract(dow from a.inicio), h.numero;";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function dos_observaciones($modelObs) {

        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center"><strong>OBSERVACIONES / OBSERVATIONS / OBSERVATIONS</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde" colspan="" align="">' . $modelObs->observacion . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        return $html;
    }

    private function tres_firmas() {
        
        $modelClase = ScholarisClase::find()->where([
            'tipo_usu_bloque' => $this->uso,
            'periodo_scholaris' => $this->periodoCodigo
        ])->one();
        
        
        
        $modelParalelo = OpCourseParalelo::findOne($modelClase->paralelo_id);

        $html = '';
        $html .= '<br>';
        $html .= '<table width="60%" cellspacing="0" class="tamano8" align="center">';
        $html .= '<tr>';
        $html .= '<td width="50%" class="conBorde colorEtiqueta" colspan="" align="center"><strong>'
                . 'FIRMA DE DOCENTE / TEACHER\'S SIGNATURE /SIGNATURE DU PROFESSEUR</strong>'
                . '</td>';
        
        $html .= '<td width="50%" class="conBorde colorEtiqueta" colspan="" align="center"><strong>'
                . 'FIRMA COORDINACION / COORDINATOR\'S SIGNATURE /SIGNATURE DU COORDINATEUR / TRICE</strong><br>'
                . '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="conBorde" height="50"></td>';
        $html .= '<td class="conBorde" height="50"></td>';
        $html .= '</tr>';
        
        
        $html .= '<tr>';
        $html .= '<td class="conBorde" height="20" align="center">'.$this->nombreDocente.'</td>';
        $html .= '<td class="conBorde" height="20" align="center">'.$modelParalelo->coordinador_nombre.'</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        return $html;
    }

}
