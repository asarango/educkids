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
class InformePai extends \yii\db\ActiveRecord {

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function genera_reporte($alumno, $quimestre, $paralelo) {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelAlumno = OpStudent::findOne($alumno);
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $modelQuimestre = ScholarisQuimestre::find()->where(['codigo' => $quimestre])->one();


        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
            'cssFile' => 'css/misestilos.css'
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo, $quimestre);
//        $pie = $this->genera_pie_pdf();
//
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html1 = $this->parte1($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo);
        $html2 = $this->parte2($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo);
        $html3 = $this->parte3($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo);
        $html4 = $this->parte4($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo);

        $mpdf->WriteHTML($html1);
        $mpdf->addPage();
        $mpdf->WriteHTML($html2);
        $mpdf->addPage();
        $mpdf->WriteHTML($html3);
        $mpdf->addPage();
        $mpdf->WriteHTML($html4);


////        $mpdf->addPage();
//        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf() {
        $html = '';

        $html .= '<img src="imagenes/instituto/boletinpai/ENCABEZADO.jpg">';
        return $html;
    }

    private function parte1($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo) {
        $fecha = date("Y-m-d");
        $modelContenido = ScholarisBoletinPaiContenidos::find()->orderBy('orden')->all();
        $html = '';
        $html .= "<style>";
        $html .= '.tamano10{ font-size: 10px;}';
        $html .= '.tamano12{ font-size: 12px;}';
        $html .= '.tamanonormal{ font-size: 10px; padding-left:20px}';
        $html .= '.conBorde { border: 0.1px solid black;}';
        $html .= '</style>';


        $html .= '<div align="center"><img src="imagenes/instituto/boletinpai/MYP_ENG.jpg" width="580px"></div>';
        $html .= '<div align="center" class="tamanonormal">Fuente * Principios a la práctica 2014 </div><br>';

        $html .= '<div align="center" class="tamanonormal">';
        $html .= '<table width="50%" class="tamanonormal">';
        $html .= '<tr>';
        $html .= '<td width="30%" class="">ESTUDIANTE:</td>';
        $html .= '<td width="70%">' . $modelAlumno->first_name .' '.$modelAlumno->middle_name.' '.$modelAlumno->last_name. '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>GRADO:</td>';
        $html .= '<td>' . $modelParalelo->course->name . ' - ' . $modelParalelo->name. ' - '. $modelParalelo->course->section0->period->name. '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>FECHA:</td>';
        $html .= '<td>' . $fecha . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';

        $html .= '<div align="center" class="tamanonormal">' . $modelContenido[1]->descripcion . '</div>';


        return $html;
    }

    private function parte2($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo) {
        $html = '';
        $html .= '<div align="center"><img src="imagenes/instituto/boletinpai/PERFILBI.jpg" width="80%"></div>';
        $html .= '<div align="center" class="tamanonormal">Fuente * Principios a la práctica 2014 </div><br>';

        return $html;
    }

    private function parte3($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo) {
        $modelContenido = ScholarisBoletinPaiContenidos::find()->orderBy('orden')->all();

        $html = '';
        $html .= '<div align="center" class="tamanonormal">' . $modelContenido[3]->descripcion . '</div>';

        return $html;
    }

    private function parte4($modelAlumno, $modelParalelo, $modelQuimestre, $modelPeriodo) {
        $areas = $this->get_Areas($modelAlumno->id, $modelQuimestre->codigo, $modelPeriodo->codigo);                

        $html = '';
        $html .= '<div align="center" class="tamanonormal"><strong>DETALLE DE NIVELES DE LOGRO POR ASIGNATURA</strong></div>';

        $grupo = 0;

        $html .= '<div style="font-size: 10px">';
        foreach ($areas as $datarea) {
            $areaId = $datarea['id'];
            $areaNo = $datarea['area'];

            $grupo++;
            $html .= '<strong>GRUPO #: ' . $grupo . ' ' . $areaId.$areaNo . '</strong><br>';

            $notas = $this->get_notas($modelAlumno->id, $areaId, $modelQuimestre->codigo, $modelPeriodo->codigo);
            
            foreach ($notas as $datoNota) {
                
                $materiaNombre = $datoNota['materia'];
                $notaA = $datoNota['nota_a'];
                $notaB = $datoNota['nota_b'];
                $notaC = $datoNota['nota_c'];
                $notaD = $datoNota['nota_d'];
                $suma = $datoNota['suma_total'];
                $final = $datoNota['final_homologado'];

                $html .= '<table width="100%"  cellpadding="2" cellspacing="0" style="font-size: 10px">';
                $html .= '<tr>';
                $html .= '<td width="80%" class="conBorde"><strong>' . $materiaNombre . '</strong></td>';
                $html .= '<td width="5%" class="conBorde"><strong>PAI x/8</strong></td>';
                $html .= '<td width="15%" class="conBorde"><strong>FINAL x/7</strong></td>';
                $html .= '</tr>';

                $criteA = $this->get_criterios($areaId, 'A');
                
                

                $html .= '<tr>';
                $html .= '<td class="conBorde">';
                $html .= '<table>';
                foreach ($criteA as $datoCriterio) {
                    $html .= '<tr>';
                    $html .= '<td>' .$datoCriterio['detalle'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</td>';
                
                $html .= '<td valign="middle" align="center" class="conBorde">' . number_format($notaA, 0) . '</td>';

                $tomadescriptor = $this->get_descriptor($final);

                isset($tomadescriptor['descriptor']) ? $descrip = $tomadescriptor['descriptor'] : $descrip = '-';
                
                $html .= '<td rowspan="4" class="rota" class="conBorde">' . $descrip . '</td>';
                $html .= '</tr>';

                $criteB = $this->get_criterios($areaId, 'B');
                $html .= '<tr>';
                $html .= '<td class="conBorde">';
                $html .= '<table>';
                foreach ($criteB as $datoCriterio) {
                    $html .= '<tr>';
                    $html .= '<td>' . $datoCriterio['detalle'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</td>';
                $html .= '<td valign="middle" align="center" class="conBorde">' . number_format($notaB, 0) . '</td>';
                $html .= '</tr>';


                $criteC = $this->get_criterios($areaId, 'C');
                $html .= '<tr>';
                $html .= '<td class="conBorde">';
                $html .= '<table>';
                foreach ($criteC as $datoCriterio) {
                    $html .= '<tr>';
                    $html .= '<td>' . $datoCriterio['detalle'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</td>';
                $html .= '<td valign="middle" align="center" class="conBorde">' . number_format($notaC, 0) . '</td>';
                $html .= '</tr>';


                $criteD = $this->get_criterios($areaId, 'D');
                $html .= '<tr>';
                $html .= '<td class="conBorde">';
                $html .= '<table>';
                foreach ($criteD as $datoCriterio) {
                    $html .= '<tr>';
                    $html .= '<td>' . $datoCriterio['detalle'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</td>';
                $html .= '<td valign="middle" align="center" class="conBorde">' . number_format($notaD, 0) . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td class="conBorde" bgcolor="#c1d2fb"></td>';
                $html .= '<td valign="middle" bgcolor="#c1d2fb" align="center" class="conBorde">' . number_format($suma, 0) . ' / 32</td>';
                $html .= '<td valign="middle" bgcolor="#c1d2fb" align="center" class="conBorde">' . number_format($final, 0) . '/ 7</td>';
                $html .= '</tr>';

                $html .= '</table>';
                $html .= '<br><br>';
            }
        }

        $html .= '</div>';
        
        
        $html .= '<strong>RESUMEN DE APROVECHAMIENTO</strong>';
    $html .= '<br>';
    //$html .= '<div align="center">';
    $html .= '<table border="1" width="60%" cellpadding="2" cellspacing="0">';
    $html .= '<tr>';
    $html .= '<td width="80%"><strong>MATERIA</strong></td>';
    $html .= '<td width="20%"><strong>CALIFICACION FINAL</strong></td>';
    $html .= '</tr>';
    
    
    foreach ($areas as $datos) {
        $areaId = $datos['id'];
        $areaNo = $datos['area'];
        $html .= '<tr>';
        $html .= '<td colspan="2"><strong>' . $areaNo . '</strong></td>';
        //$html .= '<td></td>';
        $html .= '</tr>';
        
        $detalle = $this->get_notas($modelAlumno->id, $areaId, $modelQuimestre->codigo, $modelPeriodo->codigo);

        foreach ($detalle as $valor) {
            $valMateria = $valor['materia'];
            $valFinal = $valor['final_homologado'];
            $html .= '<tr>';
            $html .= '<td width="80%">' . $valMateria . '</td>';
            $html .= '<td width="20%" valign="middle" align="center">' . $valFinal . '</td>';
            $html .= '</tr>';
        }

//                foreach ($notas as $not){
//                    $notaAreaF = $not['area_id'];
//                    $notaMateriaF = $not['materia'];
//                    $notaFinalF = $not['final_homologado'];
//                    
//                    if($areaId == $notaAreaF){
//                        $html .= '<tr>';
//                        $html .= '<td>'.$notaFinalF.'</td>';
//                        $html .= '</tr>';
//                    }
//                }
    }


    $html .= '</table>';
    
    
    


        return $html;
    }

    /*     * ******************************************** */
    //CONSULTAS PARA DATOS DE REPORTE

    /**
     * 
     * @param type $alumno
     * @param type $quimestre
     * @param type $periodo
     * @return type
     */
    private function get_Areas($alumno, $quimestre, $periodo) {
        $con = \Yii::$app->db;

        $query = "select 	a.id 
		,a.name as area
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
		inner join scholaris_area a on a.id = ma.area_id 
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodo' 
                and mm.tipo in ('NORMAL','OPTATIVAS') 
group by a.id, a.name, ma.orden  order by ma.orden;";   
        
//        echo $query;
//        die();
                
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_notas($alumno, $area, $quimestre, $periodo) {
        $con = \Yii::$app->db;
        $query = "select 	g.clase_id
		,g.estudiante_id
		,mat.area_id
		,area.name as area 
		,mat.id as materia_id
		,mat.name as materia
		,n.quimestre
		,n.nota_a
		,n.nota_b
		,n.nota_c
		,n.nota_d
		,n.suma_total
		,n.final_homologado
from 	scholaris_grupo_alumno_clase g
		left join scholaris_notas_pai n on n.alumno_id = g.estudiante_id
								and n.clase_id = g.clase_id
		inner join scholaris_clase cla on cla.id = g.clase_id
                and cla.periodo_scholaris = '$periodo'
		inner join scholaris_materia mat on mat.id = cla.idmateria
		inner join scholaris_area area on area.id = mat.area_id
where 	g.estudiante_id = $alumno
		and mat.area_id = $area
		and (n.quimestre IS null or n.quimestre = '$quimestre')
		and (n.scholaris_periodo_codigo is null or n.scholaris_periodo_codigo = '$periodo')
order by mat.area_id asc, mat.orden asc";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_criterios($area, $criterio) {
        $con = \Yii::$app->db;
        $query = "select 	id, criterio, detalle, orden
                        from 	scholaris_criterio_boletin 
                        where 	area_id = $area
                            and criterio = '$criterio'
                        order by criterio asc, orden asc";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_descriptor($notaFinal) {
        if ($notaFinal) {
            $notaFinal = $notaFinal;
        } else {
            $notaFinal = 0;
        }

        $con = \Yii::$app->db;
        $query = "select  descriptor 
                    from 	scholaris_notas_pai_homologacion_total
                    where	calificacion_final = $notaFinal";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    

}
