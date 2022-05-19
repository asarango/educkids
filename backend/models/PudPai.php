<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_pai".
 *
 * @property int $id
 * @property int $planificacion_bloque_unidad_id
 * @property int $seccion_numero
 * @property string $tipo
 * @property int $criterio_id
 * @property string $titulo
 * @property string $contenido
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 * @property string $respuesta
 *
 * @property PlanificacionBloquesUnidad $planificacionBloqueUnidad
 */
class PudPai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_pai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planificacion_bloque_unidad_id', 'seccion_numero', 'tipo', 'contenido', 'created_at', 'created'], 'required'],
            [['planificacion_bloque_unidad_id', 'seccion_numero', 'criterio_id'], 'default', 'value' => null],
            [['planificacion_bloque_unidad_id', 'seccion_numero', 'criterio_id'], 'integer'],
            [['contenido', 'respuesta'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['tipo', 'titulo'], 'string', 'max' => 50],
            [['created', 'updated'], 'string', 'max' => 200],
            [['planificacion_bloque_unidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionBloquesUnidad::className(), 'targetAttribute' => ['planificacion_bloque_unidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planificacion_bloque_unidad_id' => 'Planificacion Bloque Unidad ID',
            'seccion_numero' => 'Seccion Numero',
            'tipo' => 'Tipo',
            'criterio_id' => 'Criterio ID',
            'titulo' => 'Titulo',
            'contenido' => 'Contenido',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
            'respuesta' => 'Respuesta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloqueUnidad()
    {
        return $this->hasOne(PlanificacionBloquesUnidad::className(), ['id' => 'planificacion_bloque_unidad_id']);
    }
}
