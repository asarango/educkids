<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pud_correcciones".
 *
 * @property int $id
 * @property int $pud_id
 * @property string $detalle_cambios
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property ScholarisPlanPud $pud
 */
class ScholarisPlanPudCorrecciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pud_correcciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pud_id', 'detalle_cambios', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['pud_id'], 'default', 'value' => null],
            [['pud_id'], 'integer'],
            [['detalle_cambios'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['pud_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanPud::className(), 'targetAttribute' => ['pud_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pud_id' => 'Pud ID',
            'detalle_cambios' => 'Detalle Cambios',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPud()
    {
        return $this->hasOne(ScholarisPlanPud::className(), ['id' => 'pud_id']);
    }
}
