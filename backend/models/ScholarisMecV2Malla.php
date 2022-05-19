<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_mec_v2_malla".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $periodo_id
 *
 * @property ScholarisPeriodo $periodo
 * @property ScholarisMecV2MallaArea[] $scholarisMecV2MallaAreas
 * @property ScholarisMecV2MallaCurso[] $scholarisMecV2MallaCursos
 * @property OpCourse[] $cursos
 */
class ScholarisMecV2Malla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_mec_v2_malla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre', 'periodo_id'], 'required'],
            [['periodo_id'], 'default', 'value' => null],
            [['periodo_id'], 'integer'],
            [['codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 100],
            [['codigo'], 'unique'],
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
            'nombre' => 'Nombre',
            'periodo_id' => 'Periodo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'periodo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2MallaAreas()
    {
        return $this->hasMany(ScholarisMecV2MallaArea::className(), ['malla_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisMecV2MallaCursos()
    {
        return $this->hasMany(ScholarisMecV2MallaCurso::className(), ['malla_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursos()
    {
        return $this->hasMany(OpCourse::className(), ['id' => 'curso_id'])->viaTable('scholaris_mec_v2_malla_curso', ['malla_id' => 'id']);
    }
}
