<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_course_template".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $next_course_id Siguiente curso
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 *
 * @property OpCourse[] $opCourses
 * @property OpCourseTemplate $nextCourse
 * @property OpCourseTemplate[] $opCourseTemplates
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property PlanNivelSub[] $planNivelSubs
 * @property PlanNivel[] $nivels
 */
class OpCourseTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_course_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'next_course_id', 'write_uid'], 'default', 'value' => null],
            [['create_uid', 'next_course_id', 'write_uid','curriculo_nivel_id', 'order_curriculo'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'string'],
            [['next_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['next_course_id' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'next_course_id' => 'Next Course ID',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'curriculo_nivel_id' => 'Nivel de currículo',
            'order_curriculo' => 'Orden en currículo'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourses()
    {
        return $this->hasMany(OpCourse::className(), ['x_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNextCourse()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'next_course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseTemplates()
    {
        return $this->hasMany(OpCourseTemplate::className(), ['next_course_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'create_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriteU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'write_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanNivelSubs()
    {
        return $this->hasMany(PlanNivelSub::className(), ['curso_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNivels()
    {
        return $this->hasMany(PlanNivel::className(), ['id' => 'nivel_id'])->viaTable('plan_nivel_sub', ['curso_template_id' => 'id']);
    }
}
