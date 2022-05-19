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
class SentenciasFaltas extends \yii\db\ActiveRecord {
    
    private $periodoCode;
    
    
    public function __construct() {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPerido = ScholarisPeriodo::findOne($periodoId);
        $this->periodoCode = $modelPerido->codigo;
    }

    public function get_novedad($alumno, $bloque) {

        $arreglo = array();

        $modelBloque = ScholarisBloqueActividad::find()->where(["id" => $bloque])->one();

        $atrasoNov = $this->total_novedad($alumno, $modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelBloque->scholaris_periodo_codigo, 'at');
        $atrasoJus = $this->total_justif($alumno, $modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelBloque->scholaris_periodo_codigo, 'at');
        isset($atrasoNov['total']) ? $atrasoNovT = $atrasoNov['total'] : $atrasoNovT = 0;
        isset($atrasoJus['total']) ? $atrasoJusT = $atrasoJus['total'] : $atrasoJusT = 0;
        $atrasoTot = $atrasoNovT - $atrasoJusT;


        $jusNov = $this->total_novedad($alumno, $modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelBloque->scholaris_periodo_codigo, 'fj');
        $jusJus = $this->total_justif($alumno, $modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelBloque->scholaris_periodo_codigo, 'fj');
        isset($jusNov['total']) ? $jusNovT = $jusNov['total'] : $jusNovT = 0;
        isset($jusJus['total']) ? $jusJusT = $jusJus['total'] : $jusJusT = 0;
        $jusTot = $jusNovT - $jusJusT;

        $injusNov = $this->total_novedad($alumno, $modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelBloque->scholaris_periodo_codigo, 'fi');
        $injusJus = $this->total_justif($alumno, $modelBloque->bloque_inicia, $modelBloque->bloque_finaliza, $modelBloque->scholaris_periodo_codigo, 'fi');
        isset($injusNov['total']) ? $injusNovT = $injusNov['total'] : $injusNovT = 0;
        isset($injusJus['total']) ? $injusJusT = $injusJus['total'] : $injusJusT = 0;
        $injusTot = $injusNovT - $injusJusT;

        $faltasReales = $this->faltas_y_atrasos_parciales($bloque, $alumno);
        isset($faltasReales['atrasos']) ? $atrasosReales = $faltasReales['atrasos'] : $atrasosReales = 0;
        isset($faltasReales['faltas_justificadas']) ? $justificadasReales = $faltasReales['faltas_justificadas'] : $justificadasReales = 0;
        isset($faltasReales['faltas_injustificadas']) ? $injustificadasReales = $faltasReales['faltas_injustificadas'] : $injustificadasReales = 0;
        isset($faltasReales['observacion']) ? $obserReales = $faltasReales['observacion'] : $obserReales = '';

        array_push($arreglo, $jusNovT);
        array_push($arreglo, $jusJusT);
        array_push($arreglo, $jusTot);

        array_push($arreglo, $atrasoNovT);
        array_push($arreglo, $atrasoJusT);
        array_push($arreglo, $atrasoTot);

        array_push($arreglo, $injusNovT);
        array_push($arreglo, $injusJusT);
        array_push($arreglo, $injusTot);

        array_push($arreglo, $atrasosReales);
        array_push($arreglo, $justificadasReales);
        array_push($arreglo, $injustificadasReales);
        array_push($arreglo, $obserReales);

        return $arreglo;
    }

