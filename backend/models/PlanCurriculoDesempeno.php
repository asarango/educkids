<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo_desempeno".
 *
 * @property int $id
 * @property int $destreza_id
 * @property string $codigo
 * @property string $nombre
 * @property string $tipo_destreza
 *
 * @property PlanCurriculoDestreza $destreza
 */
class PlanCurriculoDesempeno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo_desempeno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['destreza_id', 'codigo', 'nombre', 'tipo_destreza'], 'required'],
            [['destreza_id'], 'default', 'value' => null],
            [['destreza_id'], 'integer'],
            [['nombre'], 'string'],
            [['codigo'], 'string', 'max' => 15],
            [['tipo_destreza'], 'string', 'max' => 30],
            [['codigo'], 'unique'],
            [['destreza_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanCurriculoDestreza::className(), 'targetAttribute' => ['destreza_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'destreza_id' => 'Destreza ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'tipo_destreza' => 'Tipo Destreza',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestreza()
    {
        return $this->hasOne(PlanCurriculoDestreza::className(), ['id' => 'destreza_id']);
    }
}
