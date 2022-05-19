<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_fechas_cierre_anio".
 *
 * @property int $id
 * @property int $scholaris_periodo_id
 * @property string $fecha
 * @property string $observacion
 *
 * @property ScholarisPeriodo $scholarisPeriodo
 */
class ScholarisFechasCierreAnio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_fechas_cierre_anio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scholaris_periodo_id', 'fecha'], 'required'],
            [['scholaris_periodo_id'], 'default', 'value' => null],
            [['scholaris_periodo_id'], 'integer'],
            [['observacion'], 'string'],
            [['fecha'], 'string', 'max' => 255],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
            'fecha' => 'Fecha',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }
}
