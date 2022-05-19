<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contenido_pai_opciones".
 *
 * @property int $id
 * @property string $tipo
 * @property string $contenido_es
 * @property string $contenido_en
 * @property string $contenido_fr
 * @property bool $estado
 */
class ContenidoPaiOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contenido_pai_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'contenido_es'], 'required'],
            [['contenido_es', 'contenido_en', 'contenido_fr'], 'string'],
            [['estado'], 'boolean'],
            [['tipo'], 'string', 'max' => 50],
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
            'contenido_es' => 'Contenido Es',
            'contenido_en' => 'Contenido En',
            'contenido_fr' => 'Contenido Fr',
            'estado' => 'Estado',
        ];
    }
}
