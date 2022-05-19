<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_comportamiento_cambionota".
 *
 * @property int $id
 * @property string $nombre
 */
class ScholarisAsistenciaComportamientoCambionota extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_comportamiento_cambionota';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 150],
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
        ];
    }
}
