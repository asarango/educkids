<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_pai_servicio_accion".
 *
 * @property int $id
 * @property int $planificacion_bloque_unidad_id
 * @property int $opcion_id
 * @property string $created
 * @property string $created_at
 *
 * @property PlanificacionBloquesUnidad $planificacionBloqueUnidad
 * @property PlanificacionOpciones $opcion
 */
class PudPaiServicioAccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_pai_servicio_accion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planificacion_bloque_unidad_id', 'opcion_id', 'created', 'created_at'], 'required'],
            [['planificacion_bloque_unidad_id', 'opcion_id'], 'default', 'value' => null],
            [['planificacion_bloque_unidad_id', 'opcion_id'], 'integer'],
            [['created_at'], 'safe'],
            [['created'], 'string', 'max' => 200],
            [['planificacion_bloque_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['planificacion_bloque_unidad_id' => 'id']],
            [['opcion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionOpciones::className(), 'targetAttribute' => ['opcion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planificacion_bloque_unidad_id' => 'Planificacion Bloque Unidad ID',
            'opcion_id' => 'Opcion ID',
            'created' => 'Created',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloqueUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'planificacion_bloque_unidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpcion()
    {
        return $this->hasOne(PlanificacionOpciones::className(), ['id' => 'opcion_id']);
    }
}
