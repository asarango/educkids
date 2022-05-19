<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_nivel_sub".
 *
 * @property int $curso_template_id
 * @property int $nivel_id
 *
 * @property OpCourseTemplate $cursoTemplate
 * @property PlanNivel $nivel
 */
class PlanNivelSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_nivel_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_template_id', 'nivel_id'], 'required'],
            [['curso_template_id', 'nivel_id'], 'default', 'value' => null],
            [['curso_template_id', 'nivel_id'], 'integer'],
            [['curso_template_id', 'nivel_id'], 'unique', 'targetAttribute' => ['curso_template_id', 'nivel_id']],
            [['curso_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['curso_template_id' => 'id']],
            [['nivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanNivel::className(), 'targetAttribute' => ['nivel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curso_template_id' => 'Curso Template ID',
            'nivel_id' => 'Nivel ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'curso_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNivel()
    {
        return $this->hasOne(PlanNivel::className(), ['id' => 'nivel_id']);
    }
}
