<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pca_unidades_detalle".
 *
 * @property int $id
 * @property int $unidad_id
 * @property string $tipo_referencia
 * @property string $codigo
 * @property string $detalle
 *
 * @property ScholarisPlanPcaUnidades $unidad
 */
class ScholarisPlanPcaUnidadesDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pca_unidades_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unidad_id', 'detalle'], 'required'],
            [['unidad_id'], 'default', 'value' => null],
            [['unidad_id'], 'integer'],
            [['detalle'], 'string'],
            [['tipo_referencia', 'codigo'], 'string', 'max' => 30],
            [['unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanPcaUnidades::className(), 'targetAttribute' => ['unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unidad_id' => 'Unidad ID',
            'tipo_referencia' => 'Tipo Referencia',
            'codigo' => 'Codigo',
            'detalle' => 'Detalle',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidad()
    {
        return $this->hasOne(ScholarisPlanPcaUnidades::className(), ['id' => 'unidad_id']);
    }
}
