<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_course_paralelo".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $last_date_invoice Fecha de última facturación
 * @property string $create_date Created on
 * @property string $name Paralelo
 * @property int $write_uid Last Updated by
 * @property int $period_id Período
 * @property string $write_date Last Updated on
 * @property int $course_id Curso
 * @property int $institute_id Instituto
 * @property int $x_capacidad Capacidad
 * @property int $capacidad
 * @property int $aula
 *
 * @property AccountInvoice[] $accountInvoices
 * @property OpCourse $course
 * @property OpInstitute $institute
 * @property OpPeriod $period
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property OpStudent[] $opStudents
 * @property OpStudentEnrollment[] $opStudentEnrollments
 * @property OpStudentInscription[] $opStudentInscriptions
 */
class OpCourseParalelo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_course_paralelo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'period_id', 'course_id', 'institute_id', 'x_capacidad', 'capacidad', 'aula'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'period_id', 'course_id', 'institute_id', 'x_capacidad', 'capacidad', 'aula'], 'integer'],
            [['last_date_invoice', 'create_date', 'write_date'], 'safe'],
            [['name'], 'required'],
            [['name'], 'string'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['institute_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['institute_id' => 'id']],
//            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPeriod::className(), 'targetAttribute' => ['period_id' => 'id']],
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
            'last_date_invoice' => 'Last Date Invoice',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'write_uid' => 'Write Uid',
            'period_id' => 'Period ID',
            'write_date' => 'Write Date',
            'course_id' => 'Course ID',
            'institute_id' => 'Institute ID',
            'x_capacidad' => 'X Capacidad',
            'capacidad' => 'Capacidad',
            'aula' => 'Aula',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoices()
    {
        return $this->hasMany(AccountInvoice::className(), ['x_st_parallel_id' => 'id']);
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
    public function getOpStudents()
    {
        return $this->hasMany(OpStudent::className(), ['x_paralelo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['x_paralelo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptions()
    {
        return $this->hasMany(OpStudentInscription::className(), ['parallel_id' => 'id']);
    }
}
