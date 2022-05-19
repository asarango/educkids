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
use yii\helpers\Html;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class InfSabanaPdfQ1P extends \yii\db\ActiveRecord {

    private $alumno = '';
    private $paralelo;
    private $modelParalelo;
    private $seccion;
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

        if (!isset(Yii::$app->user->identity->usuario)) {
            echo 'Su sesión expiró!!!';
            echo Html::a("Iniciar Sesión", ['site/index']);
            die();
        }

        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $this->modelParalelo->course->section0->code;

        $this->quimestre = $quimestre;
        $this->titulo_reporte(); //coloca el nombre del titulo del reporte

        $this->paralelo = $paralelo;
        $this->genera_materias_sabana();

        /*         * * para periodo ** */



        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        ///// fin de periodo /////

        $this->usuario = Yii::$app->user->identity->usuario;
        $modelNotaMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $this->notaMinima = $modelNotaMinima->valor;

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

    private function titulo_reporte() {
        if ($this->quimestre == 'q1') {
            $this->tituloReporte = 'DEL PRIMER QUIMESTRE ';
        } elseif ($this->quimestre == 'q2') {
            $this->tituloReporte = 'DEL SEGUNDO QUIMESTRE ';
        } else {
            $this->tituloReporte = 'FINAL ';
        }
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
        $html .= '<strong>INFORME ' . $this->tituloReporte . ' DE APRENDIZAJE Y COMPORTAMIENTO</strong>';
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
                //$html .= '<td>' . $materia['tipo_asignatura'] . ' - ' . $materia['porcentaje'] . ' - ' . $materia['nombre'] . '</td>';
                $html .= '<td class="conBorde">' . $materia['abreviatura'] . '</td>';
            }
        }

        $html .= '<td class="conBorde"><strong>PROMEDIO</strong></td>';


        if ($this->modelParalelo->course->section0->code != 'BACHILLERATO') {
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

        $suma = 0;
        $cont = 0;
        
        foreach ($this->arregloMaterias as $materia) {
            if ($materia['imprime']) {
                
                $sumaArea = 0;
                                
                if ($materia['tipo_asignatura'] == 'area') {

                    if ($this->tipoCalificacion == 0) {
                        $sentenciasNotasAlumnos = new AlumnoNotasNormales();
                    } elseif ($this->tipoCalificacion == 2) {
                        $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
                    } elseif ($this->tipoCalificacion == 3) {
                        $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
                    } else {
                        echo 'No tiene creado un tipo de calificación para esta institutción!!!';
                        die();
                    }
                    
                    foreach ($this->modelAlumnos as $alumno){
                        $notaAreaAl = $sentenciasNotasAlumnos->get_nota_area($materia['asignatura_id'], $alumno['id'], $this->paralelo, $this->usuario);
                        
                        $sumaArea = $sumaArea + $notaAreaAl[$this->quimestre];
                        
                    }

                    $notaA = number_format($sumaArea / count($this->modelAlumnos),2);
                    //$notaA = $this->calcula_promedio_area($materia['asignatura_id']);

                    $html .= '<td class="conBorde centrarTexto"  bgcolor="#eaeaea"><strong>' . $notaA . '</strong></td>';
                } else {
                    $notaM = $this->calcula_promedio_materia($materia['asignatura_id']);
                    $notaM = number_format($notaM, 2);

                    $suma = $suma + $notaM;
                    $cont++;

                    $html .= '<td class="conBorde centrarTexto"  bgcolor="#eaeaea"><strong>' . number_format($notaM, 2) . '</strong></td>';
                }
            }
        }

        $notaF = number_format($suma / $cont, 2);
        //$notaF = $this->calcula_promedio_final();
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

            ///para tomar los valores de promedio de estudiantes //////
            $sentencias = new SentenciasRepLibreta2();

            if ($this->tipoCalificacion == 0) {
                $sentenciasNotasAlumnos = new AlumnoNotasNormales();
            } elseif ($this->tipoCalificacion == 2) {
                $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
            } elseif ($this->tipoCalificacion == 3) {
                $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
            } else {
                echo 'No tiene creado un tipo de calificación para esta institutción!!!';
                die();
            }
            $promedios = $sentenciasNotasAlumnos->get_promedio_alumno($alumno['id'], $this->paralelo, $this->usuario);
//            echo '<pre>';
//            echo $this->quimestre;
//            print_r($promedios);
//            die();
            isset($promedios[$this->quimestre]) ? $notaFinal = $promedios[$this->quimestre] : $notaFinal = 0;

            if ($notaFinal < $this->notaMinima) {
                $html .= '<td class="conBorde centrarTexto" style="background-color: red; color: black"><strong>' . $notaFinal . '</strong></td>';
            } else {
                $html .= '<td class="conBorde centrarTexto"><strong>' . $notaFinal . '</strong></td>';
            }

            //// fin de toma de finales de estudiantes ////////


            if ($this->seccion != 'BACHILLERATO') {
                /*                 * * INICIO DE PROYECTOS ** */

                $proyectos = new MecProcesaMaterias();
                $proyQ1 = $proyectos->get_proyectos($alumno['id'], $this->paralelo, $this->quimestre);
                $html .= '<td class="bordesolido tamano10 centrarTexto" bgcolor="#eaeaea">' . $proyQ1['q2']['abreviatura'] . '</td>';

                /*                 * * FIN DE PROYECTOS *** */
            }

            /*             * * INICIA COMPORTAMIENTO ** */

            $notas = new ComportamientoProyectos($alumno['id'], $this->paralelo);
            $notaC = $notas->arrayNotasComp;

            $html .= '<td class="tamano10 bordesolido centrarTexto" bgcolor="#eaeaea" colspan="">' . $notaC[0]['q2'] . '</td>';

            /*             * * fin de comportamiento **** */



            $html .= '</tr>';
        }

        return $html;
    }

    private function busca_grupo_id($alumnoId, $materiaId) {
        $con = \Yii::$app->db;
        $query = "select 	g.id 
                    from	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id 
                    where	g.estudiante_id = $alumnoId
                                    and c.idmateria = $materiaId
                                    and c.periodo_scholaris = '$this->periodoCodigo';";

        $res = $con->createCommand($query)->queryOne();
        isset($res['id']) ? $grupo = $res['id'] : $grupo = 0;
        return $grupo;
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
                                    and bloque in ('q1','q2', 'final_ano_normal')
                    order by bloque;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function buscar_nota($materia, $alumnoId) {

        $sentencias = new SentenciasRepLibreta2();

        if ($this->tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new AlumnoNotasNormales();
        } elseif ($this->tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
        } elseif ($this->tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }


        $html = '';

        if ($materia['tipo_asignatura'] == 'area') {
            //$modelNotas = $this->get_nota_area($alumnoId, $materia['asignatura_id']);

            $modelNotas = $sentenciasNotasAlumnos->get_nota_area($materia['asignatura_id'], $alumnoId, $this->paralelo, $this->usuario);

            if ($modelNotas[$this->quimestre] < $this->notaMinima) {
                $html .= '<td class="conBorde" align="center" style="background-color: red; color: black">' . $modelNotas[$this->quimestre] . '</td>';
            } else {
                $html .= '<td class="conBorde" align="center">' . $modelNotas[$this->quimestre] . '</td>';
            }

//            foreach ($modelNotas as $nota) {
//                if ($nota['bloque'] == $this->quimestre) {
//                    if ($nota['nota'] < $this->notaMinima) {
//                        $html .= '<td class="conBorde" align="center" style="background-color: red; color: black">' . $nota['nota'] . '</td>';
//                    }else{
//                        $html .= '<td class="conBorde" align="center">' . $nota['nota'] . '</td>';
//                    }
//                }
//            }
//            echo '<pre>';
//            print_r($notas);
//            die();
        } else {

            $grupoId = $this->busca_grupo_id($alumnoId, $materia['asignatura_id']);

            if ($grupoId == 0) {
                $notaM = 0;
            } else {
                //$modelNota = $this->get_nota_materia($alumnoId, $materia['asignatura_id']);
                $modelNotas = $sentenciasNotasAlumnos->get_nota_materia($grupoId);
//                echo '<pre>';
//                print_r($modelNotas);
//                die();
                $notaM = $modelNotas[$this->quimestre];
            }


            if ($notaM < $this->notaMinima) {
                $html .= '<td class="conBorde" align="center" style="background-color: red; color: black">' . $notaM . '</td>';
            } else {
                $html .= '<td class="conBorde" align="center">' . $notaM . '</td>';
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

        if ($this->tipoCalificacion == 0) {
            $query = "select 	avg($this->quimestre) as nota
                        from	scholaris_clase_libreta l
                                        inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id 
                                        inner join scholaris_clase c on c.id = g.clase_id 
                        where 	c.paralelo_id = $this->paralelo
                                        and c.idmateria = $materiaId;";
            //        $query = "select 	trunc(avg(nota),2) as nota 
            //                    from 	scholaris_proceso_materias
            //                    where 	paralelo_id = $this->paralelo
            //                                    and usuario = '$this->usuario'
            //                                    and materia_id = $materiaId
            //                                    and	bloque = '$this->quimestre';";
            //        echo $query;
            //        die();
            $res = $con->createCommand($query)->queryOne();
            return $res['nota'];
        } elseif ($this->tipoCalificacion == 2) {

            $sentencias = new Notas();
            $digito = 2;

            $p1 = $this->consulta_promedios_disciplinares($materiaId, 1);
            $p2 = $this->consulta_promedios_disciplinares($materiaId, 2);
            $p3 = 0;
            $p4 = $this->consulta_promedios_disciplinares($materiaId, 5);
            $p5 = $this->consulta_promedios_disciplinares($materiaId, 6);
            $p6 = 0;

            $examenes = $this->consulta_examenes_disciplinares($materiaId);

            isset($examenes['ex1']) ? $ex1 = $examenes['ex1'] : $ex1 = 0;
            isset($examenes['ex2']) ? $ex2 = $examenes['ex2'] : $ex2 = 0;

            $pr1 = $sentencias->truncarNota(($p1 + $p2) / 2, 2);
            $pr180 = $sentencias->truncarNota(($pr1 * 80) / 100, $digito);
            $ex120 = $sentencias->truncarNota(($ex1 * 20) / 100, $digito);
            $q1 = $pr180 + $ex120;

            $pr2 = $sentencias->truncarNota(($p4 + $p5) / 2, 2);
            $pr280 = $sentencias->truncarNota(($pr2 * 80) / 100, $digito);
            $ex220 = $sentencias->truncarNota(($ex2 * 20) / 100, $digito);
            $q2 = $pr280 + $ex220;

            $final_ano_normal = $sentencias->truncarNota(($q1 + $q2) / 2, $digito);


            switch ($this->quimestre) {
                case 'p1':
                    return $p1;
                    break;

                case 'p2':
                    return $p2;
                    break;

                case 'p4':
                    return $p4;
                    break;


                case 'p5':
                    return $p5;
                    break;


                case 'q1':
                    return $q1;
                    break;

                case 'q2':
                    return $q2;
                    break;

                case 'final_ano_normal':
                    return $final_ano_normal;
                    break;
            }

            return $this->quimestre;
        }
    }

    private function consulta_examenes_disciplinares($materiaId) {
        $con = \Yii::$app->db;
        $query = "select 	avg(ex1) as ex1 
                                    ,avg(ex2) as ex2
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id 
                                    inner join scholaris_clase cla on cla.id = g.clase_id 
                    where 	cla.paralelo_id = $this->paralelo
                                    and cla.idmateria = $materiaId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function consulta_promedios_disciplinares($materiaId, $orden) {
        $con = \Yii::$app->db;
        $query = "select avg(suma) as nota
                        from(  
                        select 	l.grupo_id 
                                        ,sum(l.nota) as suma 
                        from 	scholaris_calificaciones_parcial l
                                        inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                        inner join scholaris_clase c on c.id = g.clase_id 
                                        inner join scholaris_bloque_actividad blo on blo.id = l.bloque_id 
                        where c.paralelo_id = $this->paralelo
                                        and blo.orden = $orden
                                        and c.idmateria = $materiaId
                        group by l.grupo_id) as nota;";

        $res = $con->createCommand($query)->queryOne();

        isset($res['nota']) ? $nota = $res['nota'] : $nota = 0;

        return $nota;
    }

    private function calcula_promedio_final() {

        $con = Yii::$app->db;
        $query = "select 	trunc(avg(nota ) ,2) as nota 
                    from 	scholaris_proceso_promedios
                    where	paralelo_id = $this->paralelo
                                    and bloque = '$this->quimestre';";
//        echo $query;
//        die();
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
