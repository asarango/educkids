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
class SentenciasFinAno extends \yii\db\ActiveRecord {

    public function total_alumnos_no_cerrados($clase) {
        $con = \Yii::$app->db;
        $query = "select count(l.id) as total
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                    where	c.id = $clase
                                    and l.estado is null;";
        $res = $con->createCommand($query)->queryOne();

        return $res['total'];
    }

    public function nota_final_clase($alumno, $mallaMateria) {
        $con = \Yii::$app->db;
        $query = "select final_total as nota
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia m on m.id = c.malla_materia
where	g.estudiante_id = $alumno
		and m.id = $mallaMateria;";

        $res = $con->createCommand($query)->queryOne();
        
        if(isset($res['nota'])){
            $nota = $res['nota'];
        }else{
            $nota = 0;
        }

        return $nota;
    }

    public function nota_final_por_area($area, $alumno) {
        $con = \Yii::$app->db;
        $query = "select sum(trunc((l.final_total*m.total_porcentaje /a.total_porcentaje),2)) as nota
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                                    inner join scholaris_malla_area a on a.id = m.malla_area_id 
                    where	g.estudiante_id = $alumno
                                    and m.malla_area_id = $area;";
        $res = $con->createCommand($query)->queryOne();

        return $res['nota'];
    }

    public function nota_final_por_materia($area, $alumno) {
        $con = \Yii::$app->db;
        $query = "select l.final_total as nota
                    from	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                    where	g.estudiante_id = $alumno
                                    and m.malla_area_id = $area
                                    and m.promedia = true;";
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    /**
     * 
     * @param type $mallaArea
     * @param type $alumno
     * @param type $paralelo
     * @return type
     * METODO QUE ENTREGA LA NOTA FINAL DE LA LIBRETA DEL ALUMNO
     * Este promedio es el final despues de haber realizado los examenes
     * extras como supletorios, remediales, gracia y mejoras quimestrales
     */
    public function nota_final_alumno($mallaArea, $alumno, $paralelo) {

        $sentencias = new Notas();

        $cont = 0;
        $suma = 0;
        foreach ($mallaArea as $area) {
            if ($area->promedia == 1) {
                $nota = $this->nota_final_por_area($area->id, $alumno);

//                echo $area->id . ' ---- ' . $nota;

                $cont++;
                $suma = $suma + $nota;
            } else {
                $modelNota = $this->nota_final_por_materia($area->id, $alumno);

                foreach ($modelNota as $nota) {
                    $notam = $nota['nota'];
//                    echo $area->id.' ---- '.$notam.'<br>';
                    $suma = $suma + $notam;
                    $cont++;
                }
            }
        }

        if($cont==0){
           $cont=1; 
        }
        
        $promedio = $suma / $cont;
        $promedio = $sentencias->truncarNota($promedio, 2);
//        die();
        return $promedio;
    }

    /**
     * 
     * @param type $nota
     * @return string
     * MEDOTO QUE DEVUELVE EL ESTADO FINAL
     */
    public function estado_final($nota, $curso, $mallaArea, $alumno) {


        $modelMinimo = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();

        $modelConfCurso = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $curso])->one();
        $rinde = $modelConfCurso->rinde_supletorio;

        if ($rinde == 1) {

            $cant = 0;
            foreach ($mallaArea as $area) {
                $modelMarteria = \backend\models\ScholarisMallaMateria::find()->where(['malla_area_id' => $area->id])->all();
                foreach ($modelMarteria as $materia) {
                    $notamat = $this->nota_final_clase($alumno, $materia->id);

                    if($notamat < $modelMinimo->valor){
                     $cant = $cant + 1;   
                    }                                        
                }

                $estado = $cant > 0 ? false : true; 
                
            }
        } else {

            $minima = $modelMinimo->valor;

            if ($nota >= $minima) {
                $estado = true;
            } else {
                $estado = false;
            }
        }



        return $estado;
    }
    
    
    
    public function tomaNotasSentadas($alumnoInscription){
        $model = ScholarisPromediosAnuales::find()
                ->where(['alumno_inscription_id' => $alumnoInscription])
                ->one();
        
        
        return $model;
    }
    
    

}
