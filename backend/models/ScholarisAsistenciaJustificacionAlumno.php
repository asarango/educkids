<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_justificacion_alumno".
 *
 * @property int $id
 * @property int $novedad_id
 * @property string $fecha
 * @property int $usuario_crea
 * @property int $codigo_persona
 * @property int $tipo_persona
 * @property string $motivo_justificacion
 * @property int $opcion_justificacion_id
 * @property int $estado
 */
class ScholarisAsistenciaJustificacionAlumno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_justificacion_alumno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['novedad_id', 'usuario_crea', 'codigo_persona', 'tipo_persona', 'opcion_justificacion_id', 'estado'], 'default', 'value' => null],
            [['novedad_id', 'usuario_crea', 'codigo_persona', 'tipo_persona', 'opcion_justificacion_id', 'estado'], 'integer'],
            [['fecha', 'usuario_crea', 'codigo_persona', 'tipo_persona', 'opcion_justificacion_id'], 'required'],
            [['fecha'], 'safe'],
            [['motivo_justificacion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'novedad_id' => 'Novedad ID',
            'fecha' => 'Fecha',
            'usuario_crea' => 'Usuario Crea',
            'codigo_persona' => 'Codigo Persona',
            'tipo_persona' => 'Tipo Persona',
            'motivo_justificacion' => 'Motivo Justificacion',
            'opcion_justificacion_id' => 'Opcion Justificacion ID',
            'estado' => 'Estado',
        ];
    }
}
