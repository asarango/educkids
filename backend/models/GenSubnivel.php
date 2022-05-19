<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gen_subnivel".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property GenCurso[] $genCursos
 * @property GenMallaArea[] $genMallaAreas
 */
class GenSubnivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gen_subnivel';
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
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 150],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenCursos()
    {
        return $this->hasMany(GenCurso::className(), ['subnivel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenMallaAreas()
    {
        return $this->hasMany(GenMallaArea::className(), ['subnivel_id' => 'id']);
    }
}
