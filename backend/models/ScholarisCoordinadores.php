<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_coordinadores".
 *
 * @property int $id
 * @property int $course_id
 * @property string $nombre
 * @property string $titulo
 *
 * @property OpCourse $course
 */
class ScholarisCoordinadores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_coordinadores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'nombre'], 'required'],
            [['course_id'], 'default', 'value' => null],
            [['course_id'], 'integer'],
            [['nombre'], 'string', 'max' => 200],
            [['titulo'], 'string', 'max' => 30],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'nombre' => 'Nombre',
            'titulo' => 'Titulo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'course_id']);
    }
}
