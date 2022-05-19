<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_malla".
 *
 * @property int $id
 * @property string $codigo
 * @property int $periodo_id
 * @property int $section_id
 * @property string $nombre_malla
 * @property int $tipo_uso
 *
 * @property OpSection $section
 * @property ScholarisPeriodo $periodo
 */
class ScholarisMalla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_malla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periodo_id', 'section_id', 'nombre_malla', 'tipo_uso'], 'required'],
            [['periodo_id', 'section_id', 'tipo_uso'], 'default', 'value' => null],
            [['periodo_id', 'section_id', 'tipo_uso'], 'integer'],
            [['codigo'], 'string', 'max' => 30],
            [['nombre_malla'], 'string', 'max' => 150],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpSection::className(), 'targetAttribute' => ['section_id' => 'id']],
            [['periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['periodo_id' => 'id']],
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
            'periodo_id' => 'Periodo ID',
            'section_id' => 'Section ID',
            'nombre_malla' => 'Nombre Malla',
            'tipo_uso' => 'Tipo Uso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(OpSection::className(), ['id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }
}
