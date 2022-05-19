<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_boletin_pai_contenidos".
 *
 * @property int $id
 * @property string $codigo
 * @property string $detalle
 * @property int $orden
 * @property string $tipo
 * @property string $url
 * @property string $descripcion
 */
class ScholarisBoletinPaiContenidos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_boletin_pai_contenidos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'detalle', 'orden', 'tipo'], 'required'],
            [['orden'], 'default', 'value' => null],
            [['orden'], 'integer'],
            [['descripcion'], 'string'],
            [['codigo'], 'string', 'max' => 30],
            [['detalle'], 'string', 'max' => 100],
            [['tipo'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 150],
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
            'detalle' => 'Detalle',
            'orden' => 'Orden',
            'tipo' => 'Tipo',
            'url' => 'Url',
            'descripcion' => 'Descripcion',
        ];
    }
}
