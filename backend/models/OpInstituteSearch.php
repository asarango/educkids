<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OpInstitute;

/**
 * OpInstituteSearch represents the model behind the search form of `backend\models\OpInstitute`.
 */
class OpInstituteSearch extends OpInstitute
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'store_id', 'write_uid'], 'integer'],
            [['code', 'create_date', 'write_date', 'direccion', 'codigo_amie', 'email', 'telefono', 'rector', 'secretario', 'inspector_general', 'celular', 
                'inscription_state', 'enrollment_deposit_message', 'codigo_distrito', 'enrollment_payment_way_message_year', 
                'enrollment_payment_way_message_month', 'name','regimen'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OpInstitute::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'create_uid' => $this->create_uid,
            'create_date' => $this->create_date,
            'store_id' => $this->store_id,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
        ]);

        $query->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'direccion', $this->direccion])
            ->andFilterWhere(['ilike', 'codigo_amie', $this->codigo_amie])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'telefono', $this->telefono])
            ->andFilterWhere(['ilike', 'rector', $this->rector])
            ->andFilterWhere(['ilike', 'secretario', $this->secretario])
            ->andFilterWhere(['ilike', 'inspector_general', $this->inspector_general])
            ->andFilterWhere(['ilike', 'celular', $this->celular])
            //->andFilterWhere(['ilike', 'inscription_state', $this->inscription_state])
            //->andFilterWhere(['ilike', 'enrollment_deposit_message', $this->enrollment_deposit_message])
            ->andFilterWhere(['ilike', 'codigo_distrito', $this->codigo_distrito])
            ->andFilterWhere(['ilike', 'regimen', $this->regimen])
            //->andFilterWhere(['ilike', 'enrollment_payment_way_message_year', $this->enrollment_payment_way_message_year])
            //->andFilterWhere(['ilike', 'enrollment_payment_way_message_month', $this->enrollment_payment_way_message_month])
            ->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
