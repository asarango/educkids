<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_area_sup".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 */
class PlanAreaSup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_area_sup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre', 'orden'], 'required'],
            [['orden'], 'default', 'value' => null],
            [['orden'], 'integer'],
            [['codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 50],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'orden' => 'Orden',
        ];
    }
}
