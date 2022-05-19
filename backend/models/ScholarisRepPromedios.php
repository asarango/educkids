<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_rep_promedios".
 *
 * @property string $codigo
 * @property int $paralelo_id
 * @property int $alumno_id
 * @property string $nota_promedio
 * @property string $nota_comportamiento
 * @property string $usuario
 *
 * @property OpCourseParalelo $paralelo
 * @property OpStudent $alumno
 */
class ScholarisRepPromedios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_rep_promedios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'paralelo_id', 'alumno_id', 'usuario'], 'required'],
            [['paralelo_id', 'alumno_id'], 'default', 'value' => null],
            [['paralelo_id', 'alumno_id'], 'integer'],
            [['nota_promedio', 'nota_comportamiento'], 'number'],
            [['codigo'], 'string', 'max' => 30],
            [['usuario'], 'string', 'max' => 150],
            [['codigo'], 'unique'],
            [['paralelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourseParalelo::className(), 'targetAttribute' => ['paralelo_id' => 'id']],
            [['alumno_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['alumno_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'paralelo_id' => 'Paralelo ID',
            'alumno_id' => 'Alumno ID',
            'nota_promedio' => 'Nota Promedio',
            'nota_comportamiento' => 'Nota Comportamiento',
            'usuario' => 'Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParalelo()
    {
        return $this->hasOne(OpCourseParalelo::className(), ['id' => 'paralelo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumno()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'alumno_id']);
    }
}
