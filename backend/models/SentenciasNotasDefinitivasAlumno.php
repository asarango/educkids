<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class SentenciasNotasDefinitivasAlumno extends \yii\db\ActiveRecord {

    public $alumnoId;
    public $periodoId;
    public $paralelo;
    public $q1;
    public $q2;
    public $finalNormal;
    public $notaFinalAprovechamiento;
    public $tieneCovid;
    private $q1TieneCovid = false;
    private $q2TieneCovid = false;
    public $TiposQuimestres;
    public $q1FinalNormal = 0;
    public $q2FinalNormal = 0;
    public $pfFinalNormal = 0;
    private $notaQ1Covid19 = 0;
    private $notaQ2Covid19 = 0;
    private $periodoCodigo;
    private $notaMinima;
    private $uso;
    private $modelBloqueQ1;
    private $modelBloqueQ2;
    private $calificacionCovidDosQuim = false;

    public function get_promedio_final_normal() {
        return $this->finalNormal;
    }

    public function __construct($alumnoId, $periodoId, $paraleloId) {
        $modelParam = ScholarisParametrosOpciones::find()->where([
                    'codigo' => 'notaminima'
                ])->one();

        $modelUso = ScholarisClase::find()->where([
                    'paralelo_id' => $paraleloId
                ])->one();
        $this->uso = $modelUso->tipo_usu_bloque;



        $this->notaMinima = $modelParam->valor;
        $this->alumnoId = $alumnoId;
        $this->periodoId = $periodoId;
        $this->paralelo = $paraleloId;
        $this->get_codigo_periodo_tipo_calificacion();


        $this->modelBloqueQ1 = ScholarisBloqueActividad::find()->where([
                    'tipo_bloque' => 'PARCIAL',
                    'tipo_uso' => $this->uso,
                    'quimestre' => 'QUIMESTRE I',
                    'scholaris_periodo_codigo' => $this->periodoCodigo
                ])->orderBy(['orden' => SORT_DESC])->all();


        $this->modelBloqueQ2 = ScholarisBloqueActividad::find()->where([
                    'tipo_bloque' => 'PARCIAL',
                    'tipo_uso' => $this->uso,
                    'quimestre' => 'QUIMESTRE II',
                    'scholaris_periodo_codigo' => $this->periodoCodigo
                ])->orderBy(['orden' => SORT_DESC])->all();        
        

        $this->tieneCovid = $this->tiene_covid();

        $this->asigna_notas_finales();
        $this->notas_con_covid();
        $this->revisa_notas_finales();
        $this->calcula_aprovechamiento_final();
    }

    private function calcula_aprovechamiento_final() {
        $this->notaFinalAprovechamiento = $this->get_nota_normal('final_total');
    }

    private function notas_con_covid() {
        $this->notaQ1Covid19 = $this->get_nota_covid19(1);
        $this->notaQ2Covid19 = $this->get_nota_covid19(2);
    }

    private function get_codigo_periodo_tipo_calificacion() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $model = ScholarisPeriodo::findOne($periodoId);

        $this->periodoCodigo = $model->codigo;
        if ($model->tipo_calificacion == 'covid') {
            $this->calificacionCovidDosQuim = true;
        }else{
            $this->calificacionCovidDosQuim = false;
        }
        
    }

    private function get_notas_covid_19($orden) {
        $con = Yii::$app->db;
        $query = "select 	c.total
from 	scholaris_calificacion_covid19 c 
		inner join op_student_inscription i on i.id = c.inscription_id
		inner join scholaris_quimestre_tipo_calificacion t on t.id = c.tipo_quimestre_id 
		inner join scholaris_quimestre q on q.id = t.quimestre_id 
where 	i.student_id = $this->alumnoId
		and i.parallel_id = $this->paralelo
		and q.orden = $orden;";
        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }

    private function tiene_covid() {
        $modelTipoCovid = ScholarisQuimestreTipoCalificacion::find()->where(['codigo' => 'covid19', 'periodo_scholaris_id' => $this->periodoId])->all();

        if (count($modelTipoCovid) > 0) {
            $this->TiposQuimestres = $modelTipoCovid;
            return true;
        } else {
            return false;
        }
    }

    private function asigna_notas_finales() {

        $sentencias = new SentenciasNotas();

        $modelQ1Covid19 = $this->get_nota_covid19(1);
        $modelQ2Covid19 = $this->get_nota_covid19(2);

        $modelQ1Normal = $this->get_nota_normal('q1');
        $modelQ2Normal = $this->get_nota_normal('q2');

        if ($this->tieneCovid == true) {
            if ($modelQ1Covid19 > 0) {
                $this->q1 = $modelQ1Covid19;
            } else {
                $this->q1 = $modelQ1Normal;
            }

            if ($modelQ2Covid19 > 0) {
                $this->q2 = $modelQ2Covid19;
            } else {
                $this->q2 = $modelQ2Normal;
            }
//            $this->finalNormal = $sentencias->truncarNota(($this->q1 + $this->q2) / 2,2);            
        } else {
            $this->q1 = $modelQ1Normal;
            $this->q2 = $modelQ2Normal;
//            $this->finalNormal = $modelFinalNormal;
        }


        $modelFinalNormal = $this->get_nota_normal('final_ano_normal');
        $this->finalNormal = $modelFinalNormal;
    }

    private function get_nota_covid19($orden) {
        $con = Yii::$app->db;
        $query = "select 	c.total 
from 	scholaris_calificacion_covid19 c
		inner join op_student_inscription i on i.id = c.inscription_id 
		inner join scholaris_quimestre_tipo_calificacion t on t.id = c.tipo_quimestre_id 
		inner join scholaris_quimestre q on q.id = t.quimestre_id 
where 	i.student_id = $this->alumnoId
		and i.parallel_id = $this->paralelo
		and q.orden = $orden;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();
        if (isset($res['total'])) {
            $nota = $res['total'];
        } else {
            $nota = 0;
        }
        return $nota;
    }

    public function get_nota_normal($campo) {

        $con = Yii::$app->db;
//        $query = "select trunc(avg(nota),2) as nota 
        $query = "select trunc(avg(nota),2) as nota 
                        from(
                        select 	l.$campo as nota
                        from 	scholaris_grupo_alumno_clase g
                                        inner join scholaris_clase_libreta l on l.grupo_id = g.id 
                                        inner join scholaris_clase c on c.id = g.clase_id
                                        inner join scholaris_malla_materia mm on mm.id  = c.malla_materia 
                        where 	c.periodo_scholaris = '$this->periodoCodigo'
                                        and g.estudiante_id  = $this->alumnoId
                                        and mm.promedia = true
                        union all	
                        select trunc(sum(l.$campo * mm.total_porcentaje/100),2) as nota 
                        from 	scholaris_grupo_alumno_clase g
                                        inner join scholaris_clase_libreta l on l.grupo_id = g.id 
                                        inner join scholaris_clase c on c.id = g.clase_id
                                        inner join scholaris_malla_materia mm on mm.id  = c.malla_materia
                                        inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                        where 	c.periodo_scholaris = '$this->periodoCodigo'
                                        and g.estudiante_id  = $this->alumnoId
                                        and ma.promedia = true
                        group by ma.id
                        ) as nota";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

    public function get_nota_area($areaId) {
        $sentencias = new SentenciasNotas();
        $modelPromedia = ScholarisMallaArea::findOne($areaId);
        $arregloNotas = array();

        $modelNotas = $this->calcula_area($areaId);

        if ($modelPromedia->promedia == true) {

            if ($this->notaQ1Covid19 == 0 && $this->notaQ2Covid19 != 0) {
                

                $finAnoNormal = ( $modelNotas['q1'] + $this->notaQ2Covid19) / 2;
                $finAnoNormal = $sentencias->truncarNota($finAnoNormal, 2);

                return array(
                    'p1' => $modelNotas['p1'],
                    'p2' => $modelNotas['p2'],
                    'p3' => $modelNotas['p3'],
                    'pr1' => $modelNotas['pr1'],
                    'pr180' => $modelNotas['pr180'],
                    'ex1' => $modelNotas['ex1'],
                    'ex120' => $modelNotas['ex120'],
                    'q1' => $modelNotas['q1'],
                    'p4' => $this->notaQ2Covid19,
                    'p5' => $this->notaQ2Covid19,
                    'p6' => $this->notaQ2Covid19,
                    'pr2' => $this->notaQ2Covid19,
                    'pr280' => $this->notaQ2Covid19,
                    'ex2' => $this->notaQ2Covid19,
                    'ex220' => $this->notaQ2Covid19,
                    'q2' => $this->notaQ2Covid19,
                    'final_ano_normal' => $finAnoNormal,
                    'final_total' => $modelNotas['final_total'],
                );
            } elseif ($this->notaQ2Covid19 == 0 && $this->notaQ1Covid19 != 0) {
                
                $finAnoNormal = ( $this->notaQ1Covid19 + $modelNotas['q2']) / 2;
                $finAnoNormal = $sentencias->truncarNota($finAnoNormal, 2);
                return array(
                    'p1' => $this->notaQ1Covid19,
                    'p2' => $this->notaQ1Covid19,
                    'p3' => $this->notaQ1Covid19,
                    'pr1' => $this->notaQ1Covid19,
                    'pr180' => $this->notaQ1Covid19,
                    'ex1' => $this->notaQ1Covid19,
                    'ex180' => $this->notaQ1Covid19,
                    'q1' => $this->notaQ1Covid19,
                    'p4' => $modelNotas['p4'],
                    'p5' => $modelNotas['p5'],
                    'p6' => $modelNotas['p6'],
                    'pr2' => $modelNotas['pr2'],
                    'pr280' => $modelNotas['pr280'],
                    'ex2' => $modelNotas['ex2'],
                    'ex220' => $modelNotas['ex220'],
                    'q2' => $modelNotas['q2'],
                    'final_ano_normal' => $modelNotas['final_ano_normal'],
                    'final_total' => $modelNotas['final_total'],
                );
            } elseif ($this->notaQ2Covid19 == 0 && $this->notaQ1Covid19 == 0) {
                
                

                return $modelNotas;
            }
        } else {
            return '*';
        }
    }

    private function calcula_area($areaId) {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $con = Yii::$app->db;
        $query = "select 	sum(trunc(l.p1 * mm.total_porcentaje/100,2)) as p1
		,sum(trunc(l.p2 * mm.total_porcentaje/100,2)) as p2
		,sum(trunc(l.p3 * mm.total_porcentaje/100,2)) as p3
		,sum(trunc(l.pr1 * mm.total_porcentaje/100,2)) as pr1
		,sum(trunc(l.pr180 * mm.total_porcentaje/100,2)) as pr180
		,sum(trunc(l.ex1 * mm.total_porcentaje/100,2)) as ex1
		,sum(trunc(l.ex120 * mm.total_porcentaje/100,2)) as ex120
		,sum(trunc(l.q1 * mm.total_porcentaje/100,2)) as q1
		,sum(trunc(l.p4 * mm.total_porcentaje/100,2)) as p4
		,sum(trunc(l.p5 * mm.total_porcentaje/100,2)) as p5
		,sum(trunc(l.p6 * mm.total_porcentaje/100,2)) as p6
		,sum(trunc(l.pr2 * mm.total_porcentaje/100,2)) as pr2
		,sum(trunc(l.pr280 * mm.total_porcentaje/100,2)) as pr280
		,sum(trunc(l.ex2 * mm.total_porcentaje/100,2)) as ex2
		,sum(trunc(l.ex220 * mm.total_porcentaje/100,2)) as ex220
		,sum(trunc(l.q2 * mm.total_porcentaje/100,2)) as q2
		,sum(trunc(l.final_ano_normal * mm.total_porcentaje/100,2)) as final_ano_normal
		,trunc(sum(l.final_total * mm.total_porcentaje/100),2) as final_total
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id 
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia mm on mm.id  = c.malla_materia
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
where 	c.periodo_scholaris = '$modelPeriodo->codigo'
		and g.estudiante_id  = $this->alumnoId
		and ma.promedia = true
		and ma.id = $areaId;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function calcula_nota_final_materia($grupoId, $finalAnoNormal, $finalConMejora, $supletorio, $remedial, $gracia) {

        $finalMateria = 0;

        $modelMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

        if ($finalConMejora > 0 && $finalConMejora > $finalAnoNormal) {
            $finalMateria = $finalConMejora;
        } elseif ($supletorio >= $modelMinima->valor || $supletorio >= $modelMinima->valor || $gracia >= $modelMinima->valor) {
            $finalMateria = $modelMinima->valor;
        } else {
            $finalMateria = $finalAnoNormal;
        }


        $model = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupoId])->one();
        if (isset($model->final_total)) {
            $model->final_total = $finalMateria;
            $model->save();
        }





        return $finalMateria;
    }

    public function revisa_notas_finales() {
        $con = Yii::$app->db;
        $query = "select 	c.idmateria, g.id 
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id 
where 	g.estudiante_id = $this->alumnoId
		and c.paralelo_id = $this->paralelo;";

        $res = $con->createCommand($query)->queryAll();

        foreach ($res as $data) {
            $this->get_nota_materia($data['idmateria'], $data['id']);
        }
    }

    private function consulta_nota_covid_v2($grupoId, $orden) {
        $con = Yii::$app->db;
        $query = "select sum(nota) as nota 
                    from scholaris_calificaciones_parcial p
                                    inner join scholaris_bloque_actividad b on b.id = p.bloque_id 
                    where 	p.grupo_id = $grupoId
                                    and b.orden = $orden
                            and b.tipo_uso = '$this->uso'
                            and b.scholaris_periodo_codigo = '$this->periodoCodigo';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }
    
    private function verifica_nota_final_covid($q1, $q2, $final_ano_normal,$mejora_q1,$mejora_q2, 
                                               $final_con_mejora,$supletorio,$remedial, 
                                               $gracia){
        /**
         * FALTA REALIZAR EL PROCESO DE NOTA FINAL PARA COVID
         */
        return 0;
        
    }
    
    private function devuelve_nota_cabiada_disciplinar($grupoId, $orden, $queCalificaCodigo){
        $con = Yii::$app->db;
        $query = "select 	nota_nueva 
                    from 	scholaris_calificaciones_parcial_cambios c
                                    inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
                    where	c.grupo_id = $grupoId
                                    and c.codigo_que_califica = '$queCalificaCodigo'
                                    and b.orden = $orden
                    order by c.id desc
                    limit 1;";
        $res = $con->createCommand($query)->queryOne();
        if(isset($res['nueva_nota'])){
            return $res['nueva_nota'];
        }else{
            return 0;
        }
    }
    
    private function verifica_notas_covid_2021($grupoId, $orden){
        $con = Yii::$app->db;
        $query = "select 	p.bloque_id, p.grupo_id, p.codigo_que_califica, p.quien_califica, p.tipo_calificacion, p.clase_usada, p.nota 
                    from 	scholaris_calificaciones_parcial p
                                    inner join scholaris_bloque_actividad b on b.id = p.bloque_id 
                    where	p.grupo_id = $grupoId
                                    and b.orden = $orden;";
        $resNormales = $con->createCommand($query)->queryAll();
        
//        if($grupoId == 33061){
            
            $suma = 0;
            foreach ($resNormales as $normal){
                
                $nuevaNota = $this->devuelve_nota_cabiada_disciplinar($grupoId, $orden, $normal['codigo_que_califica']);
                
                if($nuevaNota){
                    $suma = $suma + $nuevaNota;
                }else{
                    $suma = $suma + $normal['nota'];
                }
                
                
            }
                        
            return $suma;
//        }

        
    }

    public function get_nota_materia($materiaId, $grupoId) {
        
//        if($materiaId==''){
//            echo $grupoId;
//            die();
//        }
        
        $sentencias = new SentenciasNotas();
        $digito = 2;
        $arregloNotas = array();

        $modelNotas = $this->calcula_materia($materiaId, $grupoId);



        if ($this->calificacionCovidDosQuim == 1) {           

            $p1 = $this->verifica_notas_covid_2021($grupoId, 1);
            $p2 = $this->verifica_notas_covid_2021($grupoId, 2);
            $p3 = $this->verifica_notas_covid_2021($grupoId, 3);
            
            $p4 = $this->verifica_notas_covid_2021($grupoId, 5);
            $p5 = $this->verifica_notas_covid_2021($grupoId, 6);
            $p6 = $this->verifica_notas_covid_2021($grupoId, 7);
            
            $pr1 = $sentencias->truncarNota(($p1+$p2+$p3)/count($this->modelBloqueQ1), $digito);
            $pr180 = $sentencias->truncarNota(($pr1*80/100), $digito);
            $q1 = $pr180 + $modelNotas['ex120'];
            
            $pr2 = $sentencias->truncarNota(($p4+$p5+$p6)/count($this->modelBloqueQ2), $digito);
            $pr280 = $sentencias->truncarNota(($pr2*80/100), $digito);
            $q2 = $pr280 + $modelNotas['ex220'];
            
            $finalAnoNormal = $sentencias->truncarNota(($q1+$q2)/2, $digito);
            $finalTotal = $this->verifica_nota_final_covid($q1, $q2, $finalAnoNormal, $modelNotas['mejora_q1'], $modelNotas['mejora_q2'], 
                                                           $modelNotas['final_con_mejora'], $modelNotas['supletorio'], $modelNotas['remedial'], 
                                                           $modelNotas['gracia']);
            
            return array(
                'p1' => $p1,
                'p2' => $p2,
                'p3' => $p3,
                'pr1' => $pr1,
                'pr180' => $pr180,
                'ex1' => $modelNotas['ex1'],
                'ex120' => $modelNotas['ex120'],
                'q1' => $q1,
                'p4' => $p4,
                'p5' => $p5,
                'p6' => $p6,
                'pr2' => $pr2,
                'pr280' => $pr280,
                'ex2' => $modelNotas['ex2'],
                'ex220' => $modelNotas['ex220'],
                'q2' => $q2,
                'final_ano_normal' => $finalAnoNormal,
                'mejora_q1' => $modelNotas['mejora_q1'],
                'mejora_q2' => $modelNotas['mejora_q2'],
                'final_con_mejora' => $modelNotas['final_con_mejora'],
                'supletorio' => $modelNotas['supletorio'],
                'remedial' => $modelNotas['remedial'],
                'gracia' => $modelNotas['gracia'],
                'final_total' => $finalTotal
            );
        } else
        if ($this->notaQ1Covid19 == 0 && $this->notaQ2Covid19 != 0) {

                  
            
            $finalAnoNormal = ($modelNotas['q1'] + $this->notaQ2Covid19) / 2;
            $finalAnoNormal = $sentencias->truncarNota($finalAnoNormal, 2);

            $notaFInalMateria = $this->calcula_nota_final_materia($grupoId, $finalAnoNormal, $modelNotas['final_con_mejora'], $modelNotas['supletorio'], $modelNotas['remedial'], $modelNotas['gracia']);

            return array(
                'p1' => $modelNotas['p1'],
                'p2' => $modelNotas['p2'],
                'p3' => $modelNotas['p3'],
                'pr1' => $modelNotas['pr1'],
                'pr180' => $modelNotas['pr180'],
                'ex1' => $modelNotas['ex1'],
                'ex120' => $modelNotas['ex120'],
                'q1' => $modelNotas['q1'],
                'p4' => $this->notaQ2Covid19,
                'p5' => $this->notaQ2Covid19,
                'p6' => $this->notaQ2Covid19,
                'pr2' => $this->notaQ2Covid19,
                'pr280' => $this->notaQ2Covid19,
                'ex2' => $this->notaQ2Covid19,
                'ex220' => $this->notaQ2Covid19,
                'q2' => $this->notaQ2Covid19,
                'final_ano_normal' => $finalAnoNormal,
                'mejora_q1' => $modelNotas['mejora_q1'],
                'mejora_q2' => $modelNotas['mejora_q2'],
                'final_con_mejora' => $modelNotas['final_con_mejora'],
                'supletorio' => $modelNotas['supletorio'],
                'remedial' => $modelNotas['remedial'],
                'gracia' => $modelNotas['gracia'],
                'final_total' => $notaFInalMateria,
            );
        } elseif ($this->notaQ2Covid19 == 0 && $this->notaQ1Covid19 != 0) {
                  
            $finalAnoNormal = ($this->notaQ1Covid19 + $modelNotas['q2']) / 2;
            $finalAnoNormal = $sentencias->truncarNota($finalAnoNormal, 2);

            $notaFInalMateria = $this->calcula_nota_final_materia($grupoId, $finalAnoNormal, $modelNotas['final_con_mejora'], $modelNotas['supletorio'], $modelNotas['remedial'], $modelNotas['gracia']);

            return array(
                'p1' => $this->notaQ1Covid19,
                'p2' => $this->notaQ1Covid19,
                'p3' => $this->notaQ1Covid19,
                'pr1' => $this->notaQ1Covid19,
                'pr180' => $this->notaQ1Covid19,
                'ex1' => $this->notaQ1Covid19,
                'ex180' => $this->notaQ1Covid19,
                'q1' => $this->notaQ1Covid19,
                'p4' => $modelNotas['p4'],
                'p5' => $modelNotas['p5'],
                'p6' => $modelNotas['p6'],
                'pr2' => $modelNotas['pr2'],
                'pr280' => $modelNotas['pr280'],
                'ex2' => $modelNotas['ex2'],
                'ex220' => $modelNotas['ex220'],
                'q2' => $modelNotas['q2'],
                'final_ano_normal' => $finalAnoNormal,
                'mejora_q1' => $modelNotas['mejora_q1'],
                'mejora_q2' => $modelNotas['mejora_q2'],
                'final_con_mejora' => $modelNotas['final_con_mejora'],
                'supletorio' => $modelNotas['supletorio'],
                'remedial' => $modelNotas['remedial'],
                'gracia' => $modelNotas['gracia'],
                'final_total' => $notaFInalMateria,
            );
        } elseif ($this->notaQ2Covid19 == 0 && $this->notaQ1Covid19 == 0) {
//                echo 'aqui';
//                print_r($modelNotas);
//                
//                die();
            return $modelNotas;
        }
    }

    private function cambia_nota_final_materia() {
        
    }

    private function calcula_materia($materiaId, $grupoId) {

        $this->modifica_totales_en_tabla_libreta($materiaId, $grupoId);


        $con = Yii::$app->db;
        $query = "select 	l.p1,l.p2,l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1
                               ,l.p4,l.p5,l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal
                               ,l.mejora_q1, l.mejora_q2
                               ,l.final_con_mejora
                               ,l.supletorio
                               ,l.remedial
                               ,l.gracia
                               ,l.final_total
from 	scholaris_grupo_alumno_clase g
	 	inner join scholaris_clase c on c.id = g.clase_id
	 	inner join scholaris_clase_libreta l on l.grupo_id = g.id
where 	g.estudiante_id = $this->alumnoId
		and c.periodo_scholaris = '$this->periodoCodigo'
		and c.idmateria = $materiaId;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function modifica_totales_en_tabla_libreta($materiaId, $grupoId) {

        if (!ScholarisClaseLibreta::find()->where(['grupo_id' => $grupoId])->one()) {
            $modelL = new ScholarisClaseLibreta();
            $modelL->grupo_id = $grupoId;
            $modelL->save();
        }

        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $sentencias = new Notas();
        $digito = 2;
        
//        echo $this->periodoCodigo.'<br>';
//        echo $institutoId.'<br>';
//        echo $this->uso.'<br>';
//        die();

        $modelBloquesQ1 = ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL',
                    'quimestre' => 'QUIMESTRE I',
                    'instituto_id' => $institutoId,
                    'tipo_uso' => $this->uso
                ])->all();

        $modelBloquesQ2 = ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL',
                    'quimestre' => 'QUIMESTRE II',
                    'instituto_id' => $institutoId,
                    'tipo_uso' => $this->uso
                ])->all();

        $totalBloquesQ1 = count($modelBloquesQ1);
        $totalBloquesQ2 = count($modelBloquesQ2);

        $con = Yii::$app->db;
        $query = "select 	l.id,l.p1,l.p2,l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1
                               ,l.p4,l.p5,l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal
                               ,l.mejora_q1, l.mejora_q2
                               ,l.final_con_mejora
                               ,l.supletorio
                               ,l.remedial
                               ,l.gracia
                               ,l.final_total
