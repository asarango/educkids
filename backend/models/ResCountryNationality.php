<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "res_country_nationality".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $create_date Created on
 * @property string $name Nombre
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property string $codigo Código
 *
 * @property HrEmployee[] $hrEmployees
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property ResPartner[] $resPartners
 */
class ResCountryNationality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'res_country_nationality';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'write_uid'], 'default', 'value' => null],
            [['create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'required'],
            [['name', 'codigo'], 'string'],
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
            'create_date' => 'Create Date',
            'name' => 'Name',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'codigo' => 'Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['x_nationality_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResPartners()
    {
        return $this->hasMany(ResPartner::className(), ['x_nacionalidad_id' => 'id']);
    }
}
