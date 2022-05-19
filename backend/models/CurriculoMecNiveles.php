<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "curriculo_mec_niveles".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $is_active
 * @property string $comments
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property CurriculoMec[] $curriculoMecs
 */
class CurriculoMecNiveles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curriculo_mec_niveles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'created_at', 'created', 'updated_at', 'updated', 'plan_especial'], 'required'],
            [['is_active'], 'boolean'],
            [['comments'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 15],
            [['plan_especial'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50],
            [['created', 'updated'], 'string', 'max' => 200],
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
            'name' => 'Name',
            'is_active' => 'Is Active',
            'comments' => 'Comments',
            'plan_especial' => 'Planificacion Especial',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurriculoMecs()
    {
        return $this->hasMany(CurriculoMec::className(), ['subnivel_id' => 'id']);
    }
}
