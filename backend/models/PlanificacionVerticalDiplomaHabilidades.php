<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_vertical_diploma_habilidades".
 *
 * @property int $id
 * @property int $vertical_diploma_id
 * @property int $habilidad_id
 * @property string $created
 * @property string $created_at
 * @property string $updated
 * @property string $updated_at
 *
 * @property ContenidoPaiHabilidades $habilidad
 * @property PlanificacionVerticalDiploma $verticalDiploma
 */
class PlanificacionVerticalDiplomaHabilidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_vertical_diploma_habilidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vertical_diploma_id', 'habilidad_id', 'created', 'created_at'], 'required'],
            [['vertical_diploma_id', 'habilidad_id'], 'default', 'value' => null],
            [['vertical_diploma_id', 'habilidad_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['habilidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContenidoPaiHabilidades::className(), 'targetAttribute' => ['habilidad_id' => 'id']],
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
            'habilidad_id' => 'Habilidad ID',
            'created' => 'Created',
            'created_at' => 'Created At',
            'updated' => 'Updated',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHabilidad()
    {
        return $this->hasOne(ContenidoPaiHabilidades::className(), ['id' => 'habilidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVerticalDiploma()
    {
        return $this->hasOne(PlanificacionVerticalDiploma::className(), ['id' => 'vertical_diploma_id']);
    }
}
