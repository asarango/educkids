<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_bloques_unidad_contenido".
 *
 * @property int $id
 * @property int $subtitulo2_id
 * @property string $contenido
 * @property int $orden
 *
 * @property PlanificacionBloquesUnidadSubtitulo2 $subtitulo2
 */
class PlanificacionBloquesUnidadContenido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_bloques_unidad_contenido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subtitulo2_id', 'contenido'], 'required'],
            [['subtitulo2_id', 'orden'], 'default', 'value' => null],
            [['subtitulo2_id', 'orden'], 'integer'],
            [['contenido'], 'string'],
            [['subtitulo2_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidadSubtitulo2::className(), 'targetAttribute' => ['subtitulo2_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subtitulo2_id' => 'Subtitulo2 ID',
            'contenido' => 'Contenido',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubtitulo2()
    {
        return $this->hasOne(PlanificacionBloquesUnidadSubtitulo2::className(), ['id' => 'subtitulo2_id']);
    }
}
