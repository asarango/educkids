<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mapa_enfoques_pai".
 *
 * @property int $id
 * @property int $periodo_id
 * @property int $course_template_id
 * @property int $pai_habilidad_id
 * @property bool $estado
 *
 * @property ContenidoPaiHabilidades $paiHabilidad
 * @property OpCourseTemplate $courseTemplate
 * @property ScholarisPeriodo $periodo
 */
class MapaEnfoquesPai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mapa_enfoques_pai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periodo_id', 'course_template_id', 'pai_habilidad_id'], 'required'],
            [['periodo_id', 'course_template_id', 'pai_habilidad_id'], 'default', 'value' => null],
            [['periodo_id', 'course_template_id', 'pai_habilidad_id'], 'integer'],
            [['estado'], 'boolean'],
            [['pai_habilidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContenidoPaiHabilidades::className(), 'targetAttribute' => ['pai_habilidad_id' => 'id']],
            [['course_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['course_template_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'periodo_id' => 'Periodo ID',
            'course_template_id' => 'Course Template ID',
            'pai_habilidad_id' => 'Pai Habilidad ID',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaiHabilidad()
    {
        return $this->hasOne(ContenidoPaiHabilidades::className(), ['id' => 'pai_habilidad_id']);
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
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
