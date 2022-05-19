<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gen_asignaturas".
 *
 * @property int $id
 * @property string $codigo
 * @property string $tipo
 * @property string $nombre
 * @property string $color
 *
 * @property GenMallaArea[] $genMallaAreas
 * @property GenMallaMateria[] $genMallaMaterias
 */
class GenAsignaturas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gen_asignaturas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'tipo', 'nombre', 'color'], 'required'],
            [['codigo'], 'string', 'max' => 10],
            [['tipo', 'color'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 150],
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
            'tipo' => 'Tipo',
            'nombre' => 'Nombre',
            'color' => 'Color',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenMallaAreas()
    {
        return $this->hasMany(GenMallaArea::className(), ['area_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenMallaMaterias()
    {
        return $this->hasMany(GenMallaMateria::className(), ['materia_id' => 'id']);
    }
}
