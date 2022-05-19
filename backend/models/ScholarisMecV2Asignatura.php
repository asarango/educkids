<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_asignatura".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property string $tipo
 *
 * @property ScholarisMecV2MallaArea[] $scholarisMecV2MallaAreas
 * @property ScholarisMecV2MallaMateria[] $scholarisMecV2MallaMaterias
 */
class ScholarisMecV2Asignatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_asignatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre', 'tipo'], 'required'],
            [['codigo', 'tipo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['codigo'], 'unique'],
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
            'tipo' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2MallaAreas()
    {
        return $this->hasMany(ScholarisMecV2MallaArea::className(), ['asignatura_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2MallaMaterias()
    {
        return $this->hasMany(ScholarisMecV2MallaMateria::className(), ['asignatura_id' => 'id']);
    }
}
