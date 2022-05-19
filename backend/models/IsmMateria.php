<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ism_materia".
 *
 * @property int $id
 * @property string $nombre
 * @property string $siglas
 *
 * @property IsmAreaMateria[] $ismAreaMaterias
 */
class IsmMateria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ism_materia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'siglas'], 'required'],
            [['nombre'], 'string', 'max' => 100],
            [['siglas'], 'string', 'max' => 10],
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
            'siglas' => 'Siglas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsmAreaMaterias()
    {
        return $this->hasMany(IsmAreaMateria::className(), ['materia_id' => 'id']);
    }
}
