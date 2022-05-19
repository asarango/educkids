<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_bloques_unidad_subtitulo".
 *
 * @property int $id
 * @property int $plan_unidad_id
 * @property string $subtitulo
 * @property int $orden
 * @property string $experiencias
 * @property string $evaluacion_formativa
 * @property string $diferenciacion
 *
 * @property PlanificacionBloquesUnidad $planUnidad
 * @property PlanificacionBloquesUnidadSubtitulo2[] $planificacionBloquesUnidadSubtitulo2s
 */
class PlanificacionBloquesUnidadSubtitulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_bloques_unidad_subtitulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_unidad_id', 'subtitulo'], 'required'],
            [['plan_unidad_id', 'orden'], 'default', 'value' => null],
            [['plan_unidad_id', 'orden'], 'integer'],
            [['experiencias', 'evaluacion_formativa', 'diferenciacion'], 'string'],
            [['subtitulo'], 'string', 'max' => 255],
            [['plan_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['plan_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_unidad_id' => 'Plan Unidad ID',
            'subtitulo' => 'Subtitulo',
            'orden' => 'Orden',
            'experiencias' => 'Experiencias',
            'evaluacion_formativa' => 'Evaluacion Formativa',
            'diferenciacion' => 'Diferenciacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'plan_unidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloquesUnidadSubtitulo2s()
    {
        return $this->hasMany(PlanificacionBloquesUnidadSubtitulo2::className(), ['subtitulo_id' => 'id']);
    }
}
