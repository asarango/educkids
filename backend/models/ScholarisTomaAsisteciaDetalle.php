<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_toma_asistecia_detalle".
 *
 * @property int $id
 * @property int $toma_id
 * @property int $alumno_id
 * @property bool $asiste
 * @property bool $atraso
 * @property bool $atraso_justificado
 * @property string $atraso_observacion_justificacion
 * @property bool $falta
 * @property bool $falta_justificada
 * @property string $falta_observacion_justificacion
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpStudent $alumno
 * @property ScholarisTomaAsistecia $toma
 */
class ScholarisTomaAsisteciaDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_toma_asistecia_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toma_id', 'alumno_id', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['toma_id', 'alumno_id'], 'default', 'value' => null],
            [['toma_id', 'alumno_id'], 'integer'],
            [['asiste', 'atraso', 'atraso_justificado', 'falta', 'falta_justificada'], 'boolean'],
            [['atraso_observacion_justificacion', 'falta_observacion_justificacion'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 150],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['toma_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisTomaAsistecia::className(), 'targetAttribute' => ['toma_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'toma_id' => 'Toma ID',
            'alumno_id' => 'Alumno ID',
            'asiste' => 'Asiste',
            'atraso' => 'Atraso',
            'atraso_justificado' => 'Atraso Justificado',
            'atraso_observacion_justificacion' => 'Atraso Observacion Justificacion',
            'falta' => 'Falta',
            'falta_justificada' => 'Falta Justificada',
            'falta_observacion_justificacion' => 'Falta Observacion Justificacion',
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
    public function getToma()
    {
        return $this->hasOne(ScholarisTomaAsistecia::className(), ['id' => 'toma_id']);
    }
}
