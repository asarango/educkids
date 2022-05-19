<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "res_partner".
 *
 * @property int $id
 * @property string $name
 * @property int $company_id
 * @property string $comment Notes
 * @property string $function Job Position
 * @property string $create_date Created on
 * @property int $color Color Index
 * @property string $company_type Company Type
 * @property string $date Date
 * @property string $street Street
 * @property string $city City
 * @property string $display_name Name
 * @property string $zip Zip
 * @property int $title Title
 * @property int $country_id Country
 * @property int $parent_id Related Company
 * @property bool $supplier Is a Vendor
 * @property string $ref Internal Reference
 * @property string $email Email
 * @property bool $is_company Is a Company
 * @property string $website Website
 * @property bool $customer Is a Customer
 * @property string $fax Fax
 * @property string $street2 Street2
 * @property string $barcode Barcode
 * @property bool $employee Employee
 * @property double $credit_limit Credit Limit
 * @property string $write_date Last Updated on
 * @property bool $active Active
 * @property string $tz Timezone
 * @property int $write_uid Last Updated by
 * @property string $lang Language
 * @property int $create_uid Created by
 * @property string $phone Phone
 * @property string $mobile Mobile
 * @property string $type Address Type
 * @property bool $use_parent_address Use Company Address
 * @property int $user_id Salesperson
 * @property string $birthdate Birthdate
 * @property string $vat TIN
 * @property int $state_id State
 * @property int $commercial_partner_id Commercial Entity
 * @property string $notify_email Email Messages and Notifications
 * @property string $message_last_post Last Message Date
 * @property bool $opt_out Opt-Out
 * @property string $signup_type Signup Token Type
 * @property string $signup_expiration Signup Expiration
 * @property string $signup_token Signup Token
 * @property int $team_id Sales Team
 * @property string $last_time_entries_checked Latest Invoices & Payments Matching Date
 * @property string $debit_limit Payable Limit
 * @property int $x_nacionalidad_id Nacionalidad
 * @property int $x_city_id Ciudad
 * @property bool $x_plata Financiero
 * @property int $x_clasification_id Categoría
 * @property bool $x_autoriza Autoriza
 * @property int $x_parish_id Parish
 * @property bool $x_necesidad Necesidad
 * @property int $x_sector_id Sector
 * @property int $x_canton_id Cantón
 * @property string $tipo_identificacion Tipo Identificacion
 * @property string $correo_electronico Email Fact. Electronica
 * @property string $numero_identificacion Número Identificación
 * @property string $posicion Posición
 * @property bool $contribuyente Contribuyente
 * @property bool $contabilidad Contabilidad
 * @property bool $parte_relacionada Parte Relacionada
 * @property string $nombre_comercial Nombre Comercial
 * @property string $work_address work_address
 * @property string $x_estado_civil Estado civil
 * @property string $calendar_last_notif_ack Last notification marked as read from base Calendar
 * @property bool $x_receive_due_transfer
 * @property string $x_work_location
 * @property string $x_work_address
 * @property string $x_work_phone
 * @property int $x_civil_status_id
 * @property int $x_gender_id
 * @property string $x_birth_date
 * @property bool $x_beneficiary
 *
 * @property AccountAnalyticAccount[] $accountAnalyticAccounts
 * @property AccountAnalyticLine[] $accountAnalyticLines
 * @property AccountAssetAsset[] $accountAssetAssets
 * @property AccountBankReconciliationLine[] $accountBankReconciliationLines
 * @property AccountBankStatementLine[] $accountBankStatementLines
 * @property AccountInvoice[] $accountInvoices
 * @property AccountInvoice[] $accountInvoices0
 * @property AccountInvoice[] $accountInvoices1
 * @property AccountInvoiceConfiguration[] $accountInvoiceConfigurations
 * @property AccountInvoiceLine[] $accountInvoiceLines
 * @property AccountInvoiceTax[] $accountInvoiceTaxes
 * @property AccountInvoicesRefunds[] $accountInvoicesRefunds
 * @property AccountMove[] $accountMoves
 * @property AccountMoveLine[] $accountMoveLines
 * @property AccountPayment[] $accountPayments
 * @property AccountRegisterPayments[] $accountRegisterPayments
 * @property AccountReportGeneralLedger[] $accountReportGeneralLedgers
 * @property AccountRetentionCommission[] $accountRetentionCommissions
 * @property AccountVoucher[] $accountVouchers
 * @property BaseActionRuleLeadTest[] $baseActionRuleLeadTests
 * @property BaseActionRuleResPartnerRel[] $baseActionRuleResPartnerRels
 * @property BaseActionRule[] $baseActionRules
 * @property CalendarAttendee[] $calendarAttendees
 * @property CalendarContacts[] $calendarContacts
 * @property CalendarEventResPartnerRel[] $calendarEventResPartnerRels
 * @property CalendarEvent[] $calendarEvents
 * @property ElectronicConfiguration[] $electronicConfigurations
 * @property EmailTemplatePreviewResPartnerRel[] $emailTemplatePreviewResPartnerRels
 * @property EmailTemplatePreview[] $emailTemplatePreviews
 * @property HrContributionRegister[] $hrContributionRegisters
 * @property HrEmployee[] $hrEmployees
 * @property HrEmployee[] $hrEmployees0
 * @property HrEquipment[] $hrEquipments
 * @property InvoiceElectronicReceived[] $invoiceElectronicReceiveds
 * @property MailChannelPartner[] $mailChannelPartners
 * @property MailComposeMessage[] $mailComposeMessages
 * @property MailComposeMessageResPartnerRel[] $mailComposeMessageResPartnerRels
 * @property MailComposeMessage[] $wizards
 * @property MailFollowers[] $mailFollowers
 * @property MailMailResPartnerRel[] $mailMailResPartnerRels
 * @property MailMail[] $mailMails
 * @property MailMessage[] $mailMessages
 * @property MailMessageResPartnerNeedactionRel[] $mailMessageResPartnerNeedactionRels
 * @property MailMessage[] $mailMessages0
 * @property MailMessageResPartnerRel[] $mailMessageResPartnerRels
 * @property MailMessage[] $mailMessages1
 * @property MailMessageResPartnerStarredRel[] $mailMessageResPartnerStarredRels
 * @property MailMessage[] $mailMessages2
 * @property MailWizardInviteResPartnerRel[] $mailWizardInviteResPartnerRels
 * @property MailWizardInvite[] $mailWizardInvites
 * @property OpAdmission[] $opAdmissions
 * @property OpAdmission[] $opAdmissions0
 * @property OpAuthor[] $opAuthors
 * @property OpBookMovement[] $opBookMovements
 * @property OpBookPurchase[] $opBookPurchases
 * @property OpBookQueue[] $opBookQueues
 * @property OpExam[] $opExams
 * @property OpFaculty[] $opFaculties
 * @property OpFaculty[] $opFaculties0
 * @property OpHostelRoomResPartnerRel[] $opHostelRoomResPartnerRels
 * @property OpHostelRoom[] $opHostelRooms
 * @property OpLibraryCard[] $opLibraryCards
 * @property OpParent[] $opParents
 * @property OpPublisher[] $opPublishers
 * @property OpStudent[] $opStudents
 * @property OpStudent[] $opStudents0
 * @property OpStudent[] $opStudents1
 * @property OpStudent[] $opStudents2
 * @property OpStudentResPartnerRel[] $opStudentResPartnerRels
 * @property OpStudent[] $opStudents3
 * @property OpVehicle[] $opVehicles
 * @property PaymentMethod[] $paymentMethods
 * @property PaymentTransaction[] $paymentTransactions
 * @property PortalWizardUser[] $portalWizardUsers
 * @property ProcurementGroup[] $procurementGroups
 * @property ProcurementOrder[] $procurementOrders
 * @property ProcurementRule[] $procurementRules
 * @property ProductBrand[] $productBrands
 * @property ProductSupplierinfo[] $productSupplierinfos
 * @property ProfesionResPartnerRel[] $profesionResPartnerRels
 * @property Profesion[] $profesions
 * @property PurchaseOrder[] $purchaseOrders
 * @property PurchaseOrder[] $purchaseOrders0
 * @property PurchaseOrderLine[] $purchaseOrderLines
 * @property ReporteAmountByPartnerWizardResPartnerRel[] $reporteAmountByPartnerWizardResPartnerRels
 * @property ReporteAmountByPartnerWizard[] $reporteAmountByPartnerWizards
 * @property ResCompany[] $resCompanies
 * @property CrmTeam $team
 * @property ResCompany $company
 * @property ResCountry $country
 * @property ResCountryCanton $xCanton
 * @property ResCountryCity $xCity
 * @property ResCountryNationality $xNacionalidad
 * @property ResCountryParish $xParish
 * @property ResCountrySector $xSector
 * @property ResCountryState $state
 * @property ResPartner $parent
 * @property ResPartner[] $resPartners
 * @property ResPartner $commercialPartner
 * @property ResPartner[] $resPartners0
 * @property ResPartnerCivilStatus $xCivilStatus
 * @property ResPartnerClasification $xClasification
 * @property ResPartnerGender $xGender
 * @property ResPartnerTitle $title0
 * @property ResUsers $writeU
 * @property ResUsers $createU
 * @property ResUsers $user
 * @property ResPartnerBank[] $resPartnerBanks
 * @property ResPartnerResPartnerCategoryRel[] $resPartnerResPartnerCategoryRels
 * @property ResPartnerCategory[] $categories
 * @property ResUsers[] $resUsers
 * @property ReserveBook[] $reserveBooks
 * @property SaleOrder[] $saleOrders
 * @property SaleOrder[] $saleOrders0
 * @property SaleOrder[] $saleOrders1
 * @property SaleOrderLine[] $saleOrderLines
 * @property StockInventory[] $stockInventories
 * @property StockInventoryLine[] $stockInventoryLines
 * @property StockLocation[] $stockLocations
 * @property StockMove[] $stockMoves
 * @property StockMove[] $stockMoves0
 * @property StockPackOperation[] $stockPackOperations
 * @property StockPicking[] $stockPickings
 * @property StockPicking[] $stockPickings0
 * @property StockQuant[] $stockQuants
 * @property StockQuantPackage[] $stockQuantPackages
 * @property StockWarehouse[] $stockWarehouses
 * @property SurveyMailComposeMessage[] $surveyMailComposeMessages
 * @property SurveyMailComposeMessageResPartnerRel[] $surveyMailComposeMessageResPartnerRels
 * @property SurveyMailComposeMessage[] $wizards0
 * @property SurveyUserInput[] $surveyUserInputs
 * @property WizardSelectMoveTemplate[] $wizardSelectMoveTemplates
 */
