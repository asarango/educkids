<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_desagregacion_criterios_destreza".
 *
 * @property int $id
 * @property int $desagregacion_evaluacion_id
 * @property int $curriculo_destreza_id
 * @property int $course_template_id
 * @property string $opcion_desagregacion
 * @property string $content
 * @property bool $is_active
 *
 * @property CurriculoMec $curriculoDestreza
 * @property OpCourseTemplate $courseTemplate
 * @property PlanificacionDesagregacionCriteriosEvaluacion $desagregacionEvaluacion
 */
class PlanificacionDesagregacionCriteriosDestreza extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_desagregacion_criterios_destreza';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desagregacion_evaluacion_id', 'curriculo_destreza_id', 'course_template_id', 'opcion_desagregacion'], 'required'],
            [['desagregacion_evaluacion_id', 'curriculo_destreza_id', 'course_template_id'], 'default', 'value' => null],
            [['desagregacion_evaluacion_id', 'curriculo_destreza_id', 'course_template_id'], 'integer'],
            [['content'], 'string'],
            [['is_active'], 'boolean'],
            [['opcion_desagregacion'], 'string', 'max' => 20],
            [['curriculo_destreza_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurriculoMec::className(), 'targetAttribute' => ['curriculo_destreza_id' => 'id']],
            [['course_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['course_template_id' => 'id']],
            [['desagregacion_evaluacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionDesagregacionCriteriosEvaluacion::className(), 'targetAttribute' => ['desagregacion_evaluacion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'desagregacion_evaluacion_id' => 'Desagregacion Evaluacion ID',
            'curriculo_destreza_id' => 'Curriculo Destreza ID',
            'course_template_id' => 'Course Template ID',
            'opcion_desagregacion' => 'Opcion Desagregacion',
            'content' => 'Content',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculoDestreza()
    {
        return $this->hasOne(CurriculoMec::className(), ['id' => 'curriculo_destreza_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'course_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDesagregacionEvaluacion()
    {
        return $this->hasOne(PlanificacionDesagregacionCriteriosEvaluacion::className(), ['id' => 'desagregacion_evaluacion_id']);
    }
}
