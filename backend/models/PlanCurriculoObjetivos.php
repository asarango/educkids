<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_objetivos".
 *
 * @property int $id
 * @property int $distribucion_id
 * @property string $codigo_ministerio
 * @property string $descripcion
 * @property string $tipo_objetivo
 *
 * @property PlanCurriculoDistribucion $distribucion
 */
class PlanCurriculoObjetivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_objetivos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['distribucion_id', 'descripcion'], 'required'],
            [['distribucion_id'], 'default', 'value' => null],
            [['distribucion_id'], 'integer'],
            [['descripcion'], 'string'],
            [['codigo_ministerio', 'tipo_objetivo'], 'string', 'max' => 30],
            [['codigo_ministerio'], 'unique'],
            [['tipo_objetivo'],'required'],
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
            'codigo_ministerio' => 'Codigo Ministerio',
            'descripcion' => 'Descripcion',
            'tipo_objetivo' => 'Tipo Objetivo',
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
