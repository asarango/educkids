<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_grupo_alumno_clase".
 *
 * @property int $id
 * @property int $clase_id
 * @property int $estudiante_id
 * @property string $estado
 */
class ScholarisGrupoAlumnoClase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_grupo_alumno_clase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'estudiante_id'], 'required'],
            [['clase_id', 'estudiante_id'], 'default', 'value' => null],
            [['clase_id', 'estudiante_id'], 'integer'],
            [['estado'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'estudiante_id' => 'Estudiante ID',
            'estado' => 'Estado',
        ];
    }
    
    public function getAlumno(){
        return $this->hasOne(OpStudent::className(), ['id' => 'estudiante_id']);
    }
    
    public function getClase(){
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
