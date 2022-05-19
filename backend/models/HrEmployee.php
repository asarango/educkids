<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "hr_employee".
 *
 * @property int $id
 * @property int $address_id Working Address
 * @property string $create_date Created on
 * @property int $coach_id Coach
 * @property int $resource_id Resource
 * @property int $color Color Index
 * @property string $message_last_post Last Message Date
 * @property string $marital Marital Status
 * @property string $identification_id Identification No
 * @property int $bank_account_id Bank Account Number
 * @property int $job_id Job Title
 * @property string $work_phone Work Phone
 * @property int $country_id Nationality (Country)
 * @property int $parent_id Manager
 * @property int $department_id Department
 * @property string $mobile_phone Work Mobile
 * @property int $create_uid Created by
 * @property string $birthday Date of Birth
 * @property string $write_date Last Updated on
 * @property string $sinid SIN No
 * @property int $write_uid Last Updated by
 * @property string $work_email Work Email
 * @property string $work_location Work Location
 * @property string $gender Gender
 * @property string $ssnid SSN No
 * @property int $address_home_id Home Address
 * @property string $passport_id Passport No
 * @property string $name_related Name
 * @property string $notes Notes
 * @property bool $manager Is a Manager
 * @property int $children Number of Children
 * @property string $medic_exam Medical Examination Date
 * @property string $vehicle Company Vehicle
 * @property string $place_of_birth Place of Birth
 * @property int $vehicle_distance Home-Work Dist.
 * @property string $x_codigo_barra Código de barra
 * @property bool $x_trabaja_conyuge Trabaja
 * @property string $x_lang_read Nivel lectura
 * @property string $x_out_date Fecha de salida
 * @property int $x_work_time2_id Horario laboral (secundario)
 * @property string $x_last_institution Última institución
 * @property string $x_nivel_instruccion Nivel instrucción
 * @property string $x_id_iess Cédula IESS
 * @property string $x_fondo_reserva Fondo Reserva
 * @property int $x_city_id Ciudad
 * @property bool $x_discapacidad Discapacidad
 * @property int $x_nationality_id Nacionalidad
 * @property string $x_zone Zona
 * @property string $x_reentry_date Fecha de reingreso
 * @property double $x_eficiency_bonus Bono eficiencia
 * @property int $x_account_analytic_id Cuenta analítica
 * @property string $x_dir_conyuge Dirección
 * @property int $x_child_num Número hijos
 * @property double $x_porcentaje_discapacidad Porcentaje
 * @property string $x_parentesco Parentesco
 * @property string $x_entry_date Fecha de ingreso
 * @property bool $x_vote_card Papeleta de votación
 * @property int $x_contract_type_id Tipo contrato
 * @property string $x_ethnic_def Definición étnica
 * @property string $x_celular_conyuge Celular
 * @property int $x_work_time_id Horario laboral
 * @property string $x_blood_group Grupo sanguíneo
 * @property string $x_telef_dom Teléfono
 * @property string $x_maternity_date_end Maternidad fin
 * @property double $x_alimentation Alimentación
 * @property string $x_lang_speak Nivel conversación
 * @property int $x_worked_days Días trabajados
 * @property string $x_tiempo_perentorio Tiempo perentorio
 * @property string $x_lang_write Nivel escritura
 * @property string $x_experiencia Experiencia laboral
 * @property string $x_dir_type Tipo dirección
 * @property int $x_department_parent_id Área
 * @property string $x_calle_dom2 Calle secundaria
 * @property string $x_celular_dom Celular
 * @property string $x_instruccion Instrucción
 * @property string $x_maternity_date_ini Maternidad inicio
 * @property string $x_ident_type Tipo identificación
 * @property bool $x_foreign_institution Institución extranjera
 * @property int $x_state_id Provincia
 * @property int $x_institucion_id Graduado en
 * @property string $x_calle_dom Calle
 * @property bool $x_special_capacities Capacidades especiales
 * @property int $x_institute_id Instituto
 * @property string $x_decimo_cuarto Décimo cuarto
 * @property int $x_child_num_m Varones
 * @property int $x_child_num_f Mujeres
 * @property int $x_parish_id Parroquia
 * @property string $x_correo_personal Correo personal
 * @property int $x_country_title_id País título
 * @property double $x_initial_salary Salario inicial
 * @property string $x_escala_salarial Escala salarial
 * @property string $x_home_number No. casa
 * @property string $x_study_state Estado de estudios
 * @property string $x_work_time2 Horario laboral (secundario)
 * @property string $x_work_time3 Horario laboral (sábado)
 * @property string $x_nombre_completo Nombre(s)
 * @property string $x_nombre_conyuge Nombre
 * @property string $x_apellidos Apellidos
 * @property string $x_title_date Fecha título
 * @property string $x_decimo_tercero Décimo tercero
 * @property int $x_work_time3_id Horario laboral (sábado)
 * @property bool $x_foreign Extranjero
 * @property string $x_birth_place Lugar de nacimiento
 * @property string $x_work_time Horario laboral
 * @property string $x_telefono_conyuge Teléfono
 * @property string $x_lactation_date_end Lactancia fin
 * @property double $x_actual_salary Salario actual
 * @property string $x_certificado_idioma Certificado idiomas
 * @property int $x_edad Edad
 * @property bool $x_recorrido Recorrido
 * @property string $x_regimen Régimen
 * @property string $x_fecha_nacimiento_conyuge Fecha nacimiento
 * @property int $x_sector_id Sector
 * @property string $x_lugar_trabajo_conyuge Lugar de trabajo
 * @property string $x_sector_dom Sector
 * @property string $x_correo_dom Correo laboral
 * @property string $x_idioma_id Idioma
 * @property string $x_sectorial_activity Actividad sectorial
 * @property string $x_estado Estado laboral
 * @property string $x_change_salary_date Fecha cambio de sueldo
 * @property string $x_lactation_date_ini Lactancia inicio
 * @property string $x_tipo_discapacidad Tipo de discapacidad
 * @property int $x_store_id Sucursal
 * @property string $x_disability_condition Condición respecto a discapacidad
 * @property string $x_identification Número identificación
 * @property int $x_residence_country_id País residencia
 * @property string $x_apply_agreement Aplica convenio
 * @property string $x_galapagos_benefit Beneficio Galápagos
 * @property string $x_residence_type Tipo residencia
 * @property string $x_net_salary_system Sistema de salario neto
 * @property string $x_identification_type Tipo identificación
 * @property string $x_catastrophic_disease Enfermedad catastrófica
 * @property bool $appraisal_by_colleagues Colleagues
 * @property int $appraisal_manager_survey_id Manager's Appraisal
 * @property bool $periodic_appraisal Periodic Appraisal
 * @property bool $periodic_appraisal_created Periodic Appraisal has been created
 * @property int $appraisal_frequency Repeat Every
 * @property string $appraisal_employee Employee Name
 * @property bool $appraisal_by_collaborators Collaborators
 * @property int $appraisal_self_survey_id Self Appraisal
 * @property bool $appraisal_by_manager Managers
 * @property int $appraisal_collaborators_survey_id collaborate's Appraisal
 * @property string $appraisal_date Next Appraisal Date
 * @property bool $appraisal_self Employee
 * @property int $appraisal_colleagues_survey_id Employee's Appraisal
 * @property string $appraisal_frequency_unit Repeat Every
 * @property string $first_name First Name
 * @property string $middle_name Middle Name
 * @property string $first_surname First Surname
 * @property string $second_surname Second Surname
 *
 * @property AccountAssetAsset[] $accountAssetAssets
 * @property AccountAssetChangeResponsibleWizard[] $accountAssetChangeResponsibleWizards
 * @property AppraisalColleaguesRel[] $appraisalColleaguesRels
 * @property HrAppraisal[] $hrAppraisals
 * @property AppraisalManagerRel[] $appraisalManagerRels
 * @property HrAppraisal[] $hrAppraisals0
 * @property AppraisalSubordinatesRel[] $appraisalSubordinatesRels
 * @property HrAppraisal[] $hrAppraisals1
 * @property EmpAppraisalColleaguesRel[] $empAppraisalColleaguesRels
 * @property EmpAppraisalColleaguesRel[] $empAppraisalColleaguesRels0
 * @property HrEmployee[] $hrEmployees
 * @property HrEmployee[] $hrAppraisals2
 * @property EmpAppraisalManagerRel[] $empAppraisalManagerRels
 * @property EmpAppraisalManagerRel[] $empAppraisalManagerRels0
 * @property HrEmployee[] $hrEmployees0
 * @property HrEmployee[] $hrAppraisals3
 * @property EmpAppraisalSubordinatesRel[] $empAppraisalSubordinatesRels
 * @property EmpAppraisalSubordinatesRel[] $empAppraisalSubordinatesRels0
 * @property HrEmployee[] $hrEmployees1
 * @property HrEmployee[] $hrAppraisals4
 * @property EmployeeCategoryRel[] $employeeCategoryRels
 * @property HrEmployeeCategory[] $categories
 * @property EmployeeTitleRel[] $employeeTitleRels
 * @property HrEmployeeTitle[] $titles
 * @property HrApplicant[] $hrApplicants
 * @property HrAppraisal[] $hrAppraisals5
 * @property HrAttendance[] $hrAttendances
 * @property HrContract[] $hrContracts
 * @property HrDepartment[] $hrDepartments
 * @property AccountAnalyticAccount $xAccountAnalytic
 * @property HrDepartment $department
 * @property HrDepartment $xDepartmentParent
 * @property HrEmployee $coach
 * @property HrEmployee[] $hrEmployees2
 * @property HrEmployee $parent
 * @property HrEmployee[] $hrEmployees3
 * @property HrEmployeeContract $xContractType
 * @property HrEmployeeInstitute $xInstitucion
 * @property HrEmployeeWorktime $xWorkTime2
 * @property HrEmployeeWorktime $xWorkTime
 * @property HrEmployeeWorktime $xWorkTime3
 * @property HrJob $job
 * @property OpInstitute $xInstitute
 * @property ResCountry $country
 * @property ResCountry $xCountryTitle
 * @property ResCountry $xResidenceCountry
 * @property ResCountryCity $xCity
 * @property ResCountryNationality $xNationality
 * @property ResCountryParish $xParish
 * @property ResCountrySector $xSector
 * @property ResCountryState $xState
 * @property ResPartner $address
 * @property ResPartner $addressHome
 * @property ResPartnerBank $bankAccount
 * @property ResStore $xStore
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property ResourceResource $resource
 * @property SurveySurvey $appraisalManagerSurvey
 * @property SurveySurvey $appraisalSelfSurvey
 * @property SurveySurvey $appraisalCollaboratorsSurvey
 * @property SurveySurvey $appraisalColleaguesSurvey
 * @property HrEmployeeChildren[] $hrEmployeeChildrens
 * @property HrEmployeeExpenses[] $hrEmployeeExpenses
 * @property HrEmployeeExpensesLines[] $hrEmployeeExpensesLines
 * @property HrEmployeeGroupRel[] $hrEmployeeGroupRels
 * @property HrPayslipEmployees[] $payslips
 * @property HrEmployeeInitialBalance[] $hrEmployeeInitialBalances
 * @property HrEmployeeTraining[] $hrEmployeeTrainings
 * @property HrEquipment[] $hrEquipments
 * @property HrEquipmentRequest[] $hrEquipmentRequests
 * @property HrExpense[] $hrExpenses
 * @property HrHolidays[] $hrHolidays
 * @property HrHolidays[] $hrHolidays0
 * @property HrHolidays[] $hrHolidays1
 * @property HrInductionCheckList[] $hrInductionCheckLists
 * @property HrInductionCheckListDocument[] $documents
 * @property HrJob[] $hrJobs
 * @property HrLoan[] $hrLoans
 * @property HrLoan[] $hrLoans0
 * @property HrLoanLine[] $hrLoanLines
 * @property HrPayslip[] $hrPayslips
 * @property HrPayslipLine[] $hrPayslipLines
 * @property HrVariableDiscount[] $hrVariableDiscounts
 * @property HrVariableIncome[] $hrVariableIncomes
 * @property OpFaculty[] $opFaculties
 * @property OpPsychologicalAttention[] $opPsychologicalAttentions
 * @property OpPsychologicalCasesAttended[] $opPsychologicalCasesAttendeds
 * @property SummaryEmpRel[] $summaryEmpRels
 * @property HrHolidaysSummaryEmployee[] $sums
 */
class HrEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address_id', 'coach_id', 'resource_id', 'color', 'bank_account_id', 'job_id', 'country_id', 'parent_id', 'department_id', 'create_uid', 'write_uid', 'address_home_id', 'children', 'vehicle_distance', 'x_work_time2_id', 'x_city_id', 'x_nationality_id', 'x_account_analytic_id', 'x_child_num', 'x_contract_type_id', 'x_work_time_id', 'x_worked_days', 'x_department_parent_id', 'x_state_id', 'x_institucion_id', 'x_institute_id', 'x_child_num_m', 'x_child_num_f', 'x_parish_id', 'x_country_title_id', 'x_work_time3_id', 'x_edad', 'x_sector_id', 'x_store_id', 'x_residence_country_id', 'appraisal_manager_survey_id', 'appraisal_frequency', 'appraisal_self_survey_id', 'appraisal_collaborators_survey_id', 'appraisal_colleagues_survey_id'], 'default', 'value' => null],
            [['address_id', 'coach_id', 'resource_id', 'color', 'bank_account_id', 'job_id', 'country_id', 'parent_id', 'department_id', 'create_uid', 'write_uid', 'address_home_id', 'children', 'vehicle_distance', 'x_work_time2_id', 'x_city_id', 'x_nationality_id', 'x_account_analytic_id', 'x_child_num', 'x_contract_type_id', 'x_work_time_id', 'x_worked_days', 'x_department_parent_id', 'x_state_id', 'x_institucion_id', 'x_institute_id', 'x_child_num_m', 'x_child_num_f', 'x_parish_id', 'x_country_title_id', 'x_work_time3_id', 'x_edad', 'x_sector_id', 'x_store_id', 'x_residence_country_id', 'appraisal_manager_survey_id', 'appraisal_frequency', 'appraisal_self_survey_id', 'appraisal_collaborators_survey_id', 'appraisal_colleagues_survey_id'], 'integer'],
            [['create_date', 'message_last_post', 'birthday', 'write_date', 'medic_exam', 'x_out_date', 'x_reentry_date', 'x_entry_date', 'x_maternity_date_end', 'x_tiempo_perentorio', 'x_maternity_date_ini', 'x_title_date', 'x_lactation_date_end', 'x_fecha_nacimiento_conyuge', 'x_change_salary_date', 'x_lactation_date_ini', 'appraisal_date'], 'safe'],
            [['resource_id'], 'required'],
            [['marital', 'identification_id', 'work_phone', 'mobile_phone', 'sinid', 'work_location', 'gender', 'ssnid', 'passport_id', 'name_related', 'notes', 'vehicle', 'place_of_birth', 'x_codigo_barra', 'x_lang_read', 'x_last_institution', 'x_nivel_instruccion', 'x_id_iess', 'x_fondo_reserva', 'x_zone', 'x_dir_conyuge', 'x_parentesco', 'x_ethnic_def', 'x_celular_conyuge', 'x_blood_group', 'x_telef_dom', 'x_lang_speak', 'x_lang_write', 'x_experiencia', 'x_dir_type', 'x_calle_dom2', 'x_celular_dom', 'x_instruccion', 'x_ident_type', 'x_calle_dom', 'x_decimo_cuarto', 'x_correo_personal', 'x_escala_salarial', 'x_home_number', 'x_study_state', 'x_work_time2', 'x_work_time3', 'x_nombre_completo', 'x_nombre_conyuge', 'x_apellidos', 'x_decimo_tercero', 'x_birth_place', 'x_work_time', 'x_telefono_conyuge', 'x_certificado_idioma', 'x_regimen', 'x_lugar_trabajo_conyuge', 'x_sector_dom', 'x_correo_dom', 'x_idioma_id', 'x_sectorial_activity', 'x_estado', 'x_tipo_discapacidad', 'x_disability_condition', 'x_identification', 'x_apply_agreement', 'x_galapagos_benefit', 'x_residence_type', 'x_net_salary_system', 'x_identification_type', 'x_catastrophic_disease', 'appraisal_employee', 'appraisal_frequency_unit'], 'string'],
            [['manager', 'x_trabaja_conyuge', 'x_discapacidad', 'x_vote_card', 'x_foreign_institution', 'x_special_capacities', 'x_foreign', 'x_recorrido', 'appraisal_by_colleagues', 'periodic_appraisal', 'periodic_appraisal_created', 'appraisal_by_collaborators', 'appraisal_by_manager', 'appraisal_self'], 'boolean'],
            [['x_eficiency_bonus', 'x_porcentaje_discapacidad', 'x_alimentation', 'x_initial_salary', 'x_actual_salary'], 'number'],
            [['work_email'], 'string', 'max' => 240],
            [['first_name', 'middle_name', 'first_surname', 'second_surname'], 'string', 'max' => 40],
            [['work_email'], 'unique'],
            [['x_account_analytic_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountAnalyticAccount::className(), 'targetAttribute' => ['x_account_analytic_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
            [['x_department_parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartment::className(), 'targetAttribute' => ['x_department_parent_id' => 'id']],
            [['coach_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['coach_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['x_contract_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployeeContract::className(), 'targetAttribute' => ['x_contract_type_id' => 'id']],
            [['x_institucion_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployeeInstitute::className(), 'targetAttribute' => ['x_institucion_id' => 'id']],
            [['x_work_time2_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployeeWorktime::className(), 'targetAttribute' => ['x_work_time2_id' => 'id']],
            [['x_work_time_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployeeWorktime::className(), 'targetAttribute' => ['x_work_time_id' => 'id']],
            [['x_work_time3_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployeeWorktime::className(), 'targetAttribute' => ['x_work_time3_id' => 'id']],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrJob::className(), 'targetAttribute' => ['job_id' => 'id']],
            [['x_institute_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['x_institute_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['x_country_title_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['x_country_title_id' => 'id']],
            [['x_residence_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['x_residence_country_id' => 'id']],
            [['x_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryCity::className(), 'targetAttribute' => ['x_city_id' => 'id']],
            [['x_nationality_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryNationality::className(), 'targetAttribute' => ['x_nationality_id' => 'id']],
            [['x_parish_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryParish::className(), 'targetAttribute' => ['x_parish_id' => 'id']],
            [['x_sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountrySector::className(), 'targetAttribute' => ['x_sector_id' => 'id']],
            [['x_state_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryState::className(), 'targetAttribute' => ['x_state_id' => 'id']],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['address_home_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['address_home_id' => 'id']],
            [['bank_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartnerBank::className(), 'targetAttribute' => ['bank_account_id' => 'id']],
            [['x_store_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResStore::className(), 'targetAttribute' => ['x_store_id' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['resource_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResourceResource::className(), 'targetAttribute' => ['resource_id' => 'id']],
            [['appraisal_manager_survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveySurvey::className(), 'targetAttribute' => ['appraisal_manager_survey_id' => 'id']],
            [['appraisal_self_survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveySurvey::className(), 'targetAttribute' => ['appraisal_self_survey_id' => 'id']],
            [['appraisal_collaborators_survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveySurvey::className(), 'targetAttribute' => ['appraisal_collaborators_survey_id' => 'id']],
            [['appraisal_colleagues_survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveySurvey::className(), 'targetAttribute' => ['appraisal_colleagues_survey_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => 'Address ID',
            'create_date' => 'Create Date',
            'coach_id' => 'Coach ID',
            'resource_id' => 'Resource ID',
            'color' => 'Color',
            'message_last_post' => 'Message Last Post',
            'marital' => 'Marital',
            'identification_id' => 'Identification ID',
            'bank_account_id' => 'Bank Account ID',
            'job_id' => 'Job ID',
            'work_phone' => 'Work Phone',
            'country_id' => 'Country ID',
            'parent_id' => 'Parent ID',
            'department_id' => 'Department ID',
            'mobile_phone' => 'Mobile Phone',
            'create_uid' => 'Create Uid',
            'birthday' => 'Birthday',
            'write_date' => 'Write Date',
            'sinid' => 'Sinid',
            'write_uid' => 'Write Uid',
            'work_email' => 'Work Email',
            'work_location' => 'Work Location',
            'gender' => 'Gender',
            'ssnid' => 'Ssnid',
            'address_home_id' => 'Address Home ID',
            'passport_id' => 'Passport ID',
            'name_related' => 'Name Related',
            'notes' => 'Notes',
            'manager' => 'Manager',
            'children' => 'Children',
            'medic_exam' => 'Medic Exam',
            'vehicle' => 'Vehicle',
            'place_of_birth' => 'Place Of Birth',
            'vehicle_distance' => 'Vehicle Distance',
            'x_codigo_barra' => 'X Codigo Barra',
            'x_trabaja_conyuge' => 'X Trabaja Conyuge',
            'x_lang_read' => 'X Lang Read',
            'x_out_date' => 'X Out Date',
            'x_work_time2_id' => 'X Work Time2 ID',
            'x_last_institution' => 'X Last Institution',
            'x_nivel_instruccion' => 'X Nivel Instruccion',
            'x_id_iess' => 'X Id Iess',
            'x_fondo_reserva' => 'X Fondo Reserva',
            'x_city_id' => 'X City ID',
            'x_discapacidad' => 'X Discapacidad',
            'x_nationality_id' => 'X Nationality ID',
            'x_zone' => 'X Zone',
            'x_reentry_date' => 'X Reentry Date',
            'x_eficiency_bonus' => 'X Eficiency Bonus',
            'x_account_analytic_id' => 'X Account Analytic ID',
            'x_dir_conyuge' => 'X Dir Conyuge',
            'x_child_num' => 'X Child Num',
            'x_porcentaje_discapacidad' => 'X Porcentaje Discapacidad',
            'x_parentesco' => 'X Parentesco',
            'x_entry_date' => 'X Entry Date',
            'x_vote_card' => 'X Vote Card',
            'x_contract_type_id' => 'X Contract Type ID',
            'x_ethnic_def' => 'X Ethnic Def',
            'x_celular_conyuge' => 'X Celular Conyuge',
            'x_work_time_id' => 'X Work Time ID',
            'x_blood_group' => 'X Blood Group',
            'x_telef_dom' => 'X Telef Dom',
            'x_maternity_date_end' => 'X Maternity Date End',
            'x_alimentation' => 'X Alimentation',
            'x_lang_speak' => 'X Lang Speak',
            'x_worked_days' => 'X Worked Days',
            'x_tiempo_perentorio' => 'X Tiempo Perentorio',
            'x_lang_write' => 'X Lang Write',
            'x_experiencia' => 'X Experiencia',
            'x_dir_type' => 'X Dir Type',
            'x_department_parent_id' => 'X Department Parent ID',
            'x_calle_dom2' => 'X Calle Dom2',
            'x_celular_dom' => 'X Celular Dom',
            'x_instruccion' => 'X Instruccion',
            'x_maternity_date_ini' => 'X Maternity Date Ini',
            'x_ident_type' => 'X Ident Type',
            'x_foreign_institution' => 'X Foreign Institution',
            'x_state_id' => 'X State ID',
            'x_institucion_id' => 'X Institucion ID',
            'x_calle_dom' => 'X Calle Dom',
            'x_special_capacities' => 'X Special Capacities',
            'x_institute_id' => 'X Institute ID',
            'x_decimo_cuarto' => 'X Decimo Cuarto',
            'x_child_num_m' => 'X Child Num M',
            'x_child_num_f' => 'X Child Num F',
            'x_parish_id' => 'X Parish ID',
            'x_correo_personal' => 'X Correo Personal',
            'x_country_title_id' => 'X Country Title ID',
            'x_initial_salary' => 'X Initial Salary',
            'x_escala_salarial' => 'X Escala Salarial',
            'x_home_number' => 'X Home Number',
            'x_study_state' => 'X Study State',
            'x_work_time2' => 'X Work Time2',
            'x_work_time3' => 'X Work Time3',
            'x_nombre_completo' => 'X Nombre Completo',
            'x_nombre_conyuge' => 'X Nombre Conyuge',
            'x_apellidos' => 'X Apellidos',
            'x_title_date' => 'X Title Date',
            'x_decimo_tercero' => 'X Decimo Tercero',
            'x_work_time3_id' => 'X Work Time3 ID',
            'x_foreign' => 'X Foreign',
            'x_birth_place' => 'X Birth Place',
            'x_work_time' => 'X Work Time',
            'x_telefono_conyuge' => 'X Telefono Conyuge',
            'x_lactation_date_end' => 'X Lactation Date End',
            'x_actual_salary' => 'X Actual Salary',
            'x_certificado_idioma' => 'X Certificado Idioma',
            'x_edad' => 'X Edad',
            'x_recorrido' => 'X Recorrido',
            'x_regimen' => 'X Regimen',
            'x_fecha_nacimiento_conyuge' => 'X Fecha Nacimiento Conyuge',
            'x_sector_id' => 'X Sector ID',
            'x_lugar_trabajo_conyuge' => 'X Lugar Trabajo Conyuge',
            'x_sector_dom' => 'X Sector Dom',
            'x_correo_dom' => 'X Correo Dom',
            'x_idioma_id' => 'X Idioma ID',
            'x_sectorial_activity' => 'X Sectorial Activity',
            'x_estado' => 'X Estado',
            'x_change_salary_date' => 'X Change Salary Date',
            'x_lactation_date_ini' => 'X Lactation Date Ini',
            'x_tipo_discapacidad' => 'X Tipo Discapacidad',
            'x_store_id' => 'X Store ID',
            'x_disability_condition' => 'X Disability Condition',
            'x_identification' => 'X Identification',
            'x_residence_country_id' => 'X Residence Country ID',
            'x_apply_agreement' => 'X Apply Agreement',
            'x_galapagos_benefit' => 'X Galapagos Benefit',
            'x_residence_type' => 'X Residence Type',
            'x_net_salary_system' => 'X Net Salary System',
            'x_identification_type' => 'X Identification Type',
            'x_catastrophic_disease' => 'X Catastrophic Disease',
            'appraisal_by_colleagues' => 'Appraisal By Colleagues',
            'appraisal_manager_survey_id' => 'Appraisal Manager Survey ID',
            'periodic_appraisal' => 'Periodic Appraisal',
            'periodic_appraisal_created' => 'Periodic Appraisal Created',
            'appraisal_frequency' => 'Appraisal Frequency',
            'appraisal_employee' => 'Appraisal Employee',
            'appraisal_by_collaborators' => 'Appraisal By Collaborators',
            'appraisal_self_survey_id' => 'Appraisal Self Survey ID',
            'appraisal_by_manager' => 'Appraisal By Manager',
            'appraisal_collaborators_survey_id' => 'Appraisal Collaborators Survey ID',
            'appraisal_date' => 'Appraisal Date',
            'appraisal_self' => 'Appraisal Self',
            'appraisal_colleagues_survey_id' => 'Appraisal Colleagues Survey ID',
            'appraisal_frequency_unit' => 'Appraisal Frequency Unit',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'first_surname' => 'First Surname',
            'second_surname' => 'Second Surname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAssetAssets()
    {
        return $this->hasMany(AccountAssetAsset::className(), ['x_responsible_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAssetChangeResponsibleWizards()
    {
        return $this->hasMany(AccountAssetChangeResponsibleWizard::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalColleaguesRels()
    {
        return $this->hasMany(AppraisalColleaguesRel::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals()
    {
        return $this->hasMany(HrAppraisal::className(), ['id' => 'hr_appraisal_id'])->viaTable('appraisal_colleagues_rel', ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalManagerRels()
    {
        return $this->hasMany(AppraisalManagerRel::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals0()
    {
        return $this->hasMany(HrAppraisal::className(), ['id' => 'hr_appraisal_id'])->viaTable('appraisal_manager_rel', ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalSubordinatesRels()
    {
        return $this->hasMany(AppraisalSubordinatesRel::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals1()
    {
        return $this->hasMany(HrAppraisal::className(), ['id' => 'hr_appraisal_id'])->viaTable('appraisal_subordinates_rel', ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpAppraisalColleaguesRels()
    {
        return $this->hasMany(EmpAppraisalColleaguesRel::className(), ['hr_appraisal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpAppraisalColleaguesRels0()
    {
        return $this->hasMany(EmpAppraisalColleaguesRel::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_employee_id'])->viaTable('emp_appraisal_colleagues_rel', ['hr_appraisal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals2()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_appraisal_id'])->viaTable('emp_appraisal_colleagues_rel', ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpAppraisalManagerRels()
    {
        return $this->hasMany(EmpAppraisalManagerRel::className(), ['hr_appraisal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpAppraisalManagerRels0()
    {
        return $this->hasMany(EmpAppraisalManagerRel::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees0()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_employee_id'])->viaTable('emp_appraisal_manager_rel', ['hr_appraisal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals3()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_appraisal_id'])->viaTable('emp_appraisal_manager_rel', ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpAppraisalSubordinatesRels()
    {
        return $this->hasMany(EmpAppraisalSubordinatesRel::className(), ['hr_appraisal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpAppraisalSubordinatesRels0()
    {
        return $this->hasMany(EmpAppraisalSubordinatesRel::className(), ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees1()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_employee_id'])->viaTable('emp_appraisal_subordinates_rel', ['hr_appraisal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals4()
    {
        return $this->hasMany(HrEmployee::className(), ['id' => 'hr_appraisal_id'])->viaTable('emp_appraisal_subordinates_rel', ['hr_employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeCategoryRels()
    {
        return $this->hasMany(EmployeeCategoryRel::className(), ['emp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(HrEmployeeCategory::className(), ['id' => 'category_id'])->viaTable('employee_category_rel', ['emp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeTitleRels()
    {
        return $this->hasMany(EmployeeTitleRel::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitles()
    {
        return $this->hasMany(HrEmployeeTitle::className(), ['id' => 'title_id'])->viaTable('employee_title_rel', ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrApplicants()
    {
        return $this->hasMany(HrApplicant::className(), ['emp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAppraisals5()
    {
        return $this->hasMany(HrAppraisal::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrAttendances()
    {
        return $this->hasMany(HrAttendance::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrContracts()
    {
        return $this->hasMany(HrContract::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrDepartments()
    {
        return $this->hasMany(HrDepartment::className(), ['manager_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXAccountAnalytic()
    {
        return $this->hasOne(AccountAnalyticAccount::className(), ['id' => 'x_account_analytic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(HrDepartment::className(), ['id' => 'department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXDepartmentParent()
    {
        return $this->hasOne(HrDepartment::className(), ['id' => 'x_department_parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoach()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'coach_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees2()
    {
        return $this->hasMany(HrEmployee::className(), ['coach_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees3()
    {
        return $this->hasMany(HrEmployee::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXContractType()
    {
        return $this->hasOne(HrEmployeeContract::className(), ['id' => 'x_contract_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXInstitucion()
    {
        return $this->hasOne(HrEmployeeInstitute::className(), ['id' => 'x_institucion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXWorkTime2()
    {
        return $this->hasOne(HrEmployeeWorktime::className(), ['id' => 'x_work_time2_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXWorkTime()
    {
        return $this->hasOne(HrEmployeeWorktime::className(), ['id' => 'x_work_time_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXWorkTime3()
    {
        return $this->hasOne(HrEmployeeWorktime::className(), ['id' => 'x_work_time3_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(HrJob::className(), ['id' => 'job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXInstitute()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'x_institute_id']);
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
    public function getXCountryTitle()
    {
        return $this->hasOne(ResCountry::className(), ['id' => 'x_country_title_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXResidenceCountry()
    {
        return $this->hasOne(ResCountry::className(), ['id' => 'x_residence_country_id']);
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
    public function getXNationality()
    {
        return $this->hasOne(ResCountryNationality::className(), ['id' => 'x_nationality_id']);
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
    public function getXState()
    {
        return $this->hasOne(ResCountryState::className(), ['id' => 'x_state_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddressHome()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'address_home_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankAccount()
    {
        return $this->hasOne(ResPartnerBank::className(), ['id' => 'bank_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXStore()
    {
        return $this->hasOne(ResStore::className(), ['id' => 'x_store_id']);
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
    public function getResource()
    {
        return $this->hasOne(ResourceResource::className(), ['id' => 'resource_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalManagerSurvey()
    {
        return $this->hasOne(SurveySurvey::className(), ['id' => 'appraisal_manager_survey_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalSelfSurvey()
    {
        return $this->hasOne(SurveySurvey::className(), ['id' => 'appraisal_self_survey_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalCollaboratorsSurvey()
    {
        return $this->hasOne(SurveySurvey::className(), ['id' => 'appraisal_collaborators_survey_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppraisalColleaguesSurvey()
    {
        return $this->hasOne(SurveySurvey::className(), ['id' => 'appraisal_colleagues_survey_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeChildrens()
    {
        return $this->hasMany(HrEmployeeChildren::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeExpenses()
    {
        return $this->hasMany(HrEmployeeExpenses::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeExpensesLines()
    {
        return $this->hasMany(HrEmployeeExpensesLines::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeGroupRels()
    {
        return $this->hasMany(HrEmployeeGroupRel::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayslips()
    {
        return $this->hasMany(HrPayslipEmployees::className(), ['id' => 'payslip_id'])->viaTable('hr_employee_group_rel', ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeInitialBalances()
    {
        return $this->hasMany(HrEmployeeInitialBalance::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployeeTrainings()
    {
        return $this->hasMany(HrEmployeeTraining::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEquipments()
    {
        return $this->hasMany(HrEquipment::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEquipmentRequests()
    {
        return $this->hasMany(HrEquipmentRequest::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrExpenses()
    {
        return $this->hasMany(HrExpense::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrHolidays()
    {
        return $this->hasMany(HrHolidays::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrHolidays0()
    {
        return $this->hasMany(HrHolidays::className(), ['manager_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrHolidays1()
    {
        return $this->hasMany(HrHolidays::className(), ['manager_id2' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrInductionCheckLists()
    {
        return $this->hasMany(HrInductionCheckList::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(HrInductionCheckListDocument::className(), ['id' => 'document_id'])->viaTable('hr_induction_check_list', ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrJobs()
    {
        return $this->hasMany(HrJob::className(), ['manager_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrLoans()
    {
        return $this->hasMany(HrLoan::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrLoans0()
    {
        return $this->hasMany(HrLoan::className(), ['authorized_by_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrLoanLines()
    {
        return $this->hasMany(HrLoanLine::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPayslips()
    {
        return $this->hasMany(HrPayslip::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrPayslipLines()
    {
        return $this->hasMany(HrPayslipLine::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrVariableDiscounts()
    {
        return $this->hasMany(HrVariableDiscount::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrVariableIncomes()
    {
        return $this->hasMany(HrVariableIncome::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFaculties()
    {
        return $this->hasMany(OpFaculty::className(), ['emp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPsychologicalAttentions()
    {
        return $this->hasMany(OpPsychologicalAttention::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPsychologicalCasesAttendeds()
    {
        return $this->hasMany(OpPsychologicalCasesAttended::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSummaryEmpRels()
    {
        return $this->hasMany(SummaryEmpRel::className(), ['emp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSums()
    {
        return $this->hasMany(HrHolidaysSummaryEmployee::className(), ['id' => 'sum_id'])->viaTable('summary_emp_rel', ['emp_id' => 'id']);
    }
}
