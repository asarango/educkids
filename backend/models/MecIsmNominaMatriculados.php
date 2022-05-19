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
class MecIsmNominaMatriculados extends \yii\db\ActiveRecord {

    private $paralelo;
    private $modelParalelo;
    private $periodoId;
    private $periodoCodigo;
    private $tieneProyectos = 0;
    private $modelAlumnos;
    private $usuario;
    private $modelBloquesQ1;
    private $seccion;
    private $comportamientoAutomatico = 0;
    private $tipoCalificacionProyectos = 'PROYECTOSNORMAL';
    private $tipoCalificacion;
    private $mallaMecId;
    private $arrayMaterias;
    private $arrayAreas;
    private $escala;
    private $promedio;
    private $totalBajos;

    public function __construct($paralelo) {
        $this->paralelo = $paralelo;

        /*         * ** Periodo actual ** */
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        ///// FIN DE PERIODO

        /**
         * para tomar tipo de calificacion
         */
        $modelTipoCalificacion = ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->valor;
        //////// fin de tipo de calificacion /////////////

        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        $this->seccion = $this->modelParalelo->course->section0->code;


        $modelMalla = ScholarisMecV2MallaCurso::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->mallaMecId = $modelMalla->malla_id;


        $this->tieneProyectos = $this->tiene_proyectos(); //llama a funcion para buscar si tiene proyectos

        $this->usuario = Yii::$app->user->identity->usuario;  //usuario que esta con login

        $this->modelAlumnos = $sentencias->get_alumnos_paralelo_todos($paralelo); // toma estudiantes del paralelo

        /*         * * para el uso del bloque ** */
        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;
        //// fin del uso del bloque

        /*         * *** verifica si tiene comportamiento automatico *** */
        $modelComportamientoParam = ScholarisParametrosOpciones::find()->where(['codigo' => 'comportamiento'])->one();
        $this->comportamientoAutomatico = $modelComportamientoParam->valor;
        //// FIN DE VERIFICACION DE COMPORTAMIENTO AUTOMATICO ///////


        /*         * ********** para ver tipo de proyectos ******** */
        $modelTipoProyectos = ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $this->modelParalelo->course_id])->one();
        $this->tipoCalificacionProyectos = $modelTipoProyectos->tipo_proyectos;
        /////////////////////////////////////////////////////////////////////////////

        $this->modelBloquesQ1 = ScholarisBloqueActividad::find()->where([
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'scholaris_periodo_codigo' => $this->periodoCodigo,
                    'tipo_bloque' => 'PARCIAL'
                ])->orderBy('orden')
                ->all();

       // $this->get_materias_normales();  ///para poblar variable con arreglo de las materias
        ///para poblar variable de escala
        $parametros = ScholarisParametrosOpciones::find()->where(['codigo' => 'scala'])->one();
        $this->escala = $parametros->valor;
        ////// fin de escala /////////////////////////////

        $this->genera_reporte_pdf();
    }

    private function tiene_proyectos() {
        $cursoId = $this->modelParalelo->course_id;

        $con = Yii::$app->db;
        $query = "select 	count(ma.id) as total 
from 	scholaris_mec_v2_malla_curso c
		inner join scholaris_mec_v2_malla_area ma on ma.malla_id = c.malla_id
where	c.curso_id = $cursoId
		and ma.tipo = 'PROYECTOS';";

        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }

    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 30,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera();
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;


        $html = $this->genera_cuerpo('NÓMINA DE MATRICULADOS', 'final_total');
        $mpdf->WriteHTML($html);


        $mpdf->Output('MEC-Matriculados' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';

        $html .= '<table style="font-size:16px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="10%"></td>';
        $html .= '<td align="left"></td>';
        $html .= '<td align="right" width="10%"><img src="imagenes/instituto/mec/educacion_nuevo.png" width="190"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="left" width="10%"></td>';

        $html .= '<td class="centrarTexto" style="font-size:16px">';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong>';
        $html .= '</td>';

        $html .= '<td align="right" width="10%"></td>';
        $html .= '</tr>';
        $html .= '</table>';
//        $html .= '<hr>';

        return $html;
    }

    private function genera_cuerpo($titulo, $quimestre) {

        $html = '';

        $html .= '<style>';
        '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= 'td {
                    border-collapse: collapse;
                    border: 1px black solid;
                  }
                  tr:nth-of-type(5) td:nth-of-type(1) {
                    visibility: hidden;
                  }
                  .rotate {
                    /* FF3.5+ */
                    -moz-transform: rotate(-90.0deg);
                    /* Opera 10.5 */
                    -o-transform: rotate(-90.0deg);
                    /* Saf3.1+, Chrome */
                    -webkit-transform: rotate(-90.0deg);
                    /* IE6,IE7 */
                    filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
                    /* IE8 */
                    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
                    /* Standard */
                    transform: rotate(-90.0deg);
                  }';
        $html .= '.bordesolido{border: 0.2px solid #000;}';
        $html .= '.tamano12{font-size:12px;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';


        $html .= '<table class="tamano12 centrarTexto" width="100%">';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<strong>' . $titulo . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO ' . $this->periodoCodigo . '</strong><br>';
        $html .= '<strong>' . $this->modelParalelo->course->xTemplate->name .'</strong><br>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->procesa_nomina();

        return $html;
    }

    private function firmas() {
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $modelInstituto = OpInstitute::findOne($institutoId);

        $html = '';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';






        $html .= '<table width="100%" height="300" cellpadding="0" cellspacing="0" class="tamano8">';
        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>_________________________________________</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>_________________________________________</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelInstituto->rector . '</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>' . $modelInstituto->secretario . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td width="45%" class="centrarTexto"><strong>RECTOR(A)</strong></td>';
        $html .= '<td width="10%" class=""></td>';
        $html .= '<td width="45%" class="centrarTexto"><strong>SECRETARIO(A)</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<div class="centrarTexto"><img src="imagenes/instituto/logo/sellolibreta.png" width="100px"></div>';


        return $html;
    }

    private function procesa_nomina() {

        $html = '';

        $html .= '<br><br><br>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td><strong>JORNADA MATUTINA</strong></td>';
        $html .= '<td align="right"><strong>PARALELO:</strong> "'.$this->modelParalelo->name.'"</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" cellspacing="0" cellpadding="2" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td rowspan="" class="bordesolido centrarTexto"><strong>ORD</strong></td>';
        $html .= '<td rowspan="" class="bordesolido centrarTexto" width="250px"><strong>APELLIDOS Y NOMBRES</strong></td>';
        $html .= '<td colspan="" class="bordesolido centrarTexto"><strong>MATRÍCULA</strong></td>';
        $html .= '<td colspan="" class="bordesolido centrarTexto"><strong>FOLIO</strong></td>';
        $html .= '<td colspan="" class="bordesolido centrarTexto"><strong>OBSERVACIONES</strong></td>';
        $html .= '</tr>';

        $html .= $this->lista_estudiantes();


        $html .= '</table>';
        
        
        $html.= $this->firmas1();
        
        return $html;
    }
    
    public function lista_estudiantes(){
        $html = '';
        
        $i=0;
        foreach ($this->modelAlumnos as $alumno){
            $i++;
            $html .= '<tr>';            
            $html .= '<td rowspan="" class="bordesolido centrarTexto">'.$i.'</td>';
            $html .= '<td rowspan="" class="bordesolido">'.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'] .'</td>';
            
            $matricula = $this->toma_matricula_estado($alumno['id']);
            
            isset($matricula['matricula']) ? $matri = $matricula['matricula'] : $matri = '-';
            
            $html .= '<td class="bordesolido centrarTexto">'.$matri .'</td>';
            $html .= '<td class="bordesolido centrarTexto">'.$matri .'</td>';
            
            isset($matricula['inscription_state']) ? $inscription = $matricula['inscription_state'] : $inscription = 'R';
            
            if($inscription == 'M'){
                $observacion = '';
            }else if($inscription == 'R'){
                $observacion = 'RETIRADO';
            }
            
            $html .= '<td class="bordesolido centrarTexto">'.$observacion .'</td>';
            
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    
    public function toma_matricula_estado($alumnoId){
        $con = \Yii::$app->db;
        $query = "select 	substring(e.name,4)  as matricula
		,i.inscription_state 
from 	op_student_inscription i
		inner join op_student_enrollment e on e.inscription_id = i.id
		inner join op_period op on op.id = i.period_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = op.id 
		inner join scholaris_periodo p on p.id = sop.scholaris_id 
where	i.student_id = $alumnoId
		and p.id = $this->periodoId;";
        
        $res = $con->createCommand($query)->queryOne();
        return $res;
        
    }

public function firmas1(){
    
        $instituto = \Yii::$app->user->identity->instituto_defecto;
    
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
            'codigo_reporte' => 'MEC',
            'template_id' => $this->modelParalelo->course->xTemplate->id,
            'instituto_id' => $instituto
        ])->one();
        
        
      
    
    
        $html = '';
        $html .= '<br><br><br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelFirmas->principal_nombre . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelFirmas->secretaria_nombre . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelFirmas->principal_cargo . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelFirmas->secretaria_cargo . '</strong></td>';
        $html .= '</tr>';

        $html .= '</table>';


        return $html;
    }


}
