<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_comparte".
 *
 * @property int $id
 * @property string $nombre
 * @property int $valor
 */
class ScholarisBloqueComparte extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_comparte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'valor'], 'required'],
            [['valor'], 'default', 'value' => null],
            [['valor'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'valor' => 'Valor',
        ];
    }
}
