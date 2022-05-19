<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_pai_descriptores".
 *
 * @property int $id
 * @property int $plan_unidad_id
 * @property int $descriptor_id
 *
 * @property IsmCriterioDescriptorArea $descriptor
 * @property PlanificacionBloquesUnidad $planUnidad
 */
class PlanificacionVerticalPaiDescriptores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_pai_descriptores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_unidad_id', 'descriptor_id'], 'required'],
            [['plan_unidad_id', 'descriptor_id'], 'default', 'value' => null],
            [['plan_unidad_id', 'descriptor_id'], 'integer'],
            [['descriptor_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmCriterioDescriptorArea::className(), 'targetAttribute' => ['descriptor_id' => 'id']],
            [['plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['plan_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_unidad_id' => 'Plan Unidad ID',
            'descriptor_id' => 'Descriptor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDescriptor()
    {
        return $this->hasOne(IsmCriterioDescriptorArea::className(), ['id' => 'descriptor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'plan_unidad_id']);
    }
}
