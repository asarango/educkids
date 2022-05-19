<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "nee".
 *
 * @property int $id
 * @property int $student_id
 * @property string $created_at
 * @property string $created
 * @property string $updated_at
 * @property string $updated
 * @property int $scholaris_periodo_id
 *
 * @property OpStudent $student
 * @property ScholarisPeriodo $scholarisPeriodo
 * @property NeeXClase[] $neeXClases
 * @property NeeXOpcion[] $neeXOpcions
 */
class Nee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'created_at', 'created', 'scholaris_periodo_id'], 'required'],
            [['student_id', 'scholaris_periodo_id'], 'default', 'value' => null],
            [['student_id', 'scholaris_periodo_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created', 'updated'], 'string', 'max' => 200],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpStudent::className(), 'targetAttribute' => ['student_id' => 'id']],
            [['scholaris_periodo_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScholarisPeriodo::className(), 'targetAttribute' => ['scholaris_periodo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'created_at' => 'Created At',
            'created' => 'Created',
            'updated_at' => 'Updated At',
            'updated' => 'Updated',
            'scholaris_periodo_id' => 'Scholaris Periodo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(OpStudent::className(), ['id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScholarisPeriodo()
    {
        return $this->hasOne(ScholarisPeriodo::className(), ['id' => 'scholaris_periodo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNeeXClases()
    {
        return $this->hasMany(NeeXClase::className(), ['nee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNeeXOpcions()
    {
        return $this->hasMany(NeeXOpcion::className(), ['nee_id' => 'id']);
    }
}
