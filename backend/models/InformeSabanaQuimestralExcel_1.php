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
class InformeSabanaQuimestralExcel extends \yii\db\ActiveRecord {

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function genera_reporte($paralelo, $quimestre) {
        
        $sentencias = new \backend\models\SentenciasRepLibreta2();
        
        

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $sentencias->procesarAreas($modelParalelo->course_id, $paralelo);

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

        $data .= $this->genera_excel_cabecera($modelParalelo, $quimestre);
        $data .= $this->genera_excel_cuerpo($modelParalelo, $quimestre);


        $data .= "</body>";
        $data .= "</html>";
        echo $data;
    }

    private function genera_excel_cabecera($modelParalelo, $quimestre) {

        switch ($quimestre) {
            case 'q1':
                $quimestre = 'QUIMESTRE 1';
                break;

            default:
                $quimestre = 'QUIMESTRE 2';
                break;
        }


        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $data = '<table border="1">';
        $data .= "<tr>";
        $data .= '<td><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';
        $data .= '<td>' . $modelParalelo->institute->name . '</td>';
        $data .= '<td>' . $modelParalelo->course->name . ' ' . $modelParalelo->name . '</td>';
        $data .= "</tr>";
        $data .= "<tr>";
        $data .= "<td></td>";
        $data .= "<td>Sabana de notas</td>";
        $data .= "<td>PROMEDIOS FINALES " . $quimestre . "</td>";
        $data .= "</tr>";
        $data .= "<tr>";
        $data .= '<td>' . $modelMalla->malla->nombre_malla . '</td>';
        $data .= "</tr>";
        $data .= "</table>";


        return $data;
    }

    private function genera_excel_cuerpo($modelParalelo, $quimestre) {

        if ($quimestre == 'q1') {
            $colspan = 8;
        } else {
            $colspan = 17;
        }

        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        $sentencias = new InformeCalculoNotas();
        $modelAreas = $sentencias->get_malla_areas_paralelo($modelParalelo->id, $modelPeriodo->codigo);

        $modelAlumnos = $sentencias->get_alumnos($modelParalelo->id);


        $data = '<table border="1">';
        $data .= "<tr>";
        $data .= '<td rowspan="2"><strong>ORD</strong></td>';
        $data .= '<td rowspan="2"><strong>ESTUDIANTES</strong></td>';
        $data .= $this->cabecera_materias($modelAreas, $modelParalelo, $modelPeriodo, $colspan);
        $data .= "</tr>";

        $data .= "<tr>";
        $data .= $this->parciales($modelAreas, $modelParalelo, $modelPeriodo, $colspan);
        $data .= "</tr>";

        $data .= "<tr>";
        $data .= $this->detalle_notas($modelAlumnos, $modelAreas, $modelParalelo, $modelPeriodo, $colspan);
        $data .= "</tr>";

        $data .= "<tr>";
        $data .= $this->aprovechamientos_materia($modelAreas, $modelParalelo, $modelPeriodo, $colspan);
        $data .= "</tr>";


        $data .= "</table>";

        return $data;
    }

    private function aprovechamientos_materia($modelAreas, $modelParalelo, $modelPeriodo, $colspan) {

        $sentencias = new InformeCalculoNotas();

        $data = '';
        $data .= '<td align="center" align="center" bgcolor="#A4DCBC" colspan="2"><strong>PROMEDIOS GENERALES:</strong></td>';

        foreach ($modelAreas as $area) {

            $data .= $this->devuelve_promedios_area($area, $modelParalelo, $modelPeriodo, $colspan); //toma los promedios de las areas

            $modelMaterias = $sentencias->get_malla_materias_paralelo($modelParalelo->id, $modelPeriodo->codigo, $area['id']);

            foreach ($modelMaterias as $materia) {
                
                $data .= $this->devuelve_promedio_materias($materia, $modelParalelo, $colspan);
                
            }
        }


        $data .= '<td bgcolor="#C59AFF" colspan="3" align="center"><strong>APROVECHAMIENTO</strong></td>';
        $data .= '<td bgcolor="#939393" colspan="2" align="center"><strong>PROYECTOS ESCOLARES</strong></td>';
        $data .= '<td bgcolor="#F3FAA4" colspan="2" align="center"><strong>COMPORTAMIENTO</strong></td>';

        return $data;
    }

