<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "res_country_state".
 *
 * @property int $id
 * @property int $create_uid Created by
 * @property string $code State Code
 * @property string $create_date Created on
 * @property string $name State Name
 * @property int $country_id Country
 * @property int $write_uid Last Updated by
 * @property string $write_date Last Updated on
 *
 * @property AccountFiscalPositionResCountryStateRel[] $accountFiscalPositionResCountryStateRels
 * @property AccountFiscalPosition[] $accountFiscalPositions
 * @property AccountFiscalPositionTemplateResCountryStateRel[] $accountFiscalPositionTemplateResCountryStateRels
 * @property AccountFiscalPositionTemplate[] $accountFiscalPositionTemplates
 * @property HrEmployee[] $hrEmployees
 * @property ResBank[] $resBanks
 * @property ResCountryCity[] $resCountryCities
 * @property ResCountryParish[] $resCountryParishes
 * @property ResCountry $country
 * @property ResUsers $createU
 * @property ResUsers $writeU
 * @property ResPartner[] $resPartners
 */
class ResCountryState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'res_country_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_uid', 'country_id', 'write_uid'], 'default', 'value' => null],
            [['create_uid', 'country_id', 'write_uid'], 'integer'],
            [['code', 'name', 'country_id'], 'required'],
            [['code', 'name'], 'string'],
            [['create_date', 'write_date'], 'safe'],
            [['country_id', 'code'], 'unique', 'targetAttribute' => ['country_id', 'code']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'country_id' => 'Country ID',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFiscalPositionResCountryStateRels()
    {
        return $this->hasMany(AccountFiscalPositionResCountryStateRel::className(), ['res_country_state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFiscalPositions()
    {
        return $this->hasMany(AccountFiscalPosition::className(), ['id' => 'account_fiscal_position_id'])->viaTable('account_fiscal_position_res_country_state_rel', ['res_country_state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFiscalPositionTemplateResCountryStateRels()
    {
        return $this->hasMany(AccountFiscalPositionTemplateResCountryStateRel::className(), ['res_country_state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountFiscalPositionTemplates()
    {
        return $this->hasMany(AccountFiscalPositionTemplate::className(), ['id' => 'account_fiscal_position_template_id'])->viaTable('account_fiscal_position_template_res_country_state_rel', ['res_country_state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrEmployees()
    {
        return $this->hasMany(HrEmployee::className(), ['x_state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResBanks()
    {
        return $this->hasMany(ResBank::className(), ['state' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResCountryCities()
    {
        return $this->hasMany(ResCountryCity::className(), ['x_state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResCountryParishes()
    {
        return $this->hasMany(ResCountryParish::className(), ['state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(ResCountry::className(), ['id' => 'country_id']);
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
        return $this->hasMany(ResPartner::className(), ['state_id' => 'id']);
    }
}
