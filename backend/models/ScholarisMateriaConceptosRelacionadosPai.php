<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_materia_conceptos_relacionados_pai".
 *
 * @property int $id
 * @property int $materia_id
 * @property string $contenido_es
 * @property string $contenido_en
 * @property string $contenido_fr
 * @property bool $estado
 *
 * @property ScholarisMateria $materia
 */
class ScholarisMateriaConceptosRelacionadosPai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_materia_conceptos_relacionados_pai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['materia_id', 'contenido_es', 'contenido_en', 'contenido_fr'], 'required'],
            [['materia_id'], 'default', 'value' => null],
            [['materia_id'], 'integer'],
            [['estado'], 'boolean'],
            [['contenido_es', 'contenido_en', 'contenido_fr'], 'string', 'max' => 50],
            [['materia_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisMateria::className(), 'targetAttribute' => ['materia_id' => 'id']],
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
            'contenido_es' => 'Contenido Es',
            'contenido_en' => 'Contenido En',
            'contenido_fr' => 'Contenido Fr',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMateria()
    {
        return $this->hasOne(ScholarisMateria::className(), ['id' => 'materia_id']);
    }
}
