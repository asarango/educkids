<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_micro_observacion".
 *
 * @property int $id
 * @property int $micro_id
 * @property string $observacion
 *
 * @property KidsUnidadMicro $micro
 */
class KidsMicroObservacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_micro_observacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['micro_id', 'observacion'], 'required'],
            [['micro_id'], 'default', 'value' => null],
            [['micro_id'], 'integer'],
            [['observacion'], 'string'],
            [['micro_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidsUnidadMicro::className(), 'targetAttribute' => ['micro_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'micro_id' => 'Micro ID',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMicro()
    {
        return $this->hasOne(KidsUnidadMicro::className(), ['id' => 'micro_id']);
    }
}
