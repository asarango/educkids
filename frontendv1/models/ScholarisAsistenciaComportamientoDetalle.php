<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_comportamiento_detalle".
 *
 * @property int $id
 * @property int $comportamiento_id
 * @property string $codigo
 * @property string $nombre
 *
 * @property ScholarisAsistenciaAlumnosNovedades[] $scholarisAsistenciaAlumnosNovedades
 * @property ScholarisAsistenciaComportamiento $comportamiento
 */
class ScholarisAsistenciaComportamientoDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_comportamiento_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comportamiento_id', 'codigo', 'nombre'], 'required'],
            [['comportamiento_id'], 'default', 'value' => null],
            [['comportamiento_id'], 'integer'],
            [['codigo'], 'string', 'max' => 5],
            [['nombre'], 'string', 'max' => 150],
            [['comportamiento_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisAsistenciaComportamiento::className(), 'targetAttribute' => ['comportamiento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comportamiento_id' => 'Comportamiento ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisAsistenciaAlumnosNovedades()
    {
        return $this->hasMany(ScholarisAsistenciaAlumnosNovedades::className(), ['comportamiento_detalle_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComportamiento()
    {
        return $this->hasOne(ScholarisAsistenciaComportamiento::className(), ['id' => 'comportamiento_id']);
    }
}
