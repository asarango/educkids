<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_curso_imprime_libreta".
 *
 * @property int $id
 * @property int $curso_id
 * @property string $imprime
 * @property int $rinde_supletorio
 *
 * @property OpCourse $curso
 */
class ScholarisCursoImprimeLibreta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_curso_imprime_libreta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_id', 'imprime', 'rinde_supletorio'], 'required'],
            [['curso_id', 'rinde_supletorio'], 'default', 'value' => null],
            [['curso_id', 'rinde_supletorio'], 'integer'],
            [['esta_bloqueado'], 'boolean'],
            [['imprime', 'tipo_proyectos'], 'string', 'max' => 30],
            [['curso_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpCourse::className(), 'targetAttribute' => ['curso_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'curso_id' => 'Curso ID',
            'imprime' => 'Imprime',
            'rinde_supletorio' => 'Rinde Supletorio',
            'tipo_proyectos' => 'Tipo de Proyectos',
            'esta_bloqueado' => 'Está Bloqueado'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurso()
    {
        return $this->hasOne(OpCourse::className(), ['id' => 'curso_id']);
    }
}
