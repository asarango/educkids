<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property string $usuario
 * @property string $clave
 * @property string $email
 * @property int $rol_id
 * @property string $auth_key
 * @property string $access_token
 * @property bool $activo
 * @property int $numero_incremento
 * @property int $instituto_defecto
 * @property int $periodo_id
 *
 * @property Rol $rol
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario', 'email', 'rol_id'], 'required'],
            [['clave', 'auth_key', 'access_token'], 'string'],
            [['rol_id', 'instituto_defecto', 'periodo_id'], 'default', 'value' => null],
            [['rol_id', 'instituto_defecto', 'periodo_id'], 'integer'],
            [['activo'], 'boolean'],
            [['usuario'], 'string', 'max' => 150],
            [['email', 'avatar', 'firma'], 'string', 'max' => 200],
            [['usuario'], 'unique'],
            [['rol_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::className(), 'targetAttribute' => ['rol_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuario' => 'Usuario',
            'clave' => 'Clave',
            'email' => 'Email',
            'rol_id' => 'Rol ID',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'activo' => 'Activo',
            'numero_incremento' => 'Numero Incremento',
            'instituto_defecto' => 'Instituto Defecto',
            'periodo_id' => 'Periodo ID',
            'avatar' => 'Avatar',
            'firma' => 'Firma',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Rol::className(), ['id' => 'rol_id']);
    }
}
