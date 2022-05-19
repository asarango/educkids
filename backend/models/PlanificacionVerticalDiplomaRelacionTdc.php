<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_diploma_relacion_tdc".
 *
 * @property int $id
 * @property int $vertical_diploma_id
 * @property int $relacion_tdc_id
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property PlanificacionOpciones $relacionTdc
 * @property PlanificacionVerticalDiploma $verticalDiploma
 */
class PlanificacionVerticalDiplomaRelacionTdc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_diploma_relacion_tdc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vertical_diploma_id', 'relacion_tdc_id', 'created', 'created_at'], 'required'],
            [['vertical_diploma_id', 'relacion_tdc_id'], 'default', 'value' => null],
            [['vertical_diploma_id', 'relacion_tdc_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['relacion_tdc_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionOpciones::className(), 'targetAttribute' => ['relacion_tdc_id' => 'id']],
            [['vertical_diploma_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionVerticalDiploma::className(), 'targetAttribute' => ['vertical_diploma_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vertical_diploma_id' => 'Vertical Diploma ID',
            'relacion_tdc_id' => 'Relacion Tdc ID',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelacionTdc()
    {
        return $this->hasOne(PlanificacionOpciones::className(), ['id' => 'relacion_tdc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerticalDiploma()
    {
        return $this->hasOne(PlanificacionVerticalDiploma::className(), ['id' => 'vertical_diploma_id']);
    }
}
