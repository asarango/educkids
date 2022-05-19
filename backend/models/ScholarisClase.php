<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_clase".
 *
 * @property int $id
 * @property int $idmateria
 * @property int $idprofesor
 * @property int $idcurso
 * @property int $paralelo_id
 * @property string $peso
 * @property string $periodo_scholaris
 * @property int $promedia
 * @property int $asignado_horario
 * @property string $tipo_usu_bloque
 * @property int $todos_alumnos
 * @property int $malla_materia
 * @property string $materia_curriculo_codigo
 * @property string $codigo_curso_curriculo
 * @property string $fecha_cierre
 * @property string $fecha_activacion
 * @property bool $estado_cierre
 * @property int $rector_id
 * @property int $coordinador_dece_id
 * @property int $secretaria_id
 * @property int $coordinador_academico_id
 * @property int $inspector_id
 * @property int $dece_dhi_id
 * @property int $tutor_id
 * @property int $ism_area_materia_id
 * @property bool $es_activo
 *
 * @property NeeXClase[] $neeXClases
 * @property ScholarisAlumnoRetiradoClase[] $scholarisAlumnoRetiradoClases
 * @property ScholarisArchivosPud[] $scholarisArchivosPuds
 * @property ScholarisAsistenciaClaseTema[] $scholarisAsistenciaClaseTemas
 * @property ScholarisAsistenciaProfesor[] $scholarisAsistenciaProfesors
 * @property IsmAreaMateria $ismAreaMateria
 * @property OpCourseParalelo $paralelo
 * @property OpFaculty $profesor
 * @property OpInstituteAuthorities $rector
 * @property OpInstituteAuthorities $coordinadorDece
 * @property OpInstituteAuthorities $secretaria
 * @property OpInstituteAuthorities $coordinadorAcademico
 * @property OpInstituteAuthorities $inspector
 * @property OpInstituteAuthorities $deceDhi
 * @property OpInstituteAuthorities $tutor
 * @property ScholarisClaseUsuarios[] $scholarisClaseUsuarios
 * @property ScholarisHorario[] $scholarisHorarios
 * @property ScholarisHorariov2Horario[] $scholarisHorariov2Horarios
 * @property ScholarisLeccionarioDetalle[] $scholarisLeccionarioDetalles
 * @property ScholarisPlanInicial[] $scholarisPlanInicials
 * @property ScholarisRegistroNotasFinalPromocion[] $scholarisRegistroNotasFinalPromocions
 * @property ScholarisRepLibreta[] $scholarisRepLibretas
 * @property ScholarisReporteNotasClase[] $scholarisReporteNotasClases
 * @property ScholarisReporteNotasClasePrimeros[] $scholarisReporteNotasClasePrimeros
 * @property ScholarisResumenFinales[] $scholarisResumenFinales
 * @property ScholarisResumenParciales[] $scholarisResumenParciales
 * @property ScholarisTareaInicial[] $scholarisTareaInicials
 */
