<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_violence_reason".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 *
 * @property OpPsychologicalAttention[] $opPsychologicalAttentions
 * @property ResUsers $createU
 * @property ResUsers $writeU
 */
class OpViolenceReason extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_violence_reason';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid'], 'default', 'value' => null],
            [['create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'required'],
            [['name'], 'string'],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPsychologicalAttentions()
    {
        return $this->hasMany(OpPsychologicalAttention::className(), ['violence_reason_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'create_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriteU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'write_uid']);
    }
}
