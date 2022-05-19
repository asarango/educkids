<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_student".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $last_name Last Name
 * @property string $create_date Created on
 * @property string $write_date Last Updated on
 * @property string $blood_group Blood Group
 * @property string $id_number ID Card Number
 * @property resource $photo Photo
 * @property string $middle_name Middle Name
 * @property int $write_uid Last Updated by
 * @property int $course_id Course
 * @property int $emergency_contact Emergency Contact
 * @property string $birth_date Birth Date
 * @property int $nationality Nationality
 * @property string $gender Gender
 * @property int $batch_id Batch
 * @property string $roll_number Current Roll Number
 * @property string $gr_no GR Number
 * @property string $visa_info Visa Info
 * @property int $partner_id Partner
 * @property int $library_card_id Library Card
 * @property string $x_retirado Retirado por
 * @property string $first_name Last Name
 * @property string $insc_estado estado inscripcion
 * @property string $x_origen origen
 * @property double $x_subsidio subsidio
 * @property string $x_dir_llegada Dirección de llegada
 * @property string $x_codigo_relacion codigo_relacion
 * @property int $x_factura_a Factura a
 * @property int $x_paralelo_id Paralelo
 * @property string $reserva_campus reserva_campus
 * @property string $x_num_matricula Numero Matricula
 * @property int $x_cliente Cliente
 * @property string $reserva_cupo reserva_cupo
 * @property string $x_estado ESTADO
 * @property int $x_n_alumno_moved0 n_alumno
 * @property string $x_dir_salida Dirección de salida
 * @property int $x_city City
 * @property int $x_institute Instituto
 * @property int $x_representante Representante
 * @property string $x_ethnic_def Huérfano de
 * @property string $x_orphan Huérfano de
 * @property string $x_civil_status Estado civil
 * @property string $x_representative Representante legal
 * @property string $x_second_street Calle secundaria
 * @property string $x_observation Observaciones generales
 * @property string $x_contract_number No. de Contrato de Seguro
 * @property string $x_faith_profession Profesión de fé
 * @property int $x_enrollment_id Matrícula
 * @property string $x_home_number Número
 * @property string $x_main_street Calle principal
 * @property string $x_insurance_name Nombre de la aseguradora
 * @property bool $x_with_debt Deudas pendientes
 * @property string $x_n_alumno n_alumno
 * @property string $x_representative3 Representante legal 3
 * @property string $x_representative2 Representante legal 2
 * @property int $x_brothers Número de hermanos
 * @property double $x_disability_percent Porcentaje discapacidad
 * @property string $x_conadis_ident Identificación (Conadis)
 * @property bool $x_disability Discapacidad
 * @property string $x_representative3_id Representante legal 3 (Identificación)
 * @property string $x_representative3_rel Representante legal 3 (Parentezco)
 * @property int $x_disability_id Tipo discapacidad
 *
 * @property AccountInvoice[] $accountInvoices
 * @property AccountPayment[] $accountPayments
 * @property InscriptionGenerateWizardLines[] $inscriptionGenerateWizardLines
 * @property IssueBook[] $issueBooks
 * @property OpAchievement[] $opAchievements
 * @property OpActivity[] $opActivities
 * @property OpAdmission[] $opAdmissions
 * @property OpAllStudentOpStudentRel[] $opAllStudentOpStudentRels
 * @property OpAllStudent[] $opAllStudents
 * @property OpAssignmentOpStudentRel[] $opAssignmentOpStudentRels
 * @property OpAssignment[] $opAssignments
 * @property OpAssignmentSubLine[] $opAssignmentSubLines
 * @property OpAttendanceLine[] $opAttendanceLines
 * @property OpBookMovement[] $opBookMovements
 * @property OpExamAttendees[] $opExamAttendees
 * @property OpExamResAllocationOpStudentRel[] $opExamResAllocationOpStudentRels
 * @property OpExamResAllocation[] $opExamResAllocations
 * @property OpExamRoomOpStudentRel[] $opExamRoomOpStudentRels
 * @property OpExamRoom[] $opExamRooms
 * @property OpFamilyStudents[] $opFamilyStudents
 * @property OpHealth[] $opHealths
 * @property OpLibraryCard[] $opLibraryCards
 * @property OpMarksheetLine[] $opMarksheetLines
 * @property OpParentOpStudentRel[] $opParentOpStudentRels
 * @property OpParent[] $opParents
 * @property OpPlacementOffer[] $opPlacementOffers
 * @property OpResultLine[] $opResultLines
 * @property OpRollNumber[] $opRollNumbers
 * @property OpScholarship[] $opScholarships
 * @property OpBatch $batch
 * @property OpCourse $course
 * @property OpCourseParalelo $xParalelo
 * @property OpInstitute $xInstitute
 * @property OpLibraryCard $libraryCard
 * @property OpParent $xRepresentante
 * @property OpStudentEnrollment $xEnrollment
 * @property ResCountry $nationality0
 * @property ResCountryCity $xCity
 * @property ResDisability $xDisability
 * @property ResPartner $emergencyContact
 * @property ResPartner $partner
 * @property ResPartner $xFacturaA
 * @property ResPartner $xCliente
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property OpStudentAuthorizedParent[] $opStudentAuthorizedParents
 * @property OpStudentEnrollment[] $opStudentEnrollments
 * @property OpStudentInscription[] $opStudentInscriptions
 * @property OpStudentOpTransportationRel[] $opStudentOpTransportationRels
 * @property OpTransportation[] $opTransportations
 * @property OpStudentResPartnerRel[] $opStudentResPartnerRels
 * @property ResPartner[] $resPartners
 * @property OpStudentStudentMigrateRel[] $opStudentStudentMigrateRels
 * @property StudentMigrate[] $studentMigrates
 * @property OpStudentWizardOpStudentRel[] $opStudentWizardOpStudentRels
 * @property WizardOpStudent[] $wizardOpStudents
 * @property RelStudentService[] $relStudentServices
 * @property ScholarisNotasAutomaticasParcial[] $scholarisNotasAutomaticasParcials
 * @property ScholarisRepPromedios[] $scholarisRepPromedios
 * @property ScholarisReporteFinalAlumno[] $scholarisReporteFinalAlumnos
 * @property ScholarisReporteNotasArea[] $scholarisReporteNotasAreas
 * @property ScholarisReporteNotasClase[] $scholarisReporteNotasClases
 * @property ScholarisResumenFinales[] $scholarisResumenFinales
 * @property ScholarisResumenParciales[] $scholarisResumenParciales
 * @property StudentService[] $studentServices
 */
