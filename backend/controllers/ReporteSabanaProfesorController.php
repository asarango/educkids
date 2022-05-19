<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ReporteSabanaProfesorController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//    public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
//                echo $this->render('/site/error', [
//                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public $promedioQ1 = 0;
    public $promedioQ2 = 0;
    private $arrayTodosPromedios = array();
    private $arrayTodosPromediosQ2 = array();

    public function actionIndex1($id) {

        if (!isset(\Yii::$app->user->identity->periodo_id)) {
            echo 'Su sesión expiró!!!';
            echo \yii\helpers\Html::a(' Iniciar Sesión', ['site/index']);
            die();
        }


        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $model = \backend\models\ScholarisClase::find()->where(['id' => $id])->one();
        $uso = $model->tipo_usu_bloque;

        $modelRindeSupletorio = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $model->idcurso])->one();

        $modelLibreta = \backend\models\ScholarisClaseLibreta::find()
                ->innerJoin("scholaris_grupo_alumno_clase", "scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id")
                ->innerJoin("op_student", "op_student.id = scholaris_grupo_alumno_clase.estudiante_id")
                ->innerJoin("op_student_inscription i", "i.student_id = scholaris_grupo_alumno_clase.estudiante_id and i.student_id = op_student.id")
                ->innerJoin("scholaris_op_period_periodo_scholaris sop", "sop.op_id = i.period_id")
                ->innerJoin("scholaris_periodo p", "p.id = sop.scholaris_id")
                ->innerJoin("op_course_paralelo pa", "pa.id = i.parallel_id")
                ->where(["scholaris_grupo_alumno_clase.clase_id" => $id, "p.id" => $periodoId])
                ->orderBy([
                    'op_student.last_name' => SORT_ASC,
                    'op_student.first_name' => SORT_ASC
                ])
                ->all();


        $modelMinimo = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaminima'])
                ->one();
        $minima = $modelMinimo->valor;

        $modelRemedial = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'notaRemed'])
                ->one();
        $remedial = $modelRemedial->valor;


        $modelBloqueQ1 = \backend\models\ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL',
                    'estado' => 'activo',
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $uso,
                    'instituto_id' => $institutoId
                ])->orderBy('orden')->all();

        $modelBloqueQ2 = \backend\models\ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL',
                    'estado' => 'activo',
                    'quimestre' => 'QUIMESTRE II',
                    'tipo_uso' => $uso,
                    'instituto_id' => $institutoId
                ])->orderBy('orden')->all();


        $modelCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $modelPeriodo->id])->one();
        $tipoCalificacion = $modelCalificacion->codigo;


        return $this->render('index', [
                    'model' => $model,
                    'modelLibreta' => $modelLibreta,
                    'minima' => $minima,
                    'remedial' => $remedial,
                    'modelRindeSupletorio' => $modelRindeSupletorio,
                    'modelBloqueQ1' => $modelBloqueQ1,
                    'modelBloqueQ2' => $modelBloqueQ2,
                    'periodoId' => $periodoId,
                    'tipoCalificacion' => $tipoCalificacion
        ]);
    }

    private function datos_libreta($claseId) {
        $con = Yii::$app->db;
        $query = "";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function actionCalcular($clase) {
        //echo $clase;

        $sentencias = new \backend\models\NotasEnLibreta();
        $sentencias->calcula_promedios_clase($clase);

        return $this->redirect(['index1', 'id' => $clase]);
    }

    public function actionPdf($clase) {



        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelTipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $modelPeriodo->id])->one();
        $tipoCalif = $modelTipoCalificacion->codigo;

