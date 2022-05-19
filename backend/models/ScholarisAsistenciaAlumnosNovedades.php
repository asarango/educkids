<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_asistencia_alumnos_novedades".
 *
 * @property int $id
 * @property int $asistencia_profesor_id
 * @property int $comportamiento_detalle_id
 * @property string $observacion
 * @property int $grupo_id
 */
class ScholarisAsistenciaAlumnosNovedades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_asistencia_alumnos_novedades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'required'],
            [['asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'default', 'value' => null],
            [['asistencia_profesor_id', 'comportamiento_detalle_id', 'grupo_id'], 'integer'],
            [['observacion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asistencia_profesor_id' => 'Asistencia Profesor ID',
            'comportamiento_detalle_id' => 'Comportamiento Detalle ID',
            'observacion' => 'Observacion',
            'grupo_id' => 'Grupo ID',
        ];
    }
    
    public function getComportamientoDetalle()
    {
        return $this->hasOne(ScholarisAsistenciaComportamientoDetalle::className(), ['id' => 'comportamiento_detalle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsistenciaProfesor()
    {
        return $this->hasOne(ScholarisAsistenciaProfesor::className(), ['id' => 'asistencia_profesor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
    
}
