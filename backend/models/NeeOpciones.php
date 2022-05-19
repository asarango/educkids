<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "nee_opciones".
 *
 * @property int $id
 * @property string $codigo
 * @property string $categoria
 * @property int $orden
 * @property string $nombre
 * @property bool $estado
 */
class NeeOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nee_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'categoria', 'orden', 'nombre'], 'required'],
            [['orden'], 'default', 'value' => null],
            [['orden'], 'integer'],
            [['estado'], 'boolean'],
            [['codigo'], 'string', 'max' => 20],
            [['categoria'], 'string', 'max' => 40],
            [['nombre'], 'string', 'max' => 100],
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
            'categoria' => 'Categoria',
            'orden' => 'Orden',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }
}
