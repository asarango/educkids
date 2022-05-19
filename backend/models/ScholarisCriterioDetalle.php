<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_criterio_detalle".
 *
 * @property int $id
 * @property int $idcriterio
 * @property int $orden
 * @property string $descricpcion
 * @property int $curso_id
 *
 * @property ScholarisActividadDescriptor[] $scholarisActividadDescriptors
 */
class ScholarisCriterioDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_criterio_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcriterio', 'orden'], 'required'],
            [['idcriterio', 'orden', 'curso_id'], 'default', 'value' => null],
            [['idcriterio', 'orden', 'curso_id'], 'integer'],
            [['descricpcion'], 'string', 'max' => 500],
            [['codigo'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idcriterio' => 'Idcriterio',
            'orden' => 'Orden',
            'descricpcion' => 'Descricpcion',
            'curso_id' => 'Curso ID',
            'codigo' => 'Código'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisActividadDescriptors()
    {
        return $this->hasMany(ScholarisActividadDescriptor::className(), ['detalle_id' => 'id']);
    }
}
