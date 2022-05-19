<?php

namespace backend\controllers;

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
class ScholarisRepParcialController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function actionIndex() {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasNotas = new \backend\models\NotasEnLibreta();


        $curso = $_GET['curso'];
        $paralelo = $_GET['paralelo'];
        $bloque = $_GET['bloque'];
        $alumno = '';
        
        $sentenciasR = new \backend\models\SentenciasRecalcularUltima();
        $sentenciasR->por_paralelo($paralelo);


        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();       

        $malla = $modelMalla->malla_id;

        if ($alumno) {
            $clases = $sentencias->clases_alumno($alumno);
        } else {
            $clases = $sentencias->clases_paralelo($paralelo);
        }


        $sentencias->procesarAreas($curso, $paralelo);

        return $this->redirect(['pdf', "paralelo" => $paralelo, "alumno" => $alumno, 'malla' => $malla, 'bloque' => $bloque]);
    }

    public function actionPdf($paralelo, $alumno, $malla, $bloque) {

        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $modelBloque = ScholarisBloqueActividad::findOne($bloque);

        $modelEscalasProyectos = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'PROYECTOS'
                ])
                ->orderBy('rango_minimo')
                ->all();

        $modelEscalasApro = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'APROVECHAMIENTO'
                ])
                ->orderBy('rango_minimo')
                ->all();


        $modelEscalasComportamiento = \backend\models\ScholarisTablaEscalasHomologacion::find()
                ->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'COMPORTAMIENTO',
                    'section_codigo' => $modelParalelo->course->section0->code
                ])
                ->orderBy('abreviatura')
                ->all();




        if ($alumno) {
            $modelAlmunos = OpStudent::find()
                    ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                    ->where([
                        'op_student_inscription.student_id' => $alumno,
                        'op_student_inscription.parallel_id' => $paralelo
                    ])
                    ->all();
        } else {
            $modelAlmunos = OpStudent::find()
                    ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                    ->where([
                        'op_student_inscription.parallel_id' => $paralelo
                    ])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                    ->all();
        }


        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();

        $minima = $modelParametros->valor;

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 35,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($modelBloque);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
        $mpdf->showImageErrors = true;





        foreach ($modelAlmunos as $data) {

            $html = $this->genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $bloque);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }



//        $mpdf->addPage();
        $mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($modelBloque) {
        $instituto = Yii::$app->user->identity->instituto_defecto;

        $fecha = date("Y-m-d");

        $modelInstituto = \backend\models\OpInstitute::findOne($instituto);
        $html = '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td width="25%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';

        $html .= '<td><center>';
        $html .= '<p>' . $modelInstituto->name . '</p>';
        $html .= '<p style="font-size:10px">INFORME DE APRENDIZAJE - ' . $modelBloque->quimestre . ' - ' . $modelBloque->name . '</p>';
        $html .= '<p style="font-size:10px">AÑO LECTIVO - ' . $modelBloque->scholaris_periodo_codigo . '</p>';
        $html .= '<p style="font-size:10px">Fecha de emisión - ' . $fecha . '</p>';
        $html .= '</center>';

        $html .= '<td width="25%" style="text-align: right;">';
//        $html .= '<p style="font-size:8px">' . $modelParalelo->course->name . ' - ' . $modelParalelo->name . '</p>';
//        $html .= '<p style="font-size:7px">Año lectivo: ' . $modelPeriodo->nombre . '</p>';

        $html .= '</tr>';
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

    private function genera_cuerpo_pdf($data, $paralelo, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro, $bloque) {

        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
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
                    font-size: 11px;
                  }
                  
                .tamano10{
                    font-size: 12px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }

                    ';
        $html .= '</style>';

        $html .= '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td class="tamano10">ESTUDIANTE: ' . $data->last_name . ' ' . $data->first_name . ' ' . $data->middle_name . '</td>';
        $html .= '<td class="tamano10 derechaTexto">CURSO: ' . $modelParalelo->course->name . ' "' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->genera_cuerpo_pdf_detalle_materias($bloque, $data->id, $malla, $paralelo,
                $modelEscalasProyectos, $modelEscalasComportamiento,
                $minima, $modelEscalasApro);

        return $html;
    }

    public function genera_cuerpo_pdf_detalle_materias($bloque, $alumno, $malla, $paralelo, $modelEscalasProyectos, $modelEscalasComportamiento, $minima, $modelEscalasApro) {

        $sentencias = new \backend\models\SentenciasRepInsumos();

        $modelBloque = ScholarisBloqueActividad::findOne($bloque);

        $modelInsumos = $sentencias->get_grupo_insumos();

        $totalIns = count($modelInsumos) + 1;
        $totalPor = number_format(70 / $totalIns, 2);

        $html = '';

        $html .= '<table width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="" width="30%"><strong>ASIGNATURAS</strong></td>';

        foreach ($modelInsumos as $ins) {
            $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="" width="' . $totalPor . '"><strong>' . $ins['nombre_nacional'] . '</strong></td>';
        }

//        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="8">QUIMESTRE I</td>';
//        $html .= '<td class="tamano10 conBorde centrarTexto" colspan="8">QUIMESTRE II</td>';
//        $html .= '<td class="tamano10 conBorde centrarTexto" rowspan="2">FIN</td>';
//        $html .= '</tr>';


        $html .= '<td class="tamano10 conBorde centrarTexto" width="' . $totalPor . '"><strong>' . $modelBloque->name . '</strong></td>';

        $html .= '</tr>';

        $html .= $this->asignaturas($alumno, $malla, $minima, $modelBloque, $modelInsumos);
        $html .= '</table>';

        $html .= '<p class="tamano10">Las asignaturas marcadas con asterisco (*) no promedian al aprovechamiento</p>';

        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $paralelo, $bloque);
        $html .= $this->firmas();


