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
 * ESTA CLASE TRATA CON PROCESAMIENTO DE NOTAS SOLO PARA CALITICACIONES NORMALES
 * Ejemplo para colegios ISM; ROSA DE JESUS; SANTO DOMINGO QUITO
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model
 * 
 * Esta clase debe ser invocada una sola vez.
 */
class ProcesaNotasInterdisciplinar extends \yii\db\ActiveRecord {

    private $modelParalelo;
    private $paraleloId;
    private $alumno;
    public $arrayNotas = array();
    private $periodoId;
    private $periodoCodigo;
    private $notaMinima;
    private $grupoId;
    private $uso;
    private $totalParciales;

    public function __construct($paralelo, $alumno) {

        $this->busca_nota_minima(); //busca nota minima

        $this->paraleloId = $paralelo;        //asigna atributo paralelo
        $this->alumno = $alumno;          //asigna atributo alumno
        $this->toma_periodos();                 //para asignar periodos del paralelo
        $this->toma_clase_comportamiento();     //asigna atributo de asignatura de comportamiento, en esta clase 
        //permanecen las calificaciones de todas las asignaturas por ser interdisciplinar
        $this->toma_bloques_parciales();
        $this->toma_nota_interdisciplinar(); //toma las notas interdisciplinar 
    }

    /**
     * METODO QUE TOMA EL TOTAL DE PARCIALES
     */
    public function toma_bloques_parciales() {
        $modelBloques = ScholarisBloqueActividad::find()->where([
                    'tipo_uso' => $this->uso,
                    'tipo_bloque' => 'PARCIAL',
                    'scholaris_periodo_codigo' => $this->periodoCodigo
                ])->all();

        $this->totalParciales = count($modelBloques);
    }

    /*     * ****
     * METODO PARA ASIGNAR LA NOTA MINIMA
     */

    private function busca_nota_minima() {
        $parametros = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $this->notaMinima = $parametros->valor;
    }

    /**
     * METODO PARA POBLAR PERIODOS
     */
    private function toma_periodos() {
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
    }

    /**
     * METODO QUE REGISTRA LA CLASE DE COMPORTAMIENTO
     */
    private function toma_clase_comportamiento() {
        $con = \Yii::$app->db;
        $query = "select 	g.id, c.tipo_usu_bloque
                    from 	scholaris_clase c
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                                    inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
                    where	c.paralelo_id = $this->paraleloId
                                    and g.estudiante_id = $this->alumno
                                    and mm.tipo = 'COMPORTAMIENTO';";
        $res = $con->createCommand($query)->queryOne();

        if (isset($res['id'])) {
            $this->grupoId = $res['id'];
            $this->uso = $res['tipo_usu_bloque'];
        } else {
            return;
        }
    }

    /**
     * INICIO DE PROCESO DE CAPTURA DE NOTAS DE ESTUDIANTE DE LA CALIFICACION INTERDISCIPLINAR 
     */
    private function toma_nota_interdisciplinar() {
        $sentencias = new Notas();
        $digito = 2;

        $contParciales = 2;

        $p1 = $this->toma_nota_orden_parcial(1);
        $p2 = $this->toma_nota_orden_parcial(2);
        if ($this->totalParciales > 4) {
            $p3 = $this->toma_nota_orden_parcial(3);
            $p6 = $this->toma_nota_orden_parcial(7);
            $contParciales = 3;
        } else {
            $p3 = 0;
            $p6 = 0;
        }
        //$ex1 = $this->toma_nota_orden_parcial(4);
        
        $ex1 = $this->toma_nota_examen('ex1');
        
        $p4 = $this->toma_nota_orden_parcial(5);
        $p5 = $this->toma_nota_orden_parcial(6);
        //$ex2 = $this->toma_nota_orden_parcial(8);
        $ex2 = $this->toma_nota_examen('ex2');

        $pr1 = $sentencias->truncarNota(($p1 + $p2 + $p3) / $contParciales, $digito);
        $pr180 = $sentencias->truncarNota(($pr1 * 80 / 100), $digito);
        $ex120 = $sentencias->truncarNota(($ex1 * 20 / 100), $digito);
        $q1 = $pr180 + $ex120;

        $pr2 = $sentencias->truncarNota(($p4 + $p5 + $p6) / $contParciales, $digito);
        $pr280 = $sentencias->truncarNota(($pr2 * 80 / 100), $digito);
        $ex220 = $sentencias->truncarNota(($ex2 * 20 / 100), $digito);
        $q2 = $pr280 + $ex220;

        $final_ano_normal = $sentencias->truncarNota(($q1 + $q2) / 2, 2);

        $notasExtras = ScholarisClaseLibreta::find()->where(['grupo_id' => $this->grupoId])->one();

        $notaFinal = $this->revisa_nota_final($notasExtras, $final_ano_normal);
        
        isset($notasExtras->supletorio) ? $supletorio = $notasExtras->supletorio : $supletorio = 0;
        isset($notasExtras->remedial) ? $remedial = $notasExtras->remedial : $remedial = 0;
        isset($notasExtras->gracia) ? $gracia = $notasExtras->gracia : $gracia = 0;


        array_push($this->arrayNotas, array(
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
            'final_ano_normal' => $final_ano_normal,
            'mejora_q1' => 0,
            'mejora_q2' => 0,
            'final_con_mejora' => 0,
            'supletorio' => $supletorio,
            'remedial' => $remedial,
            'gracia' => $gracia,
            'final_total' => $notaFinal
        ));
    }

    //METODO PARA TOMA DE NOTAS POR ORDEN DE PARCIAL
    private function toma_nota_orden_parcial($orden) {

        if (isset($this->uso)) {
            $con = \Yii::$app->db;
            $query = "select 	b.orden 
                                ,b.name
                                ,sum(c.nota) as nota
                from	scholaris_calificaciones_parcial c
                                left join scholaris_bloque_actividad b on b.id = c.bloque_id 
                where	c.grupo_id = $this->grupoId
                                and b.tipo_uso = '$this->uso'
                                and b.orden = $orden
                group by b.orden, b.name;";
            
            $res = $con->createCommand($query)->queryOne();
            if (isset($res['nota'])) {
                return $res['nota'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
    private function toma_nota_examen($examen){
        $modelExamen = ScholarisClaseLibreta::find()->where(['grupo_id' => $this->grupoId])->one();
        
        isset($modelExamen->$examen) ? $nota = $modelExamen->$examen : $nota = 0; 
        
        return $nota;
        
    }
    

    //METODO QUE REVISA MEJORAS DE NOTAS DE QUIMESTRES DEL ESTUDIANTE
    private function revisa_nota_final($modelNotasGrupo, $final_ano_normal) {


        if ((isset($modelNotasGrupo->supletorio) >= $this->notaMinima) || (isset($modelNotasGrupo->remedial) >= $this->notaMinima) || (isset($modelNotasGrupo->gracia) >= $this->notaMinima)) {
            return $this->notaMinima;
        } else {
            return $final_ano_normal;
        }
    }

//    FIN DE PROCESO DE NOTAS DE INTERDISCIPLINAR
}
