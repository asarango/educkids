<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_curriculo_ambito".
 *
 * @property int $id
 * @property int $eje_id
 * @property string $codigo
 * @property string $nombre
 *
 * @property CurCurriculoEje $eje
 * @property CurCurriculoDestreza[] $curCurriculoDestrezas
 */
class CurCurriculoAmbito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_curriculo_ambito';
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
            [['eje_id', 'codigo', 'nombre'], 'required'],
            [['eje_id'], 'default', 'value' => null],
            [['eje_id'], 'integer'],
            [['codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['codigo'], 'unique'],
            [['eje_id'], 'exist', 'skipOnError' => true, 'targetClass' => CurCurriculoEje::className(), 'targetAttribute' => ['eje_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eje_id' => 'Eje ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEje()
    {
        return $this->hasOne(CurCurriculoEje::className(), ['id' => 'eje_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurCurriculoDestrezas()
    {
        return $this->hasMany(CurCurriculoDestreza::className(), ['ambito_id' => 'id']);
    }
}
