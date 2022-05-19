<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_parametros_opciones".
 *
 * @property int $id
 * @property int $parametro_id
 * @property string $codigo
 * @property string $nombre
 * @property string $valor
 */
class ScholarisParametrosOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_parametros_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parametro_id', 'codigo', 'nombre', 'valor'], 'required'],
            [['parametro_id'], 'default', 'value' => null],
            [['parametro_id'], 'integer'],
            [['codigo', 'valor'], 'string', 'max' => 100],
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
            'parametro_id' => 'Parametro ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'valor' => 'Valor',
        ];
    }
}
