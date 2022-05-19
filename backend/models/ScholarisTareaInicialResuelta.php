<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tarea_inicial_resuelta".
 *
 * @property int $id
 * @property int $tarea_inicial_id
 * @property int $alumno_id
 * @property string $archivo
 * @property string $calificacion
 * @property string $detalle_calificacion
 * @property string $creado_por
 * @property string $creado_fecha
 * @property string $actualizado_por
 * @property string $actualizado_fecha
 *
 * @property OpStudent $alumno
 * @property ScholarisTareaInicial $tareaInicial
 */
class ScholarisTareaInicialResuelta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tarea_inicial_resuelta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tarea_inicial_id', 'alumno_id', 'archivo', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'required'],
            [['tarea_inicial_id', 'alumno_id'], 'default', 'value' => null],
            [['tarea_inicial_id', 'alumno_id'], 'integer'],
            [['detalle_calificacion'], 'string'],
            [['observacion_profesor'], 'string'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['archivo'], 'string', 'max' => 80],
            [['calificacion'], 'string', 'max' => 10],
            [['creado_por', 'actualizado_por'], 'string', 'max' => 200],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
            [['tarea_inicial_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisTareaInicial::className(), 'targetAttribute' => ['tarea_inicial_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tarea_inicial_id' => 'Tarea Inicial ID',
            'alumno_id' => 'Alumno ID',
            'archivo' => 'Archivo',
            'calificacion' => 'Calificacion',
            'detalle_calificacion' => 'Detalle Calificacion',
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
    public function getTareaInicial()
    {
        return $this->hasOne(ScholarisTareaInicial::className(), ['id' => 'tarea_inicial_id']);
    }
}
