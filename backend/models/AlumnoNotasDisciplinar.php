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
class AlumnoNotasDisciplinar extends \yii\db\ActiveRecord {

    public function get_nota_area($areaId, $alumnoId, $paraleloId, $usuario) {

        $sentencias = new Notas();
        $digito = 2;
        $pr1 = 0;
        $pr2 = 0;
        $pr180 = 0;
        $pr280 = 0;
        $p3 = 0;
        $p4 = 0;
        $p5 = 0;
        $p6 = 0;

        if (isset(\Yii::$app->user->identity->periodo_id)) {
            $periodoId = \Yii::$app->user->identity->periodo_id;
        } else {
            $modelPeriodo = ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            $periodoId = $modelPeriodo->id;
        }

        $modelPer = ScholarisPeriodo::findOne($periodoId);
        $periodoCo = $modelPer->codigo;

        $examenes = $this->consulta_notas_examenes_area($alumnoId, $periodoId, $areaId);

        isset($examenes['ex1']) ? $ex1 = number_format($examenes['ex1'], 2) : $ex1 = 0;
        isset($examenes['ex120']) ? $ex120 = number_format($examenes['ex120'], 2) : $ex120 = 0;
        isset($examenes['ex2']) ? $ex2 = number_format($examenes['ex2'], 2) : $ex2 = 0;
        isset($examenes['ex220']) ? $ex220 = number_format($examenes['ex220'], 2) : $ex220 = 0;


        $notas = $this->consulta_notas_por_area($alumnoId, $periodoId, $areaId);

        isset($notas[0]['orden']) == 1 ? $p1 = number_format($notas[0]['nota'], 2) : $p1 = 0;
        isset($notas[1]['orden']) == 2 ? $p2 = number_format($notas[1]['nota'], 2) : $p2 = 0;

        $p3 = 0;

        isset($notas[2]['orden']) == 5 ? $p4 = number_format($notas[2]['nota'], 2) : $p4 = 0;
        isset($notas[3]['orden']) == 6 ? $p5 = number_format($notas[3]['nota'], 2) : $p5 = 0;

        $p6 = 0;



        $pr1 = $sentencias->truncarNota(($p1 + $p2) / 2, $digito);
        $pr180 = $sentencias->truncarNota(($pr1 * 80) / 100, $digito);

        $p4 = number_format($notas[2]['nota'], 2);
        isset($notas[3]['orden']) == 6 ? $p5 = number_format($notas[3]['nota'], 2) : $p5 = 0;
        $p6 = 0;

        $pr2 = $sentencias->truncarNota(($p4 + $p5) / 2, $digito);
        $pr280 = $sentencias->truncarNota(($pr2 * 80) / 100, $digito);



        $q1 = $pr180 + $ex120;
        $q2 = $pr280 + $ex220;

        $final_ano_normal = $sentencias->truncarNota(($q1 + $q2) / 2, $digito);


        /////para tomar notas finales
        $modelParalelo = OpCourseParalelo::findOne($paraleloId);
        $modelMalla = ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $modelMallaArea = ScholarisMallaArea::find()->where([
                    'area_id' => $areaId,
                    'malla_id' => $modelMalla->malla_id
                ])->one();


        $notaFinal = $this->busca_nota_final_area($modelMallaArea->id, $alumnoId, $periodoCo);
        
        $nota_final = $sentencias->truncarNota($notaFinal, 2);
        
        ////fin de notas finales
//        isset($notas[1]['orden']) == 2 ? $p2 = number_format($notas[1]['nota'],2) : $p2 = 0;

        $arrayNotas = array(
            'p1' => $p1,
            'p2' => $p2,
            'p3' => $p3,
            'pr1' => $pr1,
            'pr180' => $pr180,
            'ex1' => $ex1,
            'ex120' => $ex120,
            'q1' => $q1,
            'p4' => $p4,
            'p5' => $p5,
            'p6' => $p6,
            'pr2' => $pr2,
            'pr280' => $pr280,
            'ex2' => $ex2,
            'ex220' => $ex220,
            'q2' => $q2,
            'final_ano_normal' => $final_ano_normal,
            'mejora_q1' => 0,
            'mejora_q2' => 0,
            'final_con_mejora' => 0,
            'final_total' => $nota_final
        );

        return $arrayNotas;
    }

