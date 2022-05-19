<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_curriculo_eje".
 *
 * @property int $id
 * @property int $curso_id
 * @property string $codigo
 * @property string $nombre
 * @property string $color
 *
 * @property CurCurriculoAmbito[] $curCurriculoAmbitos
 * @property GenCurso $curso
 */
class CurCurriculoEje extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_curriculo_eje';
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
            [['curso_id', 'codigo', 'nombre', 'color'], 'required'],
            [['curso_id'], 'default', 'value' => null],
            [['curso_id'], 'integer'],
            [['codigo', 'color'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['codigo'], 'unique'],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenCurso::className(), 'targetAttribute' => ['curso_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'curso_id' => 'Curso ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'color' => 'Color',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurCurriculoAmbitos()
    {
        return $this->hasMany(CurCurriculoAmbito::className(), ['eje_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(GenCurso::className(), ['id' => 'curso_id']);
    }
}
