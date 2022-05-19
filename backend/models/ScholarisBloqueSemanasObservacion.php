<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_semanas_observacion".
 *
 * @property int $id
 * @property int $semana_id
 * @property int $comparte_bloque
 * @property int $usuario
 * @property string $creado_fecha
 * @property int $creado_por
 * @property string $actualizado_fecha
 * @property int $actualizado_por
 * @property string $observacion
 *
 * @property ScholarisBloqueSemanas $semana
 */
class ScholarisBloqueSemanasObservacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_semanas_observacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semana_id', 'comparte_bloque', 'usuario', 'creado_fecha', 'creado_por', 'actualizado_fecha', 'actualizado_por'], 'required'],
            [['semana_id', 'comparte_bloque', 'usuario', 'creado_por', 'actualizado_por'], 'default', 'value' => null],
            [['semana_id', 'comparte_bloque', 'usuario', 'creado_por', 'actualizado_por'], 'integer'],
            [['creado_fecha', 'actualizado_fecha'], 'safe'],
            [['observacion'], 'string'],
            [['semana_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisBloqueSemanas::className(), 'targetAttribute' => ['semana_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'semana_id' => 'Semana ID',
            'comparte_bloque' => 'Comparte Bloque',
            'usuario' => 'Usuario',
            'creado_fecha' => 'Creado Fecha',
            'creado_por' => 'Creado Por',
            'actualizado_fecha' => 'Actualizado Fecha',
            'actualizado_por' => 'Actualizado Por',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemana()
    {
        return $this->hasOne(ScholarisBloqueSemanas::className(), ['id' => 'semana_id']);
    }
}
