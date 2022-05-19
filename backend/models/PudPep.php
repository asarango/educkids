<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pud_pep".
 *
 * @property int $id
 * @property int $planificacion_bloque_unidad_id
 * @property string $tipo
 * @property string $codigo
 * @property string $contenido
 * @property int $pertenece_indicador_id
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property PlanificacionBloquesUnidad $planificacionBloqueUnidad
 */
class PudPep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pud_pep';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planificacion_bloque_unidad_id', 'tipo', 'codigo', 'contenido', 'created_at', 'created'], 'required'],
            [['planificacion_bloque_unidad_id', 'pertenece_indicador_id'], 'default', 'value' => null],
            [['planificacion_bloque_unidad_id', 'pertenece_indicador_id'], 'integer'],
            [['contenido'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['tipo', 'codigo'], 'string', 'max' => 50],
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
            'tipo' => 'Tipo',
            'codigo' => 'Codigo',
            'contenido' => 'Contenido',
            'pertenece_indicador_id' => 'Pertenece Indicador ID',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
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