//        $html .= $this->cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento);

        return $html;
    }

    private function asignaturas($alumno, $malla, $minima, $modelBloque, $modelInsumos) {


        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;

        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelMalla = \backend\models\ScholarisMalla::find()->where(['id' => $malla])->one();



        $modelMallaArea = $sentencias->get_areas_alumno($alumno, 'NORMAL');

        $campo = $this->toma_campo($modelBloque);

        $colorBajo = "#F7BEB2";
        $colorSin = "";


        $html = '';

        foreach ($modelMallaArea as $area) {

            if ($area['se_imprime'] == true) {
                $html .= "<tr>";
                $html .= '<td class="tamano10 conBorde"><strong>' . $area['area'] . '</strong></td>';

                $notas = $sentencias->get_nota_por_area($alumno, $usuario, $area['id']);
//
                for($k = 0;$k<count($modelInsumos);$k++){
                    $html .= '<td></td>';
                }
                
                $html .= $notas[$campo] < $minima ? '<td bgcolor="' . $colorBajo 
                        . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' 
                        . $notas[$campo] . '</strong></td>' : '<td bgcolor="' 
                        . $colorSin . '" class="tamano10 conBorde centrarTexto paddingTd">' . $notas[$campo] . '</td>';


                $html .= "</tr>";
            }

            $html .= $this->materias($area['id'], $alumno, $usuario, $minima, $modelBloque, $modelInsumos);
        }

        $html .= $this->aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima,$modelInsumos);

        $verificaProyectos = \backend\models\ScholarisMallaArea::find()->where(['malla_id' => $malla, 'tipo' => 'PROYECTOS'])->one();
        $verificaDhi = \backend\models\ScholarisMallaArea::find()->where(['malla_id' => $malla, 'tipo' => 'DHI'])->one();
        
