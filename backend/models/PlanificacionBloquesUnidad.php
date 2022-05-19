<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_bloques_unidad".
 *
 * @property int $id
 * @property int $curriculo_bloque_id
 * @property int $plan_cabecera_id
 * @property string $unit_title
 * @property string $settings_status
 *
 * @property CurriculoMecBloque $curriculoBloque
 * @property PlanificacionDesagregacionCabecera $planCabecera
 */
class PlanificacionBloquesUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_bloques_unidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curriculo_bloque_id', 'plan_cabecera_id', 'unit_title', 'settings_status'], 'required'],
            [['curriculo_bloque_id', 'plan_cabecera_id'], 'default', 'value' => null],
            [['curriculo_bloque_id', 'plan_cabecera_id'], 'integer'],
            [['unit_title'], 'string', 'max' => 150],
            [['settings_status'], 'string', 'max' => 30],
            [['enunciado_indagacion'], 'string'],
            [['is_open', 'pud_status'], 'boolean'],
            [['curriculo_bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecBloque::className(), 'targetAttribute' => ['curriculo_bloque_id' => 'id']],
            [['plan_cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCabecera::className(), 'targetAttribute' => ['plan_cabecera_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'curriculo_bloque_id' => 'Curriculo Bloque ID',
            'plan_cabecera_id' => 'Plan Cabecera ID',
            'unit_title' => 'Unit Title',
            'settings_status' => 'Settings Status',
            'enunciado_indagacion' => 'Enunciados de la indagación',
            'is_open' => 'Abrir Bloque'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculoBloque()
    {
        return $this->hasOne(CurriculoMecBloque::className(), ['id' => 'curriculo_bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanCabecera()
    {
        return $this->hasOne(PlanificacionDesagregacionCabecera::className(), ['id' => 'plan_cabecera_id']);
    }
}
