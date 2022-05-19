<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "res_company".
 *
 * @property int $id
 * @property string $name
 * @property int $partner_id
 * @property int $currency_id
 * @property string $rml_footer Report Footer
 * @property string $create_date Created on
 * @property string $rml_header RML Header
 * @property string $rml_paper_format Paper Format
 * @property int $write_uid Last Updated by
 * @property resource $logo_web Logo Web
 * @property int $font Font
 * @property string $account_no Account No.
 * @property int $parent_id Parent Company
 * @property string $email Email
 * @property int $create_uid Created by
 * @property bool $custom_footer Custom Footer
 * @property string $phone Phone
 * @property string $rml_header2 RML Internal Header
 * @property string $rml_header3 RML Internal Header for Landscape Reports
 * @property string $write_date Last Updated on
 * @property string $rml_header1 Company Tagline
 * @property string $company_registry Company Registry
 * @property int $paperformat_id Paper format
 * @property string $fiscalyear_lock_date Lock Date
 * @property string $bank_account_code_prefix Prefix of the bank accounts
 * @property string $cash_account_code_prefix Prefix of the cash accounts
 * @property bool $anglo_saxon_accounting Use anglo-saxon accounting
 * @property int $fiscalyear_last_day Fiscalyear last day
 * @property int $property_stock_account_input_categ_id Input Account for Stock Valuation
 * @property int $property_stock_valuation_account_id Account Template for Stock Valuation
 * @property bool $expects_chart_of_accounts Expects a Chart of Accounts
 * @property int $transfer_account_id Inter-Banks Transfer Account
 * @property int $property_stock_account_output_categ_id Output Account for Stock Valuation
 * @property int $currency_exchange_journal_id Exchange Gain or Loss Journal
 * @property string $period_lock_date Lock Date for Non-Advisers
 * @property string $paypal_account Paypal Account
 * @property int $accounts_code_digits Number of digits in an account code
 * @property int $chart_template_id Chart template id
 * @property string $overdue_msg Overdue Payments Message
 * @property int $fiscalyear_last_month Fiscalyear last month
 * @property string $tax_calculation_rounding_method Tax Calculation Rounding Method
 * @property string $accreditation Accreditation
 * @property string $approval_authority Approval Authority
 * @property resource $signature Signature
 * @property string $sale_note Default Terms and Conditions
 * @property int $propagation_minimum_delta Minimum Delta for Propagation of a Date Change on moves linked together
 * @property int $internal_transit_location_id Internal Transit Location
 * @property double $po_lead Purchase Lead Time
 * @property string $po_double_validation_amount Double validation amount
 * @property string $po_double_validation Levels of Approvals
 * @property string $x_project_start_date Fecha Inicio Proyecto
 * @property int $sequence Sequence
 * @property string $po_lock Purchase Order Modification
 * @property string $report_header Company Tagline
 * @property string $report_footer Report Footer
 * @property string $external_report_layout Document Template
 * @property int $resource_calendar_id
 * @property int $tax_cash_basis_journal_id Cash Basis Journal
 * @property bool $tax_exigibility Use Cash Basis
 * @property int $account_opening_move_id Opening Journal Entry
 * @property bool $account_setup_company_data_done Company Setup Marked As Done
 * @property bool $account_setup_bank_data_done Bank Setup Marked As Done
 * @property bool $account_setup_fy_data_done Financial Year Setup Marked As Done
 * @property bool $account_setup_coa_done Chart of Account Checked
 * @property bool $account_setup_bar_closed Setup Bar Closed
 * @property string $social_twitter Twitter Account
 * @property string $social_facebook Facebook Account
 * @property string $social_github GitHub Account
 * @property string $social_linkedin LinkedIn Account
 * @property string $social_youtube Youtube Account
 * @property string $social_googleplus Google+ Account
 * @property string $company_code
 * @property int $password_expiration Days
 * @property int $password_length Characters
 * @property int $password_lower Lowercase
 * @property int $password_upper Uppercase
 * @property int $password_numeric Numeric
 * @property int $password_special Special
 * @property int $password_history History
 * @property int $password_minimum Minimum Hours
 * @property double $security_lead Sales Safety Days
 *
 * @property AccountAccount[] $accountAccounts
 * @property AccountAgedTrialBalance[] $accountAgedTrialBalances
 * @property AccountAnalyticAccount[] $accountAnalyticAccounts
 * @property AccountAnalyticLine[] $accountAnalyticLines
 * @property AccountAssetAsset[] $accountAssetAssets
 * @property AccountAssetCategory[] $accountAssetCategories
 * @property AccountBalanceReport[] $accountBalanceReports
 * @property AccountBankReconciliation[] $accountBankReconciliations
 * @property AccountBankReconciliationLine[] $accountBankReconciliationLines
 * @property AccountBankStatement[] $accountBankStatements
 * @property AccountBankStatementLine[] $accountBankStatementLines
 * @property AccountChartTemplate[] $accountChartTemplates
 * @property AccountCommonAccountReport[] $accountCommonAccountReports
 * @property AccountCommonJournalReport[] $accountCommonJournalReports
 * @property AccountCommonPartnerReport[] $accountCommonPartnerReports
 * @property AccountCommonReport[] $accountCommonReports
 * @property AccountConfigSettings[] $accountConfigSettings
 * @property AccountDirectPaymentLine[] $accountDirectPaymentLines
 * @property AccountFinancialYearOp[] $accountFinancialYearOps
 * @property AccountFiscalPosition[] $accountFiscalPositions
 * @property AccountInvoice[] $accountInvoices
 * @property AccountInvoiceLine[] $accountInvoiceLines
 * @property AccountInvoiceTax[] $accountInvoiceTaxes
 * @property AccountJournal[] $accountJournals
 * @property AccountMove[] $accountMoves
 * @property AccountMoveLine[] $accountMoveLines
 * @property AccountMoveLineReconcile[] $accountMoveLineReconciles
 * @property AccountMoveTemplate[] $accountMoveTemplates
 * @property AccountOpening[] $accountOpenings
 * @property AccountPartialReconcile[] $accountPartialReconciles
 * @property AccountPayment[] $accountPayments
 * @property AccountPaymentLine[] $accountPaymentLines
 * @property AccountPaymentTerm[] $accountPaymentTerms
 * @property AccountPrintJournal[] $accountPrintJournals
 * @property AccountReconcileModel[] $accountReconcileModels
 * @property AccountReportGeneralLedger[] $accountReportGeneralLedgers
 * @property AccountReportPartnerLedger[] $accountReportPartnerLedgers
 * @property AccountTax[] $accountTaxes
 * @property AccountTaxReport[] $accountTaxReports
 * @property AccountTaxTemplate[] $accountTaxTemplates
 * @property AccountVoucher[] $accountVouchers
 * @property AccountVoucherLine[] $accountVoucherLines
 * @property AccountingReport[] $accountingReports
 * @property AtsPeriod[] $atsPeriods
 * @property BaseConfigSettings[] $baseConfigSettings
 * @property CashmanagementConfigSettings[] $cashmanagementConfigSettings
 * @property CostCentre[] $costCentres
 * @property CostCentreMove[] $costCentreMoves
 * @property CostCentreUplev[] $costCentreUplevs
 * @property CrmTeam[] $crmTeams
 * @property DateRange[] $dateRanges
 * @property DateRangeGenerator[] $dateRangeGenerators
 * @property DateRangeType[] $dateRangeTypes
 * @property ElectronicConfiguration[] $electronicConfigurations
 * @property FiscalAuthorizationRank[] $fiscalAuthorizationRanks
 * @property HrContract[] $hrContracts
 * @property HrContributionRegister[] $hrContributionRegisters
 * @property HrD3erSocialBenefit[] $hrD3erSocialBenefits
 * @property HrD4thSocialBenefit[] $hrD4thSocialBenefits
 * @property HrDepartment[] $hrDepartments
 * @property HrEmployee[] $hrEmployees
 * @property HrHolidaysStatus[] $hrHolidaysStatuses
 * @property HrJob[] $hrJobs
 * @property HrPayrollStructure[] $hrPayrollStructures
 * @property HrPayslip[] $hrPayslips
 * @property HrPayslipLine[] $hrPayslipLines
 * @property HrRdep[] $hrRdeps
 * @property HrRdepTagConfig[] $hrRdepTagConfigs
 * @property HrSalaryRule[] $hrSalaryRules
 * @property HrSalaryRuleCategory[] $hrSalaryRuleCategories
 * @property HummingbirdConfig $hummingbirdConfig
 * @property IapAccount[] $iapAccounts
 * @property InvoiceElectronicReceived[] $invoiceElectronicReceiveds
 * @property IrAttachment[] $irAttachments
 * @property IrDefault[] $irDefaults
 * @property IrProperty[] $irProperties
 * @property IrSequence[] $irSequences
 * @property IrValues[] $irValues
 * @property OpExtraValues[] $opExtraValues
 * @property PaymentAcquirer[] $paymentAcquirers
 * @property PrepaymentType[] $prepaymentTypes
 * @property ProcurementOrder[] $procurementOrders
 * @property ProcurementRule[] $procurementRules
 * @property ProductPriceHistory[] $productPriceHistories
 * @property ProductPricelist[] $productPricelists
 * @property ProductPricelistItem[] $productPricelistItems
 * @property ProductSupplierinfo[] $productSupplierinfos
 * @property ProductTemplate[] $productTemplates
 * @property PurchaseConfigSettings[] $purchaseConfigSettings
 * @property PurchaseOrder[] $purchaseOrders
 * @property PurchaseOrderLine[] $purchaseOrderLines
 * @property ReportDecimoCuarto[] $reportDecimoCuartos
 * @property ReporteAmountByPartnerWizard[] $reporteAmountByPartnerWizards
 * @property ReporteCajaWizard[] $reporteCajaWizards
 * @property AccountAccount $propertyStockAccountInputCateg
 * @property AccountAccount $propertyStockValuationAccount
 * @property AccountAccount $transferAccount
 * @property AccountAccount $propertyStockAccountOutputCateg
 * @property AccountChartTemplate $chartTemplate
 * @property AccountJournal $currencyExchangeJournal
 * @property AccountJournal $taxCashBasisJournal
 * @property AccountMove $accountOpeningMove
 * @property ReportPaperformat $paperformat
 * @property ResCompany $parent
 * @property ResCompany[] $resCompanies
 * @property ResCurrency $currency
 * @property ResFont $font0
 * @property ResPartner $partner
 * @property ResUsers $writeU
 * @property ResUsers $createU
 * @property ResourceCalendar $resourceCalendar
 * @property StockLocation $internalTransitLocation
 * @property ResCompanyUsersRel[] $resCompanyUsersRels
 * @property ResUsers[] $users
 * @property ResConfigSettings[] $resConfigSettings
 * @property ResCurrencyRate[] $resCurrencyRates
 * @property ResPartner[] $resPartners
 * @property ResPartnerBank[] $resPartnerBanks
 * @property ResStore[] $resStores
 * @property ResUsers[] $resUsers
 * @property ResourceCalendar[] $resourceCalendars
 * @property ResourceCalendarLeaves[] $resourceCalendarLeaves
 * @property ResourceResource[] $resourceResources
 * @property ResourceTest[] $resourceTests
 * @property SaleConfigSettings[] $saleConfigSettings
 * @property SaleOrder[] $saleOrders
 * @property SaleOrderLine[] $saleOrderLines
 * @property StockConfigSettings[] $stockConfigSettings
 * @property StockInventory[] $stockInventories
 * @property StockInventoryLine[] $stockInventoryLines
 * @property StockLocation[] $stockLocations
 * @property StockLocationPath[] $stockLocationPaths
 * @property StockLocationRoute[] $stockLocationRoutes
 * @property StockMove[] $stockMoves
 * @property StockPicking[] $stockPickings
 * @property StockQuant[] $stockQuants
 * @property StockQuantPackage[] $stockQuantPackages
 * @property StockWarehouse[] $stockWarehouses
 * @property StockWarehouseOrderpoint[] $stockWarehouseOrderpoints
 * @property Website[] $websites
 * @property WizardMultiChartsAccounts[] $wizardMultiChartsAccounts
 */
class ResCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'res_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'partner_id', 'currency_id', 'fiscalyear_last_day', 'fiscalyear_last_month', 'po_lead', 'security_lead'], 'required'],
            [['name', 'rml_footer', 'rml_header', 'rml_paper_format', 'logo_web', 'account_no', 'email', 'phone', 'rml_header2', 'rml_header3', 'rml_header1', 'company_registry', 'bank_account_code_prefix', 'cash_account_code_prefix', 'overdue_msg', 'tax_calculation_rounding_method', 'accreditation', 'approval_authority', 'signature', 'sale_note', 'po_double_validation', 'po_lock', 'report_header', 'report_footer', 'external_report_layout', 'social_twitter', 'social_facebook', 'social_github', 'social_linkedin', 'social_youtube', 'social_googleplus', 'company_code'], 'string'],
            [['partner_id', 'currency_id', 'write_uid', 'font', 'parent_id', 'create_uid', 'paperformat_id', 'fiscalyear_last_day', 'property_stock_account_input_categ_id', 'property_stock_valuation_account_id', 'transfer_account_id', 'property_stock_account_output_categ_id', 'currency_exchange_journal_id', 'accounts_code_digits', 'chart_template_id', 'fiscalyear_last_month', 'propagation_minimum_delta', 'internal_transit_location_id', 'sequence', 'resource_calendar_id', 'tax_cash_basis_journal_id', 'account_opening_move_id', 'password_expiration', 'password_length', 'password_lower', 'password_upper', 'password_numeric', 'password_special', 'password_history', 'password_minimum'], 'default', 'value' => null],
            [['partner_id', 'currency_id', 'write_uid', 'font', 'parent_id', 'create_uid', 'paperformat_id', 'fiscalyear_last_day', 'property_stock_account_input_categ_id', 'property_stock_valuation_account_id', 'transfer_account_id', 'property_stock_account_output_categ_id', 'currency_exchange_journal_id', 'accounts_code_digits', 'chart_template_id', 'fiscalyear_last_month', 'propagation_minimum_delta', 'internal_transit_location_id', 'sequence', 'resource_calendar_id', 'tax_cash_basis_journal_id', 'account_opening_move_id', 'password_expiration', 'password_length', 'password_lower', 'password_upper', 'password_numeric', 'password_special', 'password_history', 'password_minimum'], 'integer'],
            [['create_date', 'write_date', 'fiscalyear_lock_date', 'period_lock_date', 'x_project_start_date'], 'safe'],
            [['custom_footer', 'anglo_saxon_accounting', 'expects_chart_of_accounts', 'tax_exigibility', 'account_setup_company_data_done', 'account_setup_bank_data_done', 'account_setup_fy_data_done', 'account_setup_coa_done', 'account_setup_bar_closed'], 'boolean'],
            [['po_lead', 'po_double_validation_amount', 'security_lead'], 'number'],
            [['paypal_account'], 'string', 'max' => 128],
            [['name'], 'unique'],
            [['property_stock_account_input_categ_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountAccount::className(), 'targetAttribute' => ['property_stock_account_input_categ_id' => 'id']],
            [['property_stock_valuation_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountAccount::className(), 'targetAttribute' => ['property_stock_valuation_account_id' => 'id']],
            [['transfer_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountAccount::className(), 'targetAttribute' => ['transfer_account_id' => 'id']],
            [['property_stock_account_output_categ_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountAccount::className(), 'targetAttribute' => ['property_stock_account_output_categ_id' => 'id']],
            [['chart_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountChartTemplate::className(), 'targetAttribute' => ['chart_template_id' => 'id']],
            [['currency_exchange_journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountJournal::className(), 'targetAttribute' => ['currency_exchange_journal_id' => 'id']],
            [['tax_cash_basis_journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountJournal::className(), 'targetAttribute' => ['tax_cash_basis_journal_id' => 'id']],
            [['account_opening_move_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountMove::className(), 'targetAttribute' => ['account_opening_move_id' => 'id']],
            [['paperformat_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReportPaperformat::className(), 'targetAttribute' => ['paperformat_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCompany::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCurrency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['font'], 'exist', 'skipOnError' => true, 'targetClass' => ResFont::className(), 'targetAttribute' => ['font' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['partner_id' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['resource_calendar_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResourceCalendar::className(), 'targetAttribute' => ['resource_calendar_id' => 'id']],
            [['internal_transit_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockLocation::className(), 'targetAttribute' => ['internal_transit_location_id' => 'id']],
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
            'partner_id' => 'Partner ID',
            'currency_id' => 'Currency ID',
            'rml_footer' => 'Rml Footer',
            'create_date' => 'Create Date',
            'rml_header' => 'Rml Header',
            'rml_paper_format' => 'Rml Paper Format',
            'write_uid' => 'Write Uid',
            'logo_web' => 'Logo Web',
            'font' => 'Font',
            'account_no' => 'Account No',
            'parent_id' => 'Parent ID',
            'email' => 'Email',
            'create_uid' => 'Create Uid',
            'custom_footer' => 'Custom Footer',
            'phone' => 'Phone',
            'rml_header2' => 'Rml Header2',
            'rml_header3' => 'Rml Header3',
            'write_date' => 'Write Date',
            'rml_header1' => 'Rml Header1',
            'company_registry' => 'Company Registry',
            'paperformat_id' => 'Paperformat ID',
            'fiscalyear_lock_date' => 'Fiscalyear Lock Date',
            'bank_account_code_prefix' => 'Bank Account Code Prefix',
            'cash_account_code_prefix' => 'Cash Account Code Prefix',
            'anglo_saxon_accounting' => 'Anglo Saxon Accounting',
            'fiscalyear_last_day' => 'Fiscalyear Last Day',
            'property_stock_account_input_categ_id' => 'Property Stock Account Input Categ ID',
            'property_stock_valuation_account_id' => 'Property Stock Valuation Account ID',
            'expects_chart_of_accounts' => 'Expects Chart Of Accounts',
            'transfer_account_id' => 'Transfer Account ID',
            'property_stock_account_output_categ_id' => 'Property Stock Account Output Categ ID',
            'currency_exchange_journal_id' => 'Currency Exchange Journal ID',
            'period_lock_date' => 'Period Lock Date',
            'paypal_account' => 'Paypal Account',
            'accounts_code_digits' => 'Accounts Code Digits',
            'chart_template_id' => 'Chart Template ID',
            'overdue_msg' => 'Overdue Msg',
            'fiscalyear_last_month' => 'Fiscalyear Last Month',
            'tax_calculation_rounding_method' => 'Tax Calculation Rounding Method',
            'accreditation' => 'Accreditation',
            'approval_authority' => 'Approval Authority',
            'signature' => 'Signature',
            'sale_note' => 'Sale Note',
            'propagation_minimum_delta' => 'Propagation Minimum Delta',
            'internal_transit_location_id' => 'Internal Transit Location ID',
            'po_lead' => 'Po Lead',
            'po_double_validation_amount' => 'Po Double Validation Amount',
            'po_double_validation' => 'Po Double Validation',
            'x_project_start_date' => 'X Project Start Date',
            'sequence' => 'Sequence',
            'po_lock' => 'Po Lock',
            'report_header' => 'Report Header',
            'report_footer' => 'Report Footer',
            'external_report_layout' => 'External Report Layout',
            'resource_calendar_id' => 'Resource Calendar ID',
            'tax_cash_basis_journal_id' => 'Tax Cash Basis Journal ID',
            'tax_exigibility' => 'Tax Exigibility',
            'account_opening_move_id' => 'Account Opening Move ID',
            'account_setup_company_data_done' => 'Account Setup Company Data Done',
            'account_setup_bank_data_done' => 'Account Setup Bank Data Done',
            'account_setup_fy_data_done' => 'Account Setup Fy Data Done',
            'account_setup_coa_done' => 'Account Setup Coa Done',
            'account_setup_bar_closed' => 'Account Setup Bar Closed',
            'social_twitter' => 'Social Twitter',
            'social_facebook' => 'Social Facebook',
            'social_github' => 'Social Github',
            'social_linkedin' => 'Social Linkedin',
            'social_youtube' => 'Social Youtube',
            'social_googleplus' => 'Social Googleplus',
            'company_code' => 'Company Code',
            'password_expiration' => 'Password Expiration',
            'password_length' => 'Password Length',
            'password_lower' => 'Password Lower',
            'password_upper' => 'Password Upper',
            'password_numeric' => 'Password Numeric',
            'password_special' => 'Password Special',
            'password_history' => 'Password History',
            'password_minimum' => 'Password Minimum',
            'security_lead' => 'Security Lead',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAccounts()
    {
        return $this->hasMany(AccountAccount::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAgedTrialBalances()
    {
        return $this->hasMany(AccountAgedTrialBalance::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAnalyticAccounts()
    {
        return $this->hasMany(AccountAnalyticAccount::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAnalyticLines()
    {
        return $this->hasMany(AccountAnalyticLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAssetAssets()
    {
        return $this->hasMany(AccountAssetAsset::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAssetCategories()
    {
        return $this->hasMany(AccountAssetCategory::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBalanceReports()
    {
        return $this->hasMany(AccountBalanceReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBankReconciliations()
    {
        return $this->hasMany(AccountBankReconciliation::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBankReconciliationLines()
    {
        return $this->hasMany(AccountBankReconciliationLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBankStatements()
    {
        return $this->hasMany(AccountBankStatement::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountBankStatementLines()
    {
        return $this->hasMany(AccountBankStatementLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountChartTemplates()
    {
        return $this->hasMany(AccountChartTemplate::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCommonAccountReports()
    {
        return $this->hasMany(AccountCommonAccountReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCommonJournalReports()
    {
        return $this->hasMany(AccountCommonJournalReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCommonPartnerReports()
    {
        return $this->hasMany(AccountCommonPartnerReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCommonReports()
    {
        return $this->hasMany(AccountCommonReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountConfigSettings()
    {
        return $this->hasMany(AccountConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountDirectPaymentLines()
    {
        return $this->hasMany(AccountDirectPaymentLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFinancialYearOps()
    {
        return $this->hasMany(AccountFinancialYearOp::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFiscalPositions()
    {
        return $this->hasMany(AccountFiscalPosition::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoices()
    {
        return $this->hasMany(AccountInvoice::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoiceLines()
    {
        return $this->hasMany(AccountInvoiceLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoiceTaxes()
    {
        return $this->hasMany(AccountInvoiceTax::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountJournals()
    {
        return $this->hasMany(AccountJournal::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountMoves()
    {
        return $this->hasMany(AccountMove::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountMoveLines()
    {
        return $this->hasMany(AccountMoveLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountMoveLineReconciles()
    {
        return $this->hasMany(AccountMoveLineReconcile::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountMoveTemplates()
    {
        return $this->hasMany(AccountMoveTemplate::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountOpenings()
    {
        return $this->hasMany(AccountOpening::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPartialReconciles()
    {
        return $this->hasMany(AccountPartialReconcile::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPayments()
    {
        return $this->hasMany(AccountPayment::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPaymentLines()
    {
        return $this->hasMany(AccountPaymentLine::className(), ['invoice_company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPaymentTerms()
    {
        return $this->hasMany(AccountPaymentTerm::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPrintJournals()
    {
        return $this->hasMany(AccountPrintJournal::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountReconcileModels()
    {
        return $this->hasMany(AccountReconcileModel::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountReportGeneralLedgers()
    {
        return $this->hasMany(AccountReportGeneralLedger::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountReportPartnerLedgers()
    {
        return $this->hasMany(AccountReportPartnerLedger::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountTaxes()
    {
        return $this->hasMany(AccountTax::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountTaxReports()
    {
        return $this->hasMany(AccountTaxReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountTaxTemplates()
    {
        return $this->hasMany(AccountTaxTemplate::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountVouchers()
    {
        return $this->hasMany(AccountVoucher::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountVoucherLines()
    {
        return $this->hasMany(AccountVoucherLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountingReports()
    {
        return $this->hasMany(AccountingReport::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtsPeriods()
    {
        return $this->hasMany(AtsPeriod::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseConfigSettings()
    {
        return $this->hasMany(BaseConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashmanagementConfigSettings()
    {
        return $this->hasMany(CashmanagementConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostCentres()
    {
        return $this->hasMany(CostCentre::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostCentreMoves()
    {
        return $this->hasMany(CostCentreMove::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostCentreUplevs()
    {
        return $this->hasMany(CostCentreUplev::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrmTeams()
    {
        return $this->hasMany(CrmTeam::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDateRanges()
    {
        return $this->hasMany(DateRange::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDateRangeGenerators()
    {
        return $this->hasMany(DateRangeGenerator::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDateRangeTypes()
    {
        return $this->hasMany(DateRangeType::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElectronicConfigurations()
    {
        return $this->hasMany(ElectronicConfiguration::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiscalAuthorizationRanks()
    {
        return $this->hasMany(FiscalAuthorizationRank::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrContracts()
    {
        return $this->hasMany(HrContract::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrContributionRegisters()
    {
        return $this->hasMany(HrContributionRegister::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrD3erSocialBenefits()
    {
        return $this->hasMany(HrD3erSocialBenefit::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrD4thSocialBenefits()
    {
        return $this->hasMany(HrD4thSocialBenefit::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasMany(HrDepartment::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrHolidaysStatuses()
    {
        return $this->hasMany(HrHolidaysStatus::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrJobs()
    {
        return $this->hasMany(HrJob::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPayrollStructures()
    {
        return $this->hasMany(HrPayrollStructure::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPayslips()
    {
        return $this->hasMany(HrPayslip::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPayslipLines()
    {
        return $this->hasMany(HrPayslipLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrRdeps()
    {
        return $this->hasMany(HrRdep::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrRdepTagConfigs()
    {
        return $this->hasMany(HrRdepTagConfig::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrSalaryRules()
    {
        return $this->hasMany(HrSalaryRule::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrSalaryRuleCategories()
    {
        return $this->hasMany(HrSalaryRuleCategory::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHummingbirdConfig()
    {
        return $this->hasOne(HummingbirdConfig::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIapAccounts()
    {
        return $this->hasMany(IapAccount::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceElectronicReceiveds()
    {
        return $this->hasMany(InvoiceElectronicReceived::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIrAttachments()
    {
        return $this->hasMany(IrAttachment::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIrDefaults()
    {
        return $this->hasMany(IrDefault::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIrProperties()
    {
        return $this->hasMany(IrProperty::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIrSequences()
    {
        return $this->hasMany(IrSequence::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIrValues()
    {
        return $this->hasMany(IrValues::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExtraValues()
    {
        return $this->hasMany(OpExtraValues::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentAcquirers()
    {
        return $this->hasMany(PaymentAcquirer::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrepaymentTypes()
    {
        return $this->hasMany(PrepaymentType::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcurementOrders()
    {
        return $this->hasMany(ProcurementOrder::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcurementRules()
    {
        return $this->hasMany(ProcurementRule::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceHistories()
    {
        return $this->hasMany(ProductPriceHistory::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPricelists()
    {
        return $this->hasMany(ProductPricelist::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPricelistItems()
    {
        return $this->hasMany(ProductPricelistItem::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSupplierinfos()
    {
        return $this->hasMany(ProductSupplierinfo::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTemplates()
    {
        return $this->hasMany(ProductTemplate::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseConfigSettings()
    {
        return $this->hasMany(PurchaseConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderLines()
    {
        return $this->hasMany(PurchaseOrderLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportDecimoCuartos()
    {
        return $this->hasMany(ReportDecimoCuarto::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReporteAmountByPartnerWizards()
    {
        return $this->hasMany(ReporteAmountByPartnerWizard::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReporteCajaWizards()
    {
        return $this->hasMany(ReporteCajaWizard::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyStockAccountInputCateg()
    {
        return $this->hasOne(AccountAccount::className(), ['id' => 'property_stock_account_input_categ_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyStockValuationAccount()
    {
        return $this->hasOne(AccountAccount::className(), ['id' => 'property_stock_valuation_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransferAccount()
    {
        return $this->hasOne(AccountAccount::className(), ['id' => 'transfer_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropertyStockAccountOutputCateg()
    {
        return $this->hasOne(AccountAccount::className(), ['id' => 'property_stock_account_output_categ_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChartTemplate()
    {
        return $this->hasOne(AccountChartTemplate::className(), ['id' => 'chart_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyExchangeJournal()
    {
        return $this->hasOne(AccountJournal::className(), ['id' => 'currency_exchange_journal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxCashBasisJournal()
    {
        return $this->hasOne(AccountJournal::className(), ['id' => 'tax_cash_basis_journal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountOpeningMove()
    {
        return $this->hasOne(AccountMove::className(), ['id' => 'account_opening_move_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaperformat()
    {
        return $this->hasOne(ReportPaperformat::className(), ['id' => 'paperformat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ResCompany::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResCompanies()
    {
        return $this->hasMany(ResCompany::className(), ['parent_id' => 'id']);
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
    public function getFont0()
    {
        return $this->hasOne(ResFont::className(), ['id' => 'font']);
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
    public function getResourceCalendar()
    {
        return $this->hasOne(ResourceCalendar::className(), ['id' => 'resource_calendar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInternalTransitLocation()
    {
        return $this->hasOne(StockLocation::className(), ['id' => 'internal_transit_location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResCompanyUsersRels()
    {
        return $this->hasMany(ResCompanyUsersRel::className(), ['cid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(ResUsers::className(), ['id' => 'user_id'])->viaTable('res_company_users_rel', ['cid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResConfigSettings()
    {
        return $this->hasMany(ResConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResCurrencyRates()
    {
        return $this->hasMany(ResCurrencyRate::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartners()
    {
        return $this->hasMany(ResPartner::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartnerBanks()
    {
        return $this->hasMany(ResPartnerBank::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResStores()
    {
        return $this->hasMany(ResStore::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResUsers()
    {
        return $this->hasMany(ResUsers::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceCalendars()
    {
        return $this->hasMany(ResourceCalendar::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceCalendarLeaves()
    {
        return $this->hasMany(ResourceCalendarLeaves::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceResources()
    {
        return $this->hasMany(ResourceResource::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceTests()
    {
        return $this->hasMany(ResourceTest::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleConfigSettings()
    {
        return $this->hasMany(SaleConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrders()
    {
        return $this->hasMany(SaleOrder::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleOrderLines()
    {
        return $this->hasMany(SaleOrderLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockConfigSettings()
    {
        return $this->hasMany(StockConfigSettings::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockInventories()
    {
        return $this->hasMany(StockInventory::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockInventoryLines()
    {
        return $this->hasMany(StockInventoryLine::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockLocations()
    {
        return $this->hasMany(StockLocation::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockLocationPaths()
    {
        return $this->hasMany(StockLocationPath::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockLocationRoutes()
    {
        return $this->hasMany(StockLocationRoute::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockMoves()
    {
        return $this->hasMany(StockMove::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockPickings()
    {
        return $this->hasMany(StockPicking::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockQuants()
    {
        return $this->hasMany(StockQuant::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockQuantPackages()
    {
        return $this->hasMany(StockQuantPackage::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockWarehouses()
    {
        return $this->hasMany(StockWarehouse::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockWarehouseOrderpoints()
    {
        return $this->hasMany(StockWarehouseOrderpoint::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebsites()
    {
        return $this->hasMany(Website::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizardMultiChartsAccounts()
    {
        return $this->hasMany(WizardMultiChartsAccounts::className(), ['company_id' => 'id']);
    }
}
