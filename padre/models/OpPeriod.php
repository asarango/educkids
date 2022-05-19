<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "op_period".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property int $code code
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $institute Instituto
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property string $start_date Fecha inicio
 * @property bool $is_active Período activo?
 * @property bool $to_inscription Inscripción
 * @property string $end_date Fecha fin
 *
 * @property BankFile[] $bankFiles
 * @property OpCourse[] $opCourses
 * @property OpCourseParalelo[] $opCourseParalelos
 * @property OpInstitute $institute0
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property OpSection[] $opSections
 * @property OpStudentAspirant[] $opStudentAspirants
 * @property OpStudentAspirantTracking[] $opStudentAspirantTrackings
 * @property OpStudentEnrollment[] $opStudentEnrollments
 * @property OpStudentInscription[] $opStudentInscriptions
 * @property OpStudentInscriptionTemp[] $opStudentInscriptionTemps
 * @property OpStudentInscriptionTmp[] $opStudentInscriptionTmps
 * @property ScholarisOpPeriodPeriodoScholaris[] $scholarisOpPeriodPeriodoScholaris
 * @property ScholarisPeriodo[] $scholaris
 * @property StudentAspirantReport[] $studentAspirantReports
 * @property StudentAspirantTracking[] $studentAspirantTrackings
 * @property StudentByParallelWizard[] $studentByParallelWizards
 * @property WizzardPrintStudentAdmission[] $wizzardPrintStudentAdmissions
 */
class OpPeriod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_period';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'code', 'institute', 'write_uid'], 'default', 'value' => null],
            [['create_uid', 'code', 'institute', 'write_uid'], 'integer'],
            [['create_date', 'write_date', 'start_date', 'end_date'], 'safe'],
            [['name', 'institute', 'start_date'], 'required'],
            [['name'], 'string'],
            [['is_active', 'to_inscription'], 'boolean'],
            [['institute'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['institute' => 'id']],
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
            'code' => 'Code',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'institute' => 'Institute',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'start_date' => 'Start Date',
            'is_active' => 'Is Active',
            'to_inscription' => 'To Inscription',
            'end_date' => 'End Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankFiles()
    {
        return $this->hasMany(BankFile::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourses()
    {
        return $this->hasMany(OpCourse::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseParalelos()
    {
        return $this->hasMany(OpCourseParalelo::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitute0()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'institute']);
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
    public function getOpSections()
    {
        return $this->hasMany(OpSection::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentAspirants()
    {
        return $this->hasMany(OpStudentAspirant::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentAspirantTrackings()
    {
        return $this->hasMany(OpStudentAspirantTracking::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptions()
    {
        return $this->hasMany(OpStudentInscription::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptionTemps()
    {
        return $this->hasMany(OpStudentInscriptionTemp::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptionTmps()
    {
        return $this->hasMany(OpStudentInscriptionTmp::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisOpPeriodPeriodoScholaris()
    {
        return $this->hasMany(ScholarisOpPeriodPeriodoScholaris::className(), ['op_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholaris()
    {
        return $this->hasMany(ScholarisPeriodo::className(), ['id' => 'scholaris_id'])->viaTable('scholaris_op_period_periodo_scholaris', ['op_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAspirantReports()
    {
        return $this->hasMany(StudentAspirantReport::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentAspirantTrackings()
    {
        return $this->hasMany(StudentAspirantTracking::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentByParallelWizards()
    {
        return $this->hasMany(StudentByParallelWizard::className(), ['period_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizzardPrintStudentAdmissions()
    {
        return $this->hasMany(WizzardPrintStudentAdmission::className(), ['period_id' => 'id']);
    }
}
