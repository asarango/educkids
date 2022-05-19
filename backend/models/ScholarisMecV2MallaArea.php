<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_malla_area".
 *
 * @property int $id
 * @property string $codigo
 * @property int $asignatura_id
 * @property int $malla_id
 * @property bool $imprime
 * @property bool $es_cuantitativa
 *
 * @property ScholarisMecV2Asignatura $asignatura
 * @property ScholarisMecV2Malla $malla
 * @property ScholarisMecV2MallaMateria[] $scholarisMecV2MallaMaterias
 */
class ScholarisMecV2MallaArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_malla_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'asignatura_id', 'malla_id', 'imprime', 'es_cuantitativa','orden'], 'required'],
            [['asignatura_id', 'malla_id'], 'default', 'value' => null],
            [['asignatura_id', 'malla_id','orden'], 'integer'],
            [['imprime', 'es_cuantitativa', 'promedia'], 'boolean'],
            [['codigo'], 'string', 'max' => 30],
            [['tipo'], 'string', 'max' => 20],
            [['codigo'], 'unique'],
            [['asignatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Asignatura::className(), 'targetAttribute' => ['asignatura_id' => 'id']],
            [['malla_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Malla::className(), 'targetAttribute' => ['malla_id' => 'id']],
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
            'asignatura_id' => 'Asignatura ID',
            'malla_id' => 'Malla ID',
            'imprime' => 'Imprime',
            'es_cuantitativa' => 'Es Cuantitativa',
            'orden' => 'orden',
            'tipo' => 'Tipo',
            'promedia' => 'Promedia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignatura()
    {
        return $this->hasOne(ScholarisMecV2Asignatura::className(), ['id' => 'asignatura_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMalla()
    {
        return $this->hasOne(ScholarisMecV2Malla::className(), ['id' => 'malla_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2MallaMaterias()
    {
        return $this->hasMany(ScholarisMecV2MallaMateria::className(), ['area_id' => 'id']);
    }
}
