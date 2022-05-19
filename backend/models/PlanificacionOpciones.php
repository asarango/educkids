<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_opciones".
 *
 * @property int $id
 * @property string $tipo
 * @property string $categoria
 * @property string $opcion
 * @property string $seccion
 * @property bool $estado
 */
class PlanificacionOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'opcion', 'seccion'], 'required'],
            [['opcion'], 'string'],
            [['estado'], 'boolean'],
            [['tipo'], 'string', 'max' => 40],
            [['categoria'], 'string', 'max' => 50],
            [['seccion'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo' => 'Tipo',
            'categoria' => 'Categoria',
            'opcion' => 'Opcion',
            'seccion' => 'Seccion',
            'estado' => 'Estado',
        ];
    }
}
