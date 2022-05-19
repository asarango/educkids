<?php

namespace backend\models;

use Yii;

class CalificacionCovidParcial extends \yii\db\ActiveRecord {

    public $notasPortafolio;
    public $notaContenido;
    public $notaPresentacion;
    public $notaPadre;
    public $notaTotal;
    public $maximoPortafolio;
    public $maximoContenido;
    private $periodo;
    private $periodoCodigo;
    private $alumnoId;
    private $modelClases;
    private $tipoCalificacion;
    private $paraleloId;
    private $claseComportamientoId;
    private $usaPortafolio;

    public function __construct($codigoCovid, $alumnoId, $periodoId) {

        $modelParametroUsaPortafolio = ScholarisParametrosOpciones::find()->where(['codigo' => 'usaportafolio'])->one();
        $this->usaPortafolio = $modelParametroUsaPortafolio->valor;


        if ($codigoCovid == 'covid2019') {
            $this->maximoPortafolio = 7;
            $this->maximoContenido = 1;
        } else {
            $this->maximoPortafolio = 3;
            $this->maximoContenido = 5;
        }


//        echo $this->maximoPortafolio.'xx';
//        die();

        $this->periodo = $periodoId;
        $this->alumnoId = $alumnoId;

        $this->get_periodo_codigo($periodoId);
        $this->get_clases();

        $modelParamsCalificacion = \backend\models\ScholarisParametrosOpciones::find()->where([
                    'codigo' => 'tipocalif'
                ])->one();

        $this->tipoCalificacion = $modelParamsCalificacion->valor;

        $datosParaleloYComportamiento = new SentenciasAlumnos();
        $dataParalelos = $datosParaleloYComportamiento->get_paralelo_id_periodoScholaris($alumnoId, $periodoId);
        $this->paraleloId = $dataParalelos['paralelo_id'];
        $this->claseComportamientoId = $dataParalelos['id'];
    }

    private function get_periodo_codigo($periodoId) {
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
    }

    private function get_clases() {
        $con = \Yii::$app->db;
        $query = "select 	c.id 
                    from 	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase c on c.id = g.clase_id 
                    where 	g.estudiante_id = $this->alumnoId
                                    and c.periodo_scholaris = '$this->periodoCodigo';";

        $this->modelClases = $con->createCommand($query)->queryAll();
    }

    public function get_total_actividades($claseId, $bloqueId) {
        $con = \Yii::$app->db;
        $query = "select 	a.id 
                    from 	scholaris_actividad_deber d
                                    inner join scholaris_actividad a on a.id = d.actividad_id
                                    inner join scholaris_clase c on c.id = a.paralelo_id 
                    where 	d.alumno_id = $this->alumnoId
                                    and c.periodo_scholaris = '$this->periodoCodigo'
                                    and c.id = $claseId
                                        and a.calificado = 'SI'
                                        and a.bloque_actividad_id = $bloqueId
                    group by a.id 
                    order by a.id;";
//
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function get_notas_por_clase($claseId, $bloqueId, $automatico, $totalActividadesClase, $totalActividadesAlumno) {

        if ($this->tipoCalificacion == 1) {
            $claseId = $this->claseComportamientoId;
        }

        $modelBloque = ScholarisBloqueActividad::findOne($bloqueId);

        if ($modelBloque->tipo_bloque == 'PARCIAL') {
            $this->notasPortafolio = $this->revisa_nota($claseId, $bloqueId, 'portafolio', 'docente', $automatico, $totalActividadesClase, $totalActividadesAlumno);

            $this->notaContenido = $this->revisa_nota($claseId, $bloqueId, 'contenido', 'docente', $automatico, $totalActividadesClase, $totalActividadesAlumno);
            $this->notaPresentacion = $this->revisa_nota($claseId, $bloqueId, 'presentacion', 'docente', $automatico, $totalActividadesClase, $totalActividadesAlumno);
            $this->notaPadre = $this->revisa_nota($claseId, $bloqueId, 'padre', 'padre', $automatico, $totalActividadesClase, $totalActividadesAlumno);


            $this->notaTotal = $this->notasPortafolio + $this->notaContenido + $this->notaPresentacion + $this->notaPadre;
        } else {
            $notaExamen = number_format($this->consulta_nota_examen($claseId, $bloqueId, $this->alumnoId), 2);
            $this->notaTotal = $notaExamen;
        }
    }

    private function consulta_nota_examen($claseId, $bloqueId, $alumnoId) {
        $con = Yii::$app->db;
        $query = "select trunc(avg(calificacion),2) as nota
                    from (
                            select 	cal.calificacion, o.grupo_numero 
                            from 	scholaris_clase c 
                                            inner join scholaris_actividad a on a.paralelo_id = c.id
                                            inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
                                            inner join scholaris_calificaciones cal on cal.idactividad = a.id 
                                            inner join scholaris_grupo_orden_calificacion o on o.codigo_tipo_actividad = a.tipo_actividad_id 
                            where 	c.id = $claseId
                                            and b.id = $bloqueId
                                            and cal.idalumno = $alumnoId
                    )as scholaris_calificaciones 
                    group by grupo_numero;";
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['nota']) ? $nota = $res['nota'] : $nota = 0;
        return $nota;
    }

