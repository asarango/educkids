<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pci_evaluacion_destrezas".
 *
 * @property int $id
 * @property int $evaluacion_id
 * @property int $curso_subnivel_id
 * @property string $curso_subnivel_nombre
 * @property int $destreza_id
 * @property string $destreza_codigo
 * @property string $destreza_detalle
 *
 * @property ScholarisPlanPciEvaluacion $evaluacion
 */
class ScholarisPlanPciEvaluacionDestrezas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pci_evaluacion_destrezas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evaluacion_id', 'curso_subnivel_id', 'curso_subnivel_nombre', 'destreza_id', 'destreza_codigo', 'destreza_detalle'], 'required'],
            [['evaluacion_id', 'curso_subnivel_id', 'destreza_id'], 'default', 'value' => null],
            [['evaluacion_id', 'curso_subnivel_id', 'destreza_id'], 'integer'],
            [['destreza_detalle'], 'string'],
            [['desagregado'], 'boolean'],
            [['curso_subnivel_nombre'], 'string', 'max' => 150],
            [['destreza_codigo'], 'string', 'max' => 30],
            [['curso_subnivel_codigo'], 'string', 'max' => 10],
            [['evaluacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanPciEvaluacion::className(), 'targetAttribute' => ['evaluacion_id' => 'id']],
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
            'curso_subnivel_id' => 'Curso Subnivel ID',
            'curso_subnivel_nombre' => 'Curso Subnivel Nombre',
            'destreza_id' => 'Destreza ID',
            'destreza_codigo' => 'Destreza Codigo',
            'destreza_detalle' => 'Destreza Detalle',
            'desagregado' => 'Desagregado',
            'curso_subnivel_codigo' => 'Codigo Curso Curriculo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluacion()
    {
        return $this->hasOne(ScholarisPlanPciEvaluacion::className(), ['id' => 'evaluacion_id']);
    }
}
