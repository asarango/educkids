<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "curriculo_mec_asignatutas".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $color
 * @property bool $is_active
 * @property bool $is_mec
 * @property string $comments
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 *
 * @property CurriculoMec[] $curriculoMecs
 */
class CurriculoMecAsignatutas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curriculo_mec_asignatutas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'is_mec', 'created_at', 'created', 'updated_at', 'updated'], 'required'],
            [['is_active', 'is_mec'], 'boolean'],
            [['comments'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'color'], 'string', 'max' => 20],
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
            'color' => 'Color',
            'is_active' => 'Is Active',
            'is_mec' => 'Is Mec',
            'comments' => 'Comments',
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
        return $this->hasMany(CurriculoMec::className(), ['asignatura_id' => 'id']);
    }
}
