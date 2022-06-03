<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cur_curriculo_kids_criterio_evaluacion".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property bool $estado
 */
class CurCurriculoKidsCriterioEvaluacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cur_curriculo_kids_criterio_evaluacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre'], 'required'],
            [['nombre'], 'string'],
            [['estado'], 'boolean'],
            [['codigo'], 'string', 'max' => 15],
            [['codigo'], 'unique'],
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
            'estado' => 'Estado',
        ];
    }
}
