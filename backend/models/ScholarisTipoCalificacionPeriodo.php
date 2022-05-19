<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_tipo_calificacion_periodo".
 *
 * @property int $id
 * @property int $scholaris_periodo_id
 * @property string $codigo
 * @property string $descripcion
 * @property string $clase_que_usa_para_calculo
 *
 * @property ScholarisPeriodo $scholarisPeriodo
 */
class ScholarisTipoCalificacionPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_tipo_calificacion_periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scholaris_periodo_id', 'codigo', 'descripcion'], 'required'],
            [['scholaris_periodo_id'], 'default', 'value' => null],
            [['scholaris_periodo_id'], 'integer'],
            [['codigo'], 'string', 'max' => 30],
            [['descripcion'], 'string', 'max' => 150],
            [['clase_que_usa_para_calculo'], 'string', 'max' => 100],
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
            'codigo' => 'Codigo',
            'descripcion' => 'Descripcion',
            'clase_que_usa_para_calculo' => 'Clase Que Usa Para Calculo',
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
