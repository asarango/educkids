<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_criterio_descriptor".
 *
 * @property int $id
 * @property int $criterio_id
 * @property int $orden
 * @property string $descricpcion
 * @property int $curso_template_id
 * @property string $codigo
 *
 * @property ScholarisCriterio $criterio
 */
class ScholarisCriterioDescriptor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_criterio_descriptor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['criterio_id', 'orden'], 'required'],
            [['criterio_id', 'orden', 'curso_template_id'], 'default', 'value' => null],
            [['criterio_id', 'orden', 'curso_template_id'], 'integer'],
            [['descricpcion'], 'string', 'max' => 500],
            [['descricpcion_idioma_alterno'], 'string'],
            [['codigo'], 'string', 'max' => 10],
            [['criterio_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisCriterio::className(), 'targetAttribute' => ['criterio_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'criterio_id' => 'Criterio ID',
            'orden' => 'Orden',
            'descricpcion' => 'Descricpcion',
            'descripcion_idioma_alterno' => 'Descripcion Idioma Alterno',
            'curso_template_id' => 'Curso Template ID',
            'codigo' => 'Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriterio()
    {
        return $this->hasOne(ScholarisCriterio::className(), ['id' => 'criterio_id']);
    }
}
