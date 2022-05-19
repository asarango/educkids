<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pca_detalle".
 *
 * @property int $id
 * @property int $pca_id
 * @property string $tipo
 * @property string $codigo
 * @property string $detalle
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property ScholarisPlanPca $pca
 */
class ScholarisPlanPcaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pca_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pca_id', 'tipo', 'detalle', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['pca_id'], 'default', 'value' => null],
            [['pca_id'], 'integer'],
            [['detalle'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['tipo', 'codigo'], 'string', 'max' => 30],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['pca_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanPca::className(), 'targetAttribute' => ['pca_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pca_id' => 'Pca ID',
            'tipo' => 'Tipo',
            'codigo' => 'Codigo',
            'detalle' => 'Detalle',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPca()
    {
        return $this->hasOne(ScholarisPlanPca::className(), ['id' => 'pca_id']);
    }
}
