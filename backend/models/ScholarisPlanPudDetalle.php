<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "scholaris_plan_pud_detalle".
 *
 * @property int $id
 * @property int $pud_id
 * @property string $tipo
 * @property string $codigo
 * @property string $contenido
 * @property string $pertenece_a_codigo
 * @property string $estado
 *
 * @property ScholarisPlanPud $pud
 */
class ScholarisPlanPudDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scholaris_plan_pud_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pud_id', 'tipo', 'contenido', 'estado'], 'required'],
            [['pud_id'], 'default', 'value' => null],
            [['pud_id','cantidad_periodos'], 'integer'],
            [['contenido'], 'string'],
            [['tipo', 'codigo', 'pertenece_a_codigo', 'estado'], 'string', 'max' => 30],
            [['pud_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPlanPud::className(), 'targetAttribute' => ['pud_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pud_id' => 'Pud ID',
            'tipo' => 'Tipo',
            'codigo' => 'Codigo',
            'contenido' => 'Contenido',
            'pertenece_a_codigo' => 'Pertenece A Codigo',
            'estado' => 'Estado',
            'cantidad_periodos' => 'Periodos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPud()
    {
        return $this->hasOne(ScholarisPlanPud::className(), ['id' => 'pud_id']);
    }
}
