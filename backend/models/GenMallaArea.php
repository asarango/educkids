<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gen_malla_area".
 *
 * @property int $id
 * @property int $subnivel_id
 * @property string $tipo_area
 * @property int $area_id
 * @property int $orden
 *
 * @property GenAsignaturas $area
 * @property GenSubnivel $subnivel
 * @property GenMallaMateria[] $genMallaMaterias
 */
class GenMallaArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gen_malla_area';
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
            [['subnivel_id', 'tipo_area', 'area_id', 'orden'], 'required'],
            [['subnivel_id', 'area_id', 'orden'], 'default', 'value' => null],
            [['subnivel_id', 'area_id', 'orden'], 'integer'],
            [['tipo_area'], 'string', 'max' => 50],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenAsignaturas::className(), 'targetAttribute' => ['area_id' => 'id']],
            [['subnivel_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenSubnivel::className(), 'targetAttribute' => ['subnivel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subnivel_id' => 'Subnivel ID',
            'tipo_area' => 'Tipo Area',
            'area_id' => 'Area ID',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(GenAsignaturas::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubnivel()
    {
        return $this->hasOne(GenSubnivel::className(), ['id' => 'subnivel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenMallaMaterias()
    {
        return $this->hasMany(GenMallaMateria::className(), ['malla_area_id' => 'id']);
    }
}
