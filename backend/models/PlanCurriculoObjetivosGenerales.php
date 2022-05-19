<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_objetivos_generales".
 *
 * @property int $id
 * @property int $evaluacion_id
 * @property string $codigo
 * @property string $objetivo_general
 *
 * @property PlanCurriculoEvaluacion $evaluacion
 */
class PlanCurriculoObjetivosGenerales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_objetivos_generales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evaluacion_id', 'codigo', 'objetivo_general'], 'required'],
            [['evaluacion_id'], 'default', 'value' => null],
            [['evaluacion_id'], 'integer'],
            [['objetivo_general'], 'string'],
            [['codigo'], 'string', 'max' => 15],
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
            'objetivo_general' => 'Objetivo General',
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
