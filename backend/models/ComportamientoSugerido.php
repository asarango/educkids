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
class ComportamientoSugerido extends \yii\db\ActiveRecord {

    public function devuelve_nota_sugerida($alumno, $parcial, $modelActividad) {

        //toma los valores desde el comportamiento/////////////////////////////////////////////////////////////////////////
        $codigos = $this->toma_cantidad_codigos($alumno, $parcial);

        $modelTotalCompo = ScholarisParametrosOpciones::find()->where(['codigo' => 'califmmaxima'])->one();
        
        $sugeridoCompor = 0;
        foreach ($codigos as $cod) {
            if ($cod['id']) {
                $nota = $this->calcula_puntos_frecuencia($cod['id'], $cod['total']);
                $sugeridoCompor = $sugeridoCompor + $nota;
            }
        }
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //toma los valores desde las faltas y atrasos//////////////////////////////////////////////////////
        $puntosFlatas = $this->calcula_puntos_flatas_atrasos($alumno, $parcial);
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        
//        echo $modelTotalCompo->valor.'<br>';
//        echo $sugeridoCompor.'<br>';
//        echo $puntosFlatas;
//        die();
        
        $sugerido = $modelTotalCompo->valor + $sugeridoCompor + $puntosFlatas;
//        echo $sugerido;
//        die();

        if ($sugerido > $modelTotalCompo->valor) {
            $sugerido = $modelTotalCompo->valor;
        } else {
            $sugerido = $sugerido;
        }
        
      

        $notaActividad = $this->toma_nota_actividad($alumno, $modelActividad);



        if (!$notaActividad) {
            $modelGrupo = ScholarisGrupoOrdenCalificacion::find()->where(['codigo_tipo_actividad' => $modelActividad->tipo_actividad_id])->one();
            $model = new ScholarisCalificaciones();
            $model->idalumno = $alumno;
            $model->idactividad = $modelActividad->id;
            $model->idtipoactividad = $modelActividad->tipo_actividad_id;
            $model->calificacion = $sugerido;
            $model->observacion = 'CALIFICACIÓN AUTOMÁTICA';
            $model->estado_proceso = 0;
            $model->grupo_numero = $modelGrupo->grupo_numero;
            $model->estado = 1;
            $model->save();
        } else if ($notaActividad->observacion) {
            $sugerido = $sugerido;
        } else {
            $modelNo = ScholarisCalificaciones::findOne($notaActividad->id);
            $modelNo->calificacion = $sugerido;
            $modelNo->observacion = 'MODIFICADO AUTOMÁTICAMENTE'; //Se modifica automaticamente cuando no jay observacion
            $modelNo->save();
        }


        return $sugerido;
    }

    /**
     * METODO PARA TOMAR EL VALOR DESDE EL COMPORTAMIENTO
     * @param type $detalleId
     * @param type $total
     * @return type
     */
    private function calcula_puntos_frecuencia($detalleId, $total) {

        ///SE TOMA EN CUENTA PARA SOLO COMPORTAMIENTOS 
        //buca limite de la frecuencia
//        echo $detalleId;
//        die();

        $modelDetalle = ScholarisAsistenciaComportamientoDetalle::findOne($detalleId);

        //Toma las veces que se va a repetir  el cuento de los puntos de frecuencia.
        $repeticiones = floor($total / $modelDetalle->limite);
        $resto = $total % $modelDetalle->limite;


        if ($repeticiones > 0) {
            $modelDivision = $this->toma_total_puntos_frecuencia($detalleId, $modelDetalle->limite);
            $modelResto = $this->toma_total_puntos_frecuencia($detalleId, $resto);

            $puntos = ($modelDivision * $repeticiones) + $modelResto;
        } else {
            $modelFrecu = $this->toma_total_puntos_frecuencia($detalleId, $total);
            $puntos = $modelFrecu;
        }

        return $puntos;
    }

