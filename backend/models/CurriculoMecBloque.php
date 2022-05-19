<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "curriculo_mec_bloque".
 *
 * @property int $id
 * @property int $code
 * @property string $last_name
 * @property string $shot_name
 * @property bool $is_active
 *
 * @property PlanificacionBloquesUnidad[] $planificacionBloquesUnidads
 */
class CurriculoMecBloque extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curriculo_mec_bloque';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'last_name', 'shot_name'], 'required'],
            [['code'], 'default', 'value' => null],
            [['code'], 'integer'],
            [['is_active'], 'boolean'],
            [['last_name'], 'string', 'max' => 40],
            [['shot_name'], 'string', 'max' => 5],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'last_name' => 'Last Name',
            'shot_name' => 'Shot Name',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionBloquesUnidads()
    {
        return $this->hasMany(PlanificacionBloquesUnidad::className(), ['curriculo_bloque_id' => 'id']);
    }
}
