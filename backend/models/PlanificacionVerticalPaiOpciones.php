<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_pai_opciones".
 *
 * @property int $id
 * @property int $plan_unidad_id
 * @property string $tipo
 * @property string $contenido
 *
 * @property PlanificacionBloquesUnidad $planUnidad
 */
class PlanificacionVerticalPaiOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_pai_opciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_unidad_id', 'tipo', 'contenido'], 'required'],
            [['plan_unidad_id'], 'default', 'value' => null],
            [['plan_unidad_id'], 'integer'],
            [['contenido'], 'string'],
            [['tipo'], 'string', 'max' => 50],
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
            'tipo' => 'Tipo',
            'contenido' => 'Contenido',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'plan_unidad_id']);
    }
}
