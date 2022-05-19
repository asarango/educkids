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
class InfSabanaQ1 extends \yii\db\ActiveRecord {

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

        $this->genera_reporte_excel();
    }

    private function genera_reporte_excel() {
        header('Content-type: application/excel');
        $filename = 'promedios_finales.xls';
        header('Content-Disposition: attachment; filename=' . $filename);

        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                        <head>
                            <meta charset="utf-8">
                            <!--[if gte mso 9]>
                            <xml>
                                <x:ExcelWorkbook>
                                    <x:ExcelWorksheets>
                                        <x:ExcelWorksheet>
                                            <x:Name>Sheet 1</x:Name>
                                            <x:WorksheetOptions>
                                                <x:Print>
                                                    <x:ValidPrinterInfo/>
                                                </x:Print>
                                            </x:WorksheetOptions>
                                        </x:ExcelWorksheet>
                                    </x:ExcelWorksheets>
                                </x:ExcelWorkbook>
                            </xml>
                            <![endif]-->
                        </head>
                        <body>';

        $data .= $this->genera_cabecera();
        $data .= $this->genera_cuerpo();


        $data .= "</body>";
        $data .= "</html>";
        echo $data;
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td><strong>' . $modelParalelo->course->xInstitute->name . '</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td><strong>SABANA DE CALIFICACIONES</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td><strong>CURSO:</strong></td>';
        $html .= '<td>' . $modelParalelo->course->name . '</td>';
        $html .= '<td><strong>PARALELO:</strong></td>';
        $html .= '<td>' . $modelParalelo->name . '</td>';
        $html .= '<td><strong>QUIMESTRE:</strong></td>';
        $html .= '<td>' . $this->quimestre . '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function genera_cuerpo() {

        $html = '';

        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td><strong>#</strong></td>';
        $html .= '<td><strong>ESTUDIANTE</strong></td>';

        foreach ($this->arregloMaterias as $materia) {

            if ($materia['imprime']) {
                $html .= '<td>' . $materia['tipo_asignatura'] . ' - ' . $materia['porcentaje'] . ' - ' . $materia['nombre'] . '</td>';
            }
        }

        $html .= '<td><strong>PROMEDIO FINAL</strong></td>';
        $html .= '<td colspan=""><strong>PROYECTOS ESCOLARES</strong></td>';
        $html .= '<td colspan=""><strong>COMPORTAMIENTO</strong></td>';

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
            $html .= '<td>' . $i . '</td>';
            $html .= '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';

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
                    $html .= '<td>'.$nota['q1'].'</td>';
                }
                
                 $html .= '<td>'.$libreta['notas_proyecto']['q1'].'</td>';
                 $html .= '<td>'.$libreta['notas_comportamiento']['q1'].'</td>';
                
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
                                $html .= '<td>' . $notas['q1'] . '</td>';
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
                                $html .= '<td>' . $mat['notas']['q1'] . '</td>';
  
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
                'porcentaje' => $area->total_porcentaje
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
                    'porcentaje' => $materia->total_porcentaje
                ));
            }
        }
    }

}
