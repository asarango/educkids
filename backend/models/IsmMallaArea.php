<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_malla_area".
 *
 * @property int $id
 * @property int $area_id
 * @property int $periodo_malla_id
 * @property bool $promedia
 * @property bool $imprime_libreta
 * @property bool $es_cuantitativa
 * @property string $tipo
 * @property string $porcentaje
 *
 * @property IsmAreaMateria[] $ismAreaMaterias
 * @property IsmArea $area
 * @property IsmPeriodoMalla $periodoMalla
 */
class IsmMallaArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_malla_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_id', 'periodo_malla_id'], 'required'],
            [['area_id', 'periodo_malla_id'], 'default', 'value' => null],
            [['area_id', 'periodo_malla_id'], 'integer'],
            [['promedia', 'imprime_libreta', 'es_cuantitativa'], 'boolean'],
            [['porcentaje'], 'number'],
            [['tipo'], 'string', 'max' => 30],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmArea::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['periodo_malla_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmPeriodoMalla::className(), 'targetAttribute' => ['periodo_malla_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_id' => 'Area ID',
            'periodo_malla_id' => 'Periodo Malla ID',
            'promedia' => 'Promedia',
            'imprime_libreta' => 'Imprime Libreta',
            'es_cuantitativa' => 'Es Cuantitativa',
            'tipo' => 'Tipo',
            'porcentaje' => 'Porcentaje',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMaterias()
    {
        return $this->hasMany(IsmAreaMateria::className(), ['malla_area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(IsmArea::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoMalla()
    {
        return $this->hasOne(IsmPeriodoMalla::className(), ['id' => 'periodo_malla_id']);
    }
}
