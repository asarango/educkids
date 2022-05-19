<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;
use backend\models\OpCourse;
use backend\models\OpCourseParalelo;
use backend\models\OpStudent;
use backend\models\OpFaculty;
use backend\models\ScholarisOpPeriodPeriodoScholaris;
use backend\models\ScholarisClase;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\ScholarisGrupoAlumnoClase;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class CajasSelectController extends Controller {

    public function actionCursosprofesor() {
        $profesor = $_POST['id'];
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $con = \Yii::$app->db;
        $query = "select 	cur.id
                                ,cur.name as curso
                from 	scholaris_clase c
                                inner join op_course cur on cur.id = c.idcurso
                where	c.idprofesor = $profesor
                                and c.periodo_scholaris = '$modelPeriodo->codigo'
                group by cur.id, cur.name
                order by cur.name;";
        $resp = $con->createCommand($query)->queryAll();

        $data = ArrayHelper::map($resp, 'id', 'curso');

        echo Select2::widget([
            'name' => 'curso',
            'id' => 'cursoId',
            'value' => 0,
            'data' => $data,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione curso',
                'onchange' => 'mostrarParalelo(this,"' . Url::to(['paraleloscursoprofesor']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
    }

    /**
     * Paraelos por curso y profeshor
     * @return mixed
     */
    public function actionParaleloscursoprofesor() {
        $curso = $_POST['curso'];
        $profesor = $_POST['profesor'];

        $con = \Yii::$app->db;
        $query = "select 	p.id
                                    ,p.name as paralelo 
                    from	scholaris_clase c 
                                    inner join op_course_paralelo p on p.id = c.paralelo_id
                    where	c.idcurso = $curso
                                    and c.idprofesor = $profesor
                    group by p.id, p.name
                    order by p.name";
        $resp = $con->createCommand($query)->queryAll();
        $data = ArrayHelper::map($resp, 'id', 'paralelo');


        echo Select2::widget([
            'name' => 'paralelo',
            'id' => 'paraleloId',
            'value' => 0,
            'data' => $data,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione paralelo',
                'onchange' => 'mostrarClases(this,"' . Url::to(['clasesparaleloprofesor']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
    }
    
    
    /***
     * Busca paralelos cuando se escoge el curso
     */
    public function actionBuscaParalelosPorCurso(){
        $cursoId = $_POST['curso_id'];
        
        $modelParalelos = OpCourseParalelo::find()
                ->where(['course_id' => $cursoId])
                ->orderBy('name')
                ->all();
        
        $dataList = ArrayHelper::map($modelParalelos, 'id', 'name');
        
        echo '<label>Paralelo:</label>';
        echo Select2::widget([
            'name' => 'paralelo',
            'id' => 'paraleloId',
            'value' => 0,
            'data' => $dataList,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione paralelo',
                //'onchange' => 'mostrarClases(this,"' . Url::to(['clasesparaleloprofesor']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        
    }
    
    

    /**
     * BUSCA CLASES POR PROFESOR
     */
    public function actionClasesparaleloprofesor() {

        $profesor = $_POST['profesor'];
        $paralelo = $_POST['paralelo'];

        $con = Yii::$app->db;
        $query = "select 	c.id
                                    ,m.name as materia
                    from	scholaris_clase c
                                    inner join scholaris_materia m on m.id = c.idmateria
                    where	c.paralelo_id = $paralelo
                                    and idprofesor = $profesor;";
        $resp = $con->createCommand($query)->queryAll();
        $data = ArrayHelper::map($resp, 'id', 'materia');


        return Select2::widget([
                    'name' => 'clase',
                    'id' => 'claseId',
                    'value' => 0,
                    'data' => $data,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione clase',
                        'onchange' => 'mostrarAlumnos(this,"' . Url::to(['alumnosclase']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
        ]);
    }

    /**
     * ALUMNOS POR PARALELO
     */
    public function actionAlumnosclase() {

        $clase = $_POST['clase'];

        $modelAlumnos = OpStudent::find()
                ->select(["op_student.id", "concat(op_student.last_name,' ',op_student.first_name,' ',op_student.middle_name) as last_name"])
                ->innerJoin("scholaris_grupo_alumno_clase", "op_student.id = scholaris_grupo_alumno_clase.estudiante_id")
                ->where(['scholaris_grupo_alumno_clase.clase_id' => $clase])
                ->orderBy("op_student.last_name,op_student.first_name,op_student.middle_name,")
                ->all();

        $listData = ArrayHelper::map($modelAlumnos, 'id', 'last_name');

        echo Select2::widget([
            'name' => 'alumno',
            'id' => 'alumnoId',
            'value' => 0,
            'data' => $listData,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione alumno',
                'onchange' => 'mostrarBloque(this,"' . Url::to(['bloqueclase']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
    }

    public function actionBloqueclase() {

        $clase = $_POST['clase'];

        $periodoId = Yii::$app->user->identity->periodo_id;

        $modelClase = ScholarisClase::find()
                ->where(['id' => $clase])
                ->one();
        $modelPeriodo = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();


        $modelBloque = ScholarisBloqueActividad::find()
                ->where(['tipo_uso' => $modelClase->tipo_usu_bloque, "scholaris_periodo_codigo" => $modelPeriodo->codigo])
                ->orderBy("orden")
                ->all();

        $listData = ArrayHelper::map($modelBloque, 'id', 'name');

        return Select2::widget([
                    'name' => 'bloque',
                    'id' => 'bloqueId',
                    'value' => 0,
                    'data' => $listData,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione bloque',
                        'onchange' => 'mostrarActividades(this,"' . Url::to(['actividadesbloque']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
        ]);
    }

    public function actionActividadesbloque() {

        $alumno = $_POST['alumno'];
        $clase = $_POST['clase'];
        $bloque = $_POST['bloque'];


        $con = \Yii::$app->db;
        $query = "select 	c.id, a.title
                    from	scholaris_calificaciones c
                                    inner join scholaris_actividad a on a.id = c.idactividad
                    where	c.idalumno = $alumno
                                    and a.paralelo_id = $clase
                                    and a.bloque_actividad_id = $bloque
                                    and a.calificado = 'SI';";

        $resp = $con->createCommand($query)->queryAll();

        $data = ArrayHelper::map($resp, 'id', 'title');

        return Select2::widget([
                    'name' => 'calificacion',
                    'id' => 'calificacionId',
                    'value' => 0,
                    'data' => $data,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Actividad',
                    //'onchange' => 'mostrarActividades(this,"' . Url::to(['actividadesbloque']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
        ]);
    }

    public function actionMateriasmalla() {
        $tipo = $_POST['tipo'];
        $malla = $_POST['malla'];
        
        if($tipo == 'AREA'){
            $asignaturas = $this->get_areas($malla);
        }else{
            $asignaturas = $this->get_materias($malla);
        }
        
        $data = ArrayHelper::map($asignaturas, 'id', 'materia');
//
        return Select2::widget([
                    'name' => 'materia',
                    'id' => 'id',
                    'value' => 0,
                    'data' => $data,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Asignatura',
                        'required' => true
                    //'onchange' => 'mostrarActividades(this,"' . Url::to(['actividadesbloque']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
        ]);
        
        
    }
    
    private function get_materias($malla){
        $con = Yii::$app->db;
        $query = "select 	m.id
                                    ,m.name as materia
                    from 	scholaris_clase c
                                    inner join scholaris_materia m on m.id = c.idmateria
                    where 	c.idcurso in (
                                            select 	curso_id
                                            from 	scholaris_mec_v2_malla_curso
                                            where	malla_id = $malla	
                                    )
                    group by m.id, m.name
                    order by m.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_areas($malla){
        $con = Yii::$app->db;
        $query = "select 	ar.id
		,ar.name as materia
                from 	scholaris_clase c
                                inner join scholaris_malla_materia m on m.id = c.malla_materia
                                inner join scholaris_malla_area a on a.id = m.malla_area_id
                                inner join scholaris_area ar on ar.id = a.area_id
                where 	c.idcurso in (
                                        select 	curso_id
                                        from 	scholaris_mec_v2_malla_curso
                                        where	malla_id = $malla	
                                )
                group by ar.id, ar.name
                order by ar.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    
    /*****
     * inicia caja para padres por alumno
     * Proceso utilizado en la ficha del dece
     */
    
    public function actionPadres(){
        
        $alumnoId = $_POST['id'];
        
        $padres = $this->consulta_padres_alumno($alumnoId);
        
        $data = ArrayHelper::map($padres, 'id', 'padre');
//
        return Select2::widget([
                    'name' => 'materia',
                    'id' => 'id',
                    'value' => 0,
                    'data' => $data,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Padre Atendido',
                        'required' => true,
                    'onchange' => 'copiapadre(this);',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
        ]);
    }
    
    private function consulta_padres_alumno($alumnoId){
        $con = Yii::$app->db;
        $query = "select 	op.id, concat(rp.name) as padre
                    from 	op_parent op
                                    inner join res_partner rp on rp.id = op.name
                                    inner join op_parent_op_student_rel rel ON rel.op_parent_id = op.id
                    where 	rel.op_student_id = $alumnoId; ";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    //////// fin de consulta padres por alumno//////

}