from 	scholaris_grupo_alumno_clase g
	 	inner join scholaris_clase c on c.id = g.clase_id
	 	inner join scholaris_clase_libreta l on l.grupo_id = g.id
where 	g.estudiante_id = $this->alumnoId
		and c.periodo_scholaris = '$this->periodoCodigo'
		and c.idmateria = $materiaId;";

        $res = $con->createCommand($query)->queryOne();

        $id = $res['id'];
        $p1 = $res['p1'];
        $p2 = $res['p2'];
        $p3 = $res['p3'];
        $ex120 = $res['ex120'];

        $p4 = $res['p4'];
        $p5 = $res['p5'];
        $p6 = $res['p6'];
        $ex220 = $res['ex220'];

        $mejora1 = $res['mejora_q1'];
        $mejora2 = $res['mejora_q2'];

        $supletorio = $res['supletorio'];
        $remedial = $res['remedial'];
        $gracia = $res['gracia'];

        //CALCULO DE QUIMSTRE1
        $pr1 = ($p1 + $p2 + $p3) / $totalBloquesQ1;
        $pr1 = $sentencias->truncarNota($pr1, $digito);
        $pr180 = ($pr1 * 80) / 100;
        $pr180 = $sentencias->truncarNota($pr180, $digito);
        $q1 = $pr180 + $ex120;

        //CALCULO DE QUIMSTRE1
        $pr2 = ($p4 + $p5 + $p6) / $totalBloquesQ2;
        $pr2 = $sentencias->truncarNota($pr2, $digito);
        $pr280 = ($pr2 * 80) / 100;
        $pr280 = $sentencias->truncarNota($pr280, $digito);
        $q2 = $pr280 + $ex220;

        //CALCULO DE 2 QUIMESTRES
        $final_ano_normal = ($q1 + $q2) / 2;
        $final_ano_normal = $sentencias->truncarNota($final_ano_normal, $digito);

        //CALCULO DE MEJORA DE QUIMESTRE
        if ($mejora1 > $q1 && $mejora2 > $q2) {
            $totalMejorado = ($mejora1 + $mejora2) / 2;
            $totalMejorado = $sentencias->truncarNota($totalMejorado, $digito);
        } elseif ($mejora1 > $q1) {
            $totalMejorado = ($mejora1 + $q2) / 2;
            $totalMejorado = $sentencias->truncarNota($totalMejorado, $digito);
        } elseif ($mejora2 > $q2) {
            $totalMejorado = ($mejora2 + $q1) / 2;
            $totalMejorado = $sentencias->truncarNota($totalMejorado, $digito);
        } else {
            $totalMejorado = $final_ano_normal;
        }



        //CALCULO DE FINAL 
        if ($supletorio >= $this->notaMinima || $remedial >= $this->notaMinima || $supletorio >= $this->notaMinima) {
            $finalTotal = $this->notaMinima;
        } else {
            $finalTotal = $totalMejorado;
        }

        $model = ScholarisClaseLibreta::findOne($id);

        if (isset($model)) {



            $pr1 ? $pr1 = $pr1 : $pr1 = 0;
            $pr180 ? $pr180 = $pr180 : $pr180 = 0;
            $q1 ? $q1 = $q1 : $q1 = 0;

            $pr2 ? $pr2 = $pr2 : $pr2 = 0;
            $pr280 ? $pr280 = $pr280 : $pr280 = 0;
            $q2 ? $q2 = $q2 : $q2 = 0;

            $model->pr1 = $pr1;
            $model->pr180 = $pr180;
            $model->q1 = $q1;

            $model->pr2 = $pr2;
            $model->pr280 = $pr280;
            $model->q2 = $q2;

            $model->final_ano_normal = $final_ano_normal;
            $model->final_con_mejora = $totalMejorado;
            $model->final_total = $finalTotal;
            $model->save();
        }
    }

    public function get_notas_proyectos() {

        $sentencias = new SentenciasRepLibreta2();
        $q1 = 0;
        $q2 = 0;

        $con = Yii::$app->db;
        $query = "select 	l.id, l.grupo_id, l.p1, l.p2, l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1, l.p4, l.p5, l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal, l.mejora_q1, l.mejora_q2, l.final_con_mejora, l.supletorio, l.remedial, l.gracia, l.final_total, l.estado 
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
where	g.estudiante_id = $this->alumnoId
		and c.periodo_scholaris = '$this->periodoCodigo'
		and mm.tipo = 'PROYECTOS';";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        $q1 = $sentencias->homologaProyectos($res['q1']);
        $q2 = $sentencias->homologaProyectos($res['q2']);

        isset($q1['abreviatura']) ? $q1Abreviatura = $q1['abreviatura'] : $q1Abreviatura = 'N/C';
        isset($q2['abreviatura']) ? $q2Abreviatura = $q2['abreviatura'] : $q2Abreviatura = 'N/C';
        
        return array(
            'q1' => $q1Abreviatura,
            'q2' => $q2Abreviatura
        );
    }

    public function get_notas_comportamiento() {

        $modelInscription = OpStudentInscription::find()->where([
                    'student_id' => $this->alumnoId,
                    'parallel_id' => $this->paralelo
                ])->one();

        $sentencias = new SentenciasRepLibreta2();
        $q1 = 0;
        $q2 = 0;

        $con = Yii::$app->db;
        $query = "select 	c.calificacion 
		,b.quimestre 
		,b.name
		,b.id 
		,b.orden 
from 	scholaris_califica_comportamiento c
		inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
where 	inscription_id = $modelInscription->id;";

        $res = $con->createCommand($query)->queryAll();

        foreach ($res as $nq1) {
            if ($nq1['orden'] == 2) {
                $rq1 = $nq1['calificacion'];
            }
        }

        foreach ($res as $nq2) {
            if ($nq2['orden'] == 6) {
                $rq2 = $nq2['calificacion'];
            }
        }


        isset($rq1) ? $q1 = $sentencias->homologaComportamiento($rq1) : $q1 = $sentencias->homologaComportamiento(0);
        isset($rq2) ? $q2 = $sentencias->homologaComportamiento($rq2) : $q2 = $sentencias->homologaComportamiento(0);

        return array(
            'q1' => $q1['abreviatura'],
            'q2' => $q2['abreviatura']
        );
    }

    public function toma_comportamiento() {
        $sentencias = new SentenciasRepLibreta2();

        $arregloNota = $sentencias->get_notas_finales_comportamiento($this->alumnoId);

        return $arregloNota;
    }

}
