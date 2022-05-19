<?php

namespace backend\models;
use yii\helpers\Html;

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
class SentenciasRecalcularUltima extends \yii\db\ActiveRecord {

    public function por_paralelo($paralelo) {
        
        if(!isset(Yii::$app->user->identity->periodo_id)){
            echo 'Su sesión expiró!!! ';
            echo Html::a('Iniciar Sesión', ['/site/index']);
            die();
        }
        
        $periodoId = Yii::$app->user->identity->periodo_id;

        $tomaAlumnos = $this->toma_alumnos_paralelo($paralelo);
        foreach ($tomaAlumnos as $alumno) {
            $this->valida_grupo_libreta($alumno['clase_id'], $alumno['alumno_id'], $alumno['grupo_id']);
        }
        
        
        ///COMPROBACION DE COVID /////
        $modelCovid = ScholarisQuimestreTipoCalificacion::find()->where([
            'codigo' => 'covid19',
            'periodo_scholaris_id' => $periodoId
        ])->all();
        
        if(count($modelCovid) >0){
            $modelClases = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->all();
            
            foreach ($modelClases as $clase){
                $this->genera_recalculo_covid19($clase->id);
            }
            
        }
        
        
        
        ///////// FIN DE REVISION COVID 19
        
    }

    public function toma_alumnos_paralelo($paralelo) {
        $con = Yii::$app->db;
        $query = "select 	i.student_id as alumno_id
                                    ,c.id as clase_id
                                    ,g.id as grupo_id
                    from	op_student_inscription i
                                    inner join scholaris_clase c on c.paralelo_id = i.parallel_id
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                                                            and g.clase_id = c.id
                    where	i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                    order by i.student_id,c.id;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function valida_grupo_libreta($clase, $alumno, $grupo) {
        $modelGrupoLibreta = ScholarisClaseLibreta::find()
                ->where(['grupo_id' => $grupo])
                ->one();

        if (!$modelGrupoLibreta) {
            $model = new ScholarisClaseLibreta();
            $model->grupo_id = $grupo;
            $model->save();

            $modelGrupoLibreta = ScholarisClaseLibreta::find()
                    ->where(['grupo_id' => $grupo])
                    ->one();

            $this->sentar_notas_parciales($alumno, $clase, $grupo);
        } else {
            $this->sentar_notas_parciales($alumno, $clase, $grupo);
        }
    }

    public function sentar_notas_parciales($alumno, $clase, $grupo) {

        $model = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();

        $model->p1 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 1);
        $model->p2 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 2);
        $model->p3 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 3);
        $model->ex1 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 4);

        $model->p4 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 5);
        $model->p5 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 6);
        $model->p6 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 7);
        $model->ex2 = $this->consulta_nota_parciales($alumno, $clase, $grupo, 8);



        $model->save();
        $this->calcula_promedio_grupo($grupo);
    }

    public function consulta_nota_parciales($alumno, $clase, $grupo, $orden) {
        $con = \Yii::$app->db;
        $query = "select trunc(avg(nota),2) as nota
                    from
                    (
                    select 	trunc(avg(c.calificacion),2) as nota, grupo_numero
                    from 	scholaris_calificaciones c 
                                    inner join scholaris_actividad a on a.id = c.idactividad
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
                    where	a.paralelo_id = $clase
                                    and c.idalumno = $alumno
                                    and c.grupo_numero not in (
                                                                select 	orden_calificacion 
                                                                from 	scholaris_refuerzo ref
									inner join scholaris_bloque_actividad blo on blo.id = ref.bloque_id
                                                                where	ref.grupo_id = $grupo
                                                                        and ref.nota_final > ref.promedio_normal
                                                                        and blo.orden = $orden
                                                                )
                                    and b.orden = $orden
                    group by c.grupo_numero
                    union
                    select 	nota_final, orden_calificacion
                    from 	scholaris_refuerzo r
                                    inner join scholaris_bloque_actividad b on b.id = r.bloque_id
                    where	r.grupo_id = $grupo
                                    and r.nota_final > promedio_normal
                                    and b.orden = $orden
                    ) as nota;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();
        return $res['nota'];
    }

    public function calcula_promedio_grupo($grupo) {
        $sentencia = new Notas();
        $digito = 2;

        $model = ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();

        $model->pr1 = ($model->p1 + $model->p2 + $model->p3) / 3;
        $model->pr1 = $sentencia->truncarNota($model->pr1, $digito);

        $model->pr180 = ($model->pr1 * 80) / 100;
        $model->pr180 = $sentencia->truncarNota($model->pr180, $digito);

        $model->ex120 = ($model->ex1 * 20) / 100;
        $model->ex120 = $sentencia->truncarNota($model->ex120, $digito);

        $model->q1 = $model->pr180 + $model->ex120;


        $model->pr2 = ($model->p4 + $model->p5 + $model->p6) / 3;
        $model->pr2 = $sentencia->truncarNota($model->pr2, $digito);

        $model->pr280 = ($model->pr2 * 80) / 100;
        $model->pr280 = $sentencia->truncarNota($model->pr280, $digito);

        $model->ex220 = ($model->ex2 * 20) / 100;
        $model->ex220 = $sentencia->truncarNota($model->ex220, $digito);

        $model->q2 = $model->pr280 + $model->ex220;

        $model->final_ano_normal = ($model->q1 + $model->q2) / 2;
        $model->final_ano_normal = $sentencia->truncarNota($model->final_ano_normal, $digito);
        
        $model->save();
    }

    /**
     * PARA SACAR CALCULO SOLO DE CLASES
     */
    public function genera_recalculo_por_clase($clase) {

        $modelAlumnos = ScholarisGrupoAlumnoClase::find()->where(['clase_id' => $clase])->all();

        foreach ($modelAlumnos as $alumno) {
            $this->valida_grupo_libreta($alumno->clase_id, $alumno->estudiante_id, $alumno->id);
        }
    }

    /**
     * PARA SACAR CALCULO SOLO DE CLASES POR ALUMNO
     */
    public function genera_calculo_alumno($alumno) {

//        echo $alumno;
//        die();

        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPerido = \backend\models\ScholarisPeriodo::findOne($periodo);

        $modelGrupos = $this->toma_grupos($alumno, $modelPerido->codigo);

        foreach ($modelGrupos as $grupo) {
            $this->valida_grupo_libreta($grupo['clase_id'], $grupo['estudiante_id'], $grupo['grupo_id']);
        }
    }

    public function toma_grupos($alumno, $periodoCodigo) {
        $con = Yii::$app->db;
        $query = "select 	g.id as grupo_id
                                    ,c.id as clase_id
                                    ,g.estudiante_id
                    from	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id
                    where	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$periodoCodigo';";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    /**
     * PARA CALCUULOS DE COVID 19
     * @param type $claseId
     */

    public function genera_recalculo_covid19($claseId) {
        $sentencias = new Notas();
        
        
        $periodoId = \Yii::$app->user->identity->periodo_id;

        $modelAlumnos = $this->get_alumnos_clase($claseId, $periodoId);

        $modelCovid19 = \backend\models\ScholarisQuimestreTipoCalificacion::find() //buca si existe activado la calificacion covid 19en los quimestre
                ->where([
                    'codigo' => 'covid19',
                    'periodo_scholaris_id' => $periodoId
                ])
                ->all();

        foreach ($modelCovid19 as $cal) {
            if ($cal->quimestre->orden == 1) {

                foreach ($modelAlumnos as $al) {
                    $modelLibreta = ScholarisClaseLibreta::find()->where(['grupo_id' => $al['grupo_id']])->one();
                    $modelCalificacion = ScholarisCalificacionCovid19::find(['inscription_id' => $al['id'], 'tipo_quimestre_id' => $cal->quimestre_id])->one();

                    $modelLibreta->q1 = $modelCalificacion->total;
                    $modelLibreta->save();
                }
            } elseif ($cal->quimestre->orden == 2) {
                foreach ($modelAlumnos as $al) {
                    $modelLibreta = ScholarisClaseLibreta::find()->where(['grupo_id' => $al['grupo_id']])->one();
                    $modelCalificacionc = $this->recupera_calificacion_covid($al['id'], $cal->quimestre_id);
                  
                    $modelLibreta->q2 = $modelCalificacionc;
                    $modelLibreta->save();
                }
            }
        }
        
        foreach ($modelAlumnos as $al) {
            $modelCa = ScholarisClaseLibreta::find()->where(['grupo_id' => $al['grupo_id']])->one();
            $final = ($modelCa->q1+$modelCa->q2)/2;
            $final = $sentencias->truncarNota($final, 2);
            $modelCa->final_ano_normal = $final;
            $modelCa->save();
        }
       
        
    }
    
    private function recupera_calificacion_covid($incripcionId, $tipo){
        $con = \Yii::$app->db;
        $query = "select total from scholaris_calificacion_covid19 scc where inscription_id = $incripcionId and tipo_quimestre_id = $tipo;";
        $res = $con->createCommand($query)->queryOne();
        
        if(isset($res['total'])){
            return $res['total'];
        }else{
            return 0;
        }
        
        
    }
    

    private function get_alumnos_clase($claseId, $periodo) {
        $con = \Yii::$app->db;
        $query = "select 	l.grupo_id 
		,i.id 
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join op_student_inscription i on i.student_id = g.estudiante_id
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
		inner join scholaris_periodo p on p.id = sop.scholaris_id 
where 	g.clase_id = $claseId
		and p.id = $periodo;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    ///////////////////////////////////////FINALIZA CALCULO DE COVID 19//////////////////////////////////////////

}
