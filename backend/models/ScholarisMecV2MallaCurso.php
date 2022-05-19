<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_malla_curso".
 *
 * @property int $malla_id
 * @property int $curso_id
 *
 * @property OpCourse $curso
 * @property ScholarisMecV2Malla $malla
 */
class ScholarisMecV2MallaCurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_malla_curso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_id', 'curso_id'], 'required'],
            [['malla_id', 'curso_id'], 'default', 'value' => null],
            [['malla_id', 'curso_id'], 'integer'],
            [['malla_id', 'curso_id'], 'unique', 'targetAttribute' => ['malla_id', 'curso_id']],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_id' => 'id']],
            [['malla_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMecV2Malla::className(), 'targetAttribute' => ['malla_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'malla_id' => 'Malla ID',
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
    public function getMalla()
    {
        return $this->hasOne(ScholarisMecV2Malla::className(), ['id' => 'malla_id']);
    }
}