    private function revisa_nota($claseId, $bloqueId, $codigoQueCalifica, $quienCalifica, $automatico, $totalActividadesClase, $totalActividadesAlumno) {
        
        $modelGrupo = ScholarisGrupoAlumnoClase::find()->where([
                    'estudiante_id' => $this->alumnoId,
                    'clase_id' => $claseId
                ])->one();


        if (!$modelGrupo) {
            echo 'Error no existen todos los alumnos en la clase';
            echo 'Por favor comunique a su Administrador para que sincronice los alumnos';
            exit();
            die();
        }


        if ($automatico == 1) {

            $modelCambio = ScholarisCalificacionesParcialCambios::find()->where([
                        'grupo_id' => $modelGrupo->id,
                        'codigo_que_califica' => $codigoQueCalifica,
                        'bloque_id' => $bloqueId
                    ])
                    ->orderBy(['fecha_cambio' => SORT_DESC])
                    ->one();


            if ($modelCambio) {
                $modelC = ScholarisCalificacionesParcial::find()->where([
                            'grupo_id' => $modelGrupo->id,
                            'codigo_que_califica' => $codigoQueCalifica,
                            'bloque_id' => $bloqueId
                        ])->one();
                isset($modelC->nota) ? $nota = $modelC->nota : $nota = null;
            } else {

                $nota = $this->convierte_notas_covid($codigoQueCalifica, $bloqueId, $modelGrupo->id, $totalActividadesClase, $totalActividadesAlumno);
                $nota = number_format($nota, 2);

//                if($codigoQueCalifica == 'padre'){
//            echo $nota;
//            die();
//        }
            }
        } else {
            $modelC = ScholarisCalificacionesParcial::find()->where([
                        'grupo_id' => $modelGrupo->id,
                        'codigo_que_califica' => $codigoQueCalifica,
                        'bloque_id' => $bloqueId
                    ])->one();

            if ($modelC) {
                $nota = $modelC->nota;
                //echo 'si hay';
            } else {
                //echo 'no hay';
//                $model = new ScholarisCalificacionesParcial();
//                $model->bloque_id = $bloqueId;
//                $model->grupo_id = $modelGrupo->id;
//                $model->codigo_que_califica = $codigoQueCalifica;
//                $model->quien_califica = $quienCalifica;
//                $model->tipo_calificacion = 'covid2019';
//                $model->clase_usada = 'covid2019';
//                $model->save();
                $nota = null;
            }
        }


        $mod = ScholarisCalificacionesParcial::find()->where([
                    'bloque_id' => $bloqueId,
                    'grupo_id' => $modelGrupo->id,
                    'codigo_que_califica' => $codigoQueCalifica
                ])->one();

        if ($mod) {
            $mod->nota = $nota;
            $mod->save();
        } else {
            $model = new ScholarisCalificacionesParcial();
            $model->bloque_id = $bloqueId;
            $model->grupo_id = $modelGrupo->id;
            $model->codigo_que_califica = $codigoQueCalifica;
            $model->quien_califica = $quienCalifica;
            $model->tipo_calificacion = 'covid2019';
            $model->clase_usada = 'covid2019';
            $model->nota = $nota;
            $model->save();
        }
        return $nota;
    }
    
    private function total_actividades($bloque, $grupoId){
        $con = Yii::$app->db;
        $query = "select 	a.id 
		,a.calificado 
		,c.calificacion 
from	scholaris_grupo_alumno_clase g
		inner join scholaris_actividad a on a.paralelo_id = g.clase_id 
		inner join scholaris_calificaciones c on c.idactividad = a.id
where	g.id = $grupoId
		and g.estudiante_id = c.idalumno
		and a.calificado = 'SI'
		and a.bloque_actividad_id = $bloque
order by a.id;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function convierte_notas_covid($codigoCalifica, $bloqueId, $grupoId, $totalActividadesClase, $totalActividadesAlumno) {
       
        if($totalActividadesClase < 1){
            return 0;
        }
        $nota = 0;
        
//        if($totalActividadesClase < 1){
//            echo '<strong>***********************************************************</strong><br>';
//            echo '<strong>ERROR!!!. Con codigo grupoId estudiante: '.$grupoId.' Bloque: '.$bloqueId.'</strong><br>';
//            echo '<strong>La cantidad de division es cero</strong><br>';
//            echo '<strong>***********************************************************</strong><br>';
//            die();
//        }

        if ($codigoCalifica == 'portafolio') {

            if ($this->usaPortafolio == 1) {

                if ($totalActividadesClase == 0) {
                    $nota = 0;
                } else {
                    $nota = ($totalActividadesAlumno * $this->maximoPortafolio) / $totalActividadesClase;
                }
            } else {                

                $totalActAlCalculado = count($this->total_actividades($bloqueId, $grupoId));
                $nota = ($totalActAlCalculado * $this->maximoPortafolio) / $totalActividadesClase;
//                $nota = 1;
            }
        } elseif ($codigoCalifica == 'presentacion') {
            $nota = 1;
        } elseif ($codigoCalifica == 'contenido') {

            $modelParametro = ScholarisParametrosOpciones::find()->where([
                        'codigo' => 'califmmaxima'
                    ])->one();

            $modelNotas = new Notas();
            $notaParcial = $modelNotas->get_nota_parcial($bloqueId, $grupoId); //obtiene la nota del parcial

            $nota = ($notaParcial * $this->maximoContenido) / $modelParametro->valor;
        } elseif ($codigoCalifica == 'padre') {
            $modelNota = ScholarisCalificacionesParcial::find()->where([
                        'bloque_id' => $bloqueId,
                        'grupo_id' => $grupoId,
                        'codigo_que_califica' => 'padre'
                    ])->one();
            if ($modelNota) {
                $nota = $modelNota->nota;
            } else {
                $nota = 0;
            }
        }



        return $nota;
    }

}
