<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_area_materia".
 *
 * @property int $id
 * @property int $malla_area_id
 * @property int $materia_id
 * @property bool $promedia
 * @property string $porcentaje
 * @property bool $imprime_libreta
 * @property bool $es_cuantitativa
 * @property string $tipo
 * @property int $asignatura_curriculo_id
 * @property int $curso_curriculo_id
 *
 * @property CurriculoMecAsignatutas $asignaturaCurriculo
 * @property CurriculoMecNiveles $cursoCurriculo
 * @property IsmMallaArea $mallaArea
 * @property IsmMateria $materia
 * @property PlanificacionDesagregacionCabecera[] $planificacionDesagregacionCabeceras
 * @property ScholarisClase[] $scholarisClases
 */
class IsmAreaMateria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_area_materia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_area_id', 'materia_id'], 'required'],
            [['malla_area_id', 'materia_id', 'asignatura_curriculo_id', 'curso_curriculo_id'], 'default', 'value' => null],
            [['malla_area_id', 'materia_id', 'asignatura_curriculo_id', 'curso_curriculo_id'], 'integer'],
            [['promedia', 'imprime_libreta', 'es_cuantitativa'], 'boolean'],
            [['porcentaje'], 'number'],
            [['tipo'], 'string', 'max' => 30],
            [['asignatura_curriculo_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecAsignatutas::className(), 'targetAttribute' => ['asignatura_curriculo_id' => 'id']],
            [['curso_curriculo_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecNiveles::className(), 'targetAttribute' => ['curso_curriculo_id' => 'id']],
            [['malla_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmMallaArea::className(), 'targetAttribute' => ['malla_area_id' => 'id']],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmMateria::className(), 'targetAttribute' => ['materia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'malla_area_id' => 'Malla Area ID',
            'materia_id' => 'Materia ID',
            'promedia' => 'Promedia',
            'porcentaje' => 'Porcentaje',
            'imprime_libreta' => 'Imprime Libreta',
            'es_cuantitativa' => 'Es Cuantitativa',
            'tipo' => 'Tipo',
            'asignatura_curriculo_id' => 'Asignatura Curriculo ID',
            'curso_curriculo_id' => 'Curso Curriculo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCurriculo()
    {
        return $this->hasOne(CurriculoMecAsignatutas::className(), ['id' => 'asignatura_curriculo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoCurriculo()
    {
        return $this->hasOne(CurriculoMecNiveles::className(), ['id' => 'curso_curriculo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaArea()
    {
        return $this->hasOne(IsmMallaArea::className(), ['id' => 'malla_area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(IsmMateria::className(), ['id' => 'materia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionDesagregacionCabeceras()
    {
        return $this->hasMany(PlanificacionDesagregacionCabecera::className(), ['ism_area_materia_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisClases()
    {
        return $this->hasMany(ScholarisClase::className(), ['ism_area_materia_id' => 'id']);
    }
}
