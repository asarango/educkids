<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_malla".
 *
 * @property int $id
 * @property int $op_course_template_id
 * @property string $nombre
 *
 * @property OpCourseTemplate $opCourseTemplate
 * @property IsmPeriodoMalla[] $ismPeriodoMallas
 */
class IsmMalla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_malla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['op_course_template_id', 'nombre'], 'required'],
            [['op_course_template_id'], 'default', 'value' => null],
            [['op_course_template_id'], 'integer'],
            [['nombre'], 'string', 'max' => 80],
            [['op_course_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['op_course_template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'op_course_template_id' => 'Op Course Template ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'op_course_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmPeriodoMallas()
    {
        return $this->hasMany(IsmPeriodoMalla::className(), ['malla_id' => 'id']);
    }
}