class ScholarisClase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_clase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idmateria', 'idprofesor', 'idcurso', 'paralelo_id', 'promedia', 'asignado_horario', 'todos_alumnos', 'malla_materia', 'rector_id', 'coordinador_dece_id', 'secretaria_id', 'coordinador_academico_id', 'inspector_id', 'dece_dhi_id', 'tutor_id', 'ism_area_materia_id'], 'default', 'value' => null],
            [['idmateria', 'idprofesor', 'idcurso', 'paralelo_id', 'promedia', 'asignado_horario', 'todos_alumnos', 'malla_materia', 'rector_id', 'coordinador_dece_id', 'secretaria_id', 'coordinador_academico_id', 'inspector_id', 'dece_dhi_id', 'tutor_id', 'ism_area_materia_id'], 'integer'],
            [['idprofesor'], 'required'],
            [['peso'], 'number'],
            [['fecha_cierre', 'fecha_activacion'], 'safe'],
            [['estado_cierre', 'es_activo'], 'boolean'],
            [['periodo_scholaris', 'tipo_usu_bloque'], 'string', 'max' => 30],
            [['materia_curriculo_codigo', 'codigo_curso_curriculo'], 'string', 'max' => 10],
            [['ism_area_materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmAreaMateria::className(), 'targetAttribute' => ['ism_area_materia_id' => 'id']],
            [['paralelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['paralelo_id' => 'id']],
            [['idprofesor'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['idprofesor' => 'id']],
            [['rector_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['rector_id' => 'id']],
            [['coordinador_dece_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['coordinador_dece_id' => 'id']],
            [['secretaria_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['secretaria_id' => 'id']],
            [['coordinador_academico_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['coordinador_academico_id' => 'id']],
            [['inspector_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['inspector_id' => 'id']],
            [['dece_dhi_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['dece_dhi_id' => 'id']],
            [['tutor_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstituteAuthorities::className(), 'targetAttribute' => ['tutor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idmateria' => 'Idmateria',
            'idprofesor' => 'Idprofesor',
            'idcurso' => 'Idcurso',
            'paralelo_id' => 'Paralelo ID',
            'peso' => 'Peso',
            'periodo_scholaris' => 'Periodo Scholaris',
            'promedia' => 'Promedia',
            'asignado_horario' => 'Asignado Horario',
            'tipo_usu_bloque' => 'Tipo Usu Bloque',
            'todos_alumnos' => 'Todos Alumnos',
            'malla_materia' => 'Malla Materia',
            'materia_curriculo_codigo' => 'Materia Curriculo Codigo',
            'codigo_curso_curriculo' => 'Codigo Curso Curriculo',
            'fecha_cierre' => 'Fecha Cierre',
            'fecha_activacion' => 'Fecha Activacion',
            'estado_cierre' => 'Estado Cierre',
            'rector_id' => 'Rector ID',
            'coordinador_dece_id' => 'Coordinador Dece ID',
            'secretaria_id' => 'Secretaria ID',
            'coordinador_academico_id' => 'Coordinador Academico ID',
            'inspector_id' => 'Inspector ID',
            'dece_dhi_id' => 'Dece Dhi ID',
            'tutor_id' => 'Tutor ID',
            'ism_area_materia_id' => 'Ism Area Materia ID',
            'es_activo' => 'Es Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNeeXClases()
    {
        return $this->hasMany(NeeXClase::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAlumnoRetiradoClases()
    {
        return $this->hasMany(ScholarisAlumnoRetiradoClase::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisArchivosPuds()
    {
        return $this->hasMany(ScholarisArchivosPud::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaClaseTemas()
    {
        return $this->hasMany(ScholarisAsistenciaClaseTema::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaProfesors()
    {
        return $this->hasMany(ScholarisAsistenciaProfesor::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMateria()
    {
        return $this->hasOne(IsmAreaMateria::className(), ['id' => 'ism_area_materia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParalelo()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'paralelo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesor()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'idprofesor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRector()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'rector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoordinadorDece()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'coordinador_dece_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecretaria()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'secretaria_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoordinadorAcademico()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'coordinador_academico_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInspector()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'inspector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeceDhi()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'dece_dhi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTutor()
    {
        return $this->hasOne(OpInstituteAuthorities::className(), ['id' => 'tutor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisClaseUsuarios()
    {
        return $this->hasMany(ScholarisClaseUsuarios::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisHorarios()
    {
        return $this->hasMany(ScholarisHorario::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisHorariov2Horarios()
    {
        return $this->hasMany(ScholarisHorariov2Horario::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisLeccionarioDetalles()
    {
        return $this->hasMany(ScholarisLeccionarioDetalle::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPlanInicials()
    {
        return $this->hasMany(ScholarisPlanInicial::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisRegistroNotasFinalPromocions()
    {
        return $this->hasMany(ScholarisRegistroNotasFinalPromocion::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisRepLibretas()
    {
        return $this->hasMany(ScholarisRepLibreta::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisReporteNotasClases()
    {
        return $this->hasMany(ScholarisReporteNotasClase::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisReporteNotasClasePrimeros()
    {
        return $this->hasMany(ScholarisReporteNotasClasePrimeros::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisResumenFinales()
    {
        return $this->hasMany(ScholarisResumenFinales::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisResumenParciales()
    {
        return $this->hasMany(ScholarisResumenParciales::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisTareaInicials()
    {
        return $this->hasMany(ScholarisTareaInicial::className(), ['clase_id' => 'id']);
    }
}
