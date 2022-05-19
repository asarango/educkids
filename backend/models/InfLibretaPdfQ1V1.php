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
class InfLibretaPdfQ1V1 extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $quimestre;
    private $periodoId;
    private $periodoCodigo;
    private $modelAlumnos;
    private $usuario;
    private $mallaId;
    private $modelBloquesQ1;
    private $modelBloquesEx1;
    private $seccion;
    private $observacion;
    private $totalDias = 0;

    public function __construct($paralelo, $alumno, $quimestre) {

        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;

        $sentencias = new SentenciasAlumnos();
        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $modelParalelo->course->section0->code;
        
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();
        $this->mallaId = $modelMalla->malla_id;

        $this->quimestre = $quimestre;
        $this->paralelo = $paralelo;

        $this->usuario = Yii::$app->user->identity->usuario;

        if (!$alumno > 0 || !$alumno != '') {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        } else {
            //echo 'aqui'.$alumno;
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }

        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;


        $this->modelBloquesQ1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();
        
        $this->modelBloquesEx1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'EXAMEN'
                ])->orderBy('orden')
                ->one();
        
        $diasExamen = $this->modelBloquesEx1->dias_laborados;

        foreach($this->modelBloquesQ1 as $q1){
            $this->totalDias = $this->totalDias + $q1->dias_laborados;
        }

        $this->totalDias = $this->totalDias + $diasExamen;
        $this->genera_reporte_pdf();
    }

    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
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



        foreach ($this->modelAlumnos as $data) {
            $html = $this->genera_cuerpo($data);
            $mpdf->WriteHTML($html);
            $mpdf->addPage();
        }

        //$mpdf->SetFooter($pie);

        $mpdf->Output('Libreta' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left"><img src="imagenes/instituto/logo/logo2.png" width="80px" width="10%"></td>';
        $html .= '<td class="centrarTexto">';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo.'<br>';
        $html .= '<strong>INFORME DE APRENDIZAJE Y COMPORTAMENTAL</strong><br>';        
        $html .= '<strong>PRIMER QUIMESTRE</strong>';        
        $html .= '</td>';
        $html .= '<td align="right" width="10%">';
        
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
//        $html .= '<hr>';

        return $html;
    }

    private function genera_cuerpo($arregloAlumno) {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid #000;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';
        
        $html .= '<table class="tamano10" width="100%">';
        $html .= '<tr>';
        $html .= '<td><strong>ESTUDIANTE: </strong>'.$arregloAlumno['last_name'] . ' ' . $arregloAlumno['first_name'] . ' ' . $arregloAlumno['middle_name'].'</td>';
        $html .= '<td align="right"><strong>CURSO: </strong>'.$modelParalelo->course->name.'"'.$modelParalelo->name.'"</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->procesa_asignaturas($arregloAlumno['id']);
        $html .= $this->procesa_faltas_atrasos($arregloAlumno['id']);
        $html .= $this->escalas();
        $html .= $this->observaciones();
        $html .= $this->firmas();



        return $html;
    }
    
    private function firmas(){
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = OpInstitute::findOne($institutoId);
        
        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>_______________________________________</strong></td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor="">Tutor(a)</td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '</tr>';

        $html .= '</table>';
        
        return $html;
        
    }
    
    private function observaciones(){
        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td><strong>OBSERVACIONES: </strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="" bgcolor="#eaeaea">'.$this->observacion.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    
    private function escalas(){       
        
        $html = '';
        $html .= '<br>';
        
        $html .= '<table class="tamano8 bordesolido" width="100%" cellspacing="0" cellpadding="4">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto bordesolido"><strong>EQUIVALENCIA DE COMPORTAMIENTO</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESDE</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>HASTA</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESCRIPCIÓN</strong></td>';
        $html .= '</tr>';
        $comportamientos = $this->escalas_comportamiento();
        foreach($comportamientos as $comp){
        $html .= '<tr>';
        
        $html .= '<td class="centrarTexto bordesolido">'.$comp['abreviatura'].'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$comp['rango_minimo'].'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$comp['rango_maximo'].'</td>';
        $html .= '<td class="bordesolido">'.$comp['descripcion'].'</td>';
        
        $html .= '</tr>';    
            
        }
        $html .= '</table>';
        
        
        $html .= '<br>';
        
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="4">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto bordesolido"><strong>APROVECHAMIENTO</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>EQUIVALENCIA</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESDE</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>HASTA</strong></td>';
        $html .= '<td class="centrarTexto bordesolido"><strong>DESCRIPCIÓN</strong></td>';
        $html .= '</tr>';        
        $html .= '<tr>';        
        $html .= '<td class="centrarTexto bordesolido" rowspan="4">GENERAL</td>';
        $aprovechamiento = $this->escalas_aprovechamiento();
        
        $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[0]['abreviatura'].'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[0]['rango_minimo'].'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[0]['rango_maximo'].'</td>';
        $html .= '<td class="bordesolido">'.$aprovechamiento[0]['descripcion'].'</td>';
        $html .= '</tr>';
        
        for($i=1; $i< count($aprovechamiento); $i++){
            $html .= '<tr>';
            $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[$i]['abreviatura'].'</td>';
            $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[$i]['rango_minimo'].'</td>';
            $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[$i]['rango_maximo'].'</td>';
            $html .= '<td class="bordesolido">'.$aprovechamiento[$i]['descripcion'].'</td>';
            $html .= '</tr>';
        }
        
        
        if($this->seccion != 'BACHILLERATO'){
            $html .= '<tr>';        
        $html .= '<td class="centrarTexto bordesolido" rowspan="4">PROYECTOS EDUCATIVOS</td>';
        $aprovechamiento = $this->escalas_proyectos();
        
        $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[0]['abreviatura'].'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[0]['rango_minimo'].'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[0]['rango_maximo'].'</td>';
        $html .= '<td class="bordesolido">'.$aprovechamiento[0]['descripcion'].'</td>';
        $html .= '</tr>';
        
        for($i=1; $i< count($aprovechamiento); $i++){
            $html .= '<tr>';
            $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[$i]['abreviatura'].'</td>';
            $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[$i]['rango_minimo'].'</tdAPROVECHAMIENTO>';
            $html .= '<td class="centrarTexto bordesolido">'.$aprovechamiento[$i]['rango_maximo'].'</td>';
            $html .= '<td class="bordesolido">'.$aprovechamiento[$i]['descripcion'].'</td>';
            $html .= '</tr>';
        }
        }
                
        
        
        
        
        $html .= '</table>';
        
        return $html;
    }
    
    private function escalas_aprovechamiento(){
        $con = Yii::$app->db;
        $query = "select 	 abreviatura 
                                    ,descripcion
                                    ,rango_minimo
                                    ,rango_maximo
                    from 	scholaris_tabla_escalas_homologacion
                    where 	scholaris_periodo = '$this->periodoCodigo'
                                    and corresponde_a = 'APROVECHAMIENTO'
                    order by rango_maximo desc;";
               
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    private function escalas_proyectos(){
        $con = Yii::$app->db;
        $query = "select 	 abreviatura 
                                    ,descripcion
                                    ,rango_minimo
                                    ,rango_maximo
                    from 	scholaris_tabla_escalas_homologacion
                    where 	scholaris_periodo = '$this->periodoCodigo'
                                    and corresponde_a = 'PROYECTOS'
                    order by rango_maximo desc;";
               
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    private function escalas_comportamiento(){
        $con = Yii::$app->db;
        $query = "select 	 abreviatura
                                 ,rango_minimo
                                 ,rango_maximo
                                            ,descripcion 
                            from 	scholaris_tabla_escalas_homologacion
                            where 	scholaris_periodo = '$this->periodoCodigo'
                                            and section_codigo = '$this->seccion'
                                            and corresponde_a = 'COMPORTAMIENTO'
                            order by abreviatura;";
               
        $res = $con->createCommand($query)->queryAll();
        
        
        
        return $res;
    }
    
    private function procesa_faltas_atrasos($alumnoId){
        $html = '';
        
        $sentencias = new \backend\models\SentenciasFaltas();        
        
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto tamano10 bordesolido" rowspan="2"><strong>ASISTENCIA</strong></td>';        
        
        
        
        $sumaAtrasos = 0;
        $sumaJustificadas = 0;
        $sumaInjustificadas = 0;
        foreach($this->modelBloquesQ1 as $q1){
            $novedades = $sentencias->get_novedad($alumnoId, $q1->id);
//            print_r($novedades);
//            die();
            $sumaAtrasos = $sumaAtrasos + $novedades[9];
            $sumaJustificadas = $sumaJustificadas + $novedades[10];
            $sumaInjustificadas = $sumaInjustificadas + $novedades[11];
            
        }
        
        $this->observacion =  $novedades[12];
        
        
        $html .= '<td class="bordesolido centrarTexto tamano10">Atrasos</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Faltas Justificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Faltas Injustificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Presente</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$sumaAtrasos.'</td>';        
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$sumaJustificadas.'</td>';        
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$sumaInjustificadas.'</td>';
        
        
        $presente = $this->totalDias - ($sumaJustificadas + $sumaInjustificadas);
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$presente.'</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }

    private function procesa_asignaturas($alumnoId) {

        $sentenciasNotasAlumnos = new NotasAlumnos($this->paralelo, $this->quimestre, $alumnoId);

        $areas = $this->get_areas($alumnoId);

        $html = '';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto" rowspan="2"><strong>MATERIA</strong></td>';
        if(count($this->modelBloquesQ1)>2){
            $colspan = 3+2;
        }else{
            $colspan = 2+2;
        }
        
        $html .= '<td class="bordesolido centrarTexto" colspan="'.$colspan.'"><strong>PARCIALES</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" colspan="2"><strong>EVALUACIÓN</strong></td>';
        

        $html .= '<td class="bordesolido centrarTexto" bgcolor="" rowspan="2"><strong>PROMEDIO</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" bgcolor="" rowspan="2"><strong>EQUIVALENCIA</strong></td>';

        $html .= '</tr>';
        $html .= '<tr>';
                    foreach ($this->modelBloquesQ1 as $bloq1) {
            $html .= '<td class="bordesolido centrarTexto">' . $bloq1->abreviatura . '</td>';
        }

        $html .= '<td class="bordesolido centrarTexto">PROM</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>80%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto">EXAMEN</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>20%</strong></td>';
        $html .= '</tr>';

        foreach ($areas as $ar) {
            if ($ar['imprime'] == true) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido">' . $ar['area'] . '</td>';
                $notasArea = $this->busca_nota_area($alumnoId, $ar['id']);

                if ($ar['promedia'] == 1) {

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'p1') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                        }
                    }

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'p2') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                        }
                    }

                    if (count($this->modelBloquesQ1) > 2) {
                        foreach ($notasArea as $nA) {
                            if ($nA['bloque'] == 'p2') {
                                $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                            }
                        }
                    }

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'pr1') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                        }
                    }

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'pr180') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                        }
                    }

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'ex1') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                        }
                    }

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'ex120') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                        }
                    }

                    foreach ($notasArea as $nA) {
                        if ($nA['bloque'] == 'q1') {
                            $html .= '<td class="bordesolido centrarTexto">' . $nA['nota'] . '</td>';
                            $equivalencia = $this->homologa_aprovechamiento($nA['nota']);
                            $html .= '<td class="bordesolido tamano10 centrarTexto">' . $equivalencia . '</td>';
                        }
                    }
                } else {
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto">--</td>';
                    $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea">--</td>';
                }


                $html .= '</tr>';
            }

            $materias = $this->get_materias_x_area($alumnoId, $ar['id']);

            foreach ($materias as $mat) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido tamano10">' . $mat['materia'] . '</td>';

                $notasM = $this->get_nota_materia($alumnoId, $mat['materia_id']);