    private function devuelve_promedio_materias($materia, $modelParalelo, $colspan) {
        $sentencias = new SentenciasRepLibreta2();
        $sentenciasNotas = new InformeCalculoNotas();
        $periodo = \Yii::$app->user->identity->periodo_id;
        
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);
        
               
        $data = '';

        $notasPromedio = $sentenciasNotas->calcula_promedio_materia($modelParalelo->id, $materia['materia_id'], $modelPeriodo->codigo);    
        
        if($colspan == 8){
            $data .= '<td align="center"><strong>' . $notasPromedio['p1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['p2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['p3'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['pr1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['pr180'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['ex1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['ex120'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['q1'] . '</strong></td>';
        }else{
            $data .= '<td align="center"><strong>' . $notasPromedio['p1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['p2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['p3'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['pr1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['pr180'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['ex1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['ex120'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['q1'] . '</strong></td>';
            
            $data .= '<td align="center"><strong>' . $notasPromedio['p4'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['p5'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['p6'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['pr2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['pr280'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['ex2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['ex220'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['q2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notasPromedio['final_ano_normal'] . '</strong></td>';
        }
        

        return $data;
    }

    private function devuelve_promedios_area($modelArea, $modelParalelo, $modelPeriodo, $colspan) {

        $sentencias = new SentenciasRepLibreta2();
        $usuario = \Yii::$app->user->identity->usuario;

        $data = '';

        if ($modelArea['promedia'] == true) {

            $notasPromedio = $sentencias->toma_promedio_area_paralelo($modelArea['id'], $modelParalelo->id, $usuario);

            if ($colspan == 8) {
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p3'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['pr1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['pr180'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['ex1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['ex120'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['q1'] . '</strong></td>';
            } else {
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p3'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['pr1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['pr180'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['ex1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['ex120'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['q1'] . '</strong></td>';

                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p4'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p5'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['p6'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['pr2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['pr280'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['ex2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['ex220'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['q2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notasPromedio['final_ano_normal'] . '</strong></td>';
            }
        } else {
            
            if ($colspan == 8) {

            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            
            }else{
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            }
           
        }


        return $data;
    }

    private function cabecera_materias($modelAreas, $modelParalelo, $modelPeriodo, $colspan) {

        $sentencias = new InformeCalculoNotas();

        $data = '';

        foreach ($modelAreas as $area) {

            $modelMaterias = $sentencias->get_malla_materias_paralelo($modelParalelo->id, $modelPeriodo->codigo, $area['id']);

            $promAst = $area['promedia'] ? '' : ' * ';
            $data .= '<td bgcolor="#DDF5C2" colspan="' . $colspan . '" align="center"><strong>' . $promAst . '(' . $area['id'] . ')' . $area['name'] . '</strong></td>';


            foreach ($modelMaterias as $materia) {

                $promMst = $materia['promedia'] ? '' : '*';

                $data .= '<td colspan="' . $colspan . '" align="center">' . $promMst . '(' . $materia['materia_id'] . ')' . $materia['name'] . '</td>';
            }
        }


        $data .= '<td bgcolor="#C59AFF" colspan="3" align="center"><strong>APROVECHAMIENTO</strong></td>';
        $data .= '<td bgcolor="#939393" colspan="2" align="center"><strong>PROYECTOS ESCOLARES</strong></td>';
        $data .= '<td bgcolor="#F3FAA4" colspan="2" align="center"><strong>COMPORTAMIENTO</strong></td>';


        return $data;
    }

    private function parciales($modelAreas, $modelParalelo, $modelPeriodo, $colspan) {
        $sentencias = new InformeCalculoNotas();

        $data = '';

        foreach ($modelAreas as $area) {

            $modelMaterias = $sentencias->get_malla_materias_paralelo($modelParalelo->id, $modelPeriodo->codigo, $area['id']);

            if ($colspan == 8) {
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P1</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P2</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P3</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>PR</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>80%</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>EX1</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>20%</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>Q1</strong></td>';
            } else {
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P1</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P2</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P3</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>PR</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>80%</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>EX1</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>20%</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>Q1</strong></td>';

                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P4</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P5</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>P6</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>PR</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>80%</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>EX</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>20%</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>Q2</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>PF</strong></td>';
            }



            foreach ($modelMaterias as $materia) {

                if ($colspan == 8) {
                    $data .= '<td align="center"><strong>P1</strong></td>';
                    $data .= '<td align="center"><strong>P2</strong></td>';
                    $data .= '<td align="center"><strong>P3</strong></td>';
                    $data .= '<td align="center"><strong>PR</strong></td>';
                    $data .= '<td align="center"><strong>80%</strong></td>';
                    $data .= '<td align="center"><strong>EX1</strong></td>';
                    $data .= '<td align="center"><strong>20%</strong></td>';
                    $data .= '<td align="center"><strong>Q1</strong></td>';
                } else {
                    $data .= '<td align="center"><strong>P1</strong></td>';
                    $data .= '<td align="center"><strong>P2</strong></td>';
                    $data .= '<td align="center"><strong>P3</strong></td>';
                    $data .= '<td align="center"><strong>PR</strong></td>';
                    $data .= '<td align="center"><strong>80%</strong></td>';
                    $data .= '<td align="center"><strong>EX1</strong></td>';
                    $data .= '<td align="center"><strong>20%</strong></td>';
                    $data .= '<td align="center"><strong>Q1</strong></td>';

                    $data .= '<td align="center"><strong>P4</strong></td>';
                    $data .= '<td align="center"><strong>P5</strong></td>';
                    $data .= '<td align="center"><strong>P6</strong></td>';
                    $data .= '<td align="center"><strong>PR</strong></td>';
                    $data .= '<td align="center"><strong>80%</strong></td>';
                    $data .= '<td align="center"><strong>EX</strong></td>';
                    $data .= '<td align="center"><strong>20%</strong></td>';
                    $data .= '<td align="center"><strong>Q2</strong></td>';
                    $data .= '<td bgcolor="#C2E4F5" align="center"><strong>PF</strong></td>';
                }
            }
        }

        $data .= '<td bgcolor="#C59AFF"><strong>PROM. Q1</strong></td>';
        $data .= '<td bgcolor="#C59AFF"><strong>PROM. Q2</strong></td>';
        $data .= '<td bgcolor="#C59AFF"><strong>PROM. FINAL</strong></td>';

        $data .= '<td bgcolor="#939393" align="center"><strong>Q1</strong></td>';
        $data .= '<td bgcolor="#939393" align="center"><strong>Q2</strong></td>';
        $data .= '<td bgcolor="#F3FAA4" align="center"><strong>Q1</strong></td>';
        $data .= '<td bgcolor="#F3FAA4" align="center"><strong>Q2</strong></td>';


        return $data;
    }

    private function detalle_notas($modelAlumnos, $modelAreas, $modelParalelo, $modelPeriodo, $colspan) {

        $usuario = Yii::$app->user->identity->usuario;

        $data = '';

        $i = 0;
        foreach ($modelAlumnos as $alumno) {
            $i++;
            $data .= '<tr>';
            $data .= '<td align="center" align="center"><strong>' . $i . '</strong></td>';
            $data .= '<td align="center" align=""><strong>' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</strong></td>';


            $data .= $this->get_nota($alumno->id, $modelAreas, $modelParalelo, $modelPeriodo, $colspan, $usuario);

            $data .= '</tr>';
        }


        return $data;
    }

    private function get_nota($alumnoId, $modelAreas, $modelParalelo, $modelPeriodo, $colspan, $usuario) {
        $sentencias = new InformeCalculoNotas();
        $sentenciasLibreta = new \backend\models\SentenciasRepLibreta2();

        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();


        $data = '';

        foreach ($modelAreas as $area) {

            $data .= $this->devuelve_nota_area($colspan, $area, $usuario, $alumnoId);


            $modelMaterias = $sentencias->get_malla_materias_paralelo($modelParalelo->id, $modelPeriodo->codigo, $area['id']);
            foreach ($modelMaterias as $materia) {
                $data .= $this->devuelve_nota_materia($alumnoId, $materia, $modelPeriodo, $colspan);
            }
        }

        $notas = $sentenciasLibreta->get_notas_finales($alumnoId, $usuario, $modelMalla->malla_id);

        $data .= '<td bgcolor="#C59AFF" align="center"><strong>' . $notas['q1'] . '</strong></td>';
        $data .= '<td bgcolor="#C59AFF" align="center"><strong>' . $notas['q2'] . '</strong></td>';
        $data .= '<td bgcolor="#C59AFF" align="center"><strong>' . $notas['final_ano_normal'] . '</strong></td>';


        //nota de prooyectos
        $notaProyectos = $sentenciasLibreta->get_nota_proyectos($alumnoId, $modelPeriodo->codigo);
        if (isset($notaProyectos)) {
            $homologaProyectosp3 = $sentenciasLibreta->homologaProyectos($notaProyectos['p3']);
            $homologaProyectosp6 = $sentenciasLibreta->homologaProyectos($notaProyectos['p6']);

            $data .= '<td bgcolor="#939393" align="center"><strong>' . $homologaProyectosp3['abreviatura'] . '</strong></td>';
            $data .= '<td bgcolor="#939393" align="center"><strong>' . $homologaProyectosp6['abreviatura'] . '</strong></td>';
        } else {
            $data .= '<td bgcolor="#939393" align="center"><strong>--</strong></td>';
            $data .= '<td bgcolor="#939393" align="center"><strong>--</strong></td>';
        }


        //nota comportamiento

        $notaComporta = $sentenciasLibreta->get_notas_finales_comportamiento($alumnoId);

        if (isset($notaComporta)) {
            $data .= '<td bgcolor="#F3FAA4" align="center"><strong>' . $notaComporta[2] . '</strong></td>';
            $data .= '<td bgcolor="#F3FAA4" align="center"><strong>' . $notaComporta[5] . '</strong></td>';
        } else {
            $data .= '<td bgcolor="#F3FAA4" align="center"><strong>--</strong></td>';
            $data .= '<td bgcolor="#F3FAA4" align="center"><strong>--</strong></td>';
        }


        return $data;
    }

    private function devuelve_nota_materia($alumnoId, $materia, $modelPeriodo, $colspan) {
        $sentenciaNotas = new InformeCalculoNotas();
        $notas = $sentenciaNotas->calcula_nota($alumnoId, $materia['materia_id'], $modelPeriodo->codigo);


        $data = '';
        if ($colspan == 8) {
            $data .= '<td align="center">' . $notas['p1'] . '</td>';
            $data .= '<td align="center">' . $notas['p2'] . '</td>';
            $data .= '<td align="center">' . $notas['p3'] . '</td>';
            $data .= '<td align="center"><strong>' . $notas['pr1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['pr180'] . '</strong></td>';
            $data .= '<td align="center">' . $notas['ex1'] . '</td>';
            $data .= '<td align="center"><strong>' . $notas['ex120'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['q1'] . '</strong></td>';
        } else {
            $data .= '<td align="center">' . $notas['p1'] . '</td>';
            $data .= '<td align="center">' . $notas['p2'] . '</td>';
            $data .= '<td align="center">' . $notas['p3'] . '</td>';
            $data .= '<td align="center"><strong>' . $notas['pr1'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['pr180'] . '</strong></td>';
            $data .= '<td align="center">' . $notas['ex1'] . '</td>';
            $data .= '<td align="center"><strong>' . $notas['ex120'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['q1'] . '</strong></td>';

            $data .= '<td align="center">' . $notas['p4'] . '</td>';
            $data .= '<td align="center">' . $notas['p5'] . '</td>';
            $data .= '<td align="center">' . $notas['p6'] . '</td>';
            $data .= '<td align="center"><strong>' . $notas['pr2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['pr280'] . '</strong></td>';
            $data .= '<td align="center">' . $notas['ex2'] . '</td>';
            $data .= '<td align="center"><strong>' . $notas['ex220'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['q2'] . '</strong></td>';
            $data .= '<td align="center"><strong>' . $notas['final_ano_normal'] . '</strong></td>';
        }

        return $data;
    }

    private function devuelve_nota_area($colspan, $mallaArea, $usuario, $alumnoId) {
        $sentenciasLibreta2 = new SentenciasRepLibreta2();
        $notas = $sentenciasLibreta2->get_nota_por_area($alumnoId, $usuario, $mallaArea['id']);

        $data = '';

        if ($colspan == 8) {
            if ($mallaArea['promedia'] == true) {
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p1'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p2'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p3'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['pr1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['pr180'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['ex1'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['ex120'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['q1'] . '</strong></td>';
            } else {
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            }
        } else {
            if ($mallaArea['promedia'] == true) {
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p1'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p2'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p3'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['pr1'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['pr180'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['ex1'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['ex120'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['q1'] . '</strong></td>';

                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p4'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p5'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['p6'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['pr2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['pr280'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center">' . $notas['ex2'] . '</td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['ex220'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['q2'] . '</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>' . $notas['final_ano_normal'] . '</strong></td>';
            } else {
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
                $data .= '<td bgcolor="#DDF5C2" align="center"><strong>*</strong></td>';
            }
        }



        return $data;
    }

}
