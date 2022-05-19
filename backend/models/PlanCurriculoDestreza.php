<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_destreza".
 *
 * @property int $id
 * @property int $distribucion_id
 * @property int $bloque_id
 * @property string $nombre
 *
 * @property PlanCurriculoBloque $bloque
 * @property PlanCurriculoDistribucion $distribucion
 */
class PlanCurriculoDestreza extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_destreza';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['distribucion_id', 'bloque_id', 'nombre'], 'required'],
            [['distribucion_id', 'bloque_id'], 'default', 'value' => null],
            [['distribucion_id', 'bloque_id'], 'integer'],
            [['nombre'], 'string', 'max' => 150],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoBloque::className(), 'targetAttribute' => ['bloque_id' => 'id']],
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
            'bloque_id' => 'Bloque ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(PlanCurriculoBloque::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistribucion()
    {
        return $this->hasOne(PlanCurriculoDistribucion::className(), ['id' => 'distribucion_id']);
    }
}
