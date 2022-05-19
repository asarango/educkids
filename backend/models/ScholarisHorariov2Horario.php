<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_horariov2_horario".
 *
 * @property int $detalle_id
 * @property int $clase_id
 */
class ScholarisHorariov2Horario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_horariov2_horario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detalle_id', 'clase_id'], 'required'],
            [['detalle_id', 'clase_id'], 'default', 'value' => null],
            [['detalle_id', 'clase_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'detalle_id' => 'Detalle ID',
            'clase_id' => 'Clase ID',
        ];
    }
}