//        print_r($verificaProyectos);
//        die();
        if ($verificaProyectos) {
            $html .= $this->proyectos($alumno, $usuario, $modelBloque, $malla,$modelInsumos);
        }
        
        if ($verificaProyectos) {
            $html .= $this->dhi($alumno, $usuario, $modelBloque, $malla,$modelInsumos);
        }

        $html .= $this->comportamiento($alumno, $usuario, $modelBloque, $malla,$modelInsumos);


        return $html;
    }

    private function toma_campo($modelBloque) {
        switch ($modelBloque->orden) {
            case 1:
                $campo = 'p1';
                break;
            case 2:
                $campo = 'p2';
                break;
            case 3:
                $campo = 'p3';
                break;
            case 4:
                $campo = 'ex1';
                break;
            case 5:
                $campo = 'p4';
                break;
            case 6:
                $campo = 'p5';
                break;
            case 7:
                $campo = 'p6';
                break;
            case 8:
                $campo = 'ex2';
                break;
        }

        return $campo;
    }

    private function toma_campo_comportamiento($modelBloque) {
        switch ($modelBloque->orden) {
            case 1:
                $campo = 0;
                break;
            case 2:
                $campo = 1;
                break;
            case 3:
                $campo = 2;
                break;
            case 4:
                $campo = 2;
                break;
            case 5:
                $campo = 3;
                break;
            case 6:
                $campo = 4;
                break;
            case 7:
                $campo = 5;
                break;
            case 8:
                $campo = 5;
                break;
        }

        return $campo;
    }

    private function materias($area, $alumno, $usuario, $minima, $modelBloque, $modelInsumos) {

        $sentencia = new \backend\models\SentenciasRepLibreta2();
        $sentencias = new \backend\models\SentenciasRepInsumos();
        $modelMaterias = $sentencia->get_materias_alumno($area, $alumno);

        $campo = $this->toma_campo($modelBloque);

        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $html = '';

        $totalIns = count($modelInsumos) + 1;
        $totalPor = number_format(70 / $totalIns, 2);

        foreach ($modelMaterias as $clase) {
            if ($clase['se_imprime'] == true) {
                $html .= '<tr>';

                if ($clase['promedia'] == 1) {
                    $asterisco = ' ';
                } else {
                    $asterisco = ' * ';
                }

                $html .= '<td class="tamano10 conBorde">' . $asterisco . $clase['materia'] . '</td>';

//                echo $clase['id'];
//                die();
                foreach ($modelInsumos as $ins) {

                    $notaInsumo = $sentencias->get_promedio_insumo($clase['clase_id'], $alumno, $ins['grupo_numero'], $modelBloque->id);
                    
                    
                    

                    $html .= '<td class="tamano8 conBorde" align="center">' . $notaInsumo . '</td>';
                }

                $notas = $sentencia->get_notas_por_materia($clase['clase_id'], $alumno);
                $html .= $notas[$campo] < $minima ? '<td bgcolor="' . $colorBajo .
                        '" class="tamano8 conBorde centrarTexto paddingTd">' .
                        $asterisco . $notas[$campo] . '</td>' : '<td bgcolor="' .
                        $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd">' .
                        $asterisco . $notas[$campo] . '</td>';
                $html .= '</tr>';
            }
        }

        return $html;
    }

    public function aprovechamiento($alumno, $usuario, $modelBloque, $malla, $minima,$modelInsumos) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $colorBajo = "#F7BEB2";
        $colorSin = "";

        $campo = $this->toma_campo($modelBloque);

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano10 conBorde"><strong>APROVECHAMIENTO:</strong></td>';

        $notas = $sentencias->get_notas_finales($alumno, $usuario, $malla);
//
//        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas['p1'] . '</td>';
        for($k=0;$k<count($modelInsumos);$k++){
            $html .= '<td></td>';
        }
        $html .= $notas[$campo] < $minima ? '<td bgcolor="' . $colorBajo . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[$campo] . '</strong></td>' : '<td bgcolor="' . $colorSin . '" class="tamano8 conBorde centrarTexto paddingTd"><strong>' . $notas[$campo] . '</strong></td>';

        $html .= '</tr>';

        return $html;
    }

    public function proyectos($alumno, $usuario, $modelBloque, $malla,$modelInsumos) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();
        
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        $campo = $this->toma_campo($modelBloque);

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano10 conBorde">PROYECTOS ESCOLARES:</td>';

        //$notas = $sentencias->get_notas_cualitativas($alumno, $usuario, $malla, 'PROYECTOS');
        $notas = $sentencias->get_nota_proyectos($alumno, $modelPeriodo->codigo);
        $p1 = $sentencias->homologaProyectos($notas[$campo]);

        for($k=0; $k<count($modelInsumos);$k++){
            $html .= '<td></td>';
        }
        $html .= '<td class="tamano10 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
