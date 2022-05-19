<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_materia".
 *
 * @property int $id
 * @property int $malla_area_id
 * @property string $tipo
 * @property string $nombre
 * @property string $codigo
 *
 * @property ScholarisMecV2Area $mallaArea
 */
class ScholarisMecV2Materia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_materia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_area_id', 'tipo', 'nombre', 'codigo'], 'required'],
            [['malla_area_id'], 'default', 'value' => null],
            [['malla_area_id'], 'integer'],
            [['tipo', 'codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['malla_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Area::className(), 'targetAttribute' => ['malla_area_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'malla_area_id' => 'Malla Area ID',
            'tipo' => 'Tipo',
            'nombre' => 'Nombre',
            'codigo' => 'Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaArea()
    {
        return $this->hasOne(ScholarisMecV2Area::className(), ['id' => 'malla_area_id']);
    }
}