    private function total_novedad($alumno, $desde, $hasta, $periodo, $tipo) {
        $con = \Yii::$app->db;
        $query = "select count(n.id) as total
                                    ,d.tipo
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id
                                    inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
                    where 	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$periodo'
                                    and d.tipo in ('$tipo')
                                    and a.fecha between '$desde' and '$hasta'
                    group by d.tipo;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function total_justif($alumno, $desde, $hasta, $periodo, $tipo) {
        $con = \Yii::$app->db;
        $query = "select count(j.id) as total
                                    ,d.tipo
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id
                                    inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
                                    inner join scholaris_asistencia_justificacion_alumno j on j.novedad_id = n.id
                    where 	g.estudiante_id = $alumno
                                    and c.periodo_scholaris = '$periodo'
                                    and d.tipo in ('$tipo')
                                    and a.fecha between '$desde' and '$hasta'
                    group by d.tipo;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function faltas_y_atrasos_parciales($bloque, $alumno) {
        $con = \Yii::$app->db;
        $query = "select id, alumno_id, bloque_id, atrasos, faltas_justificadas, faltas_injustificadas, observacion 
                    from 	scholaris_faltas_y_atrasos_parcial
                    where	alumno_id = $alumno 
                                    and bloque_id = $bloque; ";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    public function consulta_observacion($quimestre, $alumnoId){
        if($quimestre == 'q1'){
            $q = 'QUIMESTRE I';
        }else{
            $q = 'QUIMESTRE II';
        }
        
        $con = \Yii::$app->db;
        $query = "select 	observacion 
                    from 	scholaris_faltas_y_atrasos_parcial f
                                    inner join scholaris_bloque_actividad b on b.id = f.bloque_id
                    where 	f.alumno_id = $alumnoId
                                    and b.quimestre = '$q' and b.scholaris_periodo_codigo = '$this->periodoCode' order by b.orden desc;";        
        
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['observacion']) ? $observacion = $res['observacion'] : $observacion = '';
        
        return $observacion;
        
    }
    

    public function modifica_novedad_real($alumno, $bloque, $valor, $tipo) {

        switch ($tipo) {
            case 1:
                $campo = 'atrasos';
                break;
            case 2:
                $campo = 'faltas_justificadas';
                break;
            case 3:
                $campo = 'faltas_injustificadas';
                break;
            case 4:
                $campo = 'observacion';
                break;;
        }

        $this->modificar($alumno, $bloque, $valor, $campo);
        
    }
    
    private function modificar($alumno, $bloque, $valor,$campo){
        $model = ScholarisFaltasYAtrasosParcial::find()
                ->where([
                    'alumno_id' => $alumno,
                    'bloque_id' => $bloque,
                    ])
                ->one();
        
        if(isset($model)){
            $this->modificaReal($alumno, $bloque, $valor, $campo);
        }else{
            $this->ingresaReal($alumno, $bloque, $valor, $campo);
        }
        
    }
    
    private function ingresaReal($alumno, $bloque, $valor, $campo){
        $con = \Yii::$app->db;
        $query = "insert into scholaris_faltas_y_atrasos_parcial(alumno_id, bloque_id, $campo) "
                . "values($alumno, $bloque, '$valor')";
        $con->createCommand($query)->execute();        
    }
    
    private function modificaReal($alumno, $bloque, $valor, $campo){
        $con = \Yii::$app->db;
        $query = "update scholaris_faltas_y_atrasos_parcial "
                . "set "
                . "$campo = '$valor' "
                . "where alumno_id = $alumno and bloque_id = $bloque";   
        
        $con->createCommand($query)->execute();        
    }
    
    
    
    
    
    public function busca_faltas_inspeccion($alumno, $bloque){
        $con = Yii::$app->db;
        $query = "select
	(select 	count(*) as atrasos 
		from 	scholaris_toma_asistecia_detalle d
		inner join scholaris_toma_asistecia a on a.id = d.toma_id
		where	d.alumno_id = $alumno
		and a.bloque_id = $bloque
		and atraso = true)
	,(select 	count(*) as falta 
			from 	scholaris_toma_asistecia_detalle d
			inner join scholaris_toma_asistecia a on a.id = d.toma_id
			where	d.alumno_id = $alumno
			and a.bloque_id = $bloque
			and falta = true)
	,(select 	count(*) as falta_justificada 
		from 	scholaris_toma_asistecia_detalle d
				inner join scholaris_toma_asistecia a on a.id = d.toma_id
		where	d.alumno_id = $alumno
				and a.bloque_id = $bloque
				and falta_justificada = true);";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    
    /**
     * Para tomar la cantidad de faltas y atrasos por parcial
     * @param type $alumno
     * @param type $bloque
     */
    
    public function entrega_faltas_parcial($alumno, $bloque, $paralelo){
        
         $modelBloque = ScholarisBloqueActividad::findOne($bloque);
        
        $modelParametros = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'faltas'])
                ->one();

        if ($modelParametros->valor == 1) {///ojo revisar en el rosa si salen del atrasos normal
            $datosAsistencia = array();
            

            $modelAsistidos = \backend\models\ScholarisFaltasYAtrasosParcial::find()
                    ->where(['alumno_id' => $alumno, 'bloque_id' => $bloque])
                    ->one();
            
            if ($modelAsistidos) {
                array_push($datosAsistencia, $modelAsistidos->atrasos);
                array_push($datosAsistencia, $modelAsistidos->faltas_justificadas);
                array_push($datosAsistencia, $modelAsistidos->faltas_injustificadas);
            } else {
                array_push($datosAsistencia, 0);
                array_push($datosAsistencia, 0);
                array_push($datosAsistencia, 0);
            }
        } else {
            $datosAsistencia = $this->toma_dias_asistidos_parcial($paralelo, $alumno, $modelBloque->orden);
        }
        
        
//        print_r($datosAsistencia);
//        die();
        
        return $datosAsistencia;
    }
    
    
    
