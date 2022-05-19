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
class InformeSabanaQuimestral extends \yii\db\ActiveRecord {

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function genera_reporte($paralelo, $quimestre) {



        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();
        $sentenciasAl = new \backend\models\SentenciasAlumnos();
        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $malla = $modelMalla->malla_id;
        $clases = $sentencias->clases_paralelo($paralelo);

        $sentencias->procesarAreas($modelParalelo->course_id, $paralelo);

        $modelMateriasNormales = $this->consulta_materias_normales($paralelo, "'OPTATIVAS', 'NORMAL'", $modelPeriodo->codigo);
        $modelMateriasProyecto = $this->consulta_materias_normales($paralelo, "'PROYECTOS'", $modelPeriodo->codigo);
        $modelMateriasComporta = $this->consulta_materias_normales($paralelo, "'COMPORTAMIENTO'", $modelPeriodo->codigo);


//        $modelAlmunos = OpStudent::find()
//                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
//                ->where([
//                    'op_student_inscription.parallel_id' => $paralelo
//                ])
//                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
//                ->all();

        $modelAlmunos = $sentenciasAl->get_alumnos_paralelo_todos($paralelo);


        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();

        $minima = $modelParametros->valor;




        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($paralelo, $quimestre);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        //foreach ($modelAlmunos as $data) {

        $html = $this->genera_cuerpo_pdf($paralelo, $malla, $minima, $quimestre, $modelMateriasNormales, $modelAlmunos, $modelMateriasProyecto, $modelMateriasComporta);
//
        $mpdf->WriteHTML($html);
//            $mpdf->addPage();
        // }
//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function consulta_materias_normales($paralelo, $condicion, $periodoCodigo) {
        $con = Yii::$app->db;
        $query = "select 	m.id
		,m.abreviarura   
                ,mm.promedia 
from	op_student_inscription i
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
where	i.parallel_id = $paralelo
		and i.inscription_state = 'M'
		and mm.tipo in ($condicion)
                and c.periodo_scholaris = '$periodoCodigo'
group by m.id, m.name, mm.promedia 
order by m.id asc;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function genera_cabecera_pdf($paralelo, $quimestre) {
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        $informe = $quimestre == 'q1' ? 'INFORME DEL PRIMER QUIMESTRE DE APRENDIZAJE Y COMPORTAMIENTO' : 'INFORME FINAL DE APRENDIZAJE Y COMPORTAMIENTO';

        $html = '';


        $html .= '<table width="100%" cellspacing="0" style="font-size:12px">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
        $html .= '<td align="center"><strong>' . $modelParalelo->institute->name . '</strong>';
        $html .= '<br><strong>AÑO LECTIVO ' . $modelPeriodo->codigo . '</strong>';
        $html .= '<br><strong>' . $informe . '</strong>';
        $html .= '</td>';
        $html .= '<td align="center" width="20%"></td>';
        $html .= '<tr>';
        $html .= '</table>';


        return $html;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= 'Elaborado por: ' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($paralelo, $malla, $minima, $quimestre, $modelMateriasNormales, $modelAlmunos, $modelMateriasProyecto, $modelMateriasComporta) {

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $html = '<style>';
        $html .= '.rotar90{font-size:30px;text-rotate="45"}';
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
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '</style>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano10">' . $modelParalelo->course->xTemplate->name . ' "' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->genera_cuerpo_pdf_detalle_materias($malla, $paralelo, $minima, $quimestre, $modelMateriasNormales, $modelAlmunos, $modelMateriasProyecto, $modelMateriasComporta);

        return $html;
    }

    public function genera_cuerpo_pdf_detalle_materias($malla, $paralelo, $minima, $quimestre, $modelMateriasNormales, $modelAlmunos, $modelMateriasProyecto, $modelMateriasComporta) {

        $html = '';

        $html .= '<table width="100%" cellpadding="2" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">ORD</td>';
        $html .= '<td class="tamano10 conBorde centrarTexto" width="300px">APELLIDOS NOMBRES</td>';

        foreach ($modelMateriasNormales as $matN) {
            if ($matN['promedia'] == true) {
                $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">' . $matN['abreviarura'] . '</td>';
            } else {
                $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">* ' . $matN['abreviarura'] . '</td>';
            }
        }


        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan=""><strong>PROMEDIO</strong></td>';

        foreach ($modelMateriasProyecto as $matP) {
            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">' . $matP['abreviarura'] . '</td>';
        }

        foreach ($modelMateriasComporta as $matC) {
            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="">' . $matC['abreviarura'] . '</td>';
        }

        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan=""><strong>OBSERVACIÓN</strong></td>';

        $html .= $this->asignaturas($modelAlmunos, $minima, $quimestre, $modelMateriasNormales, $modelMateriasProyecto, $modelMateriasComporta, $paralelo);

        $html .= '</table>';
//        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $quimestre, $paralelo);
//        $html .= $this->firmas();

        return $html;
    }

    private function asignaturas($modelAlmunos, $minima, $quimestre, $modelMateriasNormales, $modelMateriasProyecto, $modelMateriasComporta, $paralelo) {

        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);
        
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $modelMallaCurso = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();
        
        $malla = $modelMallaCurso->malla_id;
        

        $html = '';

        $i = 0;
        foreach ($modelAlmunos as $alumno) {
            $i++;
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto">' . $i . '</td>';
            $html .= '<td class="conBorde">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';
            $html .= $this->revisa_notas_alumno($alumno, $minima, $quimestre, $modelMateriasNormales, $modelMateriasProyecto, $modelMateriasComporta, $malla);
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto" colspan="2"><strong>PROMEDIOS GENERALES:</strong></td>';
        $html .= $this->promedios_generales($modelMateriasNormales, $paralelo, $modelPeriodo->codigo, $quimestre);
        $html .= '</tr>';

        return $html;
    }

    private function promedios_generales($modelMateriasNormales,$paralelo , $periodoCodigo, $quimestre) {
        $sentencias = new Notas();
        $sentenciasNotas = new InformeCalculoNotas();
        $campo = $quimestre == 'q1' ? 'q1' : 'final_ano_normal';
        
        
        $html = '';
        
        foreach ($modelMateriasNormales as $normal) {
        
            $nota = $sentenciasNotas->calcula_promedio_materia($paralelo, $normal['id'], $periodoCodigo);
           
            $html .= '<td class="conBorde centrarTexto"><strong>' . $nota[$campo] . '</strong></td>';
        }
        
        
        $promedio = $sentenciasNotas->calcula_promedio_paralelo($paralelo, $periodoCodigo);
        
        $html .= '<td class="conBorde centrarTexto"><strong>'.$promedio[$campo].'</strong></td>';
        //$html .= '<td class="conBorde centrarTexto" bgcolor="#CCCCCC" colspan=""></td>';
        
        return $html;
    }

    private function revisa_notas_alumno($alumno, $minima, $quimestre, $modelMateriasNormales, $modelMateriasProyecto, $modelMateriasComporta, $malla) {
        $sentencias = new SentenciasRepLibreta2();
        $sentenciasNotas = new InformeCalculoNotas();
       
        $usuario = \Yii::$app->user->identity->usuario;
        
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        $html = '';

        $campo = $quimestre == 'q1' ? 'q1' : 'final_ano_normal';


        foreach ($modelMateriasNormales as $normal) {
            if ($alumno['inscription_state'] == 'M') {
                $notas = $sentenciasNotas->calcula_nota($alumno['id'], $normal['id'], $modelPeriodo->codigo);
                if($notas[$campo] < $minima){
                    $html .= '<td class="tamano10 conBorde centrarTexto" bgcolor="#F7D9E6">' . $notas[$campo] . '</td>';
                }else{
                    $html .= '<td class="tamano10 conBorde centrarTexto">' . $notas[$campo] . '</td>';
                }
                
            } else {
                $html .= '<td class="tamano10 conBorde centrarTexto">--</td>';
            }
        }

        if ($alumno['inscription_state'] == 'M') {
            //$promedio = $sentenciasNotas->calcula_promedio($alumno['id'], $modelPeriodo->codigo);
            $promedio = $sentencias->get_notas_finales($alumno['id'], $usuario, $malla);
            if($promedio[$campo] < $minima){
                $html .= '<td class="tamano10 conBorde centrarTexto" bgcolor="#F7D9E6"><strong>' . $promedio[$campo] . '</strong></td>';
            }else{
                $html .= '<td class="tamano10 conBorde centrarTexto"><strong>' . $promedio[$campo] . '</strong></td>';
            }
            
        } else {
            $html .= '<td class="tamano10 conBorde centrarTexto"><strong>--</strong></td>';
        }



        foreach ($modelMateriasProyecto as $normal) {
            
            $campoNota = $campo == 'q1' ? 'p3' : 'p6';
            
            if ($alumno['inscription_state'] == 'M') {
                $notas = $sentenciasNotas->calcula_nota_proyectos($alumno['id'], $normal['id'], $modelPeriodo->codigo);
                $homologaP = $sentencias->homologaProyectos($notas[$campoNota]);

                $html .= '<td class="tamano10 conBorde centrarTexto">' . $homologaP['abreviatura'] . '</td>';
            } else {
                $html .= '<td class="tamano10 conBorde centrarTexto">--</td>';
            }
        }

        foreach ($modelMateriasComporta as $normal) {
            if ($alumno['inscription_state'] == 'M') {

                if ($campo == 'q1') {
                    $campoComp = 'p3';
                } else {
                    $campoComp = 'p6';
                }

                $notas = $sentenciasNotas->calcula_nota($alumno['id'], $normal['id'], $modelPeriodo->codigo);

                $homologaC = $sentencias->homologaComportamiento($notas[$campoComp]);

                $html .= '<td class="tamano10 conBorde centrarTexto">' . $homologaC['abreviatura'] . '</td>';
            } else {
                $html .= '<td class="tamano10 conBorde centrarTexto">--</td>';
            }
        }


        if ($alumno['inscription_state'] == 'M') {
            $html .= '<td class="tamano10 conBorde centrarTexto"></td>';
        } else {
            $html .= '<td class="tamano10 conBorde centrarTexto">RETIRADO</td>';
        }

        return $html;
    }

    

    

    private function materias($area, $alumno, $usuario, $minima, $quimestre) {

        $sentencia = new \backend\models\SentenciasRepLibreta2();
        $modelMaterias = $sentencia->get_materias_alumno($area, $alumno);


        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';

        foreach ($modelMaterias as $clase) {
            if ($clase['se_imprime'] == true) {
                $html .= '<tr>';

                if ($clase['promedia'] == true) {
                    $html .= '<td class="tamano8 conBorde">   ' . $clase['materia'] . '</td>';
                } else {
                    $html .= '<td class="tamano8 conBorde">   *' . $clase['materia'] . '</td>';
                }


                $notas = $sentencia->get_notas_por_materia($clase['clase_id'], $alumno);
//

                switch ($quimestre) {
                    case 'p1':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p2':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p3':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                        $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                        $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'q1':
                        $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                        $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                        $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                        $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                        $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex120'] . '</td>';
                        $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>';

                        $homol = $this->homologa_promedio($notas['q1']);

                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $homol['abreviatura'] . '</strong></td>';
                        break;



                    case 'p4':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p5':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'p6':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                        $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                        $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;

                    case 'q2':
                        $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                        $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                        $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                        $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                        $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
                        $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';
                        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                        break;
                }

                $html .= '</tr>';
            }
        }

        return $html;
    }

    public function aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima, $quimestre) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde"><strong>PROMEDIOS:</strong></td>';

        $notas = $sentencias->get_notas_finales($alumno, $usuario, $malla);


        switch ($quimestre) {
            case 'p1':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p2':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p3':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'q1':
                $html .= $notas['p1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
                $html .= $notas['p2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p2'] . '</td>';
                $html .= $notas['p3'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p3'] . '</td>';
                $html .= $notas['pr1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr180'] . '</td>';
                $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex120'] . '</td>';
                $html .= $notas['q1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;



            case 'p4':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p5':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'p6':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;

            case 'q2':
                $html .= $notas['p4'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p4'] . '</td>';
                $html .= $notas['p5'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p5'] . '</td>';
                $html .= $notas['p6'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p6'] . '</td>';
                $html .= $notas['pr2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['pr280'] . '</td>';
                $html .= $notas['ex1'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex1'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['ex220'] . '</td>';
                $html .= $notas['q2'] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' . $notas['q2'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd"><strong>--</strong></td>';
                break;
        }

        $html .= '</tr>';

        return $html;
    }

    public function proyectos($alumno, $usuario, $modelBloque, $malla, $quimestre) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde">PROYECTOS ESCOLARES:</td>';

        $notas = $sentencias->get_notas_cualitativas($alumno, $usuario, $malla, 'PROYECTOS');
        $p1 = $sentencias->homologaProyectos($notas['p1']);
        $p2 = $sentencias->homologaProyectos($notas['p2']);
        $p3 = $sentencias->homologaProyectos($notas['p3']);
        $pr1 = $sentencias->homologaProyectos($notas['pr1']);
        $pr180 = $sentencias->homologaProyectos($notas['pr180']);
        $ex1 = $sentencias->homologaProyectos($notas['ex1']);
        $ex120 = $sentencias->homologaProyectos($notas['ex120']);
        $q1 = $sentencias->homologaProyectos($notas['q1']);

        $p4 = $sentencias->homologaProyectos($notas['p4']);
        $p5 = $sentencias->homologaProyectos($notas['p5']);
        $p6 = $sentencias->homologaProyectos($notas['p6']);
        $pr2 = $sentencias->homologaProyectos($notas['pr2']);
        $pr280 = $sentencias->homologaProyectos($notas['pr280']);
        $ex2 = $sentencias->homologaProyectos($notas['ex2']);
        $ex220 = $sentencias->homologaProyectos($notas['ex220']);
        $q2 = $sentencias->homologaProyectos($notas['q2']);

        $final_ano_normal = $sentencias->homologaProyectos($notas['final_ano_normal']);


        switch ($quimestre) {
            case 'p1':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p2':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p3':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'q1':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p2['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p3['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $q1['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;





            case 'p4':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p5':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p6':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'q2':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p4['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p5['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $p6['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $q2['abreviatura'] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;
        }




        $html .= '</tr>';

        return $html;
    }

    public function comportamiento($alumno, $usuario, $modelBloque, $malla, $quimestre) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();


        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano8 conBorde">COMPORTAMIENTO:</td>';

        $notas = $sentencias->get_notas_finales_comportamiento($alumno);

        switch ($quimestre) {
            case 'p1':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p2':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p3':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'q1':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[0] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[1] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[2] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;


            case 'p4':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p5':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'p6':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;

            case 'q2':
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[3] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[4] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[5] . '</td>';
                $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">--</td>';
                break;
        }



        $html .= '</tr>';

        return $html;
    }

    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $quimestre, $paralelo) {

        $sentenciasFaltas = new SentenciasFaltas();

        $html = '';
        $html .= '<br>';

        /**
         * FALTAS Y ATRASOS
         */
        $datosFaltas = $sentenciasFaltas->devuelve_faltas_a_libreta($alumno, $quimestre, $paralelo);

        $presentes = $datosFaltas[3] - ($datosFaltas[1] + $datosFaltas[2]);

        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" rowspan="2">ASISTENCIA</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Atraso</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Justificada</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Falta Injustificada</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">Presente</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[0] . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[1] . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $datosFaltas[2] . '</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="" align="center">' . $presentes . '</td>';
        $html .= '</tr>';
        $html .= '</table>';


        /*         * *
         * ESCALAS comportamiento
         */
        $html .= '<br>';
        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" colspan="">EQUIVALENCIA DE COMPORTAMIENTO</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">DESDE</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">HASTA</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">DESCRIPCIÓN</td>';
        $html .= '</tr>';

        foreach ($modelEscalasComportamiento as $comp) {
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['abreviatura'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['rango_maximo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['descripcion'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';



        /*         * *
         * ESCALAS APROVECHAMIENTO y proyectos
         */
        $html .= '<br>';

        $html .= '<table width="100%" height="" cellpadding="3" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" colspan="">APROVECHAMIENTO</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">DESDE</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">HASTA</td>';
        $html .= '<td class="centrarTexto conBorde" colspan="">DESCRIPCIÓN</td>';
        $html .= '</tr>';

        foreach ($modelEscalasApro as $proy) {
            $html .= '<tr>';
            $html .= '<td class="conBorde" align="center">' . $proy['abreviatura'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proy['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proy['rango_maximo'] . '</td>';
            $html .= '<td class="conBorde">' . $proy['descripcion'] . '</td>';
            $html .= '</tr>';
        }

        foreach ($modelEscalasProyectos as $proyectos) {
            $html .= '<tr>';
            $html .= '<td class="conBorde" align="center">' . $proyectos['abreviatura'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proyectos['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proyectos['rango_maximo'] . '</td>';
            $html .= '<td class="conBorde">' . $proyectos['descripcion'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    private function firmas() {
        $html = '';

        $html .= '<br>';
        $html .= '<p>OBSERVACIÓN: ________________________________________________________________________________________</p>';
        $html .= '<p>________________________________________________________________________________________________________</p>';


        $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
//        $html .= '<td width="34%" class=""></td>';
//        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="centrarTexto">Tutor (a)</td>';
//        $html .= '<td width="34%" class=""></td>';
//        $html .= '<td width="33%" class="centrarTexto">TUTORÍA</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    //////SENTENCIAS DE EXTRACCION DE DATOS
    private function get_materias($paralelo) {
        
    }

}
