<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_horariov2_detalle".
 *
 * @property int $id
 * @property int $cabecera_id
 * @property int $hora_id
 * @property int $dia_id
 *
 * @property ScholarisHorariov2Cabecera $cabecera
 * @property ScholarisHorariov2Dia $dia
 * @property ScholarisHorariov2Hora $hora
 */
class ScholarisHorariov2Detalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_horariov2_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cabecera_id', 'hora_id', 'dia_id'], 'required'],
            [['cabecera_id', 'hora_id', 'dia_id'], 'default', 'value' => null],
            [['cabecera_id', 'hora_id', 'dia_id'], 'integer'],
            [['cabecera_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Cabecera::className(), 'targetAttribute' => ['cabecera_id' => 'id']],
            [['dia_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Dia::className(), 'targetAttribute' => ['dia_id' => 'id']],
            [['hora_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisHorariov2Hora::className(), 'targetAttribute' => ['hora_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cabecera_id' => 'Cabecera ID',
            'hora_id' => 'Hora ID',
            'dia_id' => 'Dia ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCabecera()
    {
        return $this->hasOne(ScholarisHorariov2Cabecera::className(), ['id' => 'cabecera_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDia()
    {
        return $this->hasOne(ScholarisHorariov2Dia::className(), ['id' => 'dia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHora()
    {
        return $this->hasOne(ScholarisHorariov2Hora::className(), ['id' => 'hora_id']);
    }
}
