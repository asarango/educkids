<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_malla_area".
 *
 * @property int $id
 * @property int $malla_id
 * @property int $area_id
 * @property bool $se_imprime
 * @property bool $promedia
 * @property int $total_porcentaje
 * @property bool $es_cuantitativa
 * @property string $tipo
 *
 * @property ScholarisArea $area
 * @property ScholarisMalla $malla
 * @property ScholarisMallaMateria[] $scholarisMallaMaterias
 */
class ScholarisMallaArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_malla_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_id', 'area_id', 'se_imprime', 'promedia', 'total_porcentaje', 'es_cuantitativa', 'tipo', 'orden'], 'required'],
            [['malla_id', 'area_id', 'total_porcentaje'], 'default', 'value' => null],
            [['malla_id', 'area_id', 'total_porcentaje','orden'], 'integer'],
            [['se_imprime', 'promedia', 'es_cuantitativa'], 'boolean'],
            [['tipo'], 'string', 'max' => 30],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisArea::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['malla_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMalla::className(), 'targetAttribute' => ['malla_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'malla_id' => 'Malla ID',
            'area_id' => 'Area ID',
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
    public function getArea()
    {
        return $this->hasOne(ScholarisArea::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMalla()
    {
        return $this->hasOne(ScholarisMalla::className(), ['id' => 'malla_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMallaMaterias()
    {
        return $this->hasMany(ScholarisMallaMateria::className(), ['malla_area_id' => 'id']);
    }
}
