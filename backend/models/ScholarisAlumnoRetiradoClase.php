<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_alumno_retirado_clase".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $alumno_id
 * @property string $fecha_retiro
 * @property string $motivo_retiro
 * @property string $usuario
 *
 * @property OpStudent $alumno
 * @property ScholarisClase $clase
 */
class ScholarisAlumnoRetiradoClase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_alumno_retirado_clase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'alumno_id', 'fecha_retiro', 'motivo_retiro', 'usuario'], 'required'],
            [['clase_id', 'alumno_id'], 'default', 'value' => null],
            [['clase_id', 'alumno_id'], 'integer'],
            [['fecha_retiro'], 'safe'],
            [['motivo_retiro'], 'string'],
            [['usuario'], 'string', 'max' => 30],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'alumno_id' => 'Alumno ID',
            'fecha_retiro' => 'Fecha Retiro',
            'motivo_retiro' => 'Motivo Retiro',
            'usuario' => 'Usuario',
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
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
