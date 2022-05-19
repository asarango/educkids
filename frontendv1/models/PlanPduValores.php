<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "plan_pdu_valores".
 *
 * @property int $id
 * @property int $cabecera_id
 * @property int $parametro_id
 *
 * @property PlanPduCabecera $cabecera
 * @property PlanPduParametros $parametro
 */
class PlanPduValores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_pdu_valores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cabecera_id', 'parametro_id'], 'required'],
            [['cabecera_id', 'parametro_id'], 'default', 'value' => null],
            [['cabecera_id', 'parametro_id'], 'integer'],
            [['cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanPduCabecera::className(), 'targetAttribute' => ['cabecera_id' => 'id']],
            [['parametro_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\PlanPduParametros::className(), 'targetAttribute' => ['parametro_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cabecera_id' => 'Cabecera ID',
            'parametro_id' => 'Parametro ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCabecera()
    {
        return $this->hasOne(PlanPduCabecera::className(), ['id' => 'cabecera_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParametro()
    {
        return $this->hasOne(\backend\models\PlanPduParametros::className(), ['id' => 'parametro_id']);
    }
}
