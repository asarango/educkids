<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_course".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $code Code
 * @property string $create_date Created on
 * @property string $name Name
 * @property string $evaluation_type Evaluation Type
 * @property int $write_uid Last Updated by
 * @property int $parent_id Parent Course
 * @property string $write_date Last Updated on
 * @property string $section_moved0 Section
 * @property int $payment_term Payment Term
 * @property int $section Sección
 * @property int $x_institute Instituto
 * @property int $level_id Nivel
 * @property int $x_template_id Plantilla curso
 * @property int $x_capacidad Capacidad
 * @property int $x_period_id Período
 * @property int $orden
 *
 * @property AccountInvoice[] $accountInvoices
 * @property AdmissionAnalysis[] $admissionAnalyses
 * @property InscriptionGenerateWizard[] $inscriptionGenerateWizards
 * @property OpAdmission[] $opAdmissions
 * @property OpAdmission[] $opAdmissions0
 * @property OpAdmissionRegister[] $opAdmissionRegisters
 * @property OpAllStudent[] $opAllStudents
 * @property OpAssignment[] $opAssignments
 * @property OpAttendanceLine[] $opAttendanceLines
 * @property OpAttendanceRegister[] $opAttendanceRegisters
 * @property OpAttendanceSheet[] $opAttendanceSheets
 * @property OpBatch[] $opBatches
 * @property OpBookOpCourseRel[] $opBookOpCourseRels
 * @property OpBook[] $opBooks
 * @property OpBookPurchase[] $opBookPurchases
 * @property OpClassroom[] $opClassrooms
 * @property AccountPaymentTerm $paymentTerm
 * @property OpCourse $parent
 * @property OpCourse[] $opCourses
 * @property OpCourseLevel $level
 * @property OpCourseTemplate $xTemplate
 * @property OpInstitute $xInstitute
 * @property OpPeriod $xPeriod
 * @property OpSection $section0
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property OpCourseOpExamRoomRel[] $opCourseOpExamRoomRels
 * @property OpExamRoom[] $opExamRooms
 * @property OpCourseOpSubjectRel[] $opCourseOpSubjectRels
 * @property OpSubject[] $opSubjects
 * @property OpCourseParalelo[] $opCourseParalelos
 * @property OpExamAttendees[] $opExamAttendees
 * @property OpExamSession[] $opExamSessions
 * @property OpExtraValues[] $opExtraValues
 * @property OpRollNumber[] $opRollNumbers
 * @property OpScheduleOpCourseRel[] $opScheduleOpCourseRels
 * @property OpScheduleEnrollment[] $schedules
 * @property OpStudent[] $opStudents
 * @property OpStudentEnrollment[] $opStudentEnrollments
 * @property OpStudentInscription[] $opStudentInscriptions
 * @property OpSubject[] $opSubjects0
 * @property PlanPlanificacion[] $planPlanificacions
 * @property ScholarisActividadIndagacionCurso[] $scholarisActividadIndagacionCursos
 * @property ScholarisAreaConfiguraciones[] $scholarisAreaConfiguraciones
 * @property ScholarisCursoImprimeLibreta[] $scholarisCursoImprimeLibretas
 * @property StudentMigrate[] $studentMigrates
 * @property StudentMigrate[] $studentMigrates0
 */
class OpCourse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'parent_id', 'payment_term', 'section', 'x_institute', 'level_id', 'x_template_id', 'x_capacidad', 'x_period_id', 'orden'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'parent_id', 'payment_term', 'section', 'x_institute', 'level_id', 'x_template_id', 'x_capacidad', 'x_period_id', 'orden'], 'integer'],
            [['code', 'name', 'evaluation_type', 'x_institute', 'x_template_id'], 'required'],
            [['create_date', 'write_date'], 'safe'],
            [['evaluation_type'], 'string'],
            [['code'], 'string', 'max' => 8],
            [['name', 'section_moved0'], 'string', 'max' => 32],
//            [['payment_term'], 'exist', 'skipOnError' => true, 'targetClass' => AccountPaymentTerm::className(), 'targetAttribute' => ['payment_term' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['parent_id' => 'id']],
//            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseLevel::className(), 'targetAttribute' => ['level_id' => 'id']],
            [['x_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseTemplate::className(), 'targetAttribute' => ['x_template_id' => 'id']],
            [['x_institute'], 'exist', 'skipOnError' => true, 'targetClass' => OpInstitute::className(), 'targetAttribute' => ['x_institute' => 'id']],
//            [['x_period_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPeriod::className(), 'targetAttribute' => ['x_period_id' => 'id']],
            [['section'], 'exist', 'skipOnError' => true, 'targetClass' => OpSection::className(), 'targetAttribute' => ['section' => 'id']],
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
            'evaluation_type' => 'Evaluation Type',
            'write_uid' => 'Write Uid',
            'parent_id' => 'Parent ID',
            'write_date' => 'Write Date',
            'section_moved0' => 'Section Moved0',
            'payment_term' => 'Payment Term',
            'section' => 'Section',
            'x_institute' => 'X Institute',
            'level_id' => 'Level ID',
            'x_template_id' => 'X Template ID',
            'x_capacidad' => 'X Capacidad',
            'x_period_id' => 'X Period ID',
            'orden' => 'Orden',
        ];
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
    public function getXPeriod()
    {
        return $this->hasOne(OpPeriod::className(), ['id' => 'x_period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection0()
    {
        return $this->hasOne(OpSection::className(), ['id' => 'section']);
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourseParalelos()
    {
        return $this->hasMany(OpCourseParalelo::className(), ['course_id' => 'id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudents()
    {
        return $this->hasMany(OpStudent::className(), ['course_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentEnrollments()
    {
        return $this->hasMany(OpStudentEnrollment::className(), ['course_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpStudentInscriptions()
    {
        return $this->hasMany(OpStudentInscription::className(), ['course_id' => 'id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanPlanificacions()
    {
        return $this->hasMany(PlanPlanificacion::className(), ['curso_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadIndagacionCursos()
    {
        return $this->hasMany(ScholarisActividadIndagacionCurso::className(), ['curso_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAreaConfiguraciones()
    {
        return $this->hasMany(ScholarisAreaConfiguraciones::className(), ['curso_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisCursoImprimeLibretas()
    {
        return $this->hasMany(ScholarisCursoImprimeLibreta::className(), ['curso_id' => 'id']);
    }

    public function getXTemplate()
    {
        return $this->hasOne(OpCourseTemplate::className(), ['id' => 'x_template_id']);
    }
    
}
