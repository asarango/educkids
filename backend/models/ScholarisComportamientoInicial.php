<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_comportamiento_inicial".
 *
 * @property int $id
 * @property int $inscription_id
 * @property int $faculty_id
 * @property string $q1
 * @property string $q2
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpFaculty $faculty
 * @property OpStudentInscription $inscription
 */
class ScholarisComportamientoInicial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_comportamiento_inicial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inscription_id', 'faculty_id', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['inscription_id', 'faculty_id'], 'default', 'value' => null],
            [['inscription_id', 'faculty_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['q1'], 'string', 'max' => 1],
            [['q2'], 'string', 'max' => 2],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['faculty_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpFaculty::className(), 'targetAttribute' => ['faculty_id' => 'id']],
            [['inscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentInscription::className(), 'targetAttribute' => ['inscription_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inscription_id' => 'Inscription ID',
            'faculty_id' => 'Faculty ID',
            'q1' => 'Q1',
            'q2' => 'Q2',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
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
    public function getInscription()
    {
        return $this->hasOne(OpStudentInscription::className(), ['id' => 'inscription_id']);
    }
}
