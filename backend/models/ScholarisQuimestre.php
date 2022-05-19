<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_quimestre".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property string $tipo_quimestre
 * @property int $orden
 * @property string $estado
 * @property string $abreviatura
 */
class ScholarisQuimestre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_quimestre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre'], 'required'],
            [['orden'], 'default', 'value' => null],
            [['orden'], 'integer'],
            [['codigo', 'tipo_quimestre', 'estado', 'abreviatura'], 'string', 'max' => 30],
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
            'tipo_quimestre' => 'Tipo Quimestre',
            'orden' => 'Orden',
            'estado' => 'Estado',
            'abreviatura' => 'Abreviatura',
        ];
    }
}
