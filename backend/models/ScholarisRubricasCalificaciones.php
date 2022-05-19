<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_rubricas_calificaciones".
 *
 * @property int $id
 * @property string $quien_aplica
 * @property string $descripcion
 * @property string $valor
 * @property bool $estado
 *
 * @property ScholarisQuimestreCalificacion[] $scholarisQuimestreCalificacions
 */
class ScholarisRubricasCalificaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_rubricas_calificaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quien_aplica', 'descripcion', 'valor', 'estado'], 'required'],
            [['descripcion'], 'string'],
            [['valor'], 'number'],
            [['estado'], 'boolean'],
            [['quien_aplica'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quien_aplica' => 'Quien Aplica',
            'descripcion' => 'Descripcion',
            'valor' => 'Valor',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisQuimestreCalificacions()
    {
        return $this->hasMany(ScholarisQuimestreCalificacion::className(), ['rubrica_id' => 'id']);
    }
}
