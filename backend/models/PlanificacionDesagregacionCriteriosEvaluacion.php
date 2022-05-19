<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_desagregacion_criterios_evaluacion".
 *
 * @property int $id
 * @property int $bloque_unidad_id
 * @property int $criterio_evaluacion_id
 * @property bool $is_active
 *
 * @property PlanificacionDesagregacionCriteriosDestreza[] $planificacionDesagregacionCriteriosDestrezas
 * @property CurriculoMec $criterioEvaluacion
 * @property PlanificacionBloquesUnidad $bloqueUnidad
 */
class PlanificacionDesagregacionCriteriosEvaluacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_desagregacion_criterios_evaluacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bloque_unidad_id', 'criterio_evaluacion_id'], 'required'],
            [['bloque_unidad_id', 'criterio_evaluacion_id'], 'default', 'value' => null],
            [['bloque_unidad_id', 'criterio_evaluacion_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['criterio_evaluacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMec::className(), 'targetAttribute' => ['criterio_evaluacion_id' => 'id']],
            [['bloque_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['bloque_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bloque_unidad_id' => 'Bloque Unidad ID',
            'criterio_evaluacion_id' => 'Criterio Evaluacion ID',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionDesagregacionCriteriosDestrezas()
    {
        return $this->hasMany(PlanificacionDesagregacionCriteriosDestreza::className(), ['desagregacion_evaluacion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterioEvaluacion()
    {
        return $this->hasOne(CurriculoMec::className(), ['id' => 'criterio_evaluacion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloqueUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'bloque_unidad_id']);
    }
}
