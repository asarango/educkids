<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "res_store".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property int $write_uid Last Updated by
 * @property string $create_date Created on
 * @property string $name Name
 * @property string $x_direccion Direccion
 * @property int $seq_req_id Secuencia de Requisicion
 * @property int $company_id Company
 * @property int $parent_id Parent Store
 * @property int $seq_pay_supp_out Secuencia de Egresos Out
 * @property int $seq_pay_cust_in Secuencia de Ingresos
 * @property string $write_date Last Updated on
 * @property int $seq_pay_cust_out Secuencia de Cust Out
 * @property int $seq_pay_supp_in Secuencia de Egresos
 * @property int $account_analytic_id Analytic Account
 * @property string $x_establishment Código establecimiento
 *
 * @property AccountAssetAsset[] $accountAssetAssets
 * @property AccountPayment[] $accountPayments
 * @property AccountRegisterPayments[] $accountRegisterPayments
 * @property HrEmployee[] $hrEmployees
 * @property OpInstitute[] $opInstitutes
 * @property PurchaseRequisition[] $purchaseRequisitions
 * @property ReporteCajaWizard[] $reporteCajaWizards
 * @property AccountAnalyticAccount $accountAnalytic
 * @property IrSequence $seqReq
 * @property IrSequence $seqPaySuppOut
 * @property IrSequence $seqPayCustIn
 * @property IrSequence $seqPayCustOut
 * @property IrSequence $seqPaySuppIn
 * @property ResCompany $company
 * @property ResStore $parent
 * @property ResStore[] $resStores
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property ResStoreJournalRel[] $resStoreJournalRels
 * @property AccountJournal[] $journals
 * @property ResStoreUsersRel[] $resStoreUsersRels
 * @property ResUsers[] $users
 * @property ResUsers[] $resUsers
 */
class ResStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'res_store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'seq_req_id', 'company_id', 'parent_id', 'seq_pay_supp_out', 'seq_pay_cust_in', 'seq_pay_cust_out', 'seq_pay_supp_in', 'account_analytic_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'seq_req_id', 'company_id', 'parent_id', 'seq_pay_supp_out', 'seq_pay_cust_in', 'seq_pay_cust_out', 'seq_pay_supp_in', 'account_analytic_id'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name', 'x_direccion', 'company_id', 'seq_pay_supp_out', 'seq_pay_cust_in', 'seq_pay_cust_out', 'seq_pay_supp_in'], 'required'],
            [['x_establishment'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['x_direccion'], 'string', 'max' => 250],
            [['name'], 'unique'],
            [['account_analytic_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountAnalyticAccount::className(), 'targetAttribute' => ['account_analytic_id' => 'id']],
            [['seq_req_id'], 'exist', 'skipOnError' => true, 'targetClass' => IrSequence::className(), 'targetAttribute' => ['seq_req_id' => 'id']],
            [['seq_pay_supp_out'], 'exist', 'skipOnError' => true, 'targetClass' => IrSequence::className(), 'targetAttribute' => ['seq_pay_supp_out' => 'id']],
            [['seq_pay_cust_in'], 'exist', 'skipOnError' => true, 'targetClass' => IrSequence::className(), 'targetAttribute' => ['seq_pay_cust_in' => 'id']],
            [['seq_pay_cust_out'], 'exist', 'skipOnError' => true, 'targetClass' => IrSequence::className(), 'targetAttribute' => ['seq_pay_cust_out' => 'id']],
            [['seq_pay_supp_in'], 'exist', 'skipOnError' => true, 'targetClass' => IrSequence::className(), 'targetAttribute' => ['seq_pay_supp_in' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResStore::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'write_uid' => 'Write Uid',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'x_direccion' => 'X Direccion',
            'seq_req_id' => 'Seq Req ID',
            'company_id' => 'Company ID',
            'parent_id' => 'Parent ID',
            'seq_pay_supp_out' => 'Seq Pay Supp Out',
            'seq_pay_cust_in' => 'Seq Pay Cust In',
            'write_date' => 'Write Date',
            'seq_pay_cust_out' => 'Seq Pay Cust Out',
            'seq_pay_supp_in' => 'Seq Pay Supp In',
            'account_analytic_id' => 'Account Analytic ID',
            'x_establishment' => 'X Establishment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAssetAssets()
    {
        return $this->hasMany(AccountAssetAsset::className(), ['x_store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPayments()
    {
        return $this->hasMany(AccountPayment::className(), ['x_store_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountRegisterPayments()
    {
        return $this->hasMany(AccountRegisterPayments::className(), ['x_store_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['x_store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpInstitutes()
    {
        return $this->hasMany(OpInstitute::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRequisitions()
    {
        return $this->hasMany(PurchaseRequisition::className(), ['x_store_user' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReporteCajaWizards()
    {
        return $this->hasMany(ReporteCajaWizard::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAnalytic()
    {
        return $this->hasOne(AccountAnalyticAccount::className(), ['id' => 'account_analytic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeqReq()
    {
        return $this->hasOne(IrSequence::className(), ['id' => 'seq_req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeqPaySuppOut()
    {
        return $this->hasOne(IrSequence::className(), ['id' => 'seq_pay_supp_out']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeqPayCustIn()
    {
        return $this->hasOne(IrSequence::className(), ['id' => 'seq_pay_cust_in']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeqPayCustOut()
    {
        return $this->hasOne(IrSequence::className(), ['id' => 'seq_pay_cust_out']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeqPaySuppIn()
    {
        return $this->hasOne(IrSequence::className(), ['id' => 'seq_pay_supp_in']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ResCompany::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ResStore::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResStores()
    {
        return $this->hasMany(ResStore::className(), ['parent_id' => 'id']);
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
    public function getResStoreJournalRels()
    {
        return $this->hasMany(ResStoreJournalRel::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournals()
    {
        return $this->hasMany(AccountJournal::className(), ['id' => 'journal_id'])->viaTable('res_store_journal_rel', ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResStoreUsersRels()
    {
        return $this->hasMany(ResStoreUsersRel::className(), ['cid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(ResUsers::className(), ['id' => 'user_id'])->viaTable('res_store_users_rel', ['cid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResUsers()
    {
        return $this->hasMany(ResUsers::className(), ['store_id' => 'id']);
    }
}
