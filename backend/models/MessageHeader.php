<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message_header".
 *
 * @property int $id
 * @property string $remite_usuario
 * @property string $created_at
 * @property string $updated_at
 * @property string $asunto
 * @property string $texto
 * @property string $aplicacion_origen
 * @property string $tabla_origen
 * @property int $tabla_origen_id
 *
 * @property MessageAdjunto[] $messageAdjuntos
 * @property Usuario $remiteUsuario
 * @property MessagePara[] $messageParas
 */
class MessageHeader extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_header';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remite_usuario', 'created_at', 'updated_at', 'asunto', 'texto', 'aplicacion_origen', 'tabla_origen'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['texto'], 'string'],
            [['tabla_origen_id'], 'default', 'value' => null],
            [['tabla_origen_id'], 'integer'],
            [['remite_usuario'], 'string', 'max' => 200],
            [['asunto'], 'string', 'max' => 100],
            [['aplicacion_origen'], 'string', 'max' => 30],
            [['tabla_origen'], 'string', 'max' => 50],
            [['remite_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['remite_usuario' => 'usuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'remite_usuario' => 'Remite Usuario',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'asunto' => 'Asunto',
            'texto' => 'Texto',
            'aplicacion_origen' => 'Aplicacion Origen',
            'tabla_origen' => 'Tabla Origen',
            'tabla_origen_id' => 'Tabla Origen ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageAdjuntos()
    {
        return $this->hasMany(MessageAdjunto::className(), ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemiteUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usuario' => 'remite_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageParas()
    {
        return $this->hasMany(MessagePara::className(), ['message_id' => 'id']);
    }
}
