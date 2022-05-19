<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_criterio".
 *
 * @property int $id
 * @property string $criterio
 * @property string $detalle
 * @property int $area_id
 *
 * @property ScholarisActividadDescriptor[] $scholarisActividadDescriptors
 */
class ScholarisCriterio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_criterio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['criterio', 'detalle'], 'required'],
            [['area_id'], 'default', 'value' => null],
            [['area_id'], 'integer'],
            [['criterio'], 'string', 'max' => 1],
            [['detalle'], 'string', 'max' => 200],
            [['detalle_alterno'], 'string'],
            [['codigo_idioma_alterno'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'criterio' => 'Criterio',
            'detalle' => 'Detalle',
            'area_id' => 'Area ID',
            'detalle_alterno' => 'Detalle de Idioma Alterno',
            'codigo_idioma_alterno' => 'Codigo Idioma Alterno',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadDescriptors()
    {
        return $this->hasMany(ScholarisActividadDescriptor::className(), ['criterio_id' => 'id']);
    }
}
