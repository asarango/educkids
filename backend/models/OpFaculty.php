<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_faculty".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $last_name Last Name
 * @property string $create_date Created on
 * @property string $blood_group Blood Group
 * @property string $id_number ID Card Number
 * @property resource $photo Photo
 * @property string $middle_name Middle Name
 * @property int $write_uid Last Updated by
 * @property string $birth_date Birth Date
 * @property int $emergency_contact Emergency Contact
 * @property string $write_date Last Updated on
 * @property string $gender Gender
 * @property int $emp_id Employee
 * @property int $nationality Nationality
 * @property string $visa_info Visa Info
 * @property int $partner_id Partner
 * @property int $library_card_id Library Card
 * @property string $x_first_name Nombre
 * @property string $name Nombre
 * @property int $x_user_id Usuario
 *
 * @property IssueBook[] $issueBooks
 * @property OpAchievement[] $opAchievements
 * @property OpActivity[] $opActivities
 * @property OpAssignment[] $opAssignments
 * @property OpAssignment[] $opAssignments0
 * @property OpAttendanceSheet[] $opAttendanceSheets
 * @property OpBookMovement[] $opBookMovements
 * @property OpExamOpFacultyRel[] $opExamOpFacultyRels
 * @property OpExam[] $opExams
 * @property OpExamResAllocationOpFacultyRel[] $opExamResAllocationOpFacultyRels
 * @property OpExamResAllocation[] $opExamResAllocations
 * @property HrEmployee $emp
 * @property OpLibraryCard $libraryCard
 * @property ResCountry $nationality0
 * @property ResPartner $emergencyContact
 * @property ResPartner $partner
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property ResUsers $xUser
 * @property OpFacultyOpSubjectRel[] $opFacultyOpSubjectRels
 * @property OpSubject[] $opSubjects
 * @property OpFacultyWizardOpFacultyRel[] $opFacultyWizardOpFacultyRels
 * @property WizardOpFaculty[] $wizardOpFaculties
 * @property OpHealth[] $opHealths
 * @property OpLibraryCard[] $opLibraryCards
 * @property PlanCurriculoDistribucion[] $planCurriculoDistribucions
 */
class OpFaculty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_faculty';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'emergency_contact', 'emp_id', 'nationality', 'partner_id', 'library_card_id', 'x_user_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'emergency_contact', 'emp_id', 'nationality', 'partner_id', 'library_card_id', 'x_user_id'], 'integer'],
            [['last_name', 'birth_date', 'gender', 'partner_id', 'x_user_id'], 'required'],
            [['create_date', 'birth_date', 'write_date'], 'safe'],
            [['blood_group', 'photo', 'gender', 'x_first_name', 'name'], 'string'],
            [['last_name', 'middle_name'], 'string', 'max' => 128],
            [['id_number', 'visa_info'], 'string', 'max' => 64],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['emp_id' => 'id']],
            [['library_card_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpLibraryCard::className(), 'targetAttribute' => ['library_card_id' => 'id']],
            [['nationality'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['nationality' => 'id']],
            [['emergency_contact'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['emergency_contact' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['partner_id' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['x_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['x_user_id' => 'id']],
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
            'last_name' => 'Last Name',
            'create_date' => 'Create Date',
            'blood_group' => 'Blood Group',
            'id_number' => 'Id Number',
            'photo' => 'Photo',
            'middle_name' => 'Middle Name',
            'write_uid' => 'Write Uid',
            'birth_date' => 'Birth Date',
            'emergency_contact' => 'Emergency Contact',
            'write_date' => 'Write Date',
            'gender' => 'Gender',
            'emp_id' => 'Emp ID',
            'nationality' => 'Nationality',
            'visa_info' => 'Visa Info',
            'partner_id' => 'Partner ID',
            'library_card_id' => 'Library Card ID',
            'x_first_name' => 'X First Name',
            'name' => 'Name',
            'x_user_id' => 'X User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueBooks()
    {
        return $this->hasMany(IssueBook::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAchievements()
    {
        return $this->hasMany(OpAchievement::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpActivities()
    {
        return $this->hasMany(OpActivity::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAssignments()
    {
        return $this->hasMany(OpAssignment::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAssignments0()
    {
        return $this->hasMany(OpAssignment::className(), ['reviewer' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAttendanceSheets()
    {
        return $this->hasMany(OpAttendanceSheet::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpBookMovements()
    {
        return $this->hasMany(OpBookMovement::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamOpFacultyRels()
    {
        return $this->hasMany(OpExamOpFacultyRel::className(), ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExams()
    {
        return $this->hasMany(OpExam::className(), ['id' => 'op_exam_id'])->viaTable('op_exam_op_faculty_rel', ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamResAllocationOpFacultyRels()
    {
        return $this->hasMany(OpExamResAllocationOpFacultyRel::className(), ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamResAllocations()
    {
        return $this->hasMany(OpExamResAllocation::className(), ['id' => 'op_exam_res_allocation_id'])->viaTable('op_exam_res_allocation_op_faculty_rel', ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmp()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'emp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibraryCard()
    {
        return $this->hasOne(OpLibraryCard::className(), ['id' => 'library_card_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNationality0()
    {
        return $this->hasOne(ResCountry::className(), ['id' => 'nationality']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmergencyContact()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'emergency_contact']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'partner_id']);
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
    public function getXUser()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'x_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFacultyOpSubjectRels()
    {
        return $this->hasMany(OpFacultyOpSubjectRel::className(), ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpSubjects()
    {
        return $this->hasMany(OpSubject::className(), ['id' => 'op_subject_id'])->viaTable('op_faculty_op_subject_rel', ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFacultyWizardOpFacultyRels()
    {
        return $this->hasMany(OpFacultyWizardOpFacultyRel::className(), ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizardOpFaculties()
    {
        return $this->hasMany(WizardOpFaculty::className(), ['id' => 'wizard_op_faculty_id'])->viaTable('op_faculty_wizard_op_faculty_rel', ['op_faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpHealths()
    {
        return $this->hasMany(OpHealth::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpLibraryCards()
    {
        return $this->hasMany(OpLibraryCard::className(), ['faculty_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanCurriculoDistribucions()
    {
        return $this->hasMany(PlanCurriculoDistribucion::className(), ['jefe_area_id' => 'id']);
    }
}