    public function toma_dias_asistidos_parcial($paralelo, $alumno, $ordenBloque){
        $arreglo = array();
        
//        $alumno = 1312;
        
        $at = $this->faltas_atrasos($paralelo, $alumno, 'atraso', $ordenBloque);
        
        isset($at['total']) ? $at = $at['total'] : $at = 0;
        array_push($arreglo, $at);
        
        isset( $fj['total'] ) ? $fj = $fj['total'] : $fj = 0;
        $fj = $this->faltas_atrasos($paralelo, $alumno, 'falta_justificada', $ordenBloque);
        array_push($arreglo, $fj);
        
        isset($fi['total']) ? $fi = $fi['total'] : $fi = 0;
        $fi = $this->faltas_atrasos($paralelo, $alumno, 'falta', $ordenBloque);
        array_push($arreglo, $fi);
        
        
        $diasLb = $this->toma_dias_asistidos($ordenBloque, $paralelo);
        
        //array_push($arreglo,$fi['dias_laborados']);
        array_push($arreglo,$diasLb);
        
//        print_r($arreglo);
//        die();
        
        return $arreglo;
        
    }
    
    
    private function toma_dias_asistidos($ordenBloque, $paralelo){
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
                
        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;
        
        $modelBloque = ScholarisBloqueActividad::find()->where(['tipo_uso' => $uso, 
                                                                'orden' => $ordenBloque,
                                                                'scholaris_periodo_codigo' => $modelPeriodo->codigo])->one();
//        echo '<pre>';
//        print_r($modelBloque);
        
        return $modelBloque->dias_laborados;
        
    }


    
    private function faltas_atrasos($paralelo, $alumno, $campo, $orden) {
    $con = Yii::$app->db;
    
    $query = "select 	count(d.id) as total
                                ,b.dias_laborados
                    from 	scholaris_toma_asistecia_detalle d
                                    inner join scholaris_toma_asistecia a on a.id = d.toma_id
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_id
                    where	a.paralelo_id = $paralelo
                                    and b.orden = $orden
                                    and d.alumno_id = $alumno
                                    and d.$campo = true group by b.dias_laborados;";
    
    
    $res = $con->createCommand($query)->queryOne();
//    echo $query;
//    die();
    return $res;
}
    


public function devuelve_faltas_a_libreta($alumno, $parcial, $paralelo){
    
//        echo $parcial;
//        die();
    
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        
        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();        
        
        switch ($parcial){
            case 'p1':
                    $bloqueId = ScholarisBloqueActividad::find()
                            ->where(['orden' => 1, 'tipo_uso' => $modelUso->tipo_usu_bloque, 'scholaris_periodo_codigo' => $modelPeriodo->codigo])
                            ->one();
                    $arregloFaltas = $this->entrega_faltas_parcial($alumno, $bloqueId->id, $paralelo);   
                    array_push($arregloFaltas,$bloqueId->dias_laborados);
                    break;
             
            case 'p2':
                    $bloqueId = ScholarisBloqueActividad::find()
                            ->where(['orden' => 2, 'tipo_uso' => $modelUso->tipo_usu_bloque, 'scholaris_periodo_codigo' => $modelPeriodo->codigo])
                            ->one();
                    $arregloFaltas = $this->entrega_faltas_parcial($alumno, $bloqueId->id, $paralelo);
                    array_push($arregloFaltas,$bloqueId->dias_laborados);
                    break;
                
            case 'p3':
                    $bloqueId = ScholarisBloqueActividad::find()
                            ->where(['orden' => 3, 'tipo_uso' => $modelUso->tipo_usu_bloque, 'scholaris_periodo_codigo' => $modelPeriodo->codigo])
                            ->one();
                    $arregloFaltas = $this->entrega_faltas_parcial($alumno, $bloqueId->id, $paralelo);
                    array_push($arregloFaltas,$bloqueId->dias_laborados);
                    break;
                
            case 'p4':
                    $bloqueId = ScholarisBloqueActividad::find()
                            ->where(['orden' => 5, 'tipo_uso' => $modelUso->tipo_usu_bloque, 'scholaris_periodo_codigo' => $modelPeriodo->codigo])
                            ->one();
                    $arregloFaltas = $this->entrega_faltas_parcial($alumno, $bloqueId->id, $paralelo);
                    array_push($arregloFaltas,$bloqueId->dias_laborados);
                    break;
                
            case 'p5':
                    $bloqueId = ScholarisBloqueActividad::find()
                            ->where(['orden' => 6, 'tipo_uso' => $modelUso->tipo_usu_bloque, 'scholaris_periodo_codigo' => $modelPeriodo->codigo])
                            ->one();
                    $arregloFaltas = $this->entrega_faltas_parcial($alumno, $bloqueId->id, $paralelo);
                    array_push($arregloFaltas,$bloqueId->dias_laborados);
                    break;
                
            case 'p6':
                    $bloqueId = ScholarisBloqueActividad::find()
                            ->where(['orden' => 7, 'tipo_uso' => $modelUso->tipo_usu_bloque, 'scholaris_periodo_codigo' => $modelPeriodo->codigo])
                            ->one();
                    $arregloFaltas = $this->entrega_faltas_parcial($alumno, $bloqueId->id, $paralelo);
                    array_push($arregloFaltas,$bloqueId->dias_laborados);
                    break;
                
            case 'q1':
                    $modelBloques = ScholarisBloqueActividad::find()
                                    ->where(['tipo_uso' => $modelUso->tipo_usu_bloque, 
                                             'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                                             'quimestre' => 'QUIMESTRE I'
                                            ])
                                    ->all();
                    $sumatraso = 0; 
                    $sumfaltaj = 0; 
                    $sumfaltai = 0;
                    $sumdiasla = 0;
                    
                    foreach ($modelBloques as $bloque){
                        $arreglo = $this->entrega_faltas_parcial($alumno, $bloque->id, $paralelo);
                        $sumatraso = $sumatraso + $arreglo[0];
                        $sumfaltaj = $sumfaltaj + $arreglo[1];
                        $sumfaltai = $sumfaltai + $arreglo[2];
                        $sumdiasla = $sumdiasla + $bloque->dias_laborados;
                    }
                    
                    $arregloFaltas = array($sumatraso, $sumfaltaj, $sumfaltai, $sumdiasla);
                    break;
                    
            case 'q2':
                    $modelBloques = ScholarisBloqueActividad::find()
                                    ->where(['tipo_uso' => $modelUso->tipo_usu_bloque, 
                                             'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                                             'quimestre' => 'QUIMESTRE II'
                                            ])
                                    ->all();
                    $sumatraso = 0; 
                    $sumfaltaj = 0; 
                    $sumfaltai = 0;
                    $sumdiasla = 0;
                    
                    foreach ($modelBloques as $bloque){
                        $arreglo = $this->entrega_faltas_parcial($alumno, $bloque->id, $paralelo);
                        
                        isset($arreglo[0]) ? $ar0 = $arreglo[0] : $ar0 = 0;
                        isset($arreglo[1]) ? $ar1 = $arreglo[1] : $ar1 = 0;
                        isset($arreglo[2]) ? $ar2 = $arreglo[2] : $ar2 = 0;                                              
                        
                        $sumatraso = $sumatraso + $ar0;
//                        $sumfaltaj = $sumfaltaj + $ar1;
//                        $sumfaltai = $sumfaltai + $ar2;
                        $sumdiasla = $sumdiasla + $bloque->dias_laborados;
                    }
                    
                    $arregloFaltas = array($sumatraso, $sumfaltaj, $sumfaltai,$sumdiasla);
                    break;
                    
        }
        
        
//        print_r($arregloFaltas);
//        die();
        
        return $arregloFaltas;
        
    }


}
