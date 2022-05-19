<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_comportamiento".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property ScholarisAsistenciaComportamientoDetalle[] $scholarisAsistenciaComportamientoDetalles
 */
class ScholarisAsistenciaComportamiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_comportamiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaComportamientoDetalles()
    {
        return $this->hasMany(ScholarisAsistenciaComportamientoDetalle::className(), ['comportamiento_id' => 'id']);
    }
}