//                    echo '<pre>';
//                    print_r($notasM);
//                    die();

                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('p1', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('p2', $notasM) . '</td>';

                if (count($this->modelBloquesQ1) > 2) {
                    $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('p3', $notasM) . '</td>';
                }

                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('pr1', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('pr180', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('ex1', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('ex120', $notasM) . '</td>';
                $notaQ1 = $this->encuentra_nota_materia('q1', $notasM);
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $notaQ1 . '</td>';
                
                $equivalencia = $this->homologa_aprovechamiento($notaQ1);
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $equivalencia . '</td>';

                $html .= '</tr>';
            }
        }

        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano10"><strong>PROMEDIOS: </strong></td>';
        
        $notaF = $this->consulta_notas_finales($alumnoId);
        
        $html .= $this->devuelve_nota_promedio($notaF, 'p1');
        $html .= $this->devuelve_nota_promedio($notaF, 'p2');
        if(count($this->modelBloquesQ1)>2){
            $html .= $this->devuelve_nota_promedio($notaF, 'p3');
        }
        $html .= $this->devuelve_nota_promedio($notaF, 'pr1');
        $html .= $this->devuelve_nota_promedio($notaF, 'pr180');
        $html .= $this->devuelve_nota_promedio($notaF, 'ex1');
        $html .= $this->devuelve_nota_promedio($notaF, 'ex120');
        $html .= $this->devuelve_nota_promedio($notaF, 'q1');
        
        
        
        $html .= '</tr>';

        $notasCompProy = $this->consulta_comportamientos_y_proyectos($alumnoId);
        if($this->seccion != 'BACHILLERATO'){
            
            $proyectos = new ComportamientoProyectos($alumnoId, $this->paralelo);
//            echo '<pre>';
//            print_r($proyectos->arrayNotasProy[0]['p1']);
//            die();
            
            $html .= '<tr>';
            $html .= '<td class="bordesolido tamano10"><strong>PROYECTOS ESCOLARES: </strong></td>';
            
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$proyectos->arrayNotasProy[0]['p1']['abreviatura'].'</strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$proyectos->arrayNotasProy[0]['p2']['abreviatura'].'</strong></td>';
            if(count($this->modelBloquesQ1)>2){
                $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$proyectos->arrayNotasProy[0]['p3']['abreviatura'].'</strong></td>';       
            }
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$notasCompProy['proyectos_notaq1'].'</strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$notasCompProy['proyectos_notaq1'].'</strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
            $html .= '</tr>';
        }
        
        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano10"><strong>COMPORTAMIENTO: </strong></td>';
        
        $compoP1 = $this->consulta_comportamientos_parciales($alumnoId, 1);
        $compoP2 = $this->consulta_comportamientos_parciales($alumnoId, 2);
        
        if(count($this->modelBloquesQ1) > 2){
            $compoP3 = $this->consulta_comportamientos_parciales($alumnoId, 3);
        }
        
        
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$compoP1.'</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$compoP2.'</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$notasCompProy['comportamiento_notaq1'].'</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>'.$notasCompProy['comportamiento_notaq1'].'</strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto"><strong>-</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }
    
    private function homologa_aprovechamiento($nota){
        $con = Yii::$app->db;
        $query = "select 	abreviatura 
from 	scholaris_tabla_escalas_homologacion
where 	$nota between rango_minimo and rango_maximo
		and scholaris_periodo = '$this->periodoCodigo'
		and corresponde_a = 'APROVECHAMIENTO';";
        $res = $con->createCommand($query)->queryOne();
        return $res['abreviatura'];
    }
    
    private function devuelve_nota_promedio($arrayNotaF, $campo){
        
        $html = '';
        foreach ($arrayNotaF as $nf){
            if($nf['bloque'] == $campo){
                $html .= '<td class="bordesolido centrarTexto"><strong>'.$nf['nota'].'</strong></td>';
                if($campo == 'q1'){
                    $equivalencia = $this->homologa_aprovechamiento($nf['nota']);
                    $html .= '<td class="bordesolido centrarTexto"><strong>'.$equivalencia.'</strong></td>';
                }
            }
        }
        return $html;
    }
    
    
    private function consulta_comportamientos_parciales($alumnoId, $orden) {
        
        $sentencias = new Notas();       
        
        $modelInscription = OpStudentInscription::find()->where([
            'student_id' => $alumnoId,
            'parallel_id' => $this->paralelo
        ])->one();
        
        $con = Yii::$app->db;
        $query = "select 	calificacion 
from 	scholaris_califica_comportamiento c
		inner join scholaris_bloque_actividad b on b.id = c.bloque_id
where	c.inscription_id = $modelInscription->id
		and b.orden = $orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        $nota = $sentencias->homologa_comportamiento($res['calificacion'], $this->seccion);
        
        return $nota;
        
    }
    
    
    private function consulta_comportamientos_y_proyectos($alumnoId) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, comportamiento_notaq1, comportamiento_notaq2, proyectos_notaq1, proyectos_notaq2 
from 	scholaris_proceso_comportamiento_y_proyectos
where	paralelo_id = $this->paralelo
		and alumno_id = $alumnoId
                and usuario = '$this->usuario';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function consulta_notas_finales($alumnoId) {

        $con = Yii::$app->db;
        $query = "select 	bloque, nota 	 
                    from 	scholaris_proceso_promedios
                    where	usuario = '$this->usuario'
                                    and paralelo_id = $this->paralelo
                                    and alumno_id = $alumnoId
                                    --and bloque in ('q1','q2')
                    order by bloque;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function encuentra_nota_materia($campoParcial, $notasM) {
        $respuesta = 0;
        $i = 0;
        foreach ($notasM as $nota) {
            if ($nota['bloque'] == $campoParcial) {
                $respuesta = $nota['nota'];
            }
        }

        return $respuesta;
    }

    private function get_nota_materia($alumnoId, $materiaId) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, clase_id, materia_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_materias
                    where	usuario = '$this->usuario'
                                    and materia_id = $materiaId
                                    and alumno_id = $alumnoId
                            and paralelo_id = $this->paralelo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_materias_x_area($alumnoId, $areaId) {
        $con = \Yii::$app->db;
        $query = "select 	c.id as clase_id 
		,c.idmateria as materia_id
		,m.name as materia
		,mm.promedia 
		,mm.se_imprime 
		,mm.total_porcentaje 
from 	scholaris_clase c
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
		inner join scholaris_materia m on m.id = c.idmateria 
where 	ma.area_id = $areaId
		and g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$this->periodoCodigo';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function busca_nota_area($alumnoId, $areaId) {
        $con = \Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_areas
                    where 	alumno_id = $alumnoId
                                    and paralelo_id = $this->paralelo
                                    and usuario = '$this->usuario'
                                    and area_id = $areaId;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_areas($alumnoId) {
        $con = \Yii::$app->db;
        $query = "select 	a.id 
                                ,a.name as area 
                                ,pa.promedia 
                                ,pa.imprime 
                from 	scholaris_proceso_areas pa
                                inner join scholaris_area a on a.id = pa.area_id
                                inner join scholaris_malla_area ma on ma.area_id = a.id 
                where	alumno_id = $alumnoId
                                and usuario = '$this->usuario'
                                and paralelo_id = $this->paralelo
                                and ma.malla_id = $this->mallaId
                group by a.id,a.name, ma.orden, pa.promedia 
                                ,pa.imprime
                order by ma.orden;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
