<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_destreza_evaluar".
 *
 * @property int $id
 * @property int $evaluacion_id
 * @property string $codigo
 * @property string $destreza
 * @property string $tipo_destreza
 *
 * @property PlanCurriculoEvaluacion $evaluacion
 */
class PlanCurriculoDestrezaEvaluar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_destreza_evaluar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evaluacion_id', 'codigo', 'destreza', 'tipo_destreza'], 'required'],
            [['evaluacion_id'], 'default', 'value' => null],
            [['evaluacion_id'], 'integer'],
            [['destreza'], 'string'],
            [['codigo'], 'string', 'max' => 15],
            [['tipo_destreza'], 'string', 'max' => 30],
            [['codigo'], 'unique'],
            [['evaluacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoEvaluacion::className(), 'targetAttribute' => ['evaluacion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'evaluacion_id' => 'Evaluacion ID',
            'codigo' => 'Codigo',
            'destreza' => 'Destreza',
            'tipo_destreza' => 'Tipo Destreza',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluacion()
    {
        return $this->hasOne(PlanCurriculoEvaluacion::className(), ['id' => 'evaluacion_id']);
    }
}
