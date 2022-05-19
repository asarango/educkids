<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pca_unidades".
 *
 * @property int $id
 * @property int $pca_id
 * @property string $unidad
 * @property int $semanas_destinadas
 * @property int $periodos_semanales
 * @property int $periodos_inprevistos
 * @property string $desde
 * @property string $hasta
 *
 * @property ScholarisPlanPca $pca
 */
class ScholarisPlanPcaUnidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pca_unidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pca_id', 'unidad', 'semanas_destinadas', 'periodos_semanales', 'periodos_inprevistos'], 'required'],
            [['pca_id', 'semanas_destinadas', 'periodos_semanales', 'periodos_inprevistos'], 'default', 'value' => null],
            [['pca_id', 'semanas_destinadas', 'periodos_semanales', 'periodos_inprevistos'], 'integer'],
            [['desde', 'hasta'], 'safe'],
            [['unidad'], 'string', 'max' => 150],
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
            'unidad' => 'Unidad',
            'semanas_destinadas' => 'Semanas Destinadas',
            'periodos_semanales' => 'Periodos Semanales',
            'periodos_inprevistos' => 'Periodos Inprevistos',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
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
