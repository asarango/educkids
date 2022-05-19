<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_calificaciones_parcial".
 *
 * @property int $bloque_id
 * @property int $grupo_id
 * @property string $codigo_que_califica
 * @property string $quien_califica
 * @property string $tipo_calificacion
 * @property string $clase_usada
 * @property string $nota
 *
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisGrupoAlumnoClase $grupo
 */
class ScholarisCalificacionesParcial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_calificaciones_parcial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bloque_id', 'grupo_id', 'codigo_que_califica', 'quien_califica', 'tipo_calificacion', 'clase_usada'], 'required'],
            [['bloque_id', 'grupo_id'], 'default', 'value' => null],
            [['bloque_id', 'grupo_id'], 'integer'],
            [['nota'], 'number'],
            [['codigo_que_califica', 'quien_califica', 'tipo_calificacion'], 'string', 'max' => 30],
            [['clase_usada'], 'string', 'max' => 150],
            [['bloque_id', 'grupo_id', 'codigo_que_califica'], 'unique', 'targetAttribute' => ['bloque_id', 'grupo_id', 'codigo_que_califica']],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
            [['grupo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisGrupoAlumnoClase::className(), 'targetAttribute' => ['grupo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bloque_id' => 'Bloque ID',
            'grupo_id' => 'Grupo ID',
            'codigo_que_califica' => 'Codigo Que Califica',
            'quien_califica' => 'Quien Califica',
            'tipo_calificacion' => 'Tipo Calificacion',
            'clase_usada' => 'Clase Usada',
            'nota' => 'Nota',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBloque()
    {
        return $this->hasOne(ScholarisBloqueActividad::className(), ['id' => 'bloque_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo()
    {
        return $this->hasOne(ScholarisGrupoAlumnoClase::className(), ['id' => 'grupo_id']);
    }
}
