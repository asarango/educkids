<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_horariov2_cabecera".
 *
 * @property int $id
 * @property string $descripcion
 *
 * @property ScholarisHorariov2Detalle[] $scholarisHorariov2Detalles
 */
class ScholarisHorariov2Cabecera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_horariov2_cabecera';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion','periodo_id'], 'required'],
            [['descripcion'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'periodo_id' => 'Periodo Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisHorariov2Detalles()
    {
        return $this->hasMany(ScholarisHorariov2Detalle::className(), ['cabecera_id' => 'id']);
    }
    
    public function getPeriodo(){
        return $this->hasOne(ScholarisPeriodo::className(), ['periodo_id' => 'id']);
    } 
}