//        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[$campo] . '</td>';

        $html .= '</tr>';

        return $html;
    }
    
    
    public function dhi($alumno, $usuario, $modelBloque, $malla,$modelInsumos) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();
        
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);
        $modelMallaA = \backend\models\ScholarisMallaArea::find()->where(['malla_id' => $malla, 'tipo' => 'DHI'])->one();
        
        
        $campo = $this->toma_campo($modelBloque);

        if($modelMallaA){
        $modelMallaMat = \backend\models\ScholarisMallaMateria::find()->where(['malla_area_id' => $modelMallaA->id, 'tipo' => 'DHI'])->one();
            
        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano10 conBorde">'.$modelMallaMat->materia->name.'</td>';

        //$notas = $sentencias->get_notas_cualitativas($alumno, $usuario, $malla, 'PROYECTOS');
        $notas = $sentencias->get_nota_dhi($alumno, $modelPeriodo->codigo);
        $p1 = $sentencias->homologaProyectos($notas[$campo]);

        for($k=0; $k<count($modelInsumos);$k++){
            $html .= '<td></td>';
        }
        $html .= '<td class="tamano10 conBorde centrarTexto paddingTd">' . $p1['abreviatura'] . '</td>';
//        $html .= '<td class="tamano8 conBorde centrarTexto paddingTd">' . $notas[$campo] . '</td>';

        $html .= '</tr>';

        return $html;
        }
    }
    

    public function comportamiento($alumno, $usuario, $modelBloque, $malla, $modelInsumos) {
        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $campo = $this->toma_campo_comportamiento($modelBloque);

        $html = '';
        $html .= '<tr>';

        $html .= '<td class="tamano10 conBorde">COMPORTAMIENTO:</td>';

        $notas = $sentencias->get_notas_finales_comportamiento($alumno);

        for($k=0; $k<count($modelInsumos); $k++){
            $html .= '<td></td>';
        }
        $html .= '<td class="tamano10 conBorde centrarTexto paddingTd">' . $notas[$campo] . '</td>';

        $html .= '</tr>';

        return $html;
    }
    
    
    private function busca_faltas_inspeccion($alumno, $bloque){
        $con = Yii::$app->db;
        $query = "select
	(select 	count(*) as atrasos 
		from 	scholaris_toma_asistecia_detalle d
		inner join scholaris_toma_asistecia a on a.id = d.toma_id
		where	d.alumno_id = $alumno
		and a.bloque_id = $bloque
		and atraso = true)
	,(select 	count(*) as falta 
			from 	scholaris_toma_asistecia_detalle d
			inner join scholaris_toma_asistecia a on a.id = d.toma_id
			where	d.alumno_id = $alumno
			and a.bloque_id = $bloque
			and falta = true)
	,(select 	count(*) as falta_justificada 
		from 	scholaris_toma_asistecia_detalle d
				inner join scholaris_toma_asistecia a on a.id = d.toma_id
		where	d.alumno_id = $alumno
				and a.bloque_id = $bloque
				and falta_justificada = true);";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function cuadros_y_faltas_atrasos($alumno, $malla, $modelEscalasProyectos, $modelEscalasComportamiento, $modelEscalasApro, $paralelo, $bloque) {

        $sentencias = new \backend\models\SentenciasRepLibreta2();
        $sentenciasFaltas = new \backend\models\SentenciasFaltas();
        $modelBloque = ScholarisBloqueActividad::findOne($bloque);
        
        $datosAsistencia = $sentenciasFaltas->entrega_faltas_parcial($alumno, $bloque, $paralelo);

        $totalDias = $modelBloque->dias_laborados;


        $totalAsistido = $totalDias - $datosAsistencia[2];


        $html = '';


        $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
        $html .= '<tr>';

        /**
         * FALTAS Y ATRASOS
         */
        $html .= '<td width="33%" class="" valign="top">';
        $html .= '<table height="300" width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto conBorde" colspan="2">FALTAS Y ATRASOS</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">ATRASOS:</td>';
        $html .= '<td class="conBorde" align="center">' . $datosAsistencia[0] . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">F. JUSTIFICADAS:</td>';
        $html .= '<td class="conBorde" align="center">' . $datosAsistencia[1] . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">F. INJUSTIFICADAS:</td>';
        $html .= '<td class="conBorde" align="center">' . $datosAsistencia[2] . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">DIAS ASISTIDOS:</td>';
        $html .= '<td class="conBorde" align="center">' . $totalAsistido . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde">DIAS LABORADOS:</td>';
        $html .= '<td class="conBorde" align="center">' . $totalDias . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</td>';

        /*         * *
         * ESCALAS APROVECHAMIENTO
         */
        $html .= '<td width="33%" valign="top">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto" colspan="3">ESCALAS DE APROVECHAMIENTO</td>';
        $html .= '</tr>';


        foreach ($modelEscalasApro as $proy) {
            $html .= '<tr>';
            $html .= '<td class="conBorde">' . $proy['abreviatura'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proy['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $proy['rango_maximo'] . '</td>';
            $html .= '</tr>';
        }




        $html .= '</table>';
        $html .= '</td>';

        /**
         * ESCALAS COMPORTAMIENTO
         */
        $html .= '<td width="34%" valign="top">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" class="conBorde">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto">ESCALAS DE COMPORTAMIENTO</td>';
        $html .= '</tr>';

        foreach ($modelEscalasComportamiento as $comp) {
            $html .= '<tr>';
            $html .= '<td class="conBorde" align="center">' . $comp['abreviatura'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['rango_minimo'] . '</td>';
            $html .= '<td class="conBorde centrarTexto">' . $comp['rango_maximo'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</td>';


        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function firmas() {
        $html = '';
        $html .= '<br>';

        $html .= '<table width="100%" height="300" cellpadding="10" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
        $html .= '<td width="34%" class=""></td>';
        $html .= '<td width="33%" class="centrarTexto">_______________________________</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="33%" class="centrarTexto">SECRETARÍA</td>';
        $html .= '<td width="34%" class=""></td>';
        $html .= '<td width="33%" class="centrarTexto">TUTOR (A)</td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

}
