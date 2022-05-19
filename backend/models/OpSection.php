<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "op_section".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $code code
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $write_uid Last Updated by
 * @property int $period_id Período
 * @property string $write_date Last Updated on
 * @property string $grupo_seccion
 * @property string $abreviatura
 *
 * @property OpCourse[] $opCourses
 * @property OpPeriod $period
 * @property ResUsers $createU
 * @property ResUsers $writeU
 */
class OpSection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'op_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid', 'period_id'], 'default', 'value' => null],
            [['create_uid', 'write_uid', 'period_id'], 'integer'],
            [['code', 'name', 'subnivel_mec_code'], 'string'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'required'],
            [['grupo_seccion'], 'string', 'max' => 50],
            [['abreviatura'], 'string', 'max' => 20],
            [['period_id'], 'exist', 'skipOnError' => true, 'targetClass' => OpPeriod::className(), 'targetAttribute' => ['period_id' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_uid' => 'Create Uid',
            'code' => 'Code',
            'create_date' => 'Create Date',
            'name' => 'Name',
            'write_uid' => 'Write Uid',
            'period_id' => 'Period ID',
            'write_date' => 'Write Date',
            'grupo_seccion' => 'Grupo Seccion',
            'abreviatura' => 'Abreviatura',
            'subnivel_mec_code' => 'Código Sub_nivel',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpCourses()
    {
        return $this->hasMany(OpCourse::className(), ['section' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriod()
    {
        return $this->hasOne(OpPeriod::className(), ['id' => 'period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'create_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriteU()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'write_uid']);
    }
}
