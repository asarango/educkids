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
class InfSabanaPdfQ1 extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $quimestre;
    private $periodoId;
    private $periodoCodigo;
    private $arregloLibretas = array();
    private $modelAlumnos;
    private $arregloMaterias = array();

    public function __construct($paralelo, $alumno, $quimestre) {

        $sentencias = new SentenciasAlumnos();
        $modelParalelo = OpCourseParalelo::findOne($paralelo);

        $this->quimestre = $quimestre;
        $this->paralelo = $paralelo;
        $this->genera_materias_sabana();

        if (!$alumno > 0 || !$alumno != '') {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        } else {
            //echo 'aqui'.$alumno;
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }

//        print_r($this->modelAlumnos);
//        die();

        foreach ($this->modelAlumnos as $alumno) {

            $libreta = new NotasAlumno($alumno['id'], $paralelo);

            array_push($this->arregloLibretas, array(
                'alumno_id' => $alumno['id'],
                'alumno' => $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'],
                'curso' => $modelParalelo->course->name,
                'paralelo' => $modelParalelo->name,
                'libreta' => $libreta->arregloLibreta,
                'notas_finales' => $libreta->arregloNotasFinales,
                'notas_proyecto' => $libreta->arregloProyectos,
                'notas_comportamiento' => $libreta->arregloComportamiento
            ));
        }
        
//        print_r($this->arregloLibretas);
//        die();

        $this->genera_reporte_pdf();
    }

    private function genera_reporte_pdf() {
//        header('Content-type: application/excel');
//        $filename = 'promedios_finales.xls';
//        header('Content-Disposition: attachment; filename=' . $filename);
//
//        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
//                        <head>
//                            <meta charset="utf-8">
//                            <!--[if gte mso 9]>
//                            <xml>
//                                <x:ExcelWorkbook>
//                                    <x:ExcelWorksheets>
//                                        <x:ExcelWorksheet>
//                                            <x:Name>Sheet 1</x:Name>
//                                            <x:WorksheetOptions>
//                                                <x:Print>
//                                                    <x:ValidPrinterInfo/>
//                                                </x:Print>
//                                            </x:WorksheetOptions>
//                                        </x:ExcelWorksheet>
//                                    </x:ExcelWorksheets>
//                                </x:ExcelWorkbook>
//                            </xml>
//                            <![endif]-->
//                        </head>
//                        <body>';
//
//        $data .= $this->genera_cabecera();
//        $data .= $this->genera_cuerpo();
//
//
//        $data .= "</body>";
//        $data .= "</html>";
//        echo $data;
        
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 3,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera();
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->genera_cuerpo();

        //$html = $this->genera_cuerpo_pdf($modelAlmunos, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $quimestre);

        $mpdf->WriteHTML($html);
        //$mpdf->SetFooter($pie);

        $mpdf->Output('Sabanaq1' . "curso" . '.pdf', 'D');
        exit;
        
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
        $html .= '<td>';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>SABANA DE CALIFICACIONES QUIMESTRE I</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>2020-2021';
        $html .= '</td>';
        $html .= '<td align="right">';
                $html .= '<strong>CURSO:</strong>';
                $html .= $modelParalelo->course->name . '<br>';
                $html .= '<strong>PARALELO:</strong>';
                $html .= $modelParalelo->name . '<br>';
                $html .= '<strong>QUIMESTRE:</strong>';
                $html .= $this->quimestre . '<br>';
        $html .= '</td>';
        $html .= '</tr>';      
        $html .= '</table>';
        $html .= '<hr>';

        return $html;
    }

    private function genera_cuerpo() {

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '</style>';

        $html .= '<table style="font-size: 10px;" cellspacing="0" width="100%">';
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>#</strong></td>';
        $html .= '<td class="conBorde"><strong>ESTUDIANTE</strong></td>';

        foreach ($this->arregloMaterias as $materia) {

            if ($materia['imprime']) {
                //$html .= '<td>' . $materia['tipo_asignatura'] . ' - ' . $materia['porcentaje'] . ' - ' . $materia['nombre'] . '</td>';
                $html .= '<td class="conBorde">' . $materia['abreviatura'].'</td>';
            }
        }

        $html .= '<td class="conBorde"><strong>Q1</strong></td>';
        $html .= '<td class="conBorde"><strong>PROY</strong></td>';
        $html .= '<td class="conBorde"><strong>COMP</strong></td>';

        $html .= '</tr>';

        $html .= $this->detalle_alumnos();

        $html .= '</table>';

        return $html;
    }

    private function detalle_alumnos() {
        $html = '';

        $i = 0;
        foreach ($this->modelAlumnos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';

            foreach ($this->arregloMaterias as $materia) {
                if ($materia['imprime']) {
                    $html .= $this->buscar_nota($materia, $alumno['id']);
                }
            }
            
            $html .= $this->consulta_notas_finales($alumno['id']);
            
            

            $html .= '</tr>';
        }

        return $html;
    }
    
    private function consulta_notas_finales($alumnoId){
            
        $html = '';
        
        foreach ($this->arregloLibretas as $libreta){
            if($libreta['alumno_id'] == $alumnoId){
                foreach ($libreta['notas_finales'] as $nota){
                    $html .= '<td class="conBorde" align="center">'.$nota['q1'].'</td>';
                }
                
                 $html .= '<td class="conBorde" align="center">'.$libreta['notas_proyecto']['q1'].'</td>';
                 $html .= '<td class="conBorde" align="center">'.$libreta['notas_comportamiento']['q1'].'</td>';
                
            }
        }
        return $html;
    }

    private function buscar_nota($materia, $alumnoId) {
        $html = '';

        if ($materia['tipo_asignatura'] == 'area') {
            foreach ($this->arregloLibretas as $libreta) {
                if ($libreta['alumno_id'] == $alumnoId) {
                    foreach ($libreta['libreta'] as $lib) {
                        if ($lib['area_id'] == $materia['asignatura_id']) {
                            foreach ($lib['notas_area'] as $notas) {
                                $html .= '<td class="conBorde" align="center">' . $notas['q1'] . '</td>';
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($this->arregloLibretas as $libreta){
                if ($libreta['alumno_id'] == $alumnoId) {
                    foreach ($libreta['libreta'] as $lib){
                        foreach ($lib['materias'] as $mat){
                            if($mat['materia_id'] == $materia['asignatura_id']){
                                $html .= '<td class="conBorde" align="center">' . $mat['notas']['q1'] . '</td>';
  
//                                print_r($mat['notas']['q1']);
//                                die();
                            }
                        }
                    }
                }
            }
            
        }



        return $html;
    }

    private function genera_materias_sabana() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);
        $cursoId = $modelParalelo->course->id;

        $modelMallaCurso = ScholarisMallaCurso::find()->where([
                    'curso_id' => $cursoId
                ])->one();

        $modelMallaArea = ScholarisMallaArea::find()->where([
                    'malla_id' => $modelMallaCurso->malla_id,
                    'tipo' => 'NORMAL'
                ])->orderBy('orden')
                ->all();

        foreach ($modelMallaArea as $area) {


            array_push($this->arregloMaterias, array(
                'tipo_asignatura' => 'area',
                'asignatura_id' => $area->id,
                'tipo' => $area->tipo,
                'promedia' => $area->promedia,
                'imprime' => $area->se_imprime,
                'nombre' => $area->area->name,
                'porcentaje' => $area->total_porcentaje,
                'abreviatura' => strtoupper(substr($area->area->name,0,3))
            ));

            $modelMaterias = ScholarisMallaMateria::find()->where([
                        'malla_area_id' => $area->id,
                        'tipo' => 'NORMAL'
                    ])->orderBy('orden')->all();

            foreach ($modelMaterias as $materia) {
                array_push($this->arregloMaterias, array(
                    'tipo_asignatura' => 'materia',
                    'asignatura_id' => $materia->materia_id,
                    'tipo' => $materia->tipo,
                    'promedia' => $materia->promedia,
                    'imprime' => $materia->se_imprime,
                    'nombre' => $materia->materia->name,
                    'porcentaje' => $materia->total_porcentaje,
                    'abreviatura' => $materia->materia->abreviarura
                ));
            }
        }
    }

}