    private function calcula_puntos_flatas_atrasos($alumno, $parcial) {
        $limitaAtraso = ScholarisAsistenciaComportamientoDetalle::find()->where(['codigo' => 'atras'])->one();
        $limitaFj = ScholarisAsistenciaComportamientoDetalle::find()->where(['codigo' => 'justi'])->one();
        $limitaFi = ScholarisAsistenciaComportamientoDetalle::find()->where(['codigo' => 'injus'])->one();
        

        $tomaTotal = $this->toma_cantidad_atrasos_faltas($alumno, $parcial);   
        
//        print_r($tomaTotal);
//        die();
        
        $totalAtrasos = $this->toma_total_puntos_frecuencia($limitaAtraso->id, $tomaTotal['atraso']);
        $totalFj = $this->toma_total_puntos_frecuencia($limitaFj->id, $tomaTotal['fj']);
        $totalFi = $this->toma_total_puntos_frecuencia($limitaFi->id, $tomaTotal['fi']);
         

        $total = $totalAtrasos + $totalFj + $totalFi;
        
        
        
        
//        echo $totalAtrasos.'--<br>';
//        echo $totalFj.'--<br>';
//        echo $totalFi.'--<br>';
//        echo $total;
//        die();

        return $total;
    }

    private function toma_total_puntos_frecuencia($detalleId, $limite) {
       
        $con = \Yii::$app->db;
        $query = "select 	sum(puntos) as puntos, accion 
                    from 	scholaris_asistencia_comportamiento_fecuencia
                    where	detalle_id = $detalleId
                                and fecuencia <= $limite group by accion;";
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();
        if ($res) {
            if ($res['accion'] == '-1') {
                return -$res['puntos'];
            } else {
                return $res['puntos'];
            }
        } else {
            return 0;
        }
    }

    private function toma_cantidad_codigos($alumno, $parcial) {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $modelBloque = ScholarisBloqueActividad::findOne($parcial);

        $con = Yii::$app->db;
        $query = "select 	d.id, count(a.id) as total
from	scholaris_asistencia_alumnos_novedades n
		inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
		left join scholaris_asistencia_justificacion_alumno j on j.novedad_id = n.id
		left join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id					
where	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$modelPeriodo->codigo'		
		and a.fecha between '$modelBloque->bloque_inicia' and '$modelBloque->bloque_finaliza'
		and j.opcion_justificacion_id is null
group by d.id
union all
select 	j.opcion_justificacion_id, count(j.id) as total
from	scholaris_asistencia_alumnos_novedades n
		inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
		left join scholaris_asistencia_justificacion_alumno j on j.novedad_id = n.id
		left join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id					
where	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$modelPeriodo->codigo'		
		and a.fecha between '$modelBloque->bloque_inicia' and '$modelBloque->bloque_finaliza'
group by j.opcion_justificacion_id; ";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    private function toma_cantidad_atrasos_faltas($alumno, $parcial) {

        $con = Yii::$app->db;
        $query = "select
                        (
                                select count(d.atraso)
                                from scholaris_toma_asistecia_detalle d 
                                inner join scholaris_toma_asistecia a on a.id = d.toma_id 
                                where d.alumno_id = $alumno and a.bloque_id = $parcial
                                                and d.atraso = true
                                                and d.atraso_justificado = false
                        )as atraso,
                        (
                                select count(d.falta_justificada )
                                from scholaris_toma_asistecia_detalle d 
                                inner join scholaris_toma_asistecia a on a.id = d.toma_id 
                                where d.alumno_id = $alumno and a.bloque_id = $parcial
                                                and d.falta_justificada = true
                        ) as fj,
                        (
                                select count(d.falta )
                                from scholaris_toma_asistecia_detalle d 
                                inner join scholaris_toma_asistecia a on a.id = d.toma_id 
                                where d.alumno_id = $alumno and a.bloque_id = $parcial
                                                and d.falta = true
                        )as fi";
        
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function toma_nota_actividad($alumno, $modelActividad) {

        $modelNota = ScholarisCalificaciones::find()
                ->where([
                    'idactividad' => $modelActividad->id,
                    'idalumno' => $alumno
                ])
                ->one();


        if ($modelNota) {
            return $modelNota;
        } else {
            return '';
        }


    }

}
