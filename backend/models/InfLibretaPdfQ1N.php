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
class InfLibretaPdfQ1N extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $quimestre;
    private $periodoId;
    private $periodoCodigo;
    private $modelAlumnos;
    private $usuario;
    private $mallaId;
    private $modelBloquesQ1;
    private $seccion;
    private $observacion;
    private $tipoCalificacion;

    public function __construct($paralelo, $alumno, $quimestre) {

        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        
        /********  busca tipo de calificacion *******/
        $modelTipoCalificacion = ScholarisParametrosOpciones::find()->where([
            'codigo' => 'tipocalif'
        ])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->valor;
        ///// fin de busca tipo de calificacion //////////
        
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
        $html .= '<td align="left"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
        $html .= '<td>';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>INFORME DE APRENDIZAJE - QUIMESTRE I</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo;
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

    private function genera_cuerpo($arregloAlumno) {

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid #ccc;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

        $html .= '<p class="centrarTexto arial"><strong>' . $arregloAlumno['last_name'] . ' ' . $arregloAlumno['first_name'] . ' ' . $arregloAlumno['middle_name'] . '</strong></p>';

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
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>_______________________________________</strong></td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>_______________________________________</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>'.$modelInstituto->rector.'</strong></td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>'.$modelInstituto->secretario.'</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>RECTORA</strong></td>';
        $html .= '<td class="centrarTexto" bgcolor=""></td>';
        $html .= '<td class="centrarTexto" bgcolor=""><strong>SECRETARIA</strong></td>';
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
        $html .= '<td class="centrarTexto" bgcolor="#eaeaea"><strong>OBSERVACIONES: </strong></td>';
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
        $html .= '<br>';
        $html .= '<table class="tamano8" width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto"><strong>ESCALAS DE APOVECHAMIENTO</strong></td>';
        $html .= '<td class="centrarTexto"><strong>ESCALAS DE COMPORTAMIENTO</strong></td>';
        $html .= '</tr>';
        
        
        $html .= '<tr>';
        $html .= '<td class="centrarTexto bordesolido">'.$this->escalas_aprovechamiento().'</td>';
        $html .= '<td class="centrarTexto bordesolido">'.$this->escalas_comportamiento().'</td>';
        $html .= '</tr>';
        
        
        
        $html .= '</table>';
        
        return $html;
    }
    
    private function escalas_aprovechamiento(){
        $con = Yii::$app->db;
        $query = "select 	 abreviatura 
                                    ,descripcion 
                    from 	scholaris_tabla_escalas_homologacion
                    where 	scholaris_periodo = '$this->periodoCodigo'
                                    and corresponde_a = 'APROVECHAMIENTO'
                    order by rango_maximo desc;";
               
        $res = $con->createCommand($query)->queryAll();
        
        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td><strong><u>CALIF</u></strong></td>';
        $html .= '<td><u><strong>DETALLE</u></strong></td>';
        $html .= '</tr>';
        
        foreach ($res as $ap){
            $html .= '<tr>';
            $html .= '<td>'.$ap['abreviatura'].'</td>';
            $html .= '<td align="left">'.$ap['descripcion'].'</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }
    
    private function escalas_comportamiento(){
        $con = Yii::$app->db;
        $query = "select 	 abreviatura 
                                            ,descripcion 
                            from 	scholaris_tabla_escalas_homologacion
                            where 	scholaris_periodo = '$this->periodoCodigo'
                                            and section_codigo = '$this->seccion'
                                            and corresponde_a = 'COMPORTAMIENTO'
                            order by abreviatura;";
               
        $res = $con->createCommand($query)->queryAll();
        
        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td><u><strong>CALIF</u></strong></td>';
        $html .= '<td><u><strong>DETALLE</u></strong></td>';
        $html .= '</tr>';
        
        foreach ($res as $ap){
            $html .= '<tr>';
            $html .= '<td>'.$ap['abreviatura'].'</td>';
            $html .= '<td align="left">'.$ap['descripcion'].'</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }
    
    private function procesa_faltas_atrasos($alumnoId){
        $html = '';
        
        $sentencias = new \backend\models\SentenciasFaltas();        
        
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto tamano10 bordesolido" colspan="6"><strong>DETALLE DE FALTAS Y ATRASOS</strong></td>';        
        $html .= '</tr>';
        
        
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
        
        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Atrasos</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$sumaAtrasos.'</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Faltas Justificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$sumaJustificadas.'</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">Faltas Injustificadas</td>';
        $html .= '<td class="bordesolido centrarTexto tamano10">'.$sumaInjustificadas.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }

    private function procesa_asignaturas($alumnoId) {

        $sentenciasProcesa = new ProcesaNotas();
        
        $areas = $sentenciasProcesa->get_areas($alumnoId, $this->tipoCalificacion, $this->paralelo, $this->usuario, $this->mallaId);

        $html = '';
        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto"><strong>ASIGNATURAS</strong></td>';
        foreach ($this->modelBloquesQ1 as $bloq1) {
            $html .= '<td class="bordesolido centrarTexto">' . $bloq1->abreviatura . '</td>';
        }

        $html .= '<td class="bordesolido centrarTexto">PR</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>80%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto">EX</td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>20%</strong></td>';
        $html .= '<td class="bordesolido centrarTexto" bgcolor="#eaeaea"><strong>Q1</strong></td>';

        $html .= '</tr>';

        foreach ($areas as $ar) {
            if ($ar['imprime'] == true) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido">' . $ar['area'] . '</td>';
                //$notasArea = $this->busca_nota_area($alumnoId, $ar['id']);
                
                $notasArea = $sentenciasProcesa->busca_nota_area($alumnoId, $ar['id'], $this->tipoCalificacion);
                

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

            $materias = $sentenciasProcesa->get_materias_x_area($alumnoId, $ar['id'], $this->periodoCodigo);
            

            foreach ($materias as $mat) {
                $html .= '<tr>';
                $html .= '<td class="bordesolido tamano10">' . $mat['materia'] . '</td>';

                //$notasM = $this->get_nota_materia($alumnoId, $mat['materia_id']);
                $notasM = $sentenciasProcesa->get_nota_materia($alumnoId, $mat['materia_id'], $this->tipoCalificacion, $mat['grupo_id']);

//                    echo '<pre>';
//                    print_r($notasM);
//                    die();

                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('p1', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('p2', $notasM) . '</td>';

                if (count($this->modelBloquesQ1) > 3) {
                    $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('p3', $notasM) . '</td>';
                }

                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('pr1', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('pr180', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('ex1', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto">' . $this->encuentra_nota_materia('ex120', $notasM) . '</td>';
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $this->encuentra_nota_materia('q1', $notasM) . '</td>';



                $html .= '</tr>';
            }
        }

        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea"><strong>PROMEDIOS: </strong></td>';
        
        $notaF = $this->consulta_notas_finales($alumnoId);
        
//        print_r($notaF);
//        die();
        
//        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea">-</td>';
//        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea">-</td>';
//        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea">-</td>';
//        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea">-</td>';
//        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea">-</td>';
//        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea">-</td>';
        
        $html .= $this->devuelve_nota_promedio($notaF, 'p1');
        $html .= $this->devuelve_nota_promedio($notaF, 'p2');
        $html .= $this->devuelve_nota_promedio($notaF, 'pr1');
        $html .= $this->devuelve_nota_promedio($notaF, 'pr180');
        $html .= $this->devuelve_nota_promedio($notaF, 'ex1');
        $html .= $this->devuelve_nota_promedio($notaF, 'ex120');
        $html .= $this->devuelve_nota_promedio($notaF, 'q1');
        
        
        
        $html .= '</tr>';

        $notasCompProy = $this->consulta_comportamientos_y_proyectos($alumnoId);
        if($this->seccion != 'BACHILLERATO'){
            $html .= '<tr>';
            $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea"><strong>PROYECTOS ESCOLARES: </strong></td>';
            $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea"><strong>'.$notasCompProy['proyectos_notaq1'].'</strong></td>';
            $html .= '</tr>';
        }
        
        $html .= '<tr>';
        $html .= '<td class="bordesolido tamano10" bgcolor="#eaeaea"><strong>COMPORTAMIENTO: </strong></td>';
        $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea"><strong>'.$notasCompProy['comportamiento_notaq1'].'</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }
    
    private function devuelve_nota_promedio($arrayNotaF, $campo){
        $html = '';
        foreach ($arrayNotaF as $nf){
            if($nf['bloque'] == $campo){
                $html .= '<td class="bordesolido tamano12 centrarTexto" bgcolor="#eaeaea"><strong>'.$nf['nota'].'</strong></td>';
            }
        }
        return $html;
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

    

    
    

    

    

}
