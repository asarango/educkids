<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_actividad_descriptor".
 *
 * @property int $id
 * @property int $actividad_id
 * @property int $criterio_id
 * @property int $detalle_id
 */
class ScholarisActividadDescriptor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_actividad_descriptor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'criterio_id', 'detalle_id'], 'required'],
            [['actividad_id', 'criterio_id', 'detalle_id'], 'default', 'value' => null],
            [['actividad_id', 'criterio_id', 'detalle_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'actividad_id' => 'Actividad ID',
            'criterio_id' => 'Criterio ID',
            'detalle_id' => 'Detalle ID',
        ];
    }
    
    public function getCriterio(){
        return $this->hasOne(ScholarisCriterio::className(), ['id' => 'criterio_id']);
    }
    
    public function getDetalle(){
        return $this->hasOne(ScholarisCriterioDetalle::className(), ['id' => 'detalle_id']);
    }
}
