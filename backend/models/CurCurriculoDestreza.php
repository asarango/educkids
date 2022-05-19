<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_curriculo_destreza".
 *
 * @property int $id
 * @property int $ambito_id
 * @property string $codigo
 * @property string $nombre
 * @property bool $imprescindible
 *
 * @property CurCurriculoAmbito $ambito
 */
class CurCurriculoDestreza extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_curriculo_destreza';
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
            [['ambito_id', 'codigo', 'nombre', 'imprescindible'], 'required'],
            [['ambito_id'], 'default', 'value' => null],
            [['ambito_id'], 'integer'],
            [['nombre'], 'string'],
            [['imprescindible'], 'boolean'],
            [['codigo'], 'string', 'max' => 30],
            [['codigo'], 'unique'],
            [['ambito_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurCurriculoAmbito::className(), 'targetAttribute' => ['ambito_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ambito_id' => 'Ambito ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'imprescindible' => 'Imprescindible',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmbito()
    {
        return $this->hasOne(CurCurriculoAmbito::className(), ['id' => 'ambito_id']);
    }
}
