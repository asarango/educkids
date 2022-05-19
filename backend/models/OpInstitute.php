<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_institute".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $code Codigo
 * @property string $create_date Created on
 * @property int $store_id Related Partner
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property string $direccion Direccion
 * @property string $codigo_amie
 * @property string $email
 * @property string $telefono
 * @property string $rector
 * @property string $secretario
 * @property string $inspector_general
 * @property string $celular
 * @property string $inscription_state Estado inscripción
 * @property string $enrollment_deposit_message Mensaje depósito
 * @property string $codigo_distrito
 * @property string $enrollment_payment_way_message_year Mensaje forma de pago anual
 * @property string $enrollment_payment_way_message_month Mensaje forma de pago mensual
 * @property string $name
 *
 * @property AccountInvoice[] $accountInvoices
 * @property BankFile[] $bankFiles
 * @property BizbankPaymentWizard[] $bizbankPaymentWizards
 * @property InscriptionGenerateWizard[] $inscriptionGenerateWizards
 * @property OpCourse[] $opCourses
 * @property OpCourseParalelo[] $opCourseParalelos
 * @property OpExtraValues[] $opExtraValues
 * @property OpInstitueTransportService[] $opInstitueTransportServices
 * @property ResStore $store
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property OpPeriod[] $opPeriods
 * @property OpStudent[] $opStudents
 * @property OpStudentInscription[] $opStudentInscriptions
 * @property ProductTemplate[] $productTemplates
 * @property ScholarisInstitutoAutoridades[] $scholarisInstitutoAutoridades
 * @property ScholarisIntitutoDatosGenerales[] $scholarisIntitutoDatosGenerales
 */
class OpInstitute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_institute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'store_id', 'write_uid'], 'default', 'value' => null],
            [['create_uid', 'store_id', 'write_uid'], 'integer'],
            [['code', 'store_id'], 'required'],
            [['create_date', 'write_date'], 'safe'],
            [['inscription_state', 'enrollment_deposit_message', 'enrollment_payment_way_message_year', 'enrollment_payment_way_message_month','regimen'], 'string'],
            [['code', 'codigo_amie'], 'string', 'max' => 10],
            [['direccion', 'name'], 'string', 'max' => 250],
            [['email', 'telefono', 'codigo_distrito'], 'string', 'max' => 50],
            [['rector', 'secretario', 'inspector_general'], 'string', 'max' => 100],
            [['celular'], 'string', 'max' => 15],
            //[['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResStore::className(), 'targetAttribute' => ['store_id' => 'id']],
            //[['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            //[['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
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
            'store_id' => 'Store ID',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'direccion' => 'Direccion',
            'codigo_amie' => 'Codigo Amie',
            'email' => 'Email',
            'telefono' => 'Telefono',
            'rector' => 'Rector',
            'secretario' => 'Secretario',
            'inspector_general' => 'Inspector General',
            'celular' => 'Celular',
            'inscription_state' => 'Inscription State',
            'enrollment_deposit_message' => 'Enrollment Deposit Message',
            'codigo_distrito' => 'Codigo Distrito',
            'enrollment_payment_way_message_year' => 'Enrollment Payment Way Message Year',
            'enrollment_payment_way_message_month' => 'Enrollment Payment Way Message Month',
            'name' => 'Name',
            'regimen' => 'Regimen',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourses()
    {
        return $this->hasMany(OpCourse::className(), ['x_institute' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseParalelos()
    {
        return $this->hasMany(OpCourseParalelo::className(), ['institute_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPeriods()
    {
        return $this->hasMany(OpPeriod::className(), ['institute' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents()
    {
        return $this->hasMany(OpStudent::className(), ['x_institute' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptions()
    {
        return $this->hasMany(OpStudentInscription::className(), ['institute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTemplates()
    {
        return $this->hasMany(ProductTemplate::className(), ['x_institute' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisInstitutoAutoridades()
    {
        return $this->hasMany(ScholarisInstitutoAutoridades::className(), ['instituto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisIntitutoDatosGenerales()
    {
        return $this->hasMany(ScholarisIntitutoDatosGenerales::className(), ['instituto_id' => 'id']);
    }
    
    
    public function getStore(){
        return $this->hasOne(ResStore::className(), ['id' => 'store_id']);
    }
}
