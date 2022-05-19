<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_reporte_novedades_comportamiento".
 *
 * @property int $novedad_id
 * @property string $bloque
 * @property string $semana
 * @property string $fecha
 * @property string $hora
 * @property string $materia
 * @property string $estudiante
 * @property string $curso
 * @property string $paralelo
 * @property string $codigo
 * @property string $falta
 * @property string $observacion
 * @property string $justificacion
 * @property string $usuario
 */
class ScholarisReporteNovedadesComportamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_reporte_novedades_comportamiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['novedad_id', 'estudiante', 'curso', 'paralelo', 'codigo', 'falta'], 'required'],
            [['novedad_id'], 'default', 'value' => null],
            [['novedad_id'], 'integer'],
            [['fecha'], 'safe'],
            [['observacion', 'justificacion'], 'string'],
            [['bloque', 'semana', 'hora', 'materia'], 'string', 'max' => 50],
            [['estudiante', 'falta'], 'string', 'max' => 150],
            [['curso'], 'string', 'max' => 40],
            [['paralelo', 'codigo'], 'string', 'max' => 10],
            [['usuario'], 'string', 'max' => 200],
            [['novedad_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'novedad_id' => 'Novedad ID',
            'bloque' => 'Bloque',
            'semana' => 'Semana',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'materia' => 'Materia',
            'estudiante' => 'Estudiante',
            'curso' => 'Curso',
            'paralelo' => 'Paralelo',
            'codigo' => 'Codigo',
            'falta' => 'Falta',
            'observacion' => 'Observacion',
            'justificacion' => 'Justificacion',
            'usuario' => 'Usuario',
        ];
    }
}
