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
class NotasAlumno extends \yii\db\ActiveRecord {

    private $alumno;
    private $paralelo;
    private $periodoCodigo;
    private $periodoId;
    private $bloques;
    public $arregloLibreta = array();
    public $arregloNotasFinales = array();
    public $arregloProyectos = array();
    public $arregloComportamiento = array();

    public function __construct($alumno, $paralelo) {
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        $this->alumno = $alumno;
        $this->paralelo = $paralelo;
        $this->get_bloques();
        $this->procesa_libreta();
        $this->procesa_notas_finales();
//        $this->
    }
    

    private function procesa_notas_finales() {
        
        $sentencias = new Notas();
        $digito = 2;

        $cont = 0;
        $sumaP1 = 0;
        $sumaP2 = 0;
        $sumaP3 = 0;
        $sumaPr1 = 0;
        $sumaPr180 = 0;
        $sumaE1 = 0;
        $sumaE120 = 0;
        $sumaQ1 = 0;
        $sumaP4 = 0;
        $sumaP5 = 0;
        $sumaP6 = 0;
        $sumaPr2 = 0;
        $sumaPr280 = 0;
        $sumaE2 = 0;
        $sumaE220 = 0;
        $sumaQ2 = 0;
        $sumaFinalAnoNormal = 0;

        foreach ($this->arregloLibreta as $libreta) {
            if ($libreta['promedia'] == true) {
                
            } else {

                foreach ($libreta['materias'] as $lib) {
                    if ($lib['promedia'] == true) {
                        $cont++;                        
                        $sumaP1 = $sumaP1 + $lib['notas']['p1'];
                        $sumaP2 = $sumaP2 + $lib['notas']['p2'];
                        $sumaP3 = $sumaP3 + $lib['notas']['p3'];
                        $sumaPr1 = $sumaPr1 + $lib['notas']['pr1'];
                        $sumaPr180 = $sumaPr180 + $lib['notas']['pr180'];
                        $sumaE1 = $sumaE1 + $lib['notas']['ex1'];
                        $sumaE120 = $sumaE120 + $lib['notas']['ex120'];
                        $sumaQ1 = $sumaQ1 + $lib['notas']['q1'];
                        
                        $sumaP4 = $sumaP4 + $lib['notas']['p4'];
                        $sumaP5 = $sumaP5 + $lib['notas']['p5'];
                        $sumaP6 = $sumaP6 + $lib['notas']['p6'];
                        $sumaPr2 = $sumaPr2 + $lib['notas']['pr2'];
                        $sumaPr280 = $sumaPr280 + $lib['notas']['pr280'];
                        $sumaE2 = $sumaE2 + $lib['notas']['ex2'];
                        $sumaE220 = $sumaE220 + $lib['notas']['ex220'];
                        $sumaQ2 = $sumaQ2 + $lib['notas']['q2'];
                        $sumaFinalAnoNormal = $sumaFinalAnoNormal + $lib['notas']['final_ano_normal'];
                    }
                }
            }
        }
        $p1 = $sentencias->truncarNota($sumaP1 / $cont, $digito);
        $p2 = $sentencias->truncarNota($sumaP2 / $cont,$digito);
        $p3 = $sentencias->truncarNota($sumaP3 / $cont, $digito);
        $pr1 = $sentencias->truncarNota($sumaPr1 / $cont,$digito);
        $pr180 = $sentencias->truncarNota($sumaPr180 / $cont, $digito);
        $ex1 = $sentencias->truncarNota($sumaE1 / $cont, $digito);
        $ex120 = $sentencias->truncarNota($sumaE120 / $cont, $digito);
        $q1 = $sentencias->truncarNota($sumaQ1 / $cont, $digito);
        
        $p4 = $sentencias->truncarNota($sumaP4 / $cont, $digito);
        $p5 = $sentencias->truncarNota($sumaP5 / $cont, $digito);
        $p6 = $sentencias->truncarNota($sumaP6 / $cont, $digito);
        $pr2 = $sentencias->truncarNota($sumaPr2 / $cont, $digito);
        $pr280 = $sentencias->truncarNota($sumaPr280 / $cont, $digito);
        $ex2 = $sentencias->truncarNota($sumaE2 / $cont, $digito);
        $ex220 = $sentencias->truncarNota($sumaE220 / $cont, $digito);
        $q2 = $sentencias->truncarNota($sumaQ2 / $cont, $digito);
        
        $final_ano_normal = $sentencias->truncarNota($sumaFinalAnoNormal / $cont, $digito);

        array_push($this->arregloNotasFinales, array(
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
            'final_ano_normal' => $final_ano_normal
        ));
    }

