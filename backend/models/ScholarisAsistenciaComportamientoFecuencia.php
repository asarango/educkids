<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_comportamiento_fecuencia".
 *
 * @property int $id
 * @property int $detalle_id
 * @property int $fecuencia
 * @property string $puntos
 * @property int $accion
 * @property string $observacion
 * @property string $alerta
 *
 * @property ScholarisAsistenciaComportamientoDetalle $detalle
 */
class ScholarisAsistenciaComportamientoFecuencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_comportamiento_fecuencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detalle_id', 'fecuencia', 'puntos', 'accion', 'observacion'], 'required'],
            [['detalle_id', 'fecuencia', 'accion'], 'default', 'value' => null],
            [['detalle_id', 'fecuencia', 'accion'], 'integer'],
            [['puntos'], 'number'],
            [['observacion', 'alerta'], 'string'],
            [['detalle_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisAsistenciaComportamientoDetalle::className(), 'targetAttribute' => ['detalle_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detalle_id' => 'Detalle ID',
            'fecuencia' => 'Fecuencia',
            'puntos' => 'Puntos',
            'accion' => 'Accion',
            'observacion' => 'Observacion',
            'alerta' => 'Alerta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalle()
    {
        return $this->hasOne(ScholarisAsistenciaComportamientoDetalle::className(), ['id' => 'detalle_id']);
    }
}
