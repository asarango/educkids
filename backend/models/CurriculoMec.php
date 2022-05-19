<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "curriculo_mec".
 *
 * @property int $id
 * @property int $asignatura_id
 * @property int $subnivel_id
 * @property string $reference_type
 * @property string $code
 * @property string $description
 * @property bool $is_essential
 * @property int $order_block
 * @property string $aux_1
 * @property string $aux_2
 * @property string $belongs_to
 *
 * @property CurriculoMecAsignatutas $asignatura
 * @property CurriculoMecNiveles $subnivel
 * @property PlanificacionDesagregacionCriteriosEvaluacion[] $planificacionDesagregacionCriteriosEvaluacions
 */
class CurriculoMec extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curriculo_mec';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asignatura_id', 'subnivel_id', 'reference_type', 'code', 'description'], 'required'],
            [['asignatura_id', 'subnivel_id', 'order_block'], 'default', 'value' => null],
            [['asignatura_id', 'subnivel_id', 'order_block'], 'integer'],
            [['description'], 'string'],
            [['is_essential'], 'boolean'],
            [['reference_type', 'code', 'belongs_to'], 'string', 'max' => 20],
            [['aux_1', 'aux_2'], 'string', 'max' => 255],
            [['asignatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecAsignatutas::className(), 'targetAttribute' => ['asignatura_id' => 'id']],
            [['subnivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMecNiveles::className(), 'targetAttribute' => ['subnivel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asignatura_id' => 'Asignatura ID',
            'subnivel_id' => 'Subnivel ID',
            'reference_type' => 'Reference Type',
            'code' => 'Code',
            'description' => 'Description',
            'is_essential' => 'Is Essential',
            'order_block' => 'Order Block',
            'aux_1' => 'Aux 1',
            'aux_2' => 'Aux 2',
            'belongs_to' => 'Belongs To',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignatura()
    {
        return $this->hasOne(CurriculoMecAsignatutas::className(), ['id' => 'asignatura_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubnivel()
    {
        return $this->hasOne(CurriculoMecNiveles::className(), ['id' => 'subnivel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionDesagregacionCriteriosEvaluacions()
    {
        return $this->hasMany(PlanificacionDesagregacionCriteriosEvaluacion::className(), ['criterio_evaluacion_id' => 'id']);
    }
}
