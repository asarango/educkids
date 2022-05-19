<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "planificacion_bloques_unidad_subtitulo2".
 *
 * @property int $id
 * @property int $subtitulo_id
 * @property string $contenido
 * @property int $orden
 *
 * @property PlanificacionBloquesUnidadContenido[] $planificacionBloquesUnidadContenidos
 * @property PlanificacionBloquesUnidadSubtitulo $subtitulo
 */
class PlanificacionBloquesUnidadSubtitulo2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_bloques_unidad_subtitulo2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subtitulo_id', 'contenido'], 'required'],
            [['subtitulo_id', 'orden'], 'default', 'value' => null],
            [['subtitulo_id', 'orden'], 'integer'],
            [['contenido'], 'string'],
            [['subtitulo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidadSubtitulo::className(), 'targetAttribute' => ['subtitulo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subtitulo_id' => 'Subtitulo ID',
            'contenido' => 'Contenido',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloquesUnidadContenidos()
    {
        return $this->hasMany(PlanificacionBloquesUnidadContenido::className(), ['subtitulo2_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubtitulo()
    {
        return $this->hasOne(PlanificacionBloquesUnidadSubtitulo::className(), ['id' => 'subtitulo_id']);
    }
}
