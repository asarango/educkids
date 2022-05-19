<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_bloque_como_califica".
 *
 * @property string $codigo
 * @property string $descripcion_calificacion
 *
 * @property ScholarisBloqueActividad[] $scholarisBloqueActividads
 */
class ScholarisBloqueComoCalifica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_bloque_como_califica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'descripcion_calificacion'], 'required'],
            [['descripcion_calificacion'], 'string'],
            [['codigo'], 'string', 'max' => 30],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'descripcion_calificacion' => 'Descripcion Calificacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisBloqueActividads()
    {
        return $this->hasMany(ScholarisBloqueActividad::className(), ['codigo_tipo_calificacion' => 'codigo']);
    }
}