class ResPartner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'res_partner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'comment', 'function', 'company_type', 'street', 'city', 'display_name', 'ref', 'email', 'website', 'fax', 'street2', 'barcode', 'lang', 'phone', 'mobile', 'type', 'birthdate', 'vat', 'notify_email', 'signup_type', 'signup_token', 'tipo_identificacion', 'x_estado_civil', 'x_work_location', 'x_work_address', 'x_work_phone'], 'string'],
            [['company_id', 'color', 'title', 'country_id', 'parent_id', 'write_uid', 'create_uid', 'user_id', 'state_id', 'commercial_partner_id', 'team_id', 'x_nacionalidad_id', 'x_city_id', 'x_clasification_id', 'x_parish_id', 'x_sector_id', 'x_canton_id', 'x_civil_status_id', 'x_gender_id'], 'default', 'value' => null],
            [['company_id', 'color', 'title', 'country_id', 'parent_id', 'write_uid', 'create_uid', 'user_id', 'state_id', 'commercial_partner_id', 'team_id', 'x_nacionalidad_id', 'x_city_id', 'x_clasification_id', 'x_parish_id', 'x_sector_id', 'x_canton_id', 'x_civil_status_id', 'x_gender_id'], 'integer'],
            [['create_date', 'date', 'write_date', 'message_last_post', 'signup_expiration', 'last_time_entries_checked', 'calendar_last_notif_ack', 'x_birth_date'], 'safe'],
            [['supplier', 'is_company', 'customer', 'employee', 'active', 'use_parent_address', 'opt_out', 'x_plata', 'x_autoriza', 'x_necesidad', 'contribuyente', 'contabilidad', 'parte_relacionada', 'x_receive_due_transfer', 'x_beneficiary'], 'boolean'],
            [['credit_limit', 'debit_limit'], 'number'],
            [['notify_email', 'tipo_identificacion', 'correo_electronico', 'nombre_comercial'], 'required'],
            [['zip'], 'string', 'max' => 24],
            [['tz'], 'string', 'max' => 64],
            [['correo_electronico', 'nombre_comercial'], 'string', 'max' => 150],
            [['numero_identificacion'], 'string', 'max' => 13],
            [['posicion'], 'string', 'max' => 30],
            [['work_address'], 'string', 'max' => 300],
            [['team_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrmTeam::className(), 'targetAttribute' => ['team_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['x_canton_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryCanton::className(), 'targetAttribute' => ['x_canton_id' => 'id']],
            [['x_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryCity::className(), 'targetAttribute' => ['x_city_id' => 'id']],
            [['x_nacionalidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryNationality::className(), 'targetAttribute' => ['x_nacionalidad_id' => 'id']],
            [['x_parish_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryParish::className(), 'targetAttribute' => ['x_parish_id' => 'id']],
            [['x_sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountrySector::className(), 'targetAttribute' => ['x_sector_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryState::className(), 'targetAttribute' => ['state_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['commercial_partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['commercial_partner_id' => 'id']],
            [['x_civil_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartnerCivilStatus::className(), 'targetAttribute' => ['x_civil_status_id' => 'id']],
            [['x_clasification_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartnerClasification::className(), 'targetAttribute' => ['x_clasification_id' => 'id']],
            [['x_gender_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartnerGender::className(), 'targetAttribute' => ['x_gender_id' => 'id']],
            [['title'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartnerTitle::className(), 'targetAttribute' => ['title' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
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
            'name' => 'Name',
            'company_id' => 'Company ID',
            'comment' => 'Comment',
            'function' => 'Function',
            'create_date' => 'Create Date',
            'color' => 'Color',
            'company_type' => 'Company Type',
            'date' => 'Date',
            'street' => 'Street',
            'city' => 'City',
            'display_name' => 'Display Name',
            'zip' => 'Zip',
            'title' => 'Title',
            'country_id' => 'Country ID',
            'parent_id' => 'Parent ID',
            'supplier' => 'Supplier',
            'ref' => 'Ref',
            'email' => 'Email',
            'is_company' => 'Is Company',
            'website' => 'Website',
            'customer' => 'Customer',
            'fax' => 'Fax',
            'street2' => 'Street2',
            'barcode' => 'Barcode',
            'employee' => 'Employee',
            'credit_limit' => 'Credit Limit',
            'write_date' => 'Write Date',
            'active' => 'Active',
            'tz' => 'Tz',
            'write_uid' => 'Write Uid',
            'lang' => 'Lang',
            'create_uid' => 'Create Uid',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'type' => 'Type',
            'use_parent_address' => 'Use Parent Address',
            'user_id' => 'User ID',
            'birthdate' => 'Birthdate',
            'vat' => 'Vat',
            'state_id' => 'State ID',
            'commercial_partner_id' => 'Commercial Partner ID',
            'notify_email' => 'Notify Email',
            'message_last_post' => 'Message Last Post',
            'opt_out' => 'Opt Out',
            'signup_type' => 'Signup Type',
            'signup_expiration' => 'Signup Expiration',
            'signup_token' => 'Signup Token',
            'team_id' => 'Team ID',
            'last_time_entries_checked' => 'Last Time Entries Checked',
            'debit_limit' => 'Debit Limit',
            'x_nacionalidad_id' => 'X Nacionalidad ID',
            'x_city_id' => 'X City ID',
            'x_plata' => 'X Plata',
            'x_clasification_id' => 'X Clasification ID',
            'x_autoriza' => 'X Autoriza',
            'x_parish_id' => 'X Parish ID',
            'x_necesidad' => 'X Necesidad',
            'x_sector_id' => 'X Sector ID',
            'x_canton_id' => 'X Canton ID',
            'tipo_identificacion' => 'Tipo Identificacion',
            'correo_electronico' => 'Correo Electronico',
            'numero_identificacion' => 'Numero Identificacion',
            'posicion' => 'Posicion',
            'contribuyente' => 'Contribuyente',
            'contabilidad' => 'Contabilidad',
            'parte_relacionada' => 'Parte Relacionada',
            'nombre_comercial' => 'Nombre Comercial',
            'work_address' => 'Work Address',
            'x_estado_civil' => 'X Estado Civil',
            'calendar_last_notif_ack' => 'Calendar Last Notif Ack',
            'x_receive_due_transfer' => 'X Receive Due Transfer',
            'x_work_location' => 'X Work Location',
            'x_work_address' => 'X Work Address',
            'x_work_phone' => 'X Work Phone',
            'x_civil_status_id' => 'X Civil Status ID',
            'x_gender_id' => 'X Gender ID',
            'x_birth_date' => 'X Birth Date',
            'x_beneficiary' => 'X Beneficiary',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAnalyticAccounts()
    {
        return $this->hasMany(AccountAnalyticAccount::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAnalyticLines()
    {
        return $this->hasMany(AccountAnalyticLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAssetAssets()
    {
        return $this->hasMany(AccountAssetAsset::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBankReconciliationLines()
    {
        return $this->hasMany(AccountBankReconciliationLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBankStatementLines()
    {
        return $this->hasMany(AccountBankStatementLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoices()
    {
        return $this->hasMany(AccountInvoice::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoices0()
    {
        return $this->hasMany(AccountInvoice::className(), ['commercial_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoices1()
    {
        return $this->hasMany(AccountInvoice::className(), ['x_transfer_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoiceConfigurations()
    {
        return $this->hasMany(AccountInvoiceConfiguration::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoiceLines()
    {
        return $this->hasMany(AccountInvoiceLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoiceTaxes()
    {
        return $this->hasMany(AccountInvoiceTax::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoicesRefunds()
    {
        return $this->hasMany(AccountInvoicesRefunds::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountMoves()
    {
        return $this->hasMany(AccountMove::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountMoveLines()
    {
        return $this->hasMany(AccountMoveLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPayments()
    {
        return $this->hasMany(AccountPayment::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountRegisterPayments()
    {
        return $this->hasMany(AccountRegisterPayments::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountReportGeneralLedgers()
    {
        return $this->hasMany(AccountReportGeneralLedger::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountRetentionCommissions()
    {
        return $this->hasMany(AccountRetentionCommission::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountVouchers()
    {
        return $this->hasMany(AccountVoucher::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseActionRuleLeadTests()
    {
        return $this->hasMany(BaseActionRuleLeadTest::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseActionRuleResPartnerRels()
    {
        return $this->hasMany(BaseActionRuleResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseActionRules()
    {
        return $this->hasMany(BaseActionRule::className(), ['id' => 'base_action_rule_id'])->viaTable('base_action_rule_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarAttendees()
    {
        return $this->hasMany(CalendarAttendee::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarContacts()
    {
        return $this->hasMany(CalendarContacts::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarEventResPartnerRels()
    {
        return $this->hasMany(CalendarEventResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarEvents()
    {
        return $this->hasMany(CalendarEvent::className(), ['id' => 'calendar_event_id'])->viaTable('calendar_event_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElectronicConfigurations()
    {
        return $this->hasMany(ElectronicConfiguration::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailTemplatePreviewResPartnerRels()
    {
        return $this->hasMany(EmailTemplatePreviewResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailTemplatePreviews()
    {
        return $this->hasMany(EmailTemplatePreview::className(), ['id' => 'email_template_preview_id'])->viaTable('email_template_preview_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrContributionRegisters()
    {
        return $this->hasMany(HrContributionRegister::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees0()
    {
        return $this->hasMany(HrEmployee::className(), ['address_home_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEquipments()
    {
        return $this->hasMany(HrEquipment::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceElectronicReceiveds()
    {
        return $this->hasMany(InvoiceElectronicReceived::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailChannelPartners()
    {
        return $this->hasMany(MailChannelPartner::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailComposeMessages()
    {
        return $this->hasMany(MailComposeMessage::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailComposeMessageResPartnerRels()
    {
        return $this->hasMany(MailComposeMessageResPartnerRel::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizards()
    {
        return $this->hasMany(MailComposeMessage::className(), ['id' => 'wizard_id'])->viaTable('mail_compose_message_res_partner_rel', ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailFollowers()
    {
        return $this->hasMany(MailFollowers::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMailResPartnerRels()
    {
        return $this->hasMany(MailMailResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMails()
    {
        return $this->hasMany(MailMail::className(), ['id' => 'mail_mail_id'])->viaTable('mail_mail_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessages()
    {
        return $this->hasMany(MailMessage::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessageResPartnerNeedactionRels()
    {
        return $this->hasMany(MailMessageResPartnerNeedactionRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessages0()
    {
        return $this->hasMany(MailMessage::className(), ['id' => 'mail_message_id'])->viaTable('mail_message_res_partner_needaction_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessageResPartnerRels()
    {
        return $this->hasMany(MailMessageResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessages1()
    {
        return $this->hasMany(MailMessage::className(), ['id' => 'mail_message_id'])->viaTable('mail_message_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessageResPartnerStarredRels()
    {
        return $this->hasMany(MailMessageResPartnerStarredRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessages2()
    {
        return $this->hasMany(MailMessage::className(), ['id' => 'mail_message_id'])->viaTable('mail_message_res_partner_starred_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailWizardInviteResPartnerRels()
    {
        return $this->hasMany(MailWizardInviteResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailWizardInvites()
    {
        return $this->hasMany(MailWizardInvite::className(), ['id' => 'mail_wizard_invite_id'])->viaTable('mail_wizard_invite_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAdmissions()
    {
        return $this->hasMany(OpAdmission::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAdmissions0()
    {
        return $this->hasMany(OpAdmission::className(), ['prev_institute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAuthors()
    {
        return $this->hasMany(OpAuthor::className(), ['address' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpBookMovements()
    {
        return $this->hasMany(OpBookMovement::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpBookPurchases()
    {
        return $this->hasMany(OpBookPurchase::className(), ['requested_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpBookQueues()
    {
        return $this->hasMany(OpBookQueue::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExams()
    {
        return $this->hasMany(OpExam::className(), ['venue' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFaculties()
    {
        return $this->hasMany(OpFaculty::className(), ['emergency_contact' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFaculties0()
    {
        return $this->hasMany(OpFaculty::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpHostelRoomResPartnerRels()
    {
        return $this->hasMany(OpHostelRoomResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpHostelRooms()
    {
        return $this->hasMany(OpHostelRoom::className(), ['id' => 'op_hostel_room_id'])->viaTable('op_hostel_room_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpLibraryCards()
    {
        return $this->hasMany(OpLibraryCard::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpParents()
    {
        return $this->hasMany(OpParent::className(), ['name' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPublishers()
    {
        return $this->hasMany(OpPublisher::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents()
    {
        return $this->hasMany(OpStudent::className(), ['emergency_contact' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents0()
    {
        return $this->hasMany(OpStudent::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents1()
    {
        return $this->hasMany(OpStudent::className(), ['x_factura_a' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents2()
    {
        return $this->hasMany(OpStudent::className(), ['x_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentResPartnerRels()
    {
        return $this->hasMany(OpStudentResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents3()
    {
        return $this->hasMany(OpStudent::className(), ['id' => 'op_student_id'])->viaTable('op_student_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpVehicles()
    {
        return $this->hasMany(OpVehicle::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPortalWizardUsers()
    {
        return $this->hasMany(PortalWizardUser::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcurementGroups()
    {
        return $this->hasMany(ProcurementGroup::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcurementOrders()
    {
        return $this->hasMany(ProcurementOrder::className(), ['partner_dest_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcurementRules()
    {
        return $this->hasMany(ProcurementRule::className(), ['partner_address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductBrands()
    {
        return $this->hasMany(ProductBrand::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSupplierinfos()
    {
        return $this->hasMany(ProductSupplierinfo::className(), ['name' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesionResPartnerRels()
    {
        return $this->hasMany(ProfesionResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesions()
    {
        return $this->hasMany(Profesion::className(), ['id' => 'profesion_id'])->viaTable('profesion_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrders0()
    {
        return $this->hasMany(PurchaseOrder::className(), ['dest_address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderLines()
    {
        return $this->hasMany(PurchaseOrderLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReporteAmountByPartnerWizardResPartnerRels()
    {
        return $this->hasMany(ReporteAmountByPartnerWizardResPartnerRel::className(), ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReporteAmountByPartnerWizards()
    {
        return $this->hasMany(ReporteAmountByPartnerWizard::className(), ['id' => 'reporte_amount_by_partner_wizard_id'])->viaTable('reporte_amount_by_partner_wizard_res_partner_rel', ['res_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResCompanies()
    {
        return $this->hasMany(ResCompany::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(CrmTeam::className(), ['id' => 'team_id']);
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
    public function getCountry()
    {
        return $this->hasOne(ResCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXCanton()
    {
        return $this->hasOne(ResCountryCanton::className(), ['id' => 'x_canton_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXCity()
    {
        return $this->hasOne(ResCountryCity::className(), ['id' => 'x_city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXNacionalidad()
    {
        return $this->hasOne(ResCountryNationality::className(), ['id' => 'x_nacionalidad_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXParish()
    {
        return $this->hasOne(ResCountryParish::className(), ['id' => 'x_parish_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXSector()
    {
        return $this->hasOne(ResCountrySector::className(), ['id' => 'x_sector_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(ResCountryState::className(), ['id' => 'state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartners()
    {
        return $this->hasMany(ResPartner::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommercialPartner()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'commercial_partner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartners0()
    {
        return $this->hasMany(ResPartner::className(), ['commercial_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXCivilStatus()
    {
        return $this->hasOne(ResPartnerCivilStatus::className(), ['id' => 'x_civil_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXClasification()
    {
        return $this->hasOne(ResPartnerClasification::className(), ['id' => 'x_clasification_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXGender()
    {
        return $this->hasOne(ResPartnerGender::className(), ['id' => 'x_gender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitle0()
    {
        return $this->hasOne(ResPartnerTitle::className(), ['id' => 'title']);
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
    public function getUser()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartnerBanks()
    {
        return $this->hasMany(ResPartnerBank::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartnerResPartnerCategoryRels()
    {
        return $this->hasMany(ResPartnerResPartnerCategoryRel::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(ResPartnerCategory::className(), ['id' => 'category_id'])->viaTable('res_partner_res_partner_category_rel', ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResUsers()
    {
        return $this->hasMany(ResUsers::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReserveBooks()
    {
        return $this->hasMany(ReserveBook::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrders()
    {
        return $this->hasMany(SaleOrder::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrders0()
    {
        return $this->hasMany(SaleOrder::className(), ['partner_invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrders1()
    {
        return $this->hasMany(SaleOrder::className(), ['partner_shipping_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrderLines()
    {
        return $this->hasMany(SaleOrderLine::className(), ['order_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockInventories()
    {
        return $this->hasMany(StockInventory::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockInventoryLines()
    {
        return $this->hasMany(StockInventoryLine::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockLocations()
    {
        return $this->hasMany(StockLocation::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockMoves()
    {
        return $this->hasMany(StockMove::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockMoves0()
    {
        return $this->hasMany(StockMove::className(), ['restrict_partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockPackOperations()
    {
        return $this->hasMany(StockPackOperation::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockPickings()
    {
        return $this->hasMany(StockPicking::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockPickings0()
    {
        return $this->hasMany(StockPicking::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockQuants()
    {
        return $this->hasMany(StockQuant::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockQuantPackages()
    {
        return $this->hasMany(StockQuantPackage::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockWarehouses()
    {
        return $this->hasMany(StockWarehouse::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyMailComposeMessages()
    {
        return $this->hasMany(SurveyMailComposeMessage::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyMailComposeMessageResPartnerRels()
    {
        return $this->hasMany(SurveyMailComposeMessageResPartnerRel::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizards0()
    {
        return $this->hasMany(SurveyMailComposeMessage::className(), ['id' => 'wizard_id'])->viaTable('survey_mail_compose_message_res_partner_rel', ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurveyUserInputs()
    {
        return $this->hasMany(SurveyUserInput::className(), ['partner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizardSelectMoveTemplates()
    {
        return $this->hasMany(WizardSelectMoveTemplate::className(), ['partner_id' => 'id']);
    }
}
