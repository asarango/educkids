<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_promedios_anuales".
 *
 * @property int $alumno_inscription_id
 * @property int $scholaris_periodo_id
 * @property string $nota_aprovechamiento
 * @property string $nota_comportamiento
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpStudentInscription $alumnoInscription
 * @property ScholarisPeriodo $scholarisPeriodo
 */
class ScholarisPromediosAnuales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_promedios_anuales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alumno_inscription_id', 'scholaris_periodo_id', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['alumno_inscription_id', 'scholaris_periodo_id'], 'default', 'value' => null],
            [['alumno_inscription_id', 'scholaris_periodo_id'], 'integer'],
            [['nota_aprovechamiento'], 'number'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['nota_comportamiento'], 'string', 'max' => 1],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['alumno_inscription_id', 'scholaris_periodo_id'], 'unique', 'targetAttribute' => ['alumno_inscription_id', 'scholaris_periodo_id']],
            [['alumno_inscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudentInscription::className(), 'targetAttribute' => ['alumno_inscription_id' => 'id']],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'alumno_inscription_id' => 'Alumno Inscription ID',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
            'nota_aprovechamiento' => 'Nota Aprovechamiento',
            'nota_comportamiento' => 'Nota Comportamiento',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscription()
    {
        return $this->hasOne(OpStudentInscription::className(), ['id' => 'alumno_inscription_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }
}
