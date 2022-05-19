<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_planificacion".
 *
 * @property int $id
 * @property int $distribucion_id
 * @property int $curso_id
 * @property int $periodo_id
 * @property string $estado
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpCourse $curso
 * @property PlanCurriculoDistribucion $distribucion
 * @property ScholarisPeriodo $periodo
 */
class PlanPlanificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_planificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['distribucion_id', 'curso_id', 'periodo_id', 'estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['distribucion_id', 'curso_id', 'periodo_id'], 'default', 'value' => null],
            [['distribucion_id', 'curso_id', 'periodo_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['estado'], 'string', 'max' => 30],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_id' => 'id']],
            [['distribucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoDistribucion::className(), 'targetAttribute' => ['distribucion_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
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
            'curso_id' => 'Curso ID',
            'periodo_id' => 'Periodo ID',
            'estado' => 'Estado',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'curso_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistribucion()
    {
        return $this->hasOne(PlanCurriculoDistribucion::className(), ['id' => 'distribucion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
