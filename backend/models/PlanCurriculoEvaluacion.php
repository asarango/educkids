<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_evaluacion".
 *
 * @property int $id
 * @property int $distribucion_id
 * @property string $codigo
 * @property string $criterio_evaluacion
 * @property string $orientacion_metodologica
 *
 * @property PlanCurriculoDistribucion $distribucion
 */
class PlanCurriculoEvaluacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_evaluacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['distribucion_id', 'codigo', 'criterio_evaluacion', 'orientacion_metodologica'], 'required'],
            [['distribucion_id'], 'default', 'value' => null],
            [['distribucion_id'], 'integer'],
            [['criterio_evaluacion', 'orientacion_metodologica'], 'string'],
            [['codigo'], 'string', 'max' => 15],
            [['distribucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoDistribucion::className(), 'targetAttribute' => ['distribucion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'distribucion_id' => 'Distribucion ID',
            'codigo' => 'Codigo',
            'criterio_evaluacion' => 'Criterio Evaluacion',
            'orientacion_metodologica' => 'Orientacion Metodologica',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistribucion()
    {
        return $this->hasOne(PlanCurriculoDistribucion::className(), ['id' => 'distribucion_id']);
    }
}
