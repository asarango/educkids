<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_quimestre_tipo_calificacion".
 *
 * @property int $id
 * @property string $codigo
 * @property int $quimestre_id
 * @property int $periodo_scholaris_id
 * @property string $reglas_calificacion
 * @property string $calificacion_portafolio
 *
 * @property ScholarisQuimestreCalificacion[] $scholarisQuimestreCalificacions
 * @property ScholarisPeriodo $periodoScholaris
 * @property ScholarisQuimestre $quimestre
 */
class ScholarisQuimestreTipoCalificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_quimestre_tipo_calificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'quimestre_id', 'periodo_scholaris_id', 'reglas_calificacion'], 'required'],
            [['quimestre_id', 'periodo_scholaris_id'], 'default', 'value' => null],
            [['quimestre_id', 'periodo_scholaris_id'], 'integer'],
            [['reglas_calificacion'], 'string'],
            [['calificacion_portafolio'], 'number'],
            [['codigo'], 'string', 'max' => 30],
            [['periodo_scholaris_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_scholaris_id' => 'id']],
            [['quimestre_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisQuimestre::className(), 'targetAttribute' => ['quimestre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'quimestre_id' => 'Quimestre ID',
            'periodo_scholaris_id' => 'Periodo Scholaris ID',
            'reglas_calificacion' => 'Reglas Calificacion',
            'calificacion_portafolio' => 'Calificacion Portafolio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisQuimestreCalificacions()
    {
        return $this->hasMany(ScholarisQuimestreCalificacion::className(), ['quimestre_calificacion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoScholaris()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_scholaris_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuimestre()
    {
        return $this->hasOne(ScholarisQuimestre::className(), ['id' => 'quimestre_id']);
    }
}
