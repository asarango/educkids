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
class Notas extends \yii\db\ActiveRecord {

//    public function actualizaParcialesLibreta($clase) {
//        
//    }

    public function truncarNota($numero, $digito) {
        $raiz = 100;
        $multiplicado = $numero * $raiz;
        $extrae = explode('.', $multiplicado);
        $entero = $extrae[0];
        $resultado = $entero / $raiz;

        return $resultado;
    }

    /**
     * METODO PRICIPAL PARA INVOCAR EL CAMBIO DE NOTA AL PARCIAL
     * METODO QUE REALIZA LA ACTUALIZACION DEL PARCIAL
     * Toma en cuenta que si el promedio es menor que el minimo, 
     * realiza la pregunta si existe refuerzos
     * @param type $bloque
     * @param type $alumno
     * @param type $clase
     */
    public function actualiza_parcial($bloque, $alumno, $clase) {

        $modelMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minima = $modelMinima['valor'];

        $modelPromedioParcial = $this->get_promedio_parcial($clase, $alumno, $bloque);

        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where(['estudiante_id' => $alumno, 'clase_id' => $clase])
                ->one();

        if ($modelPromedioParcial['promedio'] < $minima) {
            $promedio = $this->nota_parcial_con_refuerzo($clase, $bloque, $alumno, $modelGrupo->id);
            $this->actualizar_libreta_parcial($bloque, $modelGrupo->id, $promedio);
        } else {
            $promedio = $this->get_promedio_parcial($clase, $alumno, $bloque);
            $this->actualizar_libreta_parcial($bloque, $modelGrupo->id, $promedio['promedio']);
        }
    }

    private function nota_parcial_con_refuerzo($clase, $bloque, $alumno, $grupoId) {
        $sentencias = new \frontend\models\SentenciasSql();

        $modelActividades = $sentencias->get_insumos($clase, $bloque);


        $cont = 0;
        $suma = 0;
        foreach ($modelActividades as $act) {
            $modelNotaSinRefuerzo = $this->get_promedio_insumo($clase, $alumno, $bloque, $act['grupo_numero']);
            $notaOriginal = $modelNotaSinRefuerzo['calificacion'];

            $modelNotaConRefuerzo = ScholarisRefuerzo::find()
                    ->where(['grupo_id' => $grupoId, 'bloque_id' => $bloque, 'orden_calificacion' => $act['grupo_numero']])
                    ->one();
            $notaReforzada = $modelNotaConRefuerzo['nota_final'];


            if ($notaReforzada > $notaOriginal) {
                $nota = $notaReforzada;
            } else {
                $nota = $notaOriginal;
            }

            if (isset($nota)) {
                $cont++;
                $suma = $suma + $nota;
            }
        }

        if ($cont == 0) {
//            echo $clase.'*****'.$alumno.'<br>';
//            print_r($suma);
//            die();
            $cont = 1;
        }

        $promedio = $suma / $cont;
        $promedio = $this->truncarNota($promedio, 2);
        return $promedio;
    }