    private function busca_nota_final_area($areaId, $alumnoId, $periodoCodigo) {

        $sentencias = new SentenciasRepLibreta2();
        $materias = $sentencias->get_materias_alumno($areaId, $alumnoId);


        $suma = 0;
        $cont = 0;
        foreach ($materias as $mat) {

            $notasM = $this->get_nota_materia($mat['grupo_id']);

            $suma = $suma + $notasM['final_total'];
            $cont++;
            
        }

        $promedio = $suma / $cont;
        
        return $promedio;
    }

    private function consulta_notas_examenes_area($alumnoId, $periodoId, $areaId) {
        $con = \Yii::$app->db;
        $query = "select 	sum(l.ex1 * mm.total_porcentaje / ma.total_porcentaje) as ex1
                                    ,sum(l.ex120 * mm.total_porcentaje / ma.total_porcentaje) as ex120
                                    ,sum(l.ex2 * mm.total_porcentaje / ma.total_porcentaje) as ex2
                                    ,sum(l.ex220 * mm.total_porcentaje / ma.total_porcentaje) as ex220 
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                                    inner join scholaris_materia m on m.id = c.idmateria 
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                                    inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                    where	g.estudiante_id  = $alumnoId
                                    and p.id = $periodoId
                                    and m.area_id = $areaId;";

        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function consulta_notas_por_area($alumnoId, $periodoId, $areaId) {
        $con = \Yii::$app->db;
        $query = "select 	blo.orden 
                                    ,mm.total_porcentaje/100 * sum(c.nota) as nota
                    from 	scholaris_calificaciones_parcial c
                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                                    inner join op_student_inscription i on i.student_id = g.estudiante_id 
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 		
                                    inner join scholaris_clase cla on cla.id = g.clase_id 
                                    inner join scholaris_materia m on m.id = cla.idmateria 
                                    inner join scholaris_bloque_actividad blo on blo.id = c.bloque_id 
                                    inner join scholaris_malla_materia mm on mm.id = cla.malla_materia 
                    where 	g.estudiante_id = $alumnoId
                                    and sop.scholaris_id = $periodoId
                                    and m.area_id = $areaId
                                    and blo.tipo_bloque = 'PARCIAL'
                    group by blo.orden, blo.name, mm.total_porcentaje;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /*     * *
     * toma las notas de la asignatura del alumno pero por grupoId
     */

    public function get_nota_materia($grupoId) {

        if ($grupoId != 0) {
            $modelNotaMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
            $notaMinima = $modelNotaMinima->valor;

            $sentencias = new Notas();
            $digito = 2;

            $arreglo = array();

            $grupo = ScholarisGrupoAlumnoClase::findOne($grupoId);
            $uso = $grupo->clase->tipo_usu_bloque;
            $examenes = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupoId])->one();


            $p1 = $this->consulta_nota_por_orden($grupoId, 1, $uso);
            $p2 = $this->consulta_nota_por_orden($grupoId, 2, $uso);
            $p3 = $this->consulta_nota_por_orden($grupoId, 3, $uso);
            $p3 == 0 ? $cont = 2 : $cont = 3;
            $pr1 = $sentencias->truncarNota(($p1 + $p2 + $p3) / $cont, $digito);
            $pr180 = $sentencias->truncarNota(($pr1 * 80) / 100, $digito);
            $ex1 = $examenes->ex1;
            $ex120 = $sentencias->truncarNota(($ex1 * 20) / 100, $digito);
            $q1 = $pr180 + $ex120;

            $p4 = $this->consulta_nota_por_orden($grupoId, 5, $uso);
            $p5 = $this->consulta_nota_por_orden($grupoId, 6, $uso);
            $p6 = $this->consulta_nota_por_orden($grupoId, 7, $uso);
            $p6 == 0 ? $cont = 2 : $cont = 3;
            $pr2 = $sentencias->truncarNota(($p4 + $p5 + $p6) / $cont, $digito);
            $pr280 = $sentencias->truncarNota(($pr2 * 80) / 100, $digito);
            $ex2 = $examenes->ex2;
            $ex220 = $sentencias->truncarNota(($ex2 * 20) / 100, $digito);
            $q2 = $pr280 + $ex220;

            $finalAnoNormal = $sentencias->truncarNota(($q1 + $q2) / 2, $digito);

            $extras = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupoId])->one();

            if (isset($extras->mejora_q1) > $q1) {
                $mejoraQ1 = $extras->mejora_q1;
            } else {
                $mejoraQ1 = $q1;
            }

