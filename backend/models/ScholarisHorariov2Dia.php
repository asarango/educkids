<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_horariov2_dia".
 *
 * @property int $id
 * @property string $nombre
 * @property int $numero
 *
 * @property ScholarisHorariov2Detalle[] $scholarisHorariov2Detalles
 */
class ScholarisHorariov2Dia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_horariov2_dia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'numero'], 'required'],
            [['numero'], 'default', 'value' => null],
            [['numero'], 'integer'],
            [['nombre'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'numero' => 'Numero',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisHorariov2Detalles()
    {
        return $this->hasMany(ScholarisHorariov2Detalle::className(), ['dia_id' => 'id']);
    }
}
