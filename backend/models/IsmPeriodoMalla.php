<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_periodo_malla".
 *
 * @property int $id
 * @property int $malla_id
 * @property int $scholaris_periodo_id
 *
 * @property IsmMallaArea[] $ismMallaAreas
 * @property IsmMalla $malla
 * @property ScholarisPeriodo $scholarisPeriodo
 */
class IsmPeriodoMalla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_periodo_malla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_id', 'scholaris_periodo_id'], 'required'],
            [['malla_id', 'scholaris_periodo_id'], 'default', 'value' => null],
            [['malla_id', 'scholaris_periodo_id'], 'integer'],
            [['malla_id'], 'exist', 'skipOnError' => true, 'targetClass' => IsmMalla::className(), 'targetAttribute' => ['malla_id' => 'id']],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
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
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmMallaAreas()
    {
        return $this->hasMany(IsmMallaArea::className(), ['periodo_malla_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMalla()
    {
        return $this->hasOne(IsmMalla::className(), ['id' => 'malla_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }
}
