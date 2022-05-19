<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "plan_curriculo".
 *
 * @property int $id
 * @property int $ano_incia
 * @property int $ano_finaliza
 * @property bool $estado
 */
class PlanCurriculo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_curriculo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ano_incia', 'ano_finaliza', 'estado'], 'required'],
            [['ano_incia', 'ano_finaliza'], 'default', 'value' => null],
            [['ano_incia', 'ano_finaliza'], 'integer'],
            [['estado'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ano_incia' => 'Ano Incia',
            'ano_finaliza' => 'Ano Finaliza',
            'estado' => 'Estado',
        ];
    }
}
