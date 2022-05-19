<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_faltas_y_atrasos_parcial".
 *
 * @property int $id
 * @property int $alumno_id
 * @property int $bloque_id
 * @property int $atrasos
 * @property int $faltas_justificadas
 * @property int $faltas_injustificadas
 * @property string $observacion
 *
 * @property OpStudent $alumno
 * @property ScholarisBloqueActividad $bloque
 */
class ScholarisFaltasYAtrasosParcial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_faltas_y_atrasos_parcial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alumno_id', 'bloque_id'], 'required'],
            [['alumno_id', 'bloque_id', 'atrasos', 'faltas_justificadas', 'faltas_injustificadas'], 'default', 'value' => null],
            [['alumno_id', 'bloque_id', 'atrasos', 'faltas_justificadas', 'faltas_injustificadas'], 'integer'],
            [['observacion'], 'string'],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alumno_id' => 'Alumno ID',
            'bloque_id' => 'Bloque ID',
            'atrasos' => 'Atrasos',
            'faltas_justificadas' => 'Faltas Justificadas',
            'faltas_injustificadas' => 'Faltas Injustificadas',
            'observacion' => 'Observacion',
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
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }
}