class OpStudent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'course_id', 'emergency_contact', 'nationality', 'batch_id', 'partner_id', 'library_card_id', 'x_factura_a', 'x_paralelo_id', 'x_cliente', 'x_n_alumno_moved0', 'x_city', 'x_institute', 'x_representante', 'x_enrollment_id', 'x_brothers', 'x_disability_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'course_id', 'emergency_contact', 'nationality', 'batch_id', 'partner_id', 'library_card_id', 'x_factura_a', 'x_paralelo_id', 'x_cliente', 'x_n_alumno_moved0', 'x_city', 'x_institute', 'x_representante', 'x_enrollment_id', 'x_brothers', 'x_disability_id'], 'integer'],
            [['last_name', 'birth_date', 'gender', 'partner_id', 'first_name', 'x_estado', 'x_institute', 'x_n_alumno', 'x_brothers'], 'required'],
            [['create_date', 'write_date', 'birth_date'], 'safe'],
            [['blood_group', 'photo', 'gender', 'x_retirado', 'insc_estado', 'x_dir_llegada', 'reserva_cupo', 'x_estado', 'x_dir_salida', 'x_ethnic_def', 'x_orphan', 'x_civil_status', 'x_representative', 'x_second_street', 'x_observation', 'x_contract_number', 'x_faith_profession', 'x_home_number', 'x_main_street', 'x_insurance_name', 'x_n_alumno', 'x_representative3', 'x_representative2', 'x_conadis_ident', 'x_representative3_id', 'x_representative3_rel'], 'string'],
            [['x_subsidio', 'x_disability_percent'], 'number'],
            [['x_with_debt', 'x_disability'], 'boolean'],
            [['last_name', 'middle_name', 'first_name'], 'string', 'max' => 128],
            [['id_number', 'visa_info'], 'string', 'max' => 64],
            [['roll_number'], 'string', 'max' => 8],
            [['gr_no'], 'string', 'max' => 20],
            [['x_origen'], 'string', 'max' => 300],
            [['x_codigo_relacion', 'x_num_matricula'], 'string', 'max' => 10],
            [['reserva_campus'], 'string', 'max' => 30],
            [['x_n_alumno'], 'unique'],
            [['batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpBatch::className(), 'targetAttribute' => ['batch_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['x_paralelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['x_paralelo_id' => 'id']],
            [['x_institute'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['x_institute' => 'id']],
            [['library_card_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpLibraryCard::className(), 'targetAttribute' => ['library_card_id' => 'id']],
            [['x_representante'], 'exist', 'skipOnError' => true, 'targetClass' => OpParent::className(), 'targetAttribute' => ['x_representante' => 'id']],
            [['x_enrollment_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentEnrollment::className(), 'targetAttribute' => ['x_enrollment_id' => 'id']],
            [['nationality'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['nationality' => 'id']],
            [['x_city'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountryCity::className(), 'targetAttribute' => ['x_city' => 'id']],
            [['x_disability_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResDisability::className(), 'targetAttribute' => ['x_disability_id' => 'id']],
            [['emergency_contact'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['emergency_contact' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['partner_id' => 'id']],
            [['x_factura_a'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['x_factura_a' => 'id']],
            [['x_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => ResPartner::className(), 'targetAttribute' => ['x_cliente' => 'id']],
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
            'last_name' => 'Last Name',
            'create_date' => 'Create Date',
            'write_date' => 'Write Date',
            'blood_group' => 'Blood Group',
            'id_number' => 'Id Number',
            'photo' => 'Photo',
            'middle_name' => 'Middle Name',
            'write_uid' => 'Write Uid',
            'course_id' => 'Course ID',
            'emergency_contact' => 'Emergency Contact',
            'birth_date' => 'Birth Date',
            'nationality' => 'Nationality',
            'gender' => 'Gender',
            'batch_id' => 'Batch ID',
            'roll_number' => 'Roll Number',
            'gr_no' => 'Gr No',
            'visa_info' => 'Visa Info',
            'partner_id' => 'Partner ID',
            'library_card_id' => 'Library Card ID',
            'x_retirado' => 'X Retirado',
            'first_name' => 'First Name',
            'insc_estado' => 'Insc Estado',
            'x_origen' => 'X Origen',
            'x_subsidio' => 'X Subsidio',
            'x_dir_llegada' => 'X Dir Llegada',
            'x_codigo_relacion' => 'X Codigo Relacion',
            'x_factura_a' => 'X Factura A',
            'x_paralelo_id' => 'X Paralelo ID',
            'reserva_campus' => 'Reserva Campus',
            'x_num_matricula' => 'X Num Matricula',
            'x_cliente' => 'X Cliente',
            'reserva_cupo' => 'Reserva Cupo',
            'x_estado' => 'X Estado',
            'x_n_alumno_moved0' => 'X N Alumno Moved0',
            'x_dir_salida' => 'X Dir Salida',
            'x_city' => 'X City',
            'x_institute' => 'X Institute',
            'x_representante' => 'X Representante',
            'x_ethnic_def' => 'X Ethnic Def',
            'x_orphan' => 'X Orphan',
            'x_civil_status' => 'X Civil Status',
            'x_representative' => 'X Representative',
            'x_second_street' => 'X Second Street',
            'x_observation' => 'X Observation',
            'x_contract_number' => 'X Contract Number',
            'x_faith_profession' => 'X Faith Profession',
            'x_enrollment_id' => 'X Enrollment ID',
            'x_home_number' => 'X Home Number',
            'x_main_street' => 'X Main Street',
            'x_insurance_name' => 'X Insurance Name',
            'x_with_debt' => 'X With Debt',
            'x_n_alumno' => 'X N Alumno',
            'x_representative3' => 'X Representative3',
            'x_representative2' => 'X Representative2',
            'x_brothers' => 'X Brothers',
            'x_disability_percent' => 'X Disability Percent',
            'x_conadis_ident' => 'X Conadis Ident',
            'x_disability' => 'X Disability',
            'x_representative3_id' => 'X Representative3 ID',
            'x_representative3_rel' => 'X Representative3 Rel',
            'x_disability_id' => 'X Disability ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountInvoices()
    {
        return $this->hasMany(AccountInvoice::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountPayments()
    {
        return $this->hasMany(AccountPayment::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInscriptionGenerateWizardLines()
    {
        return $this->hasMany(InscriptionGenerateWizardLines::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueBooks()
    {
        return $this->hasMany(IssueBook::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAchievements()
    {
        return $this->hasMany(OpAchievement::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpActivities()
    {
        return $this->hasMany(OpActivity::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAdmissions()
    {
        return $this->hasMany(OpAdmission::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAllStudentOpStudentRels()
    {
        return $this->hasMany(OpAllStudentOpStudentRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAllStudents()
    {
        return $this->hasMany(OpAllStudent::className(), ['id' => 'op_all_student_id'])->viaTable('op_all_student_op_student_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAssignmentOpStudentRels()
    {
        return $this->hasMany(OpAssignmentOpStudentRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAssignments()
    {
        return $this->hasMany(OpAssignment::className(), ['id' => 'op_assignment_id'])->viaTable('op_assignment_op_student_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAssignmentSubLines()
    {
        return $this->hasMany(OpAssignmentSubLine::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpAttendanceLines()
    {
        return $this->hasMany(OpAttendanceLine::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpBookMovements()
    {
        return $this->hasMany(OpBookMovement::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamAttendees()
    {
        return $this->hasMany(OpExamAttendees::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamResAllocationOpStudentRels()
    {
        return $this->hasMany(OpExamResAllocationOpStudentRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamResAllocations()
    {
        return $this->hasMany(OpExamResAllocation::className(), ['id' => 'op_exam_res_allocation_id'])->viaTable('op_exam_res_allocation_op_student_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamRoomOpStudentRels()
    {
        return $this->hasMany(OpExamRoomOpStudentRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpExamRooms()
    {
        return $this->hasMany(OpExamRoom::className(), ['id' => 'op_exam_room_id'])->viaTable('op_exam_room_op_student_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpFamilyStudents()
    {
        return $this->hasMany(OpFamilyStudents::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpHealths()
    {
        return $this->hasMany(OpHealth::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpLibraryCards()
    {
        return $this->hasMany(OpLibraryCard::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpMarksheetLines()
    {
        return $this->hasMany(OpMarksheetLine::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpParentOpStudentRels()
    {
        return $this->hasMany(OpParentOpStudentRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpParents()
    {
        return $this->hasMany(OpParent::className(), ['id' => 'op_parent_id'])->viaTable('op_parent_op_student_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpPlacementOffers()
    {
        return $this->hasMany(OpPlacementOffer::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpResultLines()
    {
        return $this->hasMany(OpResultLine::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpRollNumbers()
    {
        return $this->hasMany(OpRollNumber::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpScholarships()
    {
        return $this->hasMany(OpScholarship::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatch()
    {
        return $this->hasOne(OpBatch::className(), ['id' => 'batch_id']);
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
    public function getXInstitute()
    {
        return $this->hasOne(OpInstitute::className(), ['id' => 'x_institute']);
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
    public function getXRepresentante()
    {
        return $this->hasOne(OpParent::className(), ['id' => 'x_representante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXEnrollment()
    {
        return $this->hasOne(OpStudentEnrollment::className(), ['id' => 'x_enrollment_id']);
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
    public function getXCity()
    {
        return $this->hasOne(ResCountryCity::className(), ['id' => 'x_city']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXDisability()
    {
        return $this->hasOne(ResDisability::className(), ['id' => 'x_disability_id']);
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
    public function getXFacturaA()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'x_factura_a']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXCliente()
    {
        return $this->hasOne(ResPartner::className(), ['id' => 'x_cliente']);
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
    public function getOpStudentAuthorizedParents()
    {
        return $this->hasMany(OpStudentAuthorizedParent::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptions()
    {
        return $this->hasMany(OpStudentInscription::className(), ['student_id' => 'id']);
    }
    
    public function getOp_student_inscription()
    {
        return $this->hasMany(OpStudentInscription::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentOpTransportationRels()
    {
        return $this->hasMany(OpStudentOpTransportationRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpTransportations()
    {
        return $this->hasMany(OpTransportation::className(), ['id' => 'op_transportation_id'])->viaTable('op_student_op_transportation_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentResPartnerRels()
    {
        return $this->hasMany(OpStudentResPartnerRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartners()
    {
        return $this->hasMany(ResPartner::className(), ['id' => 'res_partner_id'])->viaTable('op_student_res_partner_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentStudentMigrateRels()
    {
        return $this->hasMany(OpStudentStudentMigrateRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentMigrates()
    {
        return $this->hasMany(StudentMigrate::className(), ['id' => 'student_migrate_id'])->viaTable('op_student_student_migrate_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentWizardOpStudentRels()
    {
        return $this->hasMany(OpStudentWizardOpStudentRel::className(), ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWizardOpStudents()
    {
        return $this->hasMany(WizardOpStudent::className(), ['id' => 'wizard_op_student_id'])->viaTable('op_student_wizard_op_student_rel', ['op_student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelStudentServices()
    {
        return $this->hasMany(RelStudentService::className(), ['student_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisNotasAutomaticasParcials()
    {
        return $this->hasMany(ScholarisNotasAutomaticasParcial::className(), ['alumno_id' => 'x_n_alumno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisRepPromedios()
    {
        return $this->hasMany(ScholarisRepPromedios::className(), ['alumno_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisReporteFinalAlumnos()
    {
        return $this->hasMany(ScholarisReporteFinalAlumno::className(), ['alumno_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisReporteNotasAreas()
    {
        return $this->hasMany(ScholarisReporteNotasArea::className(), ['alumno_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisReporteNotasClases()
    {
        return $this->hasMany(ScholarisReporteNotasClase::className(), ['alumno_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisResumenFinales()
    {
        return $this->hasMany(ScholarisResumenFinales::className(), ['alumno_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisResumenParciales()
    {
        return $this->hasMany(ScholarisResumenParciales::className(), ['alumno_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentServices()
    {
        return $this->hasMany(StudentService::className(), ['student_id' => 'id']);
    }
    
    
    public function toma_alumnos_paralelo($paralelo){
        
        $con = Yii::$app->db;
//        $query = "select s.last_name
//                                ,s.first_name
//                                ,s.middle_name
//                                ,i.inscription_state
//                from	op_student_inscription i
//                                inner join op_student s on s.id = i.student_id
//                where	i.parallel_id = $paralelo
//                        and i.inscription_state = 'M' 
//                order by s.last_name::bytea;";
        $query = "select 	s.last_name
		,s.first_name
		,s.middle_name
                ,s.x_institutional_email as email_student
		,i.inscription_state
		,s.blood_group as grupo_sangre
		,c.name as curso
		,s.emergency_contact as cont_emergencia
		,s.birth_date as cumpleanios
		,s.nationality
		,n.name as nacionalidad
		,s.gender as genero
		, par.tipo_identificacion 
		, par.numero_identificacion 
		, s.insc_estado as estado 
		, s.x_second_street as calle_secundaria 
		, s.x_home_number as numero 
		, s.x_main_street as calle_principal 
		, s.x_representative as quien_respresenta
        , rep.name as representante 
		, rep.numero_identificacion as rep_cedula 
		, rep.phone as rep_telefono 
		, rep.mobile as rep_celular 
		, rep.street as rep_direccion 
		, rep.email as rep_correo
		, s.x_representante
		,(select 	ptn1.name || '('||ptn1.phone|| ')' 
			from 	op_parent_op_student_rel rel1 
					inner join op_parent par1 on par1.id = rel1.op_parent_id
					inner join res_partner ptn1 on ptn1.id = par1.name
			where 	rel1.op_student_id = i.student_id
					and par1.x_state = 'padre' limit 1) as padre
		,(select 	ptn1.name || '('||ptn1.phone|| ')' 
			from 	op_parent_op_student_rel rel1 
					inner join op_parent par1 on par1.id = rel1.op_parent_id
					inner join res_partner ptn1 on ptn1.id = par1.name
			where 	rel1.op_student_id = i.student_id
					and par1.x_state = 'madre' limit 1) as madre
		,'' as ultima
from	op_student_inscription i
		inner join op_student s on s.id = i.student_id
		inner join res_partner par on par.id = s.partner_id
		inner join op_course_paralelo p on p.id = i.parallel_id
		inner join op_course c on c.id = p.course_id
		left join res_country n on n.id = s.nationality
		left join res_partner rep on rep.id = s.emergency_contact
where	i.parallel_id = $paralelo
		and i.inscription_state = 'M'
--order by  --s.last_name::bytea,
order by  s.last_name,
		 s.first_name::bytea,
		 s.middle_name::bytea;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function tiene_deuda($alumno){
        $con = Yii::$app->db;
        $query = "select * from zabyca_reporte_estudiantes_deuda where n_alumno = $alumno and sum > 0;";
        $res = $con->createCommand($query)->queryOne();
        
        if($res){
            return true;
        }else{
            return false;
        }
        

    }
    
}
