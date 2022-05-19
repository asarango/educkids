<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message_para".
 *
 * @property int $id
 * @property int $message_id
 * @property string $para_usuario
 * @property string $estado
 * @property string $fecha_recepcion
 * @property string $fecha_lectura
 *
 * @property MessageHeader $message
 */
class MessagePara extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_para';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'para_usuario', 'estado', 'fecha_recepcion'], 'required'],
            [['message_id'], 'default', 'value' => null],
            [['message_id'], 'integer'],
            [['fecha_recepcion', 'fecha_lectura'], 'safe'],
            [['para_usuario'], 'string', 'max' => 200],
            [['estado'], 'string', 'max' => 30],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => MessageHeader::className(), 'targetAttribute' => ['message_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'para_usuario' => 'Para Usuario',
            'estado' => 'Estado',
            'fecha_recepcion' => 'Fecha Recepcion',
            'fecha_lectura' => 'Fecha Lectura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(MessageHeader::className(), ['id' => 'message_id']);
    }
}
