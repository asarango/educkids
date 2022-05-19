<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "plan_pci_subnivel".
 *
 * @property int $id
 * @property int $nivel_id
 * @property int $curriculo_id
 * @property string $estado
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property PlanCurriculo $curriculo
 * @property PlanNivel $nivel
 */
class PlanPciSubnivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_pci_subnivel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nivel_id', 'curriculo_id', 'estado', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['nivel_id', 'curriculo_id'], 'default', 'value' => null],
            [['nivel_id', 'curriculo_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['estado'], 'string', 'max' => 30],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['curriculo_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\PlanCurriculo::className(), 'targetAttribute' => ['curriculo_id' => 'id']],
            [['nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\PlanNivel::className(), 'targetAttribute' => ['nivel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nivel_id' => 'Nivel ID',
            'curriculo_id' => 'Curriculo ID',
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
    public function getCurriculo()
    {
        return $this->hasOne(\backend\models\PlanCurriculo::className(), ['id' => 'curriculo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNivel()
    {
        return $this->hasOne(\backend\models\PlanNivel::className(), ['id' => 'nivel_id']);
    }
}
