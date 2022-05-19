<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_distribucion".
 *
 * @property int $id
 * @property int $materia_id
 * @property int $curso_id
 *
 * @property OpCourse $curso
 * @property ScholarisMecV2Materia $materia
 */
class ScholarisMecV2Distribucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_distribucion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['materia_id', 'curso_id'], 'required'],
            [['materia_id', 'curso_id'], 'default', 'value' => null],
            [['materia_id', 'curso_id'], 'integer'],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_id' => 'id']],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Materia::className(), 'targetAttribute' => ['materia_id' => 'id']],
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
            'curso_id' => 'Curso ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'curso_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(ScholarisMecV2Materia::className(), ['id' => 'materia_id']);
    }
}
