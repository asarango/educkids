<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "operacion".
 *
 * @property int $id
 * @property int $menu_id
 * @property string $operacion
 * @property string $nombre
 *
 * @property Menu $menu
 * @property RolOperacion[] $rolOperacions
 * @property Rol[] $rols
 */
class Operacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'operacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'operacion', 'nombre','ruta_icono'], 'required'],
            [['menu_id'], 'default', 'value' => null],
            [['menu_id'], 'integer'],
            [['operacion', 'ruta_icono'], 'string', 'max' => 255],
            [['nombre'], 'string', 'max' => 80],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'operacion' => 'Operacion',
            'nombre' => 'Nombre',
            'ruta_icono' => 'Icono',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolOperacions()
    {
        return $this->hasMany(RolOperacion::className(), ['operacion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRols()
    {
        return $this->hasMany(Rol::className(), ['id' => 'rol_id'])->viaTable('rol_operacion', ['operacion_id' => 'id']);
    }
}
