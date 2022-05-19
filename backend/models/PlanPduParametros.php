<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_pdu_parametros".
 *
 * @property int $id
 * @property string $tipo_parametro
 * @property string $nombre
 * @property bool $estado
 */
class PlanPduParametros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_pdu_parametros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_parametro', 'nombre', 'estado'], 'required'],
            [['nombre'], 'string'],
            [['estado'], 'boolean'],
            [['tipo_parametro'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_parametro' => 'Tipo Parametro',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }
}
