<?php

namespace backend\models;

use Mpdf\Mpdf;
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
class InformeAprendizajeIniciales extends \yii\db\ActiveRecord {

    public function genera_reporte($paralelo, $quimestre, $alumno) {
        

        //invocacion a clases
        $sentencias = new SentenciasAlumnos();

        $codigoDestrezas = $this->toma_codigos_destrezas($paralelo, $quimestre);


        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 35,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 10,
        ]);

        $cabecera = $this->cabecera($paralelo, $quimestre);
        $mpdf->SetHTMLHeader($cabecera);
        $mpdf->showImageErrors = true;

        if($alumno == ''){
            $modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        }else{
            $modelAlumnos = $sentencias->get_alumnos_paralelo_alumno($paralelo, $alumno);
        }
        
        
        //Para tomar el cuerpo de la libreta       

        foreach ($modelAlumnos as $alumno) {
            $html = $this->html($alumno, $paralelo, $quimestre, $codigoDestrezas);
//            $html = 'ola k ase';

            $mpdf->WriteHTML($html);
            $mpdf->addPage();
        }

        $mpdf->Output('Informe-Aprendizaje' . "curso" . '.pdf', 'D');
        exit;
    }

    protected function cabecera($paralelo, $quimestre) {

        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        $html = '';

        if ($quimestre == 'QUIMESTRE I') {

            $html .= '<table width="100%" cellspacing="0" style="font-size:12px">';
            $html .= '<tr>';
            $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
            $html .= '<td align="center">' . $modelParalelo->institute->name;
            $html .= '<br>INFORME QUIMESTRAL DE APRENDIZAJE';
            $html .= '<br>AÑO LECTIVO ' . $modelPeriodo->codigo;
            $html .= '<br>PRIMER QUIMESTRE';
            $html .= '</td>';
            $html .= '<td align="center" width="20%"></td>';
            $html .= '<tr>';
            $html .= '</table>';
        } else {
            $html .= '<table width="100%" cellspacing="0" style="font-size:12px">';
            $html .= '<tr>';
            $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
            $html .= '<td align="center">' . $modelParalelo->institute->name;
            $html .= '<br>INFORME FINAL DE APRENDIZAJE';
            $html .= '<br>AÑO LECTIVO ' . $modelPeriodo->codigo;
            $html .= '</td>';
            $html .= '<td align="center" width="20%"></td>';
            $html .= '<tr>';
            $html .= '</table>';
        }

        return $html;
    }

    protected function html($modelAlumno, $paralelo, $quimestre, $codigoDestrezas) {

        $html = '<style>';
        $html .= '.tamano10{font-size: 10px;}';
        $html .= '.tamano8{font-size: 8px;}';
        $html .= '.conBorde{border: 0.1px solid #CCCCCC;}';
        $html .= '.colorEtiqueta{background-color:#D7E5E5;}';
        $html .= '</style>';

        $html .= $this->datos_libreta($modelAlumno, $paralelo);
        $html .= $this->cuadro_calificaciones($modelAlumno, $paralelo, $quimestre, $codigoDestrezas);


        return $html;
    }

    private function datos_libreta($modelAlumno, $paralelo) {

        $modelAl = OpStudent::findOne($modelAlumno['id']);
        $modelPa = OpCourseParalelo::findOne($paralelo);

        $html = '';

        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="" colspan="" align="" width="120px"><strong>ESTUDIANTE: </strong></td>';
        $html .= '<td><strong>' . $modelAl->last_name . ' ' . $modelAl->first_name . ' ' . $modelAl->middle_name . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2"><strong>' . $modelPa->course->name . ' - ' . $modelPa->name . '<strong></td>';
        $html .= '</tr>';
        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function cuadro_calificaciones($modelAlumno, $paralelo, $quimestre, $codigoDestrezas) {
        $tamanoejes = array();
        if ($quimestre == 'QUIMESTRE I') {
            array_push($tamanoejes, '85%');
            array_push($tamanoejes, '15%');
        } else {
            array_push($tamanoejes, '70%');
            array_push($tamanoejes, '15%');
            array_push($tamanoejes, '15%');
        }

        $html = '';

        $html .= '<table width="100%" cellspacing="0" cellpadding="5" class="tamano10">';
        $html .= $this->detalle_ejes($modelAlumno['id'], $paralelo, $quimestre, $tamanoejes, $codigoDestrezas);
        $html .= '</table>';        
        
        $html .= $this->toma_comportamiento($modelAlumno['id'], $quimestre, $paralelo);
        
        $html .= $this->cuadro_atrasos($modelAlumno['id'], $quimestre, $paralelo);
        
        $html .= $this->firmas();

        return $html;
    }
    
    private function toma_comportamiento($alumno, $quimestre, $paralelo){
        $html = '';
        
        $campo = $quimestre == 'QUIMESTRE I' ? 'q1' : 'q2';
        
        $notas = $this->consulta_calificacion($alumno, $paralelo);
        
        isset($notas[$campo]) ? $notaComp = $notas[$campo] : $notaComp = 'E';
        
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="5" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="colorEtiqueta"><strong>COMPORTAMIENTO:</strong></td>';
        $html .= '<td class="colorEtiqueta"><strong>'.$notaComp.'</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        
        return $html;
    }
    
    
    private function consulta_calificacion($alumno, $paralelo){
        $con = \Yii::$app->db;
        $query = "select 	q1,q2
                    from 	op_student_inscription i 
                                    inner join scholaris_comportamiento_inicial c on c.inscription_id = i.id
                    where 	i.student_id = $alumno 
                                    and i.parallel_id = $paralelo;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }

    private function detalle_ejes($alumno, $paralelo, $quimestre, $arregloTamanos, $codigoDestrezas) {
        $html = '';
        $modelAmbitos = $this->toma_ambitos($paralelo);

        foreach ($modelAmbitos as $ambito) {
            $html .= '<tr>';
            $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center" width="' . $arregloTamanos[0] . '"><strong>('.$ambito['eje'].') - '.$ambito['name'].'</strong></td>';            
            if ($quimestre != 'QUIMESTRE I') {
                $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center" width="' . $arregloTamanos[1] . '"><strong>2do QUIM.</strong></td>';
            }else{
                $html .= '<td class="conBorde colorEtiqueta" colspan="" align="center" width="' . $arregloTamanos[1] . '"><strong>1er QUIM.</strong></td>';
            }            
            $html .= '</tr>';
            
            $notas = $this->toma_calificacion_destreza($alumno, $ambito['id'], $quimestre);
            
            foreach ($notas as $nota){
                $html .= '<tr>';
                $html .= '<td class="conBorde" colspan="" align="" width="' . $arregloTamanos[0] . '">'.$nota['destreza_desagregada'].'</td>';
                $html .= '<td class="conBorde" colspan="" align="center" width="' . $arregloTamanos[1] . '">'.$nota['calificacion'].'</td>';
                $html .= '</tr>';
            }
            
        }



        return $html;
    }
    
    
    private function toma_calificacion_destreza($alumno, $clase, $quimestre){
        $con = Yii::$app->db;
        $query = "select 	i.destreza_desagregada
                                    ,n.calificacion
                    from 	scholaris_calificaciones_inicial n
                                    inner join scholaris_plan_inicial i on i.id = n.plan_id
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                    where	g.estudiante_id = $alumno
                                    and c.id = $clase
                                    and n.creado_fecha = (select max(creado_fecha) from scholaris_calificaciones_inicial where plan_id = n.plan_id and grupo_id = g.id)
                                    and i.quimestre_codigo = '$quimestre'
                    order by i.orden;";
        
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    

    private function toma_ambitos($paralelo) {
        $con = Yii::$app->db;
        $query = "select 	c.id
		,m.name
                ,a.name as eje
from	scholaris_clase c
		left join scholaris_malla_materia mm on mm.id = c.malla_materia
		left join scholaris_malla_area ma on ma.id = mm.malla_area_id
		left join scholaris_area a on a.id = ma.area_id
		left join scholaris_materia m on m.id = mm.materia_id
		left join scholaris_plan_inicial p on p.clase_id = c.id
where	c.paralelo_id = $paralelo
        and ma.tipo <> 'COMPORTAMIENTO'
group by c.id, m.name, ma.orden, a.name
order by ma.orden;";
        
//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function toma_codigos_destrezas($paralelo, $quimestre) {
        $con = Yii::$app->db;
        $query = "select 	i.codigo_destreza 
                    from 	scholaris_clase c
                                    inner join scholaris_plan_inicial i on i.clase_id = c.id
                    where	c.paralelo_id = $paralelo
                                    and i.quimestre_codigo = '$quimestre'
                    group by i.codigo_destreza;";
        $res = $con->createCommand($query)->queryAll();

        $arreglo_codigo_destrezas = '';


        foreach ($res as $r) {
            //array_push($arreglo_codigo_destrezas,"'".$r['codigo_destreza']."',");
            $arreglo_codigo_destrezas .= "'" . $r['codigo_destreza'] . "',";
        }

        //array_push($arreglo_codigo_destrezas,"''");
        $arreglo_codigo_destrezas .= "''";

        return $arreglo_codigo_destrezas;
    }
    
    
    
    private function cuadro_atrasos($alumno, $quimestre, $paralelo){
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        
        $tipoUso = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        
        
        $bloqueMax = ScholarisBloqueActividad::find()
                    ->where([
                        'tipo_uso' => $tipoUso->tipo_usu_bloque,
                        'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                        'quimestre' => $quimestre,
                        'tipo_bloque' => 'PARCIAL'
                    ])
                    ->orderBy(['orden'=>SORT_DESC])
                    ->one();
//                
//        $modelFaltas = ScholarisFaltasYAtrasosParcial::find()
//                       ->where([
//                                'alumno_id' => $alumno,
//                                'bloque_id' => $bloqueMax->id
//                               ])
//                       ->one();  
               
            $bloques = ScholarisBloqueActividad::find()
                    ->where([
                        'tipo_uso' => $tipoUso->tipo_usu_bloque,
                        'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                        'quimestre' => $quimestre,
                        //'tipo_bloque' => 'PARCIAL'
                    ])
                    ->orderBy(['orden'=>SORT_ASC])
                    ->all();
        
            $atrasos = 0;
            $fj = 0;
            $fi = 0;
            
            foreach ($bloques as $bloque){
                $modelFaltas = ScholarisFaltasYAtrasosParcial::find()
                       ->where([
                                'alumno_id' => $alumno,
                                'bloque_id' => $bloque->id
                               ])
                       ->one();
                
                
               if(isset($modelFaltas)){
                    $atrasos = $atrasos + $modelFaltas->atrasos;
                    $fj      = $fj + $modelFaltas->faltas_justificadas;
                    $fi      = $fi + $modelFaltas->faltas_injustificadas;
                }
            }
        
        
        $modelBloques = ScholarisBloqueActividad::find()->where([
                                        'tipo_uso' => $tipoUso->tipo_usu_bloque,
                                        'quimestre' => $quimestre,
                                        'scholaris_periodo_codigo' => $modelPeriodo->codigo
            ])->all();
        
        $totalDias = 0;
        foreach ($modelBloques as $bloque){
            $totalDias = $totalDias + $bloque->dias_laborados;
        }
        
        $presentes = $totalDias - $fj - $fi;
        
        $html = "";
        
        $html .= '<br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="50%" class="conBorde colorEtiqueta" align=""><strong>DETALLE - ASISTENCIAS</strong></td>';
        $html .= '<td class="conBorde colorEtiqueta" align="center"><strong>TOTAL</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align=""><strong>Presentes:</strong></td>';
        $html .= '<td class="conBorde" align="center"><strong>'.$presentes.'</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align=""><strong>Faltas Justificadas:</strong></td>';
        $html .= '<td class="conBorde" align="center"><strong>'.$fj.'</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align=""><strong>Faltas Injustificadas:</strong></td>';
        $html .= '<td class="conBorde" align="center"><strong>'.$fi.'</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="conBorde colorEtiqueta" align=""><strong>Atrasos:</strong></td>';
        $html .= '<td class="conBorde" align="center"><strong>'.$atrasos.'</strong></td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        $html .= '<br><br>';
        
        if(isset($bloqueMax->id)){
            $modelObservacion = ScholarisFaltasYAtrasosParcial::find()
                       ->where([
                                'alumno_id' => $alumno,
                                'bloque_id' => $bloqueMax->id
                               ])
                       ->one(); 
            
            
            if(isset($modelObservacion->observacion)){
                $observacion = $modelObservacion->observacion;
            }else{
                $observacion = '';
            }
            
        }else{
            $observacion = '';
        }
        
        $html .= '<p class="tamano10"><strong>OBSERVACIONES: </strong>'.$observacion .'</p>';
        
        return $html;
    }
    

    private function firmas() {

        $html = '';
        $html .= '<br><br><br>';

        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td class="" align="center"><strong>_______________________________________________</strong></td>';        
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="" align="center"><strong>TUTOR / A</strong></td>';
        $html .= '</tr>';

        $html .= '</table>';

        return $html;
    }

}
