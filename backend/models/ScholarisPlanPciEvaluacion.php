<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pci_evaluacion".
 *
 * @property int $id
 * @property int $pci_id
 * @property string $codigo_criterio_evaluacion
 * @property string $descripcion_criterio_evaluacion
 *
 * @property ScholarisPlanPci $pci
 */
class ScholarisPlanPciEvaluacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pci_evaluacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pci_id', 'codigo_criterio_evaluacion', 'descripcion_criterio_evaluacion'], 'required'],
            [['pci_id'], 'default', 'value' => null],
            [['pci_id'], 'integer'],
            [['descripcion_criterio_evaluacion'], 'string'],
            [['codigo_criterio_evaluacion'], 'string', 'max' => 30],
            [['pci_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanPci::className(), 'targetAttribute' => ['pci_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pci_id' => 'Pci ID',
            'codigo_criterio_evaluacion' => 'Codigo Criterio Evaluacion',
            'descripcion_criterio_evaluacion' => 'Descripcion Criterio Evaluacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPci()
    {
        return $this->hasOne(ScholarisPlanPci::className(), ['id' => 'pci_id']);
    }
}
