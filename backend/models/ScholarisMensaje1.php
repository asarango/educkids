<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mensaje1".
 *
 * @property int $id
 * @property string $mensaje
 * @property bool $estado
 * @property string $autor_usuario
 * @property string $para_usuario
 * @property string $fecha
 */
class ScholarisMensaje1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mensaje1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mensaje', 'estado'], 'required'],
            [['mensaje'], 'string'],
            [['estado'], 'boolean'],
            [['fecha'], 'safe'],
            [['autor_usuario', 'para_usuario'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mensaje' => 'Mensaje',
            'estado' => 'Estado',
            'autor_usuario' => 'Autor Usuario',
            'para_usuario' => 'Para Usuario',
            'fecha' => 'Fecha',
        ];
    }
}
