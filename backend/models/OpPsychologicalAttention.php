<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_psychological_attention".
 *
 * @property int $id
 * @property int $attended_faculty_id Docente
 * @property string $create_date Created on
 * @property string $detail Detalles del seguimiento
 * @property int $departament_id Departamento
 * @property int $course_id Curso
 * @property string $subject Asunto
 * @property int $create_uid Created by
 * @property int $employee_id Empleado
 * @property int $external_derivation_id Derivación externa
 * @property int $student_id Estudiante
 * @property int $violence_modality_id Modalidad
 * @property int $attention_type_id Tipo atención
 * @property string $agreements Acuerdos
 * @property int $violence_type_id Tipo
 * @property int $violence_reason_id Motivo
 * @property int $attended_student_id Estudiante
 * @property string $state Estado
 * @property int $attended_parent_id Representante
 * @property string $write_date Last Updated on
 * @property string $date Fecha atención
 * @property int $write_uid Last Updated by
 * @property int $special_need_id Necesidad especial
 * @property int $substance_use_id Consumo sustancias
 * @property int $parallel_id Paralelo
 * @property bool $special_attention Necesita atención especial?
 * @property string $persona_lidera Persona que lidera
 *
 * @property HrEmployee $employee
 * @property OpCourse $course
 * @property OpCourseParalelo $parallel
 * @property OpDepartmentDece $departament
 * @property OpExternalDerivation $externalDerivation
 * @property OpFaculty $attendedFaculty
 * @property OpParent $attendedParent
 * @property OpPsychologicalAttentionType $attentionType
 * @property OpSpecialNeeds $specialNeed
 * @property OpStudent $student
 * @property OpStudent $attendedStudent
 * @property OpSubstanceUse $substanceUse
 * @property OpViolenceModality $violenceModality
 * @property OpViolenceReason $violenceReason
 * @property OpViolenceType $violenceType
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property OpPsychologicalAttentionAsistentes[] $opPsychologicalAttentionAsistentes
 */
class OpPsychologicalAttention extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_psychological_attention';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attended_faculty_id', 'departament_id', 'course_id', 'create_uid', 'employee_id', 'external_derivation_id', 'student_id', 'violence_modality_id', 'attention_type_id', 'violence_type_id', 'violence_reason_id', 'attended_student_id', 'attended_parent_id', 'write_uid', 'special_need_id', 'substance_use_id', 'parallel_id'], 'default', 'value' => null],
            [['attended_faculty_id', 'departament_id', 'course_id', 'create_uid', 'employee_id', 'external_derivation_id', 'student_id', 'violence_modality_id', 'attention_type_id', 'violence_type_id', 'violence_reason_id', 'attended_student_id', 'attended_parent_id', 'write_uid', 'special_need_id', 'substance_use_id', 'parallel_id'], 'integer'],
            [['create_date', 'write_date', 'date'], 'safe'],
            [['detail', 'departament_id', 'subject', 'employee_id', 'student_id', 'attention_type_id', 'agreements', 'date'], 'required'],
            [['detail', 'agreements', 'state', 'persona_lidera'], 'string'],
            [['special_attention'], 'boolean'],
            [['subject'], 'string', 'max' => 100],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrEmployee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['parallel_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['parallel_id' => 'id']],
            [['departament_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpDepartmentDece::className(), 'targetAttribute' => ['departament_id' => 'id']],
            [['external_derivation_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpExternalDerivation::className(), 'targetAttribute' => ['external_derivation_id' => 'id']],
            [['attended_faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['attended_faculty_id' => 'id']],
            [['attended_parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpParent::className(), 'targetAttribute' => ['attended_parent_id' => 'id']],
            [['attention_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPsychologicalAttentionType::className(), 'targetAttribute' => ['attention_type_id' => 'id']],
            [['special_need_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpSpecialNeeds::className(), 'targetAttribute' => ['special_need_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['attended_student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['attended_student_id' => 'id']],
            [['substance_use_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpSubstanceUse::className(), 'targetAttribute' => ['substance_use_id' => 'id']],
            [['violence_modality_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpViolenceModality::className(), 'targetAttribute' => ['violence_modality_id' => 'id']],
            [['violence_reason_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpViolenceReason::className(), 'targetAttribute' => ['violence_reason_id' => 'id']],
            [['violence_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpViolenceType::className(), 'targetAttribute' => ['violence_type_id' => 'id']],
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
            'attended_faculty_id' => 'Attended Faculty ID',
            'create_date' => 'Create Date',
            'detail' => 'Detalle',
            'departament_id' => 'Departament ID',
            'course_id' => 'Course ID',
            'subject' => 'Asunto',
            'create_uid' => 'Creado por',
            'employee_id' => 'Employee ID',
            'external_derivation_id' => 'Derivación Externa',
            'student_id' => 'Estudiante',
            'violence_modality_id' => 'Violence Modality ID',
            'attention_type_id' => 'Attention Type ID',
            'agreements' => 'Acuerdos',
            'violence_type_id' => 'Violence Type ID',
            'violence_reason_id' => 'Violence Reason ID',
            'attended_student_id' => 'Attended Student ID',
            'state' => 'Estado',
            'attended_parent_id' => 'Attended Parent ID',
            'write_date' => 'Write Date',
            'date' => 'Fecha',
            'write_uid' => 'Write Uid',
            'special_need_id' => 'Special Need ID',
            'substance_use_id' => 'Substance Use ID',
            'parallel_id' => 'Parallel ID',
            'special_attention' => 'Atención Especial',
            'persona_lidera' => 'Persona Lidera',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(HrEmployee::className(), ['id' => 'employee_id']);
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
    public function getParallel()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'parallel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartament()
    {
        return $this->hasOne(OpDepartmentDece::className(), ['id' => 'departament_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExternalDerivation()
    {
        return $this->hasOne(OpExternalDerivation::className(), ['id' => 'external_derivation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendedFaculty()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'attended_faculty_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendedParent()
    {
        return $this->hasOne(OpParent::className(), ['id' => 'attended_parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttentionType()
    {
        return $this->hasOne(OpPsychologicalAttentionType::className(), ['id' => 'attention_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecialNeed()
    {
        return $this->hasOne(OpSpecialNeeds::className(), ['id' => 'special_need_id']);
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
    public function getAttendedStudent()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'attended_student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubstanceUse()
    {
        return $this->hasOne(OpSubstanceUse::className(), ['id' => 'substance_use_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViolenceModality()
    {
        return $this->hasOne(OpViolenceModality::className(), ['id' => 'violence_modality_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViolenceReason()
    {
        return $this->hasOne(OpViolenceReason::className(), ['id' => 'violence_reason_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViolenceType()
    {
        return $this->hasOne(OpViolenceType::className(), ['id' => 'violence_type_id']);
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
    public function getOpPsychologicalAttentionAsistentes()
    {
        return $this->hasMany(OpPsychologicalAttentionAsistentes::className(), ['psychological_attention_id' => 'id']);
    }
}
