<?php

namespace app\models;

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
 * @property int $tipo_libreta
 *
 * @property ScholarisAccesoUsuarioClase[] $scholarisAccesoUsuarioClases
 * @property Usuario[] $usuarios
 * @property ScholarisAsistenciaClaseTema[] $scholarisAsistenciaClaseTemas
 * @property ScholarisAsistenciaProfesor[] $scholarisAsistenciaProfesors
 * @property OpFaculty $profesor
 * @property ScholarisMateria $materia
 * @property ScholarisClaseUsuarios[] $scholarisClaseUsuarios
 * @property ScholarisHorario[] $scholarisHorarios
 * @property ScholarisRegistroNotasFinalPromocion[] $scholarisRegistroNotasFinalPromocions
 * @property ScholarisReporteNotasClase[] $scholarisReporteNotasClases
 * @property ScholarisReporteNotasClasePrimeros[] $scholarisReporteNotasClasePrimeros
 * @property ScholarisResumenFinales[] $scholarisResumenFinales
 * @property ScholarisResumenParciales[] $scholarisResumenParciales
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
            [['idmateria', 'idprofesor', 'idcurso'], 'required'],
            [['idmateria', 'idprofesor', 'idcurso', 'paralelo_id', 'promedia', 'asignado_horario', 'todos_alumnos', 'tipo_libreta'], 'default', 'value' => null],
            [['idmateria', 'idprofesor', 'idcurso', 'paralelo_id', 'promedia', 'asignado_horario', 'todos_alumnos', 'tipo_libreta'], 'integer'],
            [['peso'], 'number'],
            [['periodo_scholaris', 'tipo_usu_bloque'], 'string', 'max' => 30],
            [['idprofesor'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['idprofesor' => 'id']],
            [['idmateria'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMateria::className(), 'targetAttribute' => ['idmateria' => 'id']],
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
            'tipo_libreta' => 'Tipo Libreta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAccesoUsuarioClases()
    {
        return $this->hasMany(ScholarisAccesoUsuarioClase::className(), ['clase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['usuario' => 'usuario'])->viaTable('scholaris_acceso_usuario_clase', ['clase_id' => 'id']);
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
    public function getProfesor()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'idprofesor']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(ScholarisMateria::className(), ['id' => 'idmateria']);
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
    public function getScholarisRegistroNotasFinalPromocions()
    {
        return $this->hasMany(ScholarisRegistroNotasFinalPromocion::className(), ['clase_id' => 'id']);
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
    
    
    public function getParalelo(){
        return $this->hasOne(\backend\models\OpCourseParalelo::className(), ['id' => 'paralelo_id']);
    }
}