            if (isset($extras->mejora_q2) > $q2) {
                $mejoraQ2 = $extras->mejora_q2;
            } else {
                $mejoraQ2 = $q2;
            }

//isset($extras->mejora_q2) ? $mejoraQ2 = $extras->mejora_q2 : $mejoraQ2 = $q2;
            $finalConMejora = $this->calcula_mejora($q1, $q2, $mejoraQ1, $mejoraQ2, $finalAnoNormal);

            isset($extras->supletorio) ? $supletorio = $extras->supletorio : $supletorio = 0;
            isset($extras->remedial) ? $remedial = $extras->remedial : $remedial = 0;
            isset($extras->gracia) ? $gracia = $extras->gracia : $gracia = 0;

            $final = $this->calcula_final_total($supletorio, $remedial, $gracia, $finalConMejora, $notaMinima);


            //$p6 == 0 ? $cont = 2 : $cont = 3;
            array_push($arreglo, array(
                'p1' => number_format($p1, 2),
                'p2' => number_format($p2, 2),
                'p3' => number_format($p3, 2),
                'pr1' => number_format($pr1, 2),
                'pr180' => number_format($pr180, 2),
                'ex1' => number_format($ex1, 2),
                'ex120' => number_format($ex120, 2),
                'q1' => number_format($q1, 2),
                'p4' => number_format($p4, 2),
                'p5' => number_format($p5, 2),
                'p6' => number_format($p6, 2),
                'pr2' => number_format($pr2, 2),
                'pr280' => number_format($pr280, 2),
                'ex2' => number_format($ex2, 2),
                'ex220' => number_format($ex220, 2),
                'q2' => number_format($q2, 2),
                'final_ano_normal' => number_format($finalAnoNormal, 2),
                'mejora_q1' => number_format($mejoraQ1, 2),
                'mejora_q2' => number_format($mejoraQ2, 2),
                'final_con_mejora' => number_format($finalConMejora, 2),
                'supletorio' => number_format($supletorio, 2),
                'remedial' => number_format($remedial, 2),
                'gracia' => number_format($gracia, 2),
                'final_total' => number_format($final, 2)
            ));

