<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_malla_disribucion".
 *
 * @property int $id
 * @property int $materia_id
 * @property string $tipo_homologacion
 * @property int $codigo_materia_source
 *
 * @property ScholarisMecV2MallaMateria $materia
 */
class ScholarisMecV2MallaDisribucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_malla_disribucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['materia_id', 'tipo_homologacion', 'codigo_materia_source'], 'required'],
            [['materia_id', 'codigo_materia_source'], 'default', 'value' => null],
            [['materia_id', 'codigo_materia_source'], 'integer'],
            [['tipo_homologacion'], 'string', 'max' => 30],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2MallaMateria::className(), 'targetAttribute' => ['materia_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'materia_id' => 'Materia ID',
            'tipo_homologacion' => 'Tipo Homologacion',
            'codigo_materia_source' => 'Codigo Materia Source',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(ScholarisMecV2MallaMateria::className(), ['id' => 'materia_id']);
    }
}
