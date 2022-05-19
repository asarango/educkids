<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_parent".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property int $name Name
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property int $user_id User
 * @property string $x_estado_civil Estado Civil
 * @property string $x_state Relación
 * @property string $x_account_type Tipo de cuenta
 * @property string $x_civil_status Estado civil
 * @property int $x_age Edad
 * @property string $x_second_street Calle secundaria
 * @property string $x_gender Sexo
 * @property string $x_home_number Número
 * @property string $x_account_number Número de cuenta
 * @property string $x_account Titular de la cuenta
 * @property string $x_birth_date Fecha de nacimiento
 * @property string $x_account_bank Banco
 * @property string $x_education_level Nivel de educación
 * @property string $x_work_phone_ext Extensión teléfono de trabajo
 * @property string $x_complete_dir Dirección completa
 * @property string $x_work_place Lugar de trabajo
 * @property string $x_work_dir Dirección del trabajo
 * @property string $first_name First Name
 * @property string $middle_name Middle Name
 * @property string $first_surname First Surname
 * @property string $second_surname Second Surname
 *
 * @property FamilyParentRel[] $familyParentRels
 * @property OpFamily[] $families
 * @property OpFamily[] $opFamilies
 * @property ResPartner $name0
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property ResUsers $user
 * @property OpParentInstitutionalEmailRel[] $opParentInstitutionalEmailRels
 * @property OpParentInstitutionalEmail[] $institutionalEmails
 * @property OpParentOpStudentRel[] $opParentOpStudentRels
 * @property OpStudent[] $opStudents
 * @property OpParentRelStudentServiceRel[] $opParentRelStudentServiceRels
 * @property RelStudentService[] $relStudentServices
 * @property OpPsychologicalAttention[] $opPsychologicalAttentions
 * @property OpStudent[] $opStudents0
 * @property OpStudentEnrollment[] $opStudentEnrollments
 * @property OpStudentEnrollment[] $opStudentEnrollments0
 * @property OpStudentEnrollment[] $opStudentEnrollments1
 * @property SendPaymentLinkWizard[] $sendPaymentLinkWizards
 */
class OpParent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_parent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'name', 'write_uid', 'user_id', 'x_age'], 'default', 'value' => null],
            [['create_uid', 'name', 'write_uid', 'user_id', 'x_age'], 'integer'],
            [['create_date', 'write_date', 'x_birth_date'], 'safe'],
            [['name', 'user_id'], 'required'],
            [['x_estado_civil', 'x_state', 'x_account_type', 'x_civil_status', 'x_second_street', 'x_gender', 'x_home_number', 'x_account_number', 'x_account', 'x_account_bank', 'x_education_level', 'x_work_phone_ext', 'x_complete_dir', 'x_work_place', 'x_work_dir'], 'string'],
            [['first_name', 'middle_name', 'first_surname', 'second_surname'], 'string', 'max' => 40],
            [['name'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['name' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'user_id' => 'User ID',
            'x_estado_civil' => 'X Estado Civil',
            'x_state' => 'X State',
            'x_account_type' => 'X Account Type',
            'x_civil_status' => 'X Civil Status',
            'x_age' => 'X Age',
            'x_second_street' => 'X Second Street',
            'x_gender' => 'X Gender',
            'x_home_number' => 'X Home Number',
            'x_account_number' => 'X Account Number',
            'x_account' => 'X Account',
            'x_birth_date' => 'X Birth Date',
            'x_account_bank' => 'X Account Bank',
            'x_education_level' => 'X Education Level',
            'x_work_phone_ext' => 'X Work Phone Ext',
            'x_complete_dir' => 'X Complete Dir',
            'x_work_place' => 'X Work Place',
            'x_work_dir' => 'X Work Dir',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'first_surname' => 'First Surname',
            'second_surname' => 'Second Surname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilyParentRels()
    {
        return $this->hasMany(FamilyParentRel::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilies()
    {
        return $this->hasMany(OpFamily::className(), ['id' => 'family_id'])->viaTable('family_parent_rel', ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFamilies()
    {
        return $this->hasMany(OpFamily::className(), ['representant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName0()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'name']);
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
    public function getUser()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpParentInstitutionalEmailRels()
    {
        return $this->hasMany(OpParentInstitutionalEmailRel::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitutionalEmails()
    {
        return $this->hasMany(OpParentInstitutionalEmail::className(), ['id' => 'institutional_email_id'])->viaTable('op_parent_institutional_email_rel', ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpParentOpStudentRels()
    {
        return $this->hasMany(OpParentOpStudentRel::className(), ['op_parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents()
    {
        return $this->hasMany(OpStudent::className(), ['id' => 'op_student_id'])->viaTable('op_parent_op_student_rel', ['op_parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpParentRelStudentServiceRels()
    {
        return $this->hasMany(OpParentRelStudentServiceRel::className(), ['op_parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelStudentServices()
    {
        return $this->hasMany(RelStudentService::className(), ['id' => 'rel_student_service_id'])->viaTable('op_parent_rel_student_service_rel', ['op_parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPsychologicalAttentions()
    {
        return $this->hasMany(OpPsychologicalAttention::className(), ['attended_parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents0()
    {
        return $this->hasMany(OpStudent::className(), ['x_representante' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['mother_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments0()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments1()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['father_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSendPaymentLinkWizards()
    {
        return $this->hasMany(SendPaymentLinkWizard::className(), ['to_parent_id' => 'id']);
    }
}
