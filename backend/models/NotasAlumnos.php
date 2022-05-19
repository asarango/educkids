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
class NotasAlumnos extends \yii\db\ActiveRecord {

    private $paralelo;
    private $modelAlumnos;
    private $periodoId;
    private $periodoCodigo;
    private $usuario;
    private $alumno = '';
    private $notasProyectos;
    private $notasComportamiento;

    public function __construct($paralelo, $quimestre, $alumno) {

        $sentencias = new SentenciasAlumnos();
        $modelParalelo = OpCourseParalelo::findOne($paralelo);


        $this->paralelo = $paralelo;
        $this->alumno = $alumno;
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $this->usuario = \Yii::$app->user->identity->usuario;


        if ($this->alumno == '') {
            $this->modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        } else {

            $this->modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }


        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;

        $this->vaciar_notas_usuario_paralelo(); //elimina las notas que subieron a la tabla
        
        $this->llena_materias(); //llena la tabal de materias
        $this->llena_areas(); //llena la tabal de areas
        $this->llena_promedios(); //llena la tabal de areas
    }

    private function llena_areas() {
        $con = Yii::$app->db;
        $query = "insert into scholaris_proceso_areas
                    select 	m.usuario 
                                    ,m.paralelo_id 
                                    ,m.alumno_id
                                    ,m.area_id
                                    ,ma.total_porcentaje 
                                    ,ma.promedia 
                                    ,ma.se_imprime 
                                    ,m.bloque
                                    ,trunc(sum((nota*porcentaje)/100),2) 
                                    --,sum((nota*porcentaje)/100) 
                    from 	scholaris_proceso_materias m 
                                    inner join scholaris_clase c on c.id = m.clase_id
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                                    inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                    where	m.usuario = '$this->usuario'
                                    and m.paralelo_id = $this->paralelo
                    group by m.alumno_id, m.bloque,ma.total_porcentaje, m.area_id,m.usuario,ma.promedia, ma.se_imprime, m.usuario 
                                    ,m.paralelo_id
                    order by m.alumno_id, m.area_id, m.bloque;";
//                    echo $query;
//                    die();
        $con->createCommand($query)->execute();
    }

    private function llena_promedios() {
        $con = Yii::$app->db;
        $query = "insert into scholaris_proceso_promedios
                    select usuario, paralelo_id, alumno_id,bloque, trunc(avg(nota),2) as nota
                    from (
                    select 	usuario 		
                                    ,paralelo_id
                                    ,alumno_id 		
                                    ,bloque
                                    ,nota 
                    from 	scholaris_proceso_areas
                    where	paralelo_id = $this->paralelo
                                    and usuario = '$this->usuario'
                                    and promedia = true
                    union all
                    select 	usuario                                     
                                    ,paralelo_id 
                                    ,alumno_id
                                    ,bloque
                                    ,nota 
                    from 	scholaris_proceso_materias
                    where	usuario = '$this->usuario'
                                    and paralelo_id = $this->paralelo
                                    and promedia = 1
                    ) as nota
                    group by usuario,alumno_id, paralelo_id,bloque
                    order by alumno_id;";
        $con->createCommand($query)->execute();
    }

