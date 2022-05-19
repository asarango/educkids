<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_area".
 *
 * @property int $id
 * @property int $malla_id
 * @property string $tipo
 * @property string $nombre
 * @property string $codigo
 *
 * @property ScholarisMecV2Malla $malla
 * @property ScholarisMecV2Materia[] $scholarisMecV2Materias
 */
class ScholarisMecV2Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_id', 'tipo', 'nombre', 'codigo'], 'required'],
            [['malla_id'], 'default', 'value' => null],
            [['malla_id'], 'integer'],
            [['tipo', 'codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['malla_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Malla::className(), 'targetAttribute' => ['malla_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'malla_id' => 'Malla ID',
            'tipo' => 'Tipo',
            'nombre' => 'Nombre',
            'codigo' => 'Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMalla()
    {
        return $this->hasOne(ScholarisMecV2Malla::className(), ['id' => 'malla_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2Materias()
    {
        return $this->hasMany(ScholarisMecV2Materia::className(), ['malla_area_id' => 'id']);
    }
}
