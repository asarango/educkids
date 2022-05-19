<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rol".
 *
 * @property int $id
 * @property string $rol
 *
 * @property RolOperacion[] $rolOperacions
 * @property Operacion[] $operacions
 * @property Usuario[] $usuarios
 */
class Rol extends \yii\db\ActiveRecord
{
    
    public $rolOperaciones;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rol'], 'string', 'max' => 30],
            [['tipo_dashboard'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rol' => 'Rol',
            'tipo_dashboard' => 'Tipo Dashboard'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolOperacions()
    {
        return $this->hasMany(RolOperacion::className(), ['rol_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperacions()
    {
        return $this->hasMany(Operacion::className(), ['id' => 'operacion_id'])->viaTable('rol_operacion', ['rol_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['rol_id' => 'id']);
    }
    
    public function getOperacionPermitidas()
    {
        return $this->hasMany(Operacion::className(), ['id' => 'operacion_id'])->viaTable('rol_operacion', ['rol_id' => 'id']);
    }
}
