<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_clase_tema".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $hora_id
 * @property int $asistencia_profesor_id
 * @property string $tema
 */
class ScholarisAsistenciaClaseTema extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_clase_tema';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'hora_id', 'asistencia_profesor_id', 'tema'], 'required'],
            [['clase_id', 'hora_id', 'asistencia_profesor_id'], 'default', 'value' => null],
            [['clase_id', 'hora_id', 'asistencia_profesor_id'], 'integer'],
            [['tema', 'observacion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'hora_id' => 'Hora ID',
            'asistencia_profesor_id' => 'Asistencia Profesor ID',
            'tema' => 'Tema',
            'observacion' => 'Observacion',
        ];
    }
}
