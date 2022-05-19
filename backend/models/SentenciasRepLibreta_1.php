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
class SentenciasRepLibreta extends \yii\db\ActiveRecord {

    public function calcula_promedios($alumno, $usuario) {
        $con = \Yii::$app->db;
        $query = "select 	trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1		
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(nota_final),2) as nota_final
from 	scholaris_rep_libreta l
where	l.alumno_id = $alumno
		and l.tipo_calificacion = 'Cuantitativo'
		and l.promedia = 1
		and usuario = '$usuario';";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }
    
    public function homologa_proyectos($nota, $periodo){
        $con = \Yii::$app->db;
        
        if($nota){
            $nota = $nota;
        }else{
            $nota = 0;
        }
        
        $query = "select 	abreviatura
from 	scholaris_tabla_escalas_homologacion 
where 	corresponde_a = 'PROYECTOS' 
		and scholaris_periodo = '$periodo'
		and $nota between rango_minimo and rango_maximo;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp;
    }
     
    
}