            return $arreglo[0];
        } else {
            return array(
                'p1' => 0,
                'p2' => 0,
                'p3' => 0,
                'pr1' => 0,
                'pr180' => 0,
                'ex1' => 0,
                'ex120' => 0,
                'q1' => 0,
                'p4' => 0,
                'p5' => 0,
                'p6' => 0,
                'pr2' => 0,
                'pr280' => 0,
                'ex2' => 0,
                'ex220' => 0,
                'q2' => 0,
                'final_ano_normal' => 0,
                'mejora_q1' => 0,
                'mejora_q2' => 0,
                'final_con_mejora' => 0,
                'supletorio' => 0,
                'remedial' => 0,
                'gracia' => 0,
                'final_total' => 0
            );
        }
    }

    private function consulta_nota_por_orden($grupoId, $orden, $uso) {

        $con = Yii::$app->db;
        $query = "select 	sum(nota) as nota 
                    from 	scholaris_calificaciones_parcial c
                                    inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
                    where	grupo_id = $grupoId
                                    and b.orden = $orden and b.tipo_uso = '$uso';";

        $res = $con->createCommand($query)->queryOne();
        if (isset($res['nota'])) {
            return $res['nota'];
        } else {
            return 0;
        }
    }

    private function calcula_mejora($q1, $q2, $mejora1, $mejora2, $finalAnoNormal) {
        $sentencias = new Notas();
        $digito = 2;


        if ($mejora1 <= $q1 && $mejora2 <= $q2) {
            $finalConMejora = $finalAnoNormal;
        } elseif (($mejora1 > $q1) && ($mejora2 <= $q2)) {
            $finalConMejora = $sentencias->truncarNota(($mejora1 + $q2) / 2, $digito);
        } elseif (($mejora1 <= $q1) && ($mejora2 > $q2)) {
            $finalConMejora = $sentencias->truncarNota(($mejora2 + $q1) / 2, $digito);
        } else {
            $finalConMejora = $sentencias->truncarNota(($mejora1 + $mejora2) / 2, $digito);
        }

        return $finalConMejora;
    }

    private function calcula_final_total($supletorio, $remedial, $gracia, $finalConMejora, $notaMinima) {
        if ($supletorio >= $notaMinima || $remedial >= $notaMinima || $gracia >= $notaMinima) {
            $nota = $notaMinima;
        } else {
            $nota = $finalConMejora;
        }

        return $nota;
    }

    /*     * *
     * toma el promedio del alumno
     */

    public function get_promedio_alumno($alumnoId, $paraleloId, $usuarioId) {
        $modelParalelo = OpCourseParalelo::findOne($paraleloId);
        $cursoId = $modelParalelo->course->id;

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $modelMallaCurso = ScholarisMallaCurso::find()->where([
                    'curso_id' => $cursoId
                ])->one();

        $modelMallaArea = ScholarisMallaArea::find()->where([
                    'malla_id' => $modelMallaCurso->malla_id,
                    'tipo' => 'NORMAL'
                ])->orderBy('orden')
                ->all();

        $arregloMaterias = array();

        foreach ($modelMallaArea as $area) {


            array_push($arregloMaterias, array(
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
                array_push($arregloMaterias, array(
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


        $sumaP1 = 0;
        $sumaP2 = 0;
        $sumaP3 = 0;
        $sumaPr1 = 0;
        $sumaPr180 = 0;
        $sumaEx1 = 0;
        $sumaEx120 = 0;
        $sumaQ1 = 0;

        $sumaP4 = 0;
        $sumaP5 = 0;
        $sumaP6 = 0;
        $sumaPr2 = 0;
        $sumaPr280 = 0;
        $sumaEx2 = 0;
        $sumaEx220 = 0;
        $sumaQ2 = 0;

        $sumaFinalN = 0;
        $sumaMejoQ1 = 0;
        $sumaMejoQ2 = 0;
        $sumaFinMej = 0;
        $sumaSuplet = 0;
        $sumaRemedi = 0;
        $sumaGracia = 0;
        $sumaFinalT = 0;



        $cont = 0;

        foreach ($arregloMaterias as $mat) {

            if ($mat['promedia'] == true) {
                if ($mat['tipo_asignatura'] == 'area') {
                    $notaArea = $this->get_nota_area($mat['asignatura_id'], $alumnoId, $paraleloId, $usuarioId);
//                    print_r($notaArea);
//                    die();
                    $sumaP1 = $sumaP1 + $notaArea['p1'];
                    $sumaP2 = $sumaP2 + $notaArea['p2'];
                    $sumaPr1 = $sumaPr1 + $notaArea['pr1'];
                    $sumaPr180 = $sumaPr180 + $notaArea['pr180'];
                    $sumaEx1 = $sumaEx1 + $notaArea['ex1'];
                    $sumaEx120 = $sumaEx120 + $notaArea['ex120'];
                    $sumaQ1 = $sumaQ1 + $notaArea['q1'];
                    $sumaP4 = $sumaP4 + $notaArea['p4'];
                    $sumaP5 = $sumaP5 + $notaArea['p5'];
                    $sumaPr2 = $sumaPr2 + $notaArea['pr2'];
                    $sumaPr280 = $sumaPr280 + $notaArea['pr280'];
                    $sumaEx2 = $sumaEx2 + $notaArea['ex2'];
                    $sumaEx220 = $sumaEx220 + $notaArea['ex220'];
                    $sumaQ2 = $sumaQ2 + $notaArea['q2'];
                    $sumaFinalN = $sumaFinalN + $notaArea['final_ano_normal'];
                    $sumaFinalT = $sumaFinalT + $notaArea['final_total'];
                    $cont++;
                } else {
                    $grupoId = $this->busca_grupo_id($alumnoId, $mat['asignatura_id'], $modelPeriodo->codigo);

                    if ($grupoId == 0) {
                        $notaM = 0;
                    } else {
                        //$modelNota = $this->get_nota_materia($alumnoId, $materia['asignatura_id']);
                        $modelNotas = $this->get_nota_materia($grupoId);
                        $sumaP1 = $sumaP1 + $modelNotas['p1'];
                        $sumaP2 = $sumaP2 + $modelNotas['p2'];
                        $sumaPr1 = $sumaPr1 + $modelNotas['pr1'];
                        $sumaPr180 = $sumaPr180 + $modelNotas['pr180'];
                        $sumaEx1 = $sumaEx1 + $modelNotas['ex1'];
                        $sumaEx120 = $sumaEx120 + $modelNotas['ex120'];
                        $sumaQ1 = $sumaQ1 + $modelNotas['q1'];
                        $sumaP4 = $sumaP4 + $modelNotas['p4'];
                        $sumaP5 = $sumaP5 + $modelNotas['p5'];
                        $sumaPr2 = $sumaPr2 + $modelNotas['pr2'];
                        $sumaPr280 = $sumaPr280 + $modelNotas['pr280'];
                        $sumaEx2 = $sumaEx2 + $modelNotas['ex2'];
                        $sumaEx220 = $sumaEx220 + $modelNotas['ex220'];
                        $sumaQ2 = $sumaQ2 + $modelNotas['q2'];
                        $sumaFinalN = $sumaFinalN + $modelNotas['final_ano_normal'];
                        $sumaFinalT = $sumaFinalT + $modelNotas['final_total'];
                        $cont++;
                    }
                }
            }
        }

        $sentencias = new Notas();
        $digito = 2;

        if ($cont == 0) {
            $cont = 1;
        }

        $p1 = $sentencias->truncarNota(($sumaP1 / $cont), $digito);
        $p2 = $sentencias->truncarNota(($sumaP2 / $cont), $digito);
        $p3 = $sentencias->truncarNota(($sumaP3 / $cont), $digito);
        $pr1 = $sentencias->truncarNota(($sumaPr1 / $cont), $digito);
        $pr180 = $sentencias->truncarNota(($sumaPr180 / $cont), $digito);
        $ex1 = $sentencias->truncarNota(($sumaEx1 / $cont), $digito);
        $ex120 = $sentencias->truncarNota(($sumaEx120 / $cont), $digito);
        $q1 = $sentencias->truncarNota(($sumaQ1 / $cont), $digito);
        $p4 = $sentencias->truncarNota(($sumaP4 / $cont), $digito);
        $p5 = $sentencias->truncarNota(($sumaP5 / $cont), $digito);
        $p6 = $sentencias->truncarNota(($sumaP6 / $cont), $digito);
        $pr2 = $sentencias->truncarNota(($sumaPr2 / $cont), $digito);
        $pr280 = $sentencias->truncarNota(($sumaPr280 / $cont), $digito);
        $ex2 = $sentencias->truncarNota(($sumaEx2 / $cont), $digito);
        $ex220 = $sentencias->truncarNota(($sumaEx220 / $cont), $digito);
        $q2 = $sentencias->truncarNota(($sumaQ2 / $cont), $digito);
        $final_ano_normal = $sentencias->truncarNota(($sumaFinalN / $cont), $digito);
        $final_total = $sentencias->truncarNota(($sumaFinalT / $cont), $digito);

        return array(
            'p1' => $p1,
            'p2' => $p2,
            'p3' => $p3,
            'pr1' => $pr1,
            'pr180' => $pr180,
            'ex1' => $ex1,
            'ex120' => $ex120,
            'q1' => $q1,
            'p4' => $p4,
            'p5' => $p5,
            'p6' => $p6,
            'pr2' => $pr2,
            'pr280' => $pr280,
            'ex2' => $ex2,
            'ex220' => $ex220,
            'q2' => $q2,
            'final_ano_normal' => $final_ano_normal,
            'final_total' => $final_total
        );


//        $modelNotaMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
//        $notaMinima = $modelNotaMinima->valor;
//        
//        $sentencias = new Notas();
//        $digito = 2;
//        
//        $arreglo = array();
//        
//        if(isset(Yii::$app->user->identity->periodo_id)){
//            $periodoId = Yii::$app->user->identity->periodo_id;
//        }else{
//            $periodo = ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
//            $periodoId = $periodo->id;
//        }
//
//        
//        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
//        
//        $examenes = $this->promedio_examen_x_alumno($alumnoId, $modelPeriodo->codigo);
//        
//        $p1 = $this->toma_valor_por_orden($alumnoId, 1, $modelPeriodo->codigo);
//        $p2 = $this->toma_valor_por_orden($alumnoId, 2, $modelPeriodo->codigo);
//        $pr1    = $sentencias->truncarNota(($p1+$p2)/2, $digito);
//        $pr180  = $sentencias->truncarNota(($pr1 * 80)/100, $digito);
//        $ex1    = $examenes['ex1'];   
//        $ex120  = $sentencias->truncarNota(($ex1 * 20)/100, $digito);
//        $q1     = $pr180 + $ex120;
//        
//        $p3 = $this->toma_valor_por_orden($alumnoId, 5, $modelPeriodo->codigo);
//        $p4 = $this->toma_valor_por_orden($alumnoId, 6, $modelPeriodo->codigo);
//        $pr2    = $sentencias->truncarNota(($p3+$p4)/2, $digito);
//        $pr280  = $sentencias->truncarNota(($pr2 * 80)/100, $digito);
//        $ex2    = $examenes['ex2'];   
//        $ex220  = $sentencias->truncarNota(($ex2 * 20)/100, $digito);
//        $q2     = $pr280 + $ex220;
//        
//        $finalAnoNormal = $sentencias->truncarNota(($q1+$q2)/2, $digito);
//        
//        
//        array_push($arreglo, array(
//            'p1' => $p1,
//            'p2' => $p2,
//            'p3' => $p3,
//            'pr1' => $pr1,
//            'pr180' => $pr180,
//            'ex1' => $ex1,
//            'ex120' => $ex120,
//            'q1' => $q1,
//            'p4' => $p3,
//            'p5' => $p4,
////            'p6' => $p6,
//            'pr2' => $pr2,
//            'pr280' => $pr280,
//            'ex2' => $ex2,
//            'ex220' => $ex220,
//            'q2' => $q2,
//            'final_ano_normal' => $finalAnoNormal
//        ));                
//        
//        return $arreglo[0];
    }

    private function busca_grupo_id($alumnoId, $materiaId, $periodoId) {
        $con = \Yii::$app->db;
        $query = "select 	g.id 
                    from	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id 
                    where	g.estudiante_id = $alumnoId
                                    and c.idmateria = $materiaId
                                    and c.periodo_scholaris = '$periodoId';";

        $res = $con->createCommand($query)->queryOne();
        isset($res['id']) ? $grupo = $res['id'] : $grupo = 0;
        return $grupo;
    }

    private function toma_valor_por_orden($alumnoId, $orden, $periodoCodigo) {
        $con = Yii::$app->db;
        $query = "select  trunc(avg(nota),2) as nota 
                    from ( 
                                    select 	sum(c.nota) as nota
                                    from 	scholaris_calificaciones_parcial c
                                                    inner join scholaris_bloque_actividad b on b.id = c.bloque_id
                                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                                                    inner join scholaris_clase cla on cla.id =g.clase_id
                                                    inner join scholaris_malla_materia mm on mm.id = cla.malla_materia
                                                    inner join scholaris_materia m on m.id = mm.materia_id 
                                    where	g.estudiante_id  = $alumnoId
                                                    and b.orden = $orden
                                                    and cla.periodo_scholaris = '$periodoCodigo'
                                                    and mm.promedia = true
                                    group by m.name
                    ) as nota;	";

//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();

        isset($res['nota']) ? $nota = $res['nota'] : $nota = 0;
        return $nota;
    }

    private function promedio_examen_x_alumno($alumnoId, $periodoCodigo) {

        $con = Yii::$app->db;
        $query = "select 	trunc(avg(ex1),2) as ex1
		,trunc(avg(ex2),2) as ex2
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
where 	g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$periodoCodigo'
		and mm.promedia = true;";
        $res = $con->createCommand($query)->queryOne();


        return $res;
    }

    public function get_promedio_materia($materiaId, $paraleloId, $periodoId, $parcial) {
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paraleloId, 'idmateria' => $materiaId])->one();
        $uso = $modelClase->tipo_usu_bloque;

        switch ($parcial) {
            case 'p1':
                $orden = 1;
                break;

            case 'p2':
                $orden = 2;
                break;

            case 'p3':
                $orden = 3;
                break;

            case 'ex1':
                $orden = 4;
                break;

            case 'p4':
                $orden = 5;
                break;

            case 'p5':
                $orden = 6;
                break;

            case 'p6':
                $orden = 7;
                break;

            case 'ex2':
                $orden = 8;
                break;
        }


        $con = \Yii::$app->db;
        $query = "select trunc(avg(nota),2) as nota
                    from ( 
                                    select 	sum(nota) as nota
                                    from 	scholaris_calificaciones_parcial c
                                                    inner join scholaris_bloque_actividad b on b.id = c.bloque_id
                                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id 
                                                    inner join scholaris_clase cla on cla.id = g.clase_id 
                                    where	b.orden = $orden 
                                            and b.tipo_uso = '$uso'
                                            and cla.paralelo_id = $paraleloId
                                            and cla.periodo_scholaris = '$modelPeriodo->codigo'
                                            and cla.idmateria = $materiaId
                                    group by c.grupo_id
                            ) as nota";

        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

}
