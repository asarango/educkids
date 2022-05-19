<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_student_enrollment".
 *
 * @property int $id
 * @property string $ret_longitude Longitud
 * @property string $ret_latitude Latitud
 * @property string $del_latitude Latitud
 * @property string $payment_method Método de pago
 * @property int $mother_id Madre
 * @property double $total_service_one_pay Precio servicios un pago
 * @property int $write_uid Last Updated by
 * @property int $currency_id Currency
 * @property int $course_id Curso
 * @property string $create_date Created on
 * @property string $deposit_transfer_date Fecha depósito/transferencia
 * @property bool $invoiced Facturado
 * @property string $deposit_number Número de déposito
 * @property int $create_uid Created by
 * @property int $student_id Estudiante
 * @property double $total_service_all_pay Precio servicios varios pagos
 * @property int $parent_id Padre que matricula
 * @property string $state Estado
 * @property int $father_id Padre
 * @property string $del_longitude Longitud
 * @property string $payment_way Forma de pago
 * @property int $x_paralelo_id Paralelo
 * @property double $total_service_price Precio servicios
 * @property string $dir_ret_ref Referencia
 * @property int $sector_ret_id Sector
 * @property int $period_id Período inscripción
 * @property string $write_date Last Updated on
 * @property bool $accept_contract Aceptar contrato
 * @property string $name Nombre
 * @property string $dir_del_ref Referencia
 * @property string $log_detail Log
 * @property int $invoice_id Factura
 * @property bool $reserve_transport No reserva transporte
 * @property int $inscription_id Inscripción
 * @property int $sector_del_id Sector
 *
 * @property OpStudent[] $opStudents
 * @property AccountInvoice $invoice
 * @property OpCourse $course
 * @property OpCourseParalelo $xParalelo
 * @property OpParent $mother
 * @property OpParent $parent
 * @property OpParent $father
 * @property OpPeriod $period
 * @property OpStudent $student
 * @property OpStudentInscription $inscription
 * @property ResCountrySector $sectorRet
 * @property ResCountrySector $sectorDel
 * @property ResCurrency $currency
 * @property ResUsers $writeU
 * @property ResUsers $createU
 * @property OpStudentEnrollmentServices[] $opStudentEnrollmentServices
 */
class OpStudentEnrollment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_student_enrollment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ret_longitude', 'ret_latitude', 'del_latitude', 'payment_method', 'deposit_number', 'state', 'del_longitude', 'payment_way', 'dir_ret_ref', 'name', 'dir_del_ref', 'log_detail'], 'string'],
            [['mother_id', 'write_uid', 'currency_id', 'course_id', 'create_uid', 'student_id', 'parent_id', 'father_id', 'x_paralelo_id', 'sector_ret_id', 'period_id', 'invoice_id', 'inscription_id', 'sector_del_id'], 'default', 'value' => null],
            [['mother_id', 'write_uid', 'currency_id', 'course_id', 'create_uid', 'student_id', 'parent_id', 'father_id', 'x_paralelo_id', 'sector_ret_id', 'period_id', 'invoice_id', 'inscription_id', 'sector_del_id'], 'integer'],
            [['total_service_one_pay', 'total_service_all_pay', 'total_service_price'], 'number'],
            [['currency_id'], 'required'],
            [['create_date', 'deposit_transfer_date', 'write_date'], 'safe'],
            [['invoiced', 'accept_contract', 'reserve_transport'], 'boolean'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountInvoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['x_paralelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['x_paralelo_id' => 'id']],
            [['mother_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpParent::className(), 'targetAttribute' => ['mother_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpParent::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['father_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpParent::className(), 'targetAttribute' => ['father_id' => 'id']],
            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPeriod::className(), 'targetAttribute' => ['period_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['inscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentInscription::className(), 'targetAttribute' => ['inscription_id' => 'id']],
            [['sector_ret_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountrySector::className(), 'targetAttribute' => ['sector_ret_id' => 'id']],
            [['sector_del_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountrySector::className(), 'targetAttribute' => ['sector_del_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCurrency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ret_longitude' => 'Ret Longitude',
            'ret_latitude' => 'Ret Latitude',
            'del_latitude' => 'Del Latitude',
            'payment_method' => 'Payment Method',
            'mother_id' => 'Mother ID',
            'total_service_one_pay' => 'Total Service One Pay',
            'write_uid' => 'Write Uid',
            'currency_id' => 'Currency ID',
            'course_id' => 'Course ID',
            'create_date' => 'Create Date',
            'deposit_transfer_date' => 'Deposit Transfer Date',
            'invoiced' => 'Invoiced',
            'deposit_number' => 'Deposit Number',
            'create_uid' => 'Create Uid',
            'student_id' => 'Student ID',
            'total_service_all_pay' => 'Total Service All Pay',
            'parent_id' => 'Parent ID',
            'state' => 'State',
            'father_id' => 'Father ID',
            'del_longitude' => 'Del Longitude',
            'payment_way' => 'Payment Way',
            'x_paralelo_id' => 'X Paralelo ID',
            'total_service_price' => 'Total Service Price',
            'dir_ret_ref' => 'Dir Ret Ref',
            'sector_ret_id' => 'Sector Ret ID',
            'period_id' => 'Period ID',
            'write_date' => 'Write Date',
            'accept_contract' => 'Accept Contract',
            'name' => 'Name',
            'dir_del_ref' => 'Dir Del Ref',
            'log_detail' => 'Log Detail',
            'invoice_id' => 'Invoice ID',
            'reserve_transport' => 'Reserve Transport',
            'inscription_id' => 'Inscription ID',
            'sector_del_id' => 'Sector Del ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents()
    {
        return $this->hasMany(OpStudent::className(), ['x_enrollment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(AccountInvoice::className(), ['id' => 'invoice_id']);
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
    public function getXParalelo()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'x_paralelo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMother()
    {
        return $this->hasOne(OpParent::className(), ['id' => 'mother_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(OpParent::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFather()
    {
        return $this->hasOne(OpParent::className(), ['id' => 'father_id']);
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
    public function getInscription()
    {
        return $this->hasOne(OpStudentInscription::className(), ['id' => 'inscription_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectorRet()
    {
        return $this->hasOne(ResCountrySector::className(), ['id' => 'sector_ret_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectorDel()
    {
        return $this->hasOne(ResCountrySector::className(), ['id' => 'sector_del_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(ResCurrency::className(), ['id' => 'currency_id']);
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
    public function getCreateU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'create_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollmentServices()
    {
        return $this->hasMany(OpStudentEnrollmentServices::className(), ['enrollment_id' => 'id']);
    }
}
