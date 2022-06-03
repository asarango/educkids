<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "kids_micro_objetivos".
 *
 * @property int $id
 * @property int $micro_id
 * @property int $objetivo_id
 * @property string $created_at
 * @property string $created
 *
 * @property CurCurriculoObjetivoIntegrador $objetivo
 * @property KidsUnidadMicro $micro
 */
class KidsMicroObjetivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kids_micro_objetivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['micro_id', 'objetivo_id', 'created_at', 'created'], 'required'],
            [['micro_id', 'objetivo_id'], 'default', 'value' => null],
            [['micro_id', 'objetivo_id'], 'integer'],
            [['created_at'], 'safe'],
            [['created'], 'string', 'max' => 200],
            [['objetivo_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurCurriculoObjetivoIntegrador::className(), 'targetAttribute' => ['objetivo_id' => 'id']],
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
            'objetivo_id' => 'Objetivo ID',
            'created_at' => 'Created At',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivo()
    {
        return $this->hasOne(CurCurriculoObjetivoIntegrador::className(), ['id' => 'objetivo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMicro()
    {
        return $this->hasOne(KidsUnidadMicro::className(), ['id' => 'micro_id']);
    }
}
