<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_op_period_periodo_scholaris".
 *
 * @property int $scholaris_id
 * @property int $op_id
 *
 * @property OpPeriod $op
 * @property ScholarisPeriodo $scholaris
 */
class ScholarisOpPeriodPeriodoScholaris extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_op_period_periodo_scholaris';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scholaris_id', 'op_id'], 'required'],
            [['scholaris_id', 'op_id'], 'default', 'value' => null],
            [['scholaris_id', 'op_id'], 'integer'],
            [['scholaris_id', 'op_id'], 'unique', 'targetAttribute' => ['scholaris_id', 'op_id']],
            [['op_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPeriod::className(), 'targetAttribute' => ['op_id' => 'id']],
            [['scholaris_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'scholaris_id' => 'Scholaris ID',
            'op_id' => 'Op ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOp()
    {
        return $this->hasOne(OpPeriod::className(), ['id' => 'op_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholaris()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_id']);
    }
}