//        $tipoCalif = $modelCalificacion->valor;

        $modelClase = \backend\models\ScholarisClase::find()->where(['id' => $clase])->one();

        $modelBloqueQ1 = \backend\models\ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL',
                    'estado' => 'activo',
                    'quimestre' => 'QUIMESTRE I',
                    'tipo_uso' => $modelClase->tipo_usu_bloque
                ])->orderBy('orden')->all();

        $modelBloqueQ2 = \backend\models\ScholarisBloqueActividad::find()->where([
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL',
                    'estado' => 'activo',
                    'quimestre' => 'QUIMESTRE II',
                    'tipo_uso' => $modelClase->tipo_usu_bloque
                ])->orderBy('orden')->all();



        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $cabecera = $this->genera_cabecera_pdf($modelClase);
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHeader($cabecera);
        $mpdf->SetFooter($pie);
        $mpdf->showImageErrors = true;

        $html = $this->cuerpoPdf($modelClase, $modelBloqueQ1, $modelBloqueQ2, $tipoCalif);

        $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));

        $mpdf->Output('Sábana Profesor' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($modelClase) {

        $html = '<table width="100%">';
        $html .= '<tr>';
        $html .= '<td width="30%"><img src="imagenes/instituto/logo/logo2.png" width="50px"></td>';

        $html .= '<td><center>';
        $html .= '<p>' . $modelClase->curso->xInstitute->name . '</p>';
        $html .= '<p style="font-size:10px">Sábana de calificaciones</p>';
        $html .= '</center>';

        $html .= '<td width="30%" style="text-align: right;">';
        $html .= '<p style="font-size:8px">' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name . '</p>';
        $html .= '<p style="font-size:7px">Año lectivo: ' . $modelClase->periodo_scholaris . '</p>';

        $html .= '</tr>';
        $html .= '</table>';

        return $html;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function cuerpoPdf($modelClase, $modelBloqueQ1, $modelBloqueQ2, $tipoCalif) {
        $html = '';

        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.3px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano6{
                    font-size: 6px;
                  }
                  
                  .tamano8{
                    font-size: 8px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .fondoCeleste{
                    background: #C2EBF8;
                }
                
                .fondoPlomo{
                    background: #E4E4E4;
                }
                
                .padding2{
                    padding: 5px;
                }

                    ';
        $html .= '</style>';

        $html .= '<table class="conBorde tamano10 fondoCeleste" width="100%">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto"><strong>ASIGNATURA</strong></td>';
        $html .= '<td class="centrarTexto"><strong>PROFESOR</strong></td>';
        $html .= '<td class="centrarTexto"><strong>PROMEDIA</strong></td>';
        $html .= '<td class="centrarTexto"><strong>PESO %</strong></td>';
        $html .= '<td class="centrarTexto"><strong>CUANTITATIVA</strong></td>';
        $html .= '<td class="centrarTexto"><strong>TIPOP</strong></td>';
        $html .= '<td class="centrarTexto"><strong>ORDEN</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->materia->name . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->materia->promedia . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->total_porcentaje . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->es_cuantitativa . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->tipo . '</td>';
        $html .= '<td class="centrarTexto">' . $modelClase->mallaMateria->orden . '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $html .= $this->detallePdf($modelClase->id, $modelBloqueQ1, $modelBloqueQ2, $tipoCalif);

        return $html;
    }

    private function detallePdf($clase, $modelBloqueQ1, $modelBloqueQ2, $tipoCalificacion) {

        if ($tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasNormales();
        } elseif ($tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasDisciplinar();
        } elseif ($tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }

//
//        $sentencias = new \backend\models\SentenciasNotas();
//
//        $periodoId = Yii::$app->user->identity->periodo_id;
//        $modelEscala = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'scala'])->one();
//        $escala = $modelEscala->valor;
//        $modelClase = \backend\models\ScholarisClase::findOne($clase);

        $html = '';

        $html .= '<br><br>';

        $html .= '<table class="tamano8" width="100%" cellpadding="0" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>ORD:</strong></td>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>ESTUDIANTES:</strong></td>';
        if (count($modelBloqueQ1) > 2) {
            $html .= '<td class="conBorde centrarTexto" colspan="8"><strong>QUIMESTRE I</strong></td>';
        } else {
            $html .= '<td class="conBorde centrarTexto" colspan="7"><strong>QUIMESTRE I</strong></td>';
        }

        if (count($modelBloqueQ2) > 2) {
            $html .= '<td class="conBorde centrarTexto" colspan="8"><strong>QUIMESTRE II</strong></td>';
        } else {
            $html .= '<td class="conBorde centrarTexto" colspan="7"><strong>QUIMESTRE II</strong></td>';
        }


        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>QFINAL</strong></td>';
        $html .= '<td class="conBorde centrarTexto" colspan="6"><strong>EXTRAS</strong></td>';
        $html .= '<td class="conBorde centrarTexto" rowspan="2"><strong>FINAL</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        foreach ($modelBloqueQ1 as $bloq1) {
            $html .= '<td class="conBorde centrarTexto"><strong>' . $bloq1->abreviatura . '</strong></td>';
        }

        $html .= '<td class="conBorde centrarTexto"><strong>PR</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>80</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>EX</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>Q1</strong></td>';

        foreach ($modelBloqueQ2 as $bloq2) {
            $html .= '<td class="conBorde centrarTexto"><strong>' . $bloq2->abreviatura . '</strong></td>';
        }
        $html .= '<td class="conBorde centrarTexto"><strong>PR</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>80</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>EX</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>20</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>Q2</strong></td>';

        $html .= '<td class="conBorde centrarTexto"><strong>REC1</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>REC2</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>FREC</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>SUP</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>REM</strong></td>';
        $html .= '<td class="conBorde centrarTexto"><strong>GRA</strong></td>';
        $html .= '</tr>';


//        $modelNotas = \backend\models\ScholarisClaseLibreta::find()
//                ->innerJoin("scholaris_grupo_alumno_clase","scholaris_grupo_alumno_clase.id = scholaris_clase_libreta.grupo_id")
//                ->innerJoin("op_student","op_student.id = scholaris_grupo_alumno_clase.estudiante_id")
//                ->where(["scholaris_grupo_alumno_clase.clase_id" => $clase])
//                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
//                ->all();

        $modelNotas = $this->get_toma_notas_libreta($clase);

        $i=0;
        $suma = 0;
        $suma2 = 0;
        $sumaF = 0;
        $sumaFN = 0;
        $cont = 0;
        foreach ($modelNotas as $alumno) {

            if ($alumno['inscription_state'] == 'M') {
                $color = "";
            } else {
                $color = "#FA8B8B";
            }

            $i++;

            $html .= '<tr>';

            $html .= '<td class="conBorde centrarTexto" bgcolor="' . $color . '">' . $i . '</td>';
            $html .= '<td class="conBorde" bgcolor="' . $color . '">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';

            $notasM = $sentenciasNotasAlumnos->get_nota_materia($alumno['grupo_id']);
            array_push($this->arrayTodosPromedios, $notasM);

            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['p1'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['p2'] . '</strong></td>';

            if (count($modelBloqueQ1) > 2) {
                $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['p3'] . '</strong></td>';
            }
//
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['pr1'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['pr180'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['ex1'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['ex120'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['q1'] . '</strong></td>';                        

            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['p4'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['p5'] . '</strong></td>';

            if (count($modelBloqueQ2) > 2) {
                $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['p6'] . '</strong></td>';
            }

            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['pr2'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['pr280'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="' . $color . '"><strong>' . $notasM['ex2'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['ex220'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['q2'] . '</strong></td>';
            
            //$suma2 = $suma2 + $notasM['q2'];

            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['final_ano_normal'] . '</strong></td>';

            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['mejora_q1'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['mejora_q2'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['final_con_mejora'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['supletorio'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['remedial'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $notasM['gracia'] . '</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $notasM['final_total'] . '</strong></td>';
            //$sumaF = $sumaF + $notasM['final_total'];
            $html .= '</tr>';
            
            
            if ($alumno['inscription_state'] == 'M') {
                $suma   = $suma     + $notasM['q1'];
                $suma2  = $suma2    + $notasM['q2'];
                $sumaFN = $sumaF    + $notasM['final_ano_normal'];
                $sumaF  = $sumaF    + $notasM['final_total'];
                $cont++;
            }
            
        }
        
        $html .= $this->promediosPdf($clase, $modelBloqueQ1, $modelBloqueQ2, $modelNotas, $suma, $suma2, $sumaF, $sumaFN, $cont);
        $html .= $this->cuadroPdf($clase, $modelBloqueQ1, $modelBloqueQ2, $tipoCalificacion);

        $html .= '</table>';


        return $html;
    }

    private function get_toma_notas_libreta($clase) {
        $periodo = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	s.id
                                ,g.id as grupo_id
                                ,s.last_name
                                ,s.first_name
                                ,s.middle_name
                                ,i.parallel_id
                                ,i.inscription_state
                                ,l.p1
                                ,l.p2
                                ,l.p3
                                ,l.pr1
                                ,l.pr180
                                ,l.ex1
                                ,l.ex120
                                ,l.q1
                                ,l.p4
                                ,l.p5
                                ,l.p6
                                ,l.pr2
                                ,l.pr280
                                ,l.ex2
                                ,l.ex220
                                ,l.q2
                                ,l.final_ano_normal
                                ,l.mejora_q1, l.mejora_q2, l.final_con_mejora
                                ,l.supletorio, l.remedial, l.gracia 
                                ,l.final_total 
                from	scholaris_grupo_alumno_clase g
                                inner join op_student_inscription i on i.student_id = g.estudiante_id
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
                                inner join scholaris_periodo sp on sp.id = sop.scholaris_id
                                inner join scholaris_clase_libreta l on l.grupo_id = g.id
                                inner join op_student s on s.id = i.student_id
                where	g.clase_id = $clase
                                and sp.id = $periodo
                order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function promediosPdf($clase, $modelBloqueQ1, $modelBloqueQ2, $modelNotas, $suma, $suma2, $sumaF, $sumaFA, $i) {

        $sentencias = new \backend\models\NotasEnLibreta();
        $sentenciasN = new \backend\models\Notas();
        $digito = 2;

        $html = '';

//        $nota = $this->toma_promedios_clase($clase, $modelNotas);
        //$nota = $sentencias->promedios_clase($clase);

        $q1 = $sentenciasN->truncarNota(($suma / $i), $digito);
        $q2 = $sentenciasN->truncarNota(($suma2 / $i), $digito);
        $finalNormal = $sentenciasN->truncarNota(($sumaFA / $i), $digito);
        $finalTotal = $sentenciasN->truncarNota(($sumaF / $i), $digito);

        $html .= '<tr>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F" colspan="2"><strong>PROMEDIOS:</strong></td>';

        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        if (count($modelBloqueQ1) > 2) {
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        }
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $q1 . '</strong></td>';



        /*         * **** PARA QUIMSTRE 2 **** */
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';

        if (count($modelBloqueQ2) > 2) {
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        }

        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $q2 . '</strong></td>';

        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $finalNormal . '</strong></td>';

        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';
        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>-</strong></td>';

        $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#FCA04F"><strong>' . $finalTotal . '</strong></td>';

        $html .= '<tr>';

        return $html;
    }

    private function busca_cantidad($minimo, $maximo, $campo, $tipoCalif) {
        $cont = 0;
//      echo '<pre>';  
//        print_r($this->arrayTodosPromedios[0]['p1']);
//die();        

//        if ($tipoCalif == 'normal') {
//
            foreach ($this->arrayTodosPromedios as $prom[0]) {
                foreach ($prom as $cantidad) {
                    if ($cantidad[$campo] >= $minimo && $cantidad[$campo] <= $maximo) {
                        $cont++;
                    }
                }
            }
//        } else {
//            foreach ($this->arrayTodosPromedios as $prom) {
//                foreach ($prom as $cantidad) {
//                    if ($cantidad[$campo] >= $minimo && $cantidad[$campo] <= $maximo) {
//                        $cont++;
//                    }
//                }
//            }
//        }
        return $cont;
    }

    private function busca_cantidad2($minimo, $maximo, $campo, $tipoCalif) {
        $cont = 0;
//      echo '<pre>';  
//        print_r($this->arrayTodosPromedios);
//die();        

        if ($tipoCalif == 2) {
            

            foreach ($this->arrayTodosPromedios as $prom[0]) {
                foreach ($prom as $cantidad) {
                    if ($cantidad[$campo] >= $minimo && $cantidad[$campo] <= $maximo) {
                        $cont++;
                    }
                }
            }
        } else {
            
            foreach ($this->arrayTodosPromedios as $prom[0]) {
                foreach ($prom as $cantidad) {
                    if ($cantidad[$campo] >= $minimo && $cantidad[$campo] <= $maximo) {
                        $cont++;
                    }
                }
            }
        }




        return $cont;
    }

    private function cuadroPdf($clase, $modelBloqueQ1, $modelBloqueQ2, $tipoCalif) {



        //$sentencia = new \backend\models\NotasEnLibreta();

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelTablaAprovechamiento = \backend\models\ScholarisTablaEscalasHomologacion::find()->where([
                    'scholaris_periodo' => $modelPeriodo->codigo,
                    'corresponde_a' => 'APROVECHAMIENTO'
                ])->orderBy(['rango_maximo' => SORT_DESC])->all();

        $html = '';

        foreach ($modelTablaAprovechamiento as $homologacion) {
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC" colspan="2"><strong>'
                    . $homologacion->abreviatura
                    . "(" . $homologacion->rango_minimo . ' - ' . $homologacion->rango_maximo . ')'
                    . '</strong></td>';

            $cantP1 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'p1', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantP1 . '</strong></td>';

            $cantP2 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'p2', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantP2 . '</strong></td>';

            if (count($modelBloqueQ1) > 2) {
                $cantP3 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'p3', $tipoCalif);
                $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantP3 . '</strong></td>';
            }

            $cantPr1 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'pr1', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantPr1 . '</strong></td>';

            $cantPr180 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'pr180', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantPr180 . '</strong></td>';

            $cantEx1 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'ex1', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantEx1 . '</strong></td>';

            $cantEx120 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'ex120', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantEx120 . '</strong></td>';

            $cantQ1 = $this->busca_cantidad($homologacion->rango_minimo, $homologacion->rango_maximo, 'q1', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantQ1 . '</strong></td>';



            /*             * ****** pra quimestre 2 ******** */
            $cantP4 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'p4', $tipoCalif);            
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantP4 . '</strong></td>';

            $cantP5 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'p5', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantP5 . '</strong></td>';

            if (count($modelBloqueQ2) > 2) {
                $cantP6 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'p6', $tipoCalif);
                $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantP6 . '</strong></td>';
            }

            $cantPr2 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'pr2', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantPr2 . '</strong></td>';

            $cantPr280 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'pr280', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantPr280 . '</strong></td>';

            $cantEx2 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'ex2', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantEx2 . '</strong></td>';

            $cantEx220 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'ex220', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantEx220 . '</strong></td>';

            $cantQ2 = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'q2', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $cantQ2 . '</strong></td>';



            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>-</strong></td>';
            $finalTotal = $this->busca_cantidad2($homologacion->rango_minimo, $homologacion->rango_maximo, 'final_total', $tipoCalif);
            $html .= '<td class="conBorde centrarTexto padding2" bgcolor="#CCCCCC"><strong>' . $finalTotal . '</strong></td>';

            $html .= '</tr>';
        }

        return $html;
    }

}