    private function procesa_libreta() {
        $modelAreas = $this->get_areas();


        foreach ($modelAreas as $area) {

            $materias = $this->procesa_materias($area['malla_area_id']);

            $notas = $this->calcula_notas_area($materias);

            array_push($this->arregloLibreta, array(
                'area_id' => $area['malla_area_id'],
                'area' => $area['area'],
                'tipo' => $area['tipo'],
                'promedia' => $area['promedia'],
                'materias' => $materias,
                'notas_area' => $notas
            ));
        }
    }

    private function calcula_notas_area($arregloMaterias) {

        $sentencias = new Notas();
        $digito = 2;
        
        $arregloNotas = array();

        $sumaP1 = 0;
        $sumaP2 = 0;
        $sumaP3 = 0;
        $sumaPr1 = 0;
        $sumaPr180 = 0;
        $sumaE1 = 0;
        $sumaE120 = 0;
        $sumaQ1 = 0;
        $sumaP4 = 0;
        $sumaP5 = 0;
        $sumaP6 = 0;
        $sumaPr2 = 0;
        $sumaPr280 = 0;
        $sumaE2 = 0;
        $sumaE220 = 0;
        $sumaQ2 = 0;
        $sumaFinalAnoNormal = 0;
        $cont = 0;

        foreach ($arregloMaterias as $materia) {

            $notaP1 = $sentencias->truncarNota(($materia['notas']['p1'] * $materia['total_porcentaje']) / 100, $digito);
            $notaP2 = $sentencias->truncarNota(($materia['notas']['p2'] * $materia['total_porcentaje']) / 100, $digito);
            $notaP3 = $sentencias->truncarNota(($materia['notas']['p3'] * $materia['total_porcentaje']) / 100, $digito);
            $notaPr1 = $sentencias->truncarNota(($materia['notas']['pr1'] * $materia['total_porcentaje']) / 100, $digito);
            $notaPr180 = $sentencias->truncarNota(($materia['notas']['pr180'] * $materia['total_porcentaje']) / 100, $digito);
            $notaEx1 = $sentencias->truncarNota(($materia['notas']['ex1'] * $materia['total_porcentaje']) / 100, $digito);
            $notaEx120 = $sentencias->truncarNota(($materia['notas']['ex120'] * $materia['total_porcentaje']) / 100, $digito);
            $notaQ1 = $sentencias->truncarNota(($materia['notas']['q1'] * $materia['total_porcentaje']) / 100, $digito);

            $notaP4 = $sentencias->truncarNota(($materia['notas']['p4'] * $materia['total_porcentaje']) / 100, $digito);
            $notaP5 = $sentencias->truncarNota(($materia['notas']['p5'] * $materia['total_porcentaje']) / 100, $digito);
            $notaP6 = $sentencias->truncarNota(($materia['notas']['p6'] * $materia['total_porcentaje']) / 100, $digito);
            $notaPr2 = $sentencias->truncarNota(($materia['notas']['pr2'] * $materia['total_porcentaje']) / 100, $digito);
            $notaPr280 = $sentencias->truncarNota(($materia['notas']['pr280'] * $materia['total_porcentaje']) / 100, $digito);
            $notaEx2 = $sentencias->truncarNota(($materia['notas']['ex2'] * $materia['total_porcentaje']) / 100, $digito);
            $notaEx220 = $sentencias->truncarNota(($materia['notas']['ex220'] * $materia['total_porcentaje']) / 100, $digito);
            $notaQ2 = $sentencias->truncarNota(($materia['notas']['q2'] * $materia['total_porcentaje']) / 100, $digito);

            $notaFinalAnoNormal = $sentencias->truncarNota(($materia['notas']['final_ano_normal'] * $materia['total_porcentaje']) / 100, $digito);

            $sumaP1 = $sumaP1 + $notaP1;
            $sumaP2 = $sumaP2 + $notaP2;
            $sumaP3 = $sumaP3 + $notaP3;
            $sumaPr1 = $sumaPr1 + $notaPr1;
            $sumaPr180 = $sumaPr180 + $notaPr180;
            $sumaE1 = $sumaE1 + $notaEx1;
            $sumaE120 = $sumaE120 + $notaEx120;
            $sumaQ1 = $sumaQ1 + $notaQ1;

            $sumaP4 = $sumaP4 + $notaP4;
            $sumaP5 = $sumaP5 + $notaP5;
            $sumaP6 = $sumaP6 + $notaP6;
            $sumaPr2 = $sumaPr2 + $notaPr2;
            $sumaPr280 = $sumaPr280 + $notaPr280;
            $sumaE2 = $sumaE2 + $notaEx2;
            $sumaE220 = $sumaE220 + $notaEx220;
            $sumaQ2 = $sumaQ2 + $notaQ2;

            $sumaFinalAnoNormal = $sumaFinalAnoNormal + $notaFinalAnoNormal;


            $cont++;
        }

//        $p1 = $sumaP1;
//        $p2 = $sumaP2 / $cont;
//        $p3 = $sumaP3 / $cont;

        array_push($arregloNotas, array(
            'p1' => $sumaP1,
            'p2' => $sumaP2,
            'p3' => $sumaP3,
            'pr1' => $sumaPr1,
            'pr180' => $sumaPr180,
            'ex1' => $sumaE1,
            'ex120' => $sumaE120,
            'q1' => $sumaQ1,
            'p4' => $sumaP4,
            'p5' => $sumaP5,
            'p6' => $sumaP6,
            'pr2' => $sumaPr2,
            'pr280' => $sumaPr280,
            'ex2' => $sumaE2,
            'ex220' => $sumaE220,
            'q2' => $sumaQ2,
            'final_ano_normal' => $sumaFinalAnoNormal
        ));

        return $arregloNotas;
    }

