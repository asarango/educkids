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
class MecProcesaMaterias extends \yii\db\ActiveRecord {

    public function get_areas_mec_normales($mallaId) {

        $con = Yii::$app->db;
        $query = "select ma.id 
		,a.nombre
		,(select count(mm.id) 
			from scholaris_mec_v2_malla_materia mm 
			where mm.area_id = ma.id) as total_materias
                from 	scholaris_mec_v2_malla_area ma 
                        inner join scholaris_mec_v2_asignatura a on a.id = ma.asignatura_id
                where 	ma.malla_id = $mallaId 
                                and ma.tipo = 'NORMAL' 
                order by orden; ";

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_materias_mec_normales($mallaId) {
        $con = Yii::$app->db;
        $query = "select 	mm.id 
                                ,a.nombre
                from	scholaris_mec_v2_malla_area ma
                                inner join scholaris_mec_v2_malla_materia mm on mm.area_id  = ma.id 
                                inner join scholaris_mec_v2_asignatura a on a.id = mm.asignatura_id 
                where	ma.malla_id = $mallaId
                                and mm.tipo = 'normal'
                order by ma.orden asc, mm.orden asc; ";
//        $query = "select 	mm.id 
//                                ,a.nombre
//                                ,d.materia_id 
//                                ,d.tipo_homologacion 
//                                ,d.codigo_materia_source 
//                from	scholaris_mec_v2_malla_area ma
//                                inner join scholaris_mec_v2_malla_materia mm on mm.area_id  = ma.id 
//                                inner join scholaris_mec_v2_asignatura a on a.id = mm.asignatura_id 
//                                inner join scholaris_mec_v2_malla_disribucion d on d.materia_id = mm.id 
//                where	ma.malla_id = $mallaId
//                                and mm.tipo = 'normal'
//                order by ma.orden asc, mm.orden asc; ";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function get_materias_x_area_mec_normales($areaMecId) {
        $con = Yii::$app->db;
        $query = "select 	mm.id 
                                ,a.nombre
                from	scholaris_mec_v2_malla_area ma
                                inner join scholaris_mec_v2_malla_materia mm on mm.area_id  = ma.id 
                                inner join scholaris_mec_v2_asignatura a on a.id = mm.asignatura_id 
                where	ma.id = $areaMecId
                                and mm.tipo = 'normal'
                order by ma.orden asc, mm.orden asc; ";

//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function get_nota($materiaId, $alumnoId, $tipoCalificacion, $paraleloId, $usuario, $periodoCodigo) {

        if ($tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new AlumnoNotasNormales();
        } elseif ($tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
        } elseif ($tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }

        $procesosMec = new MecProcesaMaterias();

        $source = $procesosMec->devuelve_source($alumnoId, $materiaId, $periodoCodigo);
        
        $sources = ScholarisMecV2MallaDisribucion::find()->where(['materia_id' => $materiaId])->all();




        if ($source['tipo_homolagacion'] == 'AREA') {
            $notas = $sentenciasNotasAlumnos->get_nota_area($source['source_id'], $alumnoId, $paraleloId, $usuario);

            if ($notas) {
                if ($notas['final_con_mejora'] == 0) {
                    $notas['final_con_mejora'] = $notas['final_ano_normal'];
                }
            }
        } else {                 
            $notas = $sentenciasNotasAlumnos->get_nota_materia($source['grupo_id']);
        }

//        if (isset($notas['final_total'])) {
//            $notasF = $notas['final_total'];
//        } else {
//            $notasF = 0;
//        }


        if (isset($notas['final_total'])) {
            $notasF = $notas;
        } else {
            $notasF = null;
        }

        return $notasF;






//        if ($tipoCalificacion == 3) {  //para tomar notas de interdisciplinar
//            $nota = $this->get_notas_interdisciplinar($paraleloId, $alumnoId);
//        } else {
//            //Para tomar notas del resto de tipos de calificacion
//            $sentenciaNotas = new ProcesaNotas();
//            $sources = ScholarisMecV2MallaDisribucion::find()->where(['materia_id' => $materiaId])->all();
//
//            $totalMaterias = 0;
//            $totalAreas = 0;
//            foreach ($sources as $source) {
//                if ($source->tipo_homologacion == 'AREA') {
//                    $totalAreas++;
//                } else {
//                    $totalMaterias++;
//                }
//            }
//
//
//
//
//            if (($totalAreas > 0) && ($totalMaterias > 0)) {
//                echo 'GRAVE ERROR NO PUEDE MEZCLAR AREAS Y MATERIAS';
//                die();
//            } elseif ($totalAreas > 1) {
//                echo 'GRAVE ERROR NO PUEDE CONFIGURAR MAS DE DOS AREAS';
//                die();
//            } elseif ($totalAreas == 1) {
//
//                $tipoAsignatura = $source->tipo_homologacion;
//                $sourceId = $source->codigo_materia_source;
//
//                if ($tipoAsignatura == 'AREA') {
//
//                    $nota = $sentenciaNotas->busca_nota_area($alumnoId, $sourceId, $tipoCalificacion, $paraleloId, $usuario);
//                }
//            } else {
//                $nota = $this->busca_nota_materia_normal($tipoCalificacion, $materiaId, $periodoCodigo, $alumnoId);
//            }
//        }
//
//        return $nota;
    }

    private function busca_grupo_id($alumnoId, $periodoCodigo, $materiaMallaMecId) {
        $asignaturaMallaInstitucion = ScholarisMecV2MallaDisribucion::find()->where([
                    'materia_id' => $materiaMallaMecId
                ])->one();

        $con = Yii::$app->db;
        $query = "select 	g.id as grupo_id
                    from 	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id 
                    where	g.estudiante_id = $alumnoId
                                    and c.idmateria = $asignaturaMallaInstitucion->codigo_materia_source
                                    and c.periodo_scholaris = '$periodoCodigo';";
        $res = $con->createCommand($query)->queryOne();

        if (isset($res['grupo_id'])) {
            $grupoId = $res['grupo_id'];
            return $grupoId;
        } else {
            $modelStudent = OpStudent::findOne($alumnoId);
            $modelMateria = ScholarisMateria::findOne($asignaturaMallaInstitucion->codigo_materia_source);

            echo 'No existe un grupo para el estudiante: ' . $modelStudent->first_name . ' ' . $modelStudent->last_name . '(' . $alumnoId . ')';
            echo '. <br>Para la asignatura: ' . $modelMateria->name . '(' . $modelMateria->id . ')';
            echo '. <br>Usted debe realizar la asiganción correspondiente, o consulte a su administrador';

            die();
        }
    }

    private function busca_nota_materia_normal($tipoCalificacion, $materiaId, $periodoCodigo, $alumnoId) {
        $con = Yii::$app->db;

        if ($tipoCalificacion == 0) {
            $query = "select 	l.id, l.grupo_id, l.p1, l.p2, l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1, l.p4, l.p5, l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal, l.mejora_q1, l.mejora_q2, l.final_con_mejora, l.supletorio, l.remedial, l.gracia, l.final_total, l.estado 
                        from 	scholaris_grupo_alumno_clase g
                                        inner join scholaris_clase c on c.id = g.clase_id 
                                        inner join scholaris_mec_v2_malla_disribucion md on md.codigo_materia_source = c.idmateria 
                                        inner join scholaris_clase_libreta l on l.grupo_id = g.id 
                        where	g.estudiante_id = $alumnoId
                                        and c.periodo_scholaris = '$periodoCodigo'
                                        and md.materia_id = $materiaId
                                        and l.final_total <> 0
                        order by l.final_total desc
                        limit 1;";

            $notas = $con->createCommand($query)->queryOne();
        } else if ($tipoCalificacion == 2) { //para calificacion disciplinar            
            //busca grupo_id
            $grupoId = $this->busca_grupo_id($alumnoId, $periodoCodigo, $materiaId);

            //consulta notas disciplinares
            $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
            $notas = $sentenciasNotasAlumnos->get_nota_materia($grupoId);
        } elseif ($tipoCalificacion == 3) {
            echo 'No tiene configurado un tipo de calificación para este periodo en Interdisciplinar!!!';
            echo ' Tipo de alerta busca_nota_materia_normal - model MecProcesaMaterias tipo de claificación 3';
            die();
        }

        return $notas;
    }

    public function get_proyectos($alumnoId, $paralelo, $quimestre) {
        $proyectos = new ComportamientoProyectos($alumnoId, $paralelo);

        return $proyectos->arrayNotasProy[0];
    }

    public function get_comportamiento($alumnoId, $paralelo, $quimestre) {



        if ($quimestre == 'mejora_q1') {
            $quimestre = 'q1';
        } elseif ($quimestre == 'mejora_q2') {
            $quimestre = 'q2';
        }



        $proyectos = new ComportamientoProyectos($alumnoId, $paralelo);

        return $proyectos->arrayNotasComp[0][$quimestre];
    }

    public function get_proyectos_mec($alumnoId, $mallaId, $campo, $paralelo) {

        $sentencias = new Notas();

        $tipoSource = $this->busca_source($mallaId);

        if ($tipoSource['tipo_homologacion'] == 'MATERIA') {

            $nota = $this->toma_nota_proyectos_materias($tipoSource['codigo_materia_source'], $alumnoId, $campo);

            if (isset($nota)) {
                $nota = $nota;
            } else {
                $nota = 0;
            }

            $proyectosTrans = $sentencias->homologa_cualitativas($nota);
            $proyectos = array($campo => array('abreviatura' => $proyectosTrans));
        } else {

            $proyectos = $this->get_proyectos($alumnoId, $paralelo, $campo);
        }

        //isset($proyectos) ? $proy = $proyectos : $proy = '-';

        return $proyectos;
    }

    private function busca_source($mallaId) {
        $con = Yii::$app->db;
        $query = "select 	md.tipo_homologacion 
		,md.codigo_materia_source 
from 	scholaris_mec_v2_malla_area ma
		inner join scholaris_mec_v2_malla_materia mm on mm.area_id = ma.id 
		inner join scholaris_mec_v2_malla_disribucion md on md.materia_id = mm.id 
where	ma.malla_id = $mallaId
		and mm.tipo = 'proyectos';";
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    private function toma_nota_proyectos_materias($sourceId, $alumnoId, $campo) {
        $con = Yii::$app->db;
        $query = "select $campo	
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id 
where	g.estudiante_id  = $alumnoId
		and c.idmateria = $sourceId;";

        $res = $con->createCommand($query)->queryOne();

        isset($res[$campo]) ? $nota = $res[$campo] : $nota = 0;

        return $nota;
    }

    private function toma_nota_proyectos_areas($sourceId, $alumnoId, $campo) {


        $con = Yii::$app->db;
        $query = "select $campo	
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id 
where	g.estudiante_id  = $alumnoId
		and c.idmateria = $sourceId;";

        $res = $con->createCommand($query)->queryOne();

        return $res[$campo];
    }

    /*     * ******* para calificacion interdisciplinar
     * 
     */

    private function get_notas_interdisciplinar($paraleloId, $alumnoId) {

        $interdisciplinar = new ProcesaNotasInterdisciplinar($paraleloId, $alumnoId);

        return $interdisciplinar->arrayNotas;
    }

    public function devuelve_source($alumnoId, $mallaMateriaId, $periodoCodigo) {
        //busca numero de malla_id
        $modelMallaMateria = ScholarisMecV2MallaMateria::findOne($mallaMateriaId);
        $mallaId = $modelMallaMateria->area->malla_id;


        //Busca las distribuciones asignadas
        $modelDistribucion = ScholarisMecV2MallaDisribucion::find()->where(['materia_id' => $mallaMateriaId])->all();

        //VALIDACION DE QUE NO EXISTA MAS DE UNA AREA Y AREAS MEZCLADAS CON MATERIAS, ESTO NO SE PUEDE DAR.
        $totalAreas = 0;
        $totalMaterias = 0;
        foreach ($modelDistribucion as $distri) {
            if ($distri->tipo_homologacion == 'AREA') {
                $totalAreas++;
            } else {
                $totalMaterias++;
            }
        }

        if ($totalAreas > 1) {
            echo 'No se puede configurar mas de 1 área en la asigantura MEC de ' . $modelMallaMateria->asignatura->nombre;
            echo '<br>';
            echo Html::a('Arreglar el problema', ['/scholaris-mec-v2-malla-area/index1', 'id' => $mallaId]);
            die();
        } elseif ($totalAreas > 0 && $totalMaterias > 0) {
            echo 'No se puede combinar áreas y materias en la asignatura MEC de ' . $modelMallaMateria->asignatura->nombre;
            echo '<br>';
            echo Html::a('Arreglar el problema', ['/scholaris-mec-v2-malla-area/index1', 'id' => $mallaId]);
            die();
        } elseif ($totalAreas == 1) { //devuelve si es correcta la configuracion del area
            $data = array(
                'tipo_homolagacion' => 'AREA',
                'source_id' => $modelDistribucion[0]->codigo_materia_source
            );
        } elseif ($totalAreas == 0 && $totalMaterias > 0) { //devuleve si es correcta la configuracion de las materias
            $materias = $this->consulta_grupo_id($alumnoId, $periodoCodigo, $mallaMateriaId);
            
            if ($materias == false) {
                echo 'Existe problemas con la distribución en la asignatura MEC de ' . $modelMallaMateria->asignatura->nombre;
                echo 'o el alumno de código '.$alumnoId.' no existe';
                echo '<br>';
                echo Html::a('Arreglar el problema', ['/scholaris-mec-v2-malla-area/index1', 'id' => $mallaId]);
                die();
            } else {
                $data = array(
                    'tipo_homolagacion' => 'MATERIA',
                    'grupo_id' => $materias
                );
            }
        } else {
            echo 'Debe configurar un área o una materia en la asignatura MEC de ' . $modelMallaMateria->asignatura->nombre;
            echo '<br>';
            echo Html::a('Arreglar el problema', ['/scholaris-mec-v2-malla-area/index1', 'id' => $mallaId]);
            die();
        }

        /// fin de validacion de que no exista mas de una areas y materia
              

        return $data;
    }

    private function consulta_grupo_id($alumnoId, $periodoCodigo, $mallaMateriaId) {
        $con = \Yii::$app->db;
        $query = "select 	g.id as grupo_id 
                        from	scholaris_grupo_alumno_clase g
                                        inner join scholaris_clase c on c.id = g.clase_id 
                        where	g.estudiante_id = $alumnoId
                                        and c.periodo_scholaris = '$periodoCodigo'
                                        and c.idmateria in (select codigo_materia_source 
                                                                                from 	scholaris_mec_v2_malla_disribucion d
                                                                                where 	d.materia_id = $mallaMateriaId)
                        limit 1;";
        
        $res = $con->createCommand($query)->queryOne();

        if (isset($res)) {
            $respuesta = $res['grupo_id'];
        } else {
            $respuesta = false;
        }
        
        
        return $respuesta;
    }

}
