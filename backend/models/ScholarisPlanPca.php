<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pca".
 *
 * @property int $id
 * @property int $malla_materia_curriculo_id
 * @property int $malla_materia_institucion_id
 * @property int $curso_curriculo_id
 * @property int $curso_institucion_id
 * @property string $docentes
 * @property string $paralelos
 * @property int $nivel_educativo
 * @property int $carga_horaria_semanal
 * @property int $semanas_trabajo
 * @property int $aprendizaje_imprevistos
 * @property int $total_semanas_clase
 * @property int $total_periodos
 * @property int $revisado_por
 * @property int $aprobado_por
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 * @property string $estado
 *
 * @property OpCourse $cursoInstitucion
 * @property OpFaculty $revisadoPor
 * @property OpFaculty $aprobadoPor
 * @property ScholarisMallaMateria $mallaMateriaInstitucion
 */
class ScholarisPlanPca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pca';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_materia_curriculo_id', 'malla_materia_institucion_id', 'curso_curriculo_id', 'curso_institucion_id', 'docentes', 'paralelos', 'nivel_educativo', 'carga_horaria_semanal', 'semanas_trabajo', 'aprendizaje_imprevistos', 'total_semanas_clase', 'total_periodos', 'revisado_por', 'aprobado_por', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha', 'estado'], 'required'],
            [['malla_materia_curriculo_id', 'malla_materia_institucion_id', 'curso_curriculo_id', 'curso_institucion_id', 'nivel_educativo', 'carga_horaria_semanal', 'semanas_trabajo', 'aprendizaje_imprevistos', 'total_semanas_clase', 'total_periodos', 'revisado_por', 'aprobado_por'], 'default', 'value' => null],
            [['malla_materia_curriculo_id', 'malla_materia_institucion_id', 'curso_curriculo_id', 'curso_institucion_id', 'nivel_educativo', 'carga_horaria_semanal', 'semanas_trabajo', 'aprendizaje_imprevistos', 'total_semanas_clase', 'total_periodos', 'revisado_por', 'aprobado_por'], 'integer'],
            [['docentes'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['paralelos', 'creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['estado'], 'string', 'max' => 30],
            [['curso_institucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_institucion_id' => 'id']],
            [['revisado_por'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['revisado_por' => 'id']],
            [['aprobado_por'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['aprobado_por' => 'id']],
            [['malla_materia_institucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMallaMateria::className(), 'targetAttribute' => ['malla_materia_institucion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'malla_materia_curriculo_id' => 'Malla Materia Curriculo ID',
            'malla_materia_institucion_id' => 'Malla Materia Institucion ID',
            'curso_curriculo_id' => 'Curso Curriculo ID',
            'curso_institucion_id' => 'Curso Institucion ID',
            'docentes' => 'Docentes',
            'paralelos' => 'Paralelos',
            'nivel_educativo' => 'Nivel Educativo',
            'carga_horaria_semanal' => 'Carga Horaria Semanal',
            'semanas_trabajo' => 'Semanas Trabajo',
            'aprendizaje_imprevistos' => 'Aprendizaje Imprevistos',
            'total_semanas_clase' => 'Total Semanas Clase',
            'total_periodos' => 'Total Periodos',
            'revisado_por' => 'Revisado Por',
            'aprobado_por' => 'Aprobado Por',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoInstitucion()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'curso_institucion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisadoPor()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'revisado_por']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAprobadoPor()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'aprobado_por']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaMateriaInstitucion()
    {
        return $this->hasOne(ScholarisMallaMateria::className(), ['id' => 'malla_materia_institucion_id']);
    }
}
