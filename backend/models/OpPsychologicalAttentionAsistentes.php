<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_psychological_attention_asistentes".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $write_uid Last Updated by
 * @property int $psychological_attention_id Atencion psicologica
 * @property string $write_date Last Updated on
 *
 * @property OpPsychologicalAttention $psychologicalAttention
 * @property ResUsers $createU
 * @property ResUsers $writeU
 */
class OpPsychologicalAttentionAsistentes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_psychological_attention_asistentes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'psychological_attention_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'psychological_attention_id'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'string'],
            [['psychological_attention_id'], 'required'],
            [['psychological_attention_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPsychologicalAttention::className(), 'targetAttribute' => ['psychological_attention_id' => 'id']],
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
            'psychological_attention_id' => 'Psychological Attention ID',
            'write_date' => 'Write Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPsychologicalAttention()
    {
        return $this->hasOne(OpPsychologicalAttention::className(), ['id' => 'psychological_attention_id']);
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