    private function procesa_materias($mallaAreaId) {
        $arregloMaterias = array();
        $modelMaterias = $this->get_materias($mallaAreaId);

        foreach ($modelMaterias as $materia) {

            $notas = $this->materias_notas($materia['clase_id'], $materia['materia_id']);

            array_push($arregloMaterias, array(
                'area_id' => $mallaAreaId,
                'clase_id' => $materia['clase_id'],
                'materia_id' => $materia['materia_id'],
                'materia' => $materia['materia'],
                'tipo' => $materia['tipo'],
                'promedia' => $materia['promedia'],
                'se_imprime' => $materia['se_imprime'],
                'total_porcentaje' => $materia['total_porcentaje'],
                'notas' => $notas
            ));
        }
        return $arregloMaterias;
    }

    private function materias_notas($claseId, $materiaId) {
        $sentencias = new SentenciasNotasDefinitivasAlumno($this->alumno, $this->periodoId, $this->paralelo);

        $modelGrupo = ScholarisGrupoAlumnoClase::find()->where([
                    'estudiante_id' => $this->alumno,
                    'clase_id' => $claseId
                ])->one();

        $notas = $sentencias->get_nota_materia($materiaId, $modelGrupo->id);
        
        $this->arregloProyectos = $sentencias->get_notas_proyectos();
        $this->arregloComportamiento = $sentencias->get_notas_comportamiento();

        return $notas;
    }

    private function get_bloques() {
        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $this->paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;

        $this->bloques = ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_uso' => $uso
                ])->orderBy('orden')
                ->all();
    }

    /**
     * METODO QUE TOMA LAS AREAS DEL ESTUDIANTE
     * @return type
     */
    private function get_areas() {
        $con = Yii::$app->db;
        $query = "select 	ma.id as malla_area_id
                                    ,a.name as area
                                    ,ma.tipo 
                                    ,ma.promedia
                                    ,ma.se_imprime
                    from 	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                                    inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                                    inner join scholaris_area a on a.id = ma.area_id 
                    where	c.periodo_scholaris = '$this->periodoCodigo'
                                    and g.estudiante_id = $this->alumno
                                    and ma.tipo <> 'COMPORTAMIENTO'
                    group by ma.id, a.name 
                    order by a.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function get_materias($mallaAreaId) {
        $con = Yii::$app->db;
        $query = "select 	c.id as clase_id
		,m.name as materia
		,m.id as materia_id
		,mm.tipo 
		,mm.promedia 
		,mm.se_imprime 
		,mm.total_porcentaje 
from 	scholaris_clase c
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join scholaris_materia m on m.id = mm.materia_id 
where 	mm.malla_area_id = $mallaAreaId
		and g.estudiante_id = $this->alumno
order by m.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
