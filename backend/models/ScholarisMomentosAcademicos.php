<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_momentos_academicos".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 *
 * @property ScholarisActividad[] $scholarisActividads
 */
class ScholarisMomentosAcademicos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_momentos_academicos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre'], 'required'],
            [['codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
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
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividads()
    {
        return $this->hasMany(ScholarisActividad::className(), ['momento_id' => 'id']);
    }
}