    private function busca_clase_comportamiento() {

        $con = Yii::$app->db;
        $query = "select 	c.id 
                    from 	scholaris_clase c
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                    where	c.paralelo_id = $this->paralelo
                                    and mm.tipo = 'COMPORTAMIENTO';";
        $res = $con->createCommand($query)->queryOne();
        return $res['id'];
    }
    
    
    private function get_notas_interdisciplinar($grupoId){
        $con = Yii::$app->db;
        $query = "select 	c.bloque_id 
		,sum(c.nota)
		,b.orden 
		,b.name
                from 	scholaris_calificaciones_parcial c
                                inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
                where 	c.grupo_id = $grupoId
                group  by c.bloque_id, b.orden, b.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    

    private function llena_materias() {
        
        $modelCalificacion = ScholarisParametrosOpciones::find()->where([
                    'codigo' => 'tipocalif'
                ])->one();

        $tipoCalif = $modelCalificacion->valor;
        $claseComportamientoId = $this->busca_clase_comportamiento();
        
        $sentenciasNotas = new Notas();

        foreach ($this->modelAlumnos as $alumno) {

            if ($tipoCalif == 0) {
                $definitivas = new SentenciasNotasDefinitivasAlumno($alumno['id'], $this->periodoId, $this->paralelo);
            }

            //TOMA LAS MATERIAS DE ESTUDIANTES
            $materias = $this->recupera_materias_normales_alumno($alumno['id']);
            
            foreach ($materias as $mat) {

                if ($tipoCalif == 0) {
                    $nota = $definitivas->get_nota_materia($mat['materia_id'], $mat['grupo_id']);
                    $nota['p1'] ? $nota['p1'] = $nota['p1'] : $nota['p1'] = 0;
                    $nota['p2'] ? $nota['p2'] = $nota['p2'] : $nota['p2'] = 0;
                    $nota['p3'] ? $nota['p3'] = $nota['p3'] : $nota['p3'] = 0;
                    $nota['pr1'] ? $nota['pr1'] = $nota['pr1'] : $nota['pr1'] = 0;
                    $nota['pr180'] ? $nota['pr180'] = $nota['pr180'] : $nota['pr180'] = 0;
                    $nota['ex1'] ? $nota['ex1'] = $nota['ex1'] : $nota['ex1'] = 0;
                    $nota['ex120'] ? $nota['ex120'] = $nota['ex120'] : $nota['ex120'] = 0;
                    $nota['q1'] ? $nota['q1'] = $nota['q1'] : $nota['q1'] = 0;


                    $nota['p4'] ? $nota['p4'] = $nota['p4'] : $nota['p4'] = 0;
                    $nota['p5'] ? $nota['p5'] = $nota['p5'] : $nota['p5'] = 0;
                    $nota['p6'] ? $nota['p6'] = $nota['p6'] : $nota['p6'] = 0;
                    $nota['pr2'] ? $nota['pr2'] = $nota['pr2'] : $nota['pr2'] = 0;
                    $nota['pr280'] ? $nota['pr280'] = $nota['pr280'] : $nota['pr280'] = 0;
                    $nota['ex2'] ? $nota['ex2'] = $nota['ex2'] : $nota['ex2'] = 0;
                    $nota['ex220'] ? $nota['ex220'] = $nota['ex220'] : $nota['ex220'] = 0;
                    $nota['q2'] ? $nota['q2'] = $nota['q2'] : $nota['q2'] = 0;

                    $nota['final_ano_normal'] ? $nota['final_ano_normal'] = $nota['final_ano_normal'] : $nota['final_ano_normal'] = 0;
                    $nota['mejora_q1'] ? $nota['mejora_q1'] = $nota['mejora_q1'] : $nota['mejora_q1'] = 0;
                    $nota['mejora_q2'] ? $nota['mejora_q2'] = $nota['mejora_q2'] : $nota['mejora_q2'] = 0;
                    $nota['final_con_mejora'] ? $nota['final_con_mejora'] = $nota['final_con_mejora'] : $nota['final_con_mejora'] = 0;
                    $nota['supletorio'] ? $nota['supletorio'] = $nota['supletorio'] : $nota['supletorio'] = 0;
                    $nota['remedial'] ? $nota['remedial'] = $nota['remedial'] : $nota['remedial'] = 0;
                    $nota['gracia'] ? $nota['gracia'] = $nota['gracia'] : $nota['gracia'] = 0;
                    $nota['final_total'] ? $nota['final_total'] = $nota['final_total'] : $nota['final_total'] = 0;



                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p1', $nota['p1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p2', $nota['p2'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p3', $nota['p3'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr1', $nota['pr1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr180', $nota['pr180'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex1', $nota['ex1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex120', $nota['ex120'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'q1', $nota['q1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);


                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p4', $nota['p4'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p5', $nota['p5'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p6', $nota['p6'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr2', $nota['pr2'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr280', $nota['pr280'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex2', $nota['ex2'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex220', $nota['ex220'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'q2', $nota['q2'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);

                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_ano_normal', $nota['final_ano_normal'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'mejora_q1', $nota['mejora_q1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'mejora_q1', $nota['mejora_q2'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_con_mejora', $nota['final_con_mejora'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'supletorio', $nota['supletorio'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'remedial', $nota['remedial'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'gracia', $nota['gracia'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_total', $nota['final_total'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                
                    
                } else if($tipoCalif == 1){ //para interdisciplinar
                    
                    $modelGrupoComp = ScholarisGrupoAlumnoClase::find()->where([
                                'estudiante_id' => $alumno['id'],
                                'clase_id' => $claseComportamientoId
                            ])->one();
                    
                    $nota = $this->get_notas_interdisciplinar($modelGrupoComp->id);
                    $notasExamenesYExtras = ScholarisClaseLibreta::find()->where(['grupo_id' => $modelGrupoComp->id])->one();
                    
                    $p1 = 0;
                    $p2 = 0;
                    $p3 = 0;
                    $ex1 = 0;
                    $p4 = 0;
                    $p5 = 0;
                    $p6 = 0;
                    $ex2 = 0;
                    
                    foreach ($nota as $n){
                        $n['orden'] == 1 ? $p1 = $n['sum'] : $p1 = $p1;
                        $n['orden'] == 2 ? $p2 = $n['sum'] : $p2 = $p2;
                        $n['orden'] == 3 ? $p3 = $n['sum'] : $p3 = $p3;
                        $n['orden'] == 5 ? $p4 = $n['sum'] : $p4 = $p4;
                        if($n['orden'] == 6){
                            if($n['sum']){
                                $p5 = $n['sum']; 
                            }else{
                              $p5 = $p5;  
                            }
                           
                        }
                        //$p5 = $p5;
                        $n['orden'] == 7 ? $p6 = $n['sum'] : $p6 = $p6;
                    }
                    
                    $notasExamenesYExtras->ex1 ? $ex1 = $notasExamenesYExtras->ex1 : $ex1 = $ex1;
                    $notasExamenesYExtras->ex2 ? $ex2 = $notasExamenesYExtras->ex2 : $ex2 = $ex2;
                    
                    $pr1 = $sentenciasNotas->truncarNota(($p1+$p2)/2, 2);
                    $pr180 = $sentenciasNotas->truncarNota(($pr1*80)/100, 2);
                    $ex120 = $sentenciasNotas->truncarNota(($ex1*20)/100, 2);
                    $q1 = $sentenciasNotas->truncarNota($pr180+$ex120,2);
                    
                    $pr2 = $sentenciasNotas->truncarNota(($p4+$p5)/2, 2);
                    $pr280 = $sentenciasNotas->truncarNota(($pr2*80)/100, 2);
                    $ex220 = $sentenciasNotas->truncarNota(($ex2*20)/100, 2);
                    $q2 = $sentenciasNotas->truncarNota($pr280+$ex220,2);
                    
                    $final_ano_normal = $sentenciasNotas->truncarNota(($q1+$q2)/2, 2); 
                    
                    $materias = $this->recupera_materias_normales_alumno($alumno['id']);
                    
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'p1', $p1, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'p2', $p2, $this->usuario, $this->paralelo,                                                                 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'p3', $p3, $this->usuario, $this->paralelo,  
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr1', $pr1, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr180', $pr180, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex1', $ex1, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex120', $ex120, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'q1', $q1, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                       
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'p4', $p4, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'p5', $p5, $this->usuario, $this->paralelo,                                                                 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'p6', $p6, $this->usuario, $this->paralelo,  
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr2', $pr2, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr280', $pr280, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex2', $ex2, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex220', $ex220, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                        
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'q2', $q2, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                       
                        $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_ano_normal', $final_ano_normal, $this->usuario, $this->paralelo, 
                                                                $mat['clase_id'], $mat['materia_id'], 
                                                                $mat['area_id'], $mat['total_porcentaje'], $mat['promedia'], 
                                                                $mat['se_imprime']);
                       
                    
                }elseif($tipoCalif == 2) { //para disciplinar
                    
                    $calificacionDisciplinar = new NotasAlumnosCovid($mat['grupo_id']);
                    $notas = $calificacionDisciplinar->arrayNotasQ1;
                    

                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p1', $notas[0]['p1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p2', $notas[0]['p2'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p3', $notas[0]['p3'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr1', $notas[0]['pr1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr180', $notas[0]['pr180'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex1', $notas[0]['ex1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
//                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex120', $notas[0]['ex120'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
//                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'q1', $notas[0]['q1'], $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    /** termina ingreso de notas q1**/
                    
                    /************** comienza ingreso de notas de Q2 ***************/
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p4', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p5', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'p6', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr2', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'pr280', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex2', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'ex220', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'q2', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);

                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_ano_normal', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'mejora_q1', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'mejora_q1', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_con_mejora', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'supletorio', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'remedial', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'gracia', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    
                    $this->inserta_nota_en_procesa_materias($alumno['id'], 'final_total', 0, $this->usuario,
                            $this->paralelo, $mat['clase_id'], $mat['materia_id'], $mat['area_id'],
                            $mat['total_porcentaje'], $mat['promedia'], $mat['se_imprime']);
                    /** termina ingrso de notas Q2 **/
                    
                    
                    
                }
            }

            
            $notasCompProy = new ComportamientoProyectos($alumno['id'], $this->paralelo);
            
            $this->inserta_comportamiento_proyectos($alumno['id'], $notasCompProy->arrayNotasComp[0]['q1'], 0, $notasCompProy->arrayNotasProy[0]['q1'], 0);
        }
    }

    private function inserta_comportamiento_proyectos($alumnoId, $compQ1, $compQ2, $proyQ1, $proyQ2) {

//        print_r($proyQ1);
//        die();
//        
//        if($compQ2 == 0){
//           $compQ2 == 'E'; 
//        }
//        if($proyQ2 == 0){
//           $proyQ2 == 'E'; 
//        }
        
        $proyectos1 = $proyQ1['abreviatura']; 
        $proyectos2 = $proyQ2['abreviatura']; 


        $con = \Yii::$app->db;

        $query = "insert into scholaris_proceso_comportamiento_y_proyectos values('$this->usuario', $this->paralelo, $alumnoId, '$compQ1', '$compQ2', '$proyectos1', '$proyectos2')";

//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }

    private function inserta_nota_en_procesa_materias($alumnoId, $bloque, $nota, $usuario,
            $paralelo, $claseId, $materiaId, $areaId,
            $porcentaje, $promedia, $imprime) {
        if ($promedia) {
            $promedia = $promedia;
        } else {
            $promedia = 0;
        }

        $con = \Yii::$app->db;

        $query = "insert into scholaris_proceso_materias values('$usuario', $paralelo, $alumnoId, $claseId, $materiaId, $areaId, $porcentaje, $promedia, $imprime, '$bloque', $nota)";
//        echo $query;
//        die();
        $con->createCommand($query)->execute();
    }

    private function vaciar_notas_usuario_paralelo() {
        $con = \Yii::$app->db;
        $queryDeleteMaterias = "delete from scholaris_proceso_materias where usuario = '$this->usuario' and paralelo_id = $this->paralelo;";
        $queryDeleteAreas = "delete from scholaris_proceso_areas where usuario = '$this->usuario' and paralelo_id = $this->paralelo;";
        $queryDeletePromedios = "delete from scholaris_proceso_promedios where usuario = '$this->usuario' and paralelo_id = $this->paralelo;";
        $queryDeleteComportamientoProyectos = "delete from scholaris_proceso_comportamiento_y_proyectos where usuario = '$this->usuario' and paralelo_id = $this->paralelo;";
        $con->createCommand($queryDeleteMaterias)->execute();
        $con->createCommand($queryDeleteAreas)->execute();
        $con->createCommand($queryDeletePromedios)->execute();
        $con->createCommand($queryDeleteComportamientoProyectos)->execute();
    }

    private function recupera_materias_normales_alumno($alumnoId) {
        $con = \Yii::$app->db;
        $query = "select 	g.id as grupo_id
		,c.id as clase_id 
		,mm.materia_id 
		,mm.malla_area_id 
                ,ma.area_id
                ,mm.total_porcentaje
		,mm.promedia 
		,mm.se_imprime
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                inner join scholaris_malla_area ma on ma.id = mm.malla_area_id
where 	c.periodo_scholaris = '$this->periodoCodigo'
		and g.estudiante_id = $alumnoId
		and mm.tipo in ('NORMAL','OPTATIVAS');";
        
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
