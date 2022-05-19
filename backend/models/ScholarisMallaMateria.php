<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_malla_materia".
 *
 * @property int $id
 * @property int $malla_area_id
 * @property int $materia_id
 * @property bool $se_imprime
 * @property bool $promedia
 * @property int $total_porcentaje
 * @property bool $es_cuantitativa
 * @property string $tipo
 * @property int $orden
 *
 * @property ScholarisMallaArea $mallaArea
 * @property ScholarisMateria $materia
 */
class ScholarisMallaMateria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_malla_materia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_area_id', 'materia_id', 'se_imprime', 'promedia', 'total_porcentaje', 'es_cuantitativa', 'tipo', 'orden'], 'required'],
            [['malla_area_id', 'materia_id', 'total_porcentaje', 'orden'], 'default', 'value' => null],
            [['malla_area_id', 'materia_id', 'orden'], 'integer'],
            [['total_porcentaje'], 'number'],
            [['se_imprime', 'promedia', 'es_cuantitativa'], 'boolean'],
            [['tipo'], 'string', 'max' => 30],
            [['malla_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMallaArea::className(), 'targetAttribute' => ['malla_area_id' => 'id']],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMateria::className(), 'targetAttribute' => ['materia_id' => 'id']],
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
            'se_imprime' => 'Se Imprime',
            'promedia' => 'Promedia',
            'total_porcentaje' => 'Total Porcentaje',
            'es_cuantitativa' => 'Es Cuantitativa',
            'tipo' => 'Tipo',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaArea()
    {
        return $this->hasOne(ScholarisMallaArea::className(), ['id' => 'malla_area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(ScholarisMateria::className(), ['id' => 'materia_id']);
    }
}
