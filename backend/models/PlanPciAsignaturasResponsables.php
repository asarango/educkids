<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_pci_asignaturas_responsables".
 *
 * @property int $pci_asignaturas_id
 * @property int $profesor_id
 *
 * @property OpFaculty $profesor
 * @property PlanPciAsignaturas $pciAsignaturas
 */
class PlanPciAsignaturasResponsables extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_pci_asignaturas_responsables';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pci_asignaturas_id', 'profesor_id'], 'required'],
            [['pci_asignaturas_id', 'profesor_id'], 'default', 'value' => null],
            [['pci_asignaturas_id', 'profesor_id'], 'integer'],
            [['pci_asignaturas_id', 'profesor_id'], 'unique', 'targetAttribute' => ['pci_asignaturas_id', 'profesor_id']],
            [['profesor_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['profesor_id' => 'id']],
            [['pci_asignaturas_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanPciAsignaturas::className(), 'targetAttribute' => ['pci_asignaturas_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pci_asignaturas_id' => 'Pci Asignaturas ID',
            'profesor_id' => 'Profesor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesor()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'profesor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPciAsignaturas()
    {
        return $this->hasOne(PlanPciAsignaturas::className(), ['id' => 'pci_asignaturas_id']);
    }
}
