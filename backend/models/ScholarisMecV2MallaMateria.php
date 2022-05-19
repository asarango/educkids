<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_malla_materia".
 *
 * @property int $id
 * @property string $codigo
 * @property int $asignatura_id
 * @property int $area_id
 * @property bool $imprime
 * @property bool $es_cuantitativa
 *
 * @property ScholarisMecV2Asignatura $asignatura
 * @property ScholarisMecV2MallaArea $area
 */
class ScholarisMecV2MallaMateria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_malla_materia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'asignatura_id', 'area_id', 'imprime', 'es_cuantitativa','tipo','orden'], 'required'],
            [['asignatura_id', 'area_id'], 'default', 'value' => null],
            [['asignatura_id', 'area_id','orden'], 'integer'],
            [['imprime', 'es_cuantitativa', 'promedia'], 'boolean'],
            [['codigo','tipo'], 'string', 'max' => 30],
            [['codigo'], 'unique'],
            [['asignatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Asignatura::className(), 'targetAttribute' => ['asignatura_id' => 'id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2MallaArea::className(), 'targetAttribute' => ['area_id' => 'id']],
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
            'area_id' => 'Area ID',
            'imprime' => 'Imprime',
            'es_cuantitativa' => 'Es Cuantitativa',
            'tipo' => 'Tipo',
            'orden' => 'Orden',
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
    public function getArea()
    {
        return $this->hasOne(ScholarisMecV2MallaArea::className(), ['id' => 'area_id']);
    }
}
