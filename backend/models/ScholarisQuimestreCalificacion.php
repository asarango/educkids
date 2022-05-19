<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_quimestre_calificacion".
 *
 * @property int $inscription_id
 * @property int $quimestre_calificacion_id
 * @property int $rubrica_id
 *
 * @property OpStudentInscription $inscription
 * @property ScholarisQuimestreTipoCalificacion $quimestreCalificacion
 * @property ScholarisRubricasCalificaciones $rubrica
 */
class ScholarisQuimestreCalificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_quimestre_calificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inscription_id', 'quimestre_calificacion_id', 'rubrica_id'], 'required'],
            [['inscription_id', 'quimestre_calificacion_id', 'rubrica_id'], 'default', 'value' => null],
            [['inscription_id', 'quimestre_calificacion_id', 'rubrica_id'], 'integer'],
            [['inscription_id', 'quimestre_calificacion_id', 'rubrica_id'], 'unique', 'targetAttribute' => ['inscription_id', 'quimestre_calificacion_id', 'rubrica_id']],
            [['inscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentInscription::className(), 'targetAttribute' => ['inscription_id' => 'id']],
            [['quimestre_calificacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisQuimestreTipoCalificacion::className(), 'targetAttribute' => ['quimestre_calificacion_id' => 'id']],
            [['rubrica_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisRubricasCalificaciones::className(), 'targetAttribute' => ['rubrica_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'inscription_id' => 'Inscription ID',
            'quimestre_calificacion_id' => 'Quimestre Calificacion ID',
            'rubrica_id' => 'Rubrica ID',
        ];
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
    public function getQuimestreCalificacion()
    {
        return $this->hasOne(ScholarisQuimestreTipoCalificacion::className(), ['id' => 'quimestre_calificacion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRubrica()
    {
        return $this->hasOne(ScholarisRubricasCalificaciones::className(), ['id' => 'rubrica_id']);
    }
}
