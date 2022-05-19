<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_pci_asignaturas".
 *
 * @property int $id
 * @property int $pci_subnivel_id
 * @property int $distribucion_id
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property PlanCurriculoDistribucion $distribucion
 * @property PlanPciSubnivel $pciSubnivel
 * @property PlanPciAsignaturasResponsables[] $planPciAsignaturasResponsables
 * @property OpFaculty[] $profesors
 */
class PlanPciAsignaturas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_pci_asignaturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pci_subnivel_id', 'distribucion_id', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['pci_subnivel_id', 'distribucion_id'], 'default', 'value' => null],
            [['pci_subnivel_id', 'distribucion_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['distribucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoDistribucion::className(), 'targetAttribute' => ['distribucion_id' => 'id']],
            [['pci_subnivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => \frontend\models\PlanPciSubnivel::className(), 'targetAttribute' => ['pci_subnivel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pci_subnivel_id' => 'Pci Subnivel ID',
            'distribucion_id' => 'Distribucion ID',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
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
    public function getPciSubnivel()
    {
        return $this->hasOne(PlanPciSubnive::className(), ['id' => 'pci_subnivel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanPciAsignaturasResponsables()
    {
        return $this->hasMany(PlanPciAsignaturasResponsables::className(), ['pci_asignaturas_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesors()
    {
        return $this->hasMany(OpFaculty::className(), ['id' => 'profesor_id'])->viaTable('plan_pci_asignaturas_responsables', ['pci_asignaturas_id' => 'id']);
    }
}
