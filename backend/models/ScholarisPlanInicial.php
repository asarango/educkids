<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_inicial".
 *
 * @property int $id
 * @property int $clase_id
 * @property string $quimestre_codigo
 * @property string $codigo_destreza
 * @property string $destreza_original
 * @property string $destreza_desagregada
 * @property string $estado
 *
 * @property ScholarisClase $clase
 */
class ScholarisPlanInicial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_inicial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clase_id', 'quimestre_codigo', 'codigo_destreza', 'destreza_original', 'destreza_desagregada', 'estado'], 'required'],
            [['clase_id'], 'default', 'value' => null],
            [['clase_id','orden'], 'integer'],
            [['destreza_original', 'destreza_desagregada'], 'string'],
            [['quimestre_codigo'], 'string', 'max' => 15],
            [['codigo_destreza', 'estado', 'codigo_ambito'], 'string', 'max' => 30],
            [['clase_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisClase::className(), 'targetAttribute' => ['clase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clase_id' => 'Clase ID',
            'quimestre_codigo' => 'Quimestre Codigo',
            'codigo_destreza' => 'Codigo Destreza',
            'destreza_original' => 'Destreza Original',
            'destreza_desagregada' => 'Destreza Desagregada',
            'estado' => 'Estado',
            'codigo_ambito' => 'Codigo Ambito'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClase()
    {
        return $this->hasOne(ScholarisClase::className(), ['id' => 'clase_id']);
    }
}
