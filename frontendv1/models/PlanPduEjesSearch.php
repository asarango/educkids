<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\PlanPduEjes;

/**
 * PlanPduEjesSearch represents the model behind the search form of `frontend\models\PlanPduEjes`.
 */
class PlanPduEjesSearch extends PlanPduEjes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cabecera_id', 'parametro_id'], 'integer'],
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
    public function search($params, $id)
    {
        $query = PlanPduEjes::find()
                ->where(['cabecera_id' => $id]);

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
            'cabecera_id' => $this->cabecera_id,
            'parametro_id' => $this->parametro_id,
        ]);

        return $dataProvider;
    }
}
