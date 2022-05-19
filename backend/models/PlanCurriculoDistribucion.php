<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_distribucion".
 *
 * @property int $id
 * @property int $nivel_id
 * @property int $curriculo_id
 * @property int $area_id
 * @property int $jefe_area_id
 *
 * @property OpFaculty $jefeArea
 * @property PlanArea $area
 * @property PlanCurriculo $curriculo
 * @property PlanNivel $nivel
 */
class PlanCurriculoDistribucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_distribucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nivel_id', 'curriculo_id', 'area_id', 'jefe_area_id'], 'required'],
            [['nivel_id', 'curriculo_id', 'area_id', 'jefe_area_id'], 'default', 'value' => null],
            [['nivel_id', 'curriculo_id', 'area_id', 'jefe_area_id'], 'integer'],
            [['jefe_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['jefe_area_id' => 'id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanArea::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['curriculo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculo::className(), 'targetAttribute' => ['curriculo_id' => 'id']],
            [['nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanNivel::className(), 'targetAttribute' => ['nivel_id' => 'id']],
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
            'area_id' => 'Area ID',
            'jefe_area_id' => 'Jefe Area ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJefeArea()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'jefe_area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(PlanArea::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculo()
    {
        return $this->hasOne(PlanCurriculo::className(), ['id' => 'curriculo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNivel()
    {
        return $this->hasOne(PlanNivel::className(), ['id' => 'nivel_id']);
    }
}