    /**
     * METODO PARA TOMAR EL PROMEDIO DEL INSUMO
     * 
     * @param type $clase
     * @param type $alumno
     * @param type $bloque
     * @param type $orden
     * @return type
     */
    public function get_promedio_insumo($clase, $alumno, $bloque, $orden) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(c.calificacion),2) as calificacion
                    from	scholaris_calificaciones c
                                    inner join scholaris_actividad a on a.id = c.idactividad
                    where	c.idalumno = $alumno
                                    and c.grupo_numero = $orden
                                    and a.paralelo_id = $clase
                                    and a.bloque_actividad_id = $bloque
                                    and a.calificado = 'SI';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    /**
     * METODO PARA TOMAR EL VALOR DEL PARCIAL ANTES DE LA REVISION DE REFUERZOS
     * 
     * @param type $clase
     * @param type $alumno
     * @param type $bloque
     * @return type
     */
    public function get_promedio_parcial($clase, $alumno, $bloque) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(promedio),2) as promedio
                    from(
                                            select trunc(avg(c.calificacion),2) as promedio
                                                            ,c.grupo_numero
                                            from	scholaris_calificaciones c
                                                            inner join scholaris_actividad a on a.id = c.idactividad
                                            where	c.idalumno = $alumno                                                            
                                                            and a.paralelo_id = $clase
                                                            and a.bloque_actividad_id = $bloque
                                                            and a.calificado = 'SI'
                                            group by c.grupo_numero
                    ) as promedio;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function actualizar_libreta_parcial($bloque, $grupo, $promedio) {

        $modelLibreta = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();
        if (isset($modelLibreta)) {
            
        } else {
            $model = new ScholarisClaseLibreta();
            $model->grupo_id = $grupo;
            $model->save();
        }

        $modelLibreta = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();
        $this->reemplaza_nota($bloque, $grupo, $promedio);
    }

    private function reemplaza_nota($bloque, $grupo, $promedio) {

        $modelBloque = ScholarisBloqueActividad::find()->where(['id' => $bloque])->one();
        $orden = $modelBloque->orden;


        switch ($orden) {
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

        $this->ejecuta_actualizacion_nota($campo, $grupo, $promedio);
    }

    private function ejecuta_actualizacion_nota($campo, $grupo, $promedio) {



        $model = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();
        $model->$campo = $promedio;
        $model->save();

        $digito = 2;

        $model1 = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();

        $model1->pr1 = ($model1->p1 + $model1->p2 + $model1->p3) / 3;
        $model1->pr1 = $this->truncarNota($model1->pr1, $digito);

        $model1->pr180 = $model1->pr1 * 80 / 100;
        $model1->pr180 = $this->truncarNota($model1->pr180, $digito);

        $model1->ex120 = $model1->ex1 * 20 / 100;
        $model1->ex120 = $this->truncarNota($model1->ex120, $digito);

        $model1->q1 = $model1->pr180 + $model1->ex120;

        /*         * **** degundo quimestre ******** */
        $model1->pr2 = ($model1->p4 + $model1->p5 + $model1->p6) / 3;
        $model1->pr2 = $this->truncarNota($model1->pr2, $digito);

        $model1->pr280 = $model1->pr2 * 80 / 100;
        $model1->pr280 = $this->truncarNota($model1->pr280, $digito);

        $model1->ex220 = $model1->ex2 * 20 / 100;
        $model1->ex220 = $this->truncarNota($model1->ex220, $digito);

        $model1->q2 = $model1->pr280 + $model1->ex220;


        $model1->final_ano_normal = ($model1->q1 + $model1->q2) / 2;
        $model1->final_ano_normal = $this->truncarNota($model1->final_ano_normal, $digito);

        $model1->save();
    }

    public function get_nota_parcial($bloque, $grupoId) {
        $modelBloque = ScholarisBloqueActividad::find()->where(['id' => $bloque])->one();
        $orden = $modelBloque->orden;

        $campo = $this->get_campo_libreta($orden);

        $modelLibreta = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupoId])->one();
        return $modelLibreta->$campo;
    }

    private function get_campo_libreta($orden) {
        switch ($orden) {
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

    /*     * ****PROMEDIOS FINALES DE ALUMNOS ***** */

    public function get_notas_finales($alumno, $usuario, $malla) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1
		--
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(final_ano_normal),2) as final_ano_normal
		,trunc(avg(final_total),2) as final_total
from (
select trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1
		--
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(final_ano_normal),2) as final_ano_normal
		,trunc(avg(final_total),2) as final_total
from 	scholaris_notas_areas n
		inner join scholaris_malla_area a on a.id = n.malla_area_id		
where	n.alumno_id = $alumno
		and a.malla_id = $malla
		and a.promedia = true
		and n.usuario = '$usuario'
union
select trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1
		--
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(final_ano_normal),2) as final_ano_normal
		,trunc(avg(final_total),2) as final_total
