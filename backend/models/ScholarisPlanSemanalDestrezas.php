<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_semanal_destrezas".
 *
 * @property int $curso_id
 * @property int $faculty_id
 * @property int $semana_id
 * @property int $comparte_valor
 * @property string $concepto
 * @property string $contexto
 * @property string $pregunta_indagacion
 * @property string $enfoque
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpCourse $curso
 * @property OpFaculty $faculty
 * @property ScholarisBloqueSemanas $semana
 */
class ScholarisPlanSemanalDestrezas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_semanal_destrezas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_id', 'faculty_id', 'semana_id', 'comparte_valor', 'concepto', 'contexto', 'pregunta_indagacion', 'enfoque', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['curso_id', 'faculty_id', 'semana_id', 'comparte_valor'], 'default', 'value' => null],
            [['curso_id', 'faculty_id', 'semana_id', 'comparte_valor'], 'integer'],
            [['concepto', 'contexto', 'pregunta_indagacion', 'enfoque'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['curso_id', 'faculty_id', 'semana_id', 'comparte_valor'], 'unique', 'targetAttribute' => ['curso_id', 'faculty_id', 'semana_id', 'comparte_valor']],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_id' => 'id']],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['faculty_id' => 'id']],
            [['semana_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueSemanas::className(), 'targetAttribute' => ['semana_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'curso_id' => 'Curso ID',
            'faculty_id' => 'Faculty ID',
            'semana_id' => 'Semana ID',
            'comparte_valor' => 'Comparte Valor',
            'concepto' => 'Concepto',
            'contexto' => 'Contexto',
            'pregunta_indagacion' => 'Pregunta Indagacion',
            'enfoque' => 'Enfoque',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'curso_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaculty()
    {
        return $this->hasOne(OpFaculty::className(), ['id' => 'faculty_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemana()
    {
        return $this->hasOne(ScholarisBloqueSemanas::className(), ['id' => 'semana_id']);
    }
}
