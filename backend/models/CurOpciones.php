<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_opciones".
 *
 * @property int $id
 * @property string $codigo
 * @property string $detalle
 */
class CurOpciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_opciones';
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
            [['codigo', 'detalle'], 'required'],
            [['detalle'], 'string'],
            [['codigo'], 'string', 'max' => 30],
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
            'detalle' => 'Detalle',
        ];
    }
}
