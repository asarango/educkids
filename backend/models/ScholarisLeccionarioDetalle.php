<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_leccionario_detalle".
 *
 * @property int $id
 * @property int $paralelo_id
 * @property string $fecha
 * @property int $clase_id
 * @property int $hora_id
 * @property string $desde
 * @property string $hasta
 * @property int $asistencia_id
 * @property bool $falta
 * @property string $atraso
 * @property string $estado
 *
 * @property ScholarisClase $clase
 * @property ScholarisHorariov2Hora $hora
 * @property ScholarisLeccionario $paralelo
 */
class ScholarisLeccionarioDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_leccionario_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paralelo_id', 'fecha', 'clase_id', 'hora_id', 'desde', 'hasta', 'falta', 'estado'], 'required'],
            [['paralelo_id', 'clase_id', 'hora_id', 'asistencia_id'], 'default', 'value' => null],
            [['paralelo_id', 'clase_id', 'hora_id', 'asistencia_id'], 'integer'],
            [['fecha'], 'safe'],
            [['motivio_justificacion_falta'], 'string'],
            [['falta','justifica_falta','justifica_atraso'], 'boolean'],
            [['desde', 'hasta', 'atraso', 'estado'], 'string', 'max' => 30],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
            [['hora_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Hora::className(), 'targetAttribute' => ['hora_id' => 'id']],
            [['paralelo_id', 'fecha'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisLeccionario::className(), 'targetAttribute' => ['paralelo_id' => 'paralelo_id', 'fecha' => 'fecha']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'paralelo_id' => 'Paralelo ID',
            'fecha' => 'Fecha',
            'clase_id' => 'Clase ID',
            'hora_id' => 'Hora ID',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'asistencia_id' => 'Asistencia ID',
            'falta' => 'Falta',
            'atraso' => 'Atraso',
            'estado' => 'Estado',
            'motivio_justificacion_falta' => 'Motivo Juatificacion Falata',
            'justifica_falta' => 'Estado Justificación',
            'justifica_atraso' => 'Justificación Atraso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHora()
    {
        return $this->hasOne(ScholarisHorariov2Hora::className(), ['id' => 'hora_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParalelo()
    {
        return $this->hasOne(ScholarisLeccionario::className(), ['paralelo_id' => 'paralelo_id', 'fecha' => 'fecha']);
    }
}
