<?php

namespace backend\controllers;

use backend\models\ScholarisActividadDescriptor;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueSemanas;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisClase;
use backend\models\ScholarisGrupoAlumnoClase;
use backend\models\ScholarisParametrosOpciones;
use backend\models\ScholarisResumenParciales;
use backend\models\ScholarisTipoActividad;
use backend\models\ScholarisActividad;
use backend\models\ScholarisActividadSearch;
use backend\models\ScholarisArchivosprofesor;
use backend\models\SentenciasSql;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ScholarisActividadController implements the CRUD actions for ScholarisActividad model.
 */
class ScholarisActividadController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }

    /**
     * Lists all ScholarisActividad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScholarisActividadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisActividad model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisActividad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($claseId, $bloqueId, $calificado)
    {
        $modelBloques = ScholarisBloqueActividad::find()
            ->where(['id' => $bloqueId])
            ->one();

        $estado = $this->estado($modelBloques->hasta);

        $modelClase = ScholarisClase::find()->where(['id' => $claseId])->one();

        //Toma las semanas del bloque
        $weeks = ScholarisBloqueSemanas::find()->where(['bloque_id' => $bloqueId])
            ->orderBy('semana_numero')
            ->all();

        $sentencias = new SentenciasSql();

        $modelHorarios = $sentencias->fechasDisponibles($modelBloques->bloque_inicia, $modelBloques->bloque_finaliza, $claseId, $bloqueId);


        //si existe la semana renderiza a la misma vista, pero con los datos de la semana
        if (isset($_GET['week_id'])) {

            $detailWeek = $this->get_detail_week($_GET['week_id'], $claseId);
            return $this->render('create', [
                'modelClase' => $modelClase,
                'modelHorarios' => $modelHorarios,
                'estado' => $estado,
                'bloqueId' => $bloqueId,
                'weeks' => $weeks,
                'detailWeek' => $detailWeek
            ]);
        }


        return $this->render('create', [
            'modelClase' => $modelClase,
            'modelHorarios' => $modelHorarios,
            'estado' => $estado,
            'bloqueId' => $bloqueId,
            'weeks' => $weeks,
            'calificado' => $calificado
        ]);
    }


    private function get_detail_week($weekId, $classId)
    {

        $data = array();


        //$modelClase = ScholarisClase::findOne($classId); 
        $modelSemana = ScholarisBloqueSemanas::findOne($weekId);

        $bloqueId = $modelSemana->bloque_id;

        $sentencias = new SentenciasSql();

        $fechasDisponibles = $sentencias->fechasDisponiblesSemana($modelSemana->fecha_inicio, $modelSemana->fecha_finaliza, $classId, $bloqueId, $weekId);

        $disponibilidad = array();


        foreach ($fechasDisponibles as $dispo) {
            $totalActividades = $this->get_cantidad_actividades($dispo['fecha'], $classId);
            $dispo['total_actividades'] = $totalActividades;

            array_push($disponibilidad, $dispo);
        }

        $data = array(
            'week'              => $modelSemana,
            'disponibilidad'    => $disponibilidad
        );

        return $data;
    }


    private function get_cantidad_actividades($fecha, $claseId)
    {
        $periodoId = Yii::$app->user->identity->periodo_id;

        $con = Yii::$app->db;
        $query = "select 	sum(total_actividades) as total_actividades 
                    from 	dw_total_actividades_paralelo
                    where	paralelo_id in (
                    select 	cla.paralelo_id
                    from 	scholaris_grupo_alumno_clase gru
                            inner join scholaris_clase cla on cla.id = gru.clase_id 
                            inner join scholaris_periodo per on per.codigo = cla.periodo_scholaris
                            inner join op_course cur on cur.id = cla.idcurso 
                    where 	per.id = $periodoId
                            and gru.estudiante_id in (
                                                            select 	 estudiante_id 										
                                                from	scholaris_grupo_alumno_clase g
                                                        inner join scholaris_clase c on c.id = g.clase_id 
                                                where 	clase_id = $claseId
                            )
                    group by cla.paralelo_id, cur.name
                    ) and fecha_presentacion >= '$fecha' and fecha_presentacion <= '$fecha';";

        $res = $con->createCommand($query)->queryOne();

        isset($res['total_actividades']) ? $total = $res['total_actividades'] : $total = 0;
        return $total;
    }


    public function actionCrear1($clase, $fecha, $bloqueId, $tipo, $semana)
    {
        $fecha = $fecha . ' 23:59:59';
        $modelClase = ScholarisClase::find()->where(['id' => $clase])->one();

        $horas = $this->horas($fecha, $clase);

        if ($semana == 'NA') {
            $modelSemana = 0;
        } else {
            $modelSemana = ScholarisBloqueSemanas::find()
                ->where(['bloque_id' => $bloqueId, 'nombre_semana' => $semana])
                ->one();
        }

        $model = new \backend\models\ScholarisActividad();

        if ($tipo == 'P') {
            $modelInsumo = ScholarisTipoActividad::find()
                ->where(['in', 'nombre_pai', ['SUMATIVA', 'FORMATIVA']])
                ->andWhere(['activo' => true])
                ->orderBy('orden')
                ->all();
        } else {
            $modelInsumo = ScholarisTipoActividad::find()
                ->where(['not in', 'nombre_pai', ['SUMATIVA', 'FORMATIVA']])
                ->andWhere(['activo' => true])
                ->orderBy('orden')
                ->all();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['actividad', 'actividad' => $model->id]);
        }


        $modelAulas = ScholarisActividad::find()
            ->select(['link_aula_virtual'])
            ->where(['paralelo_id' => $clase])
            ->andWhere(['not', ['link_aula_virtual' => null]])
            ->groupBy(['link_aula_virtual'])
            ->one();

        $modelVideoConf = ScholarisActividad::find()
            ->select(['videoconfecia'])
            ->where(['paralelo_id' => $clase])
            ->andWhere(['not', ['videoconfecia' => null]])
            ->groupBy(['videoconfecia'])
            //                 ->orderBy(['id' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('_formcreate', [
            'model' => $model,
            'modelClase' => $modelClase,
            'bloque' => $bloqueId,
            'inicio' => $fecha,
            'modelInsumo' => $modelInsumo,
            'horas' => $horas,
            'modelSemana' => $modelSemana,
            'tipo' => $tipo,
            'modelAulas' => $modelAulas,
            'modelVideoConf' => $modelVideoConf
        ]);
    }

    /**
     * Updates an existing ScholarisActividad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $sentencias = new \backend\models\SentenciasNotas();
        $model = $this->findModel($id);
        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['idactividad' => $model->id])
            ->all();

        $horas = $this->horas($model->inicio, $model->paralelo_id);
        if ($model->load(Yii::$app->request->post())) {

            $model->fin = $model->inicio;
            $modelCalif = ScholarisCalificaciones::find()
                ->where(['idactividad' => $model->id])
                ->all();
            if ($modelCalif) {
                $modelGrupoCalif = \backend\models\ScholarisGrupoOrdenCalificacion::find()
                    ->where(['codigo_tipo_actividad' => $model->tipo_actividad_id])
                    ->one();

                $model->save();
                $sentencias->cambia_grupo_actividad($id, $modelGrupoCalif->grupo_numero, $model->tipo_actividad_id);
            } else {
                $model->save();
            }
            return $this->redirect(['actividad', 'actividad' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'modelCalificaciones' => $modelCalificaciones,
            'horas' => $horas
        ]);
    }

    /**
     * Deletes an existing ScholarisActividad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisActividad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisActividad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Administra la edici??n de una actividad
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActividad($actividad)
    {
        
        $sentencia = new \backend\models\SentenciasNotas();

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $actividad])
            ->one();
        
        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['idactividad' => $actividad])
            ->andWhere(['not', ['calificacion' => null]])
            ->all();
        //cambio
        $sentencias = new SentenciasSql();
        $modelCalificaciones2 = ScholarisCalificaciones::find()
            ->where(['idactividad' => $actividad])
            ->all();        
        

        $noAsignados = $sentencias->criteriosNoAsignados($modelActividad->clase->paralelo->course->id, $modelActividad->clase->ismAreaMateria->materia->area_id, $actividad);
        $asignados = $sentencias->criteriosAsignados($actividad);        
        //fin cambio       

        $estado = $this->estado($modelActividad->bloque->hasta);

        $modelCriterios = ScholarisActividadDescriptor::find()
            ->where(['actividad_id' => $actividad])
            ->orderBy('criterio_id')
            ->all();        

        $modelArchivos = \backend\models\ScholarisArchivosprofesor::find()->where(['idactividad' => $actividad])->all();

        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
            ->innerJoin("op_student_inscription i", "i.student_id = scholaris_grupo_alumno_clase.estudiante_id")
            ->where([
                'clase_id' => $modelActividad->paralelo_id,
                'i.inscription_state' => 'M'
            ])
            ->all();

        $modelTotalCali = $sentencia->toma_total_calificados($actividad, count($modelGrupo));

        $modelTotalCalificados = $sentencia->toma_total_calificados_con_nulos($actividad);

        if ($modelTotalCalificados == 0) {
            $totalCalificaciones = count($modelGrupo);
        } else {
            $totalCalificaciones = $modelTotalCalificados;
        }

        $modelTotal = $modelTotalCali - $modelTotalCalificados;

        $estadisticas = $this->get_valores_estadisticos($modelActividad->paralelo_id);
        
        $model = new ScholarisArchivosprofesor();
        //ingresa en este IF, para generar el update, 1 = update      
        if (isset($_GET['bandera']) && $_GET['bandera']==1)
        {            
            $idMatApoyo = $_GET['idMatApoyo'];
            $model = ScholarisArchivosprofesor::findOne($idMatApoyo);             
        }
        //ingresa en este IF, para generar el update, 2 = delete        
        if (isset($_GET['bandera']) && $_GET['bandera']==2)
        {
            $idMatApoyo = $_GET['idMatApoyo'];
            $model = ScholarisArchivosprofesor::findOne($idMatApoyo);
            if(isset($model)){
                $model->delete();
            } 
            $model = new ScholarisArchivosprofesor();
              
                     
        }

        if($modelActividad->tipo_calificacion == 'P'){
            return $this->render('actividad', [
                'modelActividad' => $modelActividad,
                'estado' => $estado,
                'modelCriterios' => $modelCriterios,
                'modelCalificaciones' => $modelCalificaciones,
                'modelArchivos' => $modelArchivos,
                'modelTotal' => $modelTotal,
                'estadisticas' => $estadisticas,
                'modelCalificaciones2' => $modelCalificaciones2, 
                'noAsignados' => $noAsignados,  
                'asignados' => $asignados ,
                'model'=>  $model     
            ]);
        }else{

            return $this->render('actividad', [
                'modelActividad' => $modelActividad,
                'estado' => $estado,
                'modelCriterios' => $modelCriterios,
                'modelCalificaciones' => $modelCalificaciones,
                'modelArchivos' => $modelArchivos,
                'modelTotal' => $modelTotal,
                'modelCalificaciones2' => $modelCalificaciones2,
                'noAsignados' => $noAsignados,  
                'asignados' => $asignados ,
                'model'=>  $model            
            ]);
        }        
    }


    private function get_valores_estadisticos($claseId){

        $con = Yii::$app->db;
        $queryCriterios = "select criterio 	 
                    from 	scholaris_criterio c
                    group by criterio 
                    order by criterio;";
        $criterios = $con->createCommand($queryCriterios)->queryAll();

        $queryCriUsados = "select 	curso, paralelo, docente, materia, clase_id, bloque_id, bloque, tipo_actividad, criterio, total 
                            from 	dw_estadisticas_criterios_pai
                            where 	clase_id = $claseId
                            order by bloque_id, criterio, tipo_actividad;";
        $criUsados = $con->createCommand($queryCriUsados)->queryAll();

        $queryParciales = "select 	b.id 
                                    ,b.abreviatura 
                            from 	scholaris_bloque_actividad b
                                    inner join scholaris_actividad a on a.bloque_actividad_id = b.id 
                            where 	a.paralelo_id = $claseId
                            group by b.id, b.abreviatura
                            order by b.orden;";
        $parciales = $con->createCommand($queryParciales)->queryAll();

        return array(
            'criterios' => $criterios,
            'criUsados' => $criUsados,
            'parciales' => $parciales
        );
    }



    public function actionDescargar($ruta)
    {
        $path = "../web/imagenes/instituto/archivos-profesor/";

        return \Yii::$app->response->sendFile($path . $ruta);


        //Si el archivo existe
        //     if (is_file($path))
        //     {
        //                 //Procedemos a descargar el archivo
        //                 // Definir headers
        //                 //$size = filesize($path);
        //                 header("Content-Type: application/force-download");
        //                 header("Content-Disposition: attachment; filename=$path");
        //                 header("Content-Transfer-Encoding: binary");
        //                 header("Content-Length: " . $path);
        //                 // Descargar archivo
        //                 readfile($path);
        //                 //Correcto
        //                 return true;
        // }
        //readfile($file);
    }

    /**
     * Toma el estado de un bloque
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function estado($fechaHasta)
    {
        $fecha = date("Y-m-d H:i:m");

        if ($fecha > $fechaHasta) {
            $estado = 'cerrado';
        } else {
            $estado = 'abierto';
        }
        return $estado;
    }

    /**
     * Realiza la calificacion de las actividades
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCalificar($id)
    {

        $sentencias = new \backend\models\SentenciasClase();

        /* crea los espacios para las calificaciones */
        $this->espaciosCalificacion($id);

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelGrupo = $sentencias->get_alumnos_clase($modelActividad->paralelo_id, $periodoId);


        $modelMinimo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        if ($modelActividad->tipo_calificacion == 'P') {
            $modelCalificaciones = ScholarisCalificaciones::find()
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_calificaciones.criterio_id')
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->orderBy('scholaris_criterio.criterio', 'op_student.last_name', 'op_student.first_name', 'op_student.middle_name')
                ->all();

            $modelCriterios = ScholarisActividadDescriptor::find()
                ->select(['criterio_id'])
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_actividad_descriptor.criterio_id')
                ->where(['actividad_id' => $id])
                ->groupBy(['criterio_id', 'scholaris_criterio.criterio'])
                ->orderBy('scholaris_criterio.criterio')
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                //->select(['op_student.last_name','op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'])
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_calificaciones.criterio_id')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy([
                    'op_student.last_name' => SORT_ASC,
                    'op_student.first_name' => SORT_ASC,
                    'op_student.middle_name' => SORT_ASC,
                    'scholaris_criterio.criterio' => SORT_ASC,
                ])
                //,'op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'
                ->limit(1)
                ->one();
        } else {

            /*             * * aqui model de criterios para nacionales ** */

            $modelCalificaciones = ScholarisCalificaciones::find()
                ->where(['idactividad' => $id])
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy('op_student.last_name', 'scholaris_calificaciones.criterio_id')
                ->one();
        }

        /* para bloques */
        $modelBloques = ScholarisBloqueActividad::find()
            ->where(['id' => $modelActividad->bloque_actividad_id])
            ->one();


        $estado = $this->estado($modelBloques->hasta);


        if ($modelActividad->tipo_calificacion == 'P') {
            return $this->render('calificar', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCriterios' => $modelCriterios,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        } else {

            return $this->render('calificar', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        }
    }

    public function actionAnularcalificaciones($id)
    {

        $sentencias = new \backend\models\SentenciasClase();

        /* crea los espacios para las calificaciones */
        $this->espaciosCalificacion($id);

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        //        $modelGrupo = ScholarisGrupoAlumnoClase::find()
        //                // ->select(['op_student.id','op_student.last_name', 'op_student.first_name'])
        //                ->innerJoin('op_student', 'op_student.id = scholaris_grupo_alumno_clase.estudiante_id')
        //                ->innerJoin('op_student_inscription', 'op_student_inscription.student_id = op_student.id')
        //                ->innerJoin('scholaris_clase', 'scholaris_clase.id = scholaris_grupo_alumno_clase.clase_id')
        //                ->innerJoin('scholaris_op_period_periodo_scholaris', 'scholaris_op_period_periodo_scholaris.op_id = op_student_inscription.period_id')
        //                ->where([
        //                    'scholaris_grupo_alumno_clase.clase_id' => $modelActividad->paralelo_id,
        //                    'op_student_inscription.inscription_state' => 'M',
        //                    'scholaris_clase.periodo_scholaris' => $modelPeriodo->codigo
        //                ])
        //                ->orderBy('op_student.last_name', 'op_student.first_name')
        //                ->all();

        $modelGrupo = $sentencias->get_alumnos_clase($modelActividad->paralelo_id, $periodoId);

        $modelMinimo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        if ($modelActividad->tipo_calificacion == 'P') {
            $modelCalificaciones = ScholarisCalificaciones::find()
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_calificaciones.criterio_id')
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->orderBy('scholaris_criterio.criterio', 'op_student.last_name', 'op_student.first_name', 'op_student.middle_name')
                ->all();

            $modelCriterios = ScholarisActividadDescriptor::find()
                ->select(['criterio_id'])
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_actividad_descriptor.criterio_id')
                ->where(['actividad_id' => $id])
                ->groupBy(['criterio_id', 'scholaris_criterio.criterio'])
                ->orderBy('scholaris_criterio.criterio')
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                //->select(['op_student.last_name','op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'])
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->innerJoin('scholaris_criterio', 'scholaris_criterio.id = scholaris_calificaciones.criterio_id')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy([
                    'op_student.last_name' => SORT_ASC,
                    'op_student.first_name' => SORT_ASC,
                    'op_student.middle_name' => SORT_ASC,
                    'scholaris_criterio.criterio' => SORT_ASC,
                ])
                //,'op_student.first_name','op_student.middle_name','scholaris_criterio.criterio'
                ->limit(1)
                ->one();
        } else {

            /*             * * aqui model de criterios para nacionales ** */

            $modelCalificaciones = ScholarisCalificaciones::find()
                ->where(['idactividad' => $id])
                ->all();

            $modelCalificarUnitario = ScholarisCalificaciones::find()
                ->innerJoin('op_student', 'op_student.id = scholaris_calificaciones.idalumno')
                ->where(['scholaris_calificaciones.idactividad' => $id])
                ->andWhere(['is', 'scholaris_calificaciones.calificacion', null])
                ->orderBy('op_student.last_name', 'scholaris_calificaciones.criterio_id')
                ->one();
        }

        /* para bloques */
        $modelBloques = ScholarisBloqueActividad::find()
            ->where(['id' => $modelActividad->bloque_actividad_id])
            ->one();

        $fecha = date("Y-m-d");
        if ($fecha > $modelBloques->hasta) {
            $estado = 'cerrado';
        } else {
            $estado = 'abierto';
        }

        if ($modelActividad->tipo_calificacion == 'P') {
            return $this->render('anular', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCriterios' => $modelCriterios,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        } else {
            return $this->render('anular', [
                'modelCalificaciones' => $modelCalificaciones,
                'modelActividad' => $modelActividad,
                'modelGrupo' => $modelGrupo,
                'modelCalificarUnitario' => $modelCalificarUnitario,
                'modelMinimo' => $modelMinimo,
                'modelMaximo' => $modelMaximo,
                'estado' => $estado,
            ]);
        }
    }

    /**
     * Metodo que realiza el llenado de espacios para la calificaci??n
     * @param integer $id
     * @return ScholarisActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function espaciosCalificacion($id)
    {

        $sentencias = new SentenciasSql();

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();

        $tipo = $modelActividad->insumo->nombre_pai;
        $grupo = \backend\models\ScholarisGrupoOrdenCalificacion::find()
            ->where(['codigo_tipo_actividad' => $modelActividad->tipo_actividad_id])
            ->one();

        if ($modelActividad->tipo_calificacion == 'P') {

            $modelCriterios = ScholarisActividadDescriptor::find()
                ->select(['criterio_id'])
                ->where(['actividad_id' => $id])
                ->groupBy(['criterio_id'])
                ->all();

            foreach ($modelCriterios as $criterio) {
                if ($tipo == 'SUMATIVA') {

                    $sentencias->insertarEspaciosCalificacionPaiSumativa($id, $modelActividad->tipo_actividad_id, $criterio->criterio_id, $modelActividad->paralelo_id, $grupo->grupo_numero);
                } else {
                    $sentencias->insertarEspaciosCalificacionPai($id, $modelActividad->tipo_actividad_id, $criterio->criterio_id, $modelActividad->paralelo_id);
                }
            }
        } else {

            $sentencias->insertarEspaciosCalificacionNac($id, $modelActividad->tipo_actividad_id, $modelActividad->paralelo_id);
        }
    }

    public function actionRegistra()
    {
        //        print_r($_POST);
        //                die();

        $nota = $_POST['nota'];
        $notaId = $_POST['notaId'];

        //        echo $notaId;
        //$model = \app\models\ScholarisActividad::findOne($id));
        $model = ScholarisCalificaciones::findOne($notaId);

        $model->calificacion = $nota;

        $model->save();

        $this->actualizaParcial($notaId);

        //        if(isset($_POST['bandera'])){
        //            return $this->redirect(['individual', 'actividadId' => $model->idactividad, 'alumnoId' => $model->idalumno]);
        //        }else{
        //            return $this->redirect(['calificar', 'id' => $model->idactividad]);
        //        }
    }

    protected function actualizaParcial($notaId)
    {

        $sentencias = new SentenciasSql();
        $sentencias2 = new \backend\models\Notas();

        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['id' => $notaId])
            ->one();



        $this->registraParcial($modelCalificaciones->idalumno, $modelCalificaciones->actividad->bloque_actividad_id, $modelCalificaciones->actividad->paralelo_id);

        //PARA REGISTRO EN LA LIBRETA CLASE
        //metodo antiguo
        //        $sentencias->actualizaLibreta($modelCalificaciones->idalumno, $modelCalificaciones->actividad->bloque_actividad_id, $modelCalificaciones->actividad->paralelo_id);

        /*         * **metodo nuevo con reforzamiento ** */
        $sentencias2->actualiza_parcial($modelCalificaciones->actividad->bloque_actividad_id, $modelCalificaciones->idalumno, $modelCalificaciones->actividad->paralelo_id);
    }

    protected function registraParcial($alumno, $bloqueId, $claseId)
    {
        $fecha = date("Y-m-d H:i:s");
        $sentencias = new SentenciasSql();

        $notaParcial = $sentencias->notaParcial($alumno, $bloqueId, $claseId);


        $model = ScholarisResumenParciales::find()
            ->where(['alumno_id' => $alumno, 'clase_id' => $claseId, 'bloque_id' => $bloqueId])
            ->one();



        if ($model) {
            $model->actualizacion_fecha = $fecha;
            $model->calificacion = $notaParcial['nota'];
            $model->save();
        } else {
            $model1 = new ScholarisResumenParciales();
            $model1->actualizacion_fecha = $fecha;
            $model1->alumno_id = $alumno;
            $model1->clase_id = $claseId;
            $model1->bloque_id = $bloqueId;
            $model1->calificacion = $notaParcial['nota'];
            $model1->save();
        }
    }

    protected function horas($fecha, $clase)
    {

        $sentencia = new SentenciasSql();

        //$fecha="2018-11-16" ; // fecha.
        #separas la fecha en subcadenas y asignarlas a variables
        #relacionadas en contenido, por ejemplo dia, mes y anio.

        $dia = substr($fecha, 8, 2);
        $mes = substr($fecha, 5, 2);
        $anio = substr($fecha, 0, 4);

        $diaNumero = date('w', mktime(0, 0, 0, $mes, $dia, $anio));
        //donde:
        #W (may??scula) te devuelve el n??mero de semana
        #w (min??scula) te devuelve el n??mero de d??a dentro de la semana (0=domingo, #6=sabado)

        $modelHoras = $sentencia->horasDia($clase, $diaNumero);

        return $modelHoras;
    }

    public function actionCriterios($id)
    {
        $sentencias = new SentenciasSql();

        $modelActividad = \backend\models\ScholarisActividad::find()
            ->where(['id' => $id])
            ->one();      

        $modelCalificaciones = ScholarisCalificaciones::find()
            ->where(['idactividad' => $id])
            ->all();

        $noAsignados = $sentencias->criteriosNoAsignados($modelActividad->clase->course->id, $modelActividad->clase->materia->area_id, $id);
        $asignados = $sentencias->criteriosAsignados($id);
              

        return $this->render('criterios', [
            'modelActividad' => $modelActividad,
            'noAsignados' => $noAsignados,
            'modelCalificaciones' => $modelCalificaciones,
            'asignados' => $asignados,
        ]);
    }

    public function actionAsignarcriterio($actividad, $criterio, $detalle)
    {
        $model = new ScholarisActividadDescriptor();

        $model->actividad_id = $actividad;
        $model->criterio_id = $criterio;
        $model->detalle_id = $detalle;
        $model->save();

        // return $this->redirect([
        //     'criterios',
        //     'id' => $actividad,
        // ]);
        
        return $this->redirect([
            'actividad',
            'actividad' => $actividad,
        ]);

    }

    public function actionQuitarcriterio($id)
    {
        $model = ScholarisActividadDescriptor::find()
            ->where(['id' => $id])
            ->one();

        $actividad = $model->actividad_id;

        $model->delete();

        //return $this->redirect(['criterios', 'id' => $actividad]);
        return $this->redirect(['actividad', 'actividad' => $actividad]);
    }

    /* INICIO ANULAR */

    public function actionAnular($actividadId, $alumnoId)
    {
        $sentencia = new SentenciasSql();

        $model = \backend\models\ScholarisActividad::find()
            ->where(['id' => $actividadId])
            ->one();

        $sentencia->eliminaCalificaciones($actividadId, $alumnoId);

        $this->registraParcial($alumnoId, $model->bloque_actividad_id, $model->paralelo_id);

        return $this->redirect(['calificar', 'id' => $actividadId]);
    }

    /* FIN ANULAR */

    /** INICIO CALIFICACION  INDIVIDUAL */
    public function actionIndividual($alumnoId, $actividadId)
    {
        $model = ScholarisCalificaciones::find()
            ->where(['idactividad' => $actividadId, 'idalumno' => $alumnoId])
            ->all();

        $modelActividad = \app\models\ScholarisActividad::find()
            ->where(['id' => $actividadId])
            ->one();

        $modelMinimo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califminima'])
            ->one();

        $modelMaximo = ScholarisParametrosOpciones::find()
            ->where(['codigo' => 'califmmaxima'])
            ->one();

        return $this->render('individual', [
            'model' => $model,
            'modelActividad' => $modelActividad,
            'modelMinimo' => $modelMinimo,
            'modelMaximo' => $modelMaximo,
        ]);
    }

    /** FIN CALIFICACION  INDIVIDUAL */

    /**
     * Elimina la Actividad.
     * Este proceso elimina la actividad y calcula los nuevos promedios alumno por alumno
     */
    public function actionEliminar($id)
    {

        $modelActividad = ScholarisActividad::find()->where(['id' => $id])->one();

        if (isset($_GET['mensaje']) == 'SI') {
            $mensaje = 'SI';

            $this->elimina_deberes($id);

            $modelActividad->delete();
            $this->actualizaParcialesPorEliminarActividad($modelActividad);

            return $this->render('eliminar', [
                'modelActividad' => $modelActividad,
                'mensaje' => 'SI'
            ]);
        } else {

            return $this->render('eliminar', [
                'modelActividad' => $modelActividad,
                'mensaje' => 'NO'
            ]);
        }
    }

    private function elimina_deberes($actividadId)
    {
        $con = Yii::$app->db;
        $query = "delete from scholaris_actividad_deber where actividad_id = $actividadId;";
        $con->createCommand($query)->execute();
    }

    private function actualizaParcialesPorEliminarActividad($modelActividad)
    {

        $modelAlumnos = ScholarisGrupoAlumnoClase::find()->where(['clase_id' => $modelActividad->paralelo_id])->all();

        foreach ($modelAlumnos as $data) {
            //echo $data->clase_id.'--';
            $this->registraParcial($data->estudiante_id, $modelActividad->bloque_actividad_id, $modelActividad->paralelo_id);
        }
    }

    public function actionDuplicar()
    {
        $actividadId = $_GET['id'];
        //$modelActividad = \backend\models\ScholarisActividad::find()->where(['id' => $actividadId])->one();

        $modelActividad = \backend\models\ScholarisActividad::findOne($actividadId);


        $modelClases = ScholarisClase::find()
            ->where([
                'idmateria' => $modelActividad->clase->idmateria,
                'idcurso' => $modelActividad->clase->idcurso
            ])
            ->andWhere(['<>', 'id', $modelActividad->paralelo_id])
            ->all();

        //        echo '<pre>';
        //        print_r($modelClases);
        //        die();


        return $this->render('duplicar', [
            'modelClases' => $modelClases,
            'modelActividad' => $modelActividad
        ]);
    }

    public function actionDuplicaraqui()
    {

        $clase = $_GET['clase'];
        $inicio = $_GET['inicio'];
        $hora = $_GET['hora'];

        $usuario = \Yii::$app->user->identity->usuario;
        $modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();

        $modelActividad = \backend\models\ScholarisActividad::find()->where(['id' => $_GET['actividadId']])->one();

        $this->save_actividad($modelActividad, $modelUsuario, $clase, $inicio, $hora);

        return $this->redirect([
            'duplicar',
            'id' => $modelActividad->id
        ]);
    }

    private function save_actividad($modelActividad, $modelUsuario, $clase, $inicio, $hora)
    {
        $fechaHoy = date("Y-m-d H:i:m");

        $sentencias = new SentenciasSql();
        //        $modelHora = $sentencias->hora_para_duplicar($clase, $inicio);

        $model = new \backend\models\ScholarisActividad();
        $model->create_date = $fechaHoy;
        $model->write_date = $fechaHoy;
        $model->create_uid = $modelUsuario->id;
        $model->write_uid = $modelUsuario->id;
        $model->title = $modelActividad->title;
        $model->descripcion = $modelActividad->descripcion;
        $model->inicio = $inicio;
        $model->fin = $inicio;
        $model->fin = $inicio;
        $model->tipo_actividad_id = $modelActividad->tipo_actividad_id;
        $model->bloque_actividad_id = $modelActividad->bloque_actividad_id;
        $model->paralelo_id = $clase;
        $model->materia_id = $modelActividad->materia_id;
        $model->calificado = $modelActividad->calificado;
        $model->tipo_calificacion = $modelActividad->tipo_calificacion;
        $model->tareas = $modelActividad->tareas;
        $model->hora_id = $hora;
        $model->actividad_original = $modelActividad->id;
        $model->semana_id = $modelActividad->semana_id;
        //        echo '<pre>';
        //        print_r($model);
        $model->save();

        $ultimoId = $model->primaryKey;

        $sentencias->duplicar_criterios($ultimoId, $modelActividad->id);
        $sentencias->duplicar_archivos($ultimoId, $modelActividad->id);
    }

    public function actionParcial()
    {

        $clase = $_GET['clase'];
        $orden = $_GET['orden'];
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $sentencias = new \backend\models\SentenciasClase();
        $sentencias2 = new SentenciasSql();

        $modelAlumnos = $sentencias->get_alumnos_clase($clase, $periodoId);
        $modelClase = ScholarisClase::find()->where(['id' => $clase])->one();
        $modelBloque = ScholarisBloqueActividad::find()
            ->where([
                'orden' => $orden,
                'tipo_uso' => $modelClase->tipo_usu_bloque,
                'scholaris_periodo_codigo' => $modelPeriodo->codigo
            ])
            ->one();

        $modelTipo = $sentencias2->get_insumos($clase, $modelBloque->id);
        $modelMinimo = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minima = $modelMinimo->valor;

        return $this->render('parcial', [
            'modelAlumnos' => $modelAlumnos,
            'modelClase' => $modelClase,
            'modelBloque' => $modelBloque,
            'modelTipo' => $modelTipo,
            'minima' => $minima
        ]);
    }

    public function actionExtraordinarios()
    {

        $modelMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $minima = $modelMinima->valor;

        if (isset($_GET['grupo'])) {
            $grupo = $_GET['grupo'];


            if (isset($_GET['accion'])) {
                $accion = $_GET['accion'];
            } else {
                $accion = 'reporte-sabana-profesor/index1';
            }

            $modelLibreta = \backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();

            $modelSupletorio = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'SUPLETORIO'])->one();
            $modelRemedial = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'REMEDIAL'])->one();
            $modelGracia = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'GRACIA'])->one();
            $modelRecuperacion = \backend\models\ScholarisQuimestre::find()->where(['codigo' => 'RECUPERACION'])->one();


            return $this->render('extraordinarios', [
                'modelLibreta' => $modelLibreta,
                'minima' => $minima,
                'modelSupletorio' => $modelSupletorio,
                'modelRemedial' => $modelRemedial,
                'modelGracia' => $modelGracia,
                'modelRecuperacion' => $modelRecuperacion,
                'accion' => $accion
            ]);
        } else {
            $grupo = $_POST['grupo'];
            $supletorio = $_POST['supletorio'];
            $remedial = $_POST['remedial'];
            $gracia = $_POST['gracia'];
            $mejora_q1 = $_POST['mejora_q1'];
            $mejora_q2 = $_POST['mejora_q2'];
            $accion = $_POST['accion'];
            $modelLibreta = \backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();


            $modelLibreta->mejora_q1 = $mejora_q1;
            $modelLibreta->mejora_q2 = $mejora_q2;
            $modelLibreta->supletorio = $supletorio;
            $modelLibreta->remedial = $remedial;
            $modelLibreta->gracia = $gracia;
            $modelLibreta->final_total = null;
            $modelLibreta->estado = null;
            if ($mejora_q1 || $mejora_q2) {
                $mejorado = $this->calcula_mejora_quimestres($modelLibreta->q1, $modelLibreta->q2, $mejora_q1, $mejora_q2);
            } else {
                $mejorado = null;
            }

            $modelLibreta->final_con_mejora = $mejorado;
            $modelLibreta->save();

            $this->calcula_final_despues_extras($grupo, $minima);

            return $this->redirect([$accion, 'id' => $modelLibreta->grupo->clase_id]);
        }
    }

    private function calcula_mejora_quimestres($q1, $q2, $mejora1, $mejora2)
    {


        $sentencias = new \backend\models\Notas();

        if ($mejora1 > $q1) {
            $mq1 = $mejora1;
        } else {
            $mq1 = $q1;
        }

        echo $mq1;

        if ($mejora2 > $q2) {
            $mq2 = $mejora2;
        } else {
            $mq2 = $q2;
        }

        $promedio = ($mq1 + $mq2) / 2;
        $promedio = $sentencias->truncarNota($promedio, 2);



        return $promedio;
    }

    private function calcula_final_despues_extras($grupo, $minima)
    {

        $modelGrupo = \backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();


        if ($modelGrupo->final_con_mejora > 0) {
            $modelGrupo->final_total = $modelGrupo->final_con_mejora;
            $modelGrupo->estado = 'APROBADO';
        } else {

            if ($modelGrupo->supletorio >= $minima || $modelGrupo->remedial >= $minima || $modelGrupo->gracia >= $minima) {
                $modelGrupo->final_total = $minima;
                $modelGrupo->estado = 'APROBADO';
            }

            if ($modelGrupo->supletorio < $minima && $modelGrupo->supletorio != null && $modelGrupo->remedial == null && $modelGrupo->gracia == null) {
                $modelGrupo->final_total = $modelGrupo->final_ano_normal;
                $modelGrupo->estado = 'REMEDIAL';
            }

            if ($modelGrupo->remedial < $minima && $modelGrupo->remedial != null && $modelGrupo->gracia == null) {
                $modelGrupo->final_total = $modelGrupo->final_ano_normal;
                $modelGrupo->estado = 'GRACIA';
            }

            if ($modelGrupo->gracia < $minima && $modelGrupo->gracia != null) {
                $modelGrupo->final_total = $modelGrupo->final_ano_normal;
                $modelGrupo->estado = 'PIERDE EL A??O';
            }
        }

        $modelGrupo->save();
    }

    public function actionTerminar()
    {

        $sentencias = new \backend\models\Notas();

        $clase = $_GET['clase'];

        if (isset($_GET['accion'])) {
            $accion = $_GET['accion'];
        } else {
            $accion = 'reporte-sabana-profesor/index1';
        }

        $modelClase = ScholarisClase::find()->where(['id' => $clase])->one();

        if (isset($_GET['ejecutar'])) {
            $accion = $_GET['accion'];

            $sentencias->ejecutar_termino_ano_clase($clase);


            $modelClase->estado_cierre = true;
            $modelClase->save();
            //            return $this->redirect(['reporte-sabana-profesor/index1','id' => $clase]);
            return $this->redirect([$accion, 'id' => $clase]);
        } else {
            return $this->render('terminar', [
                'modelClase' => $modelClase,
                'accion' => $accion
            ]);
        }
    }


    public function actionEliminararchivo()
    {
        //print_r($_GET);
        $model = ScholarisArchivosprofesor::findOne($_GET['id']);
        $actividad = $model->idactividad;
        $model->delete();

        return $this->redirect([
            'actividad',
            'actividad' => $actividad
        ]);
    }

    public function actionVerarchivos()
    {
        $actividad = $_GET['actividadId'];
        $alumno = $_GET['alumnoId'];

        $modelActividad = ScholarisActividad::findOne($actividad);
        $modelAlumno = \backend\models\OpStudent::findOne($alumno);

        $modelEntregados = \backend\models\ScholarisActividadDeber::find()
            ->where([
                'actividad_id' => $actividad,
                'alumno_id' => $alumno
            ])
            ->all();

        return $this->render('verarchivos', [
            'modelActividad' => $modelActividad,
            'modelAlumno' => $modelAlumno,
            'modelEntregados' => $modelEntregados
        ]);
    }

    public function actionUpdateobservacion()
    {
        //        print_r($_GET);

        $alumnoId = $_GET['alumnoId'];
        $actividadId = $_GET['actividadId'];


        $model = ScholarisCalificaciones::find()
            ->where([
                'idalumno' => $alumnoId,
                'idactividad' => $actividadId
            ])
            ->one();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['anularcalificaciones', 'id' => $actividadId]);
        }


        return $this->render('updateobservacion', [
            'model' => $model
        ]);
    }
}
