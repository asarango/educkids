<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_justificacion_profesor".
 *
 * @property int $id
 * @property int $asistencia_id
 * @property string $fecha
 * @property int $usuario_crea
 * @property int $codigo_persona
 * @property int $tipo_persona
 * @property string $motivo_justificacion
 * @property int $opcion_justificacion_id
 * @property int $estado
 * @property string $fecha_registro
 * @property int $hora_registro
 * @property string $tiempo_justificado
 */
class ScholarisAsistenciaJustificacionProfesor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_justificacion_profesor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asistencia_id', 'usuario_crea', 'codigo_persona', 'tipo_persona', 'opcion_justificacion_id', 'estado', 'hora_registro'], 'default', 'value' => null],
            [['asistencia_id', 'usuario_crea', 'codigo_persona', 'tipo_persona', 'opcion_justificacion_id', 'estado', 'hora_registro'], 'integer'],
            [['fecha', 'usuario_crea', 'codigo_persona', 'tipo_persona', 'opcion_justificacion_id'], 'required'],
            [['fecha', 'fecha_registro', 'tiempo_justificado'], 'safe'],
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
            'asistencia_id' => 'Asistencia ID',
            'fecha' => 'Fecha',
            'usuario_crea' => 'Usuario Crea',
            'codigo_persona' => 'Codigo Persona',
            'tipo_persona' => 'Tipo Persona',
            'motivo_justificacion' => 'Motivo Justificacion',
            'opcion_justificacion_id' => 'Opcion Justificacion ID',
            'estado' => 'Estado',
            'fecha_registro' => 'Fecha Registro',
            'hora_registro' => 'Hora Registro',
            'tiempo_justificado' => 'Tiempo Justificado',
        ];
    }
}
