<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_student_inscription".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property int $student_id Estudiante
 * @property string $inscription_state Estado
 * @property string $student_state Estado estudiante
 * @property int $write_uid Last Updated by
 * @property int $course_id Curso
 * @property int $period_id Período
 * @property string $write_date Last Updated on
 * @property string $reserva_cupo Reserva cupo
 * @property int $institute_id Instituto
 * @property int $parallel_id Paralelo
 *
 * @property OpStudentEnrollment[] $opStudentEnrollments
 * @property OpCourse $course
 * @property OpCourseParalelo $parallel
 * @property OpInstitute $institute
 * @property OpPeriod $period
 * @property OpStudent $student
 * @property ResUsers $createU
 * @property ResUsers $writeU
 */
class OpStudentInscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_student_inscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'student_id', 'write_uid', 'course_id', 'period_id', 'institute_id', 'parallel_id'], 'default', 'value' => null],
            [['create_uid', 'student_id', 'write_uid', 'course_id', 'period_id', 'institute_id', 'parallel_id'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['inscription_state', 'student_state', 'reserva_cupo'], 'string'],
            [['course_id', 'period_id', 'institute_id'], 'required'],
            [['student_id', 'course_id', 'period_id'], 'unique', 'targetAttribute' => ['student_id', 'course_id', 'period_id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['parallel_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['parallel_id' => 'id']],
            [['institute_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['institute_id' => 'id']],
            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPeriod::className(), 'targetAttribute' => ['period_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
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
            'student_id' => 'Student ID',
            'inscription_state' => 'Inscription State',
            'student_state' => 'Student State',
            'write_uid' => 'Write Uid',
            'course_id' => 'Course ID',
            'period_id' => 'Period ID',
            'write_date' => 'Write Date',
            'reserva_cupo' => 'Reserva Cupo',
            'institute_id' => 'Institute ID',
            'parallel_id' => 'Parallel ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['inscription_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParallel()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'parallel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitute()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'institute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriod()
    {
        return $this->hasOne(OpPeriod::className(), ['id' => 'period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'student_id']);
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
    
    public function getOp_student(){
        return $this->hasOne(OpStudent::className(), ['id' => 'student_id']);
    }
    
}
