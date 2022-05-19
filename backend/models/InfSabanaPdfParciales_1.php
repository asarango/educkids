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
class InfSabanaPdfParciales extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $modelParalelo;
    private $quimestre;
    private $periodoId;
    private $periodoCodigo;
    private $arregloLibretas = array();
    private $modelAlumnos;
    private $arregloMaterias = array();
    private $usuario;
    private $notaMinima;
    private $tituloReporte;
    private $tipoCalificacion;

    public function __construct($paralelo, $alumno, $quimestre) {
        
        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);

        $this->quimestre = $quimestre;
        $this->titulo_reporte(); //coloca el nombre del titulo del reporte
        
        $this->paralelo = $paralelo;
        $this->genera_materias_sabana();
        $this->usuario = Yii::$app->user->identity->usuario;
        $modelNotaMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $this->notaMinima = $modelNotaMinima->valor;
        
         /*         * * para periodo ** */



        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        ///// fin de periodo /////
        
        
        /** tipo de calificacion * */
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        ///// fin de tippo de calificacion /////

        if (!$alumno > 0 || !$alumno != '') {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        } else {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }
        

        //$sentenciasNotasAlumnos = new NotasAlumnos($paralelo, $quimestre, $alumno);

        $this->genera_reporte_pdf();
    }
    
    private function titulo_reporte(){
        
        switch ($this->quimestre){
            case 'p1':
                $this->tituloReporte = 'DEL PARCIAL 1 ';
                break;
            
            case 'p2':
                $this->tituloReporte = 'DEL PARCIAL 2 ';
                break;
            
            case 'p3':
                $this->tituloReporte = 'DEL PARCIAL 3 ';
                break;
            
            case 'ex1':
                $this->tituloReporte = 'DEL EXAMEN 1 ';
                break;
            
            case 'P4':
                $this->tituloReporte = 'DEL PARCIAL 4 ';
                break;
            
            case 'P5':
                $this->tituloReporte = 'DEL PARCIAL 5 ';
                break;
            
            case 'P6':
                $this->tituloReporte = 'DEL PARCIAL 6 ';
                break;
            
            case 'ex2':
                $this->tituloReporte = 'DEL EXAMEN 2 ';
                break;
            
            default:
                $this->tituloReporte = '';
                break;
            
            
        }
        
        
//        if($this->quimestre == 'q1'){
//            $this->tituloReporte = 'DEL PRIMER QUIMESTRE ';
//        }elseif($this->quimestre == 'q2'){
//            $this->tituloReporte = 'DEL SEGUNDO QUIMESTRE ';
//        }else{
//            $this->tituloReporte = '';
//        }
    } 

    private function genera_reporte_pdf() {
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

        $mpdf->WriteHTML($html);
        //$mpdf->SetFooter($pie);

        $mpdf->Output('Sabanaq1' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {

        $html = '';
        $html .= '<table class="tamano10" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
        $html .= '<td align="center" class="tamano12">';
        $html .= '<strong>' . $this->modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>2020-2021<br>';
        $html .= '<strong>INFORME '.$this->tituloReporte.' DE APRENDIZAJE Y COMPORTAMIENTO</strong>';
        $html .= '</td>';
        $html .= '<td width="20%" align="right" class="tamano10">';

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
        $html .= '.tamano12{font-size:12px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '</style>';


        $html .= '<p class="tamano10"><strong>CURSO:</strong>';
        $html .= $this->modelParalelo->course->name . ' - ' . $this->modelParalelo->name . '</p>';

        $html .= '<table style="font-size: 10px;" cellspacing="0" width="100%">';
        $html .= '<tr>';
        $html .= '<td class="conBorde"><strong>#</strong></td>';
        $html .= '<td class="conBorde"><strong>ESTUDIANTE</strong></td>';

        foreach ($this->arregloMaterias as $materia) {

            if ($materia['imprime']) {
                $html .= '<td class="conBorde">' . $materia['abreviatura'] . '</td>';
            }
        }

        $html .= '<td class="conBorde"><strong>PROMEDIO</strong></td>';
        
        
        if($this->modelParalelo->course->section0->code != 'BACHILLERATO'){
            $html .= '<td class="conBorde"><strong>PROY</strong></td>';
        }
                       
        $html .= '<td class="conBorde"><strong>COMP</strong></td>';

        $html .= '</tr>';

        $html .= $this->detalle_alumnos();
        $html .= $this->promedios();

        $html .= '</table>';

        return $html;
    }

    private function promedios() {

        $html = '<tr>';
        $html .= '<td colspan="2" class="conBorde centrarTexto" bgcolor="#eaeaea"><strong>PROMEDIOS DEL PARALELO:</strong></td>';

        foreach ($this->arregloMaterias as $materia) {
            if ($materia['imprime']) {
                if ($materia['tipo_asignatura'] == 'area') {
                    $notaA = $this->calcula_promedio_area($materia['asignatura_id']);
                    $html .= '<td class="conBorde centrarTexto"  bgcolor="#eaeaea"><strong>' . $notaA . '</strong></td>';
                } else {
                    $notaM = $this->calcula_promedio_materia($materia['asignatura_id']);
                    $html .= '<td class="conBorde centrarTexto"  bgcolor="#eaeaea"><strong>' . $notaM . '</strong></td>';
                }
            }
        }

        $notaF = $this->calcula_promedio_final();
        $html .= '<td class="conBorde centrarTexto"  bgcolor="#eaeaea"><strong>' . $notaF . '</strong></td>';

        $html .= '</tr>';

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

            $notasFinales = $this->consulta_notas_finales($alumno['id']);
            foreach ($notasFinales as $nf) {
//                if ($nf['bloque'] == 'q1') {
                if ($nf['bloque'] == $this->quimestre) {

                    if ($nf['nota'] < $this->notaMinima) {
                        $html .= '<td class="conBorde centrarTexto" style="background-color: red; color: black"><strong>' . $nf['nota'] . '</strong></td>';
                    } else {
                        $html .= '<td class="conBorde centrarTexto"><strong>' . $nf['nota'] . '</strong></td>';
                    }
                    
                }
            }


            if($this->quimestre != 'ex1' || $this->quimestre != 'ex2'){
                $comportamientoProyectos = new ComportamientoProyectos($alumno['id'], $this->paralelo);

                if($this->modelParalelo->course->section0->code != 'BACHILLERATO'){
                    if(isset($comportamientoProyectos->arrayNotasProy[0][$this->quimestre])){
                        $html .= '<td class="conBorde" align="center">' . $comportamientoProyectos->arrayNotasProy[0][$this->quimestre]['abreviatura'] . '</td>';
                    }else{
                        $html .= '<td class="conBorde" align="center">-</td>';
                    }

                }
            }
            

            if($this->quimestre == 'ex1'){
                $html .= '<td class="conBorde" align="center">-</td>';
            }else{
                $html .= '<td class="conBorde" align="center">' . $comportamientoProyectos->arrayNotasComp[0][$this->quimestre] . '</td>';
            }

            
            
           
            $html .= '</tr>';
        }

        return $html;
    }

//    private function consulta_comportamientos_y_proyectos($alumnoId) {
//        $con = Yii::$app->db;
//        $query = "select 	usuario, paralelo_id, alumno_id, comportamiento_notaq1, comportamiento_notaq2, proyectos_notaq1, proyectos_notaq2 
//from 	scholaris_proceso_comportamiento_y_proyectos
//where	paralelo_id = $this->paralelo
//		and alumno_id = $alumnoId
//                and usuario = '$this->usuario';";
//        $res = $con->createCommand($query)->queryOne();
//        return $res;
//    }

    private function consulta_notas_finales($alumnoId) {

        $con = Yii::$app->db;
        $query = "select 	bloque, nota 	 
                    from 	scholaris_proceso_promedios
                    where	usuario = '$this->usuario'
                                    and paralelo_id = $this->paralelo
                                    and alumno_id = $alumnoId
                                    and bloque in ('p1','p2','p3','ex1','p4','p5','p6','ex2')
                    order by bloque;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function buscar_nota($materia, $alumnoId) {
        $html = '';
        
        if ($materia['tipo_asignatura'] == 'area') {
            $modelNotas = $this->get_nota_area($alumnoId, $materia['asignatura_id']);

            foreach ($modelNotas as $nota) {
                if ($nota['bloque'] == $this->quimestre) {
                    if ($nota['nota'] < $this->notaMinima) {
                        $html .= '<td class="conBorde" align="center" style="background-color: red; color: black">' . $nota['nota'] . '</td>';
                    }else{
                        $html .= '<td class="conBorde" align="center">' . $nota['nota'] . '</td>';
                    }
                }
            }
//            echo '<pre>';
//            print_r($notas);
//            die();
        } else {
            $modelNota = $this->get_nota_materia($alumnoId, $materia['asignatura_id']);
            
                        
            foreach ($modelNota as $nota) {
                if ($nota['bloque'] == $this->quimestre) {
                    if($nota['nota'] < $this->notaMinima){
                    $html .= '<td class="conBorde" align="center" style="background-color: red; color: black">' . $nota['nota'] . '</td>';
                    }else{
                        $html .= '<td class="conBorde" align="center">' . $nota['nota'] . '</td>';
                    }
                }
            }
        }

        return $html;
    }

    private function get_nota_area($alumnoId, $areaId) {

        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, area_id, porcentaje, promedia, imprime, bloque, nota 
                    from 	scholaris_proceso_areas
                    where	alumno_id = $alumnoId
                                    and area_id = $areaId
                                    and paralelo_id = $this->paralelo
                                    and usuario = '$this->usuario';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function calcula_promedio_area($areaId) {

        $con = Yii::$app->db;
        $query = "select 	trunc((avg(nota)),2) as nota 
                    from 	scholaris_proceso_areas
                    where 	paralelo_id = $this->paralelo
                                    and usuario = '$this->usuario'
                                    and area_id = $areaId
                                    and	bloque = '$this->quimestre';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

    private function calcula_promedio_materia($materiaId) {

        $con = Yii::$app->db;
        $query = "select 	trunc(avg(nota),2) as nota 
                    from 	scholaris_proceso_materias
                    where 	paralelo_id = $this->paralelo
                                    and usuario = '$this->usuario'
                                    and materia_id = $materiaId
                                    and	bloque = '$this->quimestre';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

    private function calcula_promedio_final() {

        $con = Yii::$app->db;
        $query = "select 	trunc(avg(nota ) ,2) as nota 
                    from 	scholaris_proceso_promedios
                    where	paralelo_id = $this->paralelo
                                    and bloque = '$this->quimestre' and usuario='$this->usuario'  ;";
        
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
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
                'asignatura_id' => $area->area->id,
                'tipo' => $area->tipo,
                'promedia' => $area->promedia,
                'imprime' => $area->se_imprime,
                'nombre' => $area->area->name,
                'porcentaje' => $area->total_porcentaje,
                'abreviatura' => strtoupper(substr($area->area->name, 0, 3))
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
