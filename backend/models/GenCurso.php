<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gen_curso".
 *
 * @property int $id
 * @property int $subnivel_id
 * @property string $nombre
 * @property string $abreviatura
 * @property int $orden
 *
 * @property GenSubnivel $subnivel
 */
class GenCurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gen_curso';
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
            [['subnivel_id', 'nombre', 'abreviatura', 'orden'], 'required'],
            [['subnivel_id', 'orden'], 'default', 'value' => null],
            [['subnivel_id', 'orden'], 'integer'],
            [['nombre'], 'string', 'max' => 150],
            [['abreviatura'], 'string', 'max' => 5],
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
            'nombre' => 'Nombre',
            'abreviatura' => 'Abreviatura',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubnivel()
    {
        return $this->hasOne(GenSubnivel::className(), ['id' => 'subnivel_id']);
    }
}
