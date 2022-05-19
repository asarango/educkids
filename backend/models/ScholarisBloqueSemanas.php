<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_semanas".
 *
 * @property int $id
 * @property int $bloque_id
 * @property int $semana_numero
 * @property string $nombre_semana
 * @property string $fecha_inicio
 * @property string $fecha_finaliza
 * @property int $estado
 * @property string $fecha_limite_inicia
 * @property string $fecha_limite_tope
 *
 * @property ScholarisActividadIndagacionCurso[] $scholarisActividadIndagacionCursos
 * @property ScholarisBloqueActividad $bloque
 * @property ScholarisBloqueSemanasObservacion[] $scholarisBloqueSemanasObservacions
 */
class ScholarisBloqueSemanas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_semanas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bloque_id', 'semana_numero', 'nombre_semana', 'fecha_inicio', 'fecha_finaliza', 'estado'], 'required'],
            [['bloque_id', 'semana_numero', 'estado'], 'default', 'value' => null],
            [['bloque_id', 'semana_numero', 'estado'], 'integer'],
            [['fecha_inicio', 'fecha_finaliza', 'fecha_limite_inicia', 'fecha_limite_tope'], 'safe'],
            [['nombre_semana'], 'string', 'max' => 80],
            [['bloque_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueActividad::className(), 'targetAttribute' => ['bloque_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bloque_id' => 'Bloque ID',
            'semana_numero' => 'Semana Numero',
            'nombre_semana' => 'Nombre Semana',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_finaliza' => 'Fecha Finaliza',
            'estado' => 'Estado',
            'fecha_limite_inicia' => 'Fecha Limite Inicia',
            'fecha_limite_tope' => 'Fecha Limite Tope',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadIndagacionCursos()
    {
        return $this->hasMany(ScholarisActividadIndagacionCurso::className(), ['semana_id' => 'id']);
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
    public function getScholarisBloqueSemanasObservacions()
    {
        return $this->hasMany(ScholarisBloqueSemanasObservacion::className(), ['semana_id' => 'id']);
    }
}
