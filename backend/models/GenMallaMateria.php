<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "gen_malla_materia".
 *
 * @property int $id
 * @property int $malla_area_id
 * @property int $materia_id
 * @property string $tipo_materia
 * @property bool $obligatoria
 * @property int $carga_curso_1
 * @property int $carga_curso_2
 * @property int $carga_curso_3
 * @property int $orden
 *
 * @property CurCurriculo[] $curCurriculos
 * @property GenAsignaturas $materia
 * @property GenMallaArea $mallaArea
 */
class GenMallaMateria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gen_malla_materia';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['malla_area_id', 'materia_id', 'tipo_materia', 'obligatoria', 'carga_curso_1', 'carga_curso_2', 'carga_curso_3', 'orden'], 'required'],
            [['malla_area_id', 'materia_id', 'carga_curso_1', 'carga_curso_2', 'carga_curso_3', 'orden'], 'default', 'value' => null],
            [['malla_area_id', 'materia_id', 'carga_curso_1', 'carga_curso_2', 'carga_curso_3', 'orden'], 'integer'],
            [['obligatoria'], 'boolean'],
            [['tipo_materia'], 'string', 'max' => 50],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenAsignaturas::className(), 'targetAttribute' => ['materia_id' => 'id']],
            [['malla_area_id'], 'exist', 'skipOnError' => true, 'targetClass' => GenMallaArea::className(), 'targetAttribute' => ['malla_area_id' => 'id']],
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
            'materia_id' => 'Materia ID',
            'tipo_materia' => 'Tipo Materia',
            'obligatoria' => 'Obligatoria',
            'carga_curso_1' => 'Carga Curso 1',
            'carga_curso_2' => 'Carga Curso 2',
            'carga_curso_3' => 'Carga Curso 3',
            'orden' => 'Orden',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurCurriculos()
    {
        return $this->hasMany(CurCurriculo::className(), ['materia_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(GenAsignaturas::className(), ['id' => 'materia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaArea()
    {
        return $this->hasOne(GenMallaArea::className(), ['id' => 'malla_area_id']);
    }
}
