<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "resource_resource".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property double $time_efficiency Efficiency Factor
 * @property string $code Code
 * @property int $user_id User
 * @property string $name Name
 * @property int $company_id Company
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 * @property int $calendar_id Working Time
 * @property bool $active Active
 * @property string $create_date Created on
 * @property string $resource_type Resource Type
 *
 * @property HrEmployee[] $hrEmployees
 * @property ResourceCalendarLeaves[] $resourceCalendarLeaves
 * @property ResCompany $company
 * @property ResUsers $createU
 * @property ResUsers $user
 * @property ResUsers $writeU
 * @property ResourceCalendar $calendar
 */
class ResourceResource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resource_resource';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'user_id', 'company_id', 'write_uid', 'calendar_id'], 'default', 'value' => null],
            [['create_uid', 'user_id', 'company_id', 'write_uid', 'calendar_id'], 'integer'],
            [['time_efficiency', 'name', 'resource_type'], 'required'],
            [['time_efficiency'], 'number'],
            [['name', 'resource_type'], 'string'],
            [['write_date', 'create_date'], 'safe'],
            [['active'], 'boolean'],
            [['code'], 'string', 'max' => 16],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['create_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['create_uid' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['write_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ResUsers::className(), 'targetAttribute' => ['write_uid' => 'id']],
            [['calendar_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResourceCalendar::className(), 'targetAttribute' => ['calendar_id' => 'id']],
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
            'time_efficiency' => 'Time Efficiency',
            'code' => 'Code',
            'user_id' => 'User ID',
            'name' => 'Name',
            'company_id' => 'Company ID',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
            'calendar_id' => 'Calendar ID',
            'active' => 'Active',
            'create_date' => 'Create Date',
            'resource_type' => 'Resource Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResourceCalendarLeaves()
    {
        return $this->hasMany(ResourceCalendarLeaves::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ResCompany::className(), ['id' => 'company_id']);
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
    public function getUser()
    {
        return $this->hasOne(ResUsers::className(), ['id' => 'user_id']);
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
    public function getCalendar()
    {
        return $this->hasOne(ResourceCalendar::className(), ['id' => 'calendar_id']);
    }
}
