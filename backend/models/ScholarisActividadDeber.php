<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_actividad_deber".
 *
 * @property int $id
 * @property int $actividad_id
 * @property int $alumno_id
 * @property string $archivo
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpStudent $alumno
 * @property ScholarisActividad $actividad
 */
class ScholarisActividadDeber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_actividad_deber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actividad_id', 'alumno_id', 'archivo', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['actividad_id', 'alumno_id'], 'default', 'value' => null],
            [['actividad_id', 'alumno_id'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['archivo'], 'string', 'max' => 150],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 200],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['actividad_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisActividad::className(), 'targetAttribute' => ['actividad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'actividad_id' => 'Actividad ID',
            'alumno_id' => 'Alumno ID',
            'archivo' => 'Archivo',
            'creado_por' => 'Creado Por',
            'creado_fecha' => 'Creado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumno()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'alumno_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActividad()
    {
        return $this->hasOne(ScholarisActividad::className(), ['id' => 'actividad_id']);
    }
}