from	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia m on m.id = c.malla_materia
		inner join scholaris_malla_area a on a.id = m.malla_area_id
where	g.estudiante_id = $alumno
		and a.malla_id = $malla
		and m.promedia = true
) as nota;";

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    ///////////////////////////////////////////



    public function get_conclusion_antes_supletorios($grupo, $clase, $nota) {
        $modelSuple = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaSuple'])->one();
        $modelRemed = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaRemed'])->one();
        

        if ($nota < $modelSuple->valor && $nota >= $modelRemed->valor) {
            $conclucion = 'SUPLETORIO';
        } elseif ($nota < $modelRemed->valor) {
            $conclucion = 'REMEDIAL';
        } else {
            $conclucion = 'APROBADO';
        }

        return $conclucion;
    }

    /*     * ***HOMOLOGACIONES***** */

    public function homologa_cualitativas($nota) {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        
        isset($nota) ? $nota = $nota : $nota = 0;

        $con = Yii::$app->db;
        $query = "select abreviatura
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'PROYECTOS'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and $nota between rango_minimo and rango_maximo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        isset($res['abreviatura']) ? $nota = $res['abreviatura'] : $nota = 'R';
        return $nota;
    }

    public function get_descripcion_proyectos($nota) {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $con = Yii::$app->db;
        $query = "select descripcion
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'PROYECTOS'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and $nota between rango_minimo and rango_maximo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        $nota = $res['descripcion'];
        return $nota;
    }
    
    public function get_descripcion_proyectos_x_cualitativo($nota) {
        
        

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $con = Yii::$app->db;
        $query = "select descripcion
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'PROYECTOS'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and abreviatura = '$nota';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        $nota = $res['descripcion'];
        return $nota;
    }

    public function homologa_comportamiento($nota, $seccion) {

        if ($nota) {
            $nota = $nota;
        } else {
            $nota = 0;
        }

        if(isset(Yii::$app->user->identity->periodo_id)){
            $periodoId = Yii::$app->user->identity->periodo_id;
        }else{
            $modelPer = ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            $periodoId = $modelPer->id;
        }
        
        //$periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $con = Yii::$app->db;
        $query = "select abreviatura
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'COMPORTAMIENTO'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and $nota between rango_minimo and rango_maximo "
                . "and section_codigo = '$seccion';";
        
        $res = $con->createCommand($query)->queryOne();
        $nota = 'R';
        if(isset($res['abreviatura'])){
            $nota = $res['abreviatura'];
        }else{
            $nota = 'R';
        }
        
        return $nota;
    }

    public function homologa_comportamiento_mec($nota, $seccion) {

        if ($nota) {
            $nota = $nota;
        } else {
            $nota = 0;
        }

//        die();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $con = Yii::$app->db;
        $query = "select abreviatura, descripcion
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'COMPORTAMIENTO'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and $nota between rango_minimo and rango_maximo "
                . "and section_codigo = '$seccion';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    /*     * ********************** */
    
    public function homologa_comportamiento_mec_x_abrev($nota, $seccion) {

        if ($nota) {
            $nota = $nota;
        } else {
            $nota = 'R';
        }

//        die();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $con = Yii::$app->db;
        $query = "select abreviatura, descripcion
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'COMPORTAMIENTO'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and abreviatura = '$nota'"
                . "and section_codigo = '$seccion';";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();

        return $res['descripcion'];
    }

    /*     * ********************** */

    public function ejecutar_termino_ano_clase($clase) {
        $modelLibreta = ScholarisClaseLibreta::find()
                ->innerJoin("scholaris_grupo_alumno_clase", "scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id")
                ->where(["scholaris_grupo_alumno_clase.clase_id" => $clase])
                ->all();
        
        foreach ($modelLibreta as $libreta) {
            $this->calcula_valor_con_mejora($libreta);
            $this->calcula_nota_final($libreta);
        }
    }

    private function calcula_valor_con_mejora($modelLibreta) {

        $sentenciaNotas = new Notas();
        $digito = 2;


        if ($modelLibreta->mejora_q1 != null || $modelLibreta->mejora_q2 != null) {
            
            if($modelLibreta->mejora_q2 == null){
                $totalMejorado = ($modelLibreta->mejora_q1 + $modelLibreta->q2) / 2;
            }else{
                $totalMejorado = ($modelLibreta->mejora_q2 + $modelLibreta->q1) / 2;
            }
//            $mejoradoQ1 = $modelLibreta->q1 + $modelLibreta->mejora_q1;
//            $mejoradoQ1 = $sentenciaNotas->truncarNota($mejoradoQ1, $digito);
//
//            $mejoradoQ2 = $modelLibreta->q2 + $modelLibreta->mejora_q2;
//            $mejoradoQ2 = $sentenciaNotas->truncarNota($mejoradoQ2, $digito);
//
//            $totalMejorado = ($mejoradoQ1 + $mejoradoQ2) / 2;
            $totalMejorado = $sentenciaNotas->truncarNota($totalMejorado, $digito);
            $modelLibreta->final_con_mejora = $totalMejorado;
//
            $modelLibreta->save();
        }else{
            $modelLibreta->final_con_mejora = $modelLibreta->final_ano_normal;
            $modelLibreta->save();
        }



//        if($modelLibreta->id == 155044){
//            echo $mejoradoQ1.'<br>';
//            echo $mejoradoQ2.'<br>';
//            
//            echo $totalMejorado;
//            die();
//        }
    }

    private function calcula_nota_final($modelLibreta) {

        $modelMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

        if ($modelLibreta->final_con_mejora > $modelMinima->valor) {
            $modelLibreta->final_total = $modelLibreta->final_con_mejora;
            $modelLibreta->estado = 'APROBADO';
        } elseif ($modelLibreta->supletorio >= $modelMinima->valor ||
                $modelLibreta->remedial >= $modelMinima->valor ||
                $modelLibreta->gracia >= $modelMinima->valor) {

            $modelLibreta->final_total = $modelMinima->valor;
            $modelLibreta->estado = 'APROBADO';
        } else {
            $modelLibreta->final_total = $modelLibreta->final_con_mejora;
            $modelLibreta->estado = 'PIERDE';
        }
        $modelLibreta->save();
    }

    private function estado_final_clase($nota) {
        $modelParametros = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minima = $modelParametros->valor;

        if ($nota >= $minima) {
            $conclusion = 'APROBADO';
        } else {
            $conclusion = 'PIERDE EL AÑO';
        }

        return $conclusion;
    }

    public function toma_valor_parcial($bloqueId, $alumnoId, $claseId) {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelBloque = ScholarisBloqueActividad::findOne($bloqueId);
        $campo = $this->toma_campo_bloque($modelBloque->orden);

        $con = Yii::$app->db;
        $query = "select $campo as nota
                    from 	scholaris_clase_libreta c
                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                    where	g.estudiante_id = $alumnoId
                                    and g.clase_id = $claseId;";
        $res = $con->createCommand($query)->queryOne();

        return $res['nota'];
    }

    private function toma_campo_bloque($orden) {
        switch ($orden) {
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

    public function equivalencia_aprovechamiento($nota) {

        if ($nota) {
            $nota = $nota;
        } else {
            $nota = 0;
        }

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $con = \Yii::$app->db;
        $query = "select 	descripcion 
                        from	scholaris_tabla_escalas_homologacion 
                        where 	$nota between rango_minimo and rango_maximo 
                                        and corresponde_a = 'APROVECHAMIENTO'
                                        and scholaris_periodo = '$modelPeriodo->codigo';";
        $res = $con->createCommand($query)->queryOne();
        isset($res['descripcion']) ? $descripcion = $res['descripcion'] : $descripcion = 'no configurado';
        
        return $descripcion;
    }

}
